function muatDaftarAnggota() {
    muatDataGenerik(
        "../data/anggota.json", 
        ".table-responsive table tbody", 
        "loading-indicator", 
        ["no_anggota", "nama", "alamat", "no_hp"] 
    );
}

document.addEventListener("DOMContentLoaded", muatDaftarAnggota);