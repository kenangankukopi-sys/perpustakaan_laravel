<div class="content-wrapper">
    <div class="container">
        <!-- Card Container -->
        <div class="card-custom">

            <div class="card-header">
                <h2 class="card-title">Data Buku Dipinjam</h2>
                <!-- Tombol Tambah (Panggil JS Baru) -->
                <button onclick="openAddLoanModal()" class="btn-custom btn-primary-custom">
                    + Tambah Peminjaman
                </button>
            </div>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Peminjam</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="font-medium">{{ $d->judul }}</td>
                            <td>{{ $d->name }}</td>
                            <td>{{ $d->tanggal_pinjam }}</td>
                            <td>{{ $d->tanggal_kembali ?? '-' }}</td>
                            <td>
                                <!-- Badge Status Sederhana -->
                                <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: {{ $d->status == 'Dipinjam' ? '#b91c1c' : '#047857' }}; color: white;">
                                    {{ ucfirst($d->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <!-- Tombol Edit -->
                                    <button 
                                    onclick="openEditLoanModal(this)"
                                    data-id="{{ $d->id }}"
                                    data-book_id="{{ $d->book_id }}"
                                    data-user_id="{{ $d->user_id }}"
                                    data-tanggal_pinjam="{{ $d->tanggal_pinjam }}"
                                    data-status="{{ $d->status }}"
                                    class="btn-custom btn-warning-custom">
                                    Edit
                                </button>

                                <!-- Tombol Hapus -->
                                <a href="/peminjaman/hapus/{{ $d->id }}" 
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

<!-- === MODAL PEMINJAMAN === -->
<div id="loanModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="loanModalTitle" class="modal-title">Tambah Peminjaman</h3>
            <button onclick="closeLoanModal()" class="btn-close-modal">&times;</button>
        </div>
        
        <form id="loanModalForm" method="POST" action="">
            @csrf
            <!-- Container ID Hidden -->
            <div id="loanMethodContainer"></div>

            <div class="modal-body">

                <!-- Dropdown Buku -->
                <div class="form-group">
                    <label>Judul Buku</label>
                    <select name="book_id" id="loanBookId" class="form-input" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($books as $b)
                        <option value="{{ $b->id }}">{{ $b->judul }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown User -->
                <div class="form-group">
                    <label>Peminjam</label>
                    <select name="user_id" id="loanUserId" class="form-input" required>
                        <option value="">-- Pilih Peminjam --</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label>Tgl Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="loanTglPinjam" class="form-input" required>
                    </div>
                    <div style="flex: 1;">
                        <label>Tgl Kembali</label>
                        <input type="date" name="tanggal_kembali" id="loanTglKembali" class="form-input">
                    </div>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="loanStatus" class="form-input" required>
                        <option value="Dipinjam">Dipinjam</option>
                        <option value="Kembali">Kembali</option>
                    </select>
                </div>

            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="closeLoanModal()" class="btn-custom btn-secondary-custom">Batal</button>
                <button type="submit" class="btn-custom btn-primary-custom">Simpan</button>
            </div>
        </form>
    </div>
</div>