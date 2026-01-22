<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$con = mysqli_connect("127.0.0.1", "root", "", "", 3306); // try 3306 first
if (!$con) die(mysqli_connect_error());

$res = mysqli_query($con, "SHOW DATABASES");
while ($row = mysqli_fetch_row($res)) {
    echo $row[0] . "<br>";
}