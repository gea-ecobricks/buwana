

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
    "100-login-heading-signed-up": "¡Tu cuenta está lista! 🎉",
    "101-login-subheading-signed-up": "ahora por favor usa tu <?php echo $credential_type; ?> para iniciar sesión por primera vez y comenzar a configurar tu cuenta:",
    "000-your": "Tu",
    "000-your-password": "Tu contraseña:",
    "000-forgot-your-password": '¿Olvidaste tu contraseña? <a href="#" onclick="showModalInfo(\'reset\')" class="underline-link">Restablécela.</a>',
    "000-password-wrong": "👉 La contraseña es incorrecta.",
    "000-no-account-yet": '¿No tienes una cuenta todavía? <a href="signup-1.php">¡Regístrate!</a>'
};
