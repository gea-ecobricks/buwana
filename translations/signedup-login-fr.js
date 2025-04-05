

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
    "100-login-heading-signed-up": "Votre compte est prêt ! 🎉",
    "101-login-subheading-signed-up": "maintenant, veuillez utiliser votre <?php echo $credential_type; ?> pour vous connecter pour la première fois afin de commencer à configurer votre compte :",
    "000-your": "Votre",
    "000-your-password": "Votre mot de passe :",
    "000-forgot-your-password": 'Mot de passe oublié ? <a href="#" onclick="showModalInfo(\'reset\')" class="underline-link">Réinitialisez-le.</a>',
    "000-password-wrong": "👉 Le mot de passe est incorrect.",
    "000-no-account-yet": 'Vous n\'avez pas encore de compte ? <a href="signup.php">Inscrivez-vous !</a>'
};
