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
    "001-log-an-ecobrick": "➕ Enregistrer une écobrique",
    "002-my-ecobricks": "Mes Écobriques",
    "1103-brik": "Brique",
    "1104-weight": "Poids",
    "1105-location": "Emplacement",
    "1106-status": "Statut",
    "1107-serial": "Série",
    "003-no-ecobricks-yet": "Il semble que vous n'ayez pas encore enregistré d'écobriques! Lorsque vous le ferez, elles apparaîtront ici pour que vous puissiez les gérer.",
    "005-newest-ecobricks": "📅 Écobriques les plus récentes",
    "welcomeBeta": `Bienvenue sur le nouveau GoBrik 3.0! Merci de nous aider avec le test bêta. Pas besoin de tester d'autres fonctionnalités car nous travaillons encore sur tout. Veuillez enregistrer votre expérience et tout bug sur notre <a href="https://forms.gle/4tYxvrMYYk5iohyN7" target="_blank">formulaire de revue Google</a>.`,
    "loggedEcobricks": `Jusqu'à présent, vous avez enregistré {ecobricksMade} écobriques à {locationFullTxt}! Au total, vous avez enregistré {totalWeight} grammes avec une densité nette de {netDensity} g/ml.`
};
