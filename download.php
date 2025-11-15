<?php require  "includes/header.php"; ?>
<?php require  "config/config.php"; ?> 

<?php



    if(!isset($_SESSION['user_id'])) {
        header("location: index.php");
        exit;
    }

    $select = $conn->query("SELECT * FROM cart WHERE user_id='$_SESSION[user_id]'");
    $select->execute();
    $allProdcuts = $select->fetchAll(PDO::FETCH_OBJ);

    $user_query = $conn->query("SELECT email FROM users WHERE id='$_SESSION[user_id]'");
    $user_query->execute();
    $user_email = $user_query->fetch(PDO::FETCH_OBJ)->email;


    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'src/Exception.php';
    require 'src/PHPMailer.php';
    require 'src/SMTP.php';

    //Create an instance; passing `true` enables exceptions
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

        //sender
        $mail->setFrom("quockhanhcrusher@gmail.com", 'Bookstore');

        //Add a recipient
        $mail->addAddress($user_email, 'user');  
    

        foreach($allProdcuts as $products) {
            $path  = 'admin-panel/products-admins/books';
            //$file = $products->pro_file;

            for($i=0; $i < count($allProdcuts); $i++) {
            
                $mail->addAttachment($path . "/" . $products->pro_file);         //Add attachments

            }
        }


        


        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'the books you bought';
        $mail->Body    = 'here are you books you paid '.$_SESSION['price'].'$ <b>thanks for buying from Bookstore</b>';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();

        //delete cart items after sending products
        $select = $conn->query("DELETE FROM cart WHERE user_id='$_SESSION[user_id]'");
        $select->execute();

        header("location: success.php");

        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }


