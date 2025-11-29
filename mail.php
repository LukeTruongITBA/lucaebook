<?php

require "config/config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'quockhanhcrusher@gmail.com';                     //SMTP username
        $mail->Password   = 'wvdw vlkj lskc bsal';                               //SMTP password
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($email, $fname . ' ' . $lname); // Sender's name and email from the form
        $mail->addAddress('lucaebook.contact@gmail.com', 'Bookstore Contact');     // Add a recipient (your store's email)

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = "Dear Bookstore Contact,<br><br>";
        $mail->Body   .= "You have received a new message from the contact form on your website.<br><br>";
        $mail->Body   .= "<b>Name:</b> " . $fname . " " . $lname . "<br>";
        $mail->Body   .= "<b>Email:</b> " . $email . "<br>";
        $mail->Body   .= "<b>Subject:</b> " . $subject . "<br>";
        $mail->Body   .= "<b>Message:</b><br>" . nl2br($message);
        $mail->AltBody = "Name: " . $fname . " " . $lname . "\nEmail: " . $email . "\nSubject: " . $subject . "\nMessage: " . $message;

        $mail->send();
        header("location: success.php"); // Redirect to a success page
        exit;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {" . $mail->ErrorInfo . "}";
    }
} else {
    header("location: contact.php"); // Redirect if accessed directly
    exit;
}

?>