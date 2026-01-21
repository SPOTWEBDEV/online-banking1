<style>
    :root {
        --bottom-nav-h: 70px;
    }

    /* ================= MOBILE BOTTOM NAV ================= */
    .mobile-bottom-nav {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        height: var(--bottom-nav-h);
        z-index: 1030;
        display: flex;
        justify-content: space-around;
        align-items: center;
        background: #2b2fe0;
    }

    .mobile-bottom-nav .mobile-nav-item {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        border-radius: 14px;
        color: #fff;
        text-decoration: none;
    }

    .mobile-bottom-nav .mobile-nav-item i {
        font-size: 20px;
    }

    /* prevent bottom nav covering content */
    @media (max-width: 991px) {

        .content-body,
        body.dashboard {
            padding-bottom: var(--bottom-nav-h);
        }
    }

    /* ================= HISTORY OFFCANVAS (BIGGER) ================= */
    #historyOffcanvas {
        height: 85vh;
    }

    #historyOffcanvas .offcanvas-body {
        overflow-y: auto;
    }

    /* ================= DESKTOP: EXPAND SIDEBAR WHEN HISTORY OPENS ================= */
    @media (min-width: 992px) {
        .sidebar {
            transition: width .25s ease;
        }

        .sidebar.history-open {
            width: 320px;
        }
    }

    /* ================= ✅ FIX: DESKTOP SIDEBAR SCROLL ================= */
    @media (min-width: 992px) {
        .sidebar {
            height: 100vh;
            overflow: hidden;
            /* sidebar itself no scroll */
        }

        .sidebar .menu {
            height: calc(100vh - 80px);
            /* 80px for logo area (adjust if needed) */
            overflow-y: auto;
            /* ✅ scroll here */
            overflow-x: hidden;
            padding-bottom: 20px;
        }

        /* Optional: smoother scroll */
        .sidebar .menu {
            scroll-behavior: smooth;
        }

        /* Optional: nicer scrollbar */
        .sidebar .menu::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar .menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.25);
            border-radius: 10px;
        }
    }
</style>

<!-- ========================= -->
<!-- SIDEBAR (DESKTOP) -->
<!-- ========================= -->
<div class="sidebar" id="desktopSidebar">
    <div class="brand-logo">
        <a class="full-logo" href="<?php echo $domain ?>/dashboard/">
            <img src="<?php echo $domain ?>/images/logoi.png" alt="" width="30">
        </a>
    </div>

    <div class="menu">
        <ul>
            <li>
                <a href="<?php echo $domain ?>/dashboard/">
                    <span><i class="fi fi-rr-home"></i></span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="<?= $domain ?>/deposits/">
                    <span><i class="fi fi-rr-bank"></i></span>
                    <span class="nav-text">Deposits</span>
                </a>
            </li>

            <li>
                <a href="<?= $domain ?>/transfer/">
                    <span><i class="fi fi-rr-exchange"></i></span>
                    <span class="nav-text">Transfers</span>
                </a>
            </li>

            <li>
                <a href="<?php echo $domain ?>/investment/">
                    <span><i class="fi fi-rr-chart-line-up"></i></span>
                    <span class="nav-text">Investment</span>
                </a>
            </li>

            <li>
                <a href="<?= $domain ?>/withdrawal/">
                    <span><i class="fi fi-rr-donate"></i></span>
                    <span class="nav-text">Withdrawals</span>
                </a>
            </li>

            <li>
                <a href="<?php echo $domain ?>/loan/">
                    <span><i class="fi fi-rr-hand-holding-usd"></i></span>
                    <span class="nav-text">Loan</span>
                </a>
            </li>

            <li>
                <a style="z-index: 5000 !important;" href="<?php echo $domain ?>/setting/">
                    <span><i class="fi fi-rr-user"></i></span>
                    <span class="nav-text">Setting</span>
                </a>
            </li>

            <!-- HISTORY (DESKTOP DROPDOWN) -->
            <li>
                <a href="#historyMenu"
                    data-bs-toggle="collapse"
                    aria-expanded="false"
                    id="historyToggle">
                    <span><i class="fi fi-rr-receipt"></i></span>
                    <span class="nav-text">History</span>
                </a>

                <ul class="collapse ms-4" id="historyMenu">
                    <li>
                        <a href="<?php echo $domain ?>/deposits/deposits_history/" class="list-group-item">
                            <i class="fi fi-rr-receipt me-2"></i> Deposits
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $domain ?>/Withdrawal/Withdrawal_history/" class="list-group-item">
                            <i class="fi fi-rr-money-bill-wave me-2"></i> Withdrawals
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $domain ?>/loan/loan_history/" class="list-group-item">
                            <i class="fi fi-rr-hand-holding-usd"></i> Loan
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $domain ?>/transfer/transfer_history/" class="list-group-item">
                            <i class="fi fi-rr-exchange me-2"></i> Tranfer
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo $domain ?>/investment/investment_plan/" class="list-group-item">
                            <i class="fi fi-rr-chart-line-up"></i> My Investment
                        </a>
                    </li>
                </ul>
            </li>
            <!-- END HISTORY -->
        </ul>
    </div>
