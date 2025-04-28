<!--FOOTER STARTS-->
<div id="footer-full" style="margin-top:0px">

    <div class="vision-landscape">
        <img src="../webps/vision-day-2024.webp" style="width:100%; margin-top:-2px;" loading="lazy" data-lang-id="400-visionscape-description" alt="We envision a great green transition from ways that pollute to ways that enrich.  And it starts with our plastic.">
    </div>

    <div class="footer-vision" data-lang-id="1004-gea-vision">

        We envision a Transition in our Households, Communities and Enterprises from Plastic to an ever Greener Harmony with Earth's Cycles.

    </div>



    <div class="footer-bottom">
        <div class="footer-conclusion">


            <div id="wcb" class="carbonbadge wcb-d"></div>

            <div class="footer-conclusion" data-lang-id="419x-conclusion-disclosure">We track and disclose our net-green ecological impact.  See our <a href="https://ecobricks.org/en/regenreports.php" target="_blank">Regen Reporting</a> for 2025.</a>
            </div>

            <div class="footer-conclusion" data-lang-id="420-conclusion-contribute">
                The Buwana project is hand coded in open source HTML, PHP MYSQL, CSS and Javascript. Contribute to making this page better by leaving a bug report or push request on Github:
            </div>

            <div class="footer-conclusion">
            ↳ <a href="https://github.com/gea-ecobricks/buwana/blob/main/<?php echo ($lang); ;?>/<?php echo ($name); ;?>" target="_blank">github.com/gea-ecobricks/buwana/blob/main/<?php echo ($lang); ;?>/<?php echo ($name); ;?></a>
            </div>

            <div style="margin-top:15px">
                <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Creative Commons BY SA 4.0 License" src="../icons/cc-by-sa.svg" style="width:200px;height:45px;border-width:0" loading="lazy"></a>
            </div>



            <div class="footer-conclusion" data-lang-id="422-conclusion-copyright">
                The Buwana, GEA, Earthen, AES and Gobrik logos and emblems are copyright 2010-2025 by the Global Ecobrick Alliance.
            </div>

        </div>

    </div>

</div>

	<!--FOOTER ENDS-->



<!-- Translation variable files for the languageSwitcher-->
<script src="../translations/core-texts-en.js?v=<?php echo ($version); ;?>"></script>
<script src="../translations/core-texts-fr.js?v=<?php echo ($version); ;?>"></script>
<script src="../translations/core-texts-id.js?v=<?php echo ($version); ;?>"></script>
<script src="../translations/core-texts-es.js?v=<?php echo ($version); ;?>"></script>

<script src="../translations/<?php echo ($page); ;?>-en.js?v=<?php echo ($version); ;?>"></script>
<script src="../translations/<?php echo ($page); ;?>-fr.js?v=<?php echo ($version); ;?>"></script>
<script src="../translations/<?php echo ($page); ;?>-id.js?v=<?php echo ($version); ;?>1"></script>
<script src="../translations/<?php echo ($page); ;?>-es.js?v=<?php echo ($version); ;?>"></script>

<!--
    <script src="../scripts/website-carbon-badges@1.1.3/b.min.js" defer></script>
-->
    <script src="https://unpkg.com/website-carbon-badges@1.1.3/b.min.js" defer></script>


<script>


var siteName = 'Is this it?';
var currentLanguage = '<?php echo ($lang); ?>'; // Default language code
switchLanguage(currentLanguage);


document.getElementById('top-settings-button').addEventListener('touchstart', function(event) {
  if (window.matchMedia("(max-width: 700px)").matches) {
    var settingsButtons = document.getElementById('settings-buttons');
    settingsButtons.classList.toggle('settings-buttons-expanded');
    event.stopPropagation(); // Prevents the event from bubbling up to the document
  }
});

document.addEventListener('touchstart', function(event) {
  var settingsButtons = document.getElementById('settings-buttons');
  if (!settingsButtons.contains(event.target)) {
    settingsButtons.classList.remove('settings-buttons-expanded');
  }
});


// Add event listeners to each button inside the language-menu-slider
var langButtons = document.querySelectorAll('#language-menu-slider');
langButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        // Hide the slider after 1 second when a language button is clicked
        setTimeout(hideLangSelector, 3000);
    });
});

// Prevent hiding when clicking inside the slider
document.getElementById('language-menu-slider').addEventListener('click', function(event) {
    event.stopPropagation();
});


// Prevent hiding when clicking inside the slider
document.getElementById('login-menu-slider').addEventListener('click', function(event) {
    event.stopPropagation();
});






/* TOGGLE ACCORDION SCRIPTS  */

