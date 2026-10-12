<?php
/* =========================================================
   SETTINGS — edit these to change branding
   ========================================================= */
$siteTitle   = 'Dashboard Panel';
$companyName = 'Ashford Communities';
$pageHeading = 'Ashford Communities | Analytics & Dashboards';

/* =========================================================
   DASHBOARDS — add a new block here to add a new button,
   sidebar link and page. The key becomes the URL:
   'sales-dashboard'  ->  https://your-site.vercel.app/sales-dashboard
   Icons: https://icons.getbootstrap.com  (use the name without "bi-")
   ========================================================= */
$dashboards = [
    'sales-dashboard' => [
        'title' => 'Sales Dashboard',
        'icon'  => 'currency-dollar',
        'color' => '#5b7cfa',
        'src'   => 'https://app.powerbi.com/view?r=eyJrIjoiOTI0MTdmOWUtNGJmNS00MGE1LTgyNmEtYTI1ZGIzNmQwOWY3IiwidCI6IjU3NDJkYTBmLTY1NmUtNDk0OS05ZTk5LTczOWIyOTI0NmU5MyJ9',
    ],
    /* Example — remove the comment marks to add another one:
    'marketing-dashboard' => [
        'title' => 'Marketing Dashboard',
        'icon'  => 'megaphone',
        'color' => '#7fbf6e',
        'src'   => 'PASTE-POWER-BI-LINK-HERE',
    ],
    */
];

/* =========================================================
   ROUTING — works on Vercel (?page=) and locally (php -S)
   ========================================================= */
$page = $_GET['page'] ?? trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$page = strtolower(preg_replace('/[^a-z0-9-]/i', '', (string) $page));
if ($page === 'index' || $page === 'api') {
    $page = '';
}

$current = null;
if ($page !== '') {
    if (isset($dashboards[$page])) {
        $current = $dashboards[$page];
    } else {
        http_response_code(404);
    }
}
$notFound = ($page !== '' && $current === null);

