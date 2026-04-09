<?php
include("../../server/connection.php");
include("../../mailer/index.php"); 



$success = "";
$fullnameErr = "";
$emailErr = "";
$passwordErr = "";
$confirmPasswordErr = "";
$termsErr = "";
$exist_err = "";

$fullname = "";
$email = "";
$accept_terms = "";

$dobErr = "";
$countryErr = "";
$ssnErr = "";

$date_of_birth = "";
$country = "";
$ssn = "";


function generateUniqueCardNumber($connection) {
    while (true) {
        // 16 digits, first digit 1-9 so it doesn't start with 0
        $card = strval(random_int(1, 9));
        for ($i = 0; $i < 15; $i++) {
            $card .= strval(random_int(0, 9));
        }

        $checkSql = "SELECT id FROM users WHERE virtual_card_number = ? LIMIT 1";
        $checkStmt = mysqli_prepare($connection, $checkSql);
        if (!$checkStmt) {
            // if prepare fails, stop immediately
            throw new Exception("server error: ");
        }

        mysqli_stmt_bind_param($checkStmt, "s", $card);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        $exists = (mysqli_stmt_num_rows($checkStmt) > 0);
        mysqli_stmt_close($checkStmt);

        if (!$exists) {
            return $card;
        }
    }
}
function generateUniqueAccountNumber($connection) {
    while (true) {
        // 12 digits, first digit 1–9
        $account = strval(random_int(1, 9));
        for ($i = 0; $i < 11; $i++) {
            $account .= strval(random_int(0, 9));
        }

        $sql = "SELECT id FROM users WHERE accountnumber = ? LIMIT 1";
        $stmt = mysqli_prepare($connection, $sql);
        if (!$stmt) {
            throw new Exception("server error");
        }

        mysqli_stmt_bind_param($stmt, "s", $account);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        $exists = mysqli_stmt_num_rows($stmt) > 0;
        mysqli_stmt_close($stmt);

        if (!$exists) {
            return $account;
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $fullname = $_POST['fullName'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $accept_terms = isset($_POST['acceptTerms']) ? $_POST['acceptTerms'] : "";
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
$country = $_POST['country'] ?? '';
$ssn = $_POST['ssn'] ?? '';


    $hasError = false;
    
    // Date of birth
if (empty($date_of_birth)) {
    $dobErr = "Date of birth is required";
    $hasError = true;
} else {
    $dob = new DateTime($date_of_birth);
    $today = new DateTime();
    $age = $today->diff($dob)->y;

    if ($age < 18) {
        $dobErr = "You must be at least 18 years old";
        $hasError = true;
    }
}

// Country
if (empty($country)) {
    $countryErr = "Country is required";
    $hasError = true;
}

// SSN
if (empty($ssn)) {
    $ssnErr = "SSN is required";
    $hasError = true;
}


    // VALIDATION
    if (empty($fullname)) {
        $fullnameErr = "Full name is required";
        $hasError = true;
    }

    if (empty($email)) {
        $emailErr = "Email is required";
        $hasError = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        $hasError = true;
    }

    if (empty($password)) {
        $passwordErr = "Password is required";
        $hasError = true;
    } elseif (strlen($password) < 6) {
        $passwordErr = "Password must be at least 6 characters";
        $hasError = true;
    }

    if ($password !== $confirmPassword) {
        $confirmPasswordErr = "Passwords do not match";
        $hasError = true;
    }

    if (empty($accept_terms)) {
        $termsErr = "Please accept terms and conditions";
        $hasError = true;
    }

    // EMAIL EXISTS CHECK
    $sql_exist = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt_exist = mysqli_prepare($connection, $sql_exist);
    mysqli_stmt_bind_param($stmt_exist, "s", $email);
    mysqli_stmt_execute($stmt_exist);
    mysqli_stmt_store_result($stmt_exist);

    if (mysqli_stmt_num_rows($stmt_exist) > 0) {
        $exist_err = "This email is already registered!";
        $hasError = true;
    }
    mysqli_stmt_close($stmt_exist);

    // If NO ERRORS → insert user
    if (!$hasError) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 1) Generate unique card number
        try {
    $virtualCardNumber = generateUniqueCardNumber($connection);
    $accountNumber = generateUniqueAccountNumber($connection);
} catch (Exception $e) {
    $success = $e->getMessage();
    $hasError = true;
}


        $virtualCardExpiry = (new DateTime())->modify('+4 years')->format('Y-m-d');

        if (!$hasError) {
             $token = bin2hex(random_bytes(32));
             
            $sql = "INSERT INTO users (
    fullname,
    email,
    password,
    accountnumber,
    virtual_card_number,
    virtual_card_expiring_date,
    verification_token,
    date_of_birth,
    country,
    ssn
)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($connection, $sql);

            if ($stmt) {
               mysqli_stmt_bind_param(
    $stmt,
    "ssssssssss",
    $fullname,
    $email,
    $hashedPassword,
    $accountNumber,
    $virtualCardNumber,
    $virtualCardExpiry,
    $token,
    $date_of_birth,
    $country,
    $ssn
);



                mysqli_stmt_execute($stmt);

                if (mysqli_stmt_affected_rows($stmt) > 0) {
                  $verify_link = $domain. "/auth/verify/?token=" . $token;
                
                    $body = "
                        <html>
                        <body style='margin: 0; padding: 0; font-family: Roboto, sans-serif; background: #131722;'>
                        <section style='width: 100%; background-color: #f1f2f3; color: #333;'>
                        <div style='width: 100%; max-width: 600px; margin: 0 auto;'>
                        <div style='padding: 20px; background-color: #131722; text-align: center;'>
                        <h2 style='color: #fff; font-size: 24px;'>Welcome aboard, $fullname!</h2>
                        </div>
                        <div style='padding: 20px; background: #fff; border-radius: 0 0 8px 8px;'>
                        <p>Dear $fullname</p>
                        <p>Please verify your account by clicking the button below:</p>
                        <a href='$verify_link'
				       style='background:#00c853; color:#fff; padding:10px 20px; border-radius:6px; text-decoration:none;'>
				       Verify My Account
				    </a>
				
				    <p>If the button doesn't work, use this link:</p>
				    <p>$verify_link</p>
                        <p>Thank you for joining Zentra Bank, your trusted platform for secure online banking and smart investment solutions. We’re excited to welcome you to a digital banking experience designed for convenience, growth, and peace of mind.</p>
                        <p>By choosing Zentra Bank, you gain access to seamless online banking tools alongside tailored investment opportunities. Our experienced team is dedicated to helping you manage your finances efficiently while working toward your long-term financial goals.</p>
                        <p>To get started, simply fund your account and explore our range of online banking features and investment plans. Enjoy secure transactions, real-time account access, and opportunities to grow your wealth—all in one place.</p>
                        <p>For any inquiries or assistance, feel free to reach out to our support team at <a href='mailto:$siteemail'>$siteemail</a>.</p>
                        <p>Best regards,</p>
                        <p>The $sitename  Team</p>
                        
                        </div>
                        <div style='text-align: center; color: #666; margin-top: 20px; font-size: 12px;'> 
                        &copy; 2020 $sitename  . All rights reserved. 
                        </div>
                        </div>
                        </section>
                        </body>
                        </html>";
                
                    $to = $email;
                    $subj = "Welcome to $sitename  ! ";
                    $result = smtpmailer($to, $subj, $body);
                    
                    $account_body = "
<html>
<body style='font-family:Arial;background:#f4f6f8;padding:20px;'>
<div style='max-width:600px;margin:auto;background:#fff;border-radius:8px;overflow:hidden;'>

<div style='background:#131722;padding:20px;text-align:center;'>
<h2 style='color:#fff;'>Your Account Details</h2>
</div>

<div style='padding:20px;'>
<p>Hello <strong>$fullname</strong>,</p>

<table width='100%' cellpadding='10' cellspacing='0' style='border-collapse:collapse;'>

<tr>
<td style='border:1px solid #ddd;background:#f9f9f9;'><strong>Account Name</strong></td>
<td style='border:1px solid #ddd;'>$fullname</td>
</tr>

<tr>
<td style='border:1px solid #ddd;background:#f9f9f9;'><strong>Account Number</strong></td>
<td style='border:1px solid #ddd;'>$accountNumber</td>
</tr>

<tr>
<td style='border:1px solid #ddd;background:#f9f9f9;'><strong>Account Type</strong></td>
<td style='border:1px solid #ddd;'>Current Account</td>
</tr>

<tr>
<td style='border:1px solid #ddd;background:#f9f9f9;'><strong>Currency</strong></td>
<td style='border:1px solid #ddd;'>USD (U.S. Dollar)</td>
</tr>

<tr>
<td style='border:1px solid #ddd;background:#f9f9f9;'><strong>Status</strong></td>
<td style='border:1px solid #ddd;color:green;'><strong>Active</strong></td>
</tr>

</table>

<p style='margin-top:20px;'>
You can now log in and start banking with <strong>$sitename</strong>.
</p>

<p>
Need help? Contact us at <a href='mailto:$siteemail'>$siteemail</a>
</p>

<p>— The $sitename Team</p>
</div>

<div style='text-align:center;font-size:12px;color:#666;padding:10px;'>
&copy; " . date('Y') . " $sitename. All rights reserved.
</div>

</div>
</body>
</html>
";

smtpmailer($email, "Your $sitename Account Details", $account_body);

                    
                    
                    
                    $success = "User registered successfully";

                    // clear form values
                    $fullname = "";
                    $email = "";
                    $accept_terms = "";

                    echo "<script>
                        setTimeout(() => {
                            window.location.href = '../sign_in/'
                        }, 2500);
                    </script>";
                } else {
                    $success = "Registration failed";
                }

                mysqli_stmt_close($stmt);
            } else {
                $success = "Database error: " . mysqli_error($connection);
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $sitename ?>| Sign Up </title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <link rel="stylesheet" href="<?php echo $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>/vendor/toastr/toastr.min.css">
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
                                    <h3>Welcome to Zentra Bank</h3>
                                </div>
                                <div class="privacy-social">
                                    <div class="privacy-link"><a href="#">Have an issue with 2-factor authentication?</a><br /><a href="#">Privacy Policy</a></div>
                                    <div class="intro-social">
                                        <ul>
                                            <li><a href="#"><i class="fi fi-brands-facebook"></i></a></li>
                                            <li><a href="#"><i class="fi fi-brands-twitter-alt"></i></a></li>
                                            <li><a href="#"><i class="fi fi-brands-linkedin"></i></a></li>
                                            <li><a href="#"><i class="fi fi-brands-pinterest"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="auth-form">
                                <h4>Sign Up</h4>

                                <?php if (!empty($success)) { ?>
                                    <div class="alert alert-success"><?= $success ?></div>
                                <?php } ?>

                                <?php if (!empty($exist_err)) { ?>
                                    <div class="alert alert-danger"><?= $exist_err ?></div>
                                <?php } ?>

                                <form action="" method="POST">
                                    <div class="row">

                                        <div class="col-12 mb-3">
                                            <label class="form-label">Full Name</label>
                                            <input name="fullName" type="text" class="form-control" value="<?= htmlspecialchars($fullname) ?>" />
                                            <small style="color:red"><?= $fullnameErr ?></small>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label">Email</label>
                                            <input name="email" type="text" class="form-control" value="<?= htmlspecialchars($email) ?>" />
                                            <small style="color:red"><?= $emailErr ?></small>
                                        </div>
                                        
                                        <div class="col-12 mb-3">
    <label class="form-label">Date of Birth</label>
    <input type="date" name="date_of_birth" class="form-control"
           value="<?= htmlspecialchars($date_of_birth) ?>">
    <small style="color:red"><?= $dobErr ?></small>
</div>
<div class="col-12 mb-3">
    <label class="form-label">SSN</label>
    <input type="text" name="ssn" class="form-control" maxlength="10">
    <small style="color:red"><?= $ssnErr ?></small>
</div>

<div class="col-12 mb-3">
    <label class="form-label">Country</label>
    <select name="country" class="form-control">
        <option value="">-- Select Country --</option>

        <?php
        $countries = [
            "Afghanistan","Albania","Algeria","Andorra","Angola",
            "Argentina","Armenia","Australia","Austria","Azerbaijan",
            "Bahamas","Bahrain","Bangladesh","Barbados","Belgium",
            "Belize","Benin","Bolivia","Brazil","Bulgaria",
            "Cambodia","Cameroon","Canada","Chile","China","Colombia",
            "Costa Rica","Croatia","Cuba","Cyprus","Czech Republic",
            "Denmark","Dominican Republic",
            "Ecuador","Egypt","Estonia","Ethiopia",
            "Finland","France",
            "Georgia","Germany","Ghana","Greece",
            "Haiti","Honduras","Hong Kong","Hungary",
            "Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy",
            "Jamaica","Japan","Jordan",
            "Kazakhstan","Kenya","Kuwait",
            "Latvia","Lebanon","Liberia","Lithuania","Luxembourg",
            "Malaysia","Maldives","Mexico","Monaco","Morocco",
            "Nepal","Netherlands","New Zealand","Nigeria","Norway",
            "Oman",
            "Pakistan","Panama","Peru","Philippines","Poland","Portugal",
            "Qatar",
            "Romania","Russia","Rwanda",
            "Saudi Arabia","Senegal","Singapore","Slovakia","Slovenia","South Africa","South Korea","Spain","Sri Lanka","Sweden","Switzerland",
            "Thailand","Tunisia","Turkey",
            "Uganda","Ukraine","United Arab Emirates","United Kingdom","United States",
            "Uruguay",
            "Venezuela","Vietnam",
            "Yemen",
            "Zambia","Zimbabwe"
        ];

        foreach ($countries as $c) {
            $selected = ($country === $c) ? 'selected' : '';
            echo "<option value=\"$c\" $selected>$c</option>";
        }
        ?>
    </select>
    <small style="color:red"><?= $countryErr ?></small>
</div>




                                        <div class="col-12 mb-3">
                                            <label class="form-label">Password</label>
                                            <input name="password" type="password" class="form-control" />
                                            <small style="color:red"><?= $passwordErr ?></small>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label">Confirm Password</label>
                                            <input name="confirmPassword" type="password" class="form-control" />
                                            <small style="color:red"><?= $confirmPasswordErr ?></small>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input name="acceptTerms" type="checkbox" class="form-check-input" id="acceptTerms" <?= !empty($accept_terms) ? 'checked' : '' ?> />
                                                <label class="form-check-label" for="acceptTerms">I certify that I am 18 years of age or older, and agree to the <a href="#" class="text-primary">User Agreement</a> and <a href="#" class="text-primary">Privacy Policy</a>.</label>
                                                <br><small style="color:red"><?= $termsErr ?></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 d-grid gap-2">
                                        <button type="submit" class="btn btn-primary me-8 text-white">Sign Up</button>
                                    </div>
                                </form>

                                <p class="mt-3 mb-0">Already have an account?<a class="text-primary" href="../sign_in/"> Sign In</a></p>

                            </div>
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
