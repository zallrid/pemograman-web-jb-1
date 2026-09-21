<?php
require __DIR__ . '/../includes/koneksi.php';


$file_json = __DIR__ . '/../data/buku.json'; 

if (!file_exists($file_json)) {
    die("File JSON tidak ditemukan di: " . $file_json);
}

$data_json = file_get_contents($file_json);
$buku_array = json_decode($data_json, true);

if ($buku_array) {
    $stmt = $pdo->prepare("INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori) VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)");
    
    $jumlah = 0;
    foreach ($buku_array as $b) {
        $stmt->execute([
            'judul' => $b['judul'],
            'pengarang' => $b['pengarang'],
            'tahun' => (int)$b['tahun'],
            'isbn' => $b['isbn'] ?? '-', 
            'stok' => (int)($b['stok'] ?? 1),
            'kategori' => $b['kategori'] ?? 'fiksi'
        ]);
        $jumlah++;
    }
    echo "Sukses! $jumlah data buku dari JSON berhasil dipindahkan ke PostgreSQL.";
} else {
    echo "Gagal mengurai isi JSON.";
}
?>