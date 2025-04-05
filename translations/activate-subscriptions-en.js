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
  "001-select-subs": "Select Earthen Subscriptions",
  "002-sub-subtitle": "We share news and notifications by email.",
  "003-get-your": "Get our free Earthen newsletter and GoBrik notifications sent to ",
  "004-later-upgrade": "Later you can upgrade to a paid subscription to support the movement.",
  "005-nice": "Nice! You're already subscribed to:",
  "006-choose": "Choose to add or remove subscriptions below:",
  "007-not-subscribed": "You're not yet subscribed to any Earthen newsletters yet. All are free with upgrade options later. Please select:",
  "009-terms": "Earthen newsletters and GoBrik are sent according to our non-profit, privacy <a href=\"#\" onclick=\"showModalInfo('terms', '<?php echo $lang; ?>')\" class=\"underline-link\">Terms of Service</a>.",
  "008-that-is-it": "That's it!",
  "008b-your-activation-complete": "Your Buwana account activation process is complete! Now you can wrap up and login...",
  "016-complete-button": "<input type=\"submit\" id=\"submit-button\" value=\"Finish & Login\" class=\"submit-button enabled\">"
};

