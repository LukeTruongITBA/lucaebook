<?php require  "../includes/header.php"; ?>
<?php require  "../config/config.php"; ?> 

<?php 

    $select = $conn->query("SELECT * FROM categories");
    $select->execute();

    $categories = $select->fetchAll(PDO::FETCH_OBJ);

?>

        <div class="row mt-5">

            <?php foreach($categories as $category) : ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card" >
                                <img height="213px" class="card-img-top" src="http://localhost/bookstore/admin-panel/categories-admins/images/<?php echo $category->image; ?>">
                                <div class="card-body d-flex flex-column" >
                                    <h5><b><?php echo $category->name; ?></b> </h5>
                                    <div class="flex-grow-1"><?php echo $category->description; ?> </div>
                                    <a href="<?php echo APPURL; ?>/categories/single-category.php?id=<?php echo $category->id; ?>" class="btn btn-primary w-100 rounded my-2 btn-more">Discover Products</a>      
                                </div>
                            </div>
                        </div>
            <?php endforeach; ?>
                    
        </div>
<?php require  "../includes/footer.php"; ?>