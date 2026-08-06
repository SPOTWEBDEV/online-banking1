<?php
include("../../../server/connection.php");

$id = mysqli_real_escape_string($connection, $_GET['id']);

// Handle update submission
$update_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $fullname       = $_POST['fullname'];
    $email          = $_POST['email'];
    $accountnumber  = $_POST['accountnumber'];
    $country        = $_POST['country'];
    $date_of_birth  = $_POST['date_of_birth'];
    $balance        = $_POST['balance'];
    $loan_balance   = $_POST['loan_balance'];
    $crypto_balance = $_POST['crypto_balance'];
    $vcard_balance  = $_POST['virtual_card_balance'];
    $limits         = $_POST['limits'];
    $vcard_number   = $_POST['virtual_card_number'];
    $vcard_expiry   = $_POST['virtual_card_expiring_date'];
    $is_approved    = isset($_POST['is_approved']) ? 1 : 0;
    $status         = $_POST['status'];
    $new_password   = trim($_POST['password']);

    if ($new_password !== '') {
        // Only touch the password if the admin actually typed a new one
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $connection->prepare("
            UPDATE users SET
                fullname = ?, email = ?, accountnumber = ?, country = ?,
                date_of_birth = ?, balance = ?, loan_balance = ?, crypto_balance = ?,
                virtual_card_balance = ?, limits = ?, virtual_card_number = ?,
                virtual_card_expiring_date = ?, is_approved = ?, status = ?, password = ?
            WHERE id = ?
        ");
        $stmt->bind_param(
            "ssssssddddsssiss",
            $fullname, $email, $accountnumber, $country, $date_of_birth,
            $balance, $loan_balance, $crypto_balance, $vcard_balance,
            $limits, $vcard_number, $vcard_expiry, $is_approved, $status,
            $hashed, $id
        );
    } else {
        $stmt = $connection->prepare("
            UPDATE users SET
                fullname = ?, email = ?, accountnumber = ?, country = ?,
                date_of_birth = ?, balance = ?, loan_balance = ?, crypto_balance = ?,
                virtual_card_balance = ?, limits = ?, virtual_card_number = ?,
                virtual_card_expiring_date = ?, is_approved = ?, status = ?
            WHERE id = ?
        ");
        $stmt->bind_param(
            "ssssssddddsssis",
            $fullname, $email, $accountnumber, $country, $date_of_birth,
            $balance, $loan_balance, $crypto_balance, $vcard_balance,
            $limits, $vcard_number, $vcard_expiry, $is_approved, $status,
            $id
        );
    }

    if ($stmt->execute()) {
        $update_message = '<div class="alert alert-success">User updated successfully.</div>';
    } else {
        $update_message = '<div class="alert alert-danger">Update failed: ' . htmlspecialchars($stmt->error) . '</div>';
    }
    $stmt->close();
}

$sql = "
    SELECT 
        *
    FROM users
    WHERE id = '$id'
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

                <?= $update_message ?>

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
    <td><strong>Account Approved:</strong></td>
    <td>
        <?= $user['is_approved'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-warning">No</span>'; ?>
    </td>
</tr>

<tr>
    <td><strong>Status:</strong></td>
    <td>
        <span class="badge text-white <?= $status_color; ?>">
            <?= ucfirst($user['status']); ?>
        </span>
    </td>
</tr>

</tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Edit User Form -->
                    <div class="col-12 mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit User</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="?id=<?= urlencode($user['id']) ?>">
                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" name="fullname" class="form-control"
                                                value="<?= htmlspecialchars($user['fullname']) ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="<?= htmlspecialchars($user['email']) ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Account Number</label>
                                            <input type="text" name="accountnumber" class="form-control"
                                                value="<?= htmlspecialchars($user['accountnumber']) ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Country</label>
                                            <input type="text" name="country" class="form-control"
                                                value="<?= htmlspecialchars($user['country']) ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" name="date_of_birth" class="form-control"
                                                value="<?= htmlspecialchars($user['date_of_birth']) ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-control">
                                                <?php foreach (['active', 'pending', 'suspended', 'banned'] as $s): ?>
                                                    <option value="<?= $s ?>" <?= $user['status'] === $s ? 'selected' : '' ?>>
                                                        <?= ucfirst($s) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Main Balance</label>
                                            <input type="number" step="0.01" name="balance" class="form-control"
                                                value="<?= htmlspecialchars($user['balance']) ?>">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Loan Balance</label>
                                            <input type="number" step="0.01" name="loan_balance" class="form-control"
                                                value="<?= htmlspecialchars($user['loan_balance']) ?>">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Crypto Balance</label>
                                            <input type="number" step="0.01" name="crypto_balance" class="form-control"
                                                value="<?= htmlspecialchars($user['crypto_balance']) ?>">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Virtual Card Balance</label>
                                            <input type="number" step="0.01" name="virtual_card_balance" class="form-control"
                                                value="<?= htmlspecialchars($user['virtual_card_balance']) ?>">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Limits</label>
                                            <input type="text" name="limits" class="form-control"
                                                value="<?= htmlspecialchars($user['limits']) ?>">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Virtual Card Number</label>
                                            <input type="text" name="virtual_card_number" class="form-control"
                                                value="<?= htmlspecialchars($user['virtual_card_number']) ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Card Expiry Date</label>
                                            <input type="date" name="virtual_card_expiring_date" class="form-control"
                                                value="<?= htmlspecialchars($user['virtual_card_expiring_date']) ?>">
                                        </div>

                                        <div class="col-md-6 d-flex align-items-end">
                                            <div class="form-check">
                                                <input type="checkbox" name="is_approved" class="form-check-input" id="is_approved"
                                                    <?= $user['is_approved'] ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="is_approved">Account Approved</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">New Password</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Leave blank to keep current password" autocomplete="new-password">
                                            <small class="text-muted">Only fill this in if you want to change the password. It will be securely hashed.</small>
                                        </div>

                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" name="update_user" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php } else { ?>
                    <div class="alert alert-danger">User not found</div>
                <?php } ?>

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