<?php

include("../../server/connection.php");



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
                                    <form action="index.html#">
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
                                        <h3>Dashboard</h3>
                                        <p class="mb-2">Welcome Ekash Finance Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="breadcrumbs"><a href="index.html#">Home </a>
                                        <span><i class="fi fi-rr-angle-small-right"></i></span>
                                        <a href="index.html#">Dashboard</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="stat-widget-1">
                            <h6>Total Balance</h6>
                            <h3>$ 432568</h3>
                            <p>
                                <span class="text-success"><i class="fi fi-rr-arrow-trend-up"></i>2.47%</span>
                                Last month <strong>$24,478</strong>
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="stat-widget-1">
                            <h6>Total Period Change</h6>
                            <h3>$ 245860</h3>
                            <p>
                                <span class="text-success"><i class="fi fi-rr-arrow-trend-up"></i>2.47%</span>
                                Last month <strong>$24,478</strong>
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="stat-widget-1">
                            <h6>Total Period Expenses</h6>
                            <h3>$ 25.35</h3>
                            <p>
                                <span class="text-danger"><i class="fi fi-rr-arrow-trend-down"></i>2.47%</span>
                                Last month <strong>$24,478</strong>
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="stat-widget-1">
                            <h6>Total Period Income</h6>
                            <h3>$ 22.56</h3>
                            <p>
                                <span class="text-success"><i class="fi fi-rr-arrow-trend-up"></i>2.47%</span>
                                Last month <strong>$24,478</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-xl-4 col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Payments History</h4>
                                <a href="index.html#">See more</a>
                            </div>
                            <div class="card-body">
                                <div class="invoice-content">
                                    <ul>
                                        <li class="d-flex justify-content-between active">
                                            <div class="d-flex align-items-center">
                                                <div class="invoice-info">
                                                    <h5 class="mb-0">Electricity</h5>
                                                    <p>5 january 2024</p>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <h5 class="mb-2">+450.00</h5>
                                                <span class=" text-white bg-success">Paid</span>
                                            </div>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="invoice-info">
                                                    <h5 class="mb-0">Internet</h5>
                                                    <p>5 january 2024</p>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <h5 class="mb-2">+450.00</h5>
                                                <span class=" text-white bg-warning">Due</span>
                                            </div>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="invoice-info">
                                                    <h5 class="mb-0">Apple Music</h5>
                                                    <p>5 january 2024</p>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <h5 class="mb-2">+450.00</h5>
                                                <span class=" text-white bg-danger">Cancel</span>
                                            </div>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="invoice-info">
                                                    <h5 class="mb-0">Groceries</h5>
                                                    <p>5 january 2024</p>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <h5 class="mb-2">+450.00</h5>
                                                <span class=" text-white bg-success">Paid</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Transaction History</h4>
                            </div>
                            <div class="card-body">
                                <div class="transaction-table">
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-responsive-sm">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Date</th>
                                                    <th>Description</th>
                                                    <th>Amount</th>
                                                    <th>Currency</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <span class="table-category-icon"><i class="bg-emerald-500 fi fi-rr-barber-shop"></i>
                                                            Beauty</span>
                                                    </td>
                                                    <td>
                                                        12.12.2023
                                                    </td>
                                                    <td>
                                                        Grocery Items and Beverage soft drinks
                                                    </td>
                                                    <td>
                                                        -32.20
                                                    </td>
                                                    <td>USD</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="table-category-icon"><i class="bg-teal-500 fi fi-rr-receipt"></i> Bills &
                                                            Fees</span>
                                                    </td>
                                                    <td>
                                                        12.12.2023
                                                    </td>
                                                    <td>
                                                        Grocery Items and Beverage soft drinks
                                                    </td>
                                                    <td>
                                                        -32.20
                                                    </td>
                                                    <td>USD</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="table-category-icon"><i class="bg-cyan-500 fi fi-rr-car-side"></i> Car</span>
                                                    </td>
                                                    <td>
                                                        12.12.2023
                                                    </td>
                                                    <td>
                                                        Grocery Items and Beverage soft drinks
                                                    </td>
                                                    <td>
                                                        -32.20
                                                    </td>
                                                    <td>USD</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="table-category-icon"><i class="bg-sky-500 fi fi-rr-graduation-cap"></i>
                                                            Education</span>
                                                    </td>
                                                    <td>
                                                        12.12.2023
                                                    </td>
                                                    <td>
                                                        Grocery Items and Beverage soft drinks
                                                    </td>
                                                    <td>
                                                        -32.20
                                                    </td>
                                                    <td>USD</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="table-category-icon"><i class="bg-blue-500 fi fi-rr-clapperboard-play"></i>
                                                            Entertainment</span>
                                                    </td>
                                                    <td>
                                                        12.12.2023
                                                    </td>
                                                    <td>
                                                        Grocery Items and Beverage soft drinks
                                                    </td>
                                                    <td>
                                                        -32.20
                                                    </td>
                                                    <td>USD</td>
                                                </tr>
                                            </tbody>
                                        </table>
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
                                <a href="index.html#">Ekash</a> I All Rights Reserved
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="footer-social">
                            <ul>
                                <li><a href="index.html#"><i class="fi fi-brands-facebook"></i></a></li>
                                <li><a href="index.html#"><i class="fi fi-brands-twitter"></i></a></li>
                                <li><a href="index.html#"><i class="fi fi-brands-linkedin"></i></a></li>
                                <li><a href="index.html#"><i class="fi fi-brands-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/toastr/toastr.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/toastr/toastr-init.js"></script>
    <script src="<?php echo $domain ?>/vendor/circle-progress/circle-progress.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/circle-progress/circle-progress-init.js"></script>
    <script src="<?php echo $domain ?>/vendor/chartjs/chartjs.js"></script>
    <script src="<?php echo $domain ?>/js/plugins/chartjs-bar-income-vs-expense.js"></script>
    <script src="<?php echo $domain ?>/js/plugins/chartjs-bar-weekly-expense.js"></script>
    <script src="<?php echo $domain ?>/js/plugins/chartjs-profile-wallet.js"></script>
    <script src="<?php echo $domain ?>/js/plugins/chartjs-profile-wallet2.js"></script>
    <script src="<?php echo $domain ?>/js/plugins/chartjs-profile-wallet3.js"></script>
    <script src="<?php echo $domain ?>/js/plugins/chartjs-profile-wallet4.js"></script>
    <!--  -->
    <!--  -->
    <script src="<?php echo $domain ?>/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="<?php echo $domain ?>/js/plugins/perfect-scrollbar-init.js"></script>
    <script src="<?php echo $domain ?>/vendor/circle-progress/circle-progress.min.js"></script>
    <script src="<?php echo $domain ?>/js/plugins/circle-progress-init.js"></script>
    <script src="<?php echo $domain ?>/js/scripts.js"></script>
</body>

</html>