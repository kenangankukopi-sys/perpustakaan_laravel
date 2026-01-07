<div class="col-md-9 col-lg-10 p-5 text-center">
    @if(Session::has('id'))
        <h2>Selamat Datang di Aplikasi Perpustakaan</h2>
        <p class="mt-3 fs-5">
            Kamu login sebagai:
            @if(Session::get('level') == 1)
                <span class="badge bg-danger">Admin</span>
            @elseif(Session::get('level') == 2)
                <span class="badge bg-warning text-dark">Petugas</span>
            @elseif(Session::get('level') == 3)
                <span class="badge bg-success">Anggota</span>
            @elseif(Session::get('level') == 4)
                <span class="badge bg-success">manager</span>
            @else
                <span class="badge bg-secondary">Tamu</span>
            @endif
        </p>
    @else
        <h2>Selamat Datang di Aplikasi Perpustakaan</h2>
        <p class="mt-3 fs-5">Silakan login terlebih dahulu untuk melanjutkan.</p>
        <a href="/login" class="btn btn-primary">Login</a>
    @endif
</div>
