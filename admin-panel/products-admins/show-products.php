<?php require "../layouts/header.php"; ?>
<?php require "../../config/config.php"; ?>
<?php

if (!isset($_SESSION['adminname'])) {
    header("location: " . ADMINURL . "/admins/login-admins.php");
}

// Base query
$query = "SELECT * FROM products";
$params = [];

// Handle search and filter
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $conditions = [];

    if (!empty($_POST['name'])) {
        $conditions[] = "name LIKE :name";
        $params[':name'] = '%' . $_POST['name'] . '%';
    }

    if (!empty($_POST['price'])) {
        $conditions[] = "price <= :price";
        $params[':price'] = $_POST['price'];
    }

    if (isset($_POST['status']) && $_POST['status'] !== '') {
        $conditions[] = "status = :status";
        $params[':status'] = $_POST['status'];
    }

    if (count($conditions) > 0) {
        $query .= " WHERE " . implode(' AND ', $conditions);
    }
}

$select = $conn->prepare($query);
$select->execute($params);

$products = $select->fetchAll(PDO::FETCH_OBJ);

?>
<div class="row">
    <div class="col">
        <div class="card glass-card">
            <div class="card-body">
                <h5 class="card-title mb-4 d-inline">Products</h5>
                <a href="<?php echo ADMINURL; ?>/products-admins/create-products.php" class="btn btn-primary mb-4 text-center float-right">Create Products</a>

                <!-- Search Form -->
                <form method="POST" action="show-products.php" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="name" class="form-control" placeholder="Product Name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="price" class="form-control" placeholder="Max Price" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="1" <?php echo (isset($_POST['status']) && $_POST['status'] == '1') ? 'selected' : ''; ?>>Verified</option>
                                <option value="0" <?php echo (isset($_POST['status']) && $_POST['status'] === '0') ? 'selected' : ''; ?>>Unverified</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex">
                            <button type="submit" name="search" class="btn btn-primary btn-search-margin">Search</button>
                            <a href="show-products.php" class="btn btn-danger">Clear</a>
                        </div>
                    </div>
                </form>

                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">product</th>
                            <th scope="col">price in $$</th>
                            <th scope="col">status</th>
                            <th scope="col">update</th>
                            <th scope="col">delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                            <tr>
                                <th scope="row"><?php echo $product->id; ?></th>
                                <td><?php echo $product->name; ?></td>
                                <td><?php echo $product->price; ?></td>
                                <?php if ($product->status > 0) : ?>
                                    <td><a href="<?php echo ADMINURL; ?>/products-admins/status.php?id=<?php echo $product->id; ?>&status=<?php echo $product->status; ?>" class="btn btn-success text-center">Verified</a></td>
                                <?php else : ?>
                                    <td><a href="<?php echo ADMINURL; ?>/products-admins/status.php?id=<?php echo $product->id; ?>&status=<?php echo $product->status; ?>" class="btn btn-danger text-center">Unverified</a></td>
                                <?php endif; ?>
                                <td><a href="<?php echo ADMINURL; ?>/products-admins/update-product.php?id=<?php echo $product->id; ?>" class="btn btn-warning text-white text-center">update</a></td>
                                <td><a href="<?php echo ADMINURL; ?>/products-admins/delete-products.php?id=<?php echo $product->id; ?>" class="btn btn-danger text-center">delete</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require "../layouts/footer.php"; ?>
