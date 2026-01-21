<?php
include("../../../server/connection.php");




include("../../controllers/add-investment.php");


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
        <?php include("../../include/nav.php") ?>
        <?php include("../../include/sidenav.php") ?>
        <div class="content-body">
            <div class="verification">
                <div class="container">
                    <div class="row justify-content-center h-100 align-items-center">
                        <div class="col-12">

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Add Investment Plan</h4>
                                </div>


                                <div class="card-body">
                                    <form method="POST">

                                        <!-- PLAN NAME -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label class="mr-sm-2">Plan Name</label>
                                                <input type="text" name="plan_name" class="form-control" placeholder="Gold Plan">
                                            </div>
                                        </div>

                                        <!-- DURATION -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label class="mr-sm-2">Duration (days)</label>
                                                <input type="number" name="duration" class="form-control" placeholder="30">
                                            </div>
                                        </div>

                                        <!-- PROFIT PER DAY -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label class="mr-sm-2">Profit Per Day (USD)</label>
                                                <input type="number" step="0.01" name="profit_per_day" class="form-control" placeholder="2.5">
                                            </div>
                                        </div>

                                        <!-- TOTAL PROFIT -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label class="mr-sm-2">Total Profit (USD)</label>
                                                <input type="number" step="0.01" name="total_profit" class="form-control" placeholder="75">
                                            </div>
                                        </div>

                                        <!-- SAVE BUTTON -->
                                        <div class="col-12 mt-4">
                                            <button name="investment" class="btn btn-success w-100">Save Investment Plan</button>
                                        </div>

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