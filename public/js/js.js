const rowsPerPage = 5; 
let currentPage = 1;

let filteredRows = [];

let modal, modalForm, modalTitle, methodContainer;
let bookModal, bookForm, bookTitle, bookMethod;
let loanModal, loanForm, loanTitle, loanMethod;
let userModal, userForm, userTitle, userMethod;

let inputBookId, inputJumlah, inputTanggal;
let inpB_Judul, inpB_Penulis, inpB_Penerbit, inpB_Tahun, inpB_Stok, inpB_Kategori;
let inpL_Book, inpL_User, inpL_Pinjam, inpL_Kembali, inpL_Status;
let inpU_Name, inpU_Email, inpU_Password, inpU_Level, passHint;

function showModal(message, isSuccess = false) {
    const popupModal = document.getElementById("popupModal");
    const modalText = document.getElementById("modalText");
    if (popupModal && modalText) {
        modalText.innerText = message;
        popupModal.style.display = "flex";
    }
}

function loadDataBuku() {
    const tableBody = document.getElementById('tableDataBuku');
    if (!tableBody) return;

    tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Sedang memuat data...</td></tr>';

    fetch('/api/databuku')
    .then(response => response.json())
    .then(result => {
        if(result.status === 'success') {
            renderBookTable(result.data);
        } else {
            tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Gagal mengambil data</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Terjadi kesalahan sistem</td></tr>';
    });
}

function renderBookTable(books) {
    const tableBody = document.getElementById('tableDataBuku');
    let html = '';

    if(books.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Belum ada data buku</td></tr>';
        return;
    }

    books.forEach((b, index) => {
        let penulis = b.nama_penulis || (b.penulis ? b.penulis.nama_penulis : '-');
        let penerbit = b.nama_penerbit || (b.penerbit ? b.penerbit.nama_penerbit : '-');
        let kategori = b.nama_kategori || (b.kategori ? b.kategori.nama_kategori : '-');
        
        let imgHtml = b.foto 
        ? `<img src="/storage/${b.foto}" width="50" style="border-radius: 4px;">` 
        : `<span style="color: #6b7280; font-size: 12px;">No Image</span>`;

        html += `
            <tr>
                <td>${index + 1}</td>
                <td class="font-medium">${b.judul}</td>
                <td>${penulis}</td>
                <td>${penerbit}</td>
                <td>${b.tahun}</td>
                <td>${b.stok}</td>
                <td>${kategori}</td>
                <td>${imgHtml}</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button 
                            onclick="openEditBookModal(this)"
                            data-id="${b.id}"
                            data-judul="${b.judul}"
                            data-penulis="${b.penulis_id}"
                            data-penerbit="${b.penerbit_id}"
                            data-tahun="${b.tahun}"
                            data-stok="${b.stok}"
                            data-kategori_id="${b.kategori_id}"
                            class="btn-custom btn-warning-custom">
                            Edit
                        </button>

                        <button onclick="hapusBuku(${b.id})" 
                         class="btn-custom btn-danger-custom btn-delete-ajax">
                         Hapus
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    tableBody.innerHTML = html;
}


// --- 1. FUNGSI UTAMA LOAD DATA ---
function loadDataBukuMasuk() {
    const tableBody = document.getElementById('tableDataBukuMasuk');
    if (!tableBody) return; // Stop jika bukan halaman data masuk

    tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Sedang memuat data...</td></tr>';

    // Panggil API
    fetch('/api/datamasuk') 
    .then(response => response.json())
    .then(result => {
        if(result.status === 'success') {
            // A. Isi Tabel
            renderBookMasukTable(result.data);
            
            // B. Isi Dropdown Pilihan Buku (PENTING)
            renderBookOptions(result.options_buku);
        } else {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Gagal mengambil data</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Terjadi kesalahan sistem</td></tr>';
    });
}

// --- 2. RENDER TABEL ---
function renderBookMasukTable(data) {
    const tableBody = document.getElementById('tableDataBukuMasuk');
    let html = '';

    if(data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Belum ada data buku masuk</td></tr>';
        return;
    }

    data.forEach((d, index) => {
        // Handle null safety
        let judul = d.judul || '<span style="color:red">Judul Tidak Ditemukan</span>';

        html += `
            <tr>
                <td>${index + 1}</td>
                <td class="font-medium">${judul}</td>
                <td>${d.jumlah}</td>
                <td>${d.tanggal_masuk}</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button 
                            onclick="openEditModal(this)"
                            data-id="${d.id}"
                            data-book_id="${d.book_id}" 
                            data-jumlah="${d.jumlah}"
                            data-tanggal="${d.tanggal_masuk}"
                            class="btn-custom btn-warning-custom">
                            Edit
                        </button>
                        
                        <a href="/datamasuk/hapus/${d.id}" 
                         class="btn-custom btn-danger-custom btn-delete">
                         Hapus
                        </a>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
}

// --- 3. RENDER DROPDOWN ---
function renderBookOptions(books) {
    const select = document.getElementById('inputBookId');
    if(!select) return;

    let options = '<option value="">-- Pilih Judul Buku --</option>';
    
    // Looping data buku untuk dropdown
    books.forEach(b => {
        options += `<option value="${b.id}">${b.judul}</option>`;
    });

    select.innerHTML = options;
}

// --- 4. EXECUTE SAAT LOAD ---
document.addEventListener('DOMContentLoaded', () => {
    // Cek jika elemen tabel ada, baru jalankan fungsi
    if(document.getElementById('tableDataBukuMasuk')) {
        loadDataBukuMasuk();
    }
});


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
    modalForm.reset(); 
    modalForm.action = "/datamasuk/store";
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

function initBookElements() {
    if (!bookModal) {
        bookModal = document.getElementById('bookModal');
        bookForm = document.getElementById('bookModalForm');
        bookTitle = document.getElementById('bookModalTitle');
        bookMethod = document.getElementById('bookMethodContainer');
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
    bookForm.reset(); 
    bookForm.action = "/databuku/store"; 
    bookTitle.innerText = "Tambah Buku Baru";
    if(bookMethod) bookMethod.innerHTML = ""; 
    bookModal.style.display = 'flex';
}
window.openEditBookModal = function(button) {
    initBookElements(); if(!bookModal) return;
    const id = button.getAttribute('data-id');
    
    if(inpB_Judul) inpB_Judul.value = button.getAttribute('data-judul');
    if(inpB_Penulis) inpB_Penulis.value = button.getAttribute('data-penulis');
    if(inpB_Penerbit) inpB_Penerbit.value = button.getAttribute('data-penerbit');
    if(inpB_Tahun) inpB_Tahun.value = button.getAttribute('data-tahun');
    if(inpB_Stok) inpB_Stok.value = button.getAttribute('data-stok');
    if(inpB_Kategori) inpB_Kategori.value = button.getAttribute('data-kategori_id');

    bookForm.action = "/databuku/update/" + id;
    bookTitle.innerText = "Edit Data Buku";
    if(bookMethod) bookMethod.innerHTML = "";
    bookModal.style.display = 'flex';
}
window.closeBookModal = function() {
    initBookElements(); if(bookModal) bookModal.style.display = 'none';
}

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

function setupPaginationHTML() {
    const table = document.getElementById('tableLaporan');
    if (!table) return;

    let pagContainer = document.getElementById('paginationContainer');
    if (!pagContainer) {
        pagContainer = document.createElement('div');
        pagContainer.id = 'paginationContainer';
        pagContainer.style.cssText = "display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 20px;";
        table.parentElement.appendChild(pagContainer);
    }
    
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
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
    
    rowsToShow.forEach(row => row.style.display = '');
    renderPaginationControls();
}

function renderPaginationControls() {
    const pagContainer = document.getElementById('paginationContainer');
    if (!pagContainer) return;

    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    if (totalPages <= 1) {
        pagContainer.innerHTML = ''; return;
    }

    let html = '';
    if (currentPage > 1) {
        html += `<button onclick="changePage(${currentPage - 1})" class="btn-custom" style="padding: 5px 10px; background: #374151; color: white;">&laquo; Prev</button>`;
    }
    html += `<span style="color: white; font-size: 0.9rem;">Page ${currentPage} of ${totalPages}</span>`;
    if (currentPage < totalPages) {
        html += `<button onclick="changePage(${currentPage + 1})" class="btn-custom" style="padding: 5px 10px; background: #374151; color: white;">Next &raquo;</button>`;
    }
    pagContainer.innerHTML = html;
}

window.changePage = function(page) {
    currentPage = page;
    renderTablePartition();
}

window.filterTableByDate = function() {
    const fromDateVal = document.getElementById('filterFrom').value;
    const toDateVal = document.getElementById('filterTo').value;
    const table = document.getElementById('tableLaporan');
    
    if(!table) return;

    const tbody = table.getElementsByTagName('tbody')[0];
    const allRows = Array.from(tbody.getElementsByTagName('tr')); 

    updateExportLinks(fromDateVal, toDateVal);

    filteredRows = allRows.filter(row => {
        const dateCell = row.getElementsByClassName('tgl-pinjam')[0];
        if (!dateCell) return false; 
        const rowDateStr = dateCell.textContent.trim();
        
        let showRow = true;
        if (fromDateVal && rowDateStr < fromDateVal) showRow = false;
        if (toDateVal && rowDateStr > toDateVal) showRow = false;
        return showRow;
    });

    currentPage = 1;
    const noDataMsg = document.getElementById('noDataMessage');
    
    if (filteredRows.length === 0) {
        if(noDataMsg) noDataMsg.style.display = "block";
        table.style.display = "none";
        document.getElementById('paginationContainer').innerHTML = '';
    } else {
        if(noDataMsg) noDataMsg.style.display = "none";
        table.style.display = "table";
        renderTablePartition();
    }
}

window.resetFilter = function() {
    document.getElementById('filterFrom').value = "";
    document.getElementById('filterTo').value = "";
    
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

document.addEventListener('DOMContentLoaded', () => {

    loadDataBuku();

    initModalElements();
    initBookElements();
    initLoanElements();
    initUserElements();

    setupPaginationHTML();
    renderTablePartition(); 

    window.onclick = function(event) {
        if (modal && event.target == modal) modal.style.display = 'none';
        if (bookModal && event.target == bookModal) bookModal.style.display = 'none';
        if (loanModal && event.target == loanModal) loanModal.style.display = 'none';
        if (userModal && event.target == userModal) userModal.style.display = 'none';
    }

    const loginBtn = document.getElementById('loginBtn');
    if (loginBtn) {
        loginBtn.addEventListener("click", function (e) {
            e.preventDefault(); 
            const emailVal = document.getElementById('email').value.trim();
            const passVal = document.getElementById('password').value.trim();
            if (!emailVal || !passVal) {
                showModal("Woi bro email sama password jangan kosong lah 😤");
                return;
            }
            document.getElementById('login-form').submit();
        });
    }

    const closeModalBtn = document.getElementById("closeModal");
    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", () => {
            document.getElementById("popupModal").style.display = "none";
        });
    }

    const menuToggleBtn = document.getElementById('menu-toggle-btn');
    if (menuToggleBtn) {
        menuToggleBtn.addEventListener('click', () => {
            const navMobile = document.getElementById('nav-mobile');
            navMobile.style.display = (navMobile.style.display === 'block') ? 'none' : 'block';
        });
    }

    const deleteButtons = document.querySelectorAll('.btn-delete');
    if(deleteButtons.length > 0) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Cegah jika ini tombol ajax (biar ga double confirm)
                if(this.classList.contains('btn-delete-ajax')) return; 

                e.preventDefault();
                const href = this.getAttribute('href');
                if (confirm("Yakin mau hapus data ini secara permanen?")) {
                    window.location.href = href;
                }
            });
        });
    }
});