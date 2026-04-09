<?php
include("../server/connection.php");


$id = (int) $_GET['id'];
$type = $_GET['type'];
$user_id = $_SESSION['user_id'];

if ($type == 'transfer') {

    $sql = "
        SELECT 
            bank_transfers.*,
            users.fullname
        FROM bank_transfers
        INNER JOIN users ON users.id = bank_transfers.user_id
        WHERE bank_transfers.id = '$id'
    ";

} elseif ($type == 'withdrawal') {

    $sql = "
        SELECT 
            withdrawals.*,
            users.fullname
        FROM withdrawals
        INNER JOIN users ON users.id = withdrawals.user_id
        WHERE withdrawals.id = '$id'
    ";

} elseif ($type == 'deposit') {

    $sql = "
        SELECT 
            deposits.*,
            users.fullname,
            payment_account.type AS payment_type,
            payment_account.bank_name,
            payment_account.account_number,
            payment_account.routing_number,
            payment_account.fullname AS account_fullname,
            payment_account.network,
            payment_account.wallet_address,
            payment_account.label
        FROM deposits
        INNER JOIN users ON users.id = deposits.user_id
        LEFT JOIN payment_account ON payment_account.id = deposits.type_id
        WHERE deposits.id = '$id'
    ";

} else {
    die("Invalid transaction type");
}

$query = $connection->query($sql);



?>


<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | Transfer-History </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>/vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">

    <div id="main-wrapper">
        <!-- header -->
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
                                        <h3>Transfer Details</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?></p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="breadcrumbs"><a href="<?php echo $domain  ?>/dashboard/">Home</a>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <?php if ($query->num_rows > 0) {
                        $transfer = $query->fetch_assoc();
                       
                    ?>
                        <div class="col-12">

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        Transaction Details for <?php echo $transfer['fullname']; ?>
                                    </h4>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody style="text-transform:capitalize">

<tr>
    <td><span class="text-primary">Amounts</span></td>
    <td><span class="text-primary">$<?php echo number_format($transfer['amount'], 2); ?></span></td>
</tr>

<tr>
    <td>Status:</td>
    <td>
        <span class="badge 
        <?php
        echo ($transfer['status'] == 'pending') ? 'bg-warning' : 
             (($transfer['status'] == 'completed' || $transfer['status'] == 'success' || $transfer['status'] == 'approved') ? 'bg-success' : 'bg-danger');
        ?>">
            <?php echo $transfer['status'] == 'completed' || $transfer['status'] == 'success' || $transfer['status'] == 'approved' ? 'Successful' : ucfirst($transfer['status']);  ?>
        </span>
    </td>
</tr>

<?php if ($type == 'transfer') { ?>

    <tr>
        <td>Bank:</td>
        <td><?php echo $transfer['receiver_bank']; ?></td>
    </tr>
    <tr>
    <td>
        <?php 
        echo ($transfer['state'] == 'from') 
            ? 'Sender Account Number:' 
            : 'Beneficiary Account Number:'; 
        ?>
    </td>
    <td><?php echo $transfer['receiver_account_number']; ?></td>
</tr>

<tr>
    <td>
        <?php 
        echo ($transfer['state'] == 'from') 
            ? 'Sender Account Name:' 
            : 'Beneficiary Account Name:'; 
        ?>
    </td>
    <td><?php echo $transfer['receiver_name']; ?></td>
</tr>
    <tr>
        <td>Routing Number:</td>
        <td><?php echo $transfer['routing_number']; ?></td>
    </tr>
    <tr>
        <td>Swift Code:</td>
        <td><?php echo $transfer['swift_code']; ?></td>
    </tr>
    <tr>
        <td>Narration:</td>
        <td><?php echo $transfer['narration']; ?></td>
    </tr>
    <tr>
        <td>Date:</td>
        <td><?php echo $transfer['created_at']; ?></td>
    </tr>

<?php if (!empty($transfer['wallet_address'])) { ?>
<tr>
    <td>Network:</td>
    <td><?php echo $transfer['network']; ?></td>
</tr>
<tr>
    <td>Wallet Address:</td>
    <td><?php echo $transfer['wallet_address']; ?></td>
</tr>
<tr>
    <td>Label:</td>
    <td><?php echo $transfer['label']; ?></td>
</tr>
<?php } ?>

<tr>
    <td>Date:</td>
    <td><?php echo $transfer['date']; ?></td>
</tr>
<?php } elseif ($type == 'withdrawal') { ?>

    <tr>
        <td>Bank:</td>
        <td><?php echo $transfer['bank_name']; ?></td>
    </tr>
    <tr>
        <td>Account Number:</td>
        <td><?php echo $transfer['account_number']; ?></td>
    </tr>
    <tr>
        <td>Account Name:</td>
        <td><?php echo $transfer['account_name']; ?></td>
    </tr>
    <tr>
        <td>Account Type:</td>
        <td><?php echo $transfer['which_account']; ?></td>
    </tr>
    <tr>
        <td>Date:</td>
        <td><?php echo $transfer['date']; ?></td>
    

<?php } elseif ($type == 'deposit') { ?>

<tr>
    <td>Payment Type:</td>
    <td><?php echo ucfirst($transfer['payment_type']); ?></td>
</tr>

<?php if ($transfer['payment_type'] == 'bank') { ?>

<tr>
    <td>Bank Name:</td>
    <td><?php echo $transfer['bank_name']; ?></td>
</tr>
<tr>
    <td>Account Number:</td>
    <td><?php echo $transfer['account_number']; ?></td>
</tr>
<tr>
    <td>Account Name:</td>
    <td><?php echo $transfer['account_fullname']; ?></td>
</tr>
<tr>
    <td>Routing Number:</td>
    <td><?php echo $transfer['routing_number']; ?></td>
</tr>

<?php } elseif ($transfer['payment_type'] == 'crypto') { ?>

<tr>
    <td>Network:</td>
    <td><?php echo $transfer['network']; ?></td>
</tr>
<tr>
    <td>Wallet Address:</td>
    <td><?php echo $transfer['wallet_address']; ?></td>
</tr>
<tr>
    <td>Label:</td>
    <td><?php echo $transfer['label']; ?></td>
</tr>

<?php } ?>

<tr>
    <td>Date:</td>
    <td><?php echo $transfer['date']; ?></td>
</tr>

<?php } ?>

</tr>
        <td>Action:</td>
        <td><a href="../dashboard/" class="btn btn-secondary">
            &larr; Back
        </a></td>
    </tr>

</tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

                    <?php } else { ?>
                        <div class='alert alert-danger'>Transaction not found</div>
                    <?php } ?>

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