<?php
include("../server/connection.php");
include("../server/auth/client.php");
include("../include/crypto-widgets.php");

$user_balance = (float) $client['balance'];
$errors = [];
$success = null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['activate_investment'])) {

    // ✅ STEP 1: Get & sanitize input FIRST
    $plan_id_int = (int) ($_POST['plan_id'] ?? 0);
    $amount_decimal = (float) ($_POST['amount_invested'] ?? 0);

    // Basic validation
    if ($plan_id_int <= 0) {
        $errors[] = "Invalid plan selected.";
    }

    if ($amount_decimal <= 0) {
        $errors[] = "Enter a valid amount.";
    }

    // Continue only if no errors
    if (empty($errors)) {

        // ✅ STEP 2: Get plan safely (prepared statement)
        $stmt = mysqli_prepare($connection, "
            SELECT min_amount, max_amount 
            FROM investment_plans 
            WHERE id = ?
            LIMIT 1
        ");

        mysqli_stmt_bind_param($stmt, "i", $plan_id_int);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $plan_data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$plan_data) {
            $errors[] = "Invalid investment plan.";
        } else {

            // ✅ STEP 3: Validate plan limits
            $min_amount = (float) $plan_data['min_amount'];
            $max_amount = (float) $plan_data['max_amount'];

            if ($amount_decimal < $min_amount || $amount_decimal > $max_amount) {
                $errors[] = "Amount must be between $" . number_format($min_amount, 2) . " and $" . number_format($max_amount, 2);
            }

            // ✅ STEP 4: Check user balance
            if ($user_balance < $amount_decimal) {
                $errors[] = "Insufficient balance.";
            }
        }

        // ✅ STEP 5: ONLY RUN TRANSACTION IF NO ERRORS
        if (empty($errors)) {

            mysqli_begin_transaction($connection);

            try {

                // Insert investment
                $insert_sql = "
                    INSERT INTO investments (user_id, plan_id, amount_invested, status, days_paid)
                    VALUES (?, ?, ?, 'active', 0)
                ";

                $stmt = mysqli_prepare($connection, $insert_sql);

                if (!$stmt) {
                    throw new Exception("Failed to prepare investment insert.");
                }

                mysqli_stmt_bind_param($stmt, "iid", $user_id, $plan_id_int, $amount_decimal);

                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Investment failed: " . mysqli_stmt_error($stmt));
                }

                mysqli_stmt_close($stmt);

                // Deduct balance
                $upd_stmt = mysqli_prepare($connection, "
                    UPDATE users 
                    SET balance = balance - ? 
                    WHERE id = ? AND balance >= ?
                ");

                if (!$upd_stmt) {
                    throw new Exception("Failed to prepare balance update.");
                }

                mysqli_stmt_bind_param($upd_stmt, "did", $amount_decimal, $user_id, $amount_decimal);

                if (!mysqli_stmt_execute($upd_stmt) || mysqli_stmt_affected_rows($upd_stmt) < 1) {
                    throw new Exception("Balance update failed or insufficient funds.");
                }

                mysqli_stmt_close($upd_stmt);

                mysqli_commit($connection);

                $success = "Investment activated successfully!";
                $user_balance -= $amount_decimal;

            } catch (Exception $e) {
                mysqli_rollback($connection);
                $errors[] = $e->getMessage();
            }
        }
    }
}



$plans = [];
$plan_sql = "
    SELECT id, plan_name, duration, profit_per_day, total_profit , min_amount, max_amount
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

