<?php
/**
 * @var string $recipient
 * @var string $password
 */
?>
<tr>
    <td class="email-body" width="100%" cellpadding="0" cellspacing="0"
        style="word-break: break-word; margin: 0; padding: 0; font-family: &quot;Nunito Sans&quot;, Helvetica, Arial, sans-serif; font-size: 16px; width: 100%; -premailer-width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; background-color: #FFFFFF;"
        bgcolor="#FFFFFF">
        <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation"
               style="width: 570px; -premailer-width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; background-color: #FFFFFF; margin: 0 auto; padding: 0;"
               bgcolor="#FFFFFF">
            <!-- Body content -->
            <tr>
                <td class="content-cell"
                    style="word-break: break-word; font-family: &quot;Nunito Sans&quot;, Helvetica, Arial, sans-serif; font-size: 16px; padding: 35px;">
                    <div class="f-fallback">
                        <p style="margin-top: 0; color: #333333; font-size: 22px; font-weight: bold; text-align: left;">
                            Dear <?= $recipient ?>,
                        </p>

                        <p style="font-size: 16px; line-height: 1.625; color: #51545E; margin: .4em 0 1.1875em;">
                            A password change request has been issued for your account.
                        </p>

                        <p style="font-size: 16px; line-height: 1.625; color: #51545E; margin: .4em 0 1.1875em;">
                            Your new password is <strong> <?= $password ?> </strong>
                        </p>

                        <p style="font-size: 16px; line-height: 1.625; color: #51545E; margin: .4em 0 1.1875em;">
                            Remember to change this password after you log in.
                        </p>

                        <p style="font-size: 16px; line-height: 1.625; color: #51545E; margin: .4em 0 1.1875em;">
                            <strong>This is an autogenerated message. Please don't reply to it.</strong>
                        </p>
                    </div>
                </td>
            </tr>
        </table>
    </td>
</tr>
