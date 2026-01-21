<?php
include("../../../server/connection.php");


$id = mysqli_real_escape_string($connection, $_GET['id']);

$sql = "
    SELECT 
        bank_transfers.id,
        bank_transfers.user_id,
        bank_transfers.receiver_account_number,
        bank_transfers.receiver_name,
        bank_transfers.receiver_bank,
        bank_transfers.routing_number,
        bank_transfers.swift_code,
        bank_transfers.amount,
        bank_transfers.otp_code,
        bank_transfers.otp_expires_at,
        bank_transfers.status,
        bank_transfers.created_at,
        bank_transfers.updated_at,
        bank_transfers.narration,
        users.fullname
    FROM bank_transfers
    INNER JOIN users ON users.id = bank_transfers.user_id
    WHERE bank_transfers.id = '$id'
";

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
        <?php include("../../include/nav.php") ?>

        <!-- side nav -->

        <?php include("../../include/sidenav.php") ?>
        <div class="content-body">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-4">
                                    <div class="page-title-content">
                                        <h3>Transfer Details</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="breadcrumbs"><a href="<?php echo $domain  ?>/admin/dashboard/">Home </a>
                                        <span><i class="fi fi-rr-angle-small-right"></i></span>
                                        <a href="<?php echo $domain  ?>/admin/transfer/details">Transfer Details</a>
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
                                        Transfer Details for <?php echo $transfer['fullname']; ?>
                                    </h4>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>

                                                <tr>
                                                    <td><span class="text-primary">Amount Sent:</span></td>
                                                    <td><span class="text-primary">$<?php echo number_format($transfer['amount'], 2); ?></span></td>
                                                </tr>

                                                <tr>
                                                    <td>Sender Name:</td>
                                                    <td><?php echo $transfer['fullname']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td>Beneficiary Bank:</td>
                                                    <td><?php echo $transfer['receiver_bank']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td>Beneficiary Account Number:</td>
                                                    <td><?php echo $transfer['receiver_account_number']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Beneficiary Name:</td>
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
                                                    <td>Status:</td>
                                                    <td>
                                                        <span class="badge 
                                    <?php
                                    echo ($transfer['status'] == 'pending') ? 'bg-warning' : (($transfer['status'] == 'completed') ? 'bg-success' : 'bg-danger');
                                    ?>">
                                                            <?php echo ucfirst($transfer['status']); ?>
                                                        </span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Narration:</td>
                                                    <td><?php echo $transfer['narration']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td>Created At:</td>
                                                    <td><?php echo $transfer['created_at']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td>Last Update:</td>
                                                    <td><?php echo $transfer['updated_at']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td>Action</td>
                                                    <td>
                                                        <a href="./?approve_transfers=<?= $transfer['id'] ?>">
                                                                    <button class="btn btn-success btn-sm approve-deposit">Approve</button>
                                                                </a>
                                                                <a href="./?decline_transfers=<?= $transfer['id'] ?>">
                                                                    <button class="btn btn-danger btn-sm decline-deposit">Decline</button>
                                                                </a>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

                    <?php } else { ?>
                        <div class='alert alert-danger'>Transfer not found</div>
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