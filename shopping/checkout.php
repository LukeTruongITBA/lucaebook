<?php require  "../includes/header.php"; ?>
<?php require  "../config/config.php"; ?> 

<?php 

  if(!isset($_SERVER['HTTP_REFERER'])){
    // redirect them to your desired location
    header('location: cart.php');
    exit;
  }


  if(!isset($_SESSION['user_id'])) {
    header("location: ".APPURL."");
  }

  // Fetch user data for autofill
  $user_name = '';
  $user_email = '';
  if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = $conn->prepare("SELECT username, email FROM users WHERE id = :id");
    $query->execute([':id' => $user_id]);
    $user_data = $query->fetch(PDO::FETCH_OBJ);
    if($user_data) {
      $user_name = $user_data->username;
      $user_email = $user_data->email;
    }
  }

?>
      <!-- Heading -->
      
      <div class="container">
        <h2 class="my-5 h2 text-center text-white">Checkout</h2>

        <!--Grid row-->
        <div class="row d-flex justify-content-center align-items-center h-100 mt-5 mt-5">
  
          <!--Grid column-->
          <div class="col-md-12 mb-4">
  
            <!--Card-->
            <div class="card glass-card">
  
              <!--Card content-->
              <form class="card-body" method="POST" action="charge.php">
  
                <!--Grid row-->
                <div class="row">
  
                  <!--Grid column-->
                  <div class="col-md-6 mb-2">
  
                    <!--firstName-->
                    <div class="md-form">
                      <label for="firstName" class="text-white">First name</label>
  
                      <input type="text" name="fname" id="firstName" class="form-control form-control-lg">
                    </div>
  
                  </div>
                  <!--Grid column-->
  
                  <!--Grid column-->
                  <div class="col-md-6 mb-2">
  
                    <!--lastName-->
                    <div class="md-form">
                      <label for="lastName" class="text-white">Last name</label>
  
                      <input type="text"  name="lname" id="lastName" class="form-control form-control-lg">
                    </div>
  
                  </div>
                  <!--Grid column-->
  
                </div>
                <!--Grid row-->
  
                <!--Username-->
                <div class="md-form mb-5">
                  <label for="email" class="text-white">Username</label>
  
                  <input type="text"  name="username" class="form-control form-control-lg" placeholder="Username" aria-describedby="basic-addon1" value="<?php echo htmlspecialchars($user_name); ?>" readonly>
                </div>
  
                <!--email-->
                <div class="md-form mb-5">
                  <label for="email" class="text-white">Email</label>
  
                  <input type="text" name="email" id="email" class="form-control form-control-lg" placeholder="youremail@example.com" value="<?php echo htmlspecialchars($user_email); ?>" readonly>
                </div>
  
               
                <!--Grid row-->
  
              
                <hr class="mb-4">
                <script
                  src="https://checkout.stripe.com/checkout.js"
                  class="stripe-button"
                  data-key="pk_test_51M94h5Hp65tXrQ3PiYbpcRZmY8t09IMUrVwgrDjGlOXUJiGpK09MhKEAjzqZ2rBn13M46Hquv1fPneDRRMesw9AW00ws2aTeJC"
                  data-email="<?php echo htmlspecialchars($user_email); ?>"
                  data-currency="usd"
                  data-label="pay now"
                >  
                </script>
  
              </form>
  
            </div>
           
          </div>
  </div>
      </div>
<?php require "../includes/footer.php"; ?>    