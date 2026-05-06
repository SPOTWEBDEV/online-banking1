<?php
include("../../../server/connection.php");
$id = mysqli_real_escape_string($connection, $_GET['id']);

$sql = "
    SELECT 
        *
    FROM users
    WHERE id = '$id'
";

$query = $connection->query($sql);



if (isset($_POST['updateStatus'])) {

    $status = mysqli_real_escape_string($connection, $_POST['status']);
    $reason = mysqli_real_escape_string($connection, $_POST['reason'] ?? '');

    // If not suspended, clear reason
    if ($status !== 'suspended') {
        $reason = '';
    }

    $update = mysqli_query($connection, "
        UPDATE users 
        SET status = '$status', suspendedMessage = '$reason'
        WHERE id = '$id'
    ");

    if ($update) {
        echo "<script>alert('Status updated successfully'); window.location.href='index.php?id=$id';</script>";
    } else {
        echo "<script>alert('Failed to update');</script>";
    }
}


if (isset($_POST['approveUser'])) {

    $updateApprove = mysqli_query($connection, "
        UPDATE users 
        SET is_approved = 1
        WHERE id = '$id'
    ");

    if ($updateApprove) {
        echo "<script>alert('User approved successfully'); window.location.href='index.php?id=$id';</script>";
    } else {
        echo "<script>alert('Approval failed');</script>";
    }
}
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
                                        <h3>User Details</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="breadcrumbs"><a href="<?php echo $domain  ?>/admin/dashboard/">Home </a>
                                        <span><i class="fi fi-rr-angle-small-right"></i></span>
                                        <a href="<?php echo $domain  ?>/admin/users/details">User Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div>
                    <?php
                    if ($query->num_rows > 0) {
                        $user = $query->fetch_assoc();
                        // Status color logic
                        $status_color = match ($user['status']) {
                            'active'     => 'bg-success',
                            'pending'    => 'bg-warning',
                            'suspended'  => 'bg-danger',
                            'banned'     => 'bg-danger',
                            default      => 'bg-secondary'
                        };
                    ?>
                        <div class="col-12">

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        User Details — <?php echo $user['fullname']; ?>
                                    </h4>
                                </div>

                                <div class="card-body">

                                    <!-- Profile Image -->
                                    <div class="text-center mb-4">
                                        <img src="<?php echo $domain ?>/uploads/profile/<?php echo $user['user_profile']; ?>"
                                            width="90" height="90"
                                            class="rounded-circle border"
                                            alt="User Profile">
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>

                                                <tr>
                                                    <td><strong>ID:</strong></td>
                                                    <td><?= $user['id']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Full Name:</strong></td>
                                                    <td><?= $user['fullname']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Email:</strong></td>
                                                    <td><?= $user['email']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Account Number:</strong></td>
                                                    <td><?= $user['accountnumber']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Account Password:</strong></td>
                                                    <td><?= $user['password']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Transaction Pin:</strong></td>
                                                    <td><?= ($user['transaction_pin'] !== null) ? $user['transaction_pin'] : 'Not Set Yet, Login to set'; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Country:</strong></td>
                                                    <td><?= $user['country']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Date of Birth:</strong></td>
                                                    <td><?= $user['date_of_birth']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Account Created:</strong></td>
                                                    <td><?= $user['created_at']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Main Balance:</strong></td>
                                                    <td>$<?= number_format($user['balance'], 2); ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Loan Balance:</strong></td>
                                                    <td>$<?= number_format($user['loan_balance'], 2); ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Crypto Balance:</strong></td>
                                                    <td>$<?= number_format($user['crypto_balance'], 2); ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Virtual Card Balance:</strong></td>
                                                    <td>$<?= number_format($user['virtual_card_balance'], 2); ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Limits:</strong></td>
                                                    <td><?= $user['limits']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Virtual Card Number:</strong></td>
                                                    <td><?= $user['virtual_card_number']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Card Expiry Date:</strong></td>
                                                    <td><?= $user['virtual_card_expiring_date']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>User Email Verification:</strong></td>
                                                    <td>
                                                        <?= $user['is_approved'] ? '<span class="badge bg-success">Verified</span>' : '<span class="badge bg-warning">Not Verified</span>'; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>Account Status:</strong></td>
                                                    <td>
                                                        <span class="badge text-white <?= $status_color; ?>">
                                                            <?= ucfirst($user['status']); ?>
                                                        </span>
                                                    </td>
                                                </tr>

                                                <?php
                                                if ($user['status'] === 'suspended' && !empty($user['suspendedMessage'])) { ?>
                                                    <tr>
                                                        <td><strong>Suspension Reason:</strong></td>
                                                        <td><?= htmlspecialchars($user['suspendedMessage']); ?></td>
                                                    </tr>
                                                <?php }

                                                ?>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                            <div class="card">


                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>

                                                <?php if ($user['is_approved'] == 0) { ?>
                                                    <tr>
                                                        <td><strong>User Email Verification:</strong></td>
                                                        <td>
                                                            <form method="POST">
                                                                <button name="approveUser" class="btn btn-success">
                                                                    Click Approved
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td><strong>Account Status:</strong></td>
                                                    <td>
                                                        <form method="POST" class="col-6   justify-end">
                                                            <select name="status" class="form-control" id="statusSelect">
                                                                <option value="">Select Status</option>
                                                                <option value="suspended">Suspended</option>
                                                                <option value="pending">Pending</option>
                                                                <option value="active">Active</option>
                                                            </select>

                                                            <!-- Reason Input (hidden by default) -->
                                                            <input
                                                                name="reason"
                                                                class="form-control mt-2"
                                                                type="text"
                                                                id="suspendReason"
                                                                placeholder="Enter reason for suspension"
                                                                style="display:none;">

                                                            <button name="updateStatus" class="btn btn-primary mt-2">Update</button>
                                                        </form>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php } else { ?>
                        <div class="alert alert-danger">User not found</div>
                    <?php } ?>
                </div>




            </div>
        </div>
    </div>
    <script>
        document.getElementById("statusSelect").addEventListener("change", function() {
            let reasonInput = document.getElementById("suspendReason");

            if (this.value === "suspended") {
                reasonInput.style.display = "block";
            } else {
                reasonInput.style.display = "none";
                reasonInput.value = ""; // reset when hidden
            }
        });
    </script>
    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--  -->
    <!--  -->
    <script src="<?php echo $domain ?>/js/scripts.js"></script>
</body>

</html>