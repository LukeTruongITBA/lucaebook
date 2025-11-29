<?php require  "../includes/header.php"; ?>
<?php require  "../config/config.php"; ?>        
 <?php 
 
 
    $rows = $conn->query("SELECT products.id AS pro_id, products.name AS pro_name, products.image AS pro_image, products.price AS pro_price FROM wishlist JOIN products ON wishlist.pro_id = products.id WHERE wishlist.user_id='$_SESSION[user_id]'");
    $rows->execute();

    $allRows = $rows->fetchAll(PDO::FETCH_OBJ);
 
 
 ?>      
        <div class="row mt-5">
            <?php if(count($allRows) > 0) : ?>
                <?php foreach($allRows as $product) : ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card product-card">
            <div class="product-card-img-container">
                    <img class="card-img-top" src="<?php echo IMGURL; ?>/<?php echo  $product->pro_image; ?>">
            </div>
                    <div class="card-body product-card-body card-content">
                        <h5><b><?php echo  $product->pro_name; ?></b></h5>
                        <h5>
                            <div class="d-inline highlighted-price">($<?php echo  $product->pro_price; ?>/item)</div>
                        </h5>
                        <div><?php 
                            $description = substr($product->pro_description ?? 'Dive into a captivating narrative that will keep you on the edge of your seat. This masterpiece of literature is a must-read for any book lover.', 0, 200);
                            if (strlen($description) < 100) {
                                $description .= " Dive into a captivating narrative that will keep you on the edge of your seat. This masterpiece of literature is a must-read for any book lover.";
                            }
                            echo $description; 
                        ?></div>
                        <a href="<?php echo APPURL; ?>/shopping/single.php?id=<?php echo  $product->pro_id; ?>" class="btn btn-primary w-100 rounded btn-more"> More <i class="fas fa-arrow-right"></i> </a>
                    </div>
                </div>
            </div>              
                    <br>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="alert alert-success text-white bg-success">there are no wishlist products for now</div>
            <?php endif; ?>

         </div>
       

<?php require  "../includes/footer.php"; ?>        