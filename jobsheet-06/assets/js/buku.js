function muatDaftarBuku() {
    muatDataGenerik(
        "../data/buku.json", 
        ".table-responsive table tbody", 
        "loading-indicator", 
        ["judul", "pengarang", "tahun", "stok", "kategori"] 
    );
}

document.addEventListener("DOMContentLoaded", function () {
    muatDaftarBuku(); 
    

    const btnMuatUlang = document.getElementById("btn-muat-ulang");
    if (btnMuatUlang) {
        btnMuatUlang.addEventListener("click", muatDaftarBuku);
    }
});