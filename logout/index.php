<?php
include("../server/connection.php");

if(isset($_SESSION['user_id'])) {
    session_destroy();
    header("location: {$domain}auth/sign_in/");
}




?>