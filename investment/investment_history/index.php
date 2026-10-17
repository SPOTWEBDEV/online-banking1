<?php
include("../../server/connection.php");
include("../../server/auth/client.php");
include("../../include/crypto-widgets.php");

$user_id = $_SESSION['user_id'];

/* FETCH Investment HISTORY
   investments.* brings days_paid, status, created_at; plan columns give duration + profit_per_day */
$sql = "
    SELECT
        investment_plans.*,
        investments.*,
        investments.amount_invested AS amount,
        investments.status AS status
    FROM investment_plans
    INNER JOIN investments ON investment_plans.id = investments.plan_id
    WHERE investments.user_id = ?
    ORDER BY investments.id DESC
";

$rows = [];
$stmt = mysqli_prepare($connection, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    mysqli_stmt_close($stmt);
}

/* Quick stats */
$total_invested = 0;
$total_earned = 0;
$active = 0;
$completed = 0;
foreach ($rows as $r) {
    $total_invested += (float) $r['amount'];
    $total_earned += (int) ($r['days_paid'] ?? 0) * (float) $r['profit_per_day'];
    $s = strtolower($r['status'] ?? '');
    if ($s === 'active') $active++;
    elseif ($s === 'completed') $completed++;
}

function status_class($status)
{
    $s = strtolower($status ?? '');
    if ($s === 'active') return 'ok';
    if ($s === 'completed') return 'done';
    if ($s === 'declined' || $s === 'rejected' || $s === 'cancelled') return 'bad';
    return 'wait';
}
?>


<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | Investment-History </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>/vendor/toastr/toastr.min.css">

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

        /* Stat cards */
        .hist-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin: 18px 0;
        }

        .hist-stat {
            background: #fff;
            border-radius: 10px;
            padding: 16px 18px;
            box-shadow: 0 6px 24px rgba(15, 23, 42, .05);
        }

        .hist-stat .label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6b7280;
            font-weight: 600;
        }

        .hist-stat .value {
            font-size: 22px;
            font-weight: 800;
            color: #111;
            margin-top: 4px;
        }

        .hist-stat.accent .value {
            color: var(--inv-primary);
        }

        .hist-stat.green .value {
            color: var(--inv-green);
        }

        /* Table card */
        .hist-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 24px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .hist-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .hist-table thead th {
            background: #f9fafb;
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 600;
            padding: 14px 16px;
            border: 0;
            white-space: nowrap;
        }

        .hist-table tbody td {
            padding: 16px;
            border: 0;
            border-top: 1px solid #f1f1f4;
            font-size: 14px;
            color: #374151;
            vertical-align: middle;
        }

        .hist-table tbody tr:hover {
            background: #f8fafc;
        }

        .hist-table .plan {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #111;
        }

        .hist-table .plan .ico {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--inv-primary-soft);
            color: var(--inv-primary);
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .hist-table .amt {
            font-weight: 700;
            color: #111;
        }

        .hist-table .earned {
            font-weight: 700;
            color: var(--inv-green);
        }

        .hist-table .sub {
            display: block;
            font-size: 12px;
            color: #9ca3af;
            font-weight: 500;
        }

        /* Days progress */
        .progress-cell {
            min-width: 150px;
        }

        .progress-cell .txt {
            font-size: 13px;
            font-weight: 600;
            color: #111;
            margin-bottom: 6px;
        }

        .progress-cell .txt span {
            color: #9ca3af;
            font-weight: 500;
        }

        .bar {
            height: 6px;
            background: #e5e7eb;
            border-radius: 99px;
            overflow: hidden;
        }

        .bar i {
            display: block;
            height: 100%;
            background: var(--inv-grad);
            border-radius: 99px;
        }

        .pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .pill.ok {
            background: rgba(22, 163, 74, .12);
            color: #15803d;
        }

        .pill.done {
            background: rgba(37, 99, 235, .10);
            color: #1d4ed8;
        }

        .pill.bad {
            background: rgba(220, 38, 38, .10);
            color: #b91c1c;
        }

        .pill.wait {
            background: rgba(245, 158, 11, .15);
            color: #b45309;
        }

        /* Empty state */
        .hist-empty {
            text-align: center;
            padding: 56px 20px;
        }

        .hist-empty .ring {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--inv-primary-soft);
            color: var(--inv-primary);
            display: grid;
            place-items: center;
            margin: 0 auto 14px;
        }

        .hist-empty p {
            color: #6b7280;
            margin-bottom: 18px;
        }

        .invest-btn {
            display: inline-block;
            border: 0;
            padding: 12px 26px;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            background: var(--inv-grad);
            box-shadow: 0 8px 18px var(--inv-shadow);
        }

        .invest-btn:hover {
            color: #fff;
            filter: brightness(1.06);
        }

        /* Mobile: each row becomes a card */
        @media (max-width: 991px) {
            .hist-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 767px) {
            .hist-card {
                background: transparent;
                box-shadow: none;
            }

            .hist-table thead {
                display: none;
            }

            .hist-table,
            .hist-table tbody,
            .hist-table tr,
            .hist-table td {
                display: block;
                width: 100%;
            }

            .hist-table tbody tr {
                background: #fff;
                border-radius: 10px;
                box-shadow: 0 6px 24px rgba(15, 23, 42, .06);
                margin-bottom: 14px;
                padding: 8px 16px;
            }

            .hist-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                padding: 10px 0;
                border-top: 0;
                border-bottom: 1px dashed #eee;
                text-align: right;
            }

            .hist-table tbody td:last-child {
                border-bottom: 0;
            }

            .hist-table tbody td::before {
                content: attr(data-label);
                color: #6b7280;
                font-weight: 500;
                text-align: left;
            }

            .progress-cell {
                min-width: 0;
                width: 150px;
            }
        }
    </style>
