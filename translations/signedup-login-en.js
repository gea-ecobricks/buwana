

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


const en_Page_Translations = {
    "100-login-heading-signed-up": "Your account is ready! 🎉",
    "101-login-subheading-signed-up": "now please use your <?php echo $credential_type; ?> to login for the first time to start setting up your account:",
    "000-your": "Your",
    "000-your-password": "Your password:",
    "000-forgot-your-password": "Forgot your password? <a href=\"#\" onclick=\"showModalInfo('reset')\" class=\"underline-link\">Reset it.</a>",
    "000-password-wrong": "👉 Password is wrong.",
    "000-no-account-yet": "Don't have an account yet? <a href=\"signup-1.php\">Signup!</a>"
};