</div>

<!-- ========================= -->
<!-- MOBILE BOTTOM NAV -->
<!-- ========================= -->
<div class="mobile-bottom-nav d-lg-none">
    <a class="mobile-nav-item" href="<?php echo $domain ?>/dashboard/">
        <i class="fi fi-rr-dashboard"></i>
    </a>

    <a class="mobile-nav-item" href="<?php echo $domain ?>/investment/">
        <i class="fi fi-rr-chart-line-up"></i>
    </a>

    <a class="mobile-nav-item" href="<?php echo $domain ?>/loan/">
        <i class="fi fi-rr-hand-holding-usd"></i>
    </a>

    <a class="mobile-nav-item" href="<?php echo $domain ?>/setting/">
        <i class="fi fi-sr-bullseye-arrow"></i>
    </a>

    <!-- History (opens bottom sheet) -->
    <a class="mobile-nav-item"
        href="javascript:void(0)"
        data-bs-toggle="offcanvas"
        data-bs-target="#historyOffcanvas">
        <i class="fi fi-rr-receipt"></i>
    </a>
</div>

<!-- ========================= -->
<!-- HISTORY OFFCANVAS (MOBILE) -->
<!-- ========================= -->
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="historyOffcanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">
        <div class="list-group">
            <a href="<?php echo $domain ?>/deposits/deposits_history/" class="list-group-item">
                <i class="fi fi-rr-receipt me-2"></i> Deposits
            </a>
            <a href="<?php echo $domain ?>/Withdrawal/Withdrawal_history/" class="list-group-item">
                <i class="fi fi-rr-money-bill-wave me-2"></i> Withdrawals
            </a>
            <a href="<?php echo $domain ?>/loan/loan_history/" class="list-group-item">
                <i class="fi fi-rr-hand-holding-usd"></i> Loan
            </a>
            <a href="<?php echo $domain ?>/transfer/transfer_history/" class="list-group-item">
                <i class="fi fi-rr-exchange me-2"></i> Tranfer
            </a>

            <a href="<?php echo $domain ?>/investment/investment_plan/" class="list-group-item">
                <i class="fi fi-rr-chart-line-up"></i> My Investment
            </a>
        </div>
    </div>
</div>

<!-- JS to expand sidebar width when History is opened (desktop only) -->
<script>
    (function() {
        var sidebar = document.getElementById('desktopSidebar');
        var historyMenu = document.getElementById('historyMenu');

        if (!sidebar || !historyMenu) return;

        function isDesktop() {
            return window.matchMedia('(min-width: 992px)').matches;
        }

        historyMenu.addEventListener('shown.bs.collapse', function() {
            if (isDesktop()) sidebar.classList.add('history-open');
        });

        historyMenu.addEventListener('hidden.bs.collapse', function() {
            if (isDesktop()) sidebar.classList.remove('history-open');
        });

        window.addEventListener('resize', function() {
            if (!isDesktop()) sidebar.classList.remove('history-open');
        });
    })();
</script>