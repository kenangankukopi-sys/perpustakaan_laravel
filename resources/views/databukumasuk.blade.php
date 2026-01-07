<div class="content-wrapper">
    <div class="container">
        <div class="card-custom">

            <div class="card-header">
                <h2 class="card-title">Data Buku Masuk</h2>
                <button onclick="openAddModal()" class="btn-custom btn-primary-custom">
                    + Tambah Data
                </button>
            </div>

            <!-- Table Wrapper -->
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Jumlah</th>
                            <th>Tanggal Masuk</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($data as $d)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td class="font-medium">{{ $d->judul }}</td>
                            <td>{{ $d->jumlah }}</td>
                            <td>{{ $d->tanggal_masuk }}</td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <button 
                                    onclick="openEditModal(this)"
                                    data-id="{{ $d->id }}"
                                    data-judul="{{ $d->judul }}"
                                    data-jumlah="{{ $d->jumlah }}"
                                    data-tanggal="{{ $d->tanggal_masuk }}"
                                    class="btn-custom btn-warning-custom">
                                    Edit
                                </button>

                                <!-- Tombol Hapus -->
                                <a href="/datamasuk/hapus/{{ $d->id }}" 
                                   class="btn-custom btn-danger-custom btn-delete">
                                   Hapus
                               </a>
                           </div>
                       </td>
                   </tr>
                   @endforeach
               </tbody>
           </table>
       </div>
   </div>
</div>
</div>

<div id="dataModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="modalTitle" class="modal-title">Tambah Data Masuk</h3>
            <button onclick="closeModal()" class="btn-close-modal">&times;</button>
        </div>
        
        <form id="modalForm" method="POST" action="">
            @csrf
            <div id="methodInputContainer"></div>

            <div class="modal-body">

                <div class="form-group">
                    <input type="hidden" name="id" id="idbuku" class="form-input">
                </div>

                <div class="form-group">
                    <label>Pilih Buku</label>
                    <select name="book_id" id="inputBookId" class="form-input" required>
                        <option value="">-- Pilih Judul Buku --</option>
                        @foreach($books as $b)
                        <option value="{{ $b->id }}">{{ $b->judul }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" id="inputJumlah" class="form-input" required placeholder="0">
                </div>

                <div class="form-group">
                    <label>Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" id="inputTanggal" class="form-input" required>
                </div>

            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="closeModal()" class="btn-custom btn-secondary-custom">Batal</button>
                <button type="submit" class="btn-custom btn-primary-custom">Simpan</button>
            </div>
        </form>
    </div>
</div>