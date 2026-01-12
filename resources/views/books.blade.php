<div class="content-wrapper">
    <div class="container">
        <div class="card-custom">

            <div class="card-header">
                <h2 class="card-title">Daftar Buku</h2>
                <button onclick="openAddBookModal()" class="btn-custom btn-primary-custom">
                    + Tambah Buku
                </button>
            </div>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Stok</th>
                            <th>Kategori</th>
                            <th>Foto</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableDataBuku">
                        <tr>
                            <td colspan="9" class="text-center">Sedang memuat data...</td>
                        </tr>
                    </tbody>         
                </table>
            </div>
        </div>
    </div>
</div>

<div id="bookModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="bookModalTitle" class="modal-title">Tambah Buku Baru</h3>
            <button onclick="closeBookModal()" class="btn-close-modal">&times;</button>
        </div>

        <form id="bookModalForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div id="bookMethodContainer"></div>

            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">

                <div class="form-group">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" id="bookJudul" class="form-input" required>
                </div>

                <div class="form-group">
                    <label>Penulis</label>
                    <select name="penulis_id" id="bookPenulis" class="form-input" required>
                        <option value="">-- Pilih Penulis --</option>
                        @foreach($penulis as $ps)
                        <option value="{{ $ps->id }}">{{ $ps->nama_penulis }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Penerbit</label>
                    <select name="penerbit_id" id="bookPenerbit" class="form-input" required>
                        <option value="">-- Pilih Penerbit --</option>
                        @foreach($penerbit as $pt)
                        <option value="{{ $pt->id }}">{{ $pt->nama_penerbit }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label>Tahun Terbit</label>
                        <input type="number" name="tahun" id="bookTahun" class="form-input" required>
                    </div>
                    <div style="flex: 1;">
                        <label>Stok Awal</label>
                        <input type="number" name="stok" id="bookStok" class="form-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_id" id="bookKategori" class="form-input" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Foto Sampul</label>
                    <input type="file" name="foto" class="form-input" style="padding-top: 8px;">
                    <small style="color: #9ca3af; display: block; margin-top: 5px;">*Biarkan kosong jika tidak ingin mengganti foto saat edit.</small>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeBookModal()" class="btn-custom btn-secondary-custom">Batal</button>
                <button type="submit" class="btn-custom btn-primary-custom">Simpan</button>
            </div>
        </form>
    </div>
</div>