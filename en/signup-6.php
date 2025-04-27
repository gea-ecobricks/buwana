<?php
require_once '../earthenAuth_helper.php'; // Include the authentication helper functions

// Set up page variables
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$version = '0.44';
$page = 'activate';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));

$is_logged_in = false; // Ensure not logged in for this page

// Check if the user is logged in
if (isLoggedIn()) {
    echo "<script>
        alert('Looks like you already have an account and are logged in! Let\'s take you to your dashboard.');
        window.location.href = 'dashboard.php';
    </script>";
    exit();
}

// Initialize variables
$buwana_id = $_GET['id'] ?? null;
$page = 'activate';
$first_name = '';
$pre_community = '';

// PART 1: Check if the user is already logged in
if (isset($_SESSION['buwana_id'])) {
    header("Location: dashboard.php");
    exit();
}

// PART 2: Check if buwana_id is passed in the URL
if (is_null($buwana_id)) {
    echo '<script>
        alert("Hmm... something went wrong. No buwana ID was passed along. Please try logging in again. If this problem persists, you\'ll need to create a new account.");
        window.location.href = "login.php";
    </script>';
    exit();
}

// PART 3: Look up user information using buwana_id provided in URL
require_once("../buwanaconn_env.php");

$sql_user_info = "SELECT first_name FROM users_tb WHERE buwana_id = ?";
$stmt_user_info = $buwana_conn->prepare($sql_user_info);

if ($stmt_user_info) {
    $stmt_user_info->bind_param('i', $buwana_id);
    $stmt_user_info->execute();
    $stmt_user_info->bind_result($first_name);
    $stmt_user_info->fetch();
    $stmt_user_info->close();
} else {
    die('Error preparing statement for fetching user info: ' . $buwana_conn->error);
}

if (empty($first_name)) {
    $first_name = 'User';
}

// PART 4: Fetch Ecobricker's community from GoBrik database
require_once("../gobrikconn_env.php");

$sql_ecobricker_community = "SELECT community FROM tb_ecobrickers WHERE buwana_id = ?";
$stmt_ecobricker_community = $gobrik_conn->prepare($sql_ecobricker_community);

if ($stmt_ecobricker_community) {
    $stmt_ecobricker_community->bind_param('i', $buwana_id);
    $stmt_ecobricker_community->execute();
    $stmt_ecobricker_community->bind_result($pre_community);
    $stmt_ecobricker_community->fetch();
    $stmt_ecobricker_community->close();
} else {
    die('Error preparing statement for fetching ecobricker community: ' . $gobrik_conn->error);
}

// PART 5: Fetch all communities from the communities_tb table in Buwana database
$communities = [];
$sql_communities = "SELECT com_name FROM communities_tb";
$result_communities = $buwana_conn->query($sql_communities);

if ($result_communities && $result_communities->num_rows > 0) {
    while ($row = $result_communities->fetch_assoc()) {
        $communities[] = $row['com_name'];
    }
}

// Fetch all countries
$countries = [];
$sql_countries = "SELECT country_id, country_name FROM countries_tb ORDER BY country_name ASC";
$result_countries = $buwana_conn->query($sql_countries);

if ($result_countries && $result_countries->num_rows > 0) {
    while ($row = $result_countries->fetch_assoc()) {
        $countries[] = $row;
    }
}

// Fetch user's country_id from users_tb
$user_country_id = null;

$sql_country_lookup = "SELECT country_id FROM users_tb WHERE buwana_id = ?";
$stmt_country_lookup = $buwana_conn->prepare($sql_country_lookup);
if ($stmt_country_lookup) {
    $stmt_country_lookup->bind_param('i', $buwana_id);
    $stmt_country_lookup->execute();
    $stmt_country_lookup->bind_result($user_country_id);
    $stmt_country_lookup->fetch();
    $stmt_country_lookup->close();
}



// Fetch all languages
$languages = [];
$sql_languages = "SELECT language_id, languages_native_name FROM languages_tb ORDER BY languages_native_name ASC";
$result_languages = $buwana_conn->query($sql_languages);

if ($result_languages && $result_languages->num_rows > 0) {
    while ($row = $result_languages->fetch_assoc()) {
        $languages[] = $row;
    }
}

