<div class="content">
    <div class="container-xxl mt-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="mb-3">Koleksi Buku</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <form method="GET" action="/koleksi" class="mb-3 d-flex gap-2">
                            <select name="kategori_id" class="form-select w-auto">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriList as $k)
                                <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </form>

                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($buku as $b)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $b->judul }}</td>
                                    <td>{{ $b->nama_penulis }}</td>
                                    <td>{{ $b->nama_penerbit }}</td>
                                    <td>{{ $b->tahun }}</td>
                                    <td>{{ $b->nama_kategori }}</td>
                                    <td>
                                        @if($b->stok > 0)
                                            <span class="badge bg-success">{{ $b->stok }}</span>
                                        @else
                                            <span class="badge bg-danger">Habis</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($b->foto)
                                            <img src="{{ asset('storage/'.$b->foto) }}" width="70" class="img-thumbnail">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($b->stok > 0)
                                            <form action="/peminjaman/pinjam/{{ $b->id }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Pinjam Buku
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>
                                                Tidak Tersedia
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
