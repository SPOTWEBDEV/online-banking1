<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    $sitename = "Zentra Bank";
    $domain = "";
    define("USER", "");
    define("PASSWORD", "");
    define("DATABASE", "");


    $connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
} elseif ($request === 'http') {
    $sitename = "Zentra Bank";
    $domain = "http://localhost/online-banking1";
    define("USER", "root");
    define("PASSWORD", "");
    define("DATABASE", "zentra_bank");


    $connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
} else {
    $sitename = "Zentra Bank";
    $domain = "http://localhost/online-banking1";
    define("USER", "root");
    define("PASSWORD", "");
    define("DATABASE", "zentra_bank");


    $connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
};

session_start();
