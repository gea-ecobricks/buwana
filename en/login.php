<?php

// Generate unsubscribe link dynamically
$unsubscribe_link = isset($recipient_email)
    ? "https://gobrik.com/emailing/unsubscribe.php?email=" . urlencode($recipient_email)
    : "https://earthen.io/unsubscribe/?uuid=611f7d90-e87c-4c43-ab51-0772a7883703&key=c8c3faf87323b6ad7a8b96bcc9f9d742316e82dc604c69de46e524bcb11e3104&newsletter=7bbd5ff6-f69e-4ff0-a9d3-67963d85410b";

// Now embed your full cleaned HTML into a heredoc:

$email_template = <<<HTML
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Solstice, Ayyew & Earthen</title>
    <style>
        /* your full CSS remains exactly as-is (copy full style here) */
        /* For brevity I am not pasting again */
    </style>
</head>
<body style="background-color: #fff; font-family: -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; font-size: 18px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #15212A;">
    <!-- full newsletter content starts here -->

    <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">With the coming of this year’s Solstice...</span>

    <!-- your entire cleaned HTML content here, as you've provided -->

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="footer" width="100%" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; text-align: center; margin-top: 40px; font-size: 13px; color: #73818c;">
        <tr>
            <td style="padding: 20px; font-family: -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif; text-align: center;">
                <p><em>Together we can be the transition to ever increasing harmony with the cycles of life.</em></p>
                <p>Earthen © 2025 – <a href="$unsubscribe_link" style="color: #73818c; text-decoration: underline;">Unsubscribe</a></p>
                <p><a href="https://ghost.org/?via=pbg-newsletter" target="_blank"><img src="https://static.ghost.org/v4.0.0/images/powered.png" width="142" height="30" alt="Powered by Ghost" style="border: none;"></a></p>
            </td>
        </tr>
    </table>

    <!-- tracking pixel -->
    <img width="1" height="1" alt="" src="http://email.earthen.ecobricks.org/o/eJwszkGOgzAMQNHTNLsi2zghWfgwTuxC1DJIgc75Rx11-RdfeiZEKdXggkuETAsgBd-1v-7dJOVYMwByZqIInGlpJbmFTWaoj-qGPDcsRWcmmxHMcvFYvFroQkAR0ueLgDShPVpzT6rq6sY3Btdxbf4zeTvq6O15TsdYw5DxPs9du48bw_rBTO3YwyX1_Xre_3XhknU7zutbv0J_AQAA__9X-j1j">
</body>
</html>
HTML;

?>
