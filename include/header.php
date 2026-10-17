<?php

$user_id = $_SESSION['user_id'];

/* =========================
   FETCH USER DATA
   (fetched here, after any POST on the page, so the balance is always fresh)
   ========================= */
$sql2 = "SELECT * FROM users WHERE id = ? LIMIT 1";
$stmt2 = mysqli_prepare($connection, $sql2);
mysqli_stmt_bind_param($stmt2, "i", $user_id);
mysqli_stmt_execute($stmt2);
$result = mysqli_stmt_get_result($stmt2);
$user   = mysqli_fetch_assoc($result) ?: [];
mysqli_stmt_close($stmt2);

// Works whether $domain ends with "/" or not
$base = rtrim($domain, '/') . '/';

$userName    = $user['fullname'] ?? 'User';
$userEmail   = $user['email'] ?? '';
$userProfile = $user['user_profile'] ?? '';
$firstName   = explode(' ', trim($userName))[0] ?: 'there';
$headerBal   = isset($user['balance']) ? number_format((float) $user['balance'], 2) : null;

// ✅ Default avatar fallback
if (empty($userProfile)) {
    $userProfile = $base . "images/avatar/avatar.svg";
} else {
    $userProfile = $base . ltrim($userProfile, '/');
}

// ⚠️ Change this to your real logout route
$logoutUrl = $base . 'logout/';
?>

