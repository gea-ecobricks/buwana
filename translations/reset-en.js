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

// Extracted data-lang-id texts and HTML for translation

const en_Page_Translations = {
    // 001 - Reset Title
    "001-reset-title": "Let's Reset Your Password",

    // 002 - Reset Subtitle
    "002-reset-subtitle": "Enter your new password for your Buwana account.",

    // 003 - New Password Label
    "003-new-pass": "New password:",

    // 004 - Password Field Inner HTML
    "004-password-field": `
        <input type="password" id="password" name="password" required placeholder="Your new password...">
        <span toggle="#password" class="toggle-password" style="cursor: pointer;">🔒</span>
    `,

    // 011 - Six Characters Requirement
    "011-six-characters": "Password must be at least 6 characters long.",

    // 012 - Re-enter Password Label
    "012-re-enter": "Re-enter password to confirm:",

    // 013 - Password Wrapper Inner HTML
    "013-password-wrapper": `
        <input type="password" id="confirmPassword" name="confirmPassword" required placeholder="Re-enter password...">
        <span toggle="#confirmPassword" class="toggle-password" style="cursor: pointer;">🔒</span>
    `,

    // 013 - Password Match Error
    "013-password-match": "👉 Passwords do not match.",

    // 015 - No Need to Reset
    "015-no-need": 'No need to reset your password?  <a href="login.php">Login</a>',
};

