<?php
include("../server/connection.php");

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("location: {$domain}/auth/sign_in/");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

$errors = [];
$success = "";


$user_balance = 0.00;
$bal_sql = "SELECT balance FROM users WHERE id = ? LIMIT 1";
$bal_stmt = mysqli_prepare($connection, $bal_sql);
if ($bal_stmt) {
    mysqli_stmt_bind_param($bal_stmt, "i", $user_id);
    mysqli_stmt_execute($bal_stmt);
    $bal_res = mysqli_stmt_get_result($bal_stmt);
    if ($bal_row = mysqli_fetch_assoc($bal_res)) {
        $user_balance = (float) $bal_row['balance'];
    }
    mysqli_stmt_close($bal_stmt);
} else {
    $errors[] = "Server error: failed to fetch user balance.";
}

/**
 * ✅ ACTIVATE INVESTMENT (check balance first)
 */
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
        /* ===== Layout ===== */
        .invest-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        @media (max-width: 991px) {
            .invest-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ===== Nice Card ===== */
        .invest-card {
            border-radius: 22px;
            padding: 20px;
            background-color: white;
            color: black;
            box-shadow: 0 18px 55px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
            overflow: hidden;
        }

        .invest-card:before {
            content: "";
            position: absolute;
            width: 280px;
            height: 280px;
            /* background: radial-gradient(circle, rgba(41, 197, 255, 0.28), transparent 60%); */
            top: -140px;
            right: -140px;
        }

        .invest-top {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 14px;
            position: relative;
            z-index: 2;
        }

        .invest-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color:black;
            flex: 0 0 auto;
        }

        .invest-title {
            margin: 0;
            font-weight: 900;
            letter-spacing: .8px;
            text-transform: uppercase;
            font-size: 16px;
            color: #06121a;
        }

        .invest-sub {
            margin-top: 4px;
            font-size: 13px;
            color: black;
        }

        /* ===== Plan Details Block ===== */
        .plan-details {
            position: relative;
            z-index: 2;
            margin-top: 10px;
            padding: 14px;
            border-radius: 16px;
            background: white;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .plan-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.12);
            font-size: 14px;
        }

        .plan-row:last-child {
            border-bottom: 0;
        }

        .plan-label {
            color:black;
            font-weight: 600;
        }

        .plan-value {
            color: black;
            font-weight: 800;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(41, 197, 255, 0.14);
            color: #bfefff;
            border: 1px solid rgba(41, 197, 255, 0.25);
            font-size: 12px;
            font-weight: 800;
        }

        /* ===== Balance chip ===== */
        .balance-chip {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 14px;
            background: white;
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #06121a;
            font-weight: 800;
            font-size: 14px;
        }

        .balance-chip small {
            opacity: .65;
            font-weight: 700;
        }

        /* ===== Input + button ===== */
        .invest-form {
            position: relative;
            z-index: 2;
            margin-top: 14px;
        }

        .invest-input {
            width: 100%;
            height: 56px;
            border-radius: 14px;
            background: rgba(0, 0, 0, 0.20);
            border: 1px solid rgba(255, 255, 255, 0.12);
            outline: none;
            padding: 0 16px;
            color: #ffffff;
            font-size: 15px;
            margin-bottom: 12px;
        }

        .invest-input::placeholder {
            color: rgba(255, 255, 255, 0.45);
        }

        .invest-btn {
            width: 100%;
            height: 56px;
            border-radius: 14px;
            border: none;
            background: linear-gradient(90deg, #29c5ff, #1f89a8);
            color: #06121a;
            font-weight: 900;
            letter-spacing: 1px;
            z-index: -1 !important;
            cursor: pointer;
            font-size: 15px;
            text-transform: uppercase;
            box-shadow: 0 14px 28px rgba(41, 197, 255, 0.22);
        }

        .invest-btn:hover {
            filter: brightness(1.03);
        }

        /* ===== Small note ===== */
        .plan-note {
            margin-top: 10px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.55);
            position: relative;
            z-index: 2;
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
                                <div class="col-xl-6">
                                    <div class="page-title-content">
                                        <h3>Investment</h3>
                                        <p class="mb-2">Welcome To <?= htmlspecialchars($sitename) ?> Management</p>

                                      
                                        <div class="balance-chip mt-2">
                                            <small>Your Balance:</small>
                                            <span>$<?= number_format((float) htmlspecialchars($bal_sql), 2) ?></span>
                                        </div>
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

                                    <div class="plan-row">
                                        <span class="plan-label">Total Profit</span>
                                        <span class="plan-value">$<?= number_format($total_profit, 2) ?></span>
                                    </div>
                                </div>

                                <form class="invest-form" method="post">
                                    <input type="hidden" name="plan_id" value="<?= $plan_id ?>">
                                    <input class="invest-input" type="number" name="amount_invested" placeholder="Enter Amount" required>
                                    <button class="invest-btn" type="submit" name="activate_investment">ACTIVATE</button>
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

            <!-- FOOTER (kept intact) -->
            <!-- <div class="footer">
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
            </div> -->

        </div>

        <script src="<?= $domain ?>/vendor/jquery/jquery.min.js"></script>
        <script src="<?= $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?= $domain ?>/js/scripts.js"></script>
</body>

</html>
