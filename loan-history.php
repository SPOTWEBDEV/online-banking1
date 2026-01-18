<?php
session_start();
include("./server/connection.php");

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user_id'])) {
        header("location: ./signin.php");
    }

    $user_id = $_SESSION['user_id'] ?? null;

    $loan_amount        = trim($_POST['loan_amount'] ?? '');
    $loan_duration      = trim($_POST['loan_duration'] ?? '');
    $loan_reason        = trim($_POST['loan_reason'] ?? '');
    $monthly_income     = trim($_POST['monthly_income'] ?? '');
    $employment_status  = trim($_POST['employment_status'] ?? '');
    $account_number     = trim($_POST['account_number'] ?? '');
    $bank_name          = trim($_POST['bank_name'] ?? '');

    $loan_duration = intval($loan_duration);


    if ($loan_amount === '' || $loan_duration === '' || $loan_reason === '' || $monthly_income === '' || $employment_status === '' || $account_number === '' || $bank_name === '') {
        $errors[] = "All fields are required";
    }

    if ($loan_amount !== '') {
        if (!is_numeric($loan_amount) || $loan_amount <= 0) {
            $errors[] = "Invalid loan amount";
        }
    }



    if (empty($errors)) {

       
        $interest_rate = 5; // 5% monthly
        $monthly_interest = ($loan_amount * $interest_rate) / 100;
        $total_interest = $monthly_interest * $loan_duration;
        $total_payable = $loan_amount + $total_interest;

        $sql = "INSERT INTO loan_requests 
            (user_id, loan_amount, interest_rate, loan_duration, total_payable, loan_reason, monthly_income, employment_status, account_number, bank_name)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "iddidsdsss",
                $user_id,
                $loan_amount,
                $interest_rate,
                $loan_duration,
                $total_payable,
                $loan_reason,
                $monthly_income,
                $employment_status,
                $account_number,
                $bank_name
            );

            if (mysqli_stmt_execute($stmt)) {
                $success = "Loan applied successfully. Awaiting approval.";
            } else {
                $errors[] = "Loan application failed. Please try again.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "System error. Please contact support.";
        }
    }
}
?>


<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | loan </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">
    <!-- <div id="preloader" class="preloader-wrapper">
        <div class="loader">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div> -->
    <div id="main-wrapper">
        <div class="header">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="header-content">
                            <div class="header-left">
                                <div class="brand-logo"><a class="mini-logo" href="index.html"><img src="images/logoi.png" alt="" width="40"></a></div>
                                <div class="search">
                                    <form action="settings-api.html#">
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
                                            <a class="" href="index.html#">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon success"><i class="fi fi-bs-check"></i></span>
                                                    <div>
                                                        <p>Account created successfully</p>
                                                        <span>2024-11-04 12:00:23</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="" href="index.html#">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon fail"><i class="fi fi-sr-cross-small"></i></span>
                                                    <div>
                                                        <p>2FA verification failed</p>
                                                        <span>2024-11-04 12:00:23</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="" href="index.html#">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon success"><i class="fi fi-bs-check"></i></span>
                                                    <div>
                                                        <p>Device confirmation completed</p>
                                                        <span>2024-11-04 12:00:23</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="" href="index.html#">
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
                                            <a href="notifications.html">More<i class="fi fi-bs-angle-right"></i></a>
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
                                                <span class="thumb"><img class="rounded-full" src="images/avatar/3.jpg" alt=""></span>
                                                <div class="user-info">
                                                    <h5>Hafsa Humaira</h5>
                                                    <span>hello@email.com</span>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="dropdown-item" href="profile.html">
                                            <span><i class="fi fi-rr-user"></i></span>
                                            Profile
                                        </a>
                                        <a class="dropdown-item" href="wallets.html">
                                            <span><i class="fi fi-rr-wallet"></i></span>
                                            Wallets
                                        </a>
                                        <a class="dropdown-item" href="settings.html">
                                            <span><i class="fi fi-rr-settings"></i></span>
                                            Settings
                                        </a>
                                        <a class="dropdown-item logout" href="signin.html">
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
            <div class="brand-logo"><a class="full-logo" href="index.html"><img src="images/logoi.png" alt="" width="30"></a>
            </div>
            <div class="menu">
                <ul>
                    <li>
                        <a href="index.html">
                            <span>
                                <i class="fi fi-rr-dashboard"></i>
                            </span>
                            <span class="nav-text">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="wallets.html">
                            <span>
                                <i class="fi fi-rr-wallet"></i>
                            </span>
                            <span class="nav-text">Wallets</span>
                        </a>
                    </li>
                    <li>
                        <a href="budgets.html">
                            <span>
                                <i class="fi fi-rr-donate"></i>
                            </span>
                            <span class="nav-text">Budgets</span>
                        </a>
                    </li>
                    <li>
                        <a href="goals.html">
                            <span>
                                <i class="fi fi-sr-bullseye-arrow"></i>
                            </span>
                            <span class="nav-text">Goals</span>
                        </a>
                    </li>
                    <li>
                        <a href="profile.html">
                            <span>
                                <i class="fi fi-rr-user"></i>
                            </span>
                            <span class="nav-text">Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="analytics.html">
                            <span>
                                <i class="fi fi-rr-chart-histogram"></i>
                            </span>
                            <span class="nav-text">Analytics</span>
                        </a>
                    </li>
                    <li>
                        <a href="support.html">
                            <span>
                                <i class="fi fi-rr-user-headset"></i>
                            </span>
                            <span class="nav-text">Support</span>
                        </a>
                    </li>
                    <li>
                        <a href="affiliates.html">
                            <span>
                                <i class="fi fi-rs-link-alt"></i>
                            </span>
                            <span class="nav-text">Affiliates</span>
                        </a>
                    </li>
                    <li>
                        <a href="settings.html">
                            <span>
                                <i class="fi fi-rs-settings"></i>
                            </span>
                            <span class="nav-text">Settings</span>
                        </a>
                    </li>
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
                                        <h3>Loan</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <!-- <div class="breadcrumbs"><a href="settings-api.html#">Home </a>
                                        <span><i class="fi fi-rr-angle-small-right"></i></span>
                                        <a href="settings-api.html#">Api</a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-xl-12">

                  

                        <div class="row">
             
                            
                            <?php


                            $user_id = $_SESSION['user_id'];

                          
                            $limit = 10;
                            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                            $offset = ($page - 1) * $limit;

                          
                            $count_sql = "
    SELECT COUNT(*) AS total 
    FROM loan_requests 
    WHERE user_id = ?
