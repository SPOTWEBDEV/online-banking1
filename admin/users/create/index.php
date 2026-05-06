<?php
include("../../../server/connection.php");
include("../../../server/auth/admin.php");


$success = "";
$errors = [];

$fullname = "";
$email = "";
$date_of_birth = "";
$country = "";
$ssn = "";


/* =========================
   GENERATE UNIQUE NUMBERS
========================= */

function generateUniqueCardNumber($connection)
{
    while (true) {
        $card = strval(random_int(1, 9));
        for ($i = 0; $i < 15; $i++) {
            $card .= random_int(0, 9);
        }

        $sql = "SELECT id FROM users WHERE virtual_card_number = ? LIMIT 1";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "s", $card);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) === 0) {
            mysqli_stmt_close($stmt);
            return $card;
        }

        mysqli_stmt_close($stmt);
    }
}

function generateUniqueAccountNumber($connection)
{
    while (true) {
        $account = strval(random_int(1, 9));
        for ($i = 0; $i < 11; $i++) {
            $account .= random_int(0, 9);
        }

        $sql = "SELECT id FROM users WHERE accountnumber = ? LIMIT 1";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "s", $account);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) === 0) {
            mysqli_stmt_close($stmt);
            return $account;
        }

        mysqli_stmt_close($stmt);
    }
}


/* =========================
   FORM SUBMIT
========================= */

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $fullname = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $country = $_POST['country'] ?? '';
    $ssn = $_POST['ssn'] ?? '';
    $accept_terms = $_POST['acceptTerms'] ?? '';

    $hasError = false;

    /* =========================
       VALIDATION
    ========================= */

    if (empty($fullname)) {
        $errors[] = "Full name is required";
        $hasError = true;
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
        $hasError = true;
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
        $hasError = true;
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
        $hasError = true;
    }

    if (empty($date_of_birth)) {
        $errors[] = "Date of birth is required";
        $hasError = true;
    } else {
        $dob = new DateTime($date_of_birth);
        $age = (new DateTime())->diff($dob)->y;

        if ($age < 18) {
            $errors[] = "User must be at least 18 years old";
            $hasError = true;
        }
    }

    if (empty($country)) {
        $errors[] = "Country is required";
        $hasError = true;
    }

    if (empty($accept_terms)) {
        $errors[] = "You must accept terms";
        $hasError = true;
    }

    /* =========================
       CHECK EMAIL EXISTS
    ========================= */

    $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Email already exists";
        $hasError = true;
    }
    mysqli_stmt_close($stmt);


    /* =========================
       IMAGE UPLOAD
    ========================= */

    $upload_path = null;

    if (!$hasError && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {

        $file = $_FILES['profile_image'];

        if ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = "Image must be less than 2MB";
            $hasError = true;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png'
        ];

        if (!isset($allowed[$mimeType])) {
            $errors[] = "Only JPG and PNG allowed";
            $hasError = true;
        }

        if (!$hasError) {
            $ext = $allowed[$mimeType];
            $filename = uniqid('profile_', true) . "." . $ext;

            $uploadDir =  "../../../images/avatar/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destination = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $upload_path = "images/avatar/" . $filename;
            } else {
                $errors[] = "Failed to upload image";
                $hasError = true;
            }
        }
    }


    /* =========================
       INSERT USER
    ========================= */

    if (!$hasError) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $accountNumber = generateUniqueAccountNumber($connection);
        $cardNumber = generateUniqueCardNumber($connection);
        $expiry = (new DateTime())->modify('+4 years')->format('Y-m-d');

        $sql = "INSERT INTO users (
            fullname, email, password,
            accountnumber, virtual_card_number,
            virtual_card_expiring_date,
            date_of_birth, country, ssn, user_profile
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssss",
            $fullname,
            $email,
            $password,
            $accountNumber,
            $cardNumber,
            $expiry,
            $date_of_birth,
            $country,
            $ssn,
            $upload_path
        );

        if (mysqli_stmt_execute($stmt)) {
            $success = "User registered successfully!";
            echo "<script>
                setTimeout(() => {
                    window.location.href = '../';
                }, 1500);
            </script>";
        } else {
            $errors[] = "Database error";
        }

        mysqli_stmt_close($stmt);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $sitename ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>/vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">
    <div id="main-wrapper">
        <?php include("../../include/nav.php") ?>
        <?php include("../../include/sidenav.php") ?>
        <div class="content-body">
            <div class="verification">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-xl-4">
                                        <div class="page-title-content">
                                            <h3>All Users</h3>
                                            <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center h-100 align-items-center">
                        <div class="col-12">

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Register User</h4>
                                </div>
                                <?php if (!empty($success)) { ?>
                                    <div class="alert alert-success"><?= $success ?></div>
                                <?php } ?>

                                <?php if (!empty($errors)) { ?>
                                    <div class="alert alert-danger">
                                        <ul style="margin-bottom:0;">
                                            <?php foreach ($errors as $error) { ?>
                                                <li><?= htmlspecialchars($error) ?></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>

                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data">

                                        <!-- FULL NAME -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label>Full Name</label>
                                                <input type="text" name="fullName" class="form-control" placeholder="John Doe">
                                            </div>
                                        </div>

                                        <!-- EMAIL -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" placeholder="example@mail.com">
                                            </div>
                                        </div>

                                        <!-- PASSWORD -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-6">
                                                <label>Password</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>

                                            <div class="mb-3 col-xl-6">
                                                <label>Confirm Password</label>
                                                <input type="password" name="confirmPassword" class="form-control">
                                            </div>
                                        </div>

                                        <!-- DATE OF BIRTH -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-6">
                                                <label>Date of Birth</label>
                                                <input type="date" name="date_of_birth" class="form-control">
                                            </div>

                                            <!-- COUNTRY -->
                                            <div class="mb-3 col-xl-6">
                                                <label>Country</label>
                                                <input type="text" name="country" class="form-control" placeholder="Nigeria">
                                            </div>
                                        </div>

                                        <!-- SSN -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label>SSN</label>
                                                <input type="text" name="ssn" class="form-control" placeholder="Enter SSN">
                                            </div>
                                        </div>

                                        <!-- PROFILE IMAGE UPLOAD -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <label>Profile Image (JPG/PNG, max 2MB)</label>
                                                <input type="file" name="profile_image" class="form-control">
                                            </div>
                                        </div>

                                        <!-- TERMS -->
                                        <div class="form-row">
                                            <div class="mb-3 col-xl-12">
                                                <div class="form-check">
                                                    <input type="checkbox" name="acceptTerms" class="form-check-input" value="1">
                                                    <label class="form-check-label">
                                                        I accept the terms and conditions
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SUBMIT -->
                                        <div class="col-12 mt-4">
                                            <button type="submit" class="btn btn-success w-100">
                                                Register User
                                            </button>
                                        </div>

                                    </form>
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
                                <a href="add-bank.html#"><?php echo $sitename ?></a> I All Rights Reserved
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="footer-social">
                            <ul>
                                <li><a href="add-bank.html#"><i class="fi fi-brands-facebook"></i></a></li>
                                <li><a href="add-bank.html#"><i class="fi fi-brands-twitter"></i></a></li>
                                <li><a href="add-bank.html#"><i class="fi fi-brands-linkedin"></i></a></li>
                                <li><a href="add-bank.html#"><i class="fi fi-brands-youtube"></i></a></li>
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