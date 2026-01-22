<?php
include("../server/connection.php");


if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("location: {$domain}/auth/sign_in/");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

$errors = [];
$success = "";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['activate_investment'])) {

    $plan_name = isset($_POST['plan_name']) ? trim($_POST['plan_name']) : '';
    $amount_invested = isset($_POST['amount_invested']) ? trim($_POST['amount_invested']) : '';


    if ($plan_name === '') $errors[] = "Plan name missing.";
    if ($amount_invested === '' || !is_numeric($amount_invested) || (float)$amount_invested <= 0) $errors[] = "Enter a valid amount.";

    if (empty($errors)) {

        $insert_sql = "
            INSERT INTO investments
            (user_id, plan_id, amount_invested)
            VALUES (?, ?, ?)
        ";

        $stmt = mysqli_prepare($connection, $insert_sql);
        if (!$stmt) {
            $errors[] = "Server error: failed to prepare statement.";
        } else {

            $amount_decimal = (float) $amount_invested;

            mysqli_stmt_bind_param(
                $stmt,
                "isd",
                $user_id,
                $plan_name,
                $amount_decimal,
            );

            if (!mysqli_stmt_execute($stmt)) {
                $errors[] = "Activation failed: " . mysqli_stmt_error($stmt);
            } else {
                $success = "Investment activated successfully!";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

/**
 * FETCH INVESTMENTS
 */
$investments = [];

$inv_sql = "
    SELECT
        id,
        plan_name,
        profit_per_day,
        total_profit,
        start_date,
        end_date
    FROM investments
    ORDER BY id DESC
";

$inv_stmt = mysqli_prepare($connection, $inv_sql);
if (!$inv_stmt) {
    $errors[] = "Server error: failed to prepare investments query.";
} else {
    mysqli_stmt_execute($inv_stmt);
    $inv_result = mysqli_stmt_get_result($inv_stmt);

while ($row = mysqli_fetch_assoc($inv_result)) {
    $investments[] = $row;
}
}

mysqli_stmt_close($inv_stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= htmlspecialchars($sitename) ?> | Investment</title>

    <link rel="icon" type="image/png" sizes="16x16" href="<?= $domain ?>/images/favicon.png">
    <link rel="stylesheet" href="<?= $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?= $domain ?>/vendor/toastr/toastr.min.css">

    <!-- Extra styles to match your screenshot layout -->
    <style>
        .invest-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
        }

        @media (max-width: 991px) {
            .invest-grid {
                grid-template-columns: 1fr;
            }
        }

        .invest-card {
            background: white;
            border-radius: 20px;
            padding: 22px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
        }

        .invest-top {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 22px;
        }

        .invest-icon {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            background: #22282d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3bd3ff;
            font-size: 22px;
        }

        .invest-title {
            margin: 0;
            font-weight: 700;
            letter-spacing: .5px;
            color: #1f2429;
            text-transform: uppercase;
            font-size: 18px;
        }

        .invest-days {
            color: rgba(255, 255, 255, 0.55);
            margin-top: 2px;
            font-size: 14px;
        }

        .invest-label {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 8px;
            font-size: 15px;
        }

        .invest-amount-row {
            display: flex;
            align-items: baseline;
            gap: 12px;
            margin-bottom: 6px;
            font-size: 24px;
            font-weight: 800;
            color: #1f2429;
            flex-wrap: wrap;
        }

        .down {
            color: #ff5b5b;
            font-size: 18px;
            font-weight: 700;
        }

        .up {
            color: #2cff97;
            font-size: 18px;
            font-weight: 700;
        }

        .invest-total {
            color: #2cff97;
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 14px;
        }

        .invest-progress-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 6px;
        }

        .invest-progress {
            width: 100%;
            height: 8px;
            background: #2a2f35;
            border-radius: 20px;
            overflow: hidden;
        }

        .invest-progress>div {
            width: 50%;
            height: 100%;
            background: #29c5ff;
        }

        .invest-progress-pct {
            color: rgba(255, 255, 255, 0.5);
            font-weight: 600;
            min-width: 45px;
            text-align: right;
        }

        .inv-meta {
            margin-top: 14px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        /* ✅ input + button styles like screenshot */
        .invest-input {
            width: 100%;
            height: 56px;
            border-radius: 12px;
            background: #1f2429;
            border: 1px solid rgba(255, 255, 255, 0.08);
            outline: none;
            padding: 0 16px;
            color: #e9eef2;
            font-size: 16px;
            margin-top: 16px;
            margin-bottom: 18px;
        }

        .invest-btn {
            width: 100%;
            height: 56px;
            border-radius: 12px;
            border: none;
            background: #1f89a8;
            color: #e9eef2;
            font-weight: 800;
            letter-spacing: .8px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>

<body class="dashboard">
    <div id="main-wrapper">

        <!-- HEADER (kept intact) -->
        <?php include("../include/header.php") ?>

        <!-- SIDEBAR (kept intact) -->
        <?php include("../include/sidenav.php") ?>

        <!-- CONTENT -->
        <div class="content-body">
            <div class="container">

                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-4">
                                    <div class="page-title-content">
                                        <h3>Investment</h3>
                                        <p class="mb-2">Welcome To <?= htmlspecialchars($sitename) ?> Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="./investment_history/"><button class="btn btn-primary mr-2">View Investment History</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alerts -->
                <?php if (!empty($errors)) { ?>
                    <div class="alert alert-danger mt-3">
                        <?php foreach ($errors as $error) { ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if (!empty($success)) { ?>
                    <div class="alert alert-success mt-3">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php } ?>

                <!-- INVESTMENTS GRID -->
                <div class="invest-grid mt-3">

                    <?php if (!empty($investments)) : ?>
                        <?php foreach ($investments as $inv) :

                            $duration = $inv['duration'];
                            $plan_name = $inv['plan_name'];
                            $profit_per_day = (float) $inv['profit_per_day'];
                            $total_profit = (float) $inv['total_profit'];
                            $id = (int) $inv['id'];





                            $progress = (int) min(100, round((2 / $duration) * 100));
                        ?>

                            <div class="invest-card">
                                <div class="invest-top">
                                    <div class="invest-icon">
                                        <i class="fi fi-rr-chart-histogram"></i>
                                    </div>
                                    <div>
                                        <h4 class="invest-title"><?= htmlspecialchars($plan_name) ?></h4>
                                        <div class="invest-days"><?= $duration ?> days</div>
                                    </div>
                                </div>

                                <div class="invest-label">Plan Amount</div>

                                <div class="invest-amount-row">
                                    <span class="up">↓</span>
                                    <span>$<?= number_format($profit_per_day, 0) ?> Per Day</span>
                                    <span class="up">↓</span>
                                    <span>$<?= number_format($total_profit, 0) ?> Total Profit</span>


                                    <form method="post">
                                        <input type="hidden" name="plan_name" value="<?= htmlspecialchars($id) ?>">
                                        <input class="invest-input" type="number" name="amount_invested" placeholder="Enter Amount" required>
                                        <button class="invest-btn" type="submit" name="activate_investment">ACTIVATE</button>
                                    </form>


                                </div>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-warning">No active investments found.</div>
                        <?php endif; ?>

                            </div>

                </div>
            </div>

            <!-- FOOTER (kept intact) -->
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
                                    <a href="#"><?= htmlspecialchars($sitename) ?></a> | All Rights Reserved
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

        <script src="<?= $domain ?>/vendor/jquery/jquery.min.js"></script>
        <script src="<?= $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?= $domain ?>/js/scripts.js"></script>
</body>

</html>