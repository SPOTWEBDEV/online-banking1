<?php
include("../server/connection.php");
include("../server/auth/client.php");

$user_id = (int) ($_SESSION['user_id'] ?? 0);
$errors = [];
$success = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['withdraw'])) {

    $which_account  = trim($_POST['which_account'] ?? "");
    $amount_raw     = trim($_POST['amount'] ?? "");
    $wallet_address = trim($_POST['wallet_address'] ?? "");

    // Validate selection temporal 
    $allowedAccounts = [
        'USDT(TRC20)'          => 'crypto_balance',
        'BTC'                  => 'crypto_balance',
        'POLYGON'              => 'crypto_balance',
        'MAIN BALANCE'         => 'balance',
        'LOAN BALANCE'         => 'loan_balance',
        'VIRTUAL CARD BALANCE' => 'virtual_card_balance',
    ];

    if ($which_account === "") {
        $errors[] = "Select withdrawal account.";
    } elseif (!array_key_exists($which_account, $allowedAccounts)) {
        $errors[] = "Invalid withdrawal account.";
    } elseif (!is_numeric($amount_raw)) {
        $errors[] = "Invalid withdrawal amount.";
    } else {
        $amount = (float)$amount_raw;
        if ($amount <= 0) {
            $errors[] = "Invalid withdrawal amount.";
        }
    }

    if ($wallet_address === "") {
        $errors[] = "Wallet address is required.";
    } elseif (strlen($wallet_address) < 10 || strlen($wallet_address) > 120) {
        $errors[] = "Invalid wallet address.";
    }

    // Stop early (show only one error as you wanted)
    if (!empty($errors)) {
        $errors = [$errors[0]];
    }

    if (empty($errors)) {

        //  Decide which DB column to check
        $balanceColumn = $allowedAccounts[$which_account];

        mysqli_begin_transaction($connection);

        try {
            // lock user row so they can't submit multiple withdraws at the same time
            $sqlBal = "SELECT `$balanceColumn` FROM users WHERE id = ? FOR UPDATE";
            $stmtBal = mysqli_prepare($connection, $sqlBal);
            if (!$stmtBal) {
                throw new Exception("System error (prepare balance).");
            }

            mysqli_stmt_bind_param($stmtBal, "i", $user_id);
            mysqli_stmt_execute($stmtBal);
            $resBal = mysqli_stmt_get_result($stmtBal);
            $rowBal = mysqli_fetch_assoc($resBal);
            mysqli_stmt_close($stmtBal);

            if (!$rowBal) {
                throw new Exception("User not found.");
            }

            $currentBalance = (float)$rowBal[$balanceColumn];

            if ($amount > $currentBalance) {
                throw new Exception("Insufficient balance for this account.");
            }

            // Insert withdrawal request
            $sqlW = "INSERT INTO withdrawals (user_id, amount, which_account) VALUES (?, ?, ?)";
            $stmtW = mysqli_prepare($connection, $sqlW);
            if (!$stmtW) {
                throw new Exception("System error (prepare withdrawal).");
            }

            mysqli_stmt_bind_param($stmtW, "ids", $user_id, $amount, $which_account);

            if (!mysqli_stmt_execute($stmtW)) {
                mysqli_stmt_close($stmtW);
                throw new Exception("Withdrawal failed. Please try again.");
            }
            mysqli_stmt_close($stmtW);

            // Deduct balance immediately 
            $sqlU = "UPDATE users SET `$balanceColumn` = `$balanceColumn` - ? WHERE id = ?";
            $stmtU = mysqli_prepare($connection, $sqlU);
            if (!$stmtU) {
                throw new Exception("System error (prepare update).");
            }

            mysqli_stmt_bind_param($stmtU, "di", $amount, $user_id);

            if (!mysqli_stmt_execute($stmtU)) {
                mysqli_stmt_close($stmtU);
                throw new Exception("Failed to update balance.");
            }
            mysqli_stmt_close($stmtU);

            mysqli_commit($connection);
            $success = "Withdrawal request submitted successfully. Processing...";
        } catch (Exception $e) {
            mysqli_rollback($connection);
            $errors[] = $e->getMessage();
            $errors = [$errors[0]]; // show one error only
        }
    }
}


