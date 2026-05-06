<?php

if (!isset($_SESSION['admin'])) {
    header("location: {$domain}admin/login/");
}