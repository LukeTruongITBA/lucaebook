<?php 

    session_start();


    define("APPURL", "http://localhost/bookstore");
    define("IMGURL", "http://localhost/bookstore/admin-panel/products-admins/images");
    define("LOGOURL", "http://localhost/bookstore/logo.png");
   
    require dirname(dirname(__FILE__)) . "/config/config.php";

    if(isset($_SESSION['user_id'])) {
        $number = $conn->query("SELECT COUNT(*) as num_products FROM cart WHERE user_id='$_SESSION[user_id]'");
        $number->execute();
    
        $getNumber = $number->fetch(PDO::FETCH_OBJ);
    
    }
  




?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="<?php echo APPURL; ?>/styles/theme.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/5c5946fe44.js" crossorigin="anonymous"></script>

    <title>Luca's E-Books</title>
    <link rel="icon" href="<?php echo LOGOURL; ?>" type="image/png">
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent shadow-lg site-navbar" >
        <div class="container" style="margin-top: none">
            <a class="navbar-brand site-brand" href="<?php echo APPURL; ?>">
                <img src="<?php echo LOGOURL; ?>" alt="Bookstore Logo" style="height: 150px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form> -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link active text-dark" aria-current="page" href="<?php echo APPURL; ?>">Home</a>
                </li>
                <li class="nav-item ">
                <a class="nav-link text-dark" href="<?php echo APPURL; ?>/contact.php">Contact</a>
                </li>
                <li class="nav-item">
                <a class="nav-link active text-dark" aria-current="page" href="<?php echo APPURL; ?>/categories/index.php">Categories</a>
                </li>
    
                <?php if(isset($_SESSION['username'])) : ?>
    
                    <li class="nav-item">
                    <a class="nav-link active text-dark" aria-current="page" href="<?php echo APPURL; ?>/shopping/cart.php"><i class="fas fa-shopping-cart"></i>(<?php echo $getNumber->num_products; ?>)</a>
                    </li>
                
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo $_SESSION['username']; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?php echo APPURL; ?>/users/orders.php?id=<?php echo $_SESSION['user_id']; ?>">Orders</a></li>
                        <li><a class="dropdown-item" href="<?php echo APPURL; ?>/users/wishlist-user.php?id=<?php echo $_SESSION['user_id']; ?>">Wishlist</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo APPURL; ?>/auth/logout.php">Logout</a></li>
                    </ul>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?php echo APPURL; ?>/auth/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?php echo APPURL; ?>/auth/register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
           
            </div>
        </div>
    </nav>

    <div class="container main-content">  
