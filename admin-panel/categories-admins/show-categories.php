<?php require "../layouts/header.php"; ?>
<?php require "../../config/config.php"; ?>

<?php

// Base query
$query = "SELECT * FROM categories";
$params = [];

// Handle A-Z filtering
if (isset($_GET['filter']) && $_GET['filter'] != 'All') {
    $filter = $_GET['filter'] . '%';
    $query .= " WHERE name LIKE :filter";
    $params[':filter'] = $filter;
}

$select = $conn->prepare($query);
$select->execute($params);

$categories = $select->fetchAll(PDO::FETCH_OBJ);

?>

<div class="row">
    <div class="col">
        <div class="card glass-card">
            <div class="card-body">
                <h5 class="card-title mb-4 d-inline">Categories</h5>
                <a href="<?php echo ADMINURL; ?>/categories-admins/create-category.php" class="btn btn-primary mb-4 text-center float-right">Create Categories</a>

                <!-- A-Z Filter Bar -->
                <div class="card glass-card mb-4">
                    <div class="card-body d-flex flex-wrap justify-content-center">
                        <a href="show-categories.php?filter=All" class="az-filter-link">All</a>
                        <?php foreach (range('A', 'Z') as $char) : ?>
                            <a href="show-categories.php?filter=<?php echo $char; ?>" class="az-filter-link"><?php echo $char; ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">name</th>
                            <th scope="col">update</th>
                            <th scope="col">delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category) : ?>
                            <tr>
                                <th scope="row"><?php echo $category->id; ?></th>
                                <td><?php echo $category->name; ?></td>
                                <td><a href="<?php echo ADMINURL; ?>/categories-admins/update-category.php?id=<?php echo $category->id; ?>" class="btn btn-warning text-white text-center ">Update </a></td>
                                <td><a href="<?php echo ADMINURL; ?>/categories-admins/delete-categories.php?id=<?php echo $category->id; ?>" class="btn btn-danger  text-center ">Delete </a></td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php require "../layouts/footer.php"; ?>
