<?php

namespace MaxFW\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailController
{  

    public function sendEmail(array $to, array $content )
    {    
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = MAIL_HOST;  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = MAIL_USERNAME;                 // SMTP username
            $mail->Password = MAIL_PASSWORD;                           // SMTP password
            $mail->SMTPSecure = SMTPSecure;                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = MAIL_PORT;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom(MAIL_FROM, NAME);
            $mail->addAddress($to['email'], $to['name']);     // Add a recipient
            $mail->addReplyTo(MAIL_REPLY, NAME);            
            
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $content['subject'];
            $mail->Body    = $content['body'];
            $mail->AltBody = $content['alt'];

            $mail->send();
            return TRUE;
        } catch (Exception $e) {
            $this->mail_error =  'Mensagem não enviada' . $mail->ErrorInfo;
            return FALSE;
        }
    }

}