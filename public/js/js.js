/* =========================================
   GLOBAL HELPER & VARIABLES
   ========================================= */

// --- VARIABEL MODAL ---
let modal, modalForm, modalTitle, methodContainer;
let inputBookId, inputJumlah, inputTanggal; // Data Masuk
let bookModal, bookForm, bookTitle, bookMethod; // Data Buku
let loanModal, loanForm, loanTitle, loanMethod; // Peminjaman
let userModal, userForm, userTitle, userMethod; // User
let inpU_Name, inpU_Email, inpU_Password, inpU_Level, passHint; // Input User

// --- VARIABEL LAPORAN & PAGINATION ---
let currentPage = 1;
const rowsPerPage = 5; // JUMLAH BARIS PER HALAMAN (Ganti angka ini sesuka hati)
let filteredRows = []; // Penampung data hasil filter

// Fungsi Tampilkan Pesan
function showModal(message, isSuccess = false) {
    const popupModal = document.getElementById("popupModal");
    const modalText = document.getElementById("modalText");
    if (popupModal && modalText) {
        modalText.innerText = message;
        popupModal.style.display = "flex";
    }
}

/* =========================================
   1. LOGIC MODAL DATA MASUK (CRUD)
   ========================================= */
function initModalElements() {
    if (!modal) {
        modal = document.getElementById('dataModal');
        modalForm = document.getElementById('modalForm');
        modalTitle = document.getElementById('modalTitle');
        methodContainer = document.getElementById('methodInputContainer');
        inputBookId = document.getElementById('inputBookId');
        inputJumlah = document.getElementById('inputJumlah');
        inputTanggal = document.getElementById('inputTanggal');
    }
}

window.openAddModal = function() {
    initModalElements(); if(!modal) return;
    modalForm.reset(); modalForm.action = "/datamasuk/store";
    modalTitle.innerText = "Tambah Data Masuk";
    if(methodContainer) methodContainer.innerHTML = "";
    modal.style.display = 'flex';
}

window.openEditModal = function(button) {
    initModalElements(); if(!modal) return;
    const id = button.getAttribute('data-id');
    if(inputBookId) inputBookId.value = button.getAttribute('data-book_id');
    if(inputJumlah) inputJumlah.value = button.getAttribute('data-jumlah');
    if(inputTanggal) inputTanggal.value = button.getAttribute('data-tanggal');
    modalForm.action = "/datamasuk/update/" + id;
    modalTitle.innerText = "Edit Data Masuk";
    if(methodContainer) methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
    modal.style.display = 'flex';
}

window.closeModal = function() {
    initModalElements(); if(modal) modal.style.display = 'none';
}

/* =========================================
   2. LOGIC MODAL DATA BUKU
   ========================================= */
function initBookElements() {
    if (!bookModal) {
        bookModal = document.getElementById('bookModal');
        bookForm = document.getElementById('bookModalForm');
        bookTitle = document.getElementById('bookModalTitle');
        bookMethod = document.getElementById('bookMethodContainer');
        // ... inisialisasi input buku lainnya (disingkat biar ga kepanjangan)
        inpB_Judul = document.getElementById('bookJudul');
        inpB_Penulis = document.getElementById('bookPenulis');
        inpB_Penerbit = document.getElementById('bookPenerbit');
        inpB_Tahun = document.getElementById('bookTahun');
        inpB_Stok = document.getElementById('bookStok');
        inpB_Kategori = document.getElementById('bookKategori');
    }
}

window.openAddBookModal = function() {
    initBookElements(); if(!bookModal) return;
    bookForm.reset(); bookForm.action = "/databuku/store"; 
    bookTitle.innerText = "Tambah Buku Baru";
    if(bookMethod) bookMethod.innerHTML = ""; 
    bookModal.style.display = 'flex';
}