// PART 6: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_community_name = $_POST['community_name'];
    $selected_country_name = $_POST['country_name'];
    $selected_language_id = $_POST['language_id'];
    $earthling_emoji = $_POST['earthling_emoji'] ?? '🌍'; // Fallback default emoji

    // Fetch country_id and continent_code from selected country
    $sql_country = "SELECT country_id, continent_code FROM countries_tb WHERE country_name = ?";
    $stmt_country = $buwana_conn->prepare($sql_country);

    if ($stmt_country) {
        $stmt_country->bind_param('s', $selected_country_name);
        $stmt_country->execute();
        $stmt_country->bind_result($set_country_id, $set_continent_code);
        $stmt_country->fetch();
        $stmt_country->close();
    } else {
        die('Error preparing statement for fetching country info: ' . $buwana_conn->error);
    }

    $set_country_id = !empty($set_country_id) ? $set_country_id : null;
    $set_continent_code = !empty($set_continent_code) ? $set_continent_code : null;

    // Update Buwana user with country, continent, language, community, and emoji
    $sql_update_buwana = "UPDATE users_tb SET continent_code = ?, country_id = ?, community_id = (SELECT community_id FROM communities_tb WHERE com_name = ?), language_id = ?, earthling_emoji = ? WHERE buwana_id = ?";
    $stmt_update_buwana = $buwana_conn->prepare($sql_update_buwana);

    if ($stmt_update_buwana) {
        $stmt_update_buwana->bind_param('sisssi', $set_continent_code, $set_country_id, $selected_community_name, $selected_language_id, $earthling_emoji, $buwana_id);
        $stmt_update_buwana->execute();
        $stmt_update_buwana->close();

        // Update GoBrik record
        require_once("../gobrikconn_env.php");

        $sql_update_gobrik = "UPDATE tb_ecobrickers
            SET community_id = (SELECT community_id FROM communities_tb WHERE com_name = ?),
                country_id = ?,
                language_id = ?,
                account_notes = CONCAT(account_notes, ' Finalized community and language.')
            WHERE buwana_id = ?";

        $stmt_update_gobrik = $gobrik_conn->prepare($sql_update_gobrik);

        if ($stmt_update_gobrik) {
            $stmt_update_gobrik->bind_param('siii', $selected_community_name, $set_country_id, $selected_language_id, $buwana_id);
            $stmt_update_gobrik->execute();
            $stmt_update_gobrik->close();
        } else {
            error_log('Error preparing GoBrik update: ' . $gobrik_conn->error);
            echo "Failed to update GoBrik record.";
        }

        $gobrik_conn->close();

        // Redirect to dashboard or next step
        header("Location: dashboard.php");
        exit();
    } else {
        error_log('Error preparing Buwana update: ' . $buwana_conn->error);
        echo "Failed to update Buwana record.";
    }
}
?>






<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8">

<!--
GoBrik.com site version 3.0
Developed and made open source by the Global Ecobrick Alliance
See our git hub repository for the full code and to help out:
https://github.com/gea-ecobricks/gobrik-3.0/tree/main/en-->

<?php require_once ("../includes/signup-inc.php");?>

<div class="splash-title-block"></div>
<div id="splash-bar"></div>

<!-- PAGE CONTENT -->
<div id="top-page-image" class="message-birded top-page-image"></div>

<div id="form-submission-box" class="landing-page-form">
    <div class="form-container">
        <div style="text-align:center;width:100%;margin:auto;">
            <p style="color:green;">✔ <?php echo htmlspecialchars($first_name); ?>, <span data-lang-id="001-subs-set"> your Earthen subscriptions are confirmed!</p>
            <div id="status-message"><h4 data-lang-id="012-status-heading2" style="margin-bottom: 12px;"> Now the fun part!</h4></div>
            <p data-lang-id="013-sub-ecozone" style="font-size:1.4em;padding-bottom:10px;">To finalize your account, please choose your fellow Earthling emoji to best represent who you are.  This is your Buwana totem that will accompany your username when you're logged in.</p>
        </div>

        <!-- FINALIZE ACCOUNT FORM -->

<form id="user-info-form" method="post" action="finalize_process.php?id=<?php echo htmlspecialchars($buwana_id); ?>">




<!-- EARTHLING EMOJI SELECT -->
<div class="form-item" id="emoji-section" style="margin-top: 20px;">
    <!--<label for="earthling_emoji">🧬 Choose an emoji to represent you:</label>-->
    <div id="emoji-grid" style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px;">
        <?php
        // A curated list of lifeform-related emojis
