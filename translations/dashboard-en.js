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
    "001-log-an-ecobrick": "➕ Log an Ecobrick",
    "002-my-ecobricks": "My Ecobricks",
    "1103-brik": "Brik",
    "1104-weight": "Weight",
    "1105-location": "Location",
    "1106-status": "Status",
    "1107-serial": "Serial",
    "003-no-ecobricks-yet": "It looks like you haven't logged any ecobricks yet! When you do, they will appear here for you to manage.",
    "005-newest-ecobricks": "📅 Newest Ecobricks",
    "welcomeBeta": `Welcome to the new GoBrik 3.0! Thank you for helping with the beta testing. No need to test any other features as we are still working on everything. Please record your experience and any bugs on our <a href="https://forms.gle/4tYxvrMYYk5iohyN7" target="_blank">google review form</a>.`,
    "loggedEcobricks": `So far you've logged {ecobricksMade} ecobricks. In total you've logged {totalWeight} kg with a net density of {netDensity} g/ml.`
};


