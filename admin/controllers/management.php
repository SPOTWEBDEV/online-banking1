<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?php echo $domain ?>/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo $domain ?>/js/sweetalert2.all.min.js"></script>
</head>

<body>
    <?php

    $url = $domain . '/admin/management/add';




    // Run only on POST
    if (isset($_POST['add_user_investment'])) {
        $user_id         = $_POST['user_id'] ?? null;
        $plan_name       = $_POST['plan_name'] ?? null;
        $amount_invested = $_POST['amount_invested'] ?? null;
        $daily_profit    = $_POST['daily_profit'] ?? null;
        $total_profit    = $_POST['total_profit'] ?? null;
        $start_date      = $_POST['start_date'] ?? null;
        $end_date        = $_POST['end_date'] ?? null;

        if (
            !$user_id || !$plan_name || !$amount_invested ||
            !$daily_profit || !$total_profit || !$start_date || !$end_date
        ) {


            echo "<script>Swal.fire('You have an input error','All fields are required','warning')</script>";
            echo "<script> setTimeout(()=> { window.location.href = '$url'},1000) </script>";
        }

        $sql = "INSERT INTO investments 
            (user_id, plan_name, amount_invested, daily_profit, total_profit, start_date, end_date)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "isddiss",
            $user_id,
            $plan_name,
            $amount_invested,
            $daily_profit,
            $total_profit,
            $start_date,
            $end_date
        );

        if (mysqli_stmt_execute($stmt)) {

            echo "<script>Swal.fire('Investment Added Successfully','','success')</script>";
            echo "<script> setTimeout(()=> { window.location.href = '$domain/admin/management/add'},1000) </script>";
        } else {
            echo "<script>Swal.fire('Failed Request','Something went wrong','error')</script>";
            echo "<script> setTimeout(()=> { window.location.href = '$url'},1000) </script>";
        }
    }


    if (isset($_POST['submit'])) {
        $user_id           = $_POST['user_id'];
        $loan_amount       = $_POST['loan_amount'];
        $loan_duration     = $_POST['loan_duration'];
        $loan_reason       = $_POST['loan_reason'];
        $monthly_income    = $_POST['monthly_income'];
        $employment_status = $_POST['employment_status'];
        $bank_name         = $_POST['bank_name'];
        $account_number    = $_POST['account_number'];
        $interest_rate     = $_POST['interest_rate'];
        $total_payable     = $_POST['total_payable'];

        $insert = $connection->prepare("
        INSERT INTO loan_requests 
        (user_id, loan_amount, loan_duration, loan_reason, monthly_income, employment_status, bank_name, account_number, interest_rate, total_payable)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

        $insert->bind_param(
            "idsssissdd",
            $user_id,
            $loan_amount,
            $loan_duration,
            $loan_reason,
            $monthly_income,
            $employment_status,
            $bank_name,
            $account_number,
            $interest_rate,
            $total_payable
        );

        if ($insert->execute()) {
            echo "<script>Swal.fire('Loan Request Submitted Successfully','','success')</script>";
            echo "<script> setTimeout(()=> { window.location.href = '$domain/admin/management/add'},1000) </script>";
        } else {
            echo "<script>Swal.fire('Failed Request','Something went wrong','error')</script>";
            echo "<script> setTimeout(()=> { window.location.href = '$url'},1000) </script>";
        }
    }

    
    if (isset($_POST['add_transfer'])) {

    $user_id  = $_POST['user_id'];
    $account  = $_POST['receiver_account_number'];
    $bank     = $_POST['receiver_bank'];
    $name     = $_POST['receiver_name'];
    $routing  = $_POST['routing_number'];
    $swift    = $_POST['swift_code'];
    $amount   = $_POST['amount'];
    $status   = $_POST['status'];
    $date     = $_POST['created_at'];
    $narration = $_POST['narration'];
    $state = $_POST['state'] ; // 'to' or 'from'

    // Generate OTP (optional but good)
    $otp_code = rand(100000, 999999);
    $otp_expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));

   // Get user balance
$check = $connection->prepare("SELECT balance FROM users WHERE id = ?");
$check->bind_param("i", $user_id);
$check->execute();
$result = $check->get_result();
$user = $result->fetch_assoc();

$current_balance = $user['balance'];

// Check if amount is greater than balance
if ($state === 'from' && $amount > $current_balance) {

    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Insufficient Balance',
            html: 'Transfer amount ($$amount) is greater than user balance ($" . number_format($current_balance,2) . ")'
        })
    </script>";

    
}else{

// INSERT TRANSFER
    $insert = $connection->prepare("
        INSERT INTO bank_transfers 
(user_id, receiver_account_number, receiver_bank, receiver_name, routing_number, swift_code, amount, otp_code, otp_expires_at, status, state, created_at, narration)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insert->bind_param(
    "isssssdssssss",
    $user_id,
    $account,
    $bank,
    $name,
    $routing,
    $swift,
    $amount,
    $otp_code,
    $otp_expires,
    $status,
    $state,
    $date,
    $narration
);

    if ($insert->execute()) {

        // ✅ Deduct balance ONLY if completed
        if ($status == 'completed') {

    if ($state === 'to') {
        // ✅ CREDIT (add money)
        $update = $connection->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $update->bind_param("di", $amount, $user_id);
        $update->execute();
    }

    if ($state === 'from') {
        // ❌ DEBIT (remove money)
        $update = $connection->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $update->bind_param("di", $amount, $user_id);
        $update->execute();
    }
}

        echo "<script>Swal.fire('Transfer Added Successfully','','success')</script>";
        echo "<script> setTimeout(()=> { window.location.href = '$domain/admin/management/add'},1000) </script>";

    } else {
        echo "<script>Swal.fire('Failed Request','Something went wrong','error')</script>";
        echo "<script> setTimeout(()=> { window.location.href = '$url'},1000) </script>";
    }


}

    
}




    ?>

</body>

</html>