$emoji_options = [
    // Mammals
    '🐶','🐺','🦊','🐱','🐯','🦁','🐮','🐷','🐸','🐵','🦍','🦧','🐔','🐧','🦇','🐻','🐨','🐼','🦘','🦡','🦨','🦥','🦦','🦣','🦌','🦬','🐐','🐑','🐎','🫏','🐪','🐫','🦙','🦒','🦓','🐘','🐖','🐄','🐂',

    // Marine
    '🐬','🐳','🐋','🐟','🐠','🐡','🦈','🐙','🦑','🦐','🦀','🪼',

    // Reptiles & Amphibians
    '🐊','🦎','🐍','🐢','🦕','🦖',

    // Birds
    '🐦','🐧','🕊️','🦅','🦆','🦢','🦉','🦜','🪶',

    // Insects
    '🐝','🐞','🦋','🐛','🦗','🪲','🪳','🦟','🪰','🪱',

    // Plants
    '🌱','🌿','☘️','🍀','🎋','🌵','🌴','🌲','🌳','🪴','🪹','🪺',

    // Human-like characters (no faces)
    '🧑','🧒','🧓','👩','👨','👧','👦',
    '🧕','🧔','👮','🕵️','💂','🧙','🧝','🧛','🧟','🧞','🧜','🧚','🧑‍🚀','🧑‍🔬','🧑‍🌾','🧑‍🏫','🧑‍🎨','🧑‍🚒','🧑‍🍳','🧑‍⚖️','🧑‍💻','🧑‍🔧','🧑‍🔬','🧑‍🏭'
];
        foreach ($emoji_options as $emoji): ?>
            <div class="emoji-option" onclick="selectEmoji(this)" style="cursor:pointer; font-size: 28px; padding: 6px 10px; border: 2px solid transparent; border-radius: 8px;">
                <?php echo $emoji; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <input type="hidden" name="earthling_emoji" id="earthling_emoji">
    <p style="margin-top: 5px; font-size: 0.9em; color: #555;">Click one emoji to represent your Earthling identity.</p>
</div>



  <!-- COMMUNITY FIELD -->
<div class="form-item" id="community-section" style="margin-top:20px;">
    <label for="community_name" data-lang-id="012-community-name-x">Buwana accounts are all about connecting us with our local and global communities.  Select your primary local community:</label><br>
    <input type="text" id="community_name" name="community_name" aria-label="Community Name" list="community_list"
           placeholder="Type your community" style="width: 100%; padding: 10px;"
           value="<?php echo htmlspecialchars($pre_community ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <datalist id="community_list">
        <?php foreach ($communities as $community) : ?>
            <option value="<?php echo htmlspecialchars($community, ENT_QUOTES, 'UTF-8'); ?>" <?php echo (isset($pre_community) && $community === $pre_community) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($community, ENT_QUOTES, 'UTF-8'); ?>
            </option>
        <?php endforeach; ?>
    </datalist>

    <!-- "Add a new community" text link -->
    <p class="form-caption" data-lang-id="012-community-caption-xx">
        Start typing to see and select a community. There's a good chance someone local to you has already set one up!  <a href="#" onclick="openAddCommunityModal(); return false;" style="color: #007BFF; text-decoration: underline;">Don't see your community?  + Add it.
        </a>
    </p>
</div>


