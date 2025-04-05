/*-----------------------------------
TEXT TRANSLATION SNIPPETS FOR GOBRIK.com
-----------------------------------*/

// Ampersand (&): Should be escaped as &amp; because it starts HTML character references.
// Less-than (<): Should be escaped as &lt; because it starts an HTML tag.
// Greater-than (>): Should be escaped as &gt; because it ends an HTML tag.
// Double quote ("): Should be escaped as &quot; when inside attribute values.
// Single quote/apostrophe ('): Should be escaped as &#39; or &apos; when inside attribute values.
// Backslash (\): Should be escaped as \\ in JavaScript strings to prevent ending the string prematurely.
// Forward slash (/): Should be escaped as \/ in </script> tags to prevent prematurely closing a script.


const fr_Page_Translations = {

const fr_Page_Translations = {
    "001-reset-title": "Réinitialisons Votre Mot de Passe",
    "002-reset-subtitle": "Entrez votre nouveau mot de passe pour votre compte Buwana.",
    "003-new-pass": "Nouveau mot de passe:",
    "004-password-field": `
        <input type="password" id="password" name="password" required placeholder="Votre nouveau mot de passe...">
        <span toggle="#password" class="toggle-password" style="cursor: pointer;">🔒</span>
    `,
    "011-six-characters": "Le mot de passe doit comporter au moins 6 caractères.",
    "012-re-enter": "Entrez à nouveau le mot de passe pour confirmer:",
    "013-password-wrapper": `
        <input type="password" id="confirmPassword" name="confirmPassword" required placeholder="Entrez à nouveau le mot de passe...">
        <span toggle="#confirmPassword" class="toggle-password" style="cursor: pointer;">🔒</span>
    `,
    "013-password-match": "👉 Les mots de passe ne correspondent pas.",
    "015-no-need": 'Pas besoin de réinitialiser votre mot de passe ? <a href="login.php">Connexion</a>',
};

