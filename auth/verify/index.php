<?php include('../../server/connection.php');

 ?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php

if (!isset($_GET['token'])) {
    echo "
    <script>
        Swal.fire({
            title: 'Invalid Link',
            text: 'The verification link is missing.',
            icon: 'error'
        }).then(() => {
            window.location.href = '../sign_in';
        });
    </script>";
    exit;
}

$token = $_GET['token'];

// CHECK TOKEN
$stmt = $connection->prepare("SELECT id FROM users WHERE verification_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {

    echo "
    <script>
        Swal.fire({
            title: 'Invalid or Expired',
            text: 'This verification link is not valid.',
            icon: 'error'
        }).then(() => {
            window.location.href = '../sign_in';
        });
    </script>";
    exit;
}

$row = $result->fetch_assoc();
$user_id = $row['id'];

// UPDATE APPROVAL
$update = $connection->prepare("UPDATE users SET is_approved = 1, verification_token = NULL WHERE id = ?");
$update->bind_param("i", $user_id);

if ($update->execute()) {

    echo "
    <script>
        Swal.fire({
            title: 'Verification Successful!',
            text: 'Your email has been verified. You may now login.',
            icon: 'success'
        }).then(() => {
            window.location.href = '../sign_in';
        });
    </script>";

} else {

    echo "
    <script>
        Swal.fire({
            title: 'Verification Failed',
            text: 'We could not verify your account. Try again later.',
            icon: 'error'
        }).then(() => {
            window.location.href = '../sign_in';
        });
    </script>";
}

?>

</body>
</html>
