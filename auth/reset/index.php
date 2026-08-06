<?php
include("../../server/connection.php");
include("../../mailer/index.php");

$identifierErr = "";
$otpErr = "";
$passwordErr = "";
$confirmPasswordErr = "";
$success = "";
$identifier = "";

// Reset the flow if user explicitly starts over
if (isset($_POST['start_over'])) {
    unset($_SESSION['reset_user_id'], $_SESSION['reset_email'], $_SESSION['reset_otp'], $_SESSION['reset_otp_expiry'], $_SESSION['reset_attempts'], $_SESSION['reset_stage']);
}

$stage = $_SESSION['reset_stage'] ?? 'identifier';

// ================= STEP 1: REQUEST OTP =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_otp'])) {

    $identifier = trim($_POST['identifier'] ?? '');

    if (empty($identifier)) {
        $identifierErr = "Please enter your email or account number";
    } else {
        $sql = "SELECT id, fullname, email, accountnumber FROM users WHERE email = ? OR accountnumber = ? LIMIT 1";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $identifier, $identifier);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $user_id, $fullname, $user_email, $user_accountnumber);
            mysqli_stmt_fetch($stmt);

            // Generate 6-digit OTP
            $otp = strval(random_int(100000, 999999));

            $_SESSION['reset_user_id']    = $user_id;
            $_SESSION['reset_email']      = $user_email;
            $_SESSION['reset_fullname']   = $fullname;
            $_SESSION['reset_otp']        = $otp;
            $_SESSION['reset_otp_expiry'] = time() + (10 * 60); // 10 minutes
            $_SESSION['reset_attempts']   = 0;
            $_SESSION['reset_stage']      = 'otp';
            $stage = 'otp';

            $otp_body = "
            <html>
            <body style='margin:0;padding:0;font-family:Roboto,sans-serif;background:#131722;'>
            <section style='width:100%;background-color:#f1f2f3;color:#333;'>
            <div style='width:100%;max-width:600px;margin:0 auto;'>
            <div style='padding:20px;background-color:#131722;text-align:center;'>
            <h2 style='color:#fff;font-size:24px;'>Password Reset Request</h2>
            </div>
            <div style='padding:20px;background:#fff;border-radius:0 0 8px 8px;'>
            <p>Dear $fullname,</p>
            <p>We received a request to reset your password. Use the code below to continue:</p>
            <p style='font-size:32px;font-weight:bold;letter-spacing:6px;text-align:center;color:#131722;'>$otp</p>
            <p>This code will expire in 10 minutes. If you did not request a password reset, please ignore this email or contact support.</p>
            <p>Best regards,</p>
            <p>The $sitename Team</p>
            </div>
            <div style='text-align:center;color:#666;margin-top:20px;font-size:12px;'>
            &copy; " . date('Y') . " $sitename. All rights reserved.
            </div>
            </div>
            </section>
            </body>
            </html>";

           $result = smtpmailer($user_email, "Your $sitename Password Reset Code", $otp_body);

            echo $result ? "OTP sent successfully" : "Failed to send OTP. Please try again later.";

            // Mask email for display, e.g. jo***@example.com
            $maskedEmail = preg_replace('/(?<=.{2}).(?=.*@)/', '*', $user_email);
            $success = "An OTP has been sent to $maskedEmail";
        } else {
            $identifierErr = "No account found with that email or account number";
        }

        mysqli_stmt_close($stmt);
    }
}

// ================= STEP 2: VERIFY OTP =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {

    $entered_otp = trim($_POST['otp'] ?? '');

    if (!isset($_SESSION['reset_otp'])) {
        $otpErr = "Session expired. Please start again.";
        $_SESSION['reset_stage'] = 'identifier';
        $stage = 'identifier';
    } elseif ($_SESSION['reset_attempts'] >= 5) {
        $otpErr = "Too many failed attempts. Please start again.";
        unset($_SESSION['reset_otp'], $_SESSION['reset_otp_expiry']);
        $_SESSION['reset_stage'] = 'identifier';
        $stage = 'identifier';
    } elseif (time() > $_SESSION['reset_otp_expiry']) {
        $otpErr = "This OTP has expired. Please request a new one.";
        unset($_SESSION['reset_otp'], $_SESSION['reset_otp_expiry']);
        $_SESSION['reset_stage'] = 'identifier';
        $stage = 'identifier';
    } elseif (empty($entered_otp) || $entered_otp !== $_SESSION['reset_otp']) {
        $_SESSION['reset_attempts']++;
        $otpErr = "Invalid OTP. Please try again.";
        $stage = 'otp';
    } else {
        // OTP correct
        $_SESSION['reset_stage'] = 'password';
        $stage = 'password';
    }
}

