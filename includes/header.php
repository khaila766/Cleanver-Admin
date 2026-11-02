<?php
$pageTitle = $pageTitle ?? 'Dashboard';
$current = basename($_SERVER['PHP_SELF']);
$flash = getFlash();
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?> · Cleanver Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-shell">
<aside class="sidebar" id="sidebar">
  <a class="brand" href="index.php"><span class="brand-mark">C</span><span>CLEANVER<small>ADMIN PANEL</small></span></a>
  <div class="nav-label">MENU UTAMA</div>
  <nav>
    <a class="<?= $current==='index.php'?'active':'' ?>" href="index.php"><span>▦</span> Dashboard</a>
    <a class="<?= $current==='products.php'?'active':'' ?>" href="products.php"><span>▤</span> Data Produk</a>
    <a class="<?= $current==='categories.php'?'active':'' ?>" href="categories.php"><span>◫</span> Kategori</a>
    <a class="<?= $current==='stock.php'?'active':'' ?>" href="stock.php"><span>⇅</span> Stok Barang</a>
    <a class="<?= $current==='transactions.php'?'active':'' ?>" href="transactions.php"><span>▧</span> Riwayat Stok</a>
  </nav>
  <div class="sidebar-bottom"><div class="mini-brand">C</div><div><strong>Cleanver</strong><small>Inventory workspace</small></div></div>
</aside>
<main class="main-area">
<header class="topbar">
  <button class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('show')" aria-label="Buka menu">☰</button>
  <div><div class="eyebrow">CLEANVER / MANAGEMENT</div><h1><?= e($pageTitle) ?></h1></div>
  <div class="topbar-right"><span class="status-dot"></span><span>Admin Workspace</span><div class="avatar">CV</div></div>
</header>
<div class="content">
<?php if ($flash): ?><div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?><button onclick="this.parentElement.remove()">×</button></div><?php endif; ?>