<!-- COUNTRY SELECT -->
<div class="form-item" id="country-section" style="margin-top: 20px;">
    <label for="country_name">🌍 Please make sure we've connected you with the right country:</label><br>
    <select id="country_name" name="country_name" required style="width: 100%; padding: 10px;">
        <option value="">-- Select your country --</option>
        <?php foreach ($countries as $country): ?>
            <option value="<?php echo htmlspecialchars($country['country_id']); ?>"
                <?php echo ($country['country_id'] == $user_country_id) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($country['country_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>




<?php
// Get current language directory from URL (e.g., 'en', 'fr', etc.)
$current_lang_dir = basename(dirname($_SERVER['SCRIPT_NAME']));
?>

<!-- LANGUAGE SELECT -->
<div class="form-item" id="language-section" style="margin-top: 20px;">
    <label for="language_id">🗣️ Please make sure we've selected the right primary language for you:</label><br>
    <select id="language_id" name="language_id" required style="width: 100%; padding: 10px;">
        <option value="">-- Select your language --</option>
        <?php foreach ($languages as $language): ?>
            <option value="<?php echo htmlspecialchars($language['language_id']); ?>"
                <?php echo ($language['language_id'] === $current_lang_dir) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($language['languages_native_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>



    <!-- SUBMIT SECTION -->
    <div id="submit-section" style="text-align: center; margin-top: 25px; " data-lang-id="016-all-done-button">
        <input type="submit" id="submit-button" value="👌 All done!" class="submit-button enabled">

    </div>
<p class="form-caption" data-lang-id="022" style="text-align: center;margin-top: 20px;">Now you're ready to login! 🐵</p>

</form>





    </div>
</div>
</div>
<!-- FOOTER STARTS HERE -->
<?php require_once ("../footer-2024.php"); ?>


<!-- place at the bottom of your HTML page -->
<script>



const userLanguageId = "<?php echo $current_lang_dir; ?>"; // from URL directory
const userCountryId = "<?php echo htmlspecialchars($user_country_id ?? '', ENT_QUOTES, 'UTF-8'); ?>"; // from DB



function openAddCommunityModal() {
console.log("🌍 userCountryId:", userCountryId);
    const modal = document.getElementById('form-modal-message');
    const modalBox = document.getElementById('modal-content-box');

    modal.style.display = 'flex';
    modalBox.style.flexFlow = 'column';
    document.getElementById('page-content')?.classList.add('blurred');
    document.getElementById('footer-full')?.classList.add('blurred');
    document.body.classList.add('modal-open');

    modalBox.style.maxHeight = '80vh';
    modalBox.style.overflowY = 'auto';

    modalBox.innerHTML = `
        <h4 style="text-align:center;">Add Your Community</h4>
        <p>Add your community to GoBrik so you can manage local projects and ecobricks.</p>

        <form id="addCommunityForm" onsubmit="addCommunity2Buwana(event)">
            <label for="newCommunityName">Name of Community:</label>
            <input type="text" id="newCommunityName" name="newCommunityName" required>

            <label for="newCommunityType">Type of Community:</label>
            <select id="newCommunityType" name="newCommunityType" required>
                <option value="">Select Type</option>
                <option value="neighborhood">Neighborhood</option>
                <option value="city">City</option>
                <option value="school">School</option>
                <option value="organization">Organization</option>
            </select>

            <label for="communityCountry">Country:</label>
            <select id="communityCountry" name="communityCountry" required>
                <option value="">Select Country</option>
                <?php foreach ($countries as $country) : ?>
                    <option value="<?php echo $country['country_id']; ?>">
                        <?php echo htmlspecialchars($country['country_name'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="communityLanguage">Preferred Language:</label>
            <select id="communityLanguage" name="communityLanguage" required>
                <option value="">Select Language</option>
                <?php foreach ($languages as $language) : ?>
                    <option value="<?php echo $language['language_id']; ?>">
                        <?php echo htmlspecialchars($language['languages_native_name'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" style="margin-top:10px;" class="confirm-button enabled">Submit</button>
        </form>
    `;

    // Preselect country and language after form is injected
    setTimeout(() => {
        const countrySelect = document.getElementById('communityCountry');
        const languageSelect = document.getElementById('communityLanguage');

        if (countrySelect && userCountryId) {
            countrySelect.value = userCountryId;
        }

        if (languageSelect && userLanguageId) {
            languageSelect.value = userLanguageId;
        }
    }, 100); // Small delay ensures elements exist in the DOM
}




function addCommunity2Buwana(event) {
    event.preventDefault(); // Prevent normal form submission

    const form = document.getElementById('addCommunityForm');
    const formData = new FormData(form);

    fetch('../scripts/add_community.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message); // Show success or error message

        if (data.success) {
            // Close modal
            closeInfoModal();

            // Add the new community to the dropdown
            const communityInput = document.getElementById('community_name');
            const communityList = document.getElementById('community_list');

            // Create new option
            const newOption = document.createElement('option');
            newOption.value = data.community_name;
            newOption.textContent = data.community_name;
            communityList.appendChild(newOption);

            // Set selected value
            communityInput.value = data.community_name;
        }
    })
    .catch(error => {
        alert('Error adding community. Please try again.');
        console.error('Error:', error);
    });
}





function selectEmoji(element) {
    // Remove highlight from all
    const all = document.querySelectorAll('.emoji-option');
    all.forEach(el => el.style.border = '2px solid transparent');

    // Highlight the selected one
    element.style.border = '2px solid #28a745';

    // Set the hidden input value
    document.getElementById('earthling_emoji').value = element.innerText;
}


</script>


</body>
</html>




