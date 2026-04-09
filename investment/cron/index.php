<?php
include("../server/connection.php");

// get active investments
$sql = "
    SELECT i.*, u.balance 
    FROM investments i
    JOIN users u ON i.user_id = u.id
    WHERE i.status = 'active'
";
$result = mysqli_query($connection, $sql);

while ($inv = mysqli_fetch_assoc($result)) {

    $investment_id = $inv['id'];
    $user_id = $inv['user_id'];
    $profit = (float)$inv['profit_per_day'];
    $days_paid = (int)$inv['days_paid'];
    $duration = (int)$inv['duration'];

    // skip if completed
    if ($days_paid >= $duration) {
        mysqli_query($connection, "
            UPDATE investments 
            SET status = 'completed' 
            WHERE id = $investment_id
        ");
        continue;
    }

    mysqli_begin_transaction($connection);

    try {

        // add profit to user
        $stmt1 = mysqli_prepare($connection, "
            UPDATE users 
            SET balance = balance + ? 
            WHERE id = ?
        ");
        mysqli_stmt_bind_param($stmt1, "di", $profit, $user_id);
        mysqli_stmt_execute($stmt1);

        // update investment progress
        $stmt2 = mysqli_prepare($connection, "
            UPDATE investments 
            SET days_paid = days_paid + 1 
            WHERE id = ?
        ");
        mysqli_stmt_bind_param($stmt2, "i", $investment_id);
        mysqli_stmt_execute($stmt2);

        mysqli_commit($connection);

    } catch (Exception $e) {
        mysqli_rollback($connection);
    }
}