function openProfile() {
    window.location.href = 'profile.php';
}

function logoutUser() {
    // Extracts only the part of the URL after the last slash, including query parameters
    const path = window.location.pathname.split("/").pop() + window.location.search;
    const redirectUrl = encodeURIComponent(path);
    window.location.href = `logout.php?redirect=${redirectUrl}`;
}





function switchLanguage(langCode) {
 currentLanguage = langCode; // Update the global language variable

    // Dynamic selection of the correct translations object
    const languageMappings = {
        'en': {...en_Translations, ...en_Page_Translations},
        'fr': {...fr_Translations, ...fr_Page_Translations},
        'es': {...es_Translations, ...es_Page_Translations},
        'id': {...id_Translations, ...id_Page_Translations}
    };

    const currentTranslations = languageMappings[currentLanguage];


    const elements = document.querySelectorAll('[data-lang-id]');
    elements.forEach(element => {
        const langId = element.getAttribute('data-lang-id');
        const translation = currentTranslations[langId]; // Access the correct translations
        if (translation) {
            if (element.tagName.toLowerCase() === 'input' && element.type !== 'submit') {
                element.placeholder = translation;
            } else if (element.hasAttribute('aria-label')) {
                element.setAttribute('aria-label', translation);
            } else if (element.tagName.toLowerCase() === 'img') {
                element.alt = translation;
            } else {
                element.innerHTML = translation; // Directly set innerHTML for other elements
            }
        }
    });

}



function redirectToAppHome(apphome) {
    window.location.href = apphome;
}


// document.addEventListener("scroll", function() {
//     var scrollPosition = window.scrollY || document.documentElement.scrollTop;
//
//     // Check if the user has scrolled more than 1000px
//     if (scrollPosition > 1000) {
//         var footer = document.getElementById('footer-full');
//         if (footer) {
//             footer.style.display = 'block'; // Show the footer
//         }
//     }
// });




/* LEFT MAIN MENU OVERLAY */

function openSideMenu() {
    const modal = document.getElementById("main-menu-overlay");
    modal.classList.add("open");
    modal.style.display = "block";
    document.body.style.overflowY = "hidden";
    document.body.style.maxHeight = "100vh";

    modal.setAttribute('tabindex', '0');
    modal.focus();
}

function closeSettings() {
    const modal = document.getElementById("main-menu-overlay");
    modal.classList.remove("open");

    setTimeout(() => {
        modal.style.display = "none";
        document.body.style.overflowY = "unset";
        document.body.style.maxHeight = "unset";
    }, 400);

    if (typeof hideLoginSelector === 'function') hideLoginSelector();
    if (typeof hideLangSelector === 'function') hideLangSelector();
}

function modalCloseCurtains(e) {
    if (!e.keyCode || e.keyCode === 27) {
        closeSettings();
    }
}






/* ---------- ------------------------------
LANGUAGE SELECTOR
-------------------------------------------*/

function showLangSelector() {
    hideLoginSelector();

    var slider = document.getElementById('language-menu-slider');
    var currentMarginTop = window.getComputedStyle(slider).marginTop;
    slider.style.display = 'flex';
    slider.style.marginTop = currentMarginTop === '70px' ? '0px' : '70px';

    // Set zIndex of top-page-image
    var topPageImage = document.querySelector('.top-page-image');
    if (topPageImage) {
        topPageImage.style.zIndex = '25';
    }

    // Prevent event from bubbling to document
    event.stopPropagation();

    // Add named event listener for click on the document
    document.addEventListener('click', documentClickListener);
}

function hideLangSelector() {
    var slider = document.getElementById('language-menu-slider');
    slider.style.marginTop = '0px'; // Reset margin-top to 0px

    // Set zIndex of top-page-image
    var topPageImage = document.querySelector('.top-page-image');
    if (topPageImage) {
        topPageImage.style.zIndex = '35';
    }

    // Remove the named event listener from the document
    document.removeEventListener('click', documentClickListener);
}

// Named function to be used as an event listener
function documentClickListener() {
    hideLangSelector();
}

/* ---------- ------------------------------
SERVICE SELECTOR
-------------------------------------------*/

