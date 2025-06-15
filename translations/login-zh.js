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

const zh_Page_Translations = {
    logout: {
        main: "您已登出。",
        sub: "准备好后，$first_name，请再次使用您的帐户凭据登录。"
    },
    firsttime: {
        main: "您的 Buwana 账户已创建！🎉",
        sub: "您的 Earthen 订阅已确认。现在 $first_name，请使用新的账户凭据再次登录。"
    },
    connected: {
        main: "您现在已准备好使用 $app_display_name",
        sub: "$first_name，您的 Buwana 账户现在可以用于登录 $app_display_name"
    },
    default: {
        main: "欢迎回来！",
        sub: "请再次使用您的账户凭据登录。"
    },

    "001-cant-find": "🤔 数据库中找不到此凭证。",
    "002-password-is-wrong": "👉 密码错误。",
    "003-forgot-your-password": "忘记密码了吗？",
    "000-reset-it": "重设密码。",
    "003-code-status": "登录代码将发送到你的电子邮件。",
    "004-login-button": '<input type="submit" id="submit-password-button" value="登录" class="login-button-75">',
    "005-password-field-placeholder": '<input type="password" id="password" name="password" required placeholder="你的密码...">'
};

