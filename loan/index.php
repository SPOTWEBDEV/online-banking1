<?php
include("../server/connection.php");
if (!isset($_SESSION['user_id'])) {
    header("location: {$domain}/auth/sign_in/");
}

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $user_id = $_SESSION['user_id'] ?? null;

    $loan_amount        = trim($_POST['loan_amount'] ?? '');
    $loan_duration      = trim($_POST['loan_duration'] ?? '');
    $loan_reason        = trim($_POST['loan_reason'] ?? '');
    $monthly_income     = trim($_POST['monthly_income'] ?? '');
    $employment_status  = trim($_POST['employment_status'] ?? '');
    $account_number     = trim($_POST['account_number'] ?? '');
    $bank_name          = trim($_POST['bank_name'] ?? '');

    $loan_duration = intval($loan_duration);


    if ($loan_amount === '' || $loan_duration === '' || $loan_reason === '' || $monthly_income === '' || $employment_status === '' || $account_number === '' || $bank_name === '') {
        $errors[] = "All fields are required";
    }

    if ($loan_amount !== '') {
        if (!is_numeric($loan_amount) || $loan_amount <= 0) {
            $errors[] = "Invalid loan amount";
        }
    }



    if (empty($errors)) {


        $interest_rate = 5; // 5% monthly
        $monthly_interest = ($loan_amount * $interest_rate) / 100;
        $total_interest = $monthly_interest * $loan_duration;
        $total_payable = $loan_amount + $total_interest;

        $sql = "INSERT INTO loan_requests 
            (user_id, loan_amount, interest_rate, loan_duration, total_payable, loan_reason, monthly_income, employment_status, account_number, bank_name)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "iddidsdsss",
                $user_id,
                $loan_amount,
                $interest_rate,
                $loan_duration,
                $total_payable,
                $loan_reason,
                $monthly_income,
                $employment_status,
                $account_number,
                $bank_name
            );

            if (mysqli_stmt_execute($stmt)) {
                $success = "Loan applied successfully. Awaiting approval.";
            } else {
                $errors[] = "Loan application failed. Please try again.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "System error. Please contact support.";
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
                                        <h3>Loan</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <!-- <div class="breadcrumbs"><a href="settings-api.html#">Home </a>
                                        <span><i class="fi fi-rr-angle-small-right"></i></span>
                                        <a href="settings-api.html#">Api</a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-xl-12">



                        <div class="row">
                            <div class="col-xxl-12">
                                <h4 class="card-title mb-3">Apply for loan</h4>
                                <div class="card">
                                    <div class="card-body">
                                        <?php if (!empty($errors)) { ?>
                                            <div class="alert alert-danger mt-3">
                                                <?php foreach ($errors as $error) { ?>
                                                    <p><?= $error ?></p>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>

                                        <?php if (!empty($success)) { ?>
                                            <div class="alert alert-success mt-3">
                                                <?= $success ?>
                                            </div>
                                        <?php } ?>

                                        <form action="" method="post">
                                            <div class="row">

                                                <!-- Loan Amount -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Loan Amount</label>
                                                    <input name="loan_amount" type="number" step="0.01" class="form-control" placeholder="Enter amount">
                                                </div>

                                                <!-- Loan Duration -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Loan Duration (Months)</label>
                                                    <select name="loan_duration" class="form-control">
                                                        <option value="">Select duration</option>
                                                        <option value="3">3 Months</option>
                                                        <option value="6">6 Months</option>
                                                        <option value="12">12 Months</option>
                                                        <option value="24">24 Months</option>
                                                    </select>
                                                </div>

                                                <!-- Loan Reason -->
                                                <div class="col-xxl-12 col-xl-12 col-lg-12 mb-3">
                                                    <label class="form-label">Reason for Loan</label>
                                                    <textarea name="loan_reason" class="form-control" rows="3"
                                                        placeholder="Explain why you need this loan"></textarea>
                                                </div>

                                                <!-- Monthly Income -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Monthly Income</label>
                                                    <input name="monthly_income" type="number" step="0.01" class="form-control"
                                                        placeholder="Your monthly income">
                                                </div>

                                                <!-- Employment Status -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Employment Status</label>
                                                    <select name="employment_status" class="form-control">
                                                        <option value="">Select status</option>
                                                        <option value="employed">Employed</option>
                                                        <option value="self-employed">Self Employed</option>
                                                        <option value="business">Business Owner</option>
                                                        <option value="student">Student</option>
                                                        <option value="unemployed">Unemployed</option>
                                                    </select>
                                                </div>

                                                <!-- Bank Account Number -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Bank Account Number</label>
                                                    <input name="account_number" type="text" class="form-control"
                                                        placeholder="Your bank account number">
                                                </div>

                                                <!-- Bank Name -->
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-3">
                                                    <label class="form-label">Bank Name</label>
                                                    <input name="bank_name" type="text" class="form-control"
                                                        placeholder="Your bank name">
                                                </div>

                                            </div>

                                            <div class="mt-3">
                                                <button type="submit" class="btn btn-primary mr-2">Apply for Loan</button>
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