<?php
include("../server/connection.php");

if (!isset($_SESSION['user_id'])) {
     header("location: {$domain}/auth/sign_in/");
}

$user_id = (int) $_SESSION['user_id'];
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deposit'])) {

    $type   = trim($_POST['type'] ?? "");
    $amount = trim($_POST['amount'] ?? "");



    if (empty($type)) {
        $errors[] = "Deposit type is required.";
    } elseif (empty($amount)) {
        $errors[] = "amount is required.";
    }

    if (empty($errors)) {

        $sql = "INSERT INTO deposits (user_id,  type_id, amount)
                VALUES (?, ?,  ?)";

        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "isd", $user_id, $type, $amount);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Deposit submitted successfully. Awaiting confirmation.";
        } else {
            $errors[] = "Deposit failed. Please try again.";
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
    <title><?= $sitename ?> | Deposit </title>
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
                                        <h3>Deposit</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                     <a href="./deposits_history/"><button class="btn btn-primary mr-2">View Deposit History</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-xl-12">

                        <div class="row">

                            <div class="row g-4">

                                <!-- Error-->
                                <div class="col-xxl-6 col-xl-6 col-lg-6">
                                    <?php if (!empty($errors)): ?>
                                        <div class="alert alert-danger">
                                            <?php foreach ($errors as $error): ?>
                                                <p><?= htmlspecialchars($error) ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($success): ?>
                                        <div class="alert alert-success">
                                            <?= htmlspecialchars($success) ?>
                                        </div>
                                    <?php endif; ?>



                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Select Deposit Method</h4>
                                        </div>
                                        <div class="card-body">
                                            <form method="post" id="depositForm">

                                              <?php
                                              $sql_type = "SELECT id,  type FROM payment_account"; 
                                              $stm_query = mysqli_query($connection, $sql_type);
                                            

                                              ?>

                                                <div class="mb-3">
                                                    <label class="form-label">Type</label>
                                                    <select name="type" class="form-select" id="type">
                                                        <option disabled selected hidden  value="">Select Type</option>
                                                        <?php while($result = mysqli_fetch_assoc($stm_query)):  ?>
                                                        <option value="<?= htmlspecialchars($result['id']) ?>"><?= htmlspecialchars($result['type']) ?></option>
                                                          <?php endwhile; ?>
                                                    </select>
                                                </div>


                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT SIDE -->
                                <div class="col-xxl-6 col-xl-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h4 class="card-title">Payment Address / Account Details</h4>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="copyWallet()">Copy</button>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Crypto Type:</strong> <span id="cryptoType"> testing</span></p>
                                            <p id="walletAddress" class="text-break text-muted"><strong>Wallet Address:</strong> testing</p>
                                            <!-- <p id="walletAddress" class="text-break text-muted"></p> -->
                                        </div>
                                    </div>
                                </div>

                                <!-- BOTTOM -->
                                <div class="col-xxl-6 col-xl-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Submit Payment</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Amount Sent</label>
                                                <input name="amount" type="number" class="form-control" placeholder="Enter amount">
                                            </div>
                                            <button type="submit" name="deposit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>

                                </form>

                            </div>


                        </div>
                    </div>
                </div>

                <!-- deposite js temporal -->

                <script>
                    const wallets = {
                        USDT: "TX8K9xKJdsj2390dsUSDTexample",
                        BTC: "bc1qexamplebtcaddress123",
                        POLYGON: "0x2AC3229c7BE5A1bD7F4062d9283BC89Cb8600c5e"
                    };

                    const method = document.getElementById("method");
                    const type = document.getElementById("type");
                    const walletAddress = document.getElementById("walletAddress");
                    const cryptoType = document.getElementById("cryptoType");

                    function updateWallet() {
                        if (method.value === "wallet" && wallets[type.value]) {
                            walletAddress.textContent = wallets[type.value];
                            cryptoType.textContent = type.value;
                        } else {
                            walletAddress.textContent = "---";
                            cryptoType.textContent = "---";
                        }
                    }

                    method.addEventListener("change", updateWallet);
                    type.addEventListener("change", updateWallet);

                    function copyWallet() {
                        if (walletAddress.textContent !== "---") {
                            navigator.clipboard.writeText(walletAddress.textContent);
                            alert("Wallet address copied!");
                        }
                    }
                </script>


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