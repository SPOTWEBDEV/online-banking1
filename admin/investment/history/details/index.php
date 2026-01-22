<?php
include("../../../../server/connection.php");





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
        <?php include("../../../include/nav.php") ?>

        <!-- side nav -->

        <?php include("../../../include/sidenav.php") ?>
        <div class="content-body">
            <?php
            // --- Securely get the ID ---
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                die("Invalid investment ID.");
            }

            $id = intval($_GET['id']);


            // --- Prepare SQL Query ---
            $sql = "
    SELECT 
        investments.*, 
        users.fullname,
        users.email,
        investment_plans.plan_name,
        investment_plans.duration,
        investment_plans.profit_per_day,
        investment_plans.total_profit
    FROM investments
    LEFT JOIN users 
        ON users.id = investments.user_id
    LEFT JOIN investment_plans 
        ON investment_plans.id = investments.plan_id
    WHERE investments.id = ?
";

            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Fetch investment row
            $inv = mysqli_fetch_assoc($result);

            // If not found
            if (!$inv) {
                echo "<div class='container'><div class='alert alert-danger'>Investment not found.</div></div>";
                die();
            }
            ?>

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-4">
                                    <div class="page-title-content">
                                        <h3>Investment Details</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="breadcrumbs">
                                        <a href="<?= $domain ?>/admin/dashboard/">Home</a>
                                        <span><i class="fi fi-rr-angle-small-right"></i></span>
                                        <a href="<?= $domain ?>/admin/investments/details">Investment Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            Investment Details for <?= htmlspecialchars($inv['fullname']) ?>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>

                                    <tr>
                                        <td><strong>Full Name:</strong></td>
                                        <td><?= htmlspecialchars($inv['fullname']) ?></td>
                                    </tr>

                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td><?= htmlspecialchars($inv['email']) ?></td>
                                    </tr>

                                    <tr>
                                        <td><strong>Amount Invested:</strong></td>
                                        <td>$<?= number_format($inv['amount_invested'], 2) ?></td>
                                    </tr>

                                    <tr>
                                        <td><strong>Plan Name:</strong></td>
                                        <td><?= htmlspecialchars($inv['plan_name']) ?></td>
                                    </tr>

                                    <tr>
                                        <td><strong>Duration:</strong></td>
                                        <td><?= htmlspecialchars($inv['duration']) ?> days</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Profit Per Day:</strong></td>
                                        <td>$<?= number_format($inv['profit_per_day'], 2) ?></td>
                                    </tr>

                                    <tr>
                                        <td><strong>Total Expected Profit:</strong></td>
                                        <td>$<?= number_format($inv['total_profit'], 2) ?></td>
                                    </tr>

                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge 
                                    <?= $inv['status'] == 'active' ? 'bg-success' : 'bg-warning' ?>">
                                                <?= htmlspecialchars($inv['status']) ?>
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><strong>Date Created:</strong></td>
                                        <td><?= htmlspecialchars($inv['created_at']) ?></td>
                                    </tr>

                                </tbody>
                            </table>
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