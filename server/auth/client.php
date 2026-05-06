<?php

if(!isset($_SESSION['user_id'])) {
    header("location: {$domain}auth/sign_in/");
}

$user_id = $_SESSION['user_id'];
$select = mysqli_query($connection, "SELECT * FROM users WHERE id = $user_id");
$client = mysqli_fetch_assoc($select);


if($client['status'] == 'suspended') {
    session_destroy();
    header("location: {$domain}auth/sign_in/");
}


?>