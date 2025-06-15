/*-----------------------------------
TEXT TRANSLATION SNIPPETS FOR GOBRIK.com
-----------------------------------*/

// Ampersand (&): Should be escaped as &amp; because it starts HTML character references.
// Less-than (<): Should be escaped as &lt; because it starts an HTML tag.
// Greater-than (>): Should be escaped as &gt; because it ends an HTML tag.
// Double quote ("): Should be escaped as &quot; when inside attribute values.
// Single quote/apostrophe ('): Should be escaped as &#39; or &apos; when inside attribute values.
// Backslash (\\): Should be escaped as \\ in JavaScript strings to prevent ending the string prematurely.
// Forward slash (/): Should be escaped as \/ in </script> tags to prevent prematurely closing a script.

const de_Page_Translations = {

    "001-cant-find": "🤔 Wir können diese Zugangsdaten in der Datenbank nicht finden.",
    "002-password-is-wrong": "👉 Passwort ist falsch.",
    "003-forgot-your-password": "Passwort vergessen?",
    "000-reset-it": "Setze es zurück.",
    "003-code-status": "Ein Code zum Einloggen wird an deine E-Mail gesendet.",
    "004-login-button": '<input type="submit" id="submit-password-button" value="Anmelden" class="login-button-75">',
    "005-password-field-placeholder": '<input type="password" id="user_password" name="password" required placeholder="Dein Passwort...">'
};

