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
    "001-reset-title": "Mari Atur Ulang Kata Sandi Anda",
    "002-reset-subtitle": "Masukkan kata sandi baru untuk akun Buwana Anda.",
    "003-new-pass": "Kata sandi baru:",
    "004-password-field": `
        <input type="password" id="password" name="password" required placeholder="Kata sandi baru Anda...">
        <span toggle="#password" class="toggle-password" style="cursor: pointer;">🔒</span>
    `,
    "011-six-characters": "Kata sandi harus terdiri dari setidaknya 6 karakter.",
    "012-re-enter": "Masukkan kembali kata sandi untuk konfirmasi:",
    "013-password-wrapper": `
        <input type="password" id="confirmPassword" name="confirmPassword" required placeholder="Masukkan kembali kata sandi...">
        <span toggle="#confirmPassword" class="toggle-password" style="cursor: pointer;">🔒</span>
    `,
    "013-password-match": "👉 Kata sandi tidak cocok.",
    "015-no-need": 'Tidak perlu mengatur ulang kata sandi Anda? <a href="login.php">Login</a>',
};