<style>
    :root {
        --sb-w: 264px;
        --hd-h: 68px;
        --app-primary: #2563eb;
        --app-primary-soft: #eff6ff;
        --app-text: #111827;
        --app-muted: #6b7280;
        --app-border: #e5e7eb;
    }

    /* Make room for the fixed sidebar + header (overrides the template's own offsets) */
    body.dashboard #main-wrapper {
        padding: 0 !important;
    }

    body.dashboard .content-body {
        margin-left: var(--sb-w) !important;
        padding-top: calc(var(--hd-h) + 16px) !important;
    }

    /* ---------- Header ---------- */
    .app-header {
        position: fixed;
        top: 0;
        left: var(--sb-w);
        right: 0;
        height: var(--hd-h);
        z-index: 1000;
        background: rgba(255, 255, 255, .92);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--app-border);
    }

    .app-header .hd-inner {
        height: 100%;
        padding: 0 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .app-header .hd-left {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .app-header .menu-toggle {
        display: none;
        width: 40px;
        height: 40px;
        border: 1px solid var(--app-border);
        background: #fff;
        border-radius: 10px;
        align-items: center;
        justify-content: center;
        color: var(--app-text);
        cursor: pointer;
        flex-shrink: 0;
    }

    .app-header .menu-toggle:hover {
        border-color: var(--app-primary);
        color: var(--app-primary);
    }

    .app-header .menu-toggle svg,
    .app-header .pm-item svg {
        display: block;
        fill: none;
        stroke: currentColor;
        flex-shrink: 0;
    }

    .app-header .hd-brand {
        display: none;
    }

    .app-header .hd-welcome small {
        display: block;
        font-size: 12px;
        color: var(--app-muted);
        line-height: 1.2;
    }

    .app-header .hd-welcome strong {
        display: block;
        font-size: 16px;
        font-weight: 700;
        color: var(--app-text);
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 38vw;
    }

    .app-header .hd-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Balance pill */
    .app-header .bal-pill {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        border-radius: 999px;
        background: var(--app-primary-soft);
        color: var(--app-primary);
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .app-header .bal-pill span {
        color: var(--app-muted);
        font-weight: 500;
    }

    .app-header .bal-pill b {
        font-weight: 800;
    }

    /* Profile dropdown (plain JS - no Bootstrap needed) */
    .app-header .profile {
        position: relative;
    }

    .app-header .profile-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 4px 10px 4px 4px;
        border: 1px solid var(--app-border);
        background: #fff;
        border-radius: 999px;
        cursor: pointer;
        transition: border-color .15s, box-shadow .15s;
    }

    .app-header .profile-btn:hover,
    .app-header .profile-btn[aria-expanded="true"] {
        border-color: var(--app-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    .app-header .profile-btn img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        object-position: 50% 20%;
        display: block;
    }

    .app-header .profile-btn .pname {
        font-size: 14px;
        font-weight: 600;
        color: var(--app-text);
        max-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .app-header .profile-btn svg {
        color: var(--app-muted);
        transition: transform .2s;
    }

    .app-header .profile-btn[aria-expanded="true"] svg {
        transform: rotate(180deg);
    }

    .app-header .profile-menu {
        position: absolute;
        right: 0;
        top: calc(100% + 10px);
        width: 260px;
        background: #fff;
        border: 1px solid var(--app-border);
        border-radius: 14px;
        box-shadow: 0 20px 45px rgba(15, 23, 42, .14);
        padding: 8px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-6px);
        transition: opacity .15s, transform .15s, visibility .15s;
        z-index: 1200;
    }

    .app-header .profile-menu.open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .app-header .pm-user {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-bottom: 1px solid var(--app-border);
        margin-bottom: 6px;
    }

    .app-header .pm-user img {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        object-position: 50% 20%;
    }

    .app-header .pm-user div {
        min-width: 0;
    }

    .app-header .pm-user h6 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: var(--app-text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .app-header .pm-user span {
        display: block;
        font-size: 12px;
        color: var(--app-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .app-header .pm-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        text-decoration: none;
        transition: background .15s, color .15s;
    }

    .app-header .pm-item i {
        font-size: 16px;
        line-height: 1;
        display: inline-flex;
    }

    .app-header .pm-item:hover {
        background: #f3f4f6;
        color: var(--app-text);
    }

    .app-header .pm-item.logout {
        color: #dc2626;
    }

    .app-header .pm-item.logout:hover {
        background: rgba(220, 38, 38, .08);
        color: #b91c1c;
    }

    /* ---------- Tablet / mobile ---------- */
    @media (max-width: 991px) {
        body.dashboard .content-body {
            margin-left: 0 !important;
        }

        .app-header {
            left: 0;
        }

        .app-header .hd-inner {
            padding: 0 16px;
        }

        .app-header .menu-toggle {
            display: inline-flex;
        }
    }

    /* Phones: drop the greeting and balance, show the logo instead */
    @media (max-width: 767px) {
        .app-header .hd-welcome,
        .app-header .bal-pill {
            display: none;
        }

        .app-header .hd-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            min-width: 0;
        }

        .app-header .hd-brand img {
            width: 30px;
            height: 30px;
            object-fit: contain;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .app-header .hd-brand span {
            font-size: 16px;
            font-weight: 800;
            color: var(--app-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 45vw;
        }
    }

    @media (max-width: 575px) {
        .app-header .profile-btn .pname,
        .app-header .profile-btn svg {
            display: none;
        }

        .app-header .profile-btn {
            padding: 3px;
        }

        .app-header .bal-pill span {
            display: none;
        }

        .app-header .hd-welcome strong {
            max-width: 30vw;
        }
    }
</style>

<div class="app-header">
    <div class="hd-inner">

        <div class="hd-left">
            <!-- opens the sidebar on mobile (handled in sidenav.php) -->
            <button type="button" class="menu-toggle" id="menuToggle" aria-label="Open menu">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h10" /></svg>
            </button>

            <!-- shown on phones only -->
            <a class="hd-brand" href="<?= $base ?>dashboard/">
                <img src="<?= $base ?>images/logo.png" alt="">
                <span><?= htmlspecialchars($sitename ?? '') ?></span>
            </a>

            <div class="hd-welcome">
                <small>Welcome back</small>
                <strong><?= htmlspecialchars($firstName) ?></strong>
            </div>
        </div>

        <div class="hd-right">

            <?php if ($headerBal !== null): ?>
                <div class="bal-pill">
                    <span>Balance</span>
                    <b>$<?= $headerBal ?></b>
                </div>
            <?php endif; ?>

            <div class="profile">
                <button type="button" class="profile-btn" id="profileBtn" aria-haspopup="true" aria-expanded="false">
                    <img src="<?= htmlspecialchars($userProfile) ?>" alt="Profile image">
                    <span class="pname"><?= htmlspecialchars($userName) ?></span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6" /></svg>
                </button>

                <div class="profile-menu" id="profileMenu" role="menu">
                    <div class="pm-user">
                        <img src="<?= htmlspecialchars($userProfile) ?>" alt="">
                        <div>
                            <h6><?= htmlspecialchars($userName) ?></h6>
                            <?php if ($userEmail !== ''): ?><span><?= htmlspecialchars($userEmail) ?></span><?php endif; ?>
                        </div>
                    </div>

                    <a class="pm-item" href="<?= $base ?>setting/" role="menuitem">
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/></svg> Settings
                    </a>
                    <a class="pm-item logout" href="<?= htmlspecialchars($logoutUrl) ?>" role="menuitem">
                        <svg width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg> Logout
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Avatar dropdown - plain JS so it works regardless of the Bootstrap version
    (function () {
        var btn = document.getElementById('profileBtn');
        var menu = document.getElementById('profileMenu');
        if (!btn || !menu) return;

        function closeMenu() {
            menu.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = menu.classList.toggle('open');
            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function (e) {
            if (!menu.contains(e.target)) closeMenu();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeMenu();
        });
    })();
</script>