function e($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($current ? $current['title'] . ' | ' . $companyName : $companyName . ' | Dashboards') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
  :root {
    --navy: #0c1e47;
    --navy-light: #16295a;
    --green: #7cb342;
    --text: #1d2433;
    --muted: #6b7280;
    --bg: #ffffff;
    --footer-bg: #f5f6f8;
    --header-h: 94px;
    --sidebar-w: 264px;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html, body { height: 100%; }
  body {
    font-family: 'Nunito', 'Segoe UI', Arial, sans-serif;
    color: var(--text);
    background: var(--bg);
  }
  a { color: inherit; text-decoration: none; }

  /* ---------- Top bar ---------- */
  .topbar {
    position: fixed; inset: 0 0 auto 0; height: var(--header-h);
    background: var(--navy); color: #fff;
    display: flex; align-items: center; z-index: 30;
  }
  .brand {
    width: var(--sidebar-w); padding-left: 18px;
    font-size: 1.35rem; font-weight: 600; white-space: nowrap;
  }
  .menu-btn {
    width: 44px; height: 44px; border-radius: 50%;
    border: 2px solid #fff; background: transparent; color: #fff;
    font-size: 1.3rem; cursor: pointer; display: grid; place-items: center;
  }
  .menu-btn:focus-visible { outline: 3px solid var(--green); outline-offset: 2px; }
  .logo {
    position: absolute; left: 50%; transform: translateX(-50%);
    display: flex; flex-direction: column; align-items: center; line-height: 1;
  }
  .logo svg { width: 64px; height: 24px; }
  .logo .name { color: #3d8fd6; font-size: 1.25rem; letter-spacing: .5px; margin-top: 2px; }
  .logo .sub  { color: var(--green); font-size: .85rem; letter-spacing: 1px; margin-top: 2px; }

  /* ---------- Sidebar ---------- */
  .sidebar {
    position: fixed; top: var(--header-h); bottom: 0; left: 0;
    width: var(--sidebar-w); background: var(--navy); color: #fff;
    overflow-y: auto; z-index: 20; transition: transform .25s ease;
  }
  .side-group {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 18px; font-size: 1.05rem;
  }
  .side-group .bi-chevron-down { margin-left: auto; font-size: .85rem; }
  .side-link {
    display: block; padding: 14px 18px 14px 45px; font-size: 1.05rem;
  }
  .side-link:hover, .side-link:focus-visible { background: var(--navy-light); outline: none; }
  .side-link.active { background: var(--navy-light); border-left: 4px solid var(--green); padding-left: 41px; }

  /* ---------- Main ---------- */
  .main {
    margin-left: var(--sidebar-w); padding-top: var(--header-h);
    min-height: 100vh; display: flex; flex-direction: column;
    transition: margin-left .25s ease;
  }
  body.collapsed .sidebar { transform: translateX(-100%); }
  body.collapsed .main { margin-left: 0; }

  .page-head {
    padding: 22px 44px; border-bottom: 3px solid var(--navy);
    display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
  }
  .page-head h1 { font-size: 1.65rem; font-weight: 600; }
  .head-actions { margin-left: auto; display: flex; gap: 10px; }
  .btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 999px; font-size: .95rem; font-weight: 600;
    border: 2px solid var(--navy); color: var(--navy); background: #fff; cursor: pointer;
  }
  .btn:hover, .btn:focus-visible { background: var(--navy); color: #fff; outline: none; }

  .content { flex: 1; padding: 70px 44px 60px; }

  /* Home grid of pill buttons */
  .grid {
    display: grid; gap: 45px 64px; max-width: 1420px; margin: 0 auto;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  }
  .tile {
    display: flex; align-items: center; gap: 18px;
    height: 98px; padding: 0 24px; border-radius: 999px;
    background: var(--c); color: #fff; font-size: 1.4rem; font-weight: 700;
    transition: filter .15s ease, transform .15s ease;
  }
  .tile:hover { filter: brightness(1.07); transform: translateY(-2px); }
  .tile:focus-visible { outline: 3px solid var(--navy); outline-offset: 3px; }
  .tile .ico {
    flex: 0 0 54px; height: 54px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,.85);
    display: grid; place-items: center; font-size: 1.5rem;
  }

  /* Dashboard view */
  .content.report { padding: 24px 44px 30px; }
  .frame-wrap {
    position: relative; width: 100%;
    height: calc(100vh - var(--header-h) - 190px);
    min-height: 480px;
    border: 1px solid #dfe3ea; border-radius: 10px; overflow: hidden; background: #fafbfc;
  }
  .frame-wrap iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }

  .empty { text-align: center; padding: 60px 20px; }
  .empty h2 { font-size: 1.5rem; margin-bottom: 10px; }
  .empty p { color: var(--muted); margin-bottom: 22px; }

  /* Footer */
  .footer {
    background: var(--footer-bg); padding: 26px 16px; color: var(--muted);
    display: flex; justify-content: space-between; font-size: .95rem;
  }
  .footer a { color: #1a5fd0; text-decoration: underline; }

  /* ---------- Mobile ---------- */
  .scrim { display: none; }
  @media (max-width: 900px) {
    :root { --header-h: 72px; }
    .brand { width: auto; padding-right: 14px; font-size: 1.1rem; }
    .logo { display: none; }
    .sidebar { transform: translateX(-100%); }
    .main { margin-left: 0; }
    body.open .sidebar { transform: none; }
    body.open .scrim { display: block; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 15; }
    body.collapsed .main { margin-left: 0; }
    .page-head, .content { padding-left: 18px; padding-right: 18px; }
    .page-head h1 { font-size: 1.25rem; }
    .content { padding-top: 36px; }
    .grid { gap: 22px; grid-template-columns: 1fr; }
    .tile { height: 80px; font-size: 1.2rem; }
    .frame-wrap { height: 70vh; min-height: 360px; }
  }
  @media (prefers-reduced-motion: reduce) {
    * { transition: none !important; }
  }
</style>
</head>
<body>

<header class="topbar">
  <div class="brand"><?= e($siteTitle) ?></div>
  <button class="menu-btn" id="menuBtn" aria-label="Toggle menu" aria-controls="sidebar">
    <i class="bi bi-list"></i>
  </button>
  <a class="logo" href="/" aria-label="<?= e($companyName) ?> home">
    <svg viewBox="0 0 64 24" fill="none" stroke-width="2" aria-hidden="true">
      <path d="M4 14 L32 2 L60 14" stroke="#7cb342"/>
      <path d="M14 10 V22 M22 7 V22 M42 7 V22 M50 10 V22" stroke="#3d8fd6"/>
    </svg>
    <span class="name">ASHFORD</span>
    <span class="sub">COMMUNITIES</span>
  </a>
</header>

<nav class="sidebar" id="sidebar" aria-label="Dashboards">
  <a class="side-group" href="/">
    <i class="bi bi-speedometer2"></i> Dashboard <i class="bi bi-chevron-down"></i>
  </a>
  <?php foreach ($dashboards as $slug => $d): ?>
    <a class="side-link<?= $slug === $page ? ' active' : '' ?>" href="/<?= e($slug) ?>"><?= e($d['title']) ?></a>
  <?php endforeach; ?>
</nav>
<div class="scrim" id="scrim"></div>

<div class="main">
  <div class="page-head">
    <h1><?= e($current ? $current['title'] : $pageHeading) ?></h1>
    <?php if ($current): ?>
      <div class="head-actions">
        <a class="btn" href="/"><i class="bi bi-grid"></i> All dashboards</a>
        <button class="btn" id="fsBtn"><i class="bi bi-arrows-fullscreen"></i> Full screen</button>
      </div>
    <?php endif; ?>
  </div>

  <?php if ($current): ?>
    <main class="content report">
      <div class="frame-wrap" id="frameWrap">
        <iframe title="<?= e($current['title']) ?>"
                src="<?= e($current['src']) ?>"
                allowfullscreen="true"
                loading="lazy"></iframe>
      </div>
    </main>

  <?php elseif ($notFound): ?>
    <main class="content">
      <div class="empty">
        <h2>This dashboard doesn't exist</h2>
        <p>Check the link, or pick a dashboard from the list.</p>
        <a class="btn" href="/"><i class="bi bi-grid"></i> All dashboards</a>
      </div>
    </main>

  <?php else: ?>
    <main class="content">
      <div class="grid">
        <?php foreach ($dashboards as $slug => $d): ?>
          <a class="tile" href="/<?= e($slug) ?>" style="--c: <?= e($d['color']) ?>">
            <span class="ico"><i class="bi bi-<?= e($d['icon']) ?>"></i></span>
            <?= e($d['title']) ?>
          </a>
        <?php endforeach; ?>
      </div>
    </main>
  <?php endif; ?>

  <footer class="footer">
    <span>Copyright &copy; <?= date('Y') ?></span>
    <a href="/"><?= e($companyName) ?></a>
  </footer>
</div>

<script>
  const body = document.body;
  const mobile = () => window.matchMedia('(max-width: 900px)').matches;
  document.getElementById('menuBtn').addEventListener('click', () => {
    body.classList.toggle(mobile() ? 'open' : 'collapsed');
  });
  document.getElementById('scrim').addEventListener('click', () => body.classList.remove('open'));

  const fsBtn = document.getElementById('fsBtn');
  if (fsBtn) {
    fsBtn.addEventListener('click', () => {
      const el = document.getElementById('frameWrap');
      (el.requestFullscreen || el.webkitRequestFullscreen).call(el);
    });
  }
</script>
</body>
</html>
