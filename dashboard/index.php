<?php
include("../server/connection.php");
include("../server/auth/client.php");

$user_id = $_SESSION['user_id'];

// default values (avoid errors)
$fullname = "";
$balance = 0;
$loan_balance = 0;
$crypto_balance = 0;
$virtual_card_balance = 0;
$limit = 0;

$sql = "SELECT fullname, balance, loan_balance, crypto_balance, virtual_card_balance , limits
        FROM users
        WHERE id = ? LIMIT 1";
$stmt = mysqli_prepare($connection, $sql);

if (!$stmt) {
    die("Query error: " . mysqli_error($connection));
}

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    $fullname = $user['fullname'] ?? "";
    $balance = (float)($user['balance'] ?? 0);
    $loan_balance = (float)($user['loan_balance'] ?? 0);
    $crypto_balance = (float)($user['crypto_balance'] ?? 0);
    $virtual_card_balance = (float)($user['virtual_card_balance'] ?? 0);
    $limit = (float)($user['limits'] ?? 0);
} else {
    // session user_id not found in DB
    session_destroy();
    header("Location: {$domain}/auth/sign_in/");
    exit;
}

mysqli_stmt_close($stmt);

// helper to format money
function money($amount)
{
    return number_format((float)$amount, 2);
}

/* Same display rules as before, in one place so the desktop table
   and the mobile cards always agree */
function tx_view($t)
{
    $type   = $t['type'] ?? '';
    $state  = $t['state'] ?? '';
    $status = $t['status'] ?? '';

    if ($type == 'transfer') {
        $name = $state == 'to' ? 'Transfer to ' . $t['transaction_name'] : 'Transfer from '  . $t['transaction_name'];
    } else if ($type == 'deposit') {
        $name = 'Account Funded Via ' . $t['transaction_name'];
    } else {
        $name = $t['transaction_name'] ?? ucfirst($type);
    }

    $isCredit = ($type == 'deposit' || $state == 'from');
    $ok       = in_array($status, ['completed', 'success', 'approved']);
    $failed   = in_array($status, ['declined', 'rejected']);

    return [
        'name'         => $name,
        'is_credit'    => $isCredit,
        'label'        => $isCredit ? 'Credit' : 'Debit',
        'label_mobile' => $failed ? 'Failed' : ($isCredit ? 'Credit' : 'Debit'),
        'sign'         => $isCredit ? '+' : '-',
        'amount_class' => $isCredit ? 'in' : 'out',
        'status_text'  => $ok ? 'Successful' : ucfirst($status),
        'pill'         => ($status == 'pending') ? 'wait' : ($ok ? 'ok' : 'bad'),
        'amount'       => number_format($t['amount']),
        'date'         => date('d M Y, h:i A', strtotime($t['created_at'])),
        'link'         => '../transaction/?id=' . $t['id'] . '&type=' . $type,
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $sitename ?> | dashboard</title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/logo.png">
    <link rel="stylesheet" href="<?php echo $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>/vendor/toastr/toastr.min.css">

    <style>
        .db {
            --db-primary: var(--app-primary, #2563eb);
            --db-soft: var(--app-primary-soft, #eff6ff);
            --db-border: #eef0f4;
            --db-muted: #6b7280;
            --db-green: #16a34a;
            --db-red: #dc2626;
        }

        .db .db-crumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--db-muted);
        }

        .db .db-crumb a {
            color: var(--db-primary);
            font-weight: 600;
            text-decoration: none;
        }

        /* Stat cards */
        .db .db-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin: 8px 0 20px;
        }

        .db .db-stat {
            background: #fff;
            border: 1px solid var(--db-border);
            border-radius: 14px;
            box-shadow: 0 6px 24px rgba(15, 23, 42, .05);
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: transform .2s, box-shadow .2s;
        }

        .db .db-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 32px rgba(15, 23, 42, .09);
        }

        .db .db-stat .ico {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .db .db-stat .ico.blue { background: #eff6ff; color: #2563eb; }
        .db .db-stat .ico.amber { background: #fffbeb; color: #d97706; }
        .db .db-stat .ico.violet { background: #f5f3ff; color: #7c3aed; }
        .db .db-stat .ico.green { background: #f0fdf4; color: #16a34a; }

        .db .db-stat .lbl {
            font-size: 12.5px;
            color: var(--db-muted);
            font-weight: 500;
        }

        .db .db-stat .val {
            font-size: 20px;
            font-weight: 800;
            color: #111827;
            letter-spacing: -.01em;
            line-height: 1.25;
            word-break: break-word;
        }

        /* Quick actions */
        .db .db-actions {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .db .db-action {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #fff;
            border: 1px solid var(--db-border);
            border-radius: 14px;
            padding: 16px 18px;
            text-decoration: none;
            box-shadow: 0 6px 24px rgba(15, 23, 42, .05);
            transition: transform .2s, border-color .2s, box-shadow .2s;
        }

        .db .db-action:hover {
            transform: translateY(-3px);
            border-color: var(--db-primary);
            box-shadow: 0 14px 32px rgba(37, 99, 235, .12);
        }

        .db .db-action .ico {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 8px 16px rgba(37, 99, 235, .25);
        }

        .db .db-action h6 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .db .db-action p {
            margin: 2px 0 0;
            font-size: 12.5px;
            color: var(--db-muted);
        }

        .db .db-action .arrow {
            margin-left: auto;
            color: #9ca3af;
            transition: transform .2s, color .2s;
        }

        .db .db-action:hover .arrow {
            color: var(--db-primary);
            transform: translateX(3px);
        }

        /* Balance + card */
        .db .db-two {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .db .db-total {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            padding: 28px;
            color: #fff;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 16px 36px rgba(37, 99, 235, .3);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 210px;
        }

        .db .db-total::before,
        .db .db-total::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }

        .db .db-total::before { width: 220px; height: 220px; right: -70px; top: -90px; }
        .db .db-total::after { width: 160px; height: 160px; right: 30px; bottom: -90px; }

        .db .db-total .small-t {
            font-size: 13px;
            opacity: .85;
            font-weight: 500;
        }

        .db .db-total h2 {
            margin: 6px 0 0;
            color: #fff;
            font-size: 34px;
            font-weight: 800;
            letter-spacing: -.02em;
            word-break: break-word;
        }

        .db .db-total .personal {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, .16);
            border-radius: 12px;
            padding: 10px 14px;
            align-self: flex-start;
            font-size: 13px;
        }

        .db .db-total .personal strong {
            font-size: 16px;
            font-weight: 800;
        }

        .db .db-total .top {
            position: relative;
            z-index: 1;
        }

        /* Virtual card */
        .db .db-card {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            padding: 24px 26px;
            color: #fff;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            box-shadow: 0 16px 36px rgba(15, 23, 42, .3);
            min-height: 210px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .db .db-card::before {
            content: "";
            position: absolute;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            right: -80px;
            top: -100px;
            background: rgba(255, 255, 255, .07);
        }

        .db .db-card .row1 {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .db .db-card .row1 h4 {
            margin: 0;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            opacity: .9;
        }

        .db .db-card .brand {
            background: #fff;
            border-radius: 6px;
            padding: 4px 9px;
            display: inline-flex;
        }

        .db .db-card .brand img {
            height: 20px;
            width: auto;
            display: block;
        }

        .db .db-card .chip {
            position: relative;
            z-index: 1;
            margin: 6px 0;
        }

        .db .db-card .num {
            position: relative;
            z-index: 1;
            margin: 0;
            color: #fff;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: clamp(12px, 4.6vw, 21px);
            max-width: 100%;
            letter-spacing: .12em;
            word-spacing: .2em;
        }

        .db .db-card .row3 {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 12px;
        }

        .db .db-card .row3 small {
            display: block;
            font-size: 10px;
            letter-spacing: .1em;
            text-transform: uppercase;
            opacity: .65;
        }

        .db .db-card .row3 h5 {
            margin: 2px 0 0;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .db .db-card .row3 strong {
            font-size: 15px;
            font-weight: 600;
        }

        /* Hidden on purpose: kept so any script that looks for #circle3 still finds it */
        .db #circle3 {
            display: none;
        }

        /* Transactions */
        .db .db-panel {
            background: #fff;
            border: 1px solid var(--db-border);
            border-radius: 14px;
            box-shadow: 0 6px 24px rgba(15, 23, 42, .05);
            overflow: hidden;
        }

        .db .db-panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--db-border);
        }

        .db .db-panel-head h4 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            color: #111827;
        }

        .db .db-panel-head span {
            font-size: 12px;
            font-weight: 600;
            color: var(--db-muted);
            background: #f3f4f6;
            padding: 4px 10px;
            border-radius: 999px;
        }

        .db .tx-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .db .tx-table thead th {
            background: #f9fafb;
            color: var(--db-muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 600;
            padding: 13px 20px;
            border: 0;
            white-space: nowrap;
        }

        .db .tx-table tbody td {
            padding: 16px 20px;
            border: 0;
            border-top: 1px solid #f1f1f4;
            font-size: 14px;
            color: #374151;
            vertical-align: middle;
        }

        .db .tx-table tbody tr:hover {
            background: #f8fafc;
        }

        .db .tx-name {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .db .tx-ico {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .db .tx-ico.in { background: rgba(22, 163, 74, .12); color: var(--db-green); }
        .db .tx-ico.out { background: rgba(220, 38, 38, .1); color: var(--db-red); }

        .db .tx-name h6 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            text-transform: capitalize;
        }

        .db .tx-name small {
            font-size: 12px;
            color: #9ca3af;
        }

        .db .amt.in { color: var(--db-green); font-weight: 700; }
        .db .amt.out { color: var(--db-red); font-weight: 700; }

        .db .pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .db .pill.ok { background: rgba(22, 163, 74, .12); color: #15803d; }
        .db .pill.bad { background: rgba(220, 38, 38, .1); color: #b91c1c; }
        .db .pill.wait { background: rgba(245, 158, 11, .15); color: #b45309; }

        .db .tx-view {
            display: inline-block;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--db-primary);
            background: var(--db-soft);
            text-decoration: none;
            white-space: nowrap;
            transition: background .15s, color .15s;
        }

        .db .tx-view:hover {
            background: var(--db-primary);
            color: #fff;
        }

        .db .tx-empty {
            text-align: center;
            padding: 48px 20px;
            color: var(--db-muted);
        }

        .db .tx-empty .ring {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--db-soft);
            color: var(--db-primary);
            display: grid;
            place-items: center;
            margin: 0 auto 12px;
        }

        /* Mobile transaction cards */
        .db .tx-mobile {
            display: none;
        }

        .db .tx-card {
            display: block;
            background: #fff;
            border: 1px solid var(--db-border);
            border-radius: 14px;
            box-shadow: 0 6px 24px rgba(15, 23, 42, .05);
            padding: 16px;
            margin-bottom: 12px;
            text-decoration: none;
            color: inherit;
        }

        .db .tx-card .top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .db .tx-card .bottoms {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 12px;
        }

        .db .tx-card .bottoms .amt {
            font-size: 16px;
        }

        .db .tx-card .date {
            display: block;
            margin-top: 10px;
            font-size: 12px;
            color: #9ca3af;
        }

        @media (max-width: 1199px) {
            .db .db-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 991px) {
            .db .db-two { grid-template-columns: minmax(0, 1fr); }
        }

        @media (max-width: 767px) {
            .db .db-actions { grid-template-columns: minmax(0, 1fr); }
            .db .tx-desktop { display: none; }
            .db .tx-mobile { display: block; }
        }

        @media (max-width: 575px) {
            .db .db-stat { padding: 14px; gap: 10px; }
            .db .db-stat .ico { width: 38px; height: 38px; }
            .db .db-stat .val { font-size: 16px; }
            .db .db-total h2 { font-size: 28px; }
            .db .db-card .num { font-size: clamp(12px, 4.6vw, 17px); }
        }
    
        /* ---- Small-screen safety: nothing may spill out of its card ---- */
        .db .db-stats > *,
        .db .db-two > *,
        .db .db-actions > *,
        .db .db-stat > div,
        .db .db-action > div,
        .db .tx-name,
        .db .tx-name > div,
        .db .db-card .row3 > div {
            min-width: 0;
        }

        .db .tx-name h6,
        .db .db-card .row3 h5 {
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .db .tx-card {
            overflow: hidden;
        }

        @media (max-width: 575px) {
            .db .tx-card .top { flex-wrap: wrap; }
            .db .db-card { padding: 20px; }
            .db .db-panel-head { padding: 16px; }
        }

        @media (max-width: 360px) {
            .db .db-stat { flex-direction: column; align-items: flex-start; }
            .db .db-stat .val { font-size: 15px; }

            .db .db-total { padding: 20px; min-height: 0; gap: 18px; }
            .db .db-total h2 { font-size: 24px; }
            .db .db-total .personal { flex-wrap: wrap; gap: 2px 10px; }

            .db .db-card { padding: 16px; min-height: 0; gap: 14px; }
            .db .db-card .num { letter-spacing: .06em; word-spacing: .1em; }
            .db .db-card .row3 { gap: 10px 16px; }
            .db .db-card .row3 h5,
            .db .db-card .row3 strong { font-size: 13px; }

            .db .tx-card { padding: 12px; }
            .db .tx-card .bottoms { flex-wrap: wrap; gap: 4px 10px; }
            .db .tx-card .bottoms .amt { font-size: 15px; }
            .db .tx-name { gap: 10px; }
            .db .tx-ico { width: 32px; height: 32px; }

            .db .db-action { padding: 14px; gap: 10px; }
        }

        @media (max-width: 320px) {
            .db .db-stats { grid-template-columns: minmax(0, 1fr); }
            .db .db-stat { flex-direction: row; align-items: center; }
        }
    </style>
</head>

<body class="dashboard">

    <div id="main-wrapper">
        <!-- nav bar -->
        <?php include("../include/header.php") ?>

        <!-- side nav -->
        <?php include("../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container db">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-6">
                                    <div class="page-title-content">
                                        <h3>Dashboard</h3>
                                        <p class="mb-2">Welcome <?= $sitename ?> Finance Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="db-crumb">
                                        <a href="<?php echo $domain ?>/dashboard/ ">Dashboard</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Balances -->
                <div class="db-stats">
                    <div class="db-stat">
                        <span class="ico blue">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18" /><path d="M5 21V10" /><path d="M19 21V10" /><path d="M9 21v-8" /><path d="M15 21v-8" /><path d="m2 10 10-7 10 7z" /></svg>
                        </span>
                        <div>
                            <div class="lbl">Balance</div>
                            <div class="val">$<?= money($balance) ?></div>
                        </div>
                    </div>

                    <div class="db-stat">
                        <span class="ico amber">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2" /><path d="M2 10h20" /></svg>
                        </span>
                        <div>
                            <div class="lbl">Loan balance</div>
                            <div class="val">$<?= money($loan_balance) ?></div>
                        </div>
                    </div>

                    <div class="db-stat">
                        <span class="ico violet">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9" /><path d="M9.5 8.5h4a1.75 1.75 0 0 1 0 3.5h-4m0 0h4.5a1.75 1.75 0 0 1 0 3.5H9.5" /><path d="M9.5 8v8" /></svg>
                        </span>
                        <div>
                            <div class="lbl">Crypto Balance</div>
                            <div class="val">$<?= money($crypto_balance) ?></div>
                        </div>
                    </div>

                    <div class="db-stat">
                        <span class="ico green">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2" /><circle cx="12" cy="12" r="2.5" /><path d="M6 12h.01M18 12h.01" /></svg>
                        </span>
                        <div>
                            <div class="lbl">Virtual Card Balance</div>
                            <div class="val">$<?= money($virtual_card_balance) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Quick actions: Deposit / Transfer / Withdraw -->
                <div class="db-actions">
                    <a href="<?php echo $domain ?>/deposits/" class="db-action">
                        <span class="ico">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14" /><path d="m19 12-7 7-7-7" /></svg>
                        </span>
                        <div>
                            <h6>Deposit</h6>
                            <p>Fund your wallet</p>
                        </div>
                        <svg class="arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6" /></svg>
                    </a>

                    <a href="<?php echo $domain ?>/transfer/" class="db-action">
                        <span class="ico">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 7h13l-4-4" /><path d="M17 17H4l4 4" /></svg>
                        </span>
                        <div>
                            <h6>Transfer</h6>
                            <p>Send money</p>
                        </div>
                        <svg class="arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6" /></svg>
                    </a>

                    <a href="<?php echo $domain ?>/withdrawal/" class="db-action">
                        <span class="ico">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5" /><path d="m5 12 7-7 7 7" /></svg>
                        </span>
                        <div>
                            <h6>Withdraw</h6>
                            <p>Cash out funds</p>
                        </div>
                        <svg class="arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6" /></svg>
                    </a>
                </div>

                <?php
                $user_id = $_SESSION['user_id'];

                $sql = "SELECT virtual_card_number,  virtual_card_expiring_date 
                                           FROM users 
                                           WHERE id = ? 
                                          LIMIT 1";

                $stmt = mysqli_prepare($connection, $sql);
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);

                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_assoc($result);

                mysqli_stmt_close($stmt);

                $cardNumber = implode(' ', str_split($user['virtual_card_number'], 4));
                $expiry = date('m/y', strtotime($user['virtual_card_expiring_date']));
                ?>

                <!-- Total balance + virtual card -->
                <div class="db-two">

                    <div class="db-total">
                        <div class="top">
                            <div class="small-t">Total Balance</div>
                            <h2>$<?php echo number_format($balance + $crypto_balance + $virtual_card_balance + $loan_balance, 2); ?></h2>
                        </div>
                        <div class="personal">
                            <span>Personal Balance</span>
                            <strong>$<?php echo number_format($balance) ?></strong>
                        </div>
                    </div>

                    <div class="db-card">
                        <div class="row1">
                            <h4>Virtual Card</h4>
                            <span class="brand"><img src="<?php echo $domain ?>/images/cc/visa.png" alt=""></span>
                        </div>

                        <svg class="chip" width="40" height="30" viewBox="0 0 40 30" fill="none"><rect width="40" height="30" rx="6" fill="#fbbf24" /><path d="M0 10h40M0 20h40M14 0v30M26 0v30" stroke="#b45309" stroke-opacity=".5" stroke-width="1.2" /></svg>

                        <h6 class="num"><?= $cardNumber ?></h6>

                        <div class="row3">
                            <div>
                                <small>Card holder</small>
                                <h5><?php echo $fullname  ?></h5>
                            </div>
                            <div>
                                <small>Expires</small>
                                <strong class="text-white"><?= $expiry ?></strong>
                            </div>
                            <div>
                                <small>Currency</small>
                                <strong class="text-white">USD</strong>
                            </div>
                        </div>

                        <div id="circle3"></div>
                    </div>

                </div>

                <?php
                // Use UNION ALL to stack the tables. 
                // We add 'type' to know where the data came from.
                // We use NULL or '' for missing columns in deposits/withdrawals.
                $query = $connection->query("
    SELECT * FROM (
        -- Transfers
        SELECT 
            id, 
            amount, 
            created_at, 
            status, 
            'transfer' AS type, 
            receiver_name AS transaction_name, 
            state
        FROM bank_transfers 
        WHERE user_id = $user_id

        UNION ALL

        -- Withdrawals
        SELECT 
            id, 
            amount, 
            date AS created_at, 
            status, 
            'withdrawal' AS type, 
            account_name AS transaction_name, 
            'Null' AS state
        FROM withdrawals 
        WHERE user_id = $user_id

        UNION ALL

        -- Deposits (JOIN payment_account)
        SELECT 
            d.id, 
            d.amount, 
            d.date AS created_at, 
            d.status, 
            'deposit' AS type, 
            CASE 
                WHEN pa.type = 'bank' THEN pa.fullname
                ELSE pa.network
            END AS transaction_name,
            'Null' AS state
        FROM deposits d
        LEFT JOIN payment_account pa 
            ON d.type_id = pa.id
        WHERE d.user_id = $user_id

    ) AS transactions
    ORDER BY created_at DESC
    LIMIT 20
");

                // Read the rows once; the desktop table and mobile cards both use them
                $transactions = [];
                if ($query && $query->num_rows > 0) {
                    while ($row = $query->fetch_assoc()) {
                        $transactions[] = $row;
                    }
                }
                ?>

                <!-- Transaction History -->
                <div class="row">
                    <div class="col-12">

                        <!-- Desktop / tablet: table -->
                        <div class="tx-desktop">
                            <div class="db-panel">
                                <div class="db-panel-head">
                                    <h4>Transaction History</h4>
                                    <span>Last 20</span>
                                </div>

                                <?php if (!empty($transactions)): ?>
                                    <div class="table-responsive">
                                        <table class="tx-table">
                                            <thead>
                                                <tr>
                                                    <th>Transaction Type</th>
                                                    <th>Amount</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($transactions as $transfer):
                                                    $v = tx_view($transfer); ?>
                                                    <tr>
                                                        <td>
                                                            <div class="tx-name">
                                                                <span class="tx-ico <?= $v['amount_class'] ?>">
                                                                    <?php if ($v['is_credit']): ?>
                                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 7 7 17" /><path d="M17 17H7V7" /></svg>
                                                                    <?php else: ?>
                                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7" /><path d="M7 7h10v10" /></svg>
                                                                    <?php endif; ?>
                                                                </span>
                                                                <div>
                                                                    <h6><?= htmlspecialchars($v['name']) ?></h6>
                                                                    <small><?= $v['label'] ?></small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span class="amt <?= $v['amount_class'] ?>"><?= $v['sign'] ?>$<?= $v['amount'] ?></span></td>
                                                        <td><?= $v['date'] ?></td>
                                                        <td><span class="pill <?= $v['pill'] ?>"><?= htmlspecialchars($v['status_text']) ?></span></td>
                                                        <td>
                                                            <a class="tx-view" href="<?= htmlspecialchars($v['link']) ?>">View Details</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="tx-empty">
                                        <div class="ring">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7L3 8" /><path d="M3 3v5h5" /><path d="M12 7v5l3 2" /></svg>
                                        </div>
                                        No history found.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Mobile: cards -->
                        <div class="tx-mobile">
                            <div class="db-panel-head" style="background:#fff;border:1px solid #eef0f4;border-radius:14px;margin-bottom:12px;">
                                <h4>Transaction History</h4>
                                <span>Last 20</span>
                            </div>

                            <?php if (!empty($transactions)): ?>
                                <?php foreach ($transactions as $transfer):
                                    $v = tx_view($transfer); ?>
                                    <a class="tx-card" href="<?= htmlspecialchars($v['link']) ?>">
                                        <div class="top">
                                            <div class="tx-name">
                                                <span class="tx-ico <?= $v['amount_class'] ?>">
                                                    <?php if ($v['is_credit']): ?>
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 7 7 17" /><path d="M17 17H7V7" /></svg>
                                                    <?php else: ?>
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7" /><path d="M7 7h10v10" /></svg>
                                                    <?php endif; ?>
                                                </span>
                                                <div>
                                                    <h6><?= htmlspecialchars($v['name']) ?></h6>
                                                    <small><?= $v['label_mobile'] ?></small>
                                                </div>
                                            </div>
                                            <span class="pill <?= $v['pill'] ?>"><?= htmlspecialchars($v['status_text']) ?></span>
                                        </div>

                                        <div class="bottoms">
                                            <span class="date" style="margin:0"><?= $v['date'] ?></span>
                                            <strong class="amt <?= $v['amount_class'] ?>"><?= $v['sign'] ?>$<?= $v['amount'] ?></strong>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="db-panel">
                                    <div class="tx-empty">
                                        <div class="ring">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7L3 8" /><path d="M3 3v5h5" /><path d="M12 7v5l3 2" /></svg>
                                        </div>
                                        No history found.
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>



    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/chartjs/chartjs.js"></script>
    <script src="<?php echo $domain ?>/js/plugins/chartjs-line-balance-overtime.js"></script>
    <script src="<?php echo $domain ?>/js/scripts.js"></script>
</body>

</html>