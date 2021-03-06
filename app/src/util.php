<?php

namespace App\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function makeRandomString($length = 64) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function sendMail($recipient, $mailSubject, $mailBody, $mailAltBody) {   
    $SenderInfo = include '../app/src/send_mail_info.php';
    $mail = new PHPMailer(true);
    try {
        // Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                                // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                           // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                       // Enable SMTP authentication
        $mail->Username   = $SenderInfo['mail_address'];                   // SMTP username
        $mail->Password   = $SenderInfo['mail_password'];                             // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;             // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587;                                        // TCP port to connect to

        //Recipients
        $mail->setFrom($recipient);
        $mail->addAddress($recipient);                                  // Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
      
        /* this is a comment 
        this is also a comment
        */

        // Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');                // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');           // Optional name

        // Content
        $mail->isHTML(true);                                            // Set email format to HTML
        $mail->Subject = $mailSubject;
        $mail->Body    = $mailBody;
        $mail->AltBody = $mailAltBody;

        $mail->send();
        // echo 'Message has been sent';
        return 0;
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return -1;
    }
}