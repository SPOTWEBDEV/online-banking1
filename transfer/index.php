<?php
include("../server/connection.php");



$errors = [];
$success = "";

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("location: {$domain}/auth/sign_in/");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $receiver_account_number = isset($_POST["receiver_account_number"]) ? trim($_POST["receiver_account_number"]) : "";
    $receiver_name          = isset($_POST["receiver_name"]) ? trim($_POST["receiver_name"]) : "";
    $receiver_bank          = isset($_POST["receiver_bank"]) ? trim($_POST["receiver_bank"]) : "";
    $routing_number          = isset($_POST["routing_number"]) ? trim($_POST["routing_number"]) : "";
    $swift_code              = isset($_POST["swift_code"]) ? trim($_POST["swift_code"]) : "";
    $amount                  = isset($_POST["amount"]) ? trim($_POST["amount"]) : "";
    $narration               = isset($_POST["narration"]) ? trim($_POST["narration"]) : "";


    if ($receiver_account_number === "") {
        $errors[] = "Receiver account number is required.";
    } elseif (strlen($receiver_account_number) < 8 || strlen($receiver_account_number) > 30) {
        $errors[] = "Receiver account number must be between 8 and 30 characters.";
    }

    if ($receiver_name === "") {
        $errors[] = "Receiver Name is required.";
    } elseif (strlen($receiver_name) < 3 || strlen($receiver_name) > 100) {
        $errors[] = "Receiver Name must be between 3 and 100 characters.";
    }


    if ($routing_number !== "" && strlen($routing_number) > 50) {
        $errors[] = "Routing number is too long.";
    }

    if ($swift_code !== "" && strlen($swift_code) > 20) {
        $errors[] = "Swift code is too long.";
    }

    if ($amount === "") {
        $errors[] = "Amount is required.";
    } elseif (!is_numeric($amount)) {
        $errors[] = "Amount must be a number.";
    } elseif ((float)$amount <= 0) {
        $errors[] = "Amount must be greater than 0.";
    }

    // Narration 
    if ($narration !== "" && strlen($narration) > 255) {
        $errors[] = "Narration must not be more than 255 characters.";
    }

    if (empty($errors)) {

        // Generate OTP (6 digits)
        $otp_code = (string) random_int(100000, 999999);

        // OTP expires in 5 minutes
        $otp_expires_at = date("Y-m-d H:i:s", time() + (5 * 60));


        $insert_sql = "INSERT INTO bank_transfers 
            (user_id, receiver_account_number, receiver_name, receiver_bank, routing_number, swift_code, amount, narration, otp_code, otp_expires_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? , ?)";
        $stmt = mysqli_prepare($connection, $insert_sql);

        if (!$stmt) {
            $errors[] = "server error";
        } else {
            $amount_decimal = (float) $amount;

            mysqli_stmt_bind_param(
                $stmt,
                "isssssdsss",
                $user_id,
                $receiver_account_number,
                $receiver_name,
                $receiver_bank,
                $routing_number,
                $swift_code,
                $amount_decimal,
                $narration,
                $otp_code,
                $otp_expires_at
            );

            $run = mysqli_stmt_execute($stmt);

            if (!$run) {
                $errors[] = "Failed to create transfer: " . mysqli_stmt_error($stmt);
            } else {
                $transfer_id = mysqli_insert_id($connection);
                $success = "Transfer created successfully";
            }

            mysqli_stmt_close($stmt);
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
    <title><?= $sitename ?> | loan </title>
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
        <!-- sidenav-->
        <?php include("../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-4">
                                    <div class="page-title-content">
                                        <h3>Transfer</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="./transfer_history/"><button class="btn btn-primary mr-2">View Transfer History</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-xl-12">



                        <div class="row">
                            <div class="col-xxl-12">
                                <!-- <h4 class="card-title mb-3">Transfer</h4> -->
                                <div class="card">
                                    <div class="card-body">
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

                                        <form action="" method="post">
                                            <div class="row">

                                                <!-- Receiver Account Number -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Receiver Account Number</label>
                                                    <input name="receiver_account_number" type="text" class="form-control" placeholder="Enter receiver account number">
                                                </div>

                                                <!-- Receiver Email -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Receiver Account Name</label>
                                                    <input name="receiver_name" type="text" class="form-control" placeholder="Enter receiver Name">
                                                </div>

                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">

                                                    <label class="form-label">Select Bank</label>

                                                    <select name="receiver_name" class="form-control">
                                                        <option selected>Select Bank</option>
                                                        <?php
                                                        $users = mysqli_query($connection, "SELECT id, name FROM bank_list");
                                                        while ($u = mysqli_fetch_assoc($users)) {
                                                            echo '<option value="' . $u['name'] . '">' . $u['name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>

                                                </div>



                                                <!-- Routing Number -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Routing Number</label>
                                                    <input name="routing_number" type="text" class="form-control" placeholder="Enter routing number (optional)">
                                                </div>

                                                <!-- Swift Code -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Swift Code</label>
                                                    <input name="swift_code" type="text" class="form-control" placeholder="Enter swift code (optional)">
                                                </div>

                                                <!-- Amount -->
                                                <div class="col-xxl-12 col-xl-12 col-lg-12 mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input name="amount" type="number" step="0.01" class="form-control" placeholder="Enter amount">
                                                </div>

                                                <!-- Narration -->
                                                <div class="col-xxl-12 col-xl-12 col-lg-12 mb-3">
                                                    <label class="form-label">Narration</label>
                                                    <input name="narration" type="text" class="form-control" placeholder="e.g. Rent payment">
                                                </div>

                                            </div>

                                            <div class="mt-3">
                                                <button type="submit" class="btn btn-primary mr-2">Transfer</button>
                                            </div>
                                        </form>

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
                                <a href="#"><?= $sitename ?></a> I All Rights Reserved
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="footer-social">
                            <ul>
                                <li><a href="settings-api.html#"><i class="fi fi-brands-facebook"></i></a></li>
                                <li><a href="settings-api.html#"><i class="fi fi-brands-twitter"></i></a></li>
                                <li><a href="settings-api.html#"><i class="fi fi-brands-linkedin"></i></a></li>
                                <li><a href="settings-api.html#"><i class="fi fi-brands-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--  -->
    <!--  -->
    <script src="<?php echo $domain ?>/js/scripts.js"></script>
</body>

</html>