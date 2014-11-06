<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 10:18
 */

namespace StoreIncentives;


require "../vendor/phpmailer/phpmailer/PHPMailerAutoload.php";

$payload = file_get_contents("http://www.saudifashionmagazine.com/?ACT=78&hash=UpH8bC2nI8yXsWRX5Hg4GDmKGXoMTDOI&batch=yes");

echo $payload;

if ($payload !== "No Batches to Process") {

    $mail = new \PHPMailer();
    $mail->isSendmail();
    $mail->From = 'data@saudifashionmagazine.com';
    $mail->FromName = 'Store Incentive';
    $mail->addAddress('mike@smswmedia.com');
    $mail->Subject = 'A batch process has been started';
    $mail->isHTML();
    $mail->Body = $payload;

    $mail->send();

    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message sent!";
    }
}
