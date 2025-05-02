<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

// Page setup
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'signup-6';
$version = '0.777';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));

// Already logged in?
if (!empty($_SESSION['buwana_id'])) {
    $redirect_url = $_SESSION['redirect_url'] ?? $app_info['app_url'] ?? '/';
    echo "<script>
        alert('Looks like you’re already logged in! Redirecting to your dashboard...');
        window.location.href = '$redirect_url';
    </script>";
    exit();
}

// 🧩 Validate buwana_id
$buwana_id = $_GET['id'] ?? null;
if (!$buwana_id || !is_numeric($buwana_id)) {
    die("⚠️ Invalid or missing Buwana ID.");
}

// 🧠 Fetch basic user info
$first_name = 'User';
$stmt = $buwana_conn->prepare("SELECT first_name FROM users_tb WHERE buwana_id = ?");
if ($stmt) {
    $stmt->bind_param('i', $buwana_id);
    $stmt->execute();
    $stmt->bind_result($first_name);
    $stmt->fetch();
    $stmt->close();
}

// 📋 Fetch countries
$countries = [];
$result_countries = $buwana_conn->query("SELECT country_id, country_name FROM countries_tb ORDER BY country_name ASC");
while ($row = $result_countries->fetch_assoc()) {
    $countries[] = $row;
}

// 📋 Fetch languages
$languages = [];
$result_languages = $buwana_conn->query("SELECT language_id, languages_native_name FROM languages_tb ORDER BY languages_native_name ASC");
while ($row = $result_languages->fetch_assoc()) {
    $languages[] = $row;
}

// 📋 Fetch communities
$communities = [];
$result_communities = $buwana_conn->query("SELECT com_name FROM communities_tb");
while ($row = $result_communities->fetch_assoc()) {
    $communities[] = $row['com_name'];
}

