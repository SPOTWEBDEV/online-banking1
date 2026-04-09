<?php
include("../server/connection.php");
include("../server/auth/client.php");



$user_balance = (float) $client['balance'];
$errors = [];


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['activate_investment'])) {

    $plan_id = isset($_POST['plan_id']) ? trim($_POST['plan_id']) : '';
    $amount_invested = isset($_POST['amount_invested']) ? trim($_POST['amount_invested']) : '';

    if ($plan_id === '' || !ctype_digit($plan_id)) $errors[] = "Plan ID missing.";
    if ($amount_invested === '' || !is_numeric($amount_invested) || (float)$amount_invested <= 0) $errors[] = "Enter a valid amount.";

    if (empty($errors)) {

        $amount_decimal = (float) $amount_invested;
        $plan_id_int = (int) $plan_id;


        if ($user_balance < $amount_decimal) {
            $errors[] = "Insufficient balance";
        } else {

            mysqli_begin_transaction($connection);

            try {

                $insert_sql = "
                    INSERT INTO investments (user_id, plan_id, amount_invested)
                    VALUES (?, ?, ?)
                ";
                $stmt = mysqli_prepare($connection, $insert_sql);
                if (!$stmt) {
                    throw new Exception("Server error: failed to prepare investment insert.");
                }

                mysqli_stmt_bind_param($stmt, "iid", $user_id, $plan_id_int, $amount_decimal);

                if (!mysqli_stmt_execute($stmt)) {
                    $err = mysqli_stmt_error($stmt);
                    mysqli_stmt_close($stmt);
                    throw new Exception("Activation failed: " . $err);
                }
                mysqli_stmt_close($stmt);


                $upd_stmt = mysqli_prepare($connection, "UPDATE users SET balance = balance - ? WHERE id = ? AND balance >= ?");
                if (!$upd_stmt) {
                    throw new Exception("Server error: failed to update balance.");
                }

                mysqli_stmt_bind_param($upd_stmt, "did", $amount_decimal, $user_id, $amount_decimal);

                if (!mysqli_stmt_execute($upd_stmt) || mysqli_stmt_affected_rows($upd_stmt) < 1) {
                    $err = mysqli_stmt_error($upd_stmt);
                    mysqli_stmt_close($upd_stmt);
                    throw new Exception("Balance update failed. " . ($err ? $err : "Please try again."));
                }
                mysqli_stmt_close($upd_stmt);

                mysqli_commit($connection);

                $success = "Investment activated successfully!";
                $user_balance = $user_balance - $amount_decimal; // update local display balance
            } catch (Exception $e) {
                mysqli_rollback($connection);
                $errors[] = $e->getMessage();
            }
        }
    }
}


$plans = [];
$plan_sql = "
    SELECT id, plan_name, duration, profit_per_day, total_profit
    FROM investment_plans
    ORDER BY id DESC
";
$plan_stmt = mysqli_prepare($connection, $plan_sql);
if (!$plan_stmt) {
    $errors[] = "Server error: failed to prepare investment plans query.";
} else {
    mysqli_stmt_execute($plan_stmt);
    $plan_res = mysqli_stmt_get_result($plan_stmt);
    while ($row = mysqli_fetch_assoc($plan_res)) {
        $plans[] = $row;
    }
    mysqli_stmt_close($plan_stmt);
}
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

    <style>
        .invest-card {
            border-radius: 18px;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.25s ease;
            border: 1px solid #eee;
        }

        .invest-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
        }

        /* Header */
        .invest-title {
            font-size: 18px;
            font-weight: 700;
            color: #111;
        }

        /* Plan details */
        .plan-details {
            margin-top: 15px;
            padding: 15px;
            border-radius: 12px;
            background: #f9fafb;
        }

        .plan-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }

        /* Highlight profit */
        .plan-row.highlight {
            font-size: 16px;
            font-weight: bold;
            color: #16a34a;
        }

        /* Input */
        .invest-input {
            border: 1px solid #ddd;
            height: 50px;
        }

        /* Button */
        .invest-btn {
            background: #2563eb;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            height: 50px;
        }

        .invest-btn:hover {
            background: #1d4ed8;
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
                                <div class="col-6">
                                    <div class="page-title-content">
                                        <h3>Investment</h3>
                                        <p class="mb-2">Welcome To <?= htmlspecialchars($sitename) ?> Management</p>
                                        <div class="balance-chip mt-2">
                                            <small>Your Balance:</small>
                                            <span>$<?= number_format($client['balance'], 2) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto mt-2">
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


                <div class="invest-grid mt-3">

                    <?php if (!empty($plans)) : ?>
                        <?php foreach ($plans as $p) :

                            $plan_id = (int) $p['id'];
                            $plan_name = $p['plan_name'];
                            $duration = (int) $p['duration'];
                            $profit_per_day = (float) $p['profit_per_day'];
                            $total_profit = (float) $p['total_profit'];
                        ?>

                            <div class="invest-card" style="margin-bottom: 15px;">
                                <div class="invest-top">
                                    <div class="invest-icon">
                                        <i class="fi fi-rr-chart-histogram"></i>
                                    </div>
                                    <div>
                                        <h4 class="invest-title"><?= htmlspecialchars($plan_name) ?></h4>

                                    </div>
                                </div>


                                <div class="plan-details">
                                    <div class="plan-row">
                                        <span class="plan-label">Plan Name</span>
                                        <span class="plan-value"><?= htmlspecialchars($plan_name) ?></span>
                                    </div>

                                    <div class="plan-row">
                                        <span class="plan-label">Duration</span>
                                        <span class="plan-value"><?= $duration ?> days</span>
                                    </div>

                                    <div class="plan-row">
                                        <span class="plan-label">Profit Per Day</span>
                                        <span class="plan-value">$<?= number_format($profit_per_day, 2) ?></span>
                                    </div>

                                    <div class="plan-row highlight">
                                        <span>Total Profit</span>
                                        <span>$<?= number_format($total_profit, 2) ?></span>
                                    </div>
                                </div>

                                <form class="invest-form mt-4 d-flex flex-wrap gap-2" method="post">
                                    <input type="hidden" name="plan_id" value="<?= $plan_id ?>">
                                    <input style="width:400px !important ; min-width: 100%;" class="invest-input px-2 " type="number" name="amount_invested" placeholder="Enter Amount" required>
                                    <button style="border: none;" class="invest-btn b-none" type="submit" name="activate_investment">ACTIVATE</button>
                                </form>

                                <!-- <div class="plan-note">
                                    * Your balance will be checked automatically before activation.
                                </div> -->
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning">No investment plans found.</div>
                    <?php endif; ?>

                </div>
            </div>



        </div>

        <script src="<?= $domain ?>/vendor/jquery/jquery.min.js"></script>
        <script src="<?= $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?= $domain ?>/js/scripts.js"></script>
</body>

</html>