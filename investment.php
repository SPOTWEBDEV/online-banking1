<?php
session_start();
include("./server/connection.php");

// Protect page
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

$errors = [];
$success = "";

/**
 * ACTIVATE BUTTON ACTION
 * Since you're fetching investments already, this button will "re-activate" (insert again)
 */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['activate_investment'])) {

    $plan_name = isset($_POST['plan_name']) ? trim($_POST['plan_name']) : '';
    $amount_invested = isset($_POST['amount_invested']) ? trim($_POST['amount_invested']) : '';
    $daily_profit = isset($_POST['daily_profit']) ? trim($_POST['daily_profit']) : '';
    $total_profit = isset($_POST['total_profit']) ? trim($_POST['total_profit']) : '';
    $start_date = date("Y-m-d");
    $end_date = isset($_POST['end_date']) ? trim($_POST['end_date']) : '';

    if ($plan_name === '') $errors[] = "Plan name missing.";
    if ($amount_invested === '' || !is_numeric($amount_invested) || (float)$amount_invested <= 0) $errors[] = "Enter a valid amount.";
    if ($daily_profit === '' || !is_numeric($daily_profit)) $errors[] = "Daily profit missing.";
    if ($total_profit === '' || !is_numeric($total_profit)) $errors[] = "Total profit missing.";
    if ($end_date === '') $errors[] = "End date missing.";

    if (empty($errors)) {

        $insert_sql = "
            INSERT INTO investments
            (user_id, plan_name, amount_invested, daily_profit, total_profit, start_date, end_date)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = mysqli_prepare($connection, $insert_sql);
        if (!$stmt) {
            $errors[] = "Server error: failed to prepare statement.";
        } else {

            $amount_decimal = (float)$amount_invested;
            $daily_decimal  = (float)$daily_profit;
            $total_decimal  = (float)$total_profit;

            mysqli_stmt_bind_param(
                $stmt,
                "isdddss",
                $user_id,
                $plan_name,
                $amount_decimal,
                $daily_decimal,
                $total_decimal,
                $start_date,
                $end_date
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
        plan_name,
        amount_invested,
        daily_profit,
        total_profit,
        start_date,
        end_date
    FROM investments
    WHERE user_id = ?
    ORDER BY id DESC
";

$inv_stmt = mysqli_prepare($connection, $inv_sql);
if (!$inv_stmt) {
    $errors[] = "Server error: failed to prepare investments query.";
} else {
    mysqli_stmt_bind_param($inv_stmt, "i", $user_id);
    mysqli_stmt_execute($inv_stmt);
    $inv_result = mysqli_stmt_get_result($inv_stmt);

    while ($row = mysqli_fetch_assoc($inv_result)) {
        $investments[] = $row;
    }

    mysqli_stmt_close($inv_stmt);
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
            background: #171a1d;
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
            color: #e9eef2;
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
            font-size: 34px;
            font-weight: 800;
            color: #e9eef2;
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
        }
    </style>
</head>

<body class="dashboard">
    <div id="main-wrapper">

        <!-- HEADER (kept intact) -->
        <div class="header">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="header-content">
                            <div class="header-left">
                                <div class="brand-logo">
                                    <a class="mini-logo" href="<?= $domain ?>/index.php">
                                        <img src="<?= $domain ?>/images/logoi.png" alt="" width="40">
                                    </a>
                                </div>
                                <div class="search">
                                    <form action="#">
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
                                            <a class="" href="#"><div class="d-flex align-items-center"><span class="me-3 icon success"><i class="fi fi-bs-check"></i></span><div><p>Account created successfully</p><span>2024-11-04 12:00:23</span></div></div></a>
                                            <a class="" href="#"><div class="d-flex align-items-center"><span class="me-3 icon fail"><i class="fi fi-sr-cross-small"></i></span><div><p>2FA verification failed</p><span>2024-11-04 12:00:23</span></div></div></a>
                                            <a class="" href="#"><div class="d-flex align-items-center"><span class="me-3 icon success"><i class="fi fi-bs-check"></i></span><div><p>Device confirmation completed</p><span>2024-11-04 12:00:23</span></div></div></a>
                                            <a class="" href="#"><div class="d-flex align-items-center"><span class="me-3 icon pending"><i class="fi fi-rr-triangle-warning"></i></span><div><p>Phone verification pending</p><span>2024-11-04 12:00:23</span></div></div></a>
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
                                                <span class="thumb"><img class="rounded-full" src="<?= $domain ?>/images/avatar/3.jpg" alt=""></span>
                                                <div class="user-info">
                                                    <h5>User</h5>
                                                    <span>Account</span>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="dropdown-item" href="profile.html"><span><i class="fi fi-rr-user"></i></span>Profile</a>
                                        <a class="dropdown-item" href="wallets.html"><span><i class="fi fi-rr-wallet"></i></span>Wallets</a>
                                        <a class="dropdown-item" href="settings.html"><span><i class="fi fi-rr-settings"></i></span>Settings</a>
                                        <a class="dropdown-item logout" href="signin.html"><span><i class="fi fi-bs-sign-out-alt"></i></span>Logout</a>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SIDEBAR (kept intact) -->
        <div class="sidebar">
            <div class="brand-logo">
                <a class="full-logo" href="<?= $domain ?>/index.php">
                    <img src="<?= $domain ?>/images/logoi.png" alt="" width="30">
                </a>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="<?= $domain ?>/index.php"><span><i class="fi fi-rr-dashboard"></i></span><span class="nav-text">Home</span></a></li>
                    <li><a href="<?= $domain ?>/wallets.php"><span><i class="fi fi-rr-wallet"></i></span><span class="nav-text">Wallets</span></a></li>
                    <li><a href="<?= $domain ?>/budgets.php"><span><i class="fi fi-rr-donate"></i></span><span class="nav-text">Budgets</span></a></li>
                    <li><a href="<?= $domain ?>/goals.php"><span><i class="fi fi-sr-bullseye-arrow"></i></span><span class="nav-text">Goals</span></a></li>
                    <li><a href="<?= $domain ?>/profile.php"><span><i class="fi fi-rr-user"></i></span><span class="nav-text">Profile</span></a></li>
                    <li><a href="<?= $domain ?>/analytics.php"><span><i class="fi fi-rr-chart-histogram"></i></span><span class="nav-text">Analytics</span></a></li>
                    <li><a href="<?= $domain ?>/support.php"><span><i class="fi fi-rr-user-headset"></i></span><span class="nav-text">Support</span></a></li>
                    <li><a href="<?= $domain ?>/affiliates.php"><span><i class="fi fi-rs-link-alt"></i></span><span class="nav-text">Affiliates</span></a></li>
                    <li><a href="<?= $domain ?>/settings.php"><span><i class="fi fi-rs-settings"></i></span><span class="nav-text">Settings</span></a></li>
                </ul>
            </div>
        </div>

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

                            $plan_name = $inv['plan_name'];
                            $amount = (float) $inv['amount_invested'];
                            $daily = (float) $inv['daily_profit'];
                            $total = (float) $inv['total_profit'];

                            $start_ts = strtotime($inv['start_date']);
                            $end_ts = strtotime($inv['end_date']);

                            $total_days = max(1, (int) round(($end_ts - $start_ts) / 86400));
                            $days_passed = (int) floor((time() - $start_ts) / 86400);
                            if ($days_passed < 0) $days_passed = 0;
                            if ($days_passed > $total_days) $days_passed = $total_days;

                            $progress = (int) min(100, round(($days_passed / $total_days) * 100));
                        ?>

                            <div class="invest-card">
                                <div class="invest-top">
                                    <div class="invest-icon">
                                        <i class="fi fi-rr-chart-histogram"></i>
                                    </div>
                                    <div>
                                        <h4 class="invest-title"><?= htmlspecialchars($plan_name) ?></h4>
                                        <div class="invest-days"><?= $total_days ?> days</div>
                                    </div>
                                </div>

                                <div class="invest-label">Plan Amount</div>

                                <div class="invest-amount-row">
                                    <span class="down">↓</span>
                                    <span>$<?= number_format($amount, 0) ?></span>
                                    <span class="up">↑</span>
                                    <span>$<?= number_format($total, 0) ?></span>
                                </div>

                                <div class="invest-total">
                                    Total After <?= $total_days ?> Days: $<?= number_format($amount + $total, 0) ?>
                                </div>

                                <div class="invest-progress-wrap">
                                    <div class="invest-progress">
                                        <div style="width: <?= $progress ?>%"></div>
                                    </div>
                                    <div class="invest-progress-pct"><?= $progress ?>%</div>
                                </div>

                                <!-- ✅ ADDED INPUT + ACTIVATE BUTTON (ONLY ADDITION) -->
                                <form method="post" action="">
                                    <input type="hidden" name="plan_name" value="<?= htmlspecialchars($plan_name) ?>">
                                    <input type="hidden" name="daily_profit" value="<?= (float)$daily ?>">
                                    <input type="hidden" name="total_profit" value="<?= (float)$total ?>">
                                    <input type="hidden" name="end_date" value="<?= htmlspecialchars($inv['end_date']) ?>">

                                    <input class="invest-input" type="number" step="0.01" name="amount_invested" placeholder="Enter Amount" required>

                                    <button class="invest-btn" type="submit" name="activate_investment">ACTIVATE</button>
                                </form>

                                <div class="inv-meta">
                                    <div>Start: <?= htmlspecialchars($inv['start_date']) ?></div>
                                    <div>End: <?= htmlspecialchars($inv['end_date']) ?></div>
                                </div>
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
