<?php
include("../../server/connection.php");
include("../../server/auth/admin.php");

/* ---------- Delete (prepared statement + redirect so refresh doesn't re-run it) ---------- */
if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    $del = mysqli_prepare($connection, "DELETE FROM investment_plans WHERE id = ?");
    mysqli_stmt_bind_param($del, "i", $delete_id);
    $ok = mysqli_stmt_execute($del);
    mysqli_stmt_close($del);

    $self = strtok($_SERVER['REQUEST_URI'], '?');
    header("Location: " . $self . "?status=" . ($ok ? "deleted" : "error"));
    exit;
}

$status = $_GET['status'] ?? null;

/* ---------- Fetch plans ---------- */
$plans = [];
$result = mysqli_query($connection, "SELECT * FROM investment_plans ORDER BY id DESC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $plans[] = $row;
    }
}

/* ---------- Quick stats ---------- */
$total_plans   = count($plans);
$avg_duration  = $total_plans ? round(array_sum(array_column($plans, 'duration')) / $total_plans) : 0;
$top_daily     = $total_plans ? max(array_column($plans, 'profit_per_day')) : 0;
$top_max_amt   = $total_plans ? max(array_column($plans, 'max_amount')) : 0;

function usd($n) { return '$' . number_format((float) $n, 2); }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $sitename ?> || Investment Plans</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/logo.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>/vendor/toastr/toastr.min.css">

    <style>
        .saas {
            --s-surface: var(--bs-body-bg, #fff);
            --s-border: var(--bs-border-color, #e5e7eb);
            --s-muted: var(--bs-secondary-color, #6b7280);
            --s-primary: #4f46e5;
            --s-primary-soft: rgba(79, 70, 229, .10);
            --s-success: #16a34a;
            --s-success-soft: rgba(22, 163, 74, .12);
            --s-danger: #dc2626;
            --s-danger-soft: rgba(220, 38, 38, .10);
            --s-radius: 14px;
        }

        .saas .page-head {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .saas .page-head h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -.01em;
        }

        .saas .page-head p {
            margin: 4px 0 0;
            color: var(--s-muted);
            font-size: .925rem;
        }

        .saas .btn-saas {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--s-primary);
            color: #fff;
            border: 0;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            font-size: .9rem;
            text-decoration: none;
            transition: transform .15s, box-shadow .15s, background .15s;
            box-shadow: 0 1px 2px rgba(79, 70, 229, .3);
        }

        .saas .btn-saas:hover {
            background: #4338ca;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, .3);
        }

        .saas .stat {
            background: var(--s-surface);
            border: 1px solid var(--s-border);
            border-radius: var(--s-radius);
            padding: 18px 20px;
            height: 100%;
        }

        .saas .stat .label {
            color: var(--s-muted);
            font-size: .8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .saas .stat .value {
            font-size: 1.6rem;
            font-weight: 700;
            margin-top: 6px;
            letter-spacing: -.02em;
        }

        .saas .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            margin: 28px 0 16px;
        }

        .saas .toolbar h5 {
            margin: 0;
            font-weight: 600;
        }

        .saas .search {
            position: relative;
            min-width: 240px;
        }

        .saas .search svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--s-muted);
        }

        .saas .search input {
            width: 100%;
            padding: 9px 12px 9px 38px;
            border: 1px solid var(--s-border);
            border-radius: 10px;
            background: var(--s-surface);
            color: inherit;
            font-size: .9rem;
            outline: none;
        }

        .saas .search input:focus {
            border-color: var(--s-primary);
            box-shadow: 0 0 0 3px var(--s-primary-soft);
        }

        .saas .plan-card {
            background: var(--s-surface);
            border: 1px solid var(--s-border);
            border-radius: var(--s-radius);
            padding: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: box-shadow .2s, transform .2s, border-color .2s;
        }

        .saas .plan-card:hover {
            transform: translateY(-3px);
            border-color: var(--s-primary);
            box-shadow: 0 12px 28px rgba(15, 23, 42, .08);
        }

        .saas .plan-top {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .saas .plan-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: var(--s-primary-soft);
            color: var(--s-primary);
            flex-shrink: 0;
        }

        .saas .plan-name {
            font-weight: 600;
            font-size: 1.05rem;
            margin: 0;
            line-height: 1.2;
        }

        .saas .badge-soft {
            display: inline-block;
            font-size: .72rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 999px;
            background: var(--s-success-soft);
            color: var(--s-success);
            margin-top: 4px;
        }

        .saas .big {
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: -.02em;
            line-height: 1;
        }

        .saas .big small {
            font-size: .8rem;
            font-weight: 500;
            color: var(--s-muted);
            letter-spacing: 0;
        }

        .saas .rows {
            margin: 18px 0;
            border-top: 1px dashed var(--s-border);
            padding-top: 14px;
            display: grid;
            gap: 10px;
        }

        .saas .rows div {
            display: flex;
            justify-content: space-between;
            font-size: .88rem;
        }

        .saas .rows span:first-child {
            color: var(--s-muted);
        }

        .saas .rows span:last-child {
            font-weight: 600;
        }

        .saas .plan-foot {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .saas .plan-foot .date {
            font-size: .78rem;
            color: var(--s-muted);
        }

        .saas .btn-del {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 0;
            background: var(--s-danger-soft);
            color: var(--s-danger);
            padding: 7px 12px;
            border-radius: 8px;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s, color .15s;
        }

        .saas .btn-del:hover {
            background: var(--s-danger);
            color: #fff;
        }

        .saas .empty {
            text-align: center;
            background: var(--s-surface);
            border: 1px dashed var(--s-border);
            border-radius: var(--s-radius);
            padding: 56px 20px;
        }

        .saas .empty .ring {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            margin: 0 auto 16px;
            display: grid;
            place-items: center;
            background: var(--s-primary-soft);
            color: var(--s-primary);
        }

        .saas .empty h5 {
            font-weight: 600;
        }

        .saas .empty p {
            color: var(--s-muted);
            margin-bottom: 20px;
        }

        .saas .alert-soft {
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: .9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .saas .alert-soft.ok {
            background: var(--s-success-soft);
            color: var(--s-success);
        }

        .saas .alert-soft.err {
            background: var(--s-danger-soft);
            color: var(--s-danger);
        }

        .saas .alert-soft button {
            background: none;
            border: 0;
            color: inherit;
            font-size: 1.2rem;
            line-height: 1;
            cursor: pointer;
        }
    </style>
</head>

<body class="dashboard">
    <div id="main-wrapper">
        <?php include("../include/nav.php") ?>
        <?php include("../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container saas">

                <!-- Page header -->
                <div class="page-head">
                    <div>
                        <h2>Investment Plans</h2>
                        <p>Create and manage the plans your users can invest in.</p>
                    </div>
                    <a href="./add/" class="btn-saas">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14" /></svg>
                        New plan
                    </a>
                </div>

                <!-- Flash message -->
                <?php if ($status === 'deleted'): ?>
                    <div class="alert-soft ok">
                        <span>Investment plan deleted successfully.</span>
                        <button type="button" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                <?php elseif ($status === 'error'): ?>
                    <div class="alert-soft err">
                        <span>Failed to delete the investment plan. Please try again.</span>
                        <button type="button" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                <?php endif; ?>

                <!-- Stats -->
                <div class="row g-3">
                    <div class="col-6 col-lg-3">
                        <div class="stat">
                            <div class="label">Total plans</div>
                            <div class="value"><?php echo $total_plans; ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat">
                            <div class="label">Avg. duration</div>
                            <div class="value"><?php echo $avg_duration; ?> <small class="fs-6 fw-normal text-muted">days</small></div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat">
                            <div class="label">Top daily profit</div>
                            <div class="value"><?php echo usd($top_daily); ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat">
                            <div class="label">Highest max amount</div>
                            <div class="value"><?php echo usd($top_max_amt); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Toolbar -->
                <div class="toolbar">
                    <h5>All plans</h5>
                    <?php if ($total_plans > 0): ?>
                        <div class="search">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" /></svg>
                            <input type="text" id="planSearch" placeholder="Search plans...">
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Plans -->
                <?php if ($total_plans === 0): ?>

                    <div class="empty">
                        <div class="ring">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17l6-6 4 4 8-8" /><path d="M14 7h7v7" /></svg>
                        </div>
                        <h5>No investment plans yet</h5>
                        <p>Add your first plan so users can start investing.</p>
                        <a href="./add/" class="btn-saas">Add investment plan</a>
                    </div>

                <?php else: ?>

                    <div class="row g-3" id="planGrid">
                        <?php foreach ($plans as $row): ?>
                            <div class="col-12 col-md-6 col-xl-4 plan-col" data-name="<?php echo strtolower(htmlspecialchars($row['plan_name'])); ?>">
                                <div class="plan-card">

                                    <div class="plan-top">
                                        <span class="plan-icon">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17l6-6 4 4 8-8" /><path d="M14 7h7v7" /></svg>
                                        </span>
                                        <div>
                                            <h6 class="plan-name"><?php echo htmlspecialchars($row['plan_name']); ?></h6>
                                            <span class="badge-soft"><?php echo (int) $row['duration']; ?> days</span>
                                        </div>
                                    </div>

                                    <div class="big">
                                        <?php echo usd($row['profit_per_day']); ?> <small>/ day</small>
                                    </div>

                                    <div class="rows">
                                        <div><span>Total profit</span><span><?php echo usd($row['total_profit']); ?></span></div>
                                        <div><span>Minimum</span><span><?php echo usd($row['min_amount'] ?? 0); ?></span></div>
                                        <div><span>Maximum</span><span><?php echo usd($row['max_amount'] ?? 0); ?></span></div>
                                    </div>

                                    <div class="plan-foot">
                                        <span class="date">
                                            <?php echo !empty($row['created_at']) ? 'Added ' . date('M j, Y', strtotime($row['created_at'])) : ''; ?>
                                        </span>
                                        <button type="button" class="btn-del"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-id="<?php echo (int) $row['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($row['plan_name']); ?>">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6" /></svg>
                                            Delete
                                        </button>
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <p id="noMatch" class="text-center text-muted py-4" style="display:none">No plans match your search.</p>

                <?php endif; ?>

            </div>
        </div>

        <!-- Delete confirmation modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:16px">
                    <div class="modal-body text-center p-4">
                        <div style="width:56px;height:56px;border-radius:50%;background:rgba(220,38,38,.1);color:#dc2626;display:grid;place-items:center;margin:0 auto 14px">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" /></svg>
                        </div>
                        <h5 class="mb-2">Delete this plan?</h5>
                        <p class="text-muted mb-4">
                            <strong id="deleteName"></strong> will be permanently removed. This can't be undone.
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                            <a href="#" id="deleteConfirm" class="btn btn-danger px-4">Yes, delete</a>
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
                                <a href="#"><?php echo $sitename ?></a> I All Rights Reserved
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

    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $domain ?>/js/scripts.js"></script>

    <script>
        // Fill the delete modal with the clicked plan
        document.getElementById('deleteModal').addEventListener('show.bs.modal', function (e) {
            var btn = e.relatedTarget;
            document.getElementById('deleteName').textContent = btn.getAttribute('data-name');
            document.getElementById('deleteConfirm').href = '?delete_id=' + btn.getAttribute('data-id');
        });

        // Live search
        var search = document.getElementById('planSearch');
        if (search) {
            search.addEventListener('input', function () {
                var q = this.value.trim().toLowerCase(), shown = 0;
                document.querySelectorAll('.plan-col').forEach(function (col) {
                    var match = col.getAttribute('data-name').indexOf(q) !== -1;
                    col.style.display = match ? '' : 'none';
                    if (match) shown++;
                });
                document.getElementById('noMatch').style.display = shown ? 'none' : 'block';
            });
        }
    </script>
</body>

</html>