<?php require "../layouts/header.php"; ?>  
<?php require "../../config/config.php"; ?> 
<?php 

    if(!isset($_SESSION['adminname'])) {
        header("location: ".ADMINURL."/admins/login-admins.php");
    }

    // Fetch categories for the dropdown
    $category_query = $conn->query("SELECT * FROM categories");
    $category_query->execute();
    $categories = $category_query->fetchAll(PDO::FETCH_OBJ);

    // Fetch product data for pre-filling the form
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $select = $conn->prepare("SELECT * FROM products WHERE id = :id");
        $select->execute([':id' => $id]);
        $product = $select->fetch(PDO::FETCH_OBJ);

        if(!$product) {
            header("location: ".ADMINURL."/products-admins/show-products.php");
        }
    } else {
        header("location: ".ADMINURL."/products-admins/show-products.php");
    }

    // Handle form submission for updating the product
    if(isset($_POST['submit'])) {
        if(empty($_POST['name']) OR empty($_POST['description']) OR empty($_POST['price'])) {
            echo "<script>alert('One or more inputs are empty');</script>";
        } else {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];

            // Prepare the base update query
            $update_query = "UPDATE products SET name = :name, price = :price, description = :description, category_id = :category_id";
            $params = [
                ":name" => $name,
                ":price" => $price,
                ":description" => $description,
                ":category_id" => $category_id,
                ":id" => $id
            ];

            // Handle image upload
            if(!empty($_FILES['image']['name'])) {
                $image = $_FILES['image']['name'];
                $dir_image = "images/" . basename($image);
                $update_query .= ", image = :image";
                $params[':image'] = $image;
                move_uploaded_file($_FILES['image']['tmp_name'], $dir_image);
            }

            // Handle file upload
            if(!empty($_FILES['file']['name'])) {
                $file = $_FILES['file']['name'];
                $dir_file = "books/" . basename($file);
                $update_query .= ", file = :file";
                $params[':file'] = $file;
                move_uploaded_file($_FILES['file']['tmp_name'], $dir_file);
            }

            $update_query .= " WHERE id = :id";

            $update = $conn->prepare($update_query);
            $update->execute($params);

            header("location: ".ADMINURL."/products-admins/show-products.php");
        }
    }

?>
<div class="row">
    <div class="col">
        <div class="card glass-card">
            <div class="card-body">
                <h5 class="card-title mb-5 d-inline">Update Product</h5>
                <form method="POST" action="update-product.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
                    <div class="form-outline mb-4 mt-4">
                        <label>Name</label>
                        <input type="text" name="name" id="form2Example1" class="form-control form-control-lg" value="<?php echo $product->name; ?>" />
                    </div>

                    <div class="form-outline mb-4 mt-4">
                        <label>Price</label>
                        <input type="text" name="price" id="form2Example1" class="form-control form-control-lg" value="<?php echo $product->price; ?>" />
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Description</label>
                        <textarea name="description" class="form-control form-control-lg" id="exampleFormControlTextarea1" rows="3"><?php echo $product->description; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Select Category</label>
                        <select name="category_id" class="form-control form-control-lg" id="exampleFormControlSelect1">
                            <option>--select category--</option>
                            <?php foreach($categories as $category) : ?>
                                <option value="<?php echo $category->id; ?>" <?php if($product->category_id == $category->id) echo 'selected'; ?>><?php echo $category->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-outline mb-4 mt-4">
                        <label>Image</label>
                        <input type="file" name="image" id="form2Example1" class="form-control form-control-lg" />
                        <p>Current Image: <?php echo $product->image; ?></p>
                    </div>

                    <div class="form-outline mb-4 mt-4">
                        <label>File</label>
                        <input type="file" name="file" id="form2Example1" class="form-control form-control-lg" />
                        <p>Current File: <?php echo $product->file; ?></p>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary mb-4 text-center">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require "../layouts/footer.php"; ?>
