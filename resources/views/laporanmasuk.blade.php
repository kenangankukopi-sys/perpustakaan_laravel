<div class="content-wrapper">
    <div class="container">
        <div class="card-custom">
            
            <div class="card-header" style="background-color: #1f2937; border-bottom: 1px solid #374151; padding: 20px;">
                <h2 class="card-title" style="color: white; font-size: 1.5rem; margin: 0;">📥 Laporan Buku Masuk</h2>
            </div>

            <div style="padding: 24px;">
                
                <!-- FILTER SECTION (ID & Class sama biar JS jalan) -->
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

                <!-- EXPORT BUTTONS (Tambahkan data-base-url untuk JS baru) -->
                <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ url('/laporanmasuk/print') }}" id="btnPrint" target="_blank" 
                    data-base-url="/laporanmasuk/print"
                    class="btn-custom" style="background-color: #4b5563; color: white;">
                    🖨️ Print
                </a>
                <a href="{{ url('/laporanmasuk/pdf') }}" id="btnPdf" 
                data-base-url="/laporanmasuk/pdf"
                class="btn-custom btn-danger-custom">
                📄 Export PDF
            </a>
            <a href="{{ url('/laporanmasuk/excel') }}" id="btnExcel" 
            data-base-url="/laporanmasuk/excel"
            class="btn-custom" style="background-color: #10b981; color: white;">
            📊 Export Excel
        </a>
    </div>

    <!-- TABEL LAPORAN (ID tableLaporan) -->
    <div class="table-responsive">
        <table class="table-custom" id="tableLaporan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Tanggal Masuk</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporan as $l)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="font-medium" style="color: #60a5fa;">{{ $l->judul }}</td>
                    <td>{{ $l->nama_penulis ?? '-' }}</td>
                    
                    <!-- CLASS 'tgl-pinjam' PENTING AGAR DIBACA OLEH FILTER JS -->
                    <td class="tgl-pinjam">{{ $l->tanggal_masuk }}</td>
                    
                    <td>
                        <span style="padding: 4px 10px; border-radius: 99px; font-size: 12px; background: #374151; color: white; border: 1px solid #4b5563;">
                            + {{ $l->jumlah }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Pesan Kosong -->
        <div id="noDataMessage" style="display: none; text-align: center; padding: 40px; color: #9ca3af;">
            <p style="font-size: 1.1rem;">🚫 Tidak ada data buku masuk pada rentang tanggal tersebut.</p>
        </div>
    </div>

</div>
</div>
</div>
</div>