<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['investment'])) {

    $plan_name      = trim($_POST['plan_name'] ?? '');
    $duration       = $_POST['duration'] ?? '';
    $profit_per_day = $_POST['profit_per_day'] ?? '';
    $total_profit   = $_POST['total_profit'] ?? '';
    $min_amount     = $_POST['min_amount'] ?? '';
    $max_amount     = $_POST['max_amount'] ?? '';

    if ($plan_name === '' || $duration === '' || $profit_per_day === '' ||
        $total_profit === '' || $min_amount === '' || $max_amount === '') {
        die("Validation failed: " . json_encode($_POST));
    }

    $sql = "INSERT INTO investment_plans
            (plan_name, duration, profit_per_day, total_profit, min_amount, max_amount)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($connection, $sql);
    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "sidddd",
        $plan_name, $duration, $profit_per_day, $total_profit, $min_amount, $max_amount);

    if (!mysqli_stmt_execute($stmt)) {
        die("Execute failed: " . mysqli_stmt_error($stmt));
    }

    echo "<script>window.location.href='$domain/admin/investment/'</script>";
    exit;
}
?>