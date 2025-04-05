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
    "001-log-an-ecobrick": "➕ Registrar un Ecoladrillo",
    "002-my-ecobricks": "Mis Ecoladrillos",
    "1103-brik": "Ladrillo",
    "1104-weight": "Peso",
    "1105-location": "Ubicación",
    "1106-status": "Estado",
    "1107-serial": "Serial",
    "003-no-ecobricks-yet": "¡Parece que aún no has registrado ningún ecoladrillo! Cuando lo hagas, aparecerán aquí para que los administres.",
    "005-newest-ecobricks": "📅 Ecoladrillos más nuevos",
    "welcomeBeta": `¡Bienvenido al nuevo GoBrik 3.0! Gracias por ayudar con las pruebas beta. No es necesario probar ninguna otra función ya que todavía estamos trabajando en todo. Por favor, registre su experiencia y cualquier error en nuestro <a href="https://forms.gle/4tYxvrMYYk5iohyN7" target="_blank">formulario de revisión de Google</a>.`,
    "loggedEcobricks": `Hasta ahora has registrado {ecobricksMade} ecoladrillos en {locationFullTxt}! En total has registrado {totalWeight} gramos con una densidad neta de {netDensity} g/ml.`
};

