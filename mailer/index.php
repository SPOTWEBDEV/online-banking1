<?php

require "PHPMailer/PHPMailerAutoload.php";


function smtpmailer($to,$subject, $body)
{
        global $host , $emailpassword;
         $mail = new PHPMailer();
         $mail->IsSMTP();
         $mail->SMTPAuth = true;

         $mail->SMTPSecure = 'ssl'; // Using 'ssl' with port 465 as per your original configuration
         $mail->Host = 'mail.zentrapb.com';
         $mail->Port = 465; // Or 587 if using 'tls'
         $mail->Username = 'support@zentrapb.com';
         $mail->Password = 'support@zentrapb.com'; // Use your actual email password

         $mail->IsHTML(true);
         $mail->From = 'support@zentrapb.com';
         $mail->FromName = 'Zentra Bank';
         $mail->Sender = 'support@zentrapb.com';
         $mail->AddReplyTo('support@zentrapb.com', 'Zentra Bank');
         $mail->Subject = $subject;
         $mail->Body = $body;
         $mail->AddAddress($to);

         // Enable SMTP debugging
         // $mail->SMTPDebug = 2; // 0 = off, 1 = client messages, 2 = client and server messages
         // $mail->Debugoutput = 'html'; // Output format for debugging

         if (!$mail->Send()) {
                  // Log error or handle failure
                  error_log('Email sending failed: ' . $mail->ErrorInfo);
                  return false;
         }

         return 'success';
}



?>