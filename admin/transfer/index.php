<?php
include("../../server/connection.php");

// if (!isset($_SESSION['user_id'])) {
//     header("location: ./signin.php");
//     exit;
// }

$user_id = $_SESSION['user_id'];
?>


<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | Transfer-History </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>/vendor/toastr/toastr.min.css">
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
                                        <h3>Transfer History</h3>
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
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>ACCOUNT HOLDER</th>
                                                <th>RECEIVER ACC NO / RECEIVER BANK</th>
                                                <th>AMOUNT</th>
                                                <th>NARRATION</th>
                                                <th>DATE</th>
                                                <th>STATUS</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $query = $connection->query("SELECT bank_transfers.* , users.fullname  FROM bank_transfers , users WHERE users.id = bank_transfers.user_id ORDER BY id DESC");
                                            if ($query->num_rows > 0) {
                                                while ($transfer = $query->fetch_assoc()) { ?>

                                                    <tr>
                                                        <td>1</td>
                                                        <td><?php echo $transfer['fullname'] ?></td>
                                                        <td><?php echo $transfer['receiver_account_number'] ?> <br>  <?php echo $transfer['receiver_bank'] ?></td>
                                                        <td>$<?php echo $transfer['amount']  ?></td>
                                                        <td>Payment for services</td>
                                                        <td><?php echo $transfer['created_at']  ?></td>
                                                        <td>
                                                            <span class="badge text-white <?php
                                                                                            echo ($transfer['status'] == 'pending')
                                                                                                ? 'bg-warning'
                                                                                                : (($transfer['status'] == 'completed')
                                                                                                    ? 'bg-success'
                                                                                                    : 'bg-danger'); ?>">
                                                                <?php echo ucfirst($transfer['status']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="./details/?id=<?php echo $transfer['id'] ?>"> <span class="badge p-2 bg-info text-white">View Details</span></a>
                                                        </td>

                                                    </tr>

                                            <?php }
                                            }

                                            ?>


                                        </tbody>
                                    </table>
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
                    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
                    <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
                    <!--  -->
                    <!--  -->
                    <script src="<?php echo $domain ?>/js/scripts.js"></script>
</body>

</html>