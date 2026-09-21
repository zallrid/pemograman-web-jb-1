<?php
$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);


$keyword = $_GET['keyword'] ?? '';

if ($keyword) {
    
    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :keyword ORDER BY id DESC");
    $stmt->execute(['keyword' => "%$keyword%"]);
    $daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
 
    $daftarBuku = $pdo->query("SELECT * FROM buku ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>
        <section>
            <h2>Daftar Buku</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

         
            <div class="search-box">
                <form method="GET" action="">
                    <label for="search-input">Cari Judul Buku</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="search-input" name="keyword" placeholder="Ketik judul buku..." value="<?php echo $keyword; ?>">
                        <button type="submit" style="padding: 0.5rem 1rem; background: #1d5b8a; color: white; border: none; border-radius: 4px; cursor: pointer;">Cari</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Tahun</th>
                        <th>Tanggal Masuk</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <td colspan="6">Belum ada data buku. Silakan tambah lewat menu "Tambah Buku" (Atau jalankan migrasi JSON).</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarBuku as $buku): ?>
                        <tr>
                            <td><?php echo $buku['judul']; ?></td>
                            <td><?php echo $buku['pengarang']; ?></td>
                            <td><?php echo $buku['tahun']; ?></td>
                            <td><?php echo $buku['tanggal_ditambahkan']; ?></td>
                            <td><?php echo $buku['stok']; ?></td>
                            <td>
                                <button type="button">Edit</button>
                                <button type="button" class="btn-hapus">Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>