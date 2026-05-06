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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $sitename ?> | dashboard</title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <link rel="stylesheet" href="<?php echo $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>/vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">

    <div id="main-wrapper">
        <!-- nav bar -->
        <?php include("../include/header.php") ?>

        <!-- side nav -->
        <?php include("../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-4">
                                    <div class="page-title-content">
                                        <h3>Dashboard</h3>
                                        <p class="mb-2">Welcome <?= $sitename ?> Finance Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="breadcrumbs">
                                        <a href="<?php echo $domain ?>/dashboard/ ">Dashboard</a>
                                        <span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wallet-tab">
                    <div class="row g-0">
                        <div class="col-xl-3">
                            <div class="nav d-block">
                                <div class="row">
                                    <div class="col-xl-12 col-md-6">
                                        <div class="wallet-nav">
                                            <div class="wallet-nav-icon">
                                                <span><i class="fi fi-rr-bank"></i></span>
                                            </div>
                                            <div class="wallet-nav-text">
                                                <h3>Balance</h3>
                                                <p>$<?= money($balance) ?></p>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-6">
                                        <div class="wallet-nav">
                                            <!-- data-bs-toggle="pill" data-bs-target="#a2" -->
                                            <div class="wallet-nav-icon">
                                                <span><i class="fi fi-rr-credit-card"></i></span>
                                            </div>
                                            <div class="wallet-nav-text">
                                                <h3>Loan balance</h3>
                                                <p>$<?= money($loan_balance) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-6">
                                        <div class="wallet-nav">
                                            <div class="wallet-nav-icon">
                                                <span><i class="fi fi-brands-visa"></i></span>
                                            </div>
                                            <div class="wallet-nav-text">
                                                <h3>Crypto Balance</h3>
                                                <p>$<?= money($crypto_balance) ?></p>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-6">
                                        <div class="wallet-nav">
                                            <div class="wallet-nav-icon">
                                                <span><i class="fi fi-rr-money-bill-wave-alt"></i></span>
                                            </div>
                                            <div class="wallet-nav-text">
                                                <h3>Virtual Card Balance</h3>
                                                <p>$<?= money($virtual_card_balance) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>



                        <div class="col-xl-9">
                            <div class="tab-content wallet-tab-content">

                                <!-- ================= BALANCE TAB ================= -->
                                <div class="tab-pane show active" id="a1">
                                    <div class="wallet-tab-title">
                                        <h3>Zentra Bank</h3>
                                    </div>

                                    <!--  Deposit / Withdraw / Transfer -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row g-3">

                                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                                            <a href="<?php echo $domain ?>/deposits/" class="d-block text-decoration-none">
                                                                <div class="stat-widget-1">
                                                                    <h6><i class="fi fi-rr-money-bill-wave me-2"></i>Deposit</h6>
                                                                    <p class="mb-0">Fund your wallet</p>
                                                                </div>
                                                            </a>
                                                        </div>

                                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                                            <a href="<?php echo $domain ?>/transfer/" class="d-block text-decoration-none">
                                                                <div class="stat-widget-1">
                                                                    <h6><i class="fi fi-rr-exchange me-2"></i>Transfer</h6>
                                                                    <p class="mb-0">Send money</p>
                                                                </div>
                                                            </a>
                                                        </div>

                                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                                            <a href="<?php echo $domain ?>/withdrawal/" class="d-block text-decoration-none">
                                                                <div class="stat-widget-1">
                                                                    <h6> <i class="fi fi-rr-donate"> </i> Withdraw</h6>
                                                                    <p class="mb-0">Cash out funds</p>
                                                                </div>
                                                            </a>
                                                        </div>



                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  END QUICK ACTIONS -->

                                    <div class="row">
                                        <div class="col-xxl-6 col-xl-6 col-lg-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="wallet-total-balance">
                                                        <p class="mb-0">Total Balance</p>
                                                        <h2>
                                                            $<?php echo number_format($balance + $crypto_balance + $virtual_card_balance + $loan_balance, 2); ?>
                                                        </h2>
                                                    </div>
                                                    <div class="funds-credit">
                                                        <p class="mb-0">Personal Balance</p>
                                                        <h5>$<?php echo number_format($balance) ?></h5>
                                                    </div>
                                                    <!--<div class="funds-credit">-->
                                                    <!--    <p class="mb-0">Credit Limits</p>-->
                                                    <!--    <h5>$<?php echo number_format($limit)  ?></h5>-->
                                                    <!--</div>-->
                                                </div>
                                            </div>
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


                                        <div class="col-xxl-6 col-xl-6 col-lg-6">
                                            <div class="credit-card visa">
                                                <div class="type-brand">
                                                    <h4>Virtual Card</h4>
                                                    <img src="<?php echo $domain ?>/images/cc/visa.png" alt="">
                                                </div>
                                                <div class="cc-number">
                                                    <h6><?= $cardNumber ?></h6>
                                                </div>
                                                <div class="cc-holder-exp">
                                                    <h5><?php echo $fullname  ?></h5>
                                                    <div class="exp"><span>EXP:</span><strong><?= $expiry ?></strong></div>
                                                </div>
                                                <div class="cc-info">
                                                    <div class="row justify-content-between align-items-center">
                                                        <div class="col-5">
                                                            <div class="d-flex">
                                                                <!-- <p class="me-3">Status</p>
                                                                <p><strong>Active</strong></p> -->
                                                            </div>
                                                            <div class="d-flex">
                                                                <p class="me-3">Currency</p>
                                                                <p><strong>USD</strong></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-7">
                                                            <div class="d-flex justify-content-between">
                                                                <div class="ms-3">
                                                                    </p>
                                                                </div>
                                                                <div id="circle3"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                </div>

                                <!-- ================= OTHER TABS (UNCHANGED) ================= -->
                                <div class="tab-pane" id="a2">
                                    <div class="wallet-tab-title">
                                        <h3>Debit Card</h3>
                                    </div>
                                    <!-- (rest of your a2 content remains the same) -->
                                    <?php /* Keep your existing a2 content here exactly */ ?>
                                </div>

                                <div class="tab-pane" id="a3">
                                    <div class="wallet-tab-title">
                                        <h3>Visa Card</h3>
                                    </div>
                                    <!-- (rest of your a3 content remains the same) -->
                                    <?php /* Keep your existing a3 content here exactly */ ?>
                                </div>

                                <div class="tab-pane" id="a4">
                                    <div class="wallet-tab-title">
                                        <h3>Cash</h3>
                                    </div>
                                    <!-- (rest of your a4 content remains the same) -->
                                    <?php /* Keep your existing a4 content here exactly */ ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="d-none d-md-block">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Transaction History</h4>
                                </div>
                                <div class="card-body">
                                    <div class="transaction-table">
                                        <div class="table-responsive">
                                            <table class="table mb-0 table-responsive-sm">
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
    LIMIT 5
");

                                                    if ($query->num_rows > 0) {
                                                        while ($transfer = $query->fetch_assoc()) { ?>
                                                            <tr>

                                                                <?php
                                                                // Determine display type and badge color
                                                                if ($transfer['type'] == 'deposit') {
                                                                    $displayType = 'Credit';
                                                                    $typeClass = 'bg-success'; // green
                                                                } else { // withdrawal or transfer
                                                                    $displayType = 'Debit';
                                                                    $typeClass = 'bg-danger'; // red
                                                                }
                                                                if (in_array($transfer['status'], ['completed', 'success', 'approved'])) {
                                                                    $statusText = 'Successful';
                                                                } else {
                                                                    $statusText = ucfirst($transfer['status']);
                                                                }
                                                                ?>
                                                                <td>
                                                                    <h6 class="mb-1" style="text-transform:capitalize">
                                                                        <?php
                                                                        if ($transfer['type'] == 'transfer') {
                                                                            echo $transfer['state'] == 'to' ? 'Transfer to ' . $transfer['transaction_name'] : 'Transfer from '  . $transfer['transaction_name'];
                                                                        } else if ($transfer['type'] == 'deposit') {
                                                                            echo  'Account Funded Via ' . $transfer['transaction_name'];
                                                                        } else {
                                                                            echo $transfer['transaction_name'] ?? ucfirst($transfer['type']);
                                                                        }

                                                                        ?>

                                                                    </h6>

                                                                    <small>
                                                                        <?php
                                                                        if ($transfer['type'] == 'deposit' || $transfer['state'] == 'from') {
                                                                            echo 'Credit';
                                                                        } else {
                                                                            echo 'Debit';
                                                                        }
                                                                        ?>
                                                                    </small>
                                                                </td>
                                                                <td>$<?php echo number_format($transfer['amount']); ?></td>
                                                                <td><?php echo date('d M Y, h:i A', strtotime($transfer['created_at'])); ?></td>
                                                                <td>
                                                                    <span class="badge text-white <?php
                                                                                                    echo ($transfer['status'] == 'pending') ? 'bg-warning' : (($transfer['status'] == 'completed' || $transfer['status'] == 'success'  || $transfer['status'] == 'approved') ? 'bg-success' : 'bg-danger'); ?>">
                                                                        <?php echo $statusText  ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <!-- You can use the 'type' column here to link to different detail pages if needed -->
                                                                    <a href="../transaction/?id=<?php echo $transfer['id']; ?>&type=<?php echo $transfer['type']; ?>">
                                                                        <span class="badge p-2 bg-info text-white">View Details</span>
                                                                    </a>
                                                                </td>
                                                            </tr>

                                                    <?php }
                                                    } else {
                                                        echo '<tr><td colspan="8" class="text-center text-danger">No history found.</td></tr>';
                                                    }
                                                    ?>



                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-block d-md-none">
                            <?php
                            if ($query->num_rows > 0) {
                                // reset pointer
                                $query->data_seek(0);

                                while ($transfer = $query->fetch_assoc()) {

                                    if ($transfer['type'] == 'deposit') {
                                        $displayType = 'Credited';
                                        $typeClass = 'bg-success';
                                    } else {
                                        $displayType = 'Debited';
                                        $typeClass = 'bg-danger';
                                    }
                            ?>

                                    <div class="card mb-3 shadow-sm"
                                        style="cursor:pointer;"
                                        onclick="window.location.href='../transaction/?id=<?php echo $transfer['id']; ?>&type=<?php echo $transfer['type']; ?>'">

                                        <div class="card-body">

                                            <!-- Transaction Name -->

                                            <h6 class="mb-1" style="text-transform:capitalize">
                                                <?php
                                                if ($transfer['type'] == 'transfer') {
                                                    echo $transfer['state'] == 'to' ? 'Transfer to ' . $transfer['transaction_name'] : 'Transfer from '  . $transfer['transaction_name'];
                                                } else if ($transfer['type'] == 'deposit') {
                                                    echo  'Account Funded Via ' . $transfer['transaction_name'];
                                                } else {
                                                    echo $transfer['transaction_name'] ?? ucfirst($transfer['type']);
                                                }

                                                ?>

                                            </h6>

                                            <!-- Status -->
                                            <?php
                                            if (in_array($transfer['status'], ['completed', 'success', 'approved'])) {
                                                $statusText = 'Successful';
                                            } else {
                                                $statusText = ucfirst($transfer['status']);
                                            }
                                            ?>

                                            <span class="badge text-white <?php
                                                                            echo ($transfer['status'] == 'pending') ? 'bg-warning' : (($transfer['status'] == 'completed' || $transfer['status'] == 'success' || $transfer['status'] == 'approved') ? 'bg-success' : 'bg-danger'); ?>">
                                                <?php echo $statusText; ?>
                                            </span>

                                            <!-- Row: Details + Amount -->
                                            <div class="d-flex justify-content-between align-items-center mt-2">

                                                <!-- Left -->
                                                <div>
                                                    <small>
                                                        <?php
                                                        if ($transfer['type'] == 'deposit' || $transfer['state'] == 'from') {
                                                            if ($transfer['status'] == 'declined' || $transfer['status'] == 'rejected') {
                                                                echo 'Failed';
                                                            } else {
                                                                echo 'Credit';
                                                            }
                                                        } else {
                                                            if ($transfer['status'] == 'declined' || $transfer['status'] == 'rejected') {
                                                                echo 'Failed';
                                                            } else {
                                                                echo 'Debit';
                                                            }
                                                        }
                                                        ?>
                                                    </small>
                                                </div>

                                                <!-- Right -->
                                                <div>
                                                    <?php
                                                    $type  = $transfer['type'] ?? '';
                                                    $state = $transfer['state'] ?? '';

                                                    $isCredit = ($type == 'deposit' || $state == 'from');

                                                    $class = $isCredit ? 'text-success' : 'text-danger';
                                                    $sign  = $isCredit ? '+' : '-';
                                                    ?>

                                                    <strong class="<?php echo $class; ?>">
                                                        <?php echo $sign; ?>$<?php echo number_format($transfer['amount']); ?>
                                                    </strong>
                                                </div>

                                            </div>

                                            <!-- Date -->
                                            <small class="text-muted d-block mt-2">
                                                <?php echo date('d M Y, h:i A', strtotime($transfer['created_at'])); ?>
                                            </small>

                                        </div>
                                    </div>

                            <?php
                                }
                            } else {
                                echo '<div class="text-center text-danger">No history found.</div>';
                            }
                            ?>
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