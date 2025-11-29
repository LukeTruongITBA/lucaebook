<?php require "includes/header.php"; ?>
<?php require "config/config.php"; ?>
<?php
// Fetch categories for the dropdown
$category_query = $conn->query("SELECT * FROM categories");
$category_query->execute();
$categories = $category_query->fetchAll(PDO::FETCH_OBJ);

// Base query
$query = "SELECT * FROM products WHERE status = 1";
$params = [];

// Handle search and filter
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conditions = [];

    if (!empty($_POST['name'])) {
        $conditions[] = "name LIKE :name";
        $params[':name'] = '%' . $_POST['name'] . '%';
    }

    if (!empty($_POST['category_id'])) {
        $conditions[] = "category_id = :category_id";
        $params[':category_id'] = $_POST['category_id'];
    }

    if (!empty($_POST['min_price'])) {
        $conditions[] = "price >= :min_price";
        $params[':min_price'] = $_POST['min_price'];
    }

    if (!empty($_POST['max_price'])) {
        $conditions[] = "price <= :max_price";
        $params[':max_price'] = $_POST['max_price'];
    }

    if (count($conditions) > 0) {
        $query .= " AND " . implode(' AND ', $conditions);
    }

    // Handle sorting
    if (!empty($_POST['sort'])) {
        $sort_option = $_POST['sort'];
        switch ($sort_option) {
            case 'name_asc':
                $query .= " ORDER BY name ASC";
                break;
            case 'name_desc':
                $query .= " ORDER BY name DESC";
                break;
            case 'price_asc':
                $query .= " ORDER BY price ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY price DESC";
                break;
            default:
                $query .= " ORDER BY created_at DESC";
                break;
        }
    } else {
        $query .= " ORDER BY created_at DESC";
    }
} else {
    $query .= " ORDER BY created_at DESC";
}

$rows = $conn->prepare($query);
$rows->execute($params);

$allRows = $rows->fetchAll(PDO::FETCH_OBJ);

?>

<!-- Search and Filter Form -->
<div class="container mt-5">
    <div class="search-filter-form glass-card mb-5">
        <form method="POST" action="index.php">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Product Name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name="category_id" class="form-control">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo $category->id; ?>" <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category->id) ? 'selected' : ''; ?>><?php echo $category->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" name="min_price" class="form-control" placeholder="Min" value="<?php echo isset($_POST['min_price']) ? htmlspecialchars($_POST['min_price']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" name="max_price" class="form-control" placeholder="Max" value="<?php echo isset($_POST['max_price']) ? htmlspecialchars($_POST['max_price']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name="sort" class="form-control">
                            <option value="">Sort By</option>
                            <option value="name_asc" <?php echo (isset($_POST['sort']) && $_POST['sort'] == 'name_asc') ? 'selected' : ''; ?>>Name (A-Z)</option>
                            <option value="name_desc" <?php echo (isset($_POST['sort']) && $_POST['sort'] == 'name_desc') ? 'selected' : ''; ?>>Name (Z-A)</option>
                            <option value="price_asc" <?php echo (isset($_POST['sort']) && $_POST['sort'] == 'price_asc') ? 'selected' : ''; ?>>Price (Low to High)</option>
                            <option value="price_desc" <?php echo (isset($_POST['sort']) && $_POST['sort'] == 'price_desc') ? 'selected' : ''; ?>>Price (High to Low)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 search-filter-buttons">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="index.php" class="btn btn-danger">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="row mt-5">
    <?php if (count($allRows) > 0) : ?>
        <?php foreach ($allRows as $product) : ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card product-card">
            <div class="product-card-img-container">
                    <img class="card-img-top" src="<?php echo IMGURL; ?>/<?php echo $product->image; ?>">
            </div>
                    <div class="card-body product-card-body card-content">
                        <h5><b><?php echo $product->name; ?></b></h5>
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
                        <a href="<?php echo APPURL; ?>/shopping/single.php?id=<?php echo $product->id; ?>" class="btn btn-primary w-100 rounded btn-more"> More <i class="fas fa-arrow-right"></i> </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="col-12">
            <div class="alert alert-warning glass-card text-center" role="alert">
                No products found matching your criteria.
            </div>
        </div>
    <?php endif; ?>
</div>


<button id="scrollToTopBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
<?php require "includes/footer.php"; ?>

<script>
  // Scroll to top button functionality
  var scrollToTopBtn = document.getElementById("scrollToTopBtn");
  window.onscroll = function() {scrollFunction()};

  function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      scrollToTopBtn.style.display = "block";
    } else {
      scrollToTopBtn.style.display = "none";
    }
  }

  scrollToTopBtn.addEventListener("click", function() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
  });
</script>