?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | Withdrawal </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>/vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">
    <div id="main-wrapper">
        <!-- nav -->
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
                                        <h3>Withdrawal</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="./withdrawal_history/"><button class="btn btn-primary mr-2">View Withdrawal History</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-xl-12">
                        <div class="row g-4">

                            <!-- Error -->
                            <div class="col-12">
                                <?php
                                if (isset($_POST['withdraw'])) {

                                    $which_account   = $_POST['which_account'];
                                    $amount          = floatval($_POST['amount']);
                                    $bank_name       = mysqli_real_escape_string($connection, $_POST['bank_name']);
                                    $account_number  = mysqli_real_escape_string($connection, $_POST['account_number']);
                                    $account_name    = mysqli_real_escape_string($connection, $_POST['account_name']);
                                    $user_id         = $_SESSION['user_id'];  // or whatever variable you use

                                    $errors = [];

                                    // Basic validation
                                    if ($which_account == "Select Account") {
                                        $errors[] = "Please select an account type.";
                                    }

                                    if ($amount <= 0) {
                                        $errors[] = "Amount must be greater than zero.";
                                    }

                                    if (empty($bank_name) || empty($account_number) || empty($account_name)) {
                                        $errors[] = "Please fill all bank details.";
                                    }

                                    // Fetch user's balances
                                    $user = mysqli_query($connection, "SELECT * FROM users WHERE id = $user_id");
                                    $u = mysqli_fetch_assoc($user);

                                    // Get the selected account balance
                                    $current_balance = $u[$which_account];

                                    // Check if user has enough balance
                                    if ($amount > $current_balance) {
                                        $errors[] = "Insufficient balance in selected account.";
                                    }

                                    // If errors exist, display them
                                    if (!empty($errors)) {
                                        echo '<div class="alert alert-danger">';
                                        foreach ($errors as $err) {
                                            echo $err . "<br>";
                                        }
                                        echo '</div>';
                                    } else {

                                        // Deduct balance
                                        $new_balance = $current_balance - $amount;

                                        mysqli_query($connection, "UPDATE users SET $which_account = '$new_balance' WHERE id = $user_id");

                                        // Insert withdrawal record
                                        $stmt = $connection->prepare("
                                                INSERT INTO withdrawals (user_id, amount, which_account, status, date , bank_name, account_number, account_name)
                                                VALUES (?, ?, ?, 'pending', NOW(),?,?,?)
                                            ");

                                        $stmt->bind_param("idssss", $user_id, $amount, $which_account, $bank_name, $account_number, $account_name);
                                        $stmt->execute();

                                        echo '<div class="alert alert-success">Withdrawal request submitted successfully!</div>';
                                        echo '<script>setTimeout(function(){ window.location.href = "./"; }, 2000);</script>';
                                    }
                                }
                                ?>


                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Withdrawal Details</h4>
                                        <span class="badge bg-success">$<?php echo $client['balance'] ?></span>
                                    </div>

                                    <div class="card-body">
                                        <form method="post">

                                            <div class="mb-3">
                                                <label class="mr-sm-2">Account</label>
                                                <select name="which_account" class="form-control">
                                                    <option selected>Select Account</option>
                                                    <?php
                                                    $users = mysqli_query($connection, "SELECT * FROM users WHERE id = $user_id");
                                                    $u = mysqli_fetch_assoc($users);

                                                    echo '<option value="balance">Main Balance : $' . $u['balance'] . '</option>';
                                                    echo '<option value="loan_balance">Loan Balance : $' . $u['loan_balance'] . '</option>';
                                                    echo '<option value="crypto_balance">Crypto Balance : $' . $u['crypto_balance'] . '</option>';
                                                    echo '<option value="virtual_card_balance">Virtual Card Balance : $' . $u['virtual_card_balance'] . '</option>';
                                                    ?>
                                                </select>

                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Amount</label>
                                                <input name="amount" type="number" step="0.01" class="form-control" placeholder="Amount">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Bank Name</label>
                                                <input name="bank_name" type="text" class="form-control" placeholder="Bank Name">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Account Number</label>
                                                <input name="account_number" type="text" class="form-control" placeholder="Account Number">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Account Name</label>
                                                <input name="account_name" type="text" class="form-control" placeholder="Account Name">
                                            </div>

                                            <button type="submit" name="withdraw" class="btn btn-primary w-100">PLACE WITHDRAWAL</button>

                                        </form>
                                    </div>
                                </div>

                            </div>
                            <!-- End -->

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
                                <a href="#"><?= $sitename ?></a> I All Rights Reserved
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

    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $domain ?>/js/scripts.js"></script>
</body>

</html>