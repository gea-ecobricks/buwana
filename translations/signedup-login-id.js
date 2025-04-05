

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
    "100-login-heading-signed-up": "Akun Anda sudah siap! 🎉",
    "101-login-subheading-signed-up": "sekarang silakan gunakan <?php echo $credential_type; ?> Anda untuk masuk pertama kali dan mulai mengatur akun Anda:",
    "000-your": "Anda",
    "000-your-password": "Kata sandi Anda:",
    "000-forgot-your-password": 'Lupa kata sandi Anda? <a href="#" onclick="showModalInfo(\'reset\')" class="underline-link">Atur ulang.</a>',
    "000-password-wrong": "👉 Kata sandi salah.",
    "000-no-account-yet": 'Belum punya akun? <a href="signup.php">Daftar!</a>'
};

