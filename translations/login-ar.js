/*-----------------------------------
TEXT TRANSLATION SNIPPETS FOR GOBRIK.com
-----------------------------------*/

// Ampersand (&): Should be escaped as &amp; because it starts HTML character references.
// Less-than (<): Should be escaped as &lt; because it starts an HTML tag.
// Greater-than (>): Should be escaped as &gt; because it ends an HTML tag.
// Double quote ("): Should be escaped as &quot; when inside attribute values.
// Single quote/apostrophe ('): Should be escaped as &#39; or &apos; when inside attribute values.
// Backslash (\\): Should be escaped as \\ in JavaScript strings to prevent ending the string prematurely.
// Forward slash (/): Should be escaped as \/ in </script> tags to prevent prematurely closing a script.

const ar_Page_Translations = {
    logout: {
        main: "لقد تم تسجيل خروجك.",
        sub: "عندما تكون جاهزًا $first_name، سجّل الدخول مرة أخرى باستخدام بيانات اعتماد حسابك."
    },
    firsttime: {
        main: "تم إنشاء حساب بوانا الخاص بك! 🎉",
        sub: "وتم تأكيد اشتراكاتك في Earthen. الآن $first_name، الرجاء تسجيل الدخول مرة أخرى باستخدام بيانات اعتماد حسابك الجديدة."
    },
    connected: {
        main: "أصبح بإمكانك الآن استخدام $app_display_name",
        sub: "$first_name، يمكن الآن استخدام حساب بوانا الخاص بك لتسجيل الدخول إلى $app_display_name"
    },
    default: {
        main: "مرحبًا بعودتك!",
        sub: "يرجى تسجيل الدخول مرة أخرى باستخدام بيانات اعتماد حسابك."
    },

    "001-cant-find": "🤔 لا يمكننا العثور على بيانات الاعتماد هذه في قاعدة البيانات.",
    "002-password-is-wrong": "👉 كلمة المرور خاطئة.",
    "003-forgot-your-password": "هل نسيت كلمة المرور؟",
    "000-reset-it": "أعد تعيينها.",
    "003-code-status": "سيتم إرسال رمز لتسجيل الدخول إلى بريدك الإلكتروني.",
    "004-login-button": '<input type="submit" id="submit-password-button" value="تسجيل الدخول" class="login-button-75">',
    "005-password-field-placeholder": '<input type="password" id="password" name="password" required placeholder="كلمة مرورك...">'
};