// 📋 Fetch user's current country id
$user_country_id = null;
$stmt = $buwana_conn->prepare("SELECT country_id FROM users_tb WHERE buwana_id = ?");
if ($stmt) {
    $stmt->bind_param('i', $buwana_id);
    $stmt->execute();
    $stmt->bind_result($user_country_id);
    $stmt->fetch();
    $stmt->close();
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
<div id="top-page-image" class="marine-animals-top top-page-image"></div>

<div id="form-submission-box" class="landing-page-form">
    <div class="form-container">
        <div style="text-align:center;width:100%;margin:auto;">
            <p style="color:green;" data-lang-id="001-subs-set">✔ Your Earthen subscriptions are confirmed!</p>
            <div id="status-message"><h4 data-lang-id="002-fun-part" style="margin-bottom: 12px;margin-top:0px;">Now the fun part!</h4></div>
            <p style="font-size:1.4em;padding-bottom:10px;"><?php echo htmlspecialchars($first_name); ?>, <span data-lang-id="003-finalize">to finalize your account, choose an Earthling emoji to best represent yourself.</span></p>
        </div>

        <!-- FINALIZE ACCOUNT FORM -->

<form id="user-signup-form" method="post" action="signup-6_process.php?id=<?php echo htmlspecialchars($buwana_id); ?>">

<!-- EARTHLING EMOJI SELECT -->
<div class="form-item" id="emoji-section">
    <!-- Top tab bar -->
    <ul class="emoji-tabs" id="emojiTabs">
        <li data-tab="mammals"  class="active" data-lang-id="004-mammals">Mammals</li>
        <li data-tab="marine" data-lang-id="004-marine">Marine</li>
        <li data-tab="reptiles" data-lang-id="004-reptiles-amphibians">Reptiles & Amphibians</li>
        <li data-tab="birds" data-lang-id="004-birds">Birds</li>
        <li data-tab="insects" data-lang-id="004-insects">Insects</li>
        <li data-tab="plants" data-lang-id="004-plants">Plants</li>
        <li data-tab="humans" data-lang-id="004-humman-like">Human-like</li>
    </ul>

    <!-- ONE grid per category -->
    <div class="emoji-grids">

        <div id="tab-mammals"  class="emoji-grid active">
            <?php foreach ([
                '🐶','🐺','🦊','🐱','🐯','🦁','🐮','🐷','🐸','🐵','🦍','🦧','🐔',
                '🐧','🦇','🐻','🐨','🐼','🦘','🦡','🦨','🦥','🦦','🦣','🦌','🦬',
                '🐐','🐑','🐎','🫏','🐪','🐫','🦙','🦒','🦓','🐘','🐖','🐄','🐂'
            ] as $emoji): ?>
                <div class="emoji-option" onclick="selectEmoji(this)"><?php echo $emoji;?></div>
            <?php endforeach; ?>
        </div>

        <div id="tab-marine" class="emoji-grid">
            <?php foreach (['🐬','🐳','🐋','🐟','🐠','🐡','🦈','🐙','🦑','🦐','🦀','🪼'] as $emoji): ?>
                <div class="emoji-option" onclick="selectEmoji(this)"><?php echo $emoji;?></div>
            <?php endforeach; ?>
        </div>

        <div id="tab-reptiles" class="emoji-grid">
            <?php foreach (['🐊','🦎','🐍','🐢','🦕','🦖'] as $emoji): ?>
                <div class="emoji-option" onclick="selectEmoji(this)"><?php echo $emoji;?></div>
            <?php endforeach; ?>
        </div>

        <div id="tab-birds" class="emoji-grid">
            <?php foreach (['🐦','🐧','🕊️','🦅','🦆','🦢','🦉','🦜','🪶'] as $emoji): ?>
                <div class="emoji-option" onclick="selectEmoji(this)"><?php echo $emoji;?></div>
            <?php endforeach; ?>
        </div>

        <div id="tab-insects" class="emoji-grid">
            <?php foreach (['🐝','🐞','🦋','🐛','🦗','🪲','🪳','🦟','🪰','🪱'] as $emoji): ?>
                <div class="emoji-option" onclick="selectEmoji(this)"><?php echo $emoji;?></div>
            <?php endforeach; ?>
        </div>

        <div id="tab-plants" class="emoji-grid">
            <?php foreach (['🌱','🌿','☘️','🍀','🎋','🌵','🌴','🌲','🌳','🪴','🪹','🪺'] as $emoji): ?>
                <div class="emoji-option" onclick="selectEmoji(this)"><?php echo $emoji;?></div>
            <?php endforeach; ?>
        </div>

        <div id="tab-humans" class="emoji-grid">
            <?php foreach ([
                '🧑','🧒','🧓','👩','👨','👧','👦','🧕','🧔','👮','🕵️','💂','🧙',
                '🧝','🧛','🧟','🧞','🧜','🧚','🧑‍🚀','🧑‍🔬','🧑‍🌾','🧑‍🏫','🧑‍🎨',
                '🧑‍🚒','🧑‍🍳','🧑‍⚖️','🧑‍💻','🧑‍🔧','🧑‍🏭'
            ] as $emoji): ?>
                <div class="emoji-option" onclick="selectEmoji(this)"><?php echo $emoji;?></div>
            <?php endforeach; ?>
        </div>

    </div>

    <input type="hidden" name="earthling_emoji" id="earthling_emoji">
    <p class="emoji-hint" style="text-align: center;"><span data-lang-id="005-emoji-hint" >Choose one emoji to represent you on </span><?= htmlspecialchars($app_info['app_display_name']) ?>.</p>
</div>



<!-- COMMUNITY FIELD -->
<div class="form-item" id="community-section" style="margin-top:20px;padding-bottom: 0px;">
    <label for="community_name" data-lang-id="006-community-connect">
        Buwana accounts let you connect with local and global communities...
    </label><br>

    <div class="select-wrapper" style="position: relative;">
        <span class="select-icon" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 20px;">👥</span>

        <input type="text" id="community_name" name="community_name" aria-label="Community Name" list="community_list"
               placeholder="Type your community..." style="width: 100%; padding: 11px 10px 11px 50px; font-size: 20px !important;"
               value="<?php echo htmlspecialchars($pre_community ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    </div>

    <datalist id="community_list">
        <?php foreach ($communities as $community) : ?>
            <option value="<?php echo htmlspecialchars($community, ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo (isset($pre_community) && $community === $pre_community) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($community, ENT_QUOTES, 'UTF-8'); ?>
            </option>
        <?php endforeach; ?>
    </datalist>

    <!-- "Add a new community" text link -->
    <p class="form-caption" data-lang-id="012-community-caption-xx">
        Start typing to see and select a community. There's a good chance someone local to you has already set one up!<br>
        <a href="#" onclick="openAddCommunityModal(); return false;" style="color: #007BFF; text-decoration: underline;">
            + Don't see your community? Add it.
        </a>
    </p>
</div>


<!-- COUNTRY SELECT -->
<div class="form-item" id="country-section" style="margin-top: 20px; position: relative;">
    <label for="country_name">Please make sure we've connected you with the right country:</label><br>

    <div class="select-wrapper" style="position: relative;">
        <span class="select-icon" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 22px;">🌍</span>

        <select id="country_name" name="country_name" required style="width: 100%; padding: 10px 10px 10px 40px;">
            <option value="">-- Select your country --</option>
            <?php foreach ($countries as $country): ?>
                <option value="<?php echo htmlspecialchars($country['country_id']); ?>"
                    <?php echo ($country['country_id'] == $user_country_id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($country['country_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>





<?php
// Get current language directory from URL (e.g., 'en', 'fr', etc.)
$current_lang_dir = basename(dirname($_SERVER['SCRIPT_NAME']));
?>

<!-- LANGUAGE SELECT -->
<!-- LANGUAGE SELECT -->
<div class="form-item" id="language-section" style="margin-top: 20px; position: relative;">
    <label for="language_id">Please make sure we've selected the right primary language for you:</label><br>

    <div class="select-wrapper" style="position: relative;">
        <span class="select-icon" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 22px;">🗣️</span>

        <select id="language_id" name="language_id" required style="width: 100%; padding: 10px 10px 10px 40px;">
            <option value="">-- Select your language --</option>
            <?php foreach ($languages as $language): ?>
                <option value="<?php echo htmlspecialchars($language['language_id']); ?>"
                    <?php echo ($language['language_id'] === $current_lang_dir) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($language['languages_native_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>



<!-- Kick-Ass Submit Button -->
                <div id="submit-section" class="submit-button-wrapper">

                    <button type="submit" id="submit-button" class="kick-ass-submit">
                        <span id="submit-button-text" data-lang-id="015-next-button-x">All done! 👌</span>
                        <span id="submit-emoji" class="submit-emoji" style="display: none;"></span>
                    </button>
                </div>


<p class="form-caption" data-lang-id="022" style="text-align: center;margin-top: 20px;">Now you're ready to login!</p>

</form>





    </div>


<div id="browser-back-link" style="font-size: medium; text-align: center; margin: auto; align-self: center; padding-top: 40px; padding-bottom: 40px; margin-top: 0px;" data-lang-id="000-go-back">
    <p style="font-size: medium;">
        <a href="#" onclick="browserBack(event)" data-lang-id="000-goback">↩ Go back one</a>
    </p>
</div>

</div>
</div>
<!-- FOOTER STARTS HERE -->
<?php require_once ("../footer-2025.php"); ?>


<!-- place at the bottom of your HTML page -->


<script>

// --- tab behaviour ---------------------------------
document.getElementById('emojiTabs').addEventListener('click', e => {
    if (e.target.tagName !== 'LI') return;

    // update tab bar
    document.querySelectorAll('#emojiTabs li').forEach(li => li.classList.toggle('active', li === e.target));

    // show / hide the right grid
    const tabName = e.target.getAttribute('data-tab');
    document.querySelectorAll('.emoji-grid').forEach(grid => {
        grid.classList.toggle('active', grid.id === 'tab-' + tabName);
    });
});

// --- keep your existing picker logic ---------------
function selectEmoji(el) {
    // remove previous selection
    document.querySelectorAll('.emoji-option').forEach(opt => opt.classList.remove('selected'));

    // mark new one
    el.classList.add('selected');
    document.getElementById('earthling_emoji').value = el.textContent.trim();
}



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

<?php require_once ("../scripts/app_modals.php");?>


</body>
</html>




