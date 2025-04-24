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

    modal.style.display = "block"; // Step 1: Make it visible

    // Step 2: Use requestAnimationFrame to ensure the DOM has applied the display change before triggering the transition
    requestAnimationFrame(() => {
        modal.classList.add("open");
    });

    document.body.classList.add("no-scroll"); // Lock scroll

    modal.setAttribute('tabindex', '0');
    modal.focus();
}


function closeMainMenu() {
    const modal = document.getElementById("main-menu-overlay");
    modal.classList.remove("open");

    setTimeout(() => {
        modal.style.display = "none";
        document.body.classList.remove("no-scroll"); // ✅ Re-enable scroll
    }, 400);

    if (typeof hideLoginSelector === 'function') hideLoginSelector();
    if (typeof hideLangSelector === 'function') hideLangSelector();
}


function modalCloseCurtains(e) {
    if (!e.keyCode || e.keyCode === 27) {
        closeMainMenu();
    }
}





document.addEventListener('DOMContentLoaded', () => {
    const settingsButton = document.getElementById('top-settings-button');
    const settingsPanel = document.getElementById('settings-buttons');
    const langMenu = document.getElementById('language-menu-slider');
    const loginMenu = document.getElementById('login-menu-slider');

    let settingsOpen = false;

    // 🔁 Toggle settings panel
    window.toggleSettingsMenu = () => {
        settingsOpen = !settingsOpen;
        settingsPanel.classList.toggle('open', settingsOpen);
        settingsButton.setAttribute('aria-expanded', settingsOpen ? 'true' : 'false');

        hideLangSelector();
        hideLoginSelector();
    };

    // 🌐 Toggle language selector
    window.showLangSelector = () => {
        const isVisible = langMenu.classList.contains('menu-slider-visible');
        hideLoginSelector();

        if (isVisible) {
            hideLangSelector(); // If already shown, hide
        } else {
            langMenu.classList.add('menu-slider-visible');
            document.addEventListener('click', documentClickListenerLang);
        }
    };

    window.hideLangSelector = () => {
        langMenu.classList.remove('menu-slider-visible');
        document.removeEventListener('click', documentClickListenerLang);
    };

    function documentClickListenerLang(e) {
        if (!langMenu.contains(e.target) && e.target.id !== 'language-code') {
            hideLangSelector();
        }
    }

    // 🔐 Toggle login selector
    window.showLoginSelector = () => {
        const isVisible = loginMenu.classList.contains('menu-slider-visible');
        hideLangSelector();

        if (isVisible) {
            hideLoginSelector(); // Hide if already visible
        } else {
            loginMenu.classList.add('menu-slider-visible');
            document.addEventListener('click', documentClickListenerLogin);
        }
    };

    window.hideLoginSelector = () => {
        loginMenu.classList.remove('menu-slider-visible');
        document.removeEventListener('click', documentClickListenerLogin);
    };

    function documentClickListenerLogin(e) {
        if (!loginMenu.contains(e.target) && !e.target.classList.contains('top-login-button')) {
            hideLoginSelector();
        }
    }

    // ✋ Click outside to close settings
    document.addEventListener('click', (e) => {
        if (!settingsPanel.contains(e.target) && e.target !== settingsButton) {
            settingsPanel.classList.remove('open');
            settingsOpen = false;
            settingsButton.setAttribute('aria-expanded', 'false');
        }
    });

    // Prevent menu closure on internal click
    settingsPanel.addEventListener('click', (e) => {
        e.stopPropagation();
    });
});

// 🔻 Hide dropdowns on scroll
window.addEventListener('scroll', () => {
    hideLangSelector();
    hideLoginSelector();
});


document.addEventListener('DOMContentLoaded', function () {
    const header = document.getElementById('header');

    window.addEventListener('scroll', function () {
        if (window.innerWidth < 769) {
            if (window.scrollY > 1) {
                header.style.position = 'fixed';
                header.style.zIndex = '20';
                header.style.top = '0'; // just in case
            } else {
                header.style.position = 'absolute';
                header.style.zIndex = '36';
            }
        } else {
            // Reset for larger screens (if needed)
            header.style.position = 'absolute';
            header.style.zIndex = '36';
        }
    });
});




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





    function navigateTo(url) {
    window.location.href = url;
}


//
//     document.addEventListener('colorschemechange', (event) => {
//     const newMode = event.detail.colorScheme;
//     const isDark = newMode === 'dark';
//
//     const topLogo = document.getElementById('top-app-logo');
//     const appLogo = document.querySelector('.the-app-logo');
//     const signupBanner = document.querySelector('.app-signup-banner');
//
//     const lightWordmark = '<?= $app_info["app_wordmark_url"] ?>';
//     const darkWordmark = '<?= $app_info["app_wordmark_dark_url"] ?>';
//     const lightLogo = '<?= $app_info["app_logo_url"] ?>';
//     const darkLogo = '<?= $app_info["app_logo_dark_url"] ?>';
//     const lightBanner = '<?= $app_info["signup_top_img_url"] ?>';
//     const darkBanner = '<?= $app_info["signup_top_img_dark_url"] ?>';
//
//     if (topLogo) {
//     topLogo.style.backgroundImage = `url('${isDark ? darkWordmark : lightWordmark}')`;
// }
//
//     if (appLogo) {
//     appLogo.style.backgroundImage = `url('${isDark ? darkLogo : lightLogo}')`;
// }
//
//     if (signupBanner) {
//     signupBanner.style.backgroundImage = `url('${isDark ? darkBanner : lightBanner}')`;
// }
// });




