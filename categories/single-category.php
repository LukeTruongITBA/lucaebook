<?php require  "../includes/header.php"; ?>
<?php require  "../config/config.php"; ?>        
<?php 
 
    if(isset($_GET['id'])) {

        $id = $_GET['id'];
        
        $rows = $conn->query("SELECT * FROM products WHERE status = 1 AND category_id = '$id'");
        $rows->execute();

        $allRows = $rows->fetchAll(PDO::FETCH_OBJ);
    }
   
 
 
 ?>      
        <div class="row mt-5">
            <?php foreach($allRows as $product) : ?>
                <div class="col-lg-4 col-md-6 col-sm-12 product-card mb-4">
                    <div class="card" >
                                    <div class="product-card-img-container">
                <img class="card-img-top" src="<?php echo IMGURL; ?>/<?php echo  $product->image; ?>">
            </div>
                        <div class="card-body" >
                                                        <h5><b><?php echo  $product->name; ?></b> </h5>
                            <h5>
                                <div class="d-inline highlighted-price">($<?php echo $product->price; ?>/item)</div>
                            </h5>
                            <div><?php
                                $description = $product->description;
                                if (strlen($description) < 100) {
                                    $description .= " Dive into a captivating narrative that will keep you on the edge of your seat. This masterpiece of literature is a must-read for any book lover.";
                                }
                                echo substr($description, 0, 200); 
                            ?></div>
                            <a href="<?php echo APPURL; ?>/shopping/single.php?id=<?php echo  $product->id; ?>"  class="btn btn-primary w-100 rounded my-2 btn-more"> More <i class="fas fa-arrow-right"></i> </a>      
        
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
 
         </div>

<?php require  "../includes/footer.php"; ?>        