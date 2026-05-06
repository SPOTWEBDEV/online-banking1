<?php
error_reporting(E_ALL);
// ini_set('display_errors', 0); // ❌ don't show errors to users
ini_set('log_errors', 1);     // ✅ log errors instead
ini_set('error_log', 'error.log'); // file where errors will be saved


function checkUrlProtocol($url)
{
    $parsedUrl = parse_url($url);
    if (isset($parsedUrl['scheme'])) {
        return $parsedUrl['scheme'];
    } else {
        return 'invalid';
    }
}

$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];


$request = checkUrlProtocol($currentUrl);

define("HOST", "localhost");

if ($request === 'https') {
    $domain = "https://zenvault-app.com/";
    define("USER", "zenvault_dd");
    define("PASSWORD", "zenvault_dd");
    define("DATABASE", "zenvault_dd");


    $connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
} else {
    $domain = "http://localhost/zentra-bank-c/";
    define("USER", "root");
    define("PASSWORD", "");
    define("DATABASE", "zentra-bank");


    $connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
};

session_start();
$sitename = "Zenvault Bank";
$sitephone = "+234 XXX XXX XXXX";
$siteemail = "support@zenvault-app.com";


?>