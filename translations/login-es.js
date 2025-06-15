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
    logout: {
        main: "Has cerrado sesión.",
        sub: "Cuando estés listo $first_name, vuelve a iniciar sesión con tus credenciales."
    },
    firsttime: {
        main: "¡Tu cuenta Buwana está creada! 🎉",
        sub: "Y tus suscripciones Earthen están confirmadas. Ahora $first_name, inicia sesión de nuevo con tus nuevas credenciales."
    },
    connected: {
        main: "Ahora estás listo para usar $app_display_name",
        sub: "$first_name, tu cuenta Buwana ahora puede usarse para iniciar sesión en $app_display_name"
    },
    default: {
        main: "¡Bienvenido de nuevo!",
        sub: "Por favor, inicia sesión nuevamente con tus credenciales."
    },

    "001-cant-find": "🤔 No podemos encontrar esta credencial en la base de datos.",
    "002-password-is-wrong": "👉 La contraseña es incorrecta.",
    "003-forgot-your-password": "¿Olvidaste tu contraseña?",
    "000-reset-it": "Restablécela.",
    "003-code-status": "Un código para iniciar sesión será enviado a tu correo electrónico.",
    "004-login-button": '<input type="submit" id="submit-password-button" value="Iniciar sesión" class="login-button-75">',
    "005-password-field-placeholder": '<input type="password" id="password" name="password" required placeholder="Tu contraseña...">'
};

