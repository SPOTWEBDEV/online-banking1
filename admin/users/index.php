<?php
include("../../server/connection.php");
include("../../server/auth/admin.php");
?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | Withdrawal-History </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $domain ?>/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?= $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?= $domain ?>/vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">
   

    <div id="main-wrapper">

    <!-- header -->
        <?php include("../include/nav.php") ?>
        <!-- side nav -->
         <?php include("../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-4">
                                    <div class="page-title-content">
                                        <h3>All Users</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-xl-12">

                        <?php
                        $limit  = 10;
                        $page   = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        // FILTER INPUTS
                        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
                        $allowed_statuses = ['active', 'pending', 'suspended', 'banned'];
                        if ($status !== '' && !in_array($status, $allowed_statuses, true)) {
                            $status = '';
                        }

                        // BUILD WHERE CLAUSE DYNAMICALLY (SAFE - PREPARED)
                        $where = [];
                        $params = [];
                        $types = '';

                        if ($search !== '') {
                            $where[] = "(fullname LIKE ? OR email LIKE ? OR accountnumber LIKE ?)";
                            $like = "%{$search}%";
                            $params[] = $like;
                            $params[] = $like;
                            $params[] = $like;
                            $types .= 'sss';
                        }

                        if ($status !== '') {
                            $where[] = "status = ?";
                            $params[] = $status;
                            $types .= 's';
                        }

                        $where_sql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

                        // COUNT TOTAL USERS (respecting filters)
                        $count_sql = "SELECT COUNT(*) AS total FROM users $where_sql";
                        $count_stmt = mysqli_prepare($connection, $count_sql);
                        if (count($params) > 0) {
                            mysqli_stmt_bind_param($count_stmt, $types, ...$params);
                        }
                        mysqli_stmt_execute($count_stmt);
                        $count_result = mysqli_stmt_get_result($count_stmt);
                        $total_row = mysqli_fetch_assoc($count_result);
                        $total_records = (int)($total_row['total'] ?? 0);
                        $total_pages = (int) ceil($total_records / $limit);
                        mysqli_stmt_close($count_stmt);

                        // FETCH USERS (respecting filters)
                        $sql = "
                            SELECT 
                                id,
                                fullname,
                                email,
                                status,
                                balance,
                                loan_balance,
                                crypto_balance,
                                virtual_card_balance,
                                created_at
                            FROM users
                            $where_sql
                            ORDER BY id DESC
                            LIMIT ? OFFSET ?
                        ";

                        $stmt = mysqli_prepare($connection, $sql);
                        $bind_params = $params;
                        $bind_params[] = $limit;
                        $bind_params[] = $offset;
                        $bind_types = $types . 'ii';
                        mysqli_stmt_bind_param($stmt, $bind_types, ...$bind_params);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        // Helper to preserve filters across pagination links
                        function build_query_string($overrides = []) {
                            $current = $_GET;
                            foreach ($overrides as $k => $v) {
                                if ($v === null) {
                                    unset($current[$k]);
                                } else {
                                    $current[$k] = $v;
                                }
                            }
                            return http_build_query($current);
                        }
                        ?>

                        <!-- Filter Form -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <form method="GET" action="">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-5">
                                            <label class="form-label">Search</label>
                                            <input type="text" name="search" class="form-control"
                                                placeholder="Name, email, or account number"
                                                value="<?= htmlspecialchars($search) ?>">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="">All Statuses</option>
                                                <?php foreach ($allowed_statuses as $s): ?>
                                                    <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>>
                                                        <?= ucfirst($s) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                                        </div>

                                        <div class="col-md-2">
                                            <a href="?" class="btn btn-outline-secondary w-100">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>FULLNAME</th>
                                                <th>EMAIL</th>
                                                <th>STATUS</th>
                                                <th>BALANCE</th>
                                                <th>LOAN</th>
                                                <th>CRYPTO</th>
                                                <th>VIRTUAL CARD</th>
                                                <th>CREATED</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php if (mysqli_num_rows($result) > 0): $count = $offset; ?>
                                                <?php while ($row = mysqli_fetch_assoc($result)): $count++; ?>
                                                    <?php
                                                    $row_status_color = match ($row['status']) {
                                                        'active'     => 'bg-success',
                                                        'pending'    => 'bg-warning',
                                                        'suspended'  => 'bg-danger',
                                                        'banned'     => 'bg-danger',
                                                        default      => 'bg-secondary'
                                                    };
                                                    ?>
                                                    <tr>
                                                        <td><?= $count ?></td>
                                                        <td><?= htmlspecialchars($row['fullname']) ?></td>
                                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                                        <td>
                                                            <span class="badge text-white <?= $row_status_color ?>">
                                                                <?= ucfirst($row['status']) ?>
                                                            </span>
                                                        </td>
                                                        <td>$<?= number_format((float)$row['balance'], 2) ?></td>
                                                        <td>$<?= number_format((float)$row['loan_balance'], 2) ?></td>
                                                        <td>$<?= number_format((float)$row['crypto_balance'], 2) ?></td>
                                                        <td>$<?= number_format((float)$row['virtual_card_balance'], 2) ?></td>
                                                        <td><?= !empty($row['created_at']) ? date("Y-m-d", strtotime($row['created_at'])) : '-' ?></td>
                                                        <td>
                                                            <a href="./details/?id=<?php echo $row['id'] ?>"> <span class="badge p-2 bg-info text-white">View Details</span></a>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="10" class="text-center">No users found</td>
                                                </tr>
                                            <?php endif; ?>

                                        </tbody>
                                    </table>
                                </div>

                                <?php if ($total_pages > 1): ?>
                                    <nav class="mt-3">
                                        <ul class="pagination">
                                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                    <a class="page-link" href="?<?= build_query_string(['page' => $i]) ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>

    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="copyright">
                        <p>© Copyright
                            <script>
                                var CurrentYear = new Date().getFullYear()
                                document.write(CurrentYear)
                            </script>
                            <a href="#"><?= $sitename ?></a> I All Rights Reserved
                        </p>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="footer-social">
                        <ul>
                            <li><a href="#"><i class="fi fi-brands-facebook"></i></a></li>
                            <li><a href="#"><i class="fi fi-brands-twitter"></i></a></li>
                            <li><a href="#"><i class="fi fi-brands-linkedin"></i></a></li>
                            <li><a href="#"><i class="fi fi-brands-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <script src="<?= $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $domain ?>/js/scripts.js"></script>
</body>

</html>