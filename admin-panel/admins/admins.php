<?php require "../layouts/header.php"; ?>
<?php require "../../config/config.php"; ?>

<?php

if (!isset($_SESSION['adminname'])) {
    header("location: " . ADMINURL . "/admins/login-admins.php");
}

// Base query
$query = "SELECT * FROM admins";
$params = [];
$conditions = [];

// Handle search and filter
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {

    if (!empty($_POST['search_term'])) {
        $conditions[] = "(adminname LIKE :term OR email LIKE :term)";
        $params[':term'] = '%' . $_POST['search_term'] . '%';
    }

    if (!empty($_POST['date_filter_type'])) {
        $filter_type = $_POST['date_filter_type'];
        $date1 = $_POST['date1'];
        $date2 = $_POST['date2'];

        switch ($filter_type) {
            case 'today':
                $conditions[] = "DATE(created_at) = CURDATE()";
                break;
            case 'before':
                if (!empty($date1)) {
                    $conditions[] = "DATE(created_at) < :date1";
                    $params[':date1'] = $date1;
                }
                break;
            case 'after':
                if (!empty($date1)) {
                    $conditions[] = "DATE(created_at) > :date1";
                    $params[':date1'] = $date1;
                }
                break;
            case 'between':
                if (!empty($date1) && !empty($date2)) {
                    $conditions[] = "DATE(created_at) BETWEEN :date1 AND :date2";
                    $params[':date1'] = $date1;
                    $params[':date2'] = $date2;
                }
                break;
        }
    }

    if (count($conditions) > 0) {
        $query .= " WHERE " . implode(' AND ', $conditions);
    }
}

// Handle sorting
if (isset($_POST['sort']) && !empty($_POST['sort'])) {
    $sort_option = $_POST['sort'];
    if ($sort_option == 'oldest') {
        $query .= " ORDER BY created_at ASC";
    } else {
        $query .= " ORDER BY created_at DESC";
    }
} else {
    $query .= " ORDER BY created_at DESC";
}


$select = $conn->prepare($query);
$select->execute($params);

$admins = $select->fetchAll(PDO::FETCH_OBJ);

?>

<div class="row">
    <div class="col">
        <div class="card glass-card">
            <div class="card-body">
                <h5 class="card-title mb-4 d-inline">Admins</h5>
                <a href="<?php echo ADMINURL; ?>/admins/create-admins.php" class="btn btn-primary mb-4 text-center float-right">Create Admins</a>

                <!-- Search and Filter Form -->
                <form method="POST" action="admins.php" class="mb-4">
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="search_term" class="form-control" placeholder="Admin name or Email" value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="date_filter_type" id="date_filter_type" class="form-control">
                                <option value="">Select Date Filter</option>
                                <option value="today" <?php echo (isset($_POST['date_filter_type']) && $_POST['date_filter_type'] == 'today') ? 'selected' : ''; ?>>Today</option>
                                <option value="before" <?php echo (isset($_POST['date_filter_type']) && $_POST['date_filter_type'] == 'before') ? 'selected' : ''; ?>>Before</option>
                                <option value="after" <?php echo (isset($_POST['date_filter_type']) && $_POST['date_filter_type'] == 'after') ? 'selected' : ''; ?>>After</option>
                                <option value="between" <?php echo (isset($_POST['date_filter_type']) && $_POST['date_filter_type'] == 'between') ? 'selected' : ''; ?>>Between</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date1" id="date1" class="form-control" value="<?php echo isset($_POST['date1']) ? htmlspecialchars($_POST['date1']) : ''; ?>">
                        </div>
                        <div class="col-md-2" id="date2_container" style="display: none;">
                            <input type="date" name="date2" id="date2" class="form-control" value="<?php echo isset($_POST['date2']) ? htmlspecialchars($_POST['date2']) : ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="sort" class="form-control">
                                <option value="latest" <?php echo (isset($_POST['sort']) && $_POST['sort'] == 'latest') ? 'selected' : ''; ?>>Latest</option>
                                <option value="oldest" <?php echo (isset($_POST['sort']) && $_POST['sort'] == 'oldest') ? 'selected' : ''; ?>>Oldest</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex">
                            <button type="submit" name="search" class="btn btn-primary btn-search-margin">Search</button>
                            <a href="admins.php" class="btn btn-danger">Clear</a>
                        </div>
                    </div>
                </form>

                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">adminname</th>
                            <th scope="col">email</th>
                            <th scope="col">created_at</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($admins as $admin) : ?>
                            <tr>
                                <th scope="row"><?php echo $admin->id; ?></th>
                                <td><?php echo $admin->adminname; ?></td>
                                <td><?php echo $admin->email; ?></td>
                                <td><?php echo date('M d, Y, h:i A', strtotime($admin->created_at)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('date_filter_type').addEventListener('change', function() {
        var date1_container = document.getElementById('date1');
        var date2_container = document.getElementById('date2_container');
        if (this.value === 'between') {
            date2_container.style.display = 'block';
            date1_container.style.display = 'block';
        } else if (this.value === 'today' || this.value === '') {
            date1_container.style.display = 'none';
            date2_container.style.display = 'none';
        } else {
            date1_container.style.display = 'block';
            date2_container.style.display = 'none';
        }
    });
    // Trigger change on page load to set initial state
    document.getElementById('date_filter_type').dispatchEvent(new Event('change'));
</script>

<?php require "../layouts/footer.php"; ?>
