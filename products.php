<?php
require_once 'config/database.php'; require_once 'includes/functions.php'; verifyCsrf();
$action=$_GET['action']??'list'; $id=(int)($_GET['id']??0); $errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
 $mode=$_POST['mode']??'';
 if($mode==='delete'){ $stmt=$pdo->prepare("DELETE FROM products WHERE id=?"); $stmt->execute([(int)$_POST['id']]); flash('success','Produk berhasil dihapus.'); redirect('products.php'); }
 if($mode==='save'){
  $id=(int)($_POST['id']??0); $name=trim($_POST['name']??''); $sku=trim($_POST['sku']??''); $category=(int)($_POST['category_id']??0); $price=(float)($_POST['price']??0); $stock=(int)($_POST['stock']??0); $description=trim($_POST['description']??'');
  if($name==='')$errors[]='Nama produk wajib diisi.'; if($sku==='')$errors[]='SKU wajib diisi.'; if($price<0)$errors[]='Harga tidak boleh negatif.'; if($stock<0)$errors[]='Stok tidak boleh negatif.';
  $check=$pdo->prepare("SELECT id FROM products WHERE sku=? AND id<>?");$check->execute([$sku,$id]);if($check->fetch())$errors[]='SKU sudah digunakan produk lain.';
  if(!$errors){ if($id){$q=$pdo->prepare("UPDATE products SET name=?,sku=?,category_id=?,price=?,stock=?,description=? WHERE id=?");$q->execute([$name,$sku,$category?:null,$price,$stock,$description,$id]);flash('success','Produk berhasil diperbarui.');}
  else{$q=$pdo->prepare("INSERT INTO products(name,sku,category_id,price,stock,description) VALUES(?,?,?,?,?,?)");$q->execute([$name,$sku,$category?:null,$price,$stock,$description]);flash('success','Produk berhasil ditambahkan.');} redirect('products.php');}
  $action=$id?'edit':'create';
 }
}
$categories=$pdo->query("SELECT id,name FROM categories ORDER BY name")->fetchAll();
$edit=null;if($action==='edit'&&$id){$q=$pdo->prepare("SELECT * FROM products WHERE id=?");$q->execute([$id]);$edit=$q->fetch();if(!$edit){flash('error','Produk tidak ditemukan.');redirect('products.php');}}
$search=trim($_GET['q']??'');
$sql="SELECT p.*,c.name category FROM products p LEFT JOIN categories c ON c.id=p.category_id";$params=[];
if($search!==''){$sql.=" WHERE p.name LIKE ? OR p.sku LIKE ?";$params=["%$search%","%$search%"];}
$sql.=" ORDER BY p.created_at DESC";$stmt=$pdo->prepare($sql);$stmt->execute($params);$products=$stmt->fetchAll();
$pageTitle='Data Produk';require 'includes/header.php';
?>
<?php if(in_array($action,['create','edit'])): $v=$edit??($_POST??[]); ?>
<div class="page-tools"><a class="back-link" href="products.php">← Kembali ke produk</a></div>
<section class="form-panel"><div class="panel-head"><div><span class="eyebrow">PRODUCT MANAGEMENT</span><h3><?= $action==='edit'?'Edit Produk':'Tambah Produk Baru' ?></h3><p class="muted">Lengkapi informasi produk Cleanver di bawah ini.</p></div></div>
<?php if($errors): ?><div class="alert error"><?= implode('<br>',array_map('e',$errors)) ?></div><?php endif; ?>
<form method="post" class="form-grid"><?= '<input type="hidden" name="csrf" value="'.e(csrfToken()).'"><input type="hidden" name="mode" value="save"><input type="hidden" name="id" value="'.(int)($v['id']??$id).'">' ?>
<label>Nama Produk <input required name="name" value="<?= e($v['name']??'') ?>" placeholder="Contoh: Cleanver Foam Cleaner"></label>
<label>SKU / Kode Produk <input required name="sku" value="<?= e($v['sku']??'') ?>" placeholder="CLN-001"></label>
<label>Kategori <select name="category_id"><option value="">Pilih kategori</option><?php foreach($categories as $c): ?><option value="<?= $c['id'] ?>" <?= (int)($v['category_id']??0)===$c['id']?'selected':'' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select></label>
<label>Harga (Rp) <input type="number" min="0" step="100" name="price" value="<?= e($v['price']??'0') ?>"></label>
<label>Stok Awal <input type="number" min="0" name="stock" value="<?= e($v['stock']??'0') ?>"></label>
<label class="full">Deskripsi <textarea name="description" rows="4" placeholder="Deskripsi singkat produk..."><?= e($v['description']??'') ?></textarea></label>
<div class="form-actions"><a class="btn btn-outline" href="products.php">Batal</a><button class="btn btn-primary">Simpan Produk</button></div></form></section>
<?php else: ?>
<div class="page-tools"><p class="muted">Kelola seluruh produk dan informasi inventaris Cleanver.</p><a class="btn btn-primary" href="products.php?action=create">＋ Tambah Produk</a></div>
<section class="panel"><form class="search-bar" method="get"><input name="q" value="<?= e($search) ?>" placeholder="Cari nama produk atau SKU..."><button class="btn btn-dark">Cari</button><?php if($search): ?><a class="btn btn-outline" href="products.php">Reset</a><?php endif; ?></form>
<div class="table-wrap"><table><thead><tr><th>PRODUK</th><th>SKU</th><th>KATEGORI</th><th>HARGA</th><th>STOK</th><th>AKSI</th></tr></thead><tbody>
<?php foreach($products as $p): ?><tr><td><strong><?= e($p['name']) ?></strong><small class="table-sub"><?= e($p['description']?:'Produk Cleanver') ?></small></td><td class="muted"><?= e($p['sku']) ?></td><td><?= e($p['category']??'-') ?></td><td><?= rupiah($p['price']) ?></td><td><span class="stock-pill <?= $p['stock']<=5?'warning':'' ?>"><?= (int)$p['stock'] ?></span></td><td><div class="action-group"><a class="icon-btn" title="Edit" href="products.php?action=edit&id=<?= $p['id'] ?>">✎</a><form method="post" onsubmit="return confirm('Hapus produk ini?')"><input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>"><input type="hidden" name="mode" value="delete"><input type="hidden" name="id" value="<?= $p['id'] ?>"><button class="icon-btn danger" title="Hapus">×</button></form></div></td></tr><?php endforeach; ?>
<?php if(!$products): ?><tr><td colspan="6"><div class="empty-state compact"><strong>Produk belum ditemukan</strong><p>Tambahkan produk atau ubah kata pencarian.</p></div></td></tr><?php endif; ?>
</tbody></table></div><div class="table-foot">Menampilkan <?= count($products) ?> produk</div></section>
<?php endif; require 'includes/footer.php'; ?>
