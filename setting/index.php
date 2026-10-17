<?php
include("../server/connection.php");
include("../server/auth/client.php");
?>

<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | setting</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/logo.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .st {
            --st-primary: var(--app-primary, #2563eb);
            --st-primary-soft: var(--app-primary-soft, #eff6ff);
            --st-border: #eef0f4;
            --st-muted: #6b7280;
        }

        /* Tabs */
        .st .st-tabs {
            display: inline-flex;
            padding: 4px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, .05);
            margin: 16px 0 20px;
        }

        .st .st-tab {
            padding: 9px 18px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 6px 14px rgba(37, 99, 235, .25);
        }

        /* Alerts */
        .st .st-alert {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .st .st-alert.err {
            background: rgba(220, 38, 38, .08);
            color: #b91c1c;
            border: 1px solid rgba(220, 38, 38, .2);
        }

        .st .st-alert.ok {
            background: rgba(22, 163, 74, .08);
            color: #15803d;
            border: 1px solid rgba(22, 163, 74, .25);
        }

        .st .st-alert p {
            margin: 0 0 2px;
        }

        .st .st-alert p:last-child {
            margin: 0;
        }

        /* Layout */
        .st .st-grid {
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 20px;
            align-items: start;
        }

        .st .st-stack {
            display: grid;
            gap: 20px;
        }

        /* Cards */
        .st .st-card {
            background: #fff;
            border: 1px solid var(--st-border);
            border-radius: 14px;
            box-shadow: 0 6px 24px rgba(15, 23, 42, .05);
            padding: 24px;
        }

        .st .st-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .st .st-card-head .ico {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: var(--st-primary-soft);
            color: var(--st-primary);
            flex-shrink: 0;
        }

        .st .st-card-head h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .st .st-card-head p {
            margin: 2px 0 0;
            font-size: 13px;
            color: var(--st-muted);
        }

        /* Profile card */
        .st .st-profile {
            text-align: center;
        }

        .st .st-avatar {
            position: relative;
            width: 104px;
            height: 104px;
            margin: 4px auto 14px;
        }

        .st .st-avatar img {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            object-fit: cover;
            object-position: 50% 20%;
            border: 4px solid #fff;
            box-shadow: 0 0 0 2px var(--st-primary-soft), 0 8px 20px rgba(15, 23, 42, .12);
        }

        .st .st-verified {
            position: absolute;
            right: -10px;
            bottom: 2px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #16a34a;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 999px;
            border: 2px solid #fff;
        }

        .st .st-profile h3 {
            margin: 0;
            font-size: 19px;
            font-weight: 700;
            color: #111827;
        }

        .st .st-profile .mail {
            display: block;
            margin-top: 2px;
            font-size: 13px;
            color: var(--st-muted);
            word-break: break-all;
        }

        .st .st-divider {
            border: 0;
            border-top: 1px dashed #e5e7eb;
            margin: 20px 0;
        }

        /* Upload dropzone */
        .st .st-drop {
            position: relative;
            display: block;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 18px 14px;
            text-align: center;
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }

        .st .st-drop:hover {
            border-color: var(--st-primary);
            background: var(--st-primary-soft);
        }

        .st .st-drop input[type="file"] {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .st .st-drop .t {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        .st .st-drop .s {
            font-size: 12px;
            color: var(--st-muted);
            margin-top: 2px;
            word-break: break-all;
        }

        .st .st-drop svg {
            color: var(--st-primary);
            margin-bottom: 6px;
        }

        /* Forms */
        .st label.lbl {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .st .st-field {
            margin-bottom: 18px;
        }

        .st .st-input {
            position: relative;
        }

        .st .st-input input {
            width: 100%;
            height: 48px;
            padding: 0 46px 0 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
            background: #fff;
            color: #111827;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        .st .st-input input:focus {
            border-color: var(--st-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
        }

        .st .st-input .eye {
            position: absolute;
            right: 6px;
            top: 6px;
            width: 36px;
            height: 36px;
            border: 0;
            background: none;
            color: #9ca3af;
            border-radius: 8px;
            cursor: pointer;
            display: grid;
            place-items: center;
        }

        .st .st-input .eye:hover {
            color: var(--st-primary);
            background: #f3f4f6;
        }

        .st .st-hint {
            font-size: 12px;
            color: var(--st-muted);
            margin-top: 6px;
        }

        .st .st-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 0;
            border-radius: 10px;
            padding: 12px 22px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s, filter .15s;
        }

        .st .st-btn.primary,
        .st .btn.btn-primary.st-btn {
            background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
            color: #fff;
            box-shadow: 0 8px 18px rgba(37, 99, 235, .25);
        }

        .st .st-btn.primary:hover,
        .st .btn.btn-primary.st-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.06);
        }

        .st .st-btn.block {
            width: 100%;
        }

        .st .st-btn.warn {
            background: rgba(245, 158, 11, .14);
            color: #b45309;
        }

        .st .st-btn.warn:hover {
            background: #f59e0b;
            color: #fff;
        }

        .st .st-btn.danger {
            background: rgba(220, 38, 38, .1);
            color: #b91c1c;
        }

        .st .st-btn.danger:hover {
            background: #dc2626;
            color: #fff;
        }

        /* Wallet row */
        .st .st-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .st .st-row .txt {
            font-size: 14px;
            color: var(--st-muted);
            max-width: 420px;
        }

        /* Danger zone */
        .st .st-danger {
            border-color: rgba(220, 38, 38, .2);
        }

        .st .st-danger .st-card-head .ico {
            background: rgba(220, 38, 38, .08);
            color: #dc2626;
        }

        .st .st-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        @media (max-width: 991px) {
            .st .st-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575px) {
            .st .st-card {
                padding: 18px;
            }

            .st .st-btn {
                width: 100%;
            }
        }
    </style>

</head>

<body class="dashboard">

    <div id="main-wrapper">
        <!-- nav -->
        <?php include("../include/header.php") ?>

        <!-- sidnav -->
        <?php include("../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container st">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-6">
                                    <div class="page-title-content">
                                        <h3>Settings</h3>
                                        <p class="mb-2">Welcome <?= $sitename ?> Finance Management</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="st-tabs"><span class="st-tab">Account</span></div>
                    </div>
                </div>

                <?php


                /* =========================================================
                   BACKEND LOGIC (unchanged, same order as before)
                   Moved above the cards so the alert below can show
                   the result of both the upload and the password change.
                   ========================================================= */

                $user_id = $_SESSION['user_id'];
                $success = "";
                $errors  = [];


                if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_FILES['profile_image'])) {

                    $file = $_FILES['profile_image'];

                    if ($file['error'] !== 0) {
                        $errors[] = "Error uploading file.";
                    } else {

                        if ($file['size'] > 2 * 1024 * 1024) {
                            $errors[] = "File size must be less than 2MB.";
                        }

                        $finfo    = new finfo(FILEINFO_MIME_TYPE);
                        $mimeType = $finfo->file($file['tmp_name']);

                        $allowedTypes = [
                            'image/jpeg' => 'jpg',
                            'image/png'  => 'png'
                        ];

                        if (!array_key_exists($mimeType, $allowedTypes)) {
                            $errors[] = "Only JPG and PNG images are allowed.";
                        }

                        if (empty($errors)) {

                            $ext         = $allowedTypes[$mimeType];
                            $newFileName = uniqid('profile_', true) . "." . $ext;

                            // ✅ Correct filesystem path
                            $uploadDir = __DIR__ . "/../images/avatar/";

                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0755, true);
                            }

                            $destination = $uploadDir . $newFileName;

                            if (move_uploaded_file($file['tmp_name'], $destination)) {

                                // ✅ Store RELATIVE path only
                                $upload_path = "images/avatar/" . $newFileName;

                                $sql  = "UPDATE users SET user_profile = ? WHERE id = ?";
                                $stmt = mysqli_prepare($connection, $sql);
                                mysqli_stmt_bind_param($stmt, "si", $upload_path, $user_id);

                                if (mysqli_stmt_execute($stmt)) {
                                    $success = "Profile image uploaded successfully!";
                                    echo "<script>setTimeout(() => { 
                                                
                                                window.location.reload(); }, 1500);</script>";
                                } else {
                                    $errors[] = "Failed to update database.";
                                }

                                mysqli_stmt_close($stmt);
                            } else {
                                $errors[] = "Failed to move uploaded file.";
                            }
                        }
                    }
                }


                $sql2 = "SELECT fullname, user_profile FROM users WHERE id = ?";
                $stmt2 = mysqli_prepare($connection, $sql2);
                mysqli_stmt_bind_param($stmt2, "i", $user_id);
                mysqli_stmt_execute($stmt2);
                $result = mysqli_stmt_get_result($stmt2);
                $user   = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt2);

                $userName    = $user['fullname'];
                $userProfile = $user['user_profile'];

                // ✅ Default avatar fallback
                $defaultAvatar = "images/avatar/avatar.svg";

                if (empty($userProfile) || !file_exists(__DIR__ . "/../" . $userProfile)) {
                    $userProfile = $defaultAvatar;
                }


                if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['old_password'], $_POST['new_password'])) {

                    $old_password = trim($_POST['old_password']);
                    $new_password = trim($_POST['new_password']);

                    if (empty($old_password) || empty($new_password)) {
                        $errors[] = "All fields are required.";
                    }

                    if (strlen($new_password) < 6) {
                        $errors[] = "Minimum of 6 characters required.";
                    }

                    $sql3 = "SELECT password FROM users WHERE id = ?";
                    $stmt3 = mysqli_prepare($connection, $sql3);
                    mysqli_stmt_bind_param($stmt3, "i", $user_id);
                    mysqli_stmt_execute($stmt3);
                    $result = mysqli_stmt_get_result($stmt3);
                    $user   = mysqli_fetch_assoc($result);
                    mysqli_stmt_close($stmt3);

                    if (!$user || !password_verify($old_password, $user['password'])) {
                        $errors[] = "Old password is incorrect.";
                    }

                    if (empty($errors)) {
                        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);

                        $sql_update = "UPDATE users SET password = ? WHERE id = ?";
                        $stmt_update = mysqli_prepare($connection, $sql_update);
                        mysqli_stmt_bind_param($stmt_update, "si", $new_hash, $user_id);

                        if (mysqli_stmt_execute($stmt_update)) {
                            $success = "Password changed successfully!";
                        } else {
                            $errors[] = "Failed to update password.";
                        }

                        mysqli_stmt_close($stmt_update);
                    }
                }

                $showEmail = isset($userEmail) ? $userEmail : '';
                ?>

                <!-- Alerts -->
                <?php if (!empty($errors)): ?>
                    <div class="st-alert err">
                        <div>
                            <?php foreach ($errors as $err) echo "<p>" . htmlspecialchars($err) . "</p>"; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="st-alert ok">
                        <div><?= htmlspecialchars($success) ?></div>
                    </div>
                <?php endif; ?>

                <div class="st-grid">

                    <!-- LEFT: profile + upload -->
                    <div class="st-card st-profile">

                        <div class="st-avatar">
                            <img id="avatarPreview" src="../<?= htmlspecialchars($userProfile) ?>" alt="Profile Image">
                            <span class="st-verified">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
                                Verified
                            </span>
                        </div>

                        <h3><?= htmlspecialchars($userName) ?></h3>
                        <?php if ($showEmail !== ''): ?><span class="mail"><?= htmlspecialchars($showEmail) ?></span><?php endif; ?>

                        <hr class="st-divider">

                        <form action="#" method="post" enctype="multipart/form-data">
                            <label class="st-drop" for="profileFile">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><path d="m17 8-5-5-5 5" /><path d="M12 3v12" /></svg>
                                <div class="t">Choose a new photo</div>
                                <div class="s" id="fileName">JPG or PNG, max 2MB</div>
                                <input type="file" id="profileFile" name="profile_image" accept="image/png,image/jpeg" required>
                            </label>

                            <button class="btn btn-primary st-btn block mt-3">Upload</button>
                        </form>
                    </div>

                    <!-- RIGHT: security, wallet, danger -->
                    <div class="st-stack">

                        <!-- Password -->
                        <div class="st-card">
                            <div class="st-card-head">
                                <span class="ico">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" /></svg>
                                </span>
                                <div>
                                    <h4>Password Setting</h4>
                                    <p>Change the password you use to sign in.</p>
                                </div>
                            </div>

                            <form method="post">
                                <div class="st-field">
                                    <label class="lbl" for="oldPw">Old Password</label>
                                    <div class="st-input">
                                        <input type="password" id="oldPw" name="old_password" required>
                                        <button type="button" class="eye" data-eye="oldPw" aria-label="Show password">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" /><circle cx="12" cy="12" r="3" /></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="st-field">
                                    <label class="lbl" for="newPw">New Password</label>
                                    <div class="st-input">
                                        <input type="password" id="newPw" name="new_password" required>
                                        <button type="button" class="eye" data-eye="newPw" aria-label="Show password">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" /><circle cx="12" cy="12" r="3" /></svg>
                                        </button>
                                    </div>
                                    <div class="st-hint">At least 6 characters.</div>
                                </div>

                                <button class="btn btn-primary st-btn">Change</button>
                            </form>
                        </div>

                        <!-- Wallet -->
                        <div class="st-card">
                            <div class="st-card-head">
                                <span class="ico">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12V8H6a2 2 0 0 1 0-4h12v4" /><path d="M4 6v12a2 2 0 0 0 2 2h14v-4" /><path d="M18 12a2 2 0 0 0 0 4h4v-4z" /></svg>
                                </span>
                                <div>
                                    <h4>Connect Wallet</h4>
                                    <p>Link your wallet to this account.</p>
                                </div>
                            </div>

                            <div class="st-row">
                                <div class="txt">Use the button to connect your wallet.</div>
                                <button id="connectwalletBtn" type="button" class="btn btn-primary py-2 st-btn">Connect Wallet</button>
                            </div>
                        </div>

                        <!-- Account management -->
                        <div class="st-card st-danger">
                            <div class="st-card-head">
                                <span class="ico">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z" /><path d="M12 9v4" /><path d="M12 17h.01" /></svg>
                                </span>
                                <div>
                                    <h4>Account Management</h4>
                                    <p>Sign out of this device or delete your account.</p>
                                </div>
                            </div>

                            <div class="st-actions">
                                <button onclick="window.location.href='<?php echo $domain; ?>logout/';" class="btn btn-warning st-btn warn">Sign Out</button>
                                <button onclick="swal.fire({ title: 'Are you sure?', text: 'You will not be able to recover your account!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes, delete it!' });" class="btn btn-danger st-btn danger">Delete Account</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>


        <section id="modelWapper"></section>


    </div>
    <script src="<?php echo $domain ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@4.0.0/dist/jquery.min.js"></script>
    <script src="<?php echo $domain ?>js/wallet.js"></script>
    <!--  -->
    <!--  -->
    <script src="<?php echo $domain ?>js/scripts.js"></script>

    <script>
        // UI only: live avatar preview + file name, and show/hide password
        (function () {
            var file = document.getElementById('profileFile');
            if (file) {
                file.addEventListener('change', function () {
                    var f = this.files && this.files[0];
                    if (!f) return;
                    document.getElementById('fileName').textContent = f.name;
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('avatarPreview').src = e.target.result;
                    };
                    reader.readAsDataURL(f);
                });
            }

            document.querySelectorAll('[data-eye]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var input = document.getElementById(btn.getAttribute('data-eye'));
                    input.type = input.type === 'password' ? 'text' : 'password';
                });
            });
        })();
    </script>

</body>

</html>