// ================= STEP 3: SET NEW PASSWORD =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {

    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_new_password'] ?? '';
    $hasError = false;

    if ($stage !== 'password' || !isset($_SESSION['reset_user_id'])) {
        $passwordErr = "Session expired. Please start again.";
        $_SESSION['reset_stage'] = 'identifier';
        $stage = 'identifier';
        $hasError = true;
    }

    if (!$hasError) {
        if (empty($new_password)) {
            $passwordErr = "Password is required";
            $hasError = true;
        } elseif (strlen($new_password) < 6) {
            $passwordErr = "Password must be at least 6 characters";
            $hasError = true;
        }

        if ($new_password !== $confirm_password) {
            $confirmPasswordErr = "Passwords do not match";
            $hasError = true;
        }
    }

    if (!$hasError) {
        // NOTE: stored in plain text to stay consistent with existing login logic.
        // See recommendation above about switching to password_hash()/password_verify().
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "si", $new_password, $_SESSION['reset_user_id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $success = "Your password has been reset successfully. Redirecting to sign in...";

        // Clear reset session data
        unset($_SESSION['reset_user_id'], $_SESSION['reset_email'], $_SESSION['reset_fullname'], $_SESSION['reset_otp'], $_SESSION['reset_otp_expiry'], $_SESSION['reset_attempts'], $_SESSION['reset_stage']);
        $stage = 'done';

        echo "<script>
            setTimeout(() => {
                window.location.href = '../sign_in/';
            }, 2000);
        </script>";
    } else {
        $stage = 'password';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($sitename) ?> | Forgot Password</title>
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
                                <h4>Forgot Password</h4>

                                <?php if (!empty($success)) { ?>
                                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                                <?php } ?>

                                <?php if ($stage === 'identifier' || $stage === 'done'): ?>

                                    <!-- STEP 1: ENTER EMAIL OR ACCOUNT NUMBER -->
                                    <p class="mb-3 text-muted">Enter your email or account number and we'll send you a one-time code to reset your password.</p>
                                    <form action="" method="POST">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label class="form-label">Email or Account Number</label>
                                                <input name="identifier" type="text" class="form-control" value="<?= htmlspecialchars($identifier) ?>" />
                                                <small style="color:red"><?= $identifierErr ?></small>
                                            </div>
                                        </div>
                                        <div class="mt-3 d-grid gap-2">
                                            <button type="submit" name="request_otp" class="btn btn-primary text-white">Send OTP</button>
                                        </div>
                                    </form>

                                <?php elseif ($stage === 'otp'): ?>

                                    <!-- STEP 2: ENTER OTP -->
                                    <p class="mb-3 text-muted">Enter the 6-digit code sent to your email. It expires in 10 minutes.</p>
                                    <form action="" method="POST">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label class="form-label">OTP Code</label>
                                                <input name="otp" type="text" maxlength="6" inputmode="numeric" pattern="[0-9]*" class="form-control" autocomplete="one-time-code" />
                                                <small style="color:red"><?= $otpErr ?></small>
                                            </div>
                                        </div>
                                        <div class="mt-3 d-grid gap-2">
                                            <button type="submit" name="verify_otp" class="btn btn-primary text-white">Verify Code</button>
                                        </div>
                                    </form>
                                    <form action="" method="POST" class="mt-2">
                                        <button type="submit" name="start_over" class="btn btn-link p-0">Start over / use a different account</button>
                                    </form>

                                <?php elseif ($stage === 'password'): ?>

                                    <p class="text-muted">Code verified. Set your new password below.</p>

                                <?php endif; ?>

                                <p class="mt-3 mb-0">Remembered your password?<a class="text-primary" href="../sign_in/"> Sign In</a></p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STEP 3 MODAL: NEW PASSWORD -->
    <div class="modal fade" id="newPasswordModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div style="background:#2F3A53" class="modal-header text-white">
                    <h5 style="color:white;">Set New Password</h5>
                </div>

                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input name="new_password" type="password" class="form-control" />
                            <small style="color:red"><?= $passwordErr ?></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input name="confirm_new_password" type="password" class="form-control" />
                            <small style="color:red"><?= $confirmPasswordErr ?></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="reset_password" class="btn btn-primary text-white">Reset Password</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <?php if ($stage === 'password'): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var newPasswordModal = new bootstrap.Modal(document.getElementById('newPasswordModal'));
                newPasswordModal.show();
            });
        </script>
    <?php endif; ?>

    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>/js/scripts.js"></script>
</body>

</html>