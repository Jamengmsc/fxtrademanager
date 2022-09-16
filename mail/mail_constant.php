<?php
   include "phpmailer/PHPMailerAutoload.php";
   // include "../config/constants.php";
   
   $mail = new PHPMailer;

   $mail->isSMTP(); //Enable this when using on localhost server and otherwise of live server
   $mail->Host = 'smtp.gmail.com';
   $mail->Port = 587;
   $mail->SMTPAuth = true;
   $mail->SMTPSecure = "tls";

   // Sending Email details
   $mail->Username = EMAIL;
   $mail->Password = EMAILPASS;

   $mail->setFrom(EMAIL, "FxTrade");
   $mail->addReplyTo(EMAIL);
   $mail->isHTML(true);
   
?>