document.addEventListener('DOMContentLoaded', function() {
    // Function to rotate the plus symbol
    function spinThePlus(toggleIcon) {
        if (toggleIcon.classList.contains('rotate-plus')) {
            toggleIcon.classList.remove('rotate-plus');
            toggleIcon.classList.add('rotate-minus'); // Rotate it 45 degrees backwards
        } else if (toggleIcon.classList.contains('rotate-minus')) {
            toggleIcon.classList.remove('rotate-minus'); // Revert to original state if already rotated backwards
            toggleIcon.classList.add('rotate-plus'); // Rotate it 45 degrees forwards
        } else {
            toggleIcon.classList.add('rotate-plus'); // Initial rotation if no rotation has been applied
        }
    }

    document.querySelectorAll('.accordion-title').forEach(button => {
        button.addEventListener('click', () => {
            const accordionContent = button.nextElementSibling;
            const toggleIcon = button.querySelector('.toggle-icon');

            spinThePlus(toggleIcon); // Call function to rotate the plus symbol

            // Close other items
            document.querySelectorAll('.accordion-content').forEach(content => {
                if (content !== accordionContent && content.style.maxHeight) {
                    content.style.maxHeight = null;
                    // Reset other toggle icons to the initial state
                    const otherToggleIcon = content.previousElementSibling.querySelector('.toggle-icon');
                    otherToggleIcon.classList.remove('rotate-minus', 'rotate-plus');
                    otherToggleIcon.classList.add('rotate-plus');
                }
            });

            // Toggle current item
            if (accordionContent.style.maxHeight) {
                accordionContent.style.maxHeight = null;
            } else {
                // Use setTimeout to allow for the maxHeight change to be animated
                accordionContent.style.maxHeight = accordionContent.scrollHeight + "px";
            }
        });
    });

    // Toggle the translation information
    document.querySelectorAll('.circle').forEach(circle => {
        circle.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent the accordion toggle or link navigation

            const translationInfo = this.closest('.submenu-item-container').querySelector('.translation-info');

            // Toggle visibility of translationInfo
            if (translationInfo.style.maxHeight && translationInfo.style.maxHeight !== '0px') {
                translationInfo.style.maxHeight = null;
                setTimeout(() => {
                    translationInfo.style.display = 'none';
                }, 350); // Adjust timing as needed to match CSS transitions
            } else {
                translationInfo.style.display = 'block';
                // Ensure the display change takes effect before calculating scrollHeight
                requestAnimationFrame(() => {
                    translationInfo.style.maxHeight = translationInfo.scrollHeight + 'px';
                });
            }
        });
    });
});



//
// function createInfoModal(infoText) {
//     console.log("Modal Function called");
//     const modal = document.getElementById('form-modal-message');
//     const messageContainer = modal.querySelector('.modal-message');
//     messageContainer.textContent = infoText;
//
//     // Toggle classes to show the modal
//     // modal.classList.remove('modal-hidden');
//     // modal.classList.add('modal-shown');
//     modal.style.display = 'flex';
//
//
//     // Update other page elements as needed
//     document.getElementById('page-content').classList.add('blurred');
//     document.getElementById('footer-full').classList.add('blurred');
//     document.body.classList.add('modal-open');
//
//
//     // Show all buttons with class "x-button" again
//     const xButtons = document.querySelectorAll('.x-button');
//     xButtons.forEach(button => button.style.display = 'inline-block');
//
// }




// Function to close the modal
function closeInfoModal() {
    var modal = document.getElementById("form-modal-message");
    modal.style.display = "none";
    document.body.style.overflow = 'auto'; // Re-enable body scrolling
    // Remove blur effect and restore overflow on page-content and footer-full
    document.getElementById('page-content').classList.remove('blurred');
    document.getElementById('footer-full').classList.remove('blurred');
    document.body.classList.remove('modal-open');
}