window.openEditBookModal = function(button) {
    initBookElements(); if(!bookModal) return;
    const id = button.getAttribute('data-id');
    if(inpB_Judul) inpB_Judul.value = button.getAttribute('data-judul');
    // ... isi input lain ...
    inpB_Penulis.value = button.getAttribute('data-penulis');
    inpB_Penerbit.value = button.getAttribute('data-penerbit');
    inpB_Tahun.value = button.getAttribute('data-tahun');
    inpB_Stok.value = button.getAttribute('data-stok');
    inpB_Kategori.value = button.getAttribute('data-kategori_id');

    bookForm.action = "/databuku/update/" + id;
    bookTitle.innerText = "Edit Data Buku";
    if(bookMethod) bookMethod.innerHTML = ""; 
    bookModal.style.display = 'flex';
}

window.closeBookModal = function() {
    initBookElements(); if(bookModal) bookModal.style.display = 'none';
}

/* =========================================
   3. LOGIC MODAL DATA PEMINJAMAN
   ========================================= */
function initLoanElements() {
    if (!loanModal) {
        loanModal = document.getElementById('loanModal');
        loanForm = document.getElementById('loanModalForm');
        loanTitle = document.getElementById('loanModalTitle');
        loanMethod = document.getElementById('loanMethodContainer');
        inpL_Book = document.getElementById('loanBookId');
        inpL_User = document.getElementById('loanUserId');
        inpL_Pinjam = document.getElementById('loanTglPinjam');
        inpL_Kembali = document.getElementById('loanTglKembali');
        inpL_Status = document.getElementById('loanStatus');
    }
}

window.openAddLoanModal = function() {
    initLoanElements(); if(!loanModal) return;
    loanForm.reset(); loanForm.action = "/peminjaman/store"; 
    loanTitle.innerText = "Tambah Peminjaman";
    if(loanMethod) loanMethod.innerHTML = "";
    loanModal.style.display = 'flex';
}

window.openEditLoanModal = function(button) {
    initLoanElements(); if(!loanModal) return;
    const id = button.getAttribute('data-id');
    if(inpL_Book) inpL_Book.value = button.getAttribute('data-book_id');
    if(inpL_User) inpL_User.value = button.getAttribute('data-user_id');
    if(inpL_Pinjam) inpL_Pinjam.value = button.getAttribute('data-tanggal_pinjam');
    if(inpL_Kembali) inpL_Kembali.value = button.getAttribute('data-tanggal_kembali');
    if(inpL_Status) inpL_Status.value = button.getAttribute('data-status');

    loanForm.action = "/peminjaman/update/" + id;
    loanTitle.innerText = "Edit Peminjaman";
    if(loanMethod) loanMethod.innerHTML = '<input type="hidden" name="_method" value="PUT">';
    loanModal.style.display = 'flex';
}

window.closeLoanModal = function() {
    initLoanElements(); if(loanModal) loanModal.style.display = 'none';
}

/* =========================================
   4. LOGIC MODAL DATA USER
   ========================================= */
function initUserElements() {
    if (!userModal) {
        userModal = document.getElementById('userModal');
        userForm = document.getElementById('userModalForm');
        userTitle = document.getElementById('userModalTitle');
        userMethod = document.getElementById('userMethodContainer');
        inpU_Name = document.getElementById('userName');
        inpU_Email = document.getElementById('userEmail');
        inpU_Password = document.getElementById('userPassword');
        inpU_Level = document.getElementById('userLevel');
        passHint = document.getElementById('passwordHint');
    }
}

window.openAddUserModal = function() {
    initUserElements(); if(!userModal) return;
    userForm.reset(); userForm.action = "/datauser/store"; 
    userTitle.innerText = "Tambah User Baru";
    if(userMethod) userMethod.innerHTML = "";
    if(inpU_Password) inpU_Password.required = true;
    if(passHint) passHint.style.display = 'none';
    userModal.style.display = 'flex';
}

window.openEditUserModal = function(button) {
    initUserElements(); if(!userModal) return;
    const id = button.getAttribute('data-id');
    if(inpU_Name) inpU_Name.value = button.getAttribute('data-name');
    if(inpU_Email) inpU_Email.value = button.getAttribute('data-email');
    if(inpU_Level) inpU_Level.value = button.getAttribute('data-level_id');
    if(inpU_Password) { inpU_Password.value = ""; inpU_Password.required = false; }
    if(passHint) passHint.style.display = 'block';
    userForm.action = "/datauser/update/" + id;
    userTitle.innerText = "Edit Data User";
    if(userMethod) userMethod.innerHTML = ""; 
    userModal.style.display = 'flex';
}

