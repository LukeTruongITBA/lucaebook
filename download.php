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

    // Fetch the customer's name from their latest order
    $order_query = $conn->prepare("SELECT fname, lname FROM orders WHERE user_id = :user_id ORDER BY id DESC LIMIT 1");
    $order_query->execute([':user_id' => $_SESSION['user_id']]);
    $order_data = $order_query->fetch(PDO::FETCH_OBJ);
    $customer_name = $_SESSION['username']; // Default to username
    if ($order_data) {
        $customer_name = $order_data->fname . ' ' . $order_data->lname;
    }

    $total_price_calculated = $_SESSION['price'];


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
            $path  = 'admin-panel/products-admins/images';
            //$file = $products->pro_file;

            for($i=0; $i < count($allProdcuts); $i++) {
            
                $mail->addAttachment($path . "/" . $products->pro_image);         //Add attachments

            }
        }


        
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Your Luca\'s E-books Order Confirmation';
        
        $email_html_content = "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Your Luca\'s E-books Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f0f2f5;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .header .brand-name {
            color: #BBC863;
            font-size: 2.5rem;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 0.8em;
            color: #777;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .products-table th, .products-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .products-table th {
            background-color: #f2f2f2;
        }
        h1 {
            color: #BBC863;
        }
    </style>
</head>
<body>
    <div class=\"container\">
        <div class=\"header\">
            <h1 class=\"brand-name\">Luca's E-books</h1>
            <h1>Thank You for Your Order!</h1>
        </div>
        <div class=\"content\">
            <p>Dear {customer_name},</p>
            <p>Thank you for your recent purchase from Luca's E-books. We've attached your e-books to this email. Here are the details of your order:</p>
            
            <h3>Order Summary</h3>
            <p><strong>Total Price:</strong> {total_price}</p>
            
            <h3>Your E-books</h3>
            {product_list}
            
            <p>We hope you enjoy your new books! If you have any questions, please don't hesitate to contact us.</p>
            
            <p>Happy reading!</p>
            <p><strong>The Luca's E-books Team</strong></p>
        </div>
        <div class=\"footer\">
            <p>&copy; 2025 Luca's E-books. All rights reserved.</p>
        </div>
    </div>
</body>
</html>";
        
        $product_list = '<ul>';
        foreach($allProdcuts as $product) {
            $product_list .= '<li>' . $product->pro_name . '</li>';
        }
        $product_list .= '</ul>';
        
        $email_html_content = str_replace('{customer_name}', $customer_name, $email_html_content);
        $email_html_content = str_replace('{total_price}', number_format($total_price_calculated, 2) . '$', $email_html_content);
        $email_html_content = str_replace('{product_list}', $product_list, $email_html_content);
        
        $mail->Body = $email_html_content;

        $mail->send();

        //delete cart items after sending products
        $select = $conn->query("DELETE FROM cart WHERE user_id='$_SESSION[user_id]'");
        $select->execute();

        header("location: success.php");

        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }


