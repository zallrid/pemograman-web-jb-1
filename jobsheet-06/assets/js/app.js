// ===== Hamburger menu =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

function updateCounter() {
    const table = document.querySelector(".table-responsive table");
    const counterText = document.getElementById("table-counter");
    if (!table || !counterText) return;

    const rows = table.querySelectorAll("tbody tr");
    let visibleCount = 0;
    
    rows.forEach(row => {
        if (row.style.display !== "none") visibleCount++;
    });
    
    counterText.textContent = `Menampilkan ${visibleCount} dari ${rows.length} data`;
}

function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        
        console.log(e.target);
        const btn = e.target.closest(".btn-hapus");
        if (!btn) return; // Hentikan fungsi jika yang diklik BUKAN tombol hapus

        const row = btn.closest("tr");
        const nama = row ? row.querySelector("td")?.textContent : "data ini";
        const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
        
        if (yakin && row) {
            row.remove();
            if (typeof updateCounter === "function") {
                updateCounter(); 
            }
        }
    });
}

function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    updateCounter(); 

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");
        
        rows.forEach(function (row) {
            const selTeks = row.querySelector("td") ? row.querySelector("td").textContent.toLowerCase() : "";
            row.style.display = selTeks.includes(keyword) ? "" : "none";
        });
        
        updateCounter(); 
    });
}

// ===== Fungsi Bantuan Validasi =====
function tampilkanError(input, pesan) {
    hapusError(input);
    const span = document.createElement("span");
    span.className = "error";
    span.textContent = pesan;
    input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
    const next = input.nextElementSibling;
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

function initValidasiForm() {
    const form = document.getElementById("form-tambah");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        let valid = true;

        const aturanValidasi = [
            { name: 'judul', error: 'Judul wajib diisi.' },
            { name: 'nama', error: 'Nama wajib diisi.' },
            { name: 'pengarang', error: 'Pengarang wajib diisi.' },
            { name: 'no_anggota', error: 'Nomor Anggota wajib diisi.' },
            { name: 'tahun', type: 'number', min: 1900, max: 2026, error: 'Tahun harus 1900-2026.' },
            { name: 'stok', type: 'number', min: 0, error: 'Stok tidak boleh negatif.' },
            { name: 'isbn', type: 'regex', pattern: /^[0-9-]+$/, error: 'ISBN hanya boleh angka dan tanda hubung (-).' }
        ];

        // Looping semua field form
        aturanValidasi.forEach(function(aturan) {
            const input = form.querySelector(`[name='${aturan.name}']`);
            if (!input) return; 

            let isValid = true;
            const nilai = input.value.trim();

            if (input.hasAttribute('required') && nilai === "") {
                isValid = false;
            } else if (nilai !== "") {
                if (aturan.type === 'number') {
                    const num = parseInt(nilai, 10);
                    if (isNaN(num) || (aturan.min !== undefined && num < aturan.min) || (aturan.max !== undefined && num > aturan.max)) {
                        isValid = false;
                    }
                } else if (aturan.type === 'regex') {
                    if (!aturan.pattern.test(nilai)) {
                        isValid = false;
                    }
                }
            }

            if (!isValid) {
                tampilkanError(input, aturan.error);
                valid = false;
            } else {
                hapusError(input);
            }
        });

        if (!valid) {
            e.preventDefault();
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});

async function muatDataGenerik(urlJSON, containerBody, containerLoading, keys) {
    const tbody = document.querySelector(containerBody);
    const loading = document.getElementById(containerLoading);
    if (!tbody) return;

    if (loading) loading.style.display = "block";
    tbody.innerHTML = "";

    try {
        await new Promise((resolve) => setTimeout(resolve, 600));

        const res = await fetch(urlJSON);
        if (!res.ok) throw new Error("Gagal mengambil data");
        const data = await res.json();

        data.forEach(item => {
            const tr = document.createElement("tr");
            let isiBaris = "";
            
            
            keys.forEach(key => {
                isiBaris += "<td>" + (item[key] !== undefined ? item[key] : "-") + "</td>";
            });
            
            isiBaris += "<td>" +
                        "<button type=\"button\">Edit</button> " +
                        "<button type=\"button\" class=\"btn-hapus\">Hapus</button>" +
                        "</td>";
            tr.innerHTML = isiBaris;
            tbody.appendChild(tr);
        });
    } catch (err) {
        tbody.innerHTML = "<tr><td colspan=\"" + (keys.length + 1) + "\">Gagal: " + err.message + "</td></tr>";
    } finally {
        if (loading) loading.style.display = "none";
    }
}