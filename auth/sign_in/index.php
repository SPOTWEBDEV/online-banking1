<?php
include("../../server/connection.php");

$success = "";
$accountnumberErr = "";
$passwordErr = "";


$accountnumber = "";

$showSuspendedModal = false;

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $accountnumber    = $_POST['accountnumber'];
    $password = $_POST['password'];
    $hasError = false;

    if (empty($accountnumber)) {
        $accountnumberErr = "account number is required";
        $hasError = true;
    }


    if (empty($password)) {
        $passwordErr = "Password is required";
        $hasError = true;
    }


    if (!$hasError) {
        $sql = "SELECT id, fullname, accountnumber, password, is_approved , status , 	suspendedMessage FROM users WHERE accountnumber = ?";
        $stmt = mysqli_prepare($connection, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $accountnumber);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                mysqli_stmt_bind_result($stmt, $id, $fullname, $db_accountnumber, $db_password, $is_approved, $status, $suspendedMessage);
                mysqli_stmt_fetch($stmt);

                if ($password != $db_password) {
                    $passwordErr = "Invalid credentials";
                } elseif ($is_approved == 0) {
                    $accountnumberErr = "Your account has not been verified yet. Please check your email inbox.";
                } elseif ($status === 'suspended') {
                    $accountnumberErr = $suspendedMessage;
                    $showSuspendedModal = true;
                } else {
                    $success = "Login successful. Welcome, $fullname!";

                    $_SESSION['user_id'] = $id;
                    $_SESSION['fullname'] = $fullname;

                    echo "
            <script>
                setTimeout(() => {
                    window.location.href = '../set_transaction_pin/';
                }, 1000);
            </script>";
                }
            } else {
                $accountnumberErr = "No account found with this account number";
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
    <title><?= htmlspecialchars($sitename) ?> | Sign In</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <link rel="stylesheet" href="<?php echo $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body class="dashboard">

    <div class="authincation">
        <div class="container">
            <div class="row justify-content-center align-items-center g-0">
                <div class="col-xl-8">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <div class="welcome-content">
                                <div class="welcome-title">
                                    <div class="mini-logo">
                                        <a href="index.html"><img src="<?php echo $domain ?>/images/logo-white.png" alt="" width="30" /></a>
                                    </div>
                                    <h3>Welcome to <?= htmlspecialchars($sitename) ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="auth-form">
                                <h4>Sign In</h4>

                                <?php if (!empty($success)) { ?>
                                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                                <?php } ?>

                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Account Number</label>
                                            <input name="accountnumber" type="text" class="form-control" value="<?= htmlspecialchars($accountnumber) ?>" />
                                            <small style="color:red"><?= $accountnumberErr ?></small>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Password</label>
                                            <input name="password" type="password" class="form-control" />
                                            <small style="color:red"><?= $passwordErr ?></small>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input name="remember" id="remember" type="checkbox" class="form-check-input" />
                                                <label class="form-check-label" for="remember">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="col-6 text-end"><a href="reset.html">Forgot Password?</a></div>
                                    </div>
                                    <div class="mt-3 d-grid gap-2">
                                        <button type="submit" class="btn btn-primary me-8 text-white">Sign In</button>
                                    </div>
                                </form>
                                <p class="mt-3 mb-0 undefined">Don't have an account?<a class="text-primary" href="../sign_up/"> Sign up</a></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suspended Account Modal -->
                <div class="modal fade" id="suspendedModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div style="background:#2F3A53" class="modal-header text-white">
                                <h5 style="color: white;">Account Suspended</h5>
                            </div>

                            <div class="modal-body text-center">
                                <p style="font-size:16px;">
                                    Your account has been temporarily suspended due to security concerns.
                                </p>

                                <div class="alert alert-warning">
                                    <strong>Reason:</strong><br>
                                    <?= htmlspecialchars($suspendedMessage) ?>
                                </div>

                                <p>Please contact support to resolve this issue:</p>

                                <div style="line-height: 1.8;">
                                    <i class="bi bi-envelope"></i> Email: <?php echo $siteemail ?> <br>
                                    <i class="bi bi-telephone"></i> Phone: <?php echo $sitephone ?> <br>
                                    <i class="bi bi-chat-dots"></i> Live Chat: Live Chat is available at the bottom of the screen.
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($showSuspendedModal): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var suspendedModal = new bootstrap.Modal(document.getElementById('suspendedModal'));
                suspendedModal.show();
            });
        </script>
    <?php endif; ?>

    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>/js/scripts.js"></script>
    <script src="//code.jivosite.com/widget/WZLgVBvOU2" async></script>
</body>

</html>