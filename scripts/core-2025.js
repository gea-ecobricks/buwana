


function redirectToAppHome(apphome) {
    window.location.href = apphome;
}


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


document.addEventListener('DOMContentLoaded', () => {
    const settingsButton = document.getElementById('top-settings-button');
    const settingsPanel = document.getElementById('settings-buttons');
    const langMenu = document.getElementById('language-menu-slider');
    const loginMenu = document.getElementById('login-menu-slider');
    const header = document.getElementById('header');

    let settingsOpen = false;

    // 🔄 Update header background and z-index based on menu visibility
    function updateHeaderVisuals() {
        const langVisible = langMenu.classList.contains('menu-slider-visible');
        const loginVisible = loginMenu.classList.contains('menu-slider-visible');

        if (langVisible || loginVisible) {
            header.style.background = 'var(--top-header)';
            header.style.zIndex = '36';

            if (langVisible) {
                langMenu.style.zIndex = '35';
            } else {
                langMenu.style.zIndex = '18'; // reset if not visible
            }

            if (loginVisible) {
                loginMenu.style.zIndex = '35';
            } else {
                loginMenu.style.zIndex = '19'; // reset if not visible
            }

        } else {
            header.style.background = 'none';
            header.style.zIndex = '20';
            langMenu.style.zIndex = '18';
            loginMenu.style.zIndex = '19';
        }
    }

    // 🌐 Hide language selector with a slide-up animation
    function hideLangSelector() {
        if (!langMenu) return;

        if (langMenu.classList.contains('menu-slider-visible')) {
            langMenu.style.maxHeight = '0';
            langMenu.style.overflow = 'hidden';
            langMenu.style.transition = 'max-height 0.4s ease';

            setTimeout(() => {
                langMenu.classList.remove('menu-slider-visible');
                langMenu.style.removeProperty('max-height');
                langMenu.style.removeProperty('overflow');
                langMenu.style.removeProperty('transition');
                updateHeaderVisuals(); // ✅ Update after animation
            }, 400);
        }

        document.removeEventListener('click', documentClickListenerLang);
    }

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
            hideLangSelector();
        } else {
            langMenu.classList.add('menu-slider-visible');
            langMenu.style.maxHeight = '400px'; // or whatever max height fits your menu
            langMenu.style.overflow = 'hidden';
            langMenu.style.transition = 'max-height 0.4s ease';

            document.addEventListener('click', documentClickListenerLang);
            updateHeaderVisuals(); // ✅ Apply background and z-index
        }
    };

    window.hideLangSelector = () => {
        langMenu.classList.remove('menu-slider-visible');
        document.removeEventListener('click', documentClickListenerLang);
        updateHeaderVisuals(); // ✅ Update visuals
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
            hideLoginSelector();
        } else {
            loginMenu.classList.add('menu-slider-visible');
            loginMenu.style.maxHeight = '400px';
            loginMenu.style.overflow = 'hidden';
            loginMenu.style.transition = 'max-height 0.4s ease';

            document.addEventListener('click', documentClickListenerLogin);
            updateHeaderVisuals(); // ✅ Apply background and z-index
        }
    };

    window.hideLoginSelector = () => {
        if (loginMenu.classList.contains('menu-slider-visible')) {
            loginMenu.classList.remove('menu-slider-visible');
            document.removeEventListener('click', documentClickListenerLogin);
            updateHeaderVisuals(); // ✅ Update visuals when hidden
        }
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



function browserBack() {
    window.history.back();
}




    function navigateTo(url) {
    window.location.href = url;
}




/* WATERSHED MODAL  */

function openAboutBuwana() {
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
        <div style="text-align: center;margin:auto;padding:10%;">
            <div class="buwana-word-mark" title="Authentication by Buwana" style="margin: 0 auto 10px auto; width: 220px; height: 50px; background-size: contain; background-repeat: no-repeat; background-position: center;text-align:center;"></div>
            
            <p data-lang-id="3000-about-buwana-description"><strong>Buwana</strong> is a regenerative alternative to corporate login systems, created to serve our global community with privacy, security, and principle. Rather than rely on closed-source platforms like Google or Facebook, Buwana provides an open, not-for-profit account system that enables secure access to our apps — including GoBrik, Ecobricks.org, Open Books, and the Brikcoin Wallet — while respecting user data and ecological values. Designed to hold community, geographical, and impact data, Buwana accounts are transferable across platforms and built for organizations committed to Earth service.</p>
            <div style="text-align: center; margin-top: 20px;width:100%;">
            <a href="https://github.com/gea-ecobricks/buwana" target="_blank" rel="noopener noreferrer" class="kick-ass-submit" style="text-decoration: none; data-lang-id="3001-buwana-on-github">
                🌏 Buwana on GitHub
            </a>
        </div>
        </div>
    `;

    // ✅ Translate the newly injected modal content
    if (window.currentLanguage) {
        switchLanguage(window.currentLanguage);
    }
}
