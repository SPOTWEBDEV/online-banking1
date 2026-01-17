<?php
include("../../server/connection.php");

$sql = "SELECT * FROM payment_account";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


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
                        <div class="col-xl-8 col-md-12">

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Your Payment Accounts</h4>
                                </div>

                               
                                <div class="card-body">

                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                                        <?php if ($row['type'] === 'bank'): ?>
                                            <!-- BANK ACCOUNT CARD -->
                                            <div class="verify-content mb-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon-circle bg-primary text-white">
                                                        <i class="fi fi-rs-bank"></i>
                                                    </span>

                                                    <div class="primary-number">
                                                        <h5 class="mb-0"><?php echo htmlspecialchars($row['fullname']); ?></h5>
                                                        <small>Account ****<?php echo substr($row['account_number'], -4); ?></small>
                                                        <br>
                                                        <span class="text-success">Verified</span>
                                                    </div>
                                                </div>

                                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger">
                                                    Delete
                                                </a>
                                            </div>

                                            <hr class="border opacity-1">

                                        <?php elseif ($row['type'] === 'crypto'): ?>
                                            <!-- CRYPTO WALLET CARD -->
                                            <div class="verify-content mb-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon-circle bg-primary text-white">
                                                        <i class="fi fi-rr-credit-card"></i>
                                                    </span>

                                                    <div class="primary-number">
                                                        <h5 class="mb-0"><?php echo htmlspecialchars($row['network']); ?> Wallet</h5>
                                                        <small>Address: ****<?php echo substr($row['wallet_address'], -6); ?></small>
                                                        <br>
                                                        <span class="text-success">Verified</span>
                                                    </div>
                                                </div>

                                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger">
                                                    Delete
                                                </a>
                                            </div>

                                            <hr class="border opacity-1">

                                        <?php endif; ?>

                                    <?php endwhile; ?>


                                    <!-- Add New Buttons -->
                                    <div class="mt-5">
                                        <a href="./add?type=bank" class="btn btn-primary m-2">Add New Bank</a>
                                        <a href="./add/?type=crypto" class="btn btn-primary m-2">Add New Crypto</a>
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