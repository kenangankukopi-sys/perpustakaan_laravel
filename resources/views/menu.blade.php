@php
    // Ambil ID user dari session
    $userId = session('id');
    
    // Cari data user untuk dapatkan level_id nya
    $user = DB::table('users')->where('id', $userId)->first();
    
    // Ambil level_id (Kalau user tidak ketemu, set 0)
    $level = $user ? $user->level_id : 0;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Kekinian</title>
    <!-- Pastikan CSS terpanggil -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bodymenu">
    <header class="main-header">
        <div class="container navbar-content">
            <!-- LOGO -->
            <div class="logo-text">
                <span class="logo-icon">📖</span> PerpustakaanKekinian
            </div>

            <div style="display: flex; align-items: center;">
                <nav class="nav-desktop">
                    
                    {{-- =================================================== --}}
                    {{-- LOGIC 1: DATA MASTER (Untuk Level 1 & 2)            --}}
                    {{-- =================================================== --}}
                    @if($level == 1 || $level == 2)
                        <div class="nav-item dropdown">
                            <button class="dropdown-btn">
                                Data Master
                                <svg class="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="dropdown-content">
                                <a href="/databuku">Data Buku</a>
                                <a href="/datamasuk">Data Buku Masuk</a>
                                <a href="/peminjaman">Data Peminjaman</a>
                            </div>
                        </div>
                    @endif

                    {{-- =================================================== --}}
                    {{-- LOGIC 2: DATA USER (Hanya Level 1)                  --}}
                    {{-- =================================================== --}}
                    @if($level == 1)
                        <a href="/datauser" class="nav-link">Data User</a>
                    @endif

                    {{-- =================================================== --}}
                    {{-- LOGIC 3: LAPORAN (Untuk Level 1 & 4)                --}}
                    {{-- =================================================== --}}
                    @if($level == 1 || $level == 4)
                        <div class="nav-item dropdown">
                            <button class="dropdown-btn">
                                Laporan
                                <svg class="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="dropdown-content">
                                <a href="/laporanpeminjaman">Laporan Peminjaman</a>
                                <a href="/laporanmasuk">Laporan Buku Masuk</a>
                            </div>
                        </div>
                    @endif

                    {{-- =================================================== --}}
                    {{-- LOGIC 4: MENU ANGGOTA (Hanya Level 3)               --}}
                    {{-- =================================================== --}}
                    @if($level == 3)
                        <a href="/koleksi" class="nav-link">📚 Koleksi Buku</a>
                        <a href="/riwayat" class="nav-link">🕒 Riwayat Peminjaman</a>
                    @endif

                </nav>

                <!-- TOMBOL LOGOUT -->
                <a href="/logout" class="btn-logout-custom">
                    Logout
                </a>
            </div>
            
            <!-- TOMBOL MOBILE (HAMBURGER) -->
            <button id="menu-toggle-btn" class="menu-toggle-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>
        
        <!-- MOBILE MENU OVERLAY (Logic Dicopy Kesini Juga) -->
        <div id="nav-mobile" class="nav-mobile" style="display: none;">
            
            @if($level == 1 || $level == 2)
                <div class="nav-mobile-group">
                    <p>Data Master</p>
                    <a href="/databuku">Data Buku</a>
                    <a href="/datamasuk">Data Buku Masuk</a>
                    <a href="/peminjaman">Data Peminjaman</a>
                </div>
            @endif

            @if($level == 1)
                <div class="nav-mobile-group">
                    <a href="/datauser">Data User</a>
                </div>
            @endif

            @if($level == 1 || $level == 4)
                <div class="nav-mobile-group">
                    <p>Laporan</p>
                    <a href="/laporanpeminjaman">Laporan Peminjaman</a>
                    <a href="/laporanmasuk">Laporan Buku Masuk</a>
                </div>
            @endif

            @if($level == 3)
                <div class="nav-mobile-group">
                    <a href="/koleksi">📚 Koleksi Buku</a>
                    <a href="/riwayat">🕒 Riwayat Peminjaman</a>
                </div>
            @endif

        </div>
    </header>
    
    <!-- Script JS -->
    <script src="{{ asset('js/js.js') }}"></script>
</body>
</html>