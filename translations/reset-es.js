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


const es_Page_Translations = {


const es_Page_Translations = {
    "001-reset-title": "Restablezcamos Su Contraseña",
    "002-reset-subtitle": "Ingrese su nueva contraseña para su cuenta Buwana.",
    "003-new-pass": "Nueva contraseña:",
    "004-password-field": `
        <input type="password" id="password" name="password" required placeholder="Su nueva contraseña...">
        <span toggle="#password" class="toggle-password" style="cursor: pointer;">🔒</span>
    `,
    "011-six-characters": "La contraseña debe tener al menos 6 caracteres.",
    "012-re-enter": "Vuelva a ingresar la contraseña para confirmar:",
    "013-password-wrapper": `
        <input type="password" id="confirmPassword" name="confirmPassword" required placeholder="Vuelva a ingresar la contraseña...">
        <span toggle="#confirmPassword" class="toggle-password" style="cursor: pointer;">🔒</span>
    `,
    "013-password-match": "👉 Las contraseñas no coinciden.",
    "015-no-need": '¿No necesita restablecer su contraseña? <a href="login.php">Iniciar sesión</a>',
};