function showModalInfo(type, lang) {
    const modal = document.getElementById('form-modal-message');
    const photobox = document.getElementById('modal-photo-box');
    const messageContainer = modal.querySelector('.modal-message');
    const modalBox = document.getElementById('modal-content-box');
    let content = '';
    photobox.style.display = 'none';

    // Determine the appropriate language object
    let translations;
    switch (lang) {
        case 'fr':
            translations = fr_Translations;
            break;
        case 'es':
            translations = es_Translations;
            break;
        case 'id':
            translations = id_Translations;
            break;
        default:
            translations = en_Translations; // Default to English
    }

    switch (type) {
        case 'terms':
            content = `
                <div style="font-size: small;">
                    <?php include "../files/terms-${lang}.php"; ?>
                </div>
            `;
            modal.style.position = 'absolute';
            modal.style.overflow = 'auto';
            modalBox.style.textAlign = 'left';
            modalBox.style.maxHeight = 'unset';
            modalBox.style.marginTop = '30px';
            modalBox.style.marginBottom = '30px';
            modalBox.scrollTop = 0;
            modal.style.alignItems = 'flex-start';
            break;

        case 'earthen':
            content = `
                <img src="../svgs/earthen-newsletter-logo.svg" alt="${translations['earthen-title']}" height="250px" width="250px" class="preview-image">
                <div class="preview-title">${translations['earthen-title']}</div>
                <div class="preview-text">${translations['earthen-text']}</div>
            `;
            break;

        case 'ecobrick':
            content = `
                <img src="../webps/faqs-400px.webp" alt="${translations['ecobrick-title']}" height="200px" width="200px" class="preview-image">
                <div class="preview-title">${translations['ecobrick-title']}</div>
                <div class="preview-text">${translations['ecobrick-text']}</div>
            `;
            break;

        case 'watershed':
            content = `
                <div style="width:100%;text-align:center;">
                    <h1>💧</h1>
                </div>
                <div class="preview-text">${translations['watershed-text']}</div>
            `;
            break;

        // New Cases
        case 'ocean':
            content = `
                <img class="preview-image brik-type-image" src="../svgs/oebs.svg" alt="${translations['ocean-title']}" height="200" width="200" style="margin:auto; display:block;">
                <div class="preview-text" style="text-align:center;">${translations['ocean-text']}</div>
                <div style="width:100%;text-align:center;">
                    <a class="preview-btn" href="https://ecobricks.org/ocean" target="_blank">${translations['learn-more']}</a>
                    <p style="font-size:smaller">${translations['link-note']}</p>
                </div>
            `;
            break;

        case 'cigbrick':
            content = `
                <img src="../svgs/cigbrick.svg" alt="${translations['cigbrick-title']}" height="250px" width="250px" class="preview-image" style="margin:auto; display:block;">
                <div class="preview-text" style="text-align:center;">${translations['cigbrick-text']}</div>
                <div style="width:100%;text-align:center;">
                    <a class="preview-btn" href="/cigbricks">${translations['learn-more']}</a>
                    <p style="font-size:smaller">${translations['link-note']}</p>
                </div>
            `;
            break;

        case 'regular':
            content = `
                <img class="preview-image" src="../webps/eb-sky-400px.webp" alt="${translations['regular-title']}" height="300" style="margin:auto; display:block;">
                <p class="preview-text" style="text-align:center;">${translations['regular-text']}</p>
                <div style="width:100%;text-align:center;">
                    <a class="preview-btn" href="what.php">${translations['learn-more']}</a>
                    <p style="font-size:smaller">${translations['link-note']}</p>
                </div>

            `;
            break;

            case 'inserts':
            content = `
                <img class="preview-image" src="../photos/insert-example.webp" alt="${translations['inserts-title']}" height="300" style="margin:auto; display:block;">
                <p style="text-align:center;">⭐⭐⭐⭐⭐</p>
                <p class="preview-text" style="text-align:center;">${translations['inserts-text']}</p>


            `;
            break;

             case 'nailvarnish':
            content = `
                <img class="preview-image" src="../photos/nailvarnish-example.webp" alt="${translations['nailvarnish-title']}" height="300" style="margin:auto; display:block;">
                <p style="width:100%;text-align:center;">⭐⭐⭐⭐</p>
                <p class="preview-text" style="text-align:center;">${translations['nailvarnish-text']}</p>


            `;
            break;

            case 'enamel':
            content = `
                <img class="preview-image" src="../photos/enamel-example.webp" alt="${translations['enamel-title']}" height="300" style="margin:auto; display:block;">
                <p style="width:100%;text-align:center;">⭐⭐⭐⭐</p>
                <p class="preview-text" style="text-align:center;">${translations['enamel-text']}</p>


            `;
            break;

            case 'marker':
            content = `
                <img class="preview-image" src="../photos/marker-example.webp" alt="${translations['marker-title']}" height="300" style="margin:auto; display:block;">
                <p style="width:100%;text-align:center;">⭐⭐</p>
                <p class="preview-text" style="text-align:center;">${translations['marker-text']}</p>

            `;
            break;

        default:
            content = '<p style="width:100%;text-align:center;">Invalid term selected.</p>';
    }

    messageContainer.innerHTML = content;

    // Show the modal and update other page elements
    modal.style.display = 'flex';
    document.getElementById('page-content').classList.add('blurred');
    document.getElementById('footer-full').classList.add('blurred');
    document.body.classList.add('modal-open');
}


</script>


