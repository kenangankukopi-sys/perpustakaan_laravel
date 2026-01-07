<div class="content-wrapper">
    <div class="container">
        <div class="card-custom">
            
            <div class="card-header" style="background-color: #1f2937; border-bottom: 1px solid #374151; padding: 20px;">
                <h2 class="card-title" style="color: white; font-size: 1.5rem; margin: 0;">📊 Laporan Peminjaman</h2>
            </div>

            <div style="padding: 24px;">
                
                <!-- FILTER SECTION -->
                <div style="background-color: #111827; padding: 20px; border-radius: 8px; border: 1px solid #374151; margin-bottom: 24px;">
                    <div style="display: flex; gap: 20px; align-items: flex-end; flex-wrap: wrap;">
                        
                        <!-- Input Dari Tanggal -->
                        <div style="flex: 1; min-width: 200px;">
                            <label style="color: #9ca3af; font-size: 0.875rem; margin-bottom: 5px; display: block;">Dari Tanggal</label>
                            <input type="date" id="filterFrom" class="form-input" style="height: 42px;">
                        </div>

                        <!-- Input Sampai Tanggal -->
                        <div style="flex: 1; min-width: 200px;">
                            <label style="color: #9ca3af; font-size: 0.875rem; margin-bottom: 5px; display: block;">Sampai Tanggal</label>
                            <input type="date" id="filterTo" class="form-input" style="height: 42px;">
                        </div>

                        <!-- Tombol Filter JS -->
                        <div style="display: flex; gap: 10px;">
                            <button onclick="filterTableByDate()" class="btn-custom btn-primary-custom" style="height: 42px; padding: 0 25px;">
                                🔍 Terapkan Filter
                            </button>
                            <button onclick="resetFilter()" class="btn-custom btn-secondary-custom" style="height: 42px;">
                                🔄 Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- EXPORT BUTTONS -->
                <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ url('/laporanpeminjaman/print') }}" id="btnPrint" target="_blank" 
                       class="btn-custom" style="background-color: #4b5563; color: white;">
                       🖨️ Print
                    </a>
                    <a href="{{ url('/laporanpeminjaman/pdf') }}" id="btnPdf" 
                       class="btn-custom btn-danger-custom">
                       📄 Export PDF
                    </a>
                    <a href="{{ url('/laporanpeminjaman/excel') }}" id="btnExcel" 
                       class="btn-custom" style="background-color: #10b981; color: white;">
                       📊 Export Excel
                    </a>
                </div>

                <!-- TABEL LAPORAN -->
                <div class="table-responsive">
                    <table class="table-custom" id="tableLaporan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>Judul Buku</th>
                                <th>Kategori</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporan as $l)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $l->nama_peminjam }}</td>
                                    <td class="font-medium" style="color: #60a5fa;">{{ $l->judul_buku }}</td>
                                    <td>{{ $l->kategori ?? '-' }}</td>
                                    <td class="tgl-pinjam">{{ $l->tanggal_pinjam }}</td>
                                    <td>{{ $l->tanggal_kembali ?? '-' }}</td>
                                    <td>
                                        @if($l->status == 'Dipinjam')
                                            <span style="padding: 5px 10px; border-radius: 99px; font-size: 11px; font-weight: bold; background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid #ef4444;">
                                                SEDANG DIPINJAM
                                            </span>
                                        @elseif($l->status == 'Dikembalikan')
                                            <span style="padding: 5px 10px; border-radius: 99px; font-size: 11px; font-weight: bold; background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid #10b981;">
                                                DIKEMBALIKAN
                                            </span>
                                        @else
                                            <span style="padding: 5px 10px; border-radius: 99px; font-size: 11px; background: #374151; color: white;">
                                                {{ $l->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Pesan jika data kosong setelah difilter -->
                    <div id="noDataMessage" style="display: none; text-align: center; padding: 40px; color: #9ca3af;">
                        <p style="font-size: 1.1rem;">🚫 Tidak ada data pada rentang tanggal tersebut.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>