window.closeUserModal = function() {
    initUserElements(); if(userModal) userModal.style.display = 'none';
}

/* =========================================
   5. LOGIC FILTER, PRINT, EXCEL & PAGINATION (THE KING'S REQUEST) 👑
   ========================================= */

// Fungsi untuk menyiapkan Pagination di HTML
function setupPaginationHTML() {
    const table = document.getElementById('tableLaporan');
    if (!table) return;

    // Cek apakah pagination container sudah ada, kalau belum buat baru
    let pagContainer = document.getElementById('paginationContainer');
    if (!pagContainer) {
        pagContainer = document.createElement('div');
        pagContainer.id = 'paginationContainer';
        pagContainer.style.cssText = "display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 20px;";
        
        // Insert setelah tabel
        table.parentElement.appendChild(pagContainer);
    }
    
    // Simpan semua baris tabel ke variabel global saat pertama kali load
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    // Ubah HTMLCollection jadi Array biar enak diolah
    filteredRows = Array.from(rows); 
}

function renderTablePartition() {
    const table = document.getElementById('tableLaporan');
    if(!table) return;
    
    const allRows = Array.from(table.getElementsByTagName('tbody')[0].getElementsByTagName('tr'));
    
    allRows.forEach(row => row.style.display = 'none');

    const startIndex = (currentPage - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;
    const rowsToShow = filteredRows.slice(startIndex, endIndex);
    
    rowsToShow.forEach(row => {
        row.style.display = '';
    });

    renderPaginationControls();
}

function renderPaginationControls() {
    const pagContainer = document.getElementById('paginationContainer');
    if (!pagContainer) return;

    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    
    // Jangan tampilkan pagination kalau data kosong atau cuma 1 halaman
    if (totalPages <= 1) {
        pagContainer.innerHTML = '';
        return;
    }

    let html = '';
    
    // Tombol Previous
    if (currentPage > 1) {
        html += `<button onclick="changePage(${currentPage - 1})" class="btn-custom" style="padding: 5px 10px; background: #374151; color: white;">&laquo; Prev</button>`;
    }

    // Info Halaman
    html += `<span style="color: white; font-size: 0.9rem;">Page ${currentPage} of ${totalPages}</span>`;

    // Tombol Next
    if (currentPage < totalPages) {
        html += `<button onclick="changePage(${currentPage + 1})" class="btn-custom" style="padding: 5px 10px; background: #374151; color: white;">Next &raquo;</button>`;
    }

    pagContainer.innerHTML = html;
}

// Fungsi Ganti Halaman (Dipanggil tombol Next/Prev)
window.changePage = function(page) {
    currentPage = page;
    renderTablePartition();
}

// Fungsi Filter Utama (Tanggal) + Update Link Export
window.filterTableByDate = function() {
    const fromDateVal = document.getElementById('filterFrom').value;
    const toDateVal = document.getElementById('filterTo').value;
    const table = document.getElementById('tableLaporan');
    
    if(!table) return;

    const tbody = table.getElementsByTagName('tbody')[0];
    const allRows = Array.from(tbody.getElementsByTagName('tr')); // Ambil semua baris asli

    // Update URL Export Buttons
    updateExportLinks(fromDateVal, toDateVal);

    // Filter Logic
    // Kita reset array filteredRows dengan data yang cocok saja
    filteredRows = allRows.filter(row => {
        const dateCell = row.getElementsByClassName('tgl-pinjam')[0];
        if (!dateCell) return false; // Kalau header atau footer

        const rowDateStr = dateCell.textContent.trim();
        let showRow = true;

        if (fromDateVal && rowDateStr < fromDateVal) showRow = false;
        if (toDateVal && rowDateStr > toDateVal) showRow = false;

        return showRow;
    });

    // Reset ke halaman 1 setiap kali filter berubah
    currentPage = 1;
    
    // Tampilkan pesan kosong jika tidak ada data
    const noDataMsg = document.getElementById('noDataMessage');
    if (filteredRows.length === 0) {
        if(noDataMsg) noDataMsg.style.display = "block";
        table.style.display = "none";
        document.getElementById('paginationContainer').innerHTML = '';
    } else {
        if(noDataMsg) noDataMsg.style.display = "none";
        table.style.display = "table";
        renderTablePartition(); // Panggil fungsi pagination
    }
}

window.resetFilter = function() {
    document.getElementById('filterFrom').value = "";
    document.getElementById('filterTo').value = "";
    
    // Reset filteredRows ke semua baris lagi
    const table = document.getElementById('tableLaporan');
    if(table) {
        const tbody = table.getElementsByTagName('tbody')[0];
        filteredRows = Array.from(tbody.getElementsByTagName('tr'));
        
        document.getElementById('noDataMessage').style.display = "none";
        table.style.display = "table";
        
        currentPage = 1;
        renderTablePartition();
        updateExportLinks("", "");
    }
}

function updateExportLinks(from, to) {
    const btnPrint = document.getElementById('btnPrint');
    const btnPdf = document.getElementById('btnPdf');
    const btnExcel = document.getElementById('btnExcel');
    const queryParams = `?from=${from}&to=${to}`;

    if(btnPrint) btnPrint.href = "/laporanpeminjaman/print" + queryParams;
    if(btnPdf) btnPdf.href = "/laporanpeminjaman/pdf" + queryParams;
    if(btnExcel) btnExcel.href = "/laporanpeminjaman/excel" + queryParams;
}


/* =========================================
   6. EVENT LISTENERS (ON LOAD)
   ========================================= */

document.addEventListener('DOMContentLoaded', () => {
    
    // Init Semua Modal
    initModalElements();
    initBookElements();
    initLoanElements();
    initUserElements();

    // SETUP PAGINATION PERTAMA KALI
    setupPaginationHTML();
    renderTablePartition(); // Tampilkan halaman 1

    // Global Close Modal
    window.onclick = function(event) {
        if (modal && event.target == modal) modal.style.display = 'none';
        if (bookModal && event.target == bookModal) bookModal.style.display = 'none';
        if (loanModal && event.target == loanModal) loanModal.style.display = 'none';
        if (userModal && event.target == userModal) userModal.style.display = 'none';
    }

    // Logic Login
    const loginBtn = document.getElementById('loginBtn');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const loginForm = document.getElementById('login-form');
    const popupModal = document.getElementById("popupModal");
    const closeModalBtn = document.getElementById("closeModal");

    if (loginBtn) {
        loginBtn.addEventListener("click", function (e) {
            e.preventDefault(); 
            const email = emailInput ? emailInput.value.trim() : "";
            const pass = passwordInput ? passwordInput.value.trim() : "";
            if (!email || !pass) {
                showModal("Woi bro email sama password jangan kosong lah 😤");
                return;
            }
            if(loginForm) loginForm.submit();
        });
    }

    if (closeModalBtn && popupModal) {
        closeModalBtn.addEventListener("click", () => {
            popupModal.style.display = "none";
        });
    }

    // Logic Navbar Mobile
    const menuToggleBtn = document.getElementById('menu-toggle-btn');
    const navMobile = document.getElementById('nav-mobile');
    if (menuToggleBtn && navMobile) {
        menuToggleBtn.addEventListener('click', () => {
            if (navMobile.style.display === 'none' || navMobile.style.display === '') {
                navMobile.style.display = 'block';
            } else {
                navMobile.style.display = 'none';
            }
        });
    }

    // Logic Konfirmasi Hapus
    const deleteButtons = document.querySelectorAll('.btn-delete');
    if(deleteButtons.length > 0) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                if (confirm("Yakin mau hapus data ini secara permanen?")) {
                    window.location.href = href;
                }
            });
        });
    }
});