// Compact number for the price range (100, 25000, 1500.5)
function amt($n)
{
    $n = (float) $n;
    return rtrim(rtrim(number_format($n, 2, '.', ''), '0'), '.');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= htmlspecialchars($sitename) ?> | Investment</title>

    <link rel="icon" type="image/png" sizes="16x16" href="<?= $domain ?>/images/logo.png">
    <link rel="stylesheet" href="<?= $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?= $domain ?>/vendor/toastr/toastr.min.css">

    <?php cw_styles(); ?>

    <style>
        /* Original investment-page palette: blue primary, green profit */
        :root {
            --inv-primary: #2563eb;
            --inv-primary-dark: #1d4ed8;
            --inv-primary-soft: #eff6ff;
            --inv-grad: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
            --inv-shadow: rgba(37, 99, 235, .28);
            --inv-green: #16a34a;
        }

        /* Grid: 1 column on phones, 2 on tablets, 3 on desktop */
        .invest-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .invest-card {
            background: #fff;
            border-radius: 10px;
            padding: 32px 26px 28px;
            text-align: center;
            box-shadow: 0 6px 24px rgba(15, 23, 42, .06);
            border: 1px solid #eef0f4;
            transition: transform .25s ease, box-shadow .25s ease;
            display: flex;
            flex-direction: column;
        }

        .invest-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 40px rgba(15, 23, 42, .12);
        }

        /* Gem icon in a soft circle */
        .invest-icon {
            width: 52px;
            height: 52px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: var(--inv-primary-soft);
            display: grid;
            place-items: center;
            color: var(--inv-primary);
        }

        .invest-title {
            font-size: 18px;
            font-weight: 700;
            color: #111;
            margin: 0 0 10px;
        }

        .invest-range {
            font-size: 26px;
            font-weight: 800;
            color: var(--inv-primary);
            letter-spacing: -.01em;
            margin-bottom: 22px;
        }

        /* Checklist */
        .invest-features {
            list-style: none;
            padding: 0;
            margin: 0 0 26px;
            text-align: left;
            flex-grow: 1;
        }

        .invest-features li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 14.5px;
            color: #374151;
            padding: 7px 0;
        }

        .invest-features li svg {
            flex-shrink: 0;
            margin-top: 3px;
            color: var(--inv-green);
        }

        .invest-features li strong {
            color: #111;
            font-weight: 600;
        }

        /* Button */
        .invest-btn {
            display: block;
            width: 100%;
            border: 0;
            padding: 14px 18px;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            background: var(--inv-grad);
            box-shadow: 0 8px 18px var(--inv-shadow);
            transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
            cursor: pointer;
        }

        .invest-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.06);
            box-shadow: 0 12px 24px var(--inv-shadow);
        }

        /* Shared tab bar (same on both pages) */
        .inv-tabs {
            display: inline-flex;
            gap: 4px;
            padding: 4px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, .05);
            margin: 16px 0 4px;
        }

        .inv-tab {
            padding: 9px 18px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            text-decoration: none;
            transition: color .15s;
        }

        .inv-tab:hover {
            color: var(--inv-primary);
        }

        .inv-tab.active {
            color: #fff;
            background: var(--inv-grad);
            box-shadow: 0 6px 14px var(--inv-shadow);
        }

        /* Modal (plain JS, no Bootstrap needed) */
        .invest-modal {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            background: rgba(15, 23, 42, .55);
            overflow-y: auto;
        }

        .invest-modal.open {
            display: flex;
        }

        .invest-modal .inv-dialog {
            width: 100%;
            max-width: 420px;
            margin: auto;
        }

        .invest-modal .inv-content {
            background: #fff;
            border: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, .25);
        }

        .invest-modal .modal-top {
            text-align: center;
            padding: 28px 24px 8px;
        }

        .invest-modal .modal-top .invest-icon {
            margin-bottom: 12px;
        }

        .invest-modal .modal-top h5 {
            font-weight: 700;
            margin: 0 0 6px;
        }

        .invest-modal .modal-top .range {
            color: var(--inv-primary);
            font-weight: 700;
        }

        .invest-modal .modal-body {
            padding: 16px 24px 28px;
        }

        .invest-modal label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .invest-modal .input-group-text {
            background: transparent;
            border-radius: 10px 0 0 10px;
        }

        .invest-modal .form-control {
            height: 50px;
            border-radius: 0 10px 10px 0;
        }

        .invest-modal .form-control:focus {
            border-color: var(--inv-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
        }

        .invest-modal .meta {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #6b7280;
            margin: 10px 0 20px;
        }

        .invest-modal .meta strong {
            color: #111;
        }

        .invest-modal .close-link {
            display: block;
            text-align: center;
            margin-top: 12px;
            font-size: 14px;
            color: #6b7280;
            background: none;
            border: 0;
            width: 100%;
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

                <!-- Live crypto price bar -->
                <?php cw_ticker(); ?>

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
                                            <span>$<?= number_format($user_balance, 2) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs: connects this page with the history page -->
                <div class="inv-tabs">
                    <a href="./" class="inv-tab active">Plans</a>
                    <a href="./investment_history/" class="inv-tab">My Investments</a>
                </div>

                <!-- Alerts -->
                <?php if (!empty($errors)) { ?>
                    <div class="alert alert-danger mt-3">
                        <?php foreach ($errors as $error) { ?>
                            <p class="mb-1"><?= htmlspecialchars($error) ?></p>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if (!empty($success)) { ?>
                    <div class="alert alert-success mt-3">
                        <?= htmlspecialchars($success) ?>
                        <a href="./investment_history/" class="fw-bold ms-1">View your investments &rarr;</a>
                    </div>
                <?php } ?>

                <!-- Plans -->
                <?php cw_heading('Investment plans', count($plans) . ' available'); ?>

                <div class="invest-grid">

                    <?php if (!empty($plans)) : ?>
                        <?php foreach ($plans as $p) :

                            $plan_id = (int) $p['id'];
                            $plan_name = $p['plan_name'];
                            $duration = (int) $p['duration'];
                            $profit_per_day = (float) $p['profit_per_day'];
                            $total_profit = (float) $p['total_profit'];
                            $min_amount = (float) $p['min_amount'];
                            $max_amount = (float) $p['max_amount'];
                        ?>

                            <div class="invest-card">

                                <div class="invest-icon">
                                    <!-- gem -->
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 3h12l4 6-10 12L2 9z" />
                                        <path d="M11 3 8 9l4 12 4-12-3-6" />
                                        <path d="M2 9h20" />
                                    </svg>
                                </div>

                                <h4 class="invest-title"><?= htmlspecialchars($plan_name) ?></h4>

                                <div class="invest-range">
                                    <?= amt($min_amount) ?>$ - <?= amt($max_amount) ?>$
                                </div>

                                <ul class="invest-features">
                                    <li>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
                                        <span><strong><?= $duration ?> days</strong> investment duration</span>
                                    </li>
                                    <li>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
                                        <span><strong>$<?= number_format($profit_per_day, 2) ?></strong> profit per day</span>
                                    </li>
                                    <li>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
                                        <span><strong>$<?= number_format($total_profit, 2) ?></strong> total profit</span>
                                    </li>
                                    <li>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
                                        <span>Activates instantly from your balance</span>
                                    </li>
                                </ul>

                                <button type="button" class="invest-btn"
                                    data-open-invest="1"
                                    data-id="<?= $plan_id ?>"
                                    data-name="<?= htmlspecialchars($plan_name) ?>"
                                    data-min="<?= $min_amount ?>"
                                    data-max="<?= $max_amount ?>">
                                    Get Started
                                </button>
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning">No investment plans found.</div>
                    <?php endif; ?>

                </div>

                <!-- Live crypto market -->
                <?php cw_heading('Live crypto market'); ?>
                <?php cw_market_overview(); ?>

            </div>
        </div>

        <!-- Activate modal (one modal shared by all plans) -->
        <div class="invest-modal" id="investModal" aria-hidden="true">
            <div class="inv-dialog">
                <div class="inv-content">

                    <div class="modal-top">
                        <div class="invest-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 3h12l4 6-10 12L2 9z" />
                                <path d="M11 3 8 9l4 12 4-12-3-6" />
                                <path d="M2 9h20" />
                            </svg>
                        </div>
                        <h5 id="mName">Plan</h5>
                        <div class="range" id="mRange"></div>
                    </div>

                    <div class="modal-body">
                        <form method="post">
                            <input type="hidden" name="plan_id" id="mPlanId">

                            <label for="mAmount">Amount to invest</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" id="mAmount" name="amount_invested" placeholder="Enter amount" required>
                            </div>

                            <div class="meta">
                                <span>Your balance: <strong>$<?= number_format($user_balance, 2) ?></strong></span>
                                <span id="mLimits"></span>
                            </div>

                            <button type="submit" name="activate_investment" class="invest-btn">Activate Investment</button>
                            <button type="button" class="close-link" data-close-invest="1">Cancel</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <script src="<?= $domain ?>/vendor/jquery/jquery.min.js"></script>
        <script src="<?= $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?= $domain ?>/js/scripts.js"></script>

        <script>
            // Fill the shared modal with the clicked plan's details
            function openInvest(b) {
                var min = parseFloat(b.getAttribute('data-min'));
                var max = parseFloat(b.getAttribute('data-max'));
                var fmt = function (n) { return n.toLocaleString(undefined, { maximumFractionDigits: 2 }); };

                document.getElementById('mPlanId').value = b.getAttribute('data-id');
                document.getElementById('mName').textContent = b.getAttribute('data-name');
                document.getElementById('mRange').textContent = fmt(min) + '$ - ' + fmt(max) + '$';
                document.getElementById('mLimits').textContent = 'Min ' + fmt(min) + ' · Max ' + fmt(max);

                var input = document.getElementById('mAmount');
                input.min = min;
                input.max = max;
                input.value = '';

                document.getElementById('investModal').classList.add('open');
                document.body.style.overflow = 'hidden';
                setTimeout(function () { input.focus(); }, 50);
            }

            function closeInvest() {
                document.getElementById('investModal').classList.remove('open');
                document.body.style.overflow = '';
            }

            document.querySelectorAll('[data-open-invest]').forEach(function (btn) {
                btn.addEventListener('click', function () { openInvest(btn); });
            });

            document.querySelectorAll('[data-close-invest]').forEach(function (btn) {
                btn.addEventListener('click', closeInvest);
            });

            // Click on the dark backdrop closes it
            document.getElementById('investModal').addEventListener('click', function (e) {
                if (e.target === this) closeInvest();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeInvest();
            });
        </script>
</body>

</html>