</head>

<body class="dashboard">

    <div id="main-wrapper">
        <!-- nav -->
        <?php include("../../include/header.php") ?>

        <!-- sidenav -->
        <?php include("../../include/sidenav.php") ?>
        <div class="content-body">
            <div class="container">

                <!-- Live crypto price bar -->
                <?php cw_ticker(); ?>

                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-6">
                                    <div class="page-title-content">
                                        <h3>Investment History</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabs: connects this page with the plans page -->
                        <div class="inv-tabs">
                            <a href="../" class="inv-tab">Plans</a>
                            <a href="./" class="inv-tab active">My Investments</a>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="hist-stats">
                    <div class="hist-stat accent">
                        <div class="label">Total invested</div>
                        <div class="value">$<?= number_format($total_invested, 2) ?></div>
                    </div>
                    <div class="hist-stat green">
                        <div class="label">Profit earned</div>
                        <div class="value">$<?= number_format($total_earned, 2) ?></div>
                    </div>
                    <div class="hist-stat">
                        <div class="label">Active</div>
                        <div class="value"><?= $active ?></div>
                    </div>
                    <div class="hist-stat">
                        <div class="label">Completed</div>
                        <div class="value"><?= $completed ?></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">

                        <?php if (empty($rows)): ?>

                            <div class="hist-card">
                                <div class="hist-empty">
                                    <div class="ring">
                                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M6 3h12l4 6-10 12L2 9z" />
                                            <path d="M11 3 8 9l4 12 4-12-3-6" />
                                            <path d="M2 9h20" />
                                        </svg>
                                    </div>
                                    <h5>No investment history yet</h5>
                                    <p>Once you activate a plan, it will show up here.</p>
                                    <a href="../" class="invest-btn">Browse plans</a>
                                </div>
                            </div>

                        <?php else: ?>

                            <div class="hist-card">
                                <table class="hist-table">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Plan</th>
                                            <th>Amount</th>
                                            <th>Daily profit</th>
                                            <th>Days</th>
                                            <th>Profit earned</th>
                                            <th>Started</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 0;
                                        foreach ($rows as $row): $count++;
                                            $duration  = max(1, (int) $row['duration']);
                                            $days_paid = min($duration, (int) ($row['days_paid'] ?? 0));
                                            $remaining = $duration - $days_paid;
                                            $pct       = round(($days_paid / $duration) * 100);
                                            $daily     = (float) $row['profit_per_day'];
                                            $earned    = $days_paid * $daily;
                                            $expected  = $duration * $daily;
                                        ?>
                                            <tr>
                                                <td data-label="S/N"><?= $count ?></td>
                                                <td data-label="Plan">
                                                    <span class="plan">
                                                        <span class="ico">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M6 3h12l4 6-10 12L2 9z" />
                                                                <path d="M2 9h20" />
                                                            </svg>
                                                        </span>
                                                        <?= htmlspecialchars($row['plan_name']) ?>
                                                    </span>
                                                </td>
                                                <td data-label="Amount" class="amt">$<?= number_format($row['amount'], 2) ?></td>
                                                <td data-label="Daily profit">$<?= number_format($daily, 2) ?></td>
                                                <td data-label="Days">
                                                    <div class="progress-cell">
                                                        <div class="txt"><?= $days_paid ?> / <?= $duration ?> days <span>· <?= $remaining ?> left</span></div>
                                                        <div class="bar"><i style="width: <?= $pct ?>%"></i></div>
                                                    </div>
                                                </td>
                                                <td data-label="Profit earned" class="earned">
                                                    $<?= number_format($earned, 2) ?>
                                                    <span class="sub">of $<?= number_format($expected, 2) ?></span>
                                                </td>
                                                <td data-label="Started"><?= !empty($row['created_at']) ? date("Y-m-d", strtotime($row['created_at'])) : '-' ?></td>
                                                <td data-label="Status">
                                                    <span class="pill <?= status_class($row['status']) ?>">
                                                        <?= htmlspecialchars(ucfirst($row['status'] ?? 'pending')) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>

                <!-- Live crypto market -->
                <?php cw_heading('Live crypto market'); ?>
                <?php cw_market_overview(); ?>

            </div>
        </div>
    </div>

    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $domain ?>/js/scripts.js"></script>
</body>

</html>