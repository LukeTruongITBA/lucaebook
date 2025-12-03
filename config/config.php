<?php 



    //host
    $host = "localhost";

    //dbname

    $dbname = "Bookstore";

    //username

    $user = "root";

    //password

    $pass = "";

    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $secret_key = "sk_test_51M94h5Hp65tXrQ3P4wJRttxoeJDyxOXhUc1p402Oc4fbmtzTK1VZ3yDF5kJKIty9Sf1ygA7WDevSht8Ba88SbSsk00GwlnUHF9";
<<<<<<< HEAD

=======
>>>>>>> 9eb523dc8a93c3e9ff63998fcf39ad87edc8007a


    // if($conn) {
    //     echo "worked successfully";
    // } else {
    //     echo "error in db connection";
    // }
