<?php
include("../server/connection.php");
if (!isset($_SESSION['user_id'])) {
    header("location: {$domain}/auth/sign_in/");
    exit;
}


$user_id = $_SESSION['user_id'];

// default values (avoid errors)
$fullname = "";
$balance = 0;
$loan_balance = 0;
$crypto_balance = 0;
$virtual_card_balance = 0;

$sql = "SELECT fullname, balance, loan_balance, crypto_balance, virtual_card_balance
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
                                        <a href="<?php echo  $domain ?>/dashboard/ ">Dashboard</a>
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
                                                            <a href="<?php echo $domain ?>/withdrawal/" class="d-block text-decoration-none">
                                                                <div class="stat-widget-1">
                                                                    <h6> <i class="fi fi-rr-donate"> </i> Withdraw</h6>
                                                                    <p class="mb-0">Cash out funds</p>
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
                                                        <h2>$221,478</h2>
                                                    </div>
                                                    <div class="funds-credit">
                                                        <p class="mb-0">Personal Funds</p>
                                                        <h5>$32,500.28</h5>
                                                    </div>
                                                    <div class="funds-credit">
                                                        <p class="mb-0">Credit Limits</p>
                                                        <h5>$2500.00</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-6 col-xl-6 col-lg-6">
                                            <div class="credit-card visa">
                                                <div class="type-brand">
                                                    <h4>Debit Card</h4>
                                                    <img src="<?php echo $domain ?>/images/cc/visa.png" alt="">
                                                </div>
                                                <div class="cc-number">
                                                    <h6>1234</h6>
                                                    <h6>5678</h6>
                                                    <h6>7890</h6>
                                                    <h6>9875</h6>
                                                </div>
                                                <div class="cc-holder-exp">
                                                    <h5>Saiful Islam</h5>
                                                    <div class="exp"><span>EXP:</span><strong>12/21</strong></div>
                                                </div>
                                                <div class="cc-info">
                                                    <div class="row justify-content-between align-items-center">
                                                        <div class="col-5">
                                                            <div class="d-flex">
                                                                <p class="me-3">Status</p>
                                                                <p><strong>Active</strong></p>
                                                            </div>
                                                            <div class="d-flex">
                                                                <p class="me-3">Currency</p>
                                                                <p><strong>USD</strong></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-7">
                                                            <div class="d-flex justify-content-between">
                                                                <div class="ms-3">
                                                                    <p>Credit Limit</p>
                                                                    <p><strong>2000 USD</strong></p>
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
                                                    <th>Id</th>
                                                    <th>Account Number</th>
                                                    <th>Bank</th>
                                                    <th>Narration</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $query = $connection->query("SELECT * FROM bank_transfers WHERE user_id=$user_id ORDER BY id DESC LIMIT 5");
                                                if ($query->num_rows > 0) {
                                                    while ($transfer = $query->fetch_assoc()) { ?>

                                                        <tr>
                                                            <td>1</td>
                                                            <td><?php echo $transfer['receiver_account_number'] ?></td>
                                                            <td><?php echo $transfer['receiver_bank'] ?></td>
                                                            <td>Payment for services</td>
                                                            <td>$<?php echo $transfer['amount']  ?></td>
                                                            <td>
                                                                <span class="badge text-white <?php
                                                                                                echo ($transfer['status'] == 'pending')
                                                                                                    ? 'bg-warning'
                                                                                                    : (($transfer['status'] == 'completed')
                                                                                                        ? 'bg-success'
                                                                                                        : 'bg-danger'); ?>">
                                                                    <?php echo ucfirst($transfer['status']); ?>
                                                                </span>
                                                            </td>

                                                        </tr>

                                                <?php }
                                                }

                                                ?>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
                            <a href="wallets.html#">Ekash</a> I All Rights Reserved
                        </p>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="footer-social">
                        <ul>
                            <li><a href="wallets.html#"><i class="fi fi-brands-facebook"></i></a></li>
                            <li><a href="wallets.html#"><i class="fi fi-brands-twitter"></i></a></li>
                            <li><a href="wallets.html#"><i class="fi fi-brands-linkedin"></i></a></li>
                            <li><a href="wallets.html#"><i class="fi fi-brands-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/chartjs/chartjs.js"></script>
    <script src="<?php echo $domain ?>/js/plugins/chartjs-line-balance-overtime.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>