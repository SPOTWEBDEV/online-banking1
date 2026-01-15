<?php
include("./server/connection.php");


$success = "";
$fullnameErr = "";
$emailErr = "";
$passwordErr = "";
$confirmPasswordErr = "";
$termsErr = "";


$fullname = "";
$email = "";
$accept_terms = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $fullname = $_POST['fullName'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $accept_terms = isset($_POST['acceptTerms']) ? $_POST['acceptTerms'] : "";
    $confirmPassword = $_POST['confirmPassword'];

    $hasError = false;

 
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

    // Confirm password
    if ($password !== $confirmPassword) {
        $confirmPasswordErr = "Passwords do not match";
        $hasError = true;
    }

    // Terms acceptance
    if (empty($accept_terms)) {
        $termsErr = "Please accept terms and conditions";
        $hasError = true;
    }

  
    if (!$hasError) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($connection, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $fullname, $email, $hashedPassword);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $success = "User registered successfully";
               
                $fullname = $email = "";
                $accept_terms = "";
                 echo  "<script>window.location.href = './signin'</script>";
            } else {
                $success = "Registration failed";
            }

            mysqli_stmt_close($stmt);
        } else {
            $success = "Database error: " . mysqli_error($connection);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=  $sitename ?>| Sign Up </title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">
    <div id="preloader" class="preloader-wrapper">
        <div class="loader">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="authincation">
        <div class="container">
            <div class="row justify-content-center align-items-center g-0">
                <div class="col-xl-8">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <div class="welcome-content">
                                <div class="welcome-title">
                                    <div class="mini-logo">
                                        <a href="index.html"><img src="images/logo-white.png" alt="" width="30" /></a>
                                    </div>
                                    <h3>Welcome to Zentra Bank</h3>
                                </div>
                                <div class="privacy-social">
                                    <div class="privacy-link"><a href="signup.html#">Have an issue with 2-factor authentication?</a><br /><a href="signup.html#">Privacy Policy</a></div>
                                    <div class="intro-social">
                                        <ul>
                                            <li><a href="signup.html#"><i class="fi fi-brands-facebook"></i></a></li>
                                            <li><a href="signup.html#"><i class="fi fi-brands-twitter-alt"></i></a></li>
                                            <li><a href="signup.html#"><i class="fi fi-brands-linkedin"></i></a></li>
                                            <li><a href="signup.html#"><i class="fi fi-brands-pinterest"></i></a></li>
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
                                                <label class="form-check-label" for="acceptTerms">I certify that I am 18 years of age or older, and agree to the <a href="signup.html#" class="text-primary">User Agreement</a> and <a href="signup.html#" class="text-primary">Privacy Policy</a>.</label>
                                                <br><small style="color:red"><?= $termsErr ?></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 d-grid gap-2">
                                        <button type="submit" class="btn btn-primary me-8 text-white">Sign Up</button>
                                    </div>
                                </form>
                                <p class="mt-3 mb-0 undefined">Already have an account?<a class="text-primary" href="signin.php"> Sign In</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>