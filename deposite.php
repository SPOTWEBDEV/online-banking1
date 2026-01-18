<?php
session_start();
include("./server/connection.php");



// if (!isset($_SESSION['user_id'])) {
//     die("Unauthorized access");
// }

$user_id = (int) $_SESSION['user_id'];
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deposit'])) {

    $method = trim($_POST['method'] ?? "");
    $type   = trim($_POST['type'] ?? "");
    $amount = trim($_POST['amount'] ?? "");

    $allowedMethods = ['wallet'];
    $allowedTypes   = ['USDT', 'BTC', 'POLYGON'];

    if ($method === "") {
        $errors[] = "Deposit method is required.";
    } elseif (!in_array($method, $allowedMethods, true)) {
        $errors[] = "Invalid deposit method.";
    } elseif ($type === "") {
        $errors[] = "Deposit type is required.";
    } elseif (!in_array($type, $allowedTypes, true)) {
        $errors[] = "Invalid deposit type.";
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $errors[] = "Invalid deposit amount.";
    }

    if (empty($errors)) {

        $sql = "INSERT INTO deposits (user_id, method, type, amount)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "issd", $user_id, $method, $type, $amount);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Deposit submitted successfully. Awaiting confirmation.";
        } else {
            $errors[] = "Deposit failed. Please try again.";
        }

        mysqli_stmt_close($stmt);
    }
}






?>


<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | Deposit </title>
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
                                        <h3>Deposit</h3>
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

                            <div class="row g-4">

                                <!-- Error-->
                                <div class="col-xxl-6 col-xl-6 col-lg-6">
                                    <?php if (!empty($errors)): ?>
                                        <div class="alert alert-danger">
                                            <?php foreach ($errors as $error): ?>
                                                <p><?= htmlspecialchars($error) ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($success): ?>
                                        <div class="alert alert-success">
                                            <?= htmlspecialchars($success) ?>
                                        </div>
                                    <?php endif; ?>



                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Select Deposit Method</h4>
                                        </div>
                                        <div class="card-body">
                                            <form method="post" id="depositForm">

                                                <div class="mb-3">
                                                    <label class="form-label">Method</label>
                                                    <select name="method" class="form-select" id="method">
                                                        <option disabled selected hidden value="">Select Method</option>
                                                        <option value="wallet">Wallet</option>
                                                        <option value="bank">Bank Transfer</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Type</label>
                                                    <select name="type" class="form-select" id="type">
                                                        <option disabled selected hidden  value="">Select Type</option>
                                                        <option value="USDT">USDT</option>
                                                        <option value="BTC">BTC</option>
                                                        <option value="POLYGON">POLYGON</option>
                                                    </select>
                                                </div>


                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT SIDE -->
                                <div class="col-xxl-6 col-xl-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h4 class="card-title">Payment Address / Account Details</h4>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="copyWallet()">Copy</button>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Crypto Type:</strong> <span id="cryptoType"> testing</span></p>
                                            <p id="walletAddress" class="text-break text-muted"><strong>Wallet Address:</strong> testing</p>
                                            <!-- <p id="walletAddress" class="text-break text-muted"></p> -->
                                        </div>
                                    </div>
                                </div>

                                <!-- BOTTOM -->
                                <div class="col-xxl-6 col-xl-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Submit Payment</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Amount Sent</label>
                                                <input name="amount" type="number" class="form-control" placeholder="Enter amount">
                                            </div>
                                            <button type="submit" name="deposit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>

                                </form>

                            </div>


                        </div>
                    </div>
                </div>

                <!-- deposite js temporal -->

                <script>
                    const wallets = {
                        USDT: "TX8K9xKJdsj2390dsUSDTexample",
                        BTC: "bc1qexamplebtcaddress123",
                        POLYGON: "0x2AC3229c7BE5A1bD7F4062d9283BC89Cb8600c5e"
                    };

                    const method = document.getElementById("method");
                    const type = document.getElementById("type");
                    const walletAddress = document.getElementById("walletAddress");
                    const cryptoType = document.getElementById("cryptoType");

                    function updateWallet() {
                        if (method.value === "wallet" && wallets[type.value]) {
                            walletAddress.textContent = wallets[type.value];
                            cryptoType.textContent = type.value;
                        } else {
                            walletAddress.textContent = "---";
                            cryptoType.textContent = "---";
                        }
                    }

                    method.addEventListener("change", updateWallet);
                    type.addEventListener("change", updateWallet);

                    function copyWallet() {
                        if (walletAddress.textContent !== "---") {
                            navigator.clipboard.writeText(walletAddress.textContent);
                            alert("Wallet address copied!");
                        }
                    }
                </script>


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