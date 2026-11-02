<?php
require_once 'config/database.php'; require_once 'includes/functions.php';
$pageTitle = 'Dashboard';
$totalProducts = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalCategories = (int)$pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$totalStock = (int)$pdo->query("SELECT COALESCE(SUM(stock),0) FROM products")->fetchColumn();
$lowStock = (int)$pdo->query("SELECT COUNT(*) FROM products WHERE stock <= 5")->fetchColumn();
$recent = $pdo->query("SELECT p.name,p.sku,p.stock,c.name category FROM products p LEFT JOIN categories c ON c.id=p.category_id ORDER BY p.created_at DESC LIMIT 6")->fetchAll();
$activity = $pdo->query("SELECT t.*,p.name product_name FROM stock_transactions t JOIN products p ON p.id=t.product_id ORDER BY t.created_at DESC LIMIT 5")->fetchAll();
require 'includes/header.php';
?>
<section class="welcome-card"><div><span class="welcome-kicker">SELAMAT DATANG DI CLEANVER</span><h2>Kelola inventaris lebih<br><em>mudah dan teratur.</em></h2><p>Pantau produk dan pergerakan stok Cleanver dalam satu tempat.</p><a class="btn btn-light" href="products.php">Kelola Produk <span>→</span></a></div><div class="welcome-art"><div class="orbit orbit-one"></div><div class="orbit orbit-two"></div><div class="bottle">C<span>clean<br>care</span></div><div class="sparkle">✦</div></div></section>
<div class="section-heading"><div><span class="eyebrow">OVERVIEW</span><h2>Ringkasan inventaris</h2></div><span class="date-chip"><?= date('d M Y') ?></span></div>
<div class="stats-grid">
 <article class="stat-card"><div class="stat-icon red">▤</div><span>Total Produk</span><strong><?= $totalProducts ?></strong><small>Produk terdaftar</small></article>
 <article class="stat-card"><div class="stat-icon peach">◫</div><span>Kategori</span><strong><?= $totalCategories ?></strong><small>Kategori produk</small></article>
 <article class="stat-card"><div class="stat-icon dark">▦</div><span>Total Stok</span><strong><?= number_format($totalStock) ?></strong><small>Unit tersedia</small></article>
 <article class="stat-card"><div class="stat-icon yellow">!</div><span>Stok Menipis</span><strong><?= $lowStock ?></strong><small>Produk dengan stok ≤ 5</small></article>
</div>
<div class="dashboard-grid">
<section class="panel"><div class="panel-head"><div><span class="eyebrow">INVENTORY</span><h3>Produk terbaru</h3></div><a class="text-link" href="products.php">Lihat semua →</a></div>
<?php if (!$recent): ?><div class="empty-state"><div>▤</div><strong>Belum ada produk</strong><p>Tambahkan produk pertama Cleanver.</p><a class="btn btn-primary" href="products.php?action=create">Tambah Produk</a></div><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>PRODUK</th><th>KATEGORI</th><th>STOK</th><th>SKU</th></tr></thead><tbody>
<?php foreach($recent as $p): ?><tr><td><strong><?= e($p['name']) ?></strong></td><td><?= e($p['category'] ?? '-') ?></td><td><span class="stock-pill <?= $p['stock']<=5?'warning':'' ?>"><?= (int)$p['stock'] ?> unit</span></td><td class="muted"><?= e($p['sku']) ?></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<section class="panel"><div class="panel-head"><div><span class="eyebrow">STOCK ACTIVITY</span><h3>Aktivitas terbaru</h3></div><a class="text-link" href="transactions.php">Semua →</a></div>
<?php if (!$activity): ?><div class="empty-state compact"><div>⇅</div><strong>Belum ada aktivitas stok</strong><p>Catat stok masuk atau keluar.</p></div><?php else: ?><div class="activity-list"><?php foreach($activity as $a): ?><div class="activity-item"><span class="activity-icon <?= $a['type']==='masuk'?'in':'out' ?>"><?= $a['type']==='masuk'?'↓':'↑' ?></span><div><strong><?= e($a['product_name']) ?></strong><small><?= e($a['note'] ?: ucfirst($a['type'])) ?> · <?= date('d/m H:i',strtotime($a['created_at'])) ?></small></div><b class="<?= $a['type']==='masuk'?'positive':'negative' ?>"><?= $a['type']==='masuk'?'+':'-' ?><?= (int)$a['quantity'] ?></b></div><?php endforeach; ?></div><?php endif; ?></section>
</div>
<?php require 'includes/footer.php'; ?>