function showLoginSelector() {
    hideLangSelector();

    var slider = document.getElementById('login-menu-slider');
    var currentMarginTop = window.getComputedStyle(slider).marginTop;
    slider.style.display = 'flex';
    slider.style.marginTop = currentMarginTop === '70px' ? '0px' : '70px';

    // Set zIndex of top-page-image
    var topPageImage = document.querySelector('.top-page-image');
    if (topPageImage) {
        topPageImage.style.zIndex = '25';
    }

    // Prevent event from bubbling to document
    event.stopPropagation();

    // Add named event listener for click on the document
    document.addEventListener('click', documentClickListenerLogin);
}

function hideLoginSelector() {
    var slider = document.getElementById('login-menu-slider');
    slider.style.marginTop = '0px'; // Reset margin-top to 0px

    // Set zIndex of top-page-image
    var topPageImage = document.querySelector('.top-page-image');
    if (topPageImage) {
        topPageImage.style.zIndex = '35';
    }

    // Remove the named event listener from the document
    document.removeEventListener('click', documentClickListenerLogin);
}

// Named function to be used as an event listener
function documentClickListenerLogin() {
    hideLoginSelector();
}

function goBack() {
    window.history.back();
}




document.querySelectorAll('.x-button').forEach(button => {
    button.addEventListener('transitionend', (e) => {
        // Ensure the transitioned property is the transform to avoid catching other transitions
        if (e.propertyName === 'transform') {
            // Check if the button is still being hovered over
            if (button.matches(':hover')) {
                button.style.backgroundImage = "url('../svgs/x-button-night-over.svg?v=3')";
            }
        }
    });

    // Optionally, revert to the original background image when not hovering anymore
    button.addEventListener('mouseleave', () => {
        button.style.backgroundImage = "url('../svgs/x-button-night.svg?v=3')";
    });
});



//ECOBRICK MODAL PREVIEW

function ecobrickPreview(imageUrl, brik_serial, weight, owner, location) {
    const modal = document.getElementById('form-modal-message');
    const contentBox = modal.querySelector('.modal-content-box'); // This is the part we want to hide
    const photoBox = modal.querySelector('.modal-photo-box'); // This is where we'll show the image
    const photoContainer = modal.querySelector('.modal-photo'); // The container for the image

    // Hide the content box and show the photo box
    contentBox.style.display = 'none'; // Hide the content box
    photoBox.style.display = 'block'; // Make sure the photo box is visible

    // Clear previous images from the photo container
    photoContainer.innerHTML = '';

    // Create and append the ecobrick image to the photo container
    var img = document.createElement('img');
    img.src = imageUrl;
    img.alt = "Ecobrick " + brik_serial;
    img.style.maxWidth = '90%';
    img.style.maxHeight = '75vh';
    img.style.minHeight = "400px";
    img.style.minWidth = "400px";
    img.style.margin = 'auto';
    // img.style.backgroundColor = '#8080802e'; hmmm
    photoContainer.appendChild(img);

    // Add ecobrick details and view details button inside photo container
    var details = document.createElement('div');
    details.className = 'ecobrick-details';
    details.innerHTML = '<p>Ecobrick ' + brik_serial + ' | ' + weight + ' of plastic sequestered by ' + owner + ' in ' + location + '.</p>' +
                        '<a href="brik.php?serial_no=' + brik_serial + '" class="preview-btn" style="margin-bottom: 50px;height: 25px;padding: 5px;border: none;padding: 5px 12px;">ℹ️ View Full Details</a>';
    photoContainer.appendChild(details);

    // Hide other parts of the modal that are not used for this preview
    modal.querySelector('.modal-content-box').style.display = 'none'; // Assuming this contains elements not needed for this preview

    // Show the modal
    modal.style.display = 'flex';

    //Blur out background
    document.getElementById('page-content')?.classList.add('blurred');
    document.getElementById('footer-full')?.classList.add('blurred');
    document.body.classList.add('modal-open');
}



