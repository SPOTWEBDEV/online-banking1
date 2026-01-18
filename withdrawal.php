<?php
session_start();
include("./server/connection.php");

// if (!isset($_SESSION['user_id'])) {
//     die("Unauthorized access");
// }

$user_id = (int) ($_SESSION['user_id'] ?? 0);
$errors = [];
$success = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['withdraw'])) {

    $which_account  = trim($_POST['which_account'] ?? "");
    $amount_raw     = trim($_POST['amount'] ?? "");
    $wallet_address = trim($_POST['wallet_address'] ?? "");

    // Validate selection 
    $allowedAccounts = [
        'USDT(TRC20)'          => 'crypto_balance',
        'BTC'                  => 'crypto_balance',
        'POLYGON'              => 'crypto_balance',
        'MAIN BALANCE'         => 'balance',
        'LOAN BALANCE'         => 'loan_balance',
        'VIRTUAL CARD BALANCE' => 'virtual_card_balance',
    ];

    if ($which_account === "") {
        $errors[] = "Select withdrawal account.";
    } elseif (!array_key_exists($which_account, $allowedAccounts)) {
        $errors[] = "Invalid withdrawal account.";
    } elseif (!is_numeric($amount_raw)) {
        $errors[] = "Invalid withdrawal amount.";
    } else {
        $amount = (float)$amount_raw;
        if ($amount <= 0) {
            $errors[] = "Invalid withdrawal amount.";
        }
    }

    if ($wallet_address === "") {
        $errors[] = "Wallet address is required.";
    } elseif (strlen($wallet_address) < 10 || strlen($wallet_address) > 120) {
        $errors[] = "Invalid wallet address.";
    }

    // Stop early (show only one error as you wanted)
    if (!empty($errors)) {
        $errors = [$errors[0]];
    }

    if (empty($errors)) {

        //  Decide which DB column to check
        $balanceColumn = $allowedAccounts[$which_account]; 
  
        mysqli_begin_transaction($connection);

        try {
            // lock user row so they can't submit multiple withdraws at the same time
            $sqlBal = "SELECT `$balanceColumn` FROM users WHERE id = ? FOR UPDATE";
            $stmtBal = mysqli_prepare($connection, $sqlBal);
            if (!$stmtBal) {
                throw new Exception("System error (prepare balance).");
            }

            mysqli_stmt_bind_param($stmtBal, "i", $user_id);
            mysqli_stmt_execute($stmtBal);
            $resBal = mysqli_stmt_get_result($stmtBal);
            $rowBal = mysqli_fetch_assoc($resBal);
            mysqli_stmt_close($stmtBal);

            if (!$rowBal) {
                throw new Exception("User not found.");
            }

            $currentBalance = (float)$rowBal[$balanceColumn];

            if ($amount > $currentBalance) {
                throw new Exception("Insufficient balance for this account.");
            }

            // Insert withdrawal request
            $sqlW = "INSERT INTO withdrawals (user_id, amount, which_account) VALUES (?, ?, ?)";
            $stmtW = mysqli_prepare($connection, $sqlW);
            if (!$stmtW) {
                throw new Exception("System error (prepare withdrawal).");
            }

            mysqli_stmt_bind_param($stmtW, "ids", $user_id, $amount, $which_account);

            if (!mysqli_stmt_execute($stmtW)) {
                mysqli_stmt_close($stmtW);
                throw new Exception("Withdrawal failed. Please try again.");
            }
            mysqli_stmt_close($stmtW);

            // Deduct balance immediately 
            $sqlU = "UPDATE users SET `$balanceColumn` = `$balanceColumn` - ? WHERE id = ?";
            $stmtU = mysqli_prepare($connection, $sqlU);
            if (!$stmtU) {
                throw new Exception("System error (prepare update).");
            }

            mysqli_stmt_bind_param($stmtU, "di", $amount, $user_id);

            if (!mysqli_stmt_execute($stmtU)) {
                mysqli_stmt_close($stmtU);
                throw new Exception("Failed to update balance.");
            }
            mysqli_stmt_close($stmtU);

            mysqli_commit($connection);
            $success = "Withdrawal request submitted successfully. Processing...";

        } catch (Exception $e) {
            mysqli_rollback($connection);
            $errors[] = $e->getMessage();
            $errors = [$errors[0]]; // show one error only
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
    <title><?= $sitename ?> | Withdrawal </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">
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
                            <span><i class="fi fi-rr-dashboard"></i></span>
                            <span class="nav-text">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="wallets.html">
                            <span><i class="fi fi-rr-wallet"></i></span>
                            <span class="nav-text">Wallets</span>
                        </a>
                    </li>
                    <li>
                        <a href="budgets.html">
                            <span><i class="fi fi-rr-donate"></i></span>
                            <span class="nav-text">Budgets</span>
                        </a>
                    </li>
                    <li>
                        <a href="goals.html">
                            <span><i class="fi fi-sr-bullseye-arrow"></i></span>
                            <span class="nav-text">Goals</span>
                        </a>
                    </li>
                    <li>
                        <a href="profile.html">
                            <span><i class="fi fi-rr-user"></i></span>
                            <span class="nav-text">Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="analytics.html">
                            <span><i class="fi fi-rr-chart-histogram"></i></span>
                            <span class="nav-text">Analytics</span>
                        </a>
                    </li>
                    <li>
                        <a href="support.html">
                            <span><i class="fi fi-rr-user-headset"></i></span>
                            <span class="nav-text">Support</span>
                        </a>
                    </li>
                    <li>
                        <a href="affiliates.html">
                            <span><i class="fi fi-rs-link-alt"></i></span>
                            <span class="nav-text">Affiliates</span>
                        </a>
                    </li>
                    <li>
                        <a href="settings.html">
                            <span><i class="fi fi-rs-settings"></i></span>
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
                                        <h3>Withdrawal</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-xl-12">
                        <div class="row g-4">

                            <!-- Error -->
                            <div class="col-xxl-6 col-xl-6 col-lg-6">
                                <?php if (!empty($errors)): ?>
                                    <div class="alert alert-danger">
                                        <p><?= htmlspecialchars($errors[0]) ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if ($success): ?>
                                    <div class="alert alert-success">
                                        <?= htmlspecialchars($success) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Withdrawal Details</h4>
                                        <span class="badge bg-success">$0</span>
                                    </div>

                                    <div class="card-body">
                                        <form method="post">

                                            <div class="mb-3">
                                                <label class="form-label">Select Account</label>
                                                <select name="which_account" class="form-select">
                                                    <option disabled selected hidden value="">Select</option>
                                                    <option value="USDT(TRC20)">USDT(Trc20)</option>
                                                    <option value="BTC">BTC</option>
                                                    <option value="POLYGON">POLYGON</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Amount to Withdraw</label>
                                                <input name="amount" type="number" step="0.01" class="form-control" placeholder="Amount to Withdraw">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Withdrawal Wallet Address</label>
                                                <input name="wallet_address" type="text" class="form-control" placeholder="withdrawal Wallet Address">
                                            </div>

                                            <button type="submit" name="withdraw" class="btn btn-primary w-100">PLACE WITHDRAWAL</button>

                                        </form>
                                    </div>
                                </div>

                            </div>
                            <!-- End -->

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

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>
