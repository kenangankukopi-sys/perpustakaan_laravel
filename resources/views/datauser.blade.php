<div class="content-wrapper">
    <div class="container">
        <div class="card-custom">

            <div class="card-header">
                <h2 class="card-title">Data Pengguna</h2>
                <button onclick="openAddUserModal()" class="btn-custom btn-primary-custom">
                    + Tambah User
                </button>
            </div>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="font-medium">{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: #374151; color: #e5e7eb; border: 1px solid #4b5563;">
                                    {{ $u->nama_level ?? 'User' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <button 
                                    onclick="openEditUserModal(this)"
                                    data-id="{{ $u->id }}"
                                    data-name="{{ $u->name }}"
                                    data-email="{{ $u->email }}"
                                    data-level_id="{{ $u->level_id }}" 
                                    class="btn-custom btn-warning-custom">
                                    Edit
                                </button>

                                <a href="/datauser/reset/{{ $u->id }}" 
                                   class="btn-custom btn-info-custom"
                                   onclick="return confirm('Yakin mau reset password user ini menjadi 12345678?')">
                                   Reset
                               </a>

                               <a href="/datauser/delete/{{ $u->id }}" 
                                   class="btn-custom btn-danger-custom"
                                   onclick="return confirm('Yakin mau hapus user {{ $u->name }}? Data ini akan hilang permanen!')">
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

<!-- === MODAL DATA USER (WAJIB ADA DI VIEW) === -->
<div id="userModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="userModalTitle" class="modal-title">Edit User</h3>
            <button onclick="closeUserModal()" class="btn-close-modal">&times;</button>
        </div>
        
        <form id="userModalForm" method="POST" action="">
            @csrf
            <div id="userMethodContainer"></div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" id="userName" class="form-input" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="userEmail" class="form-input" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="userPassword" class="form-input" placeholder="******">
                    <small id="passwordHint" style="color: #9ca3af; display: none; margin-top: 5px;">*Kosongkan jika tidak ingin ganti password</small>
                </div>

                <div class="form-group">
                    <label>Level Akses</label>
                    <select name="level_id" id="userLevel" class="form-input" required>
                        <option value="">-- Pilih Level --</option>
                        @foreach($levels as $l)
                        <option value="{{ $l->id }}">{{ $l->nama_level }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="closeUserModal()" class="btn-custom btn-secondary-custom">Batal</button>
                <button type="submit" class="btn-custom btn-primary-custom">Simpan</button>
            </div>
        </form>
    </div>
</div>