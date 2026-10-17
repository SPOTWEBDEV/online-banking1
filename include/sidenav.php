<?php
// Works whether $domain ends with "/" or not
$base = rtrim($domain, '/') . '/';

// Current path, used to highlight the active menu item
$nav_path = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '', '/') . '/';

if (!function_exists('nav_active')) {
    function nav_active($slug, $path)
    {
        return strpos($path, '/' . $slug . '/') !== false ? ' active' : '';
    }
}

// [folder, label, icon]
$nav_groups = [
    'Overview' => [
        ['dashboard', 'Dashboard', 'fi fi-rr-home'],
    ],
    'Money' => [
        ['deposits', 'Deposits', 'fi fi-rr-bank'],
        ['transfer', 'International Money Transfers', 'fi fi-rr-exchange'],
        ['withdrawal', 'Withdrawals', 'fi fi-rr-donate'],
    ],
    'Grow' => [
        ['investment', 'Investment Plan', 'fi fi-rr-chart-line-up'],
        ['loan', 'Loan and Mortgage', 'fi fi-rr-hand-holding-usd'],
    ],
    'Account' => [
        ['setting', 'Settings', 'fi fi-rr-user'],
    ],
];
?>

<style>
    .sb-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .5);
        z-index: 1090;
        opacity: 0;
        visibility: hidden;
        transition: opacity .2s, visibility .2s;
    }

    .sb-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .app-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: var(--sb-w, 264px);
        background: #fff;
        border-right: 1px solid var(--app-border, #e5e7eb);
        z-index: 1100;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        padding: 0 16px 16px;
        transition: transform .25s ease;
    }

    /* Brand */
    .app-sidebar .sb-brand {
        height: var(--hd-h, 68px);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
        padding: 0 8px;
        margin-bottom: 8px;
    }

    .app-sidebar .sb-brand a {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        min-width: 0;
    }

    .app-sidebar .sb-brand img {
        width: 34px;
        height: 34px;
        object-fit: contain;
        border-radius: 8px;
    }

    .app-sidebar .sb-brand .name {
        font-size: 17px;
        font-weight: 800;
        color: var(--app-text, #111827);
        letter-spacing: -.01em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .app-sidebar .sb-close {
        display: none;
        width: 34px;
        height: 34px;
        border: 0;
        background: #f3f4f6;
        border-radius: 8px;
        color: #374151;
        cursor: pointer;
        align-items: center;
        justify-content: center;
    }

    /* Menu */
    .app-sidebar .sb-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #9ca3af;
        padding: 16px 12px 8px;
    }

    .app-sidebar ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .app-sidebar li {
        margin-bottom: 2px;
    }

    .app-sidebar .sb-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        color: #4b5563;
        text-decoration: none;
        transition: background .15s, color .15s;
    }

    .app-sidebar .sb-link .ic {
        width: 20px;
        display: inline-flex;
        justify-content: center;
        font-size: 17px;
        line-height: 1;
        color: #9ca3af;
        transition: color .15s;
    }

    .app-sidebar .sb-link:hover {
        background: #f3f4f6;
        color: var(--app-text, #111827);
    }

    .app-sidebar .sb-link:hover .ic {
        color: #4b5563;
    }

    .app-sidebar .sb-link.active {
        background: var(--app-primary-soft, #eff6ff);
        color: var(--app-primary, #2563eb);
        font-weight: 600;
    }

    .app-sidebar .sb-link.active .ic {
        color: var(--app-primary, #2563eb);
    }

    .app-sidebar .sb-link.active::before {
        content: "";
        position: absolute;
        left: -16px;
        top: 8px;
        bottom: 8px;
        width: 3px;
        border-radius: 0 4px 4px 0;
        background: var(--app-primary, #2563eb);
    }

    /* Help card */
    .app-sidebar .sb-help {
        margin-top: auto;
        padding: 18px;
        border-radius: 14px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: #fff;
    }

    .app-sidebar .sb-help h6 {
        margin: 0 0 4px;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
    }

    .app-sidebar .sb-help p {
        margin: 0 0 14px;
        font-size: 12.5px;
        opacity: .85;
        line-height: 1.45;
    }

    .app-sidebar .sb-help button {
        width: 100%;
        border: 0;
        background: #fff;
        color: #1d4ed8;
        font-weight: 700;
        font-size: 13px;
        padding: 9px 12px;
        border-radius: 8px;
        cursor: pointer;
    }

    .app-sidebar .sb-help button:hover {
        background: #eff6ff;
    }

    /* Mobile: off-canvas drawer */
    @media (max-width: 991px) {
        .app-sidebar {
            transform: translateX(-100%);
            box-shadow: none;
        }

        .app-sidebar.open {
            transform: translateX(0);
            box-shadow: 0 0 40px rgba(0, 0, 0, .25);
        }

        .app-sidebar .sb-close {
            display: inline-flex;
        }

        .app-sidebar .sb-link.active::before {
            left: -16px;
        }
    }
</style>

<div class="sb-overlay" id="sbOverlay"></div>

<div class="app-sidebar" id="desktopSidebar">

    <div class="sb-brand">
        <a href="<?= $base ?>dashboard/">
            <img src="<?= $base ?>images/favicon.png" alt="">
            <span class="name"><?= htmlspecialchars($sitename ?? '') ?></span>
        </a>
        <button type="button" class="sb-close" id="sbClose" aria-label="Close menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18" /></svg>
        </button>
    </div>

    <?php foreach ($nav_groups as $label => $items): ?>
        <div class="sb-label"><?= htmlspecialchars($label) ?></div>
        <ul>
            <?php foreach ($items as [$slug, $text, $icon]): ?>
                <li>
                    <a class="sb-link<?= nav_active($slug, $nav_path) ?>" href="<?= $base . $slug ?>/">
                        <span class="ic"><i class="<?= $icon ?>"></i></span>
                        <span class="nav-text"><?= htmlspecialchars($text) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>

    <div class="sb-help">
        <h6>Need help?</h6>
        <p>Chat with our support team, we usually reply within minutes.</p>
        <button type="button" onclick="if (window.jivo_api) { jivo_api.open(); }">Chat with support</button>
    </div>

</div>

<script>
    // Mobile drawer (the hamburger button lives in header.php)
    (function () {
        var sb = document.getElementById('desktopSidebar');
        var ov = document.getElementById('sbOverlay');
        var openBtn = document.getElementById('menuToggle');
        var closeBtn = document.getElementById('sbClose');

        function openSb() {
            sb.classList.add('open');
            ov.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSb() {
            sb.classList.remove('open');
            ov.classList.remove('show');
            document.body.style.overflow = '';
        }

        if (openBtn) openBtn.addEventListener('click', openSb);
        if (closeBtn) closeBtn.addEventListener('click', closeSb);
        ov.addEventListener('click', closeSb);
        sb.querySelectorAll('.sb-link').forEach(function (a) {
            a.addEventListener('click', closeSb);
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSb();
        });
    })();
</script>

<script src="//code.jivosite.com/widget/9w7TI99TE1" async></script>