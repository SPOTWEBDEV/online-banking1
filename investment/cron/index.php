<?php
/*
 * Daily investment payout cron.
 * Run ONCE a day, e.g. cPanel cron:   0 0 * * *  php /home/USER/public_html/cron/investment-cron.php
 * (If your host only allows URL crons, call it as  .../investment-cron.php?key=YOUR_SECRET )
 */
define('CRON_KEY', 'change-this-secret');

// Block random visitors: allow CLI, or HTTP only with the secret key
if (php_sapi_name() !== 'cli') {
    if (!isset($_GET['key']) || !hash_equals(CRON_KEY, (string) $_GET['key'])) {
        http_response_code(403);
        exit('Forbidden');
    }
}

include(__DIR__ . "/../server/connection.php");

// Make mysqli THROW on errors, otherwise the catch block below never runs
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 1) Close anything that has already been fully paid
mysqli_query($connection, "
    UPDATE investments i
    JOIN investment_plans p ON p.id = i.plan_id
    SET i.status = 'completed'
    WHERE i.status = 'active' AND i.days_paid >= p.duration
");

// 2) Active investments that are due a payout today
//    profit_per_day and duration live in investment_plans, not investments
$result = mysqli_query($connection, "
    SELECT i.id, i.user_id, i.days_paid, p.profit_per_day, p.duration
    FROM investments i
    JOIN investment_plans p ON p.id = i.plan_id
    WHERE i.status = 'active'
      AND i.days_paid < p.duration
      AND (i.last_paid_at IS NULL OR DATE(i.last_paid_at) < CURDATE())
");
$due = mysqli_fetch_all($result, MYSQLI_ASSOC);

$paid = $skipped = $failed = 0;

foreach ($due as $inv) {

    $investment_id = (int) $inv['id'];
    $user_id       = (int) $inv['user_id'];
    $profit        = (float) $inv['profit_per_day'];
    $days_paid     = (int) $inv['days_paid'];
    $duration      = (int) $inv['duration'];

    if ($profit <= 0) {
        $skipped++;
        continue;
    }

    $new_days   = $days_paid + 1;
    $new_status = ($new_days >= $duration) ? 'completed' : 'active';

    mysqli_begin_transaction($connection);

    try {

        // Move the investment forward. The WHERE makes this safe to run twice:
        // if it was already paid today (or by another run), 0 rows change and we stop.
        $stmt1 = mysqli_prepare($connection, "
            UPDATE investments
            SET days_paid = ?, last_paid_at = NOW(), status = ?
            WHERE id = ?
              AND status = 'active'
              AND days_paid = ?
              AND (last_paid_at IS NULL OR DATE(last_paid_at) < CURDATE())
        ");
        mysqli_stmt_bind_param($stmt1, "isii", $new_days, $new_status, $investment_id, $days_paid);
        mysqli_stmt_execute($stmt1);
        $moved = mysqli_stmt_affected_rows($stmt1);
        mysqli_stmt_close($stmt1);

        if ($moved !== 1) {
            mysqli_rollback($connection);
            $skipped++;
            continue;
        }

        // Credit the user's balance
        $stmt2 = mysqli_prepare($connection, "
            UPDATE users
            SET balance = balance + ?
            WHERE id = ?
        ");
        mysqli_stmt_bind_param($stmt2, "di", $profit, $user_id);
        mysqli_stmt_execute($stmt2);
        $credited = mysqli_stmt_affected_rows($stmt2);
        mysqli_stmt_close($stmt2);

        if ($credited !== 1) {
            throw new Exception("User $user_id not found for investment $investment_id");
        }

        mysqli_commit($connection);
        $paid++;

    } catch (Throwable $e) {
        mysqli_rollback($connection);
        error_log("Investment cron failed for #$investment_id: " . $e->getMessage());
        $failed++;
    }
}

echo date('Y-m-d H:i:s') . " | paid: $paid | skipped: $skipped | failed: $failed\n";