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

    "002-bug-report-subtitle": "GoBrik 3.0 has just launched. Help us catch all the bugs by reporting any problems you encounter. Messages go to our volunteer development team. Attach a screen shot if necessary.📸",
    "003-bug-form": '<textarea id="bugReportInput" placeholder="What went wrong? Or... what could be better?" rows="6" required></textarea><input type="file" id="imageUploadInput" accept="image/jpeg, image/jpg, image/png, image/webp" style="display: none;" /><span id="imageFileName" class="image-file-name"></span><button type="button" id="uploadPhotoButton" class="upload-photo-button" title="Upload Photo" aria-label="Upload Photo">📷</button>',
    "004-bug-submit": "🐞 Submit Bug",
    "005-report-info": "When you send a bug report your message and details on your browser and OS will be sent to our development team over GoBrik messenger.",

     "thanks-title": "Bug report submitted successfully.",
        "thanks-message": "Thank you for taking the time to make GoBrik better for everyone.",




    };
