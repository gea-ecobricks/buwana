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
    "001-signup-heading": "Create Your Account",
    "002-signup-subtext": " uses the Buwana Authentication protocol— a powerful and private, opensource and for-Earth protocol that powers regenerative apps.",
    "003-firstname": "What's your first name?",
    "000-name-field-too-long-error": "The name is too long. Max 255 characters.",
    "005b-name-error": "The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.",
    "006-credential-choice": "Select how you register...",
    "007-way-to-contact": "We'll send your account confirmation messages this way.  Later you'll login this way.",
    "016-submit-to-password": "Next",

/* Next page: Signup-2 - Set your name and email  */

 "001-setup-access-heading": "Setup Your Access",
    "002-setup-access-heading-a": "let's use your ",
    "003-setup-access-heading-b": "as your means of registration and the way we contact you.",
    "004-your": "Your",
    "004b-please": " please:",
    "010-duplicate-email": "🚧 Whoops! Looks like that e-mail address is already being used by a Buwana Account. Please choose another.",
    "010-gobrik-duplicate": "🌏 It looks like this email is already being used with a legacy GoBrik account. Please <a href=\"login.php\" class=\"underline-link\">login with this email to upgrade your account.</a>",
    "006-email-sub-caption": "💌 This is the way we will contact you to confirm your account",
    "007-set-your-pass": "Set your password:",
    "008-password-advice": "🔑 Your password must be at least 6 characters.",
    "009-confirm-pass": "Confirm Your Password:",
    "010-pass-error-no-match": "👉 Passwords do not match.",
    "011-prove-human": "Please prove you are human by typing the word \"ecobrick\" below:",
    "012-fun-fact": "🤓 Fun fact:",
    "012b-is-spelled": " is spelled without a space, capital or hyphen!",
    "013-by-registering": "By registering today, I agree to the <a href=\"#\" onclick=\"showModalInfo('terms', '<?php echo $lang; ?>')\" class=\"underline-link\">GoBrik Terms of Service</a>",
    "014-i-agree-newsletter": "I agree to receive the <a href=\"#\" onclick=\"showModalInfo('earthen', '<?php echo $lang; ?>')\" class=\"underline-link\">Earthen newsletter</a> for app, ecobrick, and earthen updates",


};

