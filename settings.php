<?php
include("./server/connection.php");
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: ./signin.php");
}
?>

<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | setting</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">
    <div id="preloader" class="preloader-wrapper">
        <div class="loader">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="header">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="header-content">
                            <div class="header-left">
                                <div class="brand-logo"><a class="mini-logo" href="index.html"><img src="/images/logoi.png" alt="" width="40"></a></div>
                                <div class="search">
                                    <form action="settings.html#">
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
                                        <h3>Settings</h3>
                                        <p class="mb-2">Welcome <?= $sitename ?> Finance Management</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-xl-12">
                        <div class="settings-menu">
                            <a href="settings.html">Account</a>

                        </div>
                        <div class="row">

                            <?php
                            $user_id = $_SESSION['user_id'];
                            $success = "";
                            $errors = [];


                            if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_FILES['profile_image'])) {
                                $file = $_FILES['profile_image'];


                                if ($file['error'] !== 0) {
                                    $errors[] = "Error uploading file.";
                                } else {
                                    if ($file['size'] > 2 * 1024 * 1024) {
                                        $errors[] = "File size must be less than 2MB.";
                                    }


                                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                                    $mimeType = $finfo->file($file['tmp_name']);



                                    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                                    if (!in_array($mimeType, $allowedTypes)) {
                                        $errors[] = "Only JPG, PNG, and GIF images are allowed.";
                                    }


                                    if (empty($errors)) {
                                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                                        $newFileName = uniqid('profile_', true) . "." . $ext;
                                        $uploadDir = __DIR__ . "/images/avatar/";

                                        if (!is_dir($uploadDir)) {
                                            mkdir($uploadDir, 0755, true);
                                        }

                                        $destination = $uploadDir . $newFileName;

                                        if (move_uploaded_file($file['tmp_name'], $destination)) {

                                            $relativePath = "/images/avatar/" . $newFileName;
                                            $upload_path = "/images/avatar/" . $newFileName;
                                            $sql = "UPDATE users SET user_profile = ? WHERE id = ?";
                                            $stmt = mysqli_prepare($connection, $sql);
                                            mysqli_stmt_bind_param($stmt, "si", $upload_path, $user_id);

                                            if (mysqli_stmt_execute($stmt)) {
                                                $success = "Profile image uploaded successfully!";
                                            } else {
                                                $errors[] = "Failed to update database.";
                                            }
                                            mysqli_stmt_close($stmt);
                                        } else {
                                            $errors[] = "Failed to move uploaded file.";
                                        }
                                    }
                                }
                            }

                            // / Fetch current profile
                            $sql2 = "SELECT fullname, user_profile FROM users WHERE id = ?";
                            $stmt2 = mysqli_prepare($connection, $sql2);
                            mysqli_stmt_bind_param($stmt2, "i", $user_id);
                            mysqli_stmt_execute($stmt2);
                            $result = mysqli_stmt_get_result($stmt2);
                            $user = mysqli_fetch_assoc($result);
                            mysqli_stmt_close($stmt2);
                            $userProfile = $user['user_profile'];
                            $userName = $user['fullname'];

                            ?>




                            <div class="row">
                                <div class="col-xxl-6 col-xl-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Upload Profile</h4>
                                        </div>
                                        <div class="card-body">
                                            <form action="#" method="post" enctype="multipart/form-data">
                                                <div class="row g-3">
                                                    <div class="col-xxl-12 col-12 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <img class="me-3 rounded-circle me-0 me-sm-3" src=".<?= $userProfile ?? 'images/avatar/3.jpg'  ?> " width="55" height="55" alt="">
                                                            <div class="media-body">
                                                                <h4 class="mb-0"><?= $userName ?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-12 col-12 mb-3">
                                                        <div class="form-file">
                                                            <input type="file" class="form-file-input" name="profile_image" id="customFile">
                                                            <label class="form-file-label" for="customFile">
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-12 col-12 mb-3">
                                                        <button class="btn btn-primary">Upload</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <?php



                                //Handle password change
                                if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['old_password'], $_POST['new_password'])) {

                                    $old_password = trim($_POST['old_password']);
                                    $new_password = trim($_POST['new_password']);

                                   
                                    if (empty($old_password) || empty($new_password)) {
                                        $errors[] = "All fields are required.";
                                    }
                                    if (strlen($new_password) < 6) {
                                          $errors[] = "minimum of six character required.";  
                                    }
 

                                    $sql3 = "SELECT password FROM users WHERE id = ?";
                                    $stmt3 = mysqli_prepare($connection, $sql3);
                                    mysqli_stmt_bind_param($stmt3, "s", $user_id);
                                    mysqli_stmt_execute($stmt3);
                                    $result = mysqli_stmt_get_result($stmt3);
                                    $user = mysqli_fetch_assoc($result);
                                    mysqli_stmt_close($stmt3);

                                    if (!$user) {
                                        $errors[] = "User not found.";
                                    } else {
                                        $current_hash = $user['password'];


                                        if (!password_verify($old_password, $current_hash)) {
                                            $errors[] = "Old password is incorrect.";
                                        }
                                    }


                                    if (empty($errors)) {
                                        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);

                                        $sql_update = "UPDATE users SET password = ? WHERE id = ?";
                                        $stmt_update = mysqli_prepare($connection, $sql_update);
                                        mysqli_stmt_bind_param($stmt_update, "si", $new_hash, $user_id);

                                        if (mysqli_stmt_execute($stmt_update)) {
                                            $success = "Password changed successfully!";
                                        } else {
                                            $errors[] = "Failed to update password.";
                                        }
                                        mysqli_stmt_close($stmt_update);
                                    }
                                }

                                ?>



                                <div class="col-xxl-6 col-xl-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Password Setting</h4>
                                        </div>
                                        <div class="card-body">
                                            <form action="#" method="post">
                                                <?php if (!empty($errors)): ?>
                                                    <div class="alert alert-danger">
                                                        <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (!empty($success)): ?>
                                                    <div class="alert alert-success"><?= $success ?></div>
                                                <?php endif; ?>

                                                <div class="row g-3">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Old Password</label>
                                                        <input type="password" name="old_password" class="form-control" placeholder="**********" required>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">New Password</label>
                                                        <input type="password" name="new_password" class="form-control" placeholder="**********" required>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <button type="submit" class="btn btn-primary">Change</button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>




                                <!-- don't delete we will use it later just commented it out -->
                              <div class="row g-4">

    <!-- LEFT SIDE -->
    <div class="col-xxl-6 col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Select Deposit Method</h4>
            </div>
            <div class="card-body">
                <form method="post" id="depositForm">

                    <div class="mb-3">
                        <label class="form-label">Method</label>
                        <select class="form-select" id="method">
                            <option value="">Select Method</option>
                            <option value="wallet">Wallet</option>
                            <option value="bank">Bank Transfer</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select class="form-select" id="type">
                            <option value="">Select Type</option>
                            <option value="USDT">USDT</option>
                            <option value="BTC">BTC</option>
                            <option value="POLYGON">POLYGON</option>
                        </select>
                    </div>

                </form>
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
                <p><strong>Crypto Type:</strong> <span id="cryptoType">---</span></p>
                <p><strong>Wallet Address:</strong></p>
                <p id="walletAddress" class="text-break text-muted">---</p>
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
                    <input type="number" class="form-control" placeholder="Enter amount">
                </div>
                <button class="btn btn-primary">Submit</button>
            </div>
        </div>
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
                                <a href="settings.html#"><?= $sitename ?></a> I All Rights Reserved
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="footer-social">
                            <ul>
                                <li><a href="settings.html#"><i class="fi fi-brands-facebook"></i></a></li>
                                <li><a href="settings.html#"><i class="fi fi-brands-twitter"></i></a></li>
                                <li><a href="settings.html#"><i class="fi fi-brands-linkedin"></i></a></li>
                                <li><a href="settings.html#"><i class="fi fi-brands-youtube"></i></a></li>
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