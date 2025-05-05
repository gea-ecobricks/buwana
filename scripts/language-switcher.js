


/* ---------- ------------------------------

EARTHEN LANGUAGE SELECTOR v2.0

-------------------------------------------*/

function switchLanguage(langCode) {
    window.currentLanguage = langCode;

    let currentTranslations = {};
    switch (langCode) {
        case 'en':
            currentTranslations = {...en_Translations, ...en_Page_Translations};
            break;
        case 'fr':
            currentTranslations = {...fr_Translations, ...fr_Page_Translations};
            break;
        case 'es':
            currentTranslations = {...es_Translations, ...es_Page_Translations};
            break;
        case 'id':
            currentTranslations = {...id_Translations, ...id_Page_Translations};
            break;
        case 'ar':
            currentTranslations = {...ar_Translations, ...ar_Page_Translations};
            break;
        case 'zh':
            currentTranslations = {...zh_Translations, ...zh_Page_Translations};
            break;
        case 'de':
            currentTranslations = {...de_Translations, ...de_Page_Translations};
            break;
        default:
            console.warn(`No translations found for language: ${langCode}`);
            return;
    }

    // RTL Support
    const rtlLanguages = ['ar', 'he', 'fa', 'ur'];
    const formContainer = document.getElementById('form-submission-box');
    if (formContainer) {
        formContainer.setAttribute('dir', rtlLanguages.includes(langCode) ? 'rtl' : 'ltr');
    }

    const elements = document.querySelectorAll('[data-lang-id]');
    elements.forEach(element => {
        const langId = element.getAttribute('data-lang-id');
        const translation = currentTranslations[langId];
        if (translation) {
            if (element.tagName.toLowerCase() === 'input' && element.type !== 'submit') {
                element.placeholder = translation;
            } else if (element.hasAttribute('aria-label')) {
                element.setAttribute('aria-label', translation);
            } else if (element.tagName.toLowerCase() === 'img') {
                element.alt = translation;
            } else {
                element.innerHTML = translation;
            }
        }
    });
}



function loadTranslationScripts(lang, page, callback) {

    let loadedScripts = 0;

    function scriptLoaded() {
        loadedScripts++;
        if (loadedScripts === 2) {
            callback(); // Both scripts loaded
        }
    }

    const coreScript = document.createElement('script');
    coreScript.src = `../translations/core-texts-${lang}.js?v=${version}`;
    coreScript.onload = scriptLoaded;
    document.head.appendChild(coreScript);

    const pageScript = document.createElement('script');
    pageScript.src = `../translations/${page}-${lang}.js?v=${version}`;
    pageScript.onload = scriptLoaded;
    document.head.appendChild(pageScript);
}






function clearSiteCache() {
    // Translations for the confirm message
    const confirmMessages = {
        en: 'Do you wish to delete your language and site settings for ecobricks.org?',
        fr: 'Souhaitez-vous supprimer vos paramètres de langue et de site pour ecobricks.org?',
        es: '¿Desea eliminar la configuración de idioma y sitio para ecobricks.org?',
        id: 'Apakah Anda ingin menghapus pengaturan bahasa dan situs untuk ecobricks.org?'
    };

    // Translations for the alert message
    const alertMessages = {
        en: 'All site cache items are now cleared.',
        fr: 'Tous les éléments du cache du site sont maintenant effacés.',
        es: 'Todos los elementos en caché del sitio ahora están borrados.',
        id: 'Semua item cache situs sekarang dibersihkan.'
    };

    // Check the currentLanguage global variable, default to 'en' if undefined
    const language = window.currentLanguage || 'en';

    // Use confirm message based on currentLanguage or default to English
    const confirmMessage = confirmMessages[language] || confirmMessages.en;

    // Show confirm dialog
    if (confirm(confirmMessage)) {
        // Clear local storage if user confirms
        localStorage.clear();

        // Use alert message based on currentLanguage or default to English
        const alertMessage = alertMessages[language] || alertMessages.en;

        // Show alert dialog
        alert(alertMessage);
    }
}
