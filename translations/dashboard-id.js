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


const id_Page_Translations = {
    "001-log-an-ecobrick": "➕ Catat Ecobrick",
    "002-my-ecobricks": "Ecobrick Saya",
    "1103-brik": "Bata",
    "1104-weight": "Berat",
    "1105-location": "Lokasi",
    "1106-status": "Status",
    "1107-serial": "Seri",
    "003-no-ecobricks-yet": "Sepertinya Anda belum mencatat ecobrick apapun! Setelah Anda melakukannya, ecobrick akan muncul di sini untuk Anda kelola.",
    "005-newest-ecobricks": "📅 Ecobrick Terbaru",
    "welcomeBeta": `Selamat datang di GoBrik 3.0 baru! Terima kasih telah membantu dengan pengujian beta. Tidak perlu menguji fitur lain karena kami masih mengerjakan semuanya. Silakan catat pengalaman Anda dan setiap bug di <a href="https://forms.gle/4tYxvrMYYk5iohyN7" target="_blank">formulir ulasan Google kami</a>.`,
    "loggedEcobricks": `Sejauh ini Anda telah mencatat {ecobricksMade} ecobrick di {locationFullTxt}! Secara total Anda telah mencatat {totalWeight} gram dengan kepadatan bersih {netDensity} g/ml.`
};

