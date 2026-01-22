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
        <?php include("../include/nav.php") ?>
        <?php include("../include/sidenav.php") ?>
        <div class="content-body">
            <div class="verification">
                <div class="container">
                    <div class="row justify-content-center h-100 align-items-center">
                        <div class="col-xl-8 col-md-12">

                            <?php


                            // Fetch all plans
                            $sql = "SELECT * FROM investment_plans ORDER BY id DESC";
                            $result = mysqli_query($connection, $sql);
                            ?>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Investment Plans</h4>
                                </div>
                                <div class="card-body">

                                    <?php if (mysqli_num_rows($result) === 0): ?>

                                        <p class="text-muted">No investment plans added yet.</p>

                                    <?php else: ?>

                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                            <div class="verify-content mb-3">

                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon-circle bg-primary text-white">
                                                        <i class="fi fi-rs-chart-line-up"></i>
                                                    </span>

                                                    <div class="primary-number">
                                                        <h5 class="mb-0">
                                                            <?php echo htmlspecialchars($row['plan_name']); ?>
                                                        </h5>

                                                        <small>
                                                            Duration: <?php echo $row['duration']; ?> days
                                                        </small><br>

                                                        <small>
                                                            Profit/Day: <?php echo $row['profit_per_day']; ?>USD
                                                        </small><br>

                                                        <small>
                                                            Total Profit: <?php echo $row['total_profit']; ?>USD
                                                        </small>
                                                    </div>
                                                </div>

                                                <a href="manage-investment.php?id=<?php echo $row['id']; ?>"
                                                    class="btn btn-outline-primary">
                                                    Manage
                                                </a>
                                            </div>

                                            <hr class="border opacity-1">
                                        <?php endwhile; ?>

                                    <?php endif; ?>

                                    <!-- Add New Plan -->
                                    <div class="mt-5">
                                        <a href="./add/" class="btn btn-primary m-2">
                                            Add New Investment Plan
                                        </a>
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