/* ---------- ------------------------------

SCROLL CONTROL

-------------------------------------------*/
// let lastScrollTop = 0;
//
// window.onscroll = function() {
//     scrollLessThan30();
//     scrollMoreThan30();
//     scrollMoreThan800();
//     scrollLessThan800();
// };
//
// function scrollLessThan30() {
//     if (window.pageYOffset <= 30) {
// //        document.getElementById("header").style.height = "85px";
//         document.getElementById("header").style.borderBottom = "none";
//         document.getElementById("header").style.boxShadow = "none";
// //        document.getElementById("gea-logo").style.width = "190px";
// //        document.getElementById("gea-logo").style.height = "40px";
//         document.getElementById("logo-gobrik").style.opacity = "1";
// //        document.getElementById("header").style.top = "0";
// //        document.getElementById("settings-buttons").style.padding = "16px 43px 16px 12px";
// //        document.getElementById("language-menu-slider").style.top = "-15px";
// //        document.getElementById("login-menu-slider").style.top = "-15px";
//
//         // Set zIndex for the top banner image
//         var topPageImage = document.querySelector('.top-page-image');
//         if (topPageImage) {
//             topPageImage.style.zIndex = "35";
//         }
//     }
// }
//
// function scrollMoreThan30() {
//     if (window.pageYOffset > 30 && window.pageYOffset < 800) {
// //        document.getElementById("header").style.height = "60px";
//         document.getElementById("header").style.borderBottom = "var(--header-accent) 0.5px solid";
//         document.getElementById("header").style.boxShadow = "0px 0px 15px rgba(0, 0, 10, 0.805)";
//         document.getElementById("gea-logo").style.width = "170px";
//         document.getElementById("gea-logo").style.height = "35px";
//         document.getElementById("logo-gobrik").style.opacity = "0.9";
// //        document.getElementById("settings-buttons").style.padding = "12px 43px 10px 12px";
// //        document.getElementById("language-menu-slider").style.top = "-35px";
// //        document.getElementById("login-menu-slider").style.top = "-35px";
//
//         // Tuck the top banner image under the header
//         var topPageImage = document.querySelector('.top-page-image');
//         if (topPageImage) {
//             topPageImage.style.zIndex = "25";
//         }
//     }
// }
//
// function scrollMoreThan800() {
//     if (window.pageYOffset >= 800) {
//         // Hide the header completely
//         document.getElementById("header").style.top = "-140px";
//     }
// }
//
// function scrollLessThan800() {
//     if (window.pageYOffset < 800) {
//         // Show the header again
//         document.getElementById("header").style.top = "0";
//     }
// }


/* ---------- ------------------------------
TOGGLE PASSWORD VISIBILITY
-------------------------------------------*/


document.addEventListener("DOMContentLoaded", function() {
    // Select all elements with the class 'toggle-password'
    const togglePasswordIcons = document.querySelectorAll('.toggle-password');

    togglePasswordIcons.forEach(function(icon) {
        icon.addEventListener('click', function() {
            // Find the associated input field using the 'toggle' attribute
            const input = document.querySelector(icon.getAttribute('toggle'));
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = '🙉'; // 🔓 Change to unlocked emoji
                } else {
                    input.type = 'password';
                    icon.textContent = '🙈'; // 🔒 Change to locked emoji
                }
            }
        });
    });
});



/*-------------------------------------------


 SCRIPTS FOR ONCE LOGGED IN


-------------------------------------------*/

function handleLogout(event) {
    event.preventDefault(); // Prevent default link behavior

    // Perform logout via AJAX
    fetch(event.target.href)
        .then(response => {
            if (response.ok) {
                // Redirect to the login page with the appropriate parameters
                window.location.href = response.url;
            } else {
                console.error('Failed to log out:', response.statusText);
            }
        })
        .catch(error => {
            console.error('Error during logout:', error);
        });
}



function openAboutBuwanaModal() {
    closeSettings();

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
        <div style="text-align: center;">
            <h1 style="font-size: 3em; margin-bottom: 0;">🌏</h1>
            <div class="buwana-word-mark" title="Authentication by Buwana" style="margin: 0 auto 10px auto; width: 220px; height: 50px; background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
        </div>

        <p><strong>Buwana</strong> is a regenerative alternative to corporate login systems, created to serve our global community with privacy, security, and principle. Rather than rely on closed-source platforms like Google or Facebook, Buwana provides an open, not-for-profit account system that enables secure access to our apps — including GoBrik, Ecobricks.org, Open Books, and the Brikcoin Wallet — while respecting user data and ecological values. Designed to hold community, geographical, and impact data, Buwana accounts are transferable across platforms and built for organizations committed to Earth service.</p>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="https://github.com/gea-ecobricks/buwana" target="_blank" rel="noopener noreferrer" class="confirm-button enabled" style="text-decoration: none;">
                🌐 View Buwana Project on GitHub
            </a>
        </div>
    `;
}






