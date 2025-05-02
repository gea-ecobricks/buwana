


/* ---------- ------------------------------

EARTHEN LANGUAGE SELECTOR v2.0

-------------------------------------------*/

function switchLanguage(langCode) {
    window.currentLanguage = langCode;

    const languageMappings = {
        'en': {...en_Translations, ...en_Page_Translations},
        'fr': {...fr_Translations, ...fr_Page_Translations},
        'es': {...es_Translations, ...es_Page_Translations},
        'id': {...id_Translations, ...id_Page_Translations},
        'ar': {...ar_Translations, ...ar_Page_Translations},
        'cn': {...cn_Translations, ...cn_Page_Translations},
        'de': {...de_Translations, ...de_Page_Translations},
    };

    if (!languageMappings[currentLanguage]) {
        console.warn(`No translations found for language: ${currentLanguage}`);
        return;
    }

    const currentTranslations = languageMappings[currentLanguage];

    // RTL Support
    const rtlLanguages = ['ar', 'he', 'fa', 'ur'];
    document.documentElement.setAttribute(
        'dir',
        rtlLanguages.includes(currentLanguage) ? 'rtl' : 'ltr'
    );

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
                element.textContent = translation; // use textContent to avoid injection
            }
        }
    });
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
