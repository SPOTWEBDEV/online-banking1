<?php
include("../../../server/connection.php");




include("../../controllers/add-investment.php");


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $sitename ?> || Add Investment Plan</title>
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
            --s-radius: 14px;
        }

        .saas .back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .875rem;
            text-decoration: none;
            margin-bottom: 14px;
        }

        .saas .back:hover {
            color: var(--s-primary);
        }

        .saas .page-head h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -.01em;
        }

        .saas .page-head p {
            margin: 4px 0 24px;
            font-size: .925rem;
        }

        .saas .panel {
            background: var(--s-surface);
            border: 1px solid var(--s-border);
            border-radius: var(--s-radius);
            padding: 24px;
            margin-bottom: 20px;
        }

        .saas .panel-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 4px;
            font-weight: 600;
            font-size: 1rem;
        }

        .saas .panel-title .num {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            background: var(--s-primary-soft);
            /* color: var(--s-primary); */
            font-size: .8rem;
            font-weight: 700;
        }

        .saas .panel-sub {
            font-size: .85rem;
            margin: 0 0 20px 36px;
        }

        .saas label.lbl {
            font-size: .85rem;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .saas .hint {
            font-size: .78rem;
            margin-top: 6px;
        }

        .saas .form-control,
        .saas .input-group-text {
            border-radius: 10px;
            padding: 10px 14px;
            font-size: .92rem;
            border-color: var(--s-border);
        }

        .saas .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        .saas .input-group .input-group-text {
            border-radius: 10px 0 0 10px;
            background: transparent;
        }

        .saas .input-group .form-control.has-suffix {
            border-radius: 10px 0 0 10px;
        }

        .saas .input-group .input-group-text.suffix {
            border-radius: 0 10px 10px 0;
        }

        .saas .form-control:focus {
            border-color: var(--s-primary);
            box-shadow: 0 0 0 3px var(--s-primary-soft);
        }

        .saas .actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .saas .btn-saas {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--s-primary);
            color: #fff;
            border: 0;
            padding: 11px 22px;
            border-radius: 10px;
            font-weight: 600;
            font-size: .92rem;
            transition: transform .15s, box-shadow .15s, background .15s;
            box-shadow: 0 1px 2px rgba(79, 70, 229, .3);
            cursor: pointer;
        }

        .saas .btn-saas:hover {
            background: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, .3);
        }

        .saas .btn-ghost {
            display: inline-flex;
            align-items: center;
            padding: 11px 22px;
            border-radius: 10px;
            font-weight: 600;
            font-size: .92rem;
            border: 1px solid var(--s-border);
            color: inherit;
            text-decoration: none;
            background: transparent;
        }

        .saas .btn-ghost:hover {
            border-color: var(--s-primary);
            color: var(--s-primary);
        }

        /* Live preview */
        .saas .sticky {
            position: sticky;
            top: 90px;
        }

        .saas .preview {
            background: var(--s-surface);
            border: 1px solid var(--s-border);
            border-radius: var(--s-radius);
            padding: 20px;
        }

        .saas .preview .tag {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            font-weight: 600;
            color: var(--s-muted);
            margin-bottom: 14px;
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
        }

        .saas .plan-name {
            font-weight: 600;
            margin: 0;
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
        }

        .saas .rows {
            margin-top: 18px;
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
    </style>
</head>

<body class="dashboard">
    <div id="main-wrapper">
        <?php include("../../include/nav.php") ?>
        <?php include("../../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container saas">

                <a href="<?php echo $domain ?>/admin/investment/" class="back">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7" /></svg>
                    Back to plans
                </a>

                <div class="page-head">
                    <h2>Add investment plan</h2>
                    <p>Set the duration, returns and investment limits for a new plan.</p>
                </div>

                <form method="POST" id="planForm">
                    <div class="row g-4">

                        <!-- LEFT: form -->
                        <div class="col-lg-8">

                            <!-- 1. Basics -->
                            <div class="panel">
                                <div class="panel-title"><span class="num">1</span> Plan details</div>
                                <p class="panel-sub">Give the plan a name and set how long it runs.</p>

                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label class="lbl" for="plan_name">Plan name</label>
                                        <input type="text" id="plan_name" name="plan_name" class="form-control" placeholder="Gold Plan" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="lbl" for="duration">Duration</label>
                                        <div class="input-group">
                                            <input type="number" id="duration" name="duration" class="form-control has-suffix" placeholder="30" min="1" required>
                                            <span class="input-group-text suffix">days</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Returns -->
                            <div class="panel">
                                <div class="panel-title"><span class="num">2</span> Returns</div>
                                <p class="panel-sub">How much the investor earns from this plan.</p>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="lbl" for="profit_per_day">Profit per day</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" id="profit_per_day" name="profit_per_day" class="form-control" placeholder="2.50" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="lbl" for="total_profit">Total profit</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" id="total_profit" name="total_profit" class="form-control" placeholder="75.00" min="0" required>
                                        </div>
                                        <div class="hint" id="autoHint">Auto-calculated from profit per day × duration. You can still edit it.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Limits -->
                            <div class="panel">
                                <div class="panel-title"><span class="num">3</span> Investment limits</div>
                                <p class="panel-sub">The range a user can invest into this plan.</p>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="lbl" for="min_amount">Minimum amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" id="min_amount" name="min_amount" class="form-control" placeholder="10" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="lbl" for="max_amount">Maximum amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" id="max_amount" name="max_amount" class="form-control" placeholder="1000" min="0" required>
                                        </div>
                                        <div class="hint text-danger" id="limitErr" style="display:none">Maximum must be greater than minimum.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="actions">
                                <a href="<?php echo $domain ?>/admin/investment/" class="btn-ghost">Cancel</a>
                                <button type="submit" name="investment" value="1" class="btn-saas">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
                                    Save investment plan
                                </button>
                            </div>
                        </div>

                        <!-- RIGHT: live preview -->
                        <div class="col-lg-4">
                            <div class="sticky">
                                <div class="preview">
                                    <div class="tag">Live preview</div>

                                    <div class="plan-top">
                                        <span class="plan-icon">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17l6-6 4 4 8-8" /><path d="M14 7h7v7" /></svg>
                                        </span>
                                        <div>
                                            <h6 class="plan-name" id="pvName">Plan name</h6>
                                            <span class="badge-soft"><span id="pvDuration">0</span> days</span>
                                        </div>
                                    </div>

                                    <div class="big">$<span id="pvDaily">0.00</span> <small>/ day</small></div>

                                    <div class="rows">
                                        <div><span>Total profit</span><span>$<span id="pvTotal">0.00</span></span></div>
                                        <div><span>Minimum</span><span>$<span id="pvMin">0.00</span></span></div>
                                        <div><span>Maximum</span><span>$<span id="pvMax">0.00</span></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

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
        (function () {
            var $ = function (id) { return document.getElementById(id); };
            var f = {
                name: $('plan_name'), duration: $('duration'), daily: $('profit_per_day'),
                total: $('total_profit'), min: $('min_amount'), max: $('max_amount')
            };
            var totalTouched = false;
            var money = function (v) { return (parseFloat(v) || 0).toFixed(2); };

            function render() {
                $('pvName').textContent = f.name.value.trim() || 'Plan name';
                $('pvDuration').textContent = parseInt(f.duration.value) || 0;
                $('pvDaily').textContent = money(f.daily.value);
                $('pvTotal').textContent = money(f.total.value);
                $('pvMin').textContent = money(f.min.value);
                $('pvMax').textContent = money(f.max.value);

                var bad = f.min.value !== '' && f.max.value !== '' && parseFloat(f.max.value) <= parseFloat(f.min.value);
                $('limitErr').style.display = bad ? 'block' : 'none';
            }

            function autoTotal() {
                if (totalTouched) return;
                var d = parseFloat(f.daily.value), n = parseInt(f.duration.value);
                if (d > 0 && n > 0) f.total.value = (d * n).toFixed(2);
            }

            f.total.addEventListener('input', function () { totalTouched = this.value !== ''; });
            ['duration', 'daily'].forEach(function (k) {
                f[k].addEventListener('input', function () { autoTotal(); render(); });
            });
            ['name', 'total', 'min', 'max'].forEach(function (k) {
                f[k].addEventListener('input', render);
            });

            $('planForm').addEventListener('submit', function (e) {
                if (parseFloat(f.max.value) <= parseFloat(f.min.value)) {
                    e.preventDefault();
                    $('limitErr').style.display = 'block';
                    f.max.focus();
                }
            });

            render();
        })();
    </script>
</body>

</html>