";
                            $count_stmt = mysqli_prepare($connection, $count_sql);
                            mysqli_stmt_bind_param($count_stmt, "i", $user_id);
                            mysqli_stmt_execute($count_stmt);
                            $count_result = mysqli_stmt_get_result($count_stmt);
                            $total_row = mysqli_fetch_assoc($count_result);
                            $total_records = $total_row['total'];
                            $total_pages = ceil($total_records / $limit);


                            $sql = "
        SELECT 
        loan_requests.id,
        loan_requests.loan_amount,
        loan_requests.status,
        loan_requests.created_at,
        users.fullname
    FROM loan_requests, users
    WHERE loan_requests.user_id = users.id
    AND loan_requests.user_id = ?
    ORDER BY loan_requests.id DESC
    LIMIT ? OFFSET ?
";

                            $stmt = mysqli_prepare($connection, $sql);
                            mysqli_stmt_bind_param($stmt, "iii", $user_id, $limit, $offset);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            ?>


                            <div class="col-xl-12">
                                <h4 class="card-title mb-3">Loan History</h4>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive api-table">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th>ACCOUNT HOLDER</th>
                                                        <th>AMOUNT</th>
                                                        <th>APPLIED DATE</th>
                                                        <th>STATUS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php if (mysqli_num_rows($result) > 0): $count = 0 ?>
                                                        <?php while ($row = mysqli_fetch_assoc($result)):  $count++ ?>
                                                            <tr>
                                                                <td><?=  $count?></td>
                                                                <td><?= htmlspecialchars($row['fullname']) ?></td>
                                                                <td>$<?= number_format($row['loan_amount'], 2) ?></td>

                                                                   <td><?= date("Y-m-d", strtotime($row['created_at'])) ?></td>
                                                                <td>
                                                                    <span class="badge 
                                            <?php
                                                            if ($row['status'] === 'approved') echo 'bg-success';
                                                            elseif ($row['status'] === 'rejected') echo 'bg-danger';
                                                            else echo 'bg-warning';
                                            ?>">
                                                                        <?= ucfirst($row['status']) ?>
                                                                    </span>
                                                                </td>
                                                             
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="4" class="text-center">No loan history found</td>
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
                                <li><a href="settings-api.html#"><i class="fi fi-brands-facebook"></i></a></li>
                                <li><a href="settings-api.html#"><i class="fi fi-brands-twitter"></i></a></li>
                                <li><a href="settings-api.html#"><i class="fi fi-brands-linkedin"></i></a></li>
                                <li><a href="settings-api.html#"><i class="fi fi-brands-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--  -->
    <!--  -->
    <script src="js/scripts.js"></script>
</body>

</html>