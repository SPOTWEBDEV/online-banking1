<?php
include("../server/connection.php");
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
        <div class="header">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="header-content">
                            <div class="header-left">
                                <div class="brand-logo">
                                    <a class="mini-logo" href="<?= $domain ?>/admin/index.php">
                                        <img src="<?= $domain ?>/images/logoi.png" alt="" width="40">
                                    </a>
                                </div>

                                <div class="search">
                                    <form action="#">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search Here">
                                            <span class="input-group-text"><i class="fi fi-br-search"></i></span>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="header-right">
                                <div class="dark-light-toggle" onclick="themeToggle()">
                                    <span class="dark"><i class="fi fi-rr-eclipse-alt"></i></span>
                                    <span class="light"><i class="fi fi-rr-eclipse-alt"></i></span>
                                </div>

                                <div class="nav-item dropdown notification">
                                    <div data-bs-toggle="dropdown">
                                        <div class="notify-bell icon-menu">
                                            <span><i class="fi fi-rs-bells"></i></span>
                                        </div>
                                    </div>
                                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-end">
                                        <h4>Recent Notification</h4>
                                        <div class="lists">
                                            <a class="" href="#">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon success"><i class="fi fi-bs-check"></i></span>
                                                    <div>
                                                        <p>Account created successfully</p>
                                                        <span>2024-11-04 12:00:23</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="" href="#">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon fail"><i class="fi fi-sr-cross-small"></i></span>
                                                    <div>
                                                        <p>2FA verification failed</p>
                                                        <span>2024-11-04 12:00:23</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="" href="#">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon success"><i class="fi fi-bs-check"></i></span>
                                                    <div>
                                                        <p>Device confirmation completed</p>
                                                        <span>2024-11-04 12:00:23</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="" href="#">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon pending"><i class="fi fi-rr-triangle-warning"></i></span>
                                                    <div>
                                                        <p>Phone verification pending</p>
                                                        <span>2024-11-04 12:00:23</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="more">
                                            <a href="<?= $domain ?>/admin/notifications.php">More<i class="fi fi-bs-angle-right"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown profile_log dropdown">
                                    <div data-bs-toggle="dropdown">
                                        <div class="user icon-menu active"><span><i class="fi fi-rr-user"></i></span></div>
                                    </div>
                                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu dropdown-menu-end">
                                        <div class="user-email">
                                            <div class="user">
                                                <span class="thumb">
                                                    <img class="rounded-full" src="<?= $domain ?>/images/avatar/3.jpg" alt="">
                                                </span>
                                                <div class="user-info">
                                                    <h5>Hafsa Humaira</h5>
                                                    <span>hello@email.com</span>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="dropdown-item" href="<?= $domain ?>/admin/profile.php">
                                            <span><i class="fi fi-rr-user"></i></span>
                                            Profile
                                        </a>
                                        <a class="dropdown-item" href="<?= $domain ?>/admin/wallets.php">
                                            <span><i class="fi fi-rr-wallet"></i></span>
                                            Wallets
                                        </a>
                                        <a class="dropdown-item" href="<?= $domain ?>/admin/settings.php">
                                            <span><i class="fi fi-rr-settings"></i></span>
                                            Settings
                                        </a>
                                        <a class="dropdown-item logout" href="<?= $domain ?>/signin.php">
                                            <span><i class="fi fi-bs-sign-out-alt"></i></span>
                                            Logout
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar">
            <div class="brand-logo">
                <a class="full-logo" href="<?= $domain ?>/admin/index.php">
                    <img src="<?= $domain ?>/images/logoi.png" alt="" width="30">
                </a>
            </div>

            <div class="menu">
                <ul>
                    <li><a href="<?= $domain ?>/admin/index.php"><span><i class="fi fi-rr-dashboard"></i></span><span class="nav-text">Home</span></a></li>
                    <li><a href="<?= $domain ?>/admin/wallets.php"><span><i class="fi fi-rr-wallet"></i></span><span class="nav-text">Wallets</span></a></li>
                    <li><a href="<?= $domain ?>/admin/budgets.php"><span><i class="fi fi-rr-donate"></i></span><span class="nav-text">Budgets</span></a></li>
                    <li><a href="<?= $domain ?>/admin/goals.php"><span><i class="fi fi-sr-bullseye-arrow"></i></span><span class="nav-text">Goals</span></a></li>
                    <li><a href="<?= $domain ?>/admin/profile.php"><span><i class="fi fi-rr-user"></i></span><span class="nav-text">Profile</span></a></li>
                    <li><a href="<?= $domain ?>/admin/analytics.php"><span><i class="fi fi-rr-chart-histogram"></i></span><span class="nav-text">Analytics</span></a></li>
                    <li><a href="<?= $domain ?>/admin/support.php"><span><i class="fi fi-rr-user-headset"></i></span><span class="nav-text">Support</span></a></li>
                    <li><a href="<?= $domain ?>/admin/affiliates.php"><span><i class="fi fi-rs-link-alt"></i></span><span class="nav-text">Affiliates</span></a></li>
                    <li><a href="<?= $domain ?>/admin/settings.php"><span><i class="fi fi-rs-settings"></i></span><span class="nav-text">Settings</span></a></li>
                </ul>
            </div>
        </div>

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

                        // COUNT TOTAL USERS
                        $count_sql = "SELECT COUNT(*) AS total FROM users";
                        $count_stmt = mysqli_prepare($connection, $count_sql);
                        mysqli_stmt_execute($count_stmt);
                        $count_result = mysqli_stmt_get_result($count_stmt);
                        $total_row = mysqli_fetch_assoc($count_result);
                        $total_records = (int)($total_row['total'] ?? 0);
                        $total_pages = (int) ceil($total_records / $limit);
                        mysqli_stmt_close($count_stmt);

                        // FETCH USERS
                        $sql = "
                            SELECT 
                                id,
                                fullname,
                                email,
                                balance,
                                loan_balance,
                                crypto_balance,
                                virtual_card_balance,
                                created_at
                            FROM users
                            ORDER BY id DESC
                            LIMIT ? OFFSET ?
                        ";

                        $stmt = mysqli_prepare($connection, $sql);
                        mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        ?>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>FULLNAME</th>
                                                <th>EMAIL</th>
                                                <th>BALANCE</th>
                                                <th>LOAN</th>
                                                <th>CRYPTO</th>
                                                <th>VIRTUAL CARD</th>
                                                <th>CREATED</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php if (mysqli_num_rows($result) > 0): $count = 0; ?>
                                                <?php while ($row = mysqli_fetch_assoc($result)): $count++; ?>
                                                    <tr>
                                                        <td><?= $count ?></td>
                                                        <td><?= htmlspecialchars($row['fullname']) ?></td>
                                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                                        <td>$<?= number_format((float)$row['balance'], 2) ?></td>
                                                        <td>$<?= number_format((float)$row['loan_balance'], 2) ?></td>
                                                        <td>$<?= number_format((float)$row['crypto_balance'], 2) ?></td>
                                                        <td>$<?= number_format((float)$row['virtual_card_balance'], 2) ?></td>
                                                        <td><?= !empty($row['created_at']) ? date("Y-m-d", strtotime($row['created_at'])) : '-' ?></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">No users found</td>
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
                                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
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