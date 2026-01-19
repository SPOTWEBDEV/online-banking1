<?php
include("../../../server/connection.php");




include("../../controllers/management.php");


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ekash : Personal Finance Management Admin Dashboard HTML Template</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>/vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">
    <div id="main-wrapper">
        <div class="header">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="header-content">
                            <div class="header-left">
                                <div class="brand-logo"><a class="mini-logo" href="index.html"><img src="<?php echo $domain ?>/images/logoi.png" alt="" width="40"></a></div>
                                <div class="search">
                                    <form action="add-bank.html#">
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
                                                <span class="thumb"><img class="rounded-full" src="<?php echo $domain ?>/images/avatar/3.jpg" alt=""></span>
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
            <div class="brand-logo"><a class="full-logo" href="index.html"><img src="<?php echo $domain ?>/images/logoi.png" alt="" width="30"></a>
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
            <div class="verification section-padding">
                <div class="container h-100">
                    <div class="row justify-content-center h-100 align-items-center">
                        <div class="col-xl-5 col-md-6">

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Add User Investment</h4>
                                </div>

                                <div class="card-body">
                                    <form method="POST">

                                        <!-- USER SELECT -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label class="mr-sm-2">Select User</label>
                                                <select name="user_id" class="form-control">
                                                    <option selected>Select User</option>
                                                    <?php
                                                    $users = mysqli_query($connection, "SELECT id, fullname FROM users ORDER BY fullname ASC");
                                                    while ($u = mysqli_fetch_assoc($users)) {
                                                        echo '<option value="' . $u['id'] . '">' . $u['fullname'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- PLAN NAME -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label class="mr-sm-2">Plan Name</label>
                                                <select name="plan_name" class="form-control">
                                                    <option selected>Select Plan</option>
                                                    <?php
                                                    $users = mysqli_query($connection, "SELECT plan_name FROM investment_plans ORDER BY id ASC");
                                                    while ($u = mysqli_fetch_assoc($users)) {
                                                        echo '<option value="' . $u['id'] . '">' . $u['plan_name'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- AMOUNT INVESTED -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label class="mr-sm-2">Amount Invested</label>
                                                <input type="number" name="amount_invested" class="form-control" placeholder="5000">
                                            </div>
                                        </div>

                                        <!-- DAILY PROFIT -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label class="mr-sm-2">Daily Profit (%)</label>
                                                <input type="number" step="0.01" name="daily_profit" class="form-control" placeholder="2.5">
                                            </div>
                                        </div>

                                        <!-- TOTAL PROFIT -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label class="mr-sm-2">Total Profit (%)</label>
                                                <input type="number" step="0.01" name="total_profit" class="form-control" placeholder="75">
                                            </div>
                                        </div>

                                        <!-- START DATE -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label class="mr-sm-2">Start Date</label>
                                                <input type="date" name="start_date" class="form-control">
                                            </div>
                                        </div>

                                        <!-- END DATE -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label class="mr-sm-2">End Date</label>
                                                <input type="date" name="end_date" class="form-control">
                                            </div>
                                        </div>

                                        <!-- SAVE BUTTON -->
                                        <div class="col-12 mt-4">
                                            <button name="add_user_investment" class="btn btn-success w-100">Save Investment</button>
                                        </div>

                                    </form>
                                </div>
                            </div>

                            <div class="card">


                                <div class="card-header">
                                    <h4 class="card-title">Add Loan Request</h4>
                                </div>

                                <div class="card-body">

                                    <form method="POST" class="form-style">

                                        <label>User</label>
                                        <select name="user_id" class="form-control">
                                            <option selected>Select User</option>
                                            <?php
                                            $users = mysqli_query($connection, "SELECT id, fullname FROM users ORDER BY fullname ASC");
                                            while ($u = mysqli_fetch_assoc($users)) {
                                                echo '<option value="' . $u['id'] . '">' . $u['fullname'] . '</option>';
                                            }
                                            ?>
                                        </select>

                                        <label>Loan Amount</label>
                                        <input type="number" name="loan_amount" class="form-control" required>

                                        <label>Loan Duration</label>
                                        <input type="text" name="loan_duration" class="form-control" placeholder="e.g. 12 months" required>

                                        <label>Loan Reason</label>
                                        <textarea name="loan_reason" class="form-control" required></textarea>

                                        <label>Monthly Income</label>
                                        <input type="number" name="monthly_income" class="form-control" required>

                                        <label>Employment Status</label>
                                        <input type="text" name="employment_status" class="form-control" required>

                                        <label>Bank Name</label>
                                        <input type="text" name="bank_name" class="form-control" required>

                                        <label>Account Number</label>
                                        <input type="text" name="account_number" class="form-control" required>

                                        <label>Interest Rate (%)</label>
                                        <input type="number" step="0.01" name="interest_rate" class="form-control" required>

                                        <label>Total Payable</label>
                                        <input type="number" step="0.01" name="total_payable" class="form-control" required>

                                        <button type="submit" name="submit" class="btn-submit mt-3 btn btn-success w-100">Submit Loan Request</button>

                                    </form>


                                </div>




                            </div>

                            <div class="card">


                                <div class="card-header">
                                    <h4 class="card-title">Add Deposit To User</h4>
                                </div>

                                <div class="card-body">

                                    <form method="POST" class="form-style">

                                        <label>User</label>
                                        <select name="user_id" class="form-control" required>
                                            <option value="">Select User</option>
                                            <?php while ($u = $users->fetch_assoc()) { ?>
                                                <option value="<?= $u['id'] ?>">
                                                    <?= $u['fullname'] ?> (<?= $u['email'] ?>)
                                                </option>
                                            <?php } ?>
                                        </select>

                                        <label>Method (e.g. Bank Transfer, Crypto, Paystack)</label>
                                        <input type="text" name="method" class="form-control" required>

                                        <label>Type (e.g. Deposit / Withdrawal)</label>
                                        <input type="text" name="type" class="form-control" required>

                                        <label>Amount</label>
                                        <input type="number" step="0.01" name="amount" class="form-control" required>

                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approved</option>
                                            <option value="failed">Failed</option>
                                        </select>

                                        <button type="submit" name="add_deposit" class="btn-submit mt-3 btn btn-success w-100">Submit Deposit</button>

                                    </form>


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
                                <a href="add-bank.html#">Ekash</a> I All Rights Reserved
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="footer-social">
                            <ul>
                                <li><a href="add-bank.html#"><i class="fi fi-brands-facebook"></i></a></li>
                                <li><a href="add-bank.html#"><i class="fi fi-brands-twitter"></i></a></li>
                                <li><a href="add-bank.html#"><i class="fi fi-brands-linkedin"></i></a></li>
                                <li><a href="add-bank.html#"><i class="fi fi-brands-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


    <script src="<?php echo $domain ?>/js/scripts.js"></script>
</body>

</html>