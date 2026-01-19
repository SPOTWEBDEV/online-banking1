<?php
include("./server/connection.php");



$errors = [];
$success = "";

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $receiver_account_number = isset($_POST["receiver_account_number"]) ? trim($_POST["receiver_account_number"]) : "";
    $receiver_email          = isset($_POST["receiver_email"]) ? trim($_POST["receiver_email"]) : "";
    $routing_number          = isset($_POST["routing_number"]) ? trim($_POST["routing_number"]) : "";
    $swift_code              = isset($_POST["swift_code"]) ? trim($_POST["swift_code"]) : "";
    $amount                  = isset($_POST["amount"]) ? trim($_POST["amount"]) : "";
    $narration               = isset($_POST["narration"]) ? trim($_POST["narration"]) : "";

   
    if ($receiver_account_number === "") {
        $errors[] = "Receiver account number is required.";
    } elseif (strlen($receiver_account_number) < 8 || strlen($receiver_account_number) > 30) {
        $errors[] = "Receiver account number must be between 8 and 30 characters.";
    }

    if ($receiver_email === "") {
        $errors[] = "Receiver email is required.";
    } elseif (!filter_var($receiver_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Receiver email is not valid.";
    }

   
    if ($routing_number !== "" && strlen($routing_number) > 50) {
        $errors[] = "Routing number is too long.";
    }

    if ($swift_code !== "" && strlen($swift_code) > 20) {
        $errors[] = "Swift code is too long.";
    }

    if ($amount === "") {
        $errors[] = "Amount is required.";
    } elseif (!is_numeric($amount)) {
        $errors[] = "Amount must be a number.";
    } elseif ((float)$amount <= 0) {
        $errors[] = "Amount must be greater than 0.";
    }

    // Narration 
    if ($narration !== "" && strlen($narration) > 255) {
        $errors[] = "Narration must not be more than 255 characters.";
    }

    if (empty($errors)) {

        // Generate OTP (6 digits)
        $otp_code = (string) random_int(100000, 999999);

        // OTP expires in 5 minutes
        $otp_expires_at = date("Y-m-d H:i:s", time() + (5 * 60));

        
        $insert_sql = "
            INSERT INTO bank_transfers
                (user_id, receiver_account_number, receiver_email, routing_number, swift_code, amount, narration, otp_code, otp_expires_at, status)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
        ";

        $stmt = mysqli_prepare($connection, $insert_sql);

        if (!$stmt) {
            $errors[] = "server error";
        } else {
            $amount_decimal = (float) $amount;

            mysqli_stmt_bind_param(
                $stmt,
                "issssdsss",
                $user_id,
                $receiver_account_number,
                $receiver_email,
                $routing_number,
                $swift_code,
                $amount_decimal,
                $narration,
                $otp_code,
                $otp_expires_at
            );

            $run = mysqli_stmt_execute($stmt);

            if (!$run) {
                $errors[] = "Failed to create transfer: " . mysqli_stmt_error($stmt);
            } else {
                $transfer_id = mysqli_insert_id($connection);
                $success = "Transfer created successfully";
            }

            mysqli_stmt_close($stmt);
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
                            <div class="col-xxl-12">
                                <h4 class="card-title mb-3">Apply for loan</h4>
                                <div class="card">
                                    <div class="card-body">
                                        <?php if (!empty($errors)) { ?>
                                            <div class="alert alert-danger mt-3">
                                                <?php foreach ($errors as $error) { ?>
                                                    <p><?= htmlspecialchars($error) ?></p>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>

                                        <?php if (!empty($success)) { ?>
                                            <div class="alert alert-success mt-3">
                                                <?= htmlspecialchars($success) ?>
                                            </div>
                                        <?php } ?>

                                        <form action="" method="post">
                                            <div class="row">

                                                <!-- Receiver Account Number -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Receiver Account Number</label>
                                                    <input name="receiver_account_number" type="text" class="form-control" placeholder="Enter receiver account number">
                                                </div>

                                                <!-- Receiver Email -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Receiver Email</label>
                                                    <input name="receiver_email" type="email" class="form-control" placeholder="Enter receiver email">
                                                </div>

                                                <!-- Routing Number -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Routing Number</label>
                                                    <input name="routing_number" type="text" class="form-control" placeholder="Enter routing number (optional)">
                                                </div>

                                                <!-- Swift Code -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Swift Code</label>
                                                    <input name="swift_code" type="text" class="form-control" placeholder="Enter swift code (optional)">
                                                </div>

                                                <!-- Amount -->
                                                <div class="col-xxl-12 col-xl-12 col-lg-12 mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input name="amount" type="number" step="0.01" class="form-control" placeholder="Enter amount">
                                                </div>

                                                <!-- Narration -->
                                                <div class="col-xxl-12 col-xl-12 col-lg-12 mb-3">
                                                    <label class="form-label">Narration</label>
                                                    <input name="narration" type="text" class="form-control" placeholder="e.g. Rent payment">
                                                </div>

                                            </div>

                                            <div class="mt-3">
                                                <button type="submit" class="btn btn-primary mr-2">Transfer</button>
                                            </div>
                                        </form>

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
