<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

// Page setup
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'signup';
$version = '0.74';
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

// 🧠 Fetch user info
$first_name = 'User';
$sql = "SELECT first_name FROM users_tb WHERE buwana_id = ?";
$stmt = $buwana_conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('i', $buwana_id);
    $stmt->execute();
    $stmt->bind_result($first_name);
    $stmt->fetch();
    $stmt->close();
}

// 🌍 Fetch countries
$countries = [];
$sql_countries = "SELECT country_id, country_name FROM countries_tb ORDER BY country_name ASC";
$result_countries = $buwana_conn->query($sql_countries);
if ($result_countries && $result_countries->num_rows > 0) {
    while ($row = $result_countries->fetch_assoc()) {
        $countries[] = $row;
    }
}

// 🗣️ Fetch languages
$languages = [];
$sql_languages = "SELECT language_id, languages_native_name FROM languages_tb ORDER BY languages_native_name ASC";
$result_languages = $buwana_conn->query($sql_languages);
if ($result_languages && $result_languages->num_rows > 0) {
    while ($row = $result_languages->fetch_assoc()) {
        $languages[] = $row;
    }
}
?>







<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" integrity="sha512-h9FcoyWjHcOcmEVkxOfTLnmZFWIH0iZhZT1H2TbOq55xssQGEJHEaIm+PgoUaZbRvQTNTluNOEfb1ZRy6D3BOw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js" integrity="sha512-puJW3E/qXDqYp9IfhAI54BJEaWIfloJ7JWs7OeD5i6ruC9JZL1gERT1wjtwXFlh7CjE7ZJ+/vcRZRkIYIb6p4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<!--
GoBrik.com site version 3.0
Developed and made open source by the Global Ecobrick Alliance
See our git hub repository for the full code and to help out:
https://github.com/gea-ecobricks/gobrik-3.0/tree/main/en-->

<?php require_once ("../includes/signup-inc.php");?>

<div class="splash-title-block"></div>
<div id="splash-bar"></div>

<!-- PAGE CONTENT -->
<div id="top-page-image" class="welcome-casandra top-page-image"></div>

<div id="form-submission-box" class="landing-page-form">
    <div class="form-container">
        <div style="text-align:center;width:100%;margin:auto;">
            <p style="color:green;">✔ <?php echo htmlspecialchars($first_name); ?>, <span data-lang-id="001-password-set"> your password is set!</p>
            <div id="status-message"><span data-lang-id="012-status-heading2"> Now let's get you localized.</span></div>
            <div id="sub-status-message" data-lang-id="013-sub-ecozone" style="font-size:1.3em;padding-top:10px;padding-bottom:10px;">GoBrik is all about ecological action. Please help us determine your ecological zone:  the water shed or riverbasin where you live.</div>
        </div>

        <!-- ACTIVATE 3 FORM -->

      <form id="user-signup-form" method="post" action="activate-3.php?id=<?php echo htmlspecialchars($buwana_id); ?>">

    <!-- LOCATION FULL -->
    <div class="form-item">
        <label for="location_full" data-lang-id="011-your-local-area">Where is your home?</label><br>
        <div class="input-container">
            <input type="text" id="location_full" name="location_full" aria-label="Location Full" required style="padding-left:45px;">
            <div id="loading-spinner" class="spinner" style="display: none;"></div>
            <div id="location-pin" class="pin-icon">📍</div>
        </div>
        <p class="form-caption" data-lang-id="011-location-full-caption">Start typing your home location (without the street location!), and we'll fill in the rest.  Data source: OpenStreetMap API.</p>
        <div id="location-error-required" class="form-field-error" data-lang-id="000-field-required-error">This field is required.</div>
    </div>

    <input type="hidden" id="lat" name="latitude">
    <input type="hidden" id="lon" name="longitude">

    <!-- MAP AND WATERSHED SEARCH SECTION -->
    <div class="form-item" id="watershed-map-section" style="display: none; margin-top:20px;">
        <label for="watershed_select" data-lang-id="011-watershed-select">To what river/stream watershed does your local water flow?</label><br>
        <div id="map" style="height: 350px; border-radius: 0px 0px 12px 12px; margin-top: 8px;"></div>
        <p class="form-caption" data-lang-id="012-river-basics-2" style="margin-top:10px;">ℹ️ The map shows rivers and streams around you.  Choose the one to which your water flows.</p>
        <select id="watershed_select" name="watershed_select" aria-label="Watershed Select" style="width: 100%; padding: 10px;">
            <option value="" disabled selected data-lang-id="011b-select-river">👉 Select river/stream...</option>

        </select>


    </div>


             <!-- Kick-Ass Submit Button -->
             <div id="submit-section" style="display:none;" class="submit-button-wrapper">

                         <p style="margin-bottom:15px;">Buwana accounts use <a href="#" onclick="showModalInfo('watershed', '<?php echo $lang; ?>')" class="underline-link">watersheds</a> as a great non-political way to localize users by bioregion!</p>


               <button type="submit" id="submit-button" class="kick-ass-submit" title="Be sure to choose your local watershed!">
                 <span id="submit-button-text" data-lang-id="015-next-button-x">Next ➡</span>
                 <span id="submit-emoji" class="submit-emoji" style="display: none;"></span>
               </button>
             </div>



    <!-- SUBMIT SECTION
    <div id="submit-section" style="text-align: center; margin-top: 25px; display: none;" data-lang-id="016-next-button-2">

        <p style="margin-bottom:15px;">Buwana accounts use <a href="#" onclick="showModalInfo('watershed', '<?php echo $lang; ?>')" class="underline-link">watersheds</a> as a great non-political way to localize users by bioregion!</p>

        <input type="submit" id="submit-button" value="Next ➡️" class="submit-button enabled">

    </div>-->

</form>





    </div>



</div>

<div id="browser-back-link" style="font-size: medium; text-align: center; margin: auto; align-self: center; padding-top: 40px; padding-bottom: 40px; margin-top: 0px;" data-lang-id="000-go-back">
    <p style="font-size: medium;" >

        <a href="#" onclick="browserBack(event)" data-lang-id="000-goback">↩ Go back </a> if you need to correct something.
    </p>
</div>



</div>
<!-- FOOTER STARTS HERE -->
<?php require_once ("../footer-2025.php"); ?>


<script>





//FUnctions to access the openstreetmaps api and to populate the local area field and watershed field.
$(function () {
    let debounceTimer;
    let map, userMarker;
    let riverLayerGroup = L.layerGroup();

    // --- SECTION 1: Show/hide pin icon based on input value and loading state ---
    // This function manages the visibility of the location pin based on whether
    // the input field is empty or loading
    function updatePinIconVisibility() {
        if ($("#location_full").val().trim() === "" || $("#loading-spinner").is(":hidden")) {
            $("#location-pin").show();
        } else {
            $("#location-pin").hide();
        }
    }

    // --- SECTION 2: Initialize autocomplete for location search using OpenStreetMap Nominatim API ---
    // This section uses jQuery UI Autocomplete to fetch location suggestions from the OpenStreetMap Nominatim API.
    // It debounces the search query and sends a request to the API, returning location results.
    $("#location_full").autocomplete({
        source: function (request, response) {
            $("#loading-spinner").show();
            $("#location-pin").hide(); // Hide the pin icon when typing starts

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                $.ajax({
                    url: "https://nominatim.openstreetmap.org/search",
                    dataType: "json",
                    headers: {
                        'User-Agent': 'ecobricks.org'
                    },
                    data: {
                        q: request.term,
                        format: "json"
                    },
                    success: function (data) {
                        $("#loading-spinner").hide();
                        updatePinIconVisibility(); // Show the pin when data has loaded

                        // Map the returned data to an array of display_name, lat, and lon
                        response($.map(data, function (item) {
                            return {
                                label: item.display_name,
                                value: item.display_name,
                                lat: item.lat,
                                lon: item.lon
                            };
                        }));
                    },
                    error: function (xhr, status, error) {
                        $("#loading-spinner").hide();
                        updatePinIconVisibility(); // Show the pin when an error occurs
                        console.error("Autocomplete error:", error);
                        response([]);
                    }
                });
            }, 300);
        },
        select: function (event, ui) {
            // When a location is selected, the lat/lon values are populated and
            // the map/watershed sections are displayed.
            console.log('Selected location:', ui.item);
            $('#lat').val(ui.item.lat);
            $('#lon').val(ui.item.lon);

            initializeMap(ui.item.lat, ui.item.lon); // Initialize the map
            $('#watershed-map-section').fadeIn(); // Show the watershed map section
            $('#community-section').fadeIn(); // Show the community section
            showSubmitButton(); // Display the submit button

            updatePinIconVisibility(); // Show pin icon after selection
        },
        minLength: 3
    });

    // Update pin icon visibility when the user types in the location input field
    $("#location_full").on("input", function () {
        updatePinIconVisibility();
    });

    // --- SECTION 3: Show the submit button and set the height of the main div ---
    // This function fades in the submit button and adjusts the height of the `#main` div
    function showSubmitButton() {
        $('#submit-section').fadeIn();

        // Set the height of the main div to 1500px when the submit button is shown
        $('#main').css('height', '1500px');
    }

    // --- SECTION 4: Initialize the map using Leaflet and display user location ---
    // This section initializes a Leaflet map, centered on the selected latitude and longitude.
    // It also adds a marker for the user's selected location and loads nearby rivers.
    function initializeMap(lat, lon) {
        if (map) {
            map.remove(); // Remove the previous map instance if it exists
        }
        map = L.map('map', { preferCanvas: true }).setView([lat, lon], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add a marker to the map to show the user's selected location
        userMarker = L.marker([lat, lon]).addTo(map).bindPopup("Your Location").openPopup();

        // Fix map display issue if loaded in a hidden or resized container
        setTimeout(() => {
            map.invalidateSize(); // Ensure the map resizes correctly
        }, 200);

        // Fetch nearby rivers using Overpass API
        fetchNearbyRivers(lat, lon);
    }

    // --- SECTION 5: Fetch nearby rivers/watersheds from the Overpass API ---
    // This function sends a request to the Overpass API to fetch rivers or watersheds
    // near the selected location, and populates the watershed dropdown with the results.
// --- SECTION 5: Fetch nearby rivers/watersheds from the Overpass API ---
function fetchNearbyRivers(lat, lon) {
    riverLayerGroup.clearLayers(); // Clear previous rivers from the map
    $("#watershed_select").empty().append('<option value="" disabled selected>Select a river or watershed</option>');

    const overpassUrl = `https://overpass-api.de/api/interpreter?data=[out:json];(way["waterway"="river"](around:5000,${lat},${lon});relation["waterway"="river"](around:5000,${lat},${lon}););out geom;`;

    $.get(overpassUrl, function (data) {
        let rivers = data.elements;
        let uniqueRivers = new Set(); // Set to store unique river names

        rivers.forEach((river, index) => {
            let riverName = river.tags.name;

            // Only add named rivers that aren't "unnamed" to the dropdown and the map
            if (riverName && !uniqueRivers.has(riverName) && !riverName.toLowerCase().includes("unnamed")) {
                uniqueRivers.add(riverName); // Track unique river names

                let coordinates = river.geometry.map(point => [point.lat, point.lon]);
                // Draw the river polyline on the map
                let riverPolyline = L.polyline(coordinates, { color: 'blue' }).addTo(riverLayerGroup).bindPopup(riverName);
                riverLayerGroup.addTo(map);

                // Add river to the watershed dropdown
                $("#watershed_select").append(new Option(riverName, riverName));
            }
        });

        if (uniqueRivers.size === 0) {
            $("#watershed_select").append('<option value="" disabled>No rivers or watersheds found nearby</option>');
        }

        // --- Select the appropriate language object based on the $lang variable ---
        const lang = '<?php echo htmlspecialchars($lang); ?>'; // Retrieve the PHP $lang variable
        let translations;

        switch (lang) {
            case 'fr':
                translations = fr_Page_Translations;
                break;
            case 'es':
                translations = es_Page_Translations;
                break;
            case 'id':
                translations = id_Page_Translations;
                break;
            default:
                translations = en_Page_Translations; // Default to English
        }

        // --- Add the additional fixed options using the selected language translations ---
        $("#watershed_select").append(
            $('<option>', {
                value: "watershed unknown",
                text: translations['011c-unknown'], // Using translation variable for "I don't know"
                'data-lang-id': "011c-unknown"
            })
        );
        $("#watershed_select").append(
            $('<option>', {
                value: "watershed unseen",
                text: translations['011d-unseen'], // Using translation variable for "I don't see my local river/stream"
                'data-lang-id': "011d-unseen"
            })
        );
        $("#watershed_select").append(
            $('<option>', {
                value: "no watershed",
                text: translations['011e-no-watershed'], // Using translation variable for "No watershed"
                'data-lang-id': "011e-no-watershed"
            })
        );
    }).fail(function () {
        console.error("Failed to fetch data from Overpass API.");
        $("#watershed_select").append('<option value="" disabled>Error fetching rivers</option>');
    });
}



    // --- SECTION 6: Form submission handling ---
    // This section logs the latitude and longitude when the form is submitted.
    $('#user-info-form').on('submit', function () {
        console.log('Latitude:', $('#lat').val());
        console.log('Longitude:', $('#lon').val());
        // Additional submit handling if needed
    });
});




</script>


<?php require_once ("../scripts/app_modals.php");?>




</body>
</html>
