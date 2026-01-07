<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ !empty($p) && $p ? 'Edit Buku' : 'Tambah Buku' }}</title>
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow">
      <div class="card-body">
        <h4 class="mb-4">{{ !empty($p) && $p ? 'Edit Buku' : 'Tambah Buku' }}</h4>

        <form 
          action="@if(!empty($p) && $p) {{ url('/databuku/update/'.$buku->id) }} @else {{ url('/databuku/save') }} @endif" 
          method="POST" 
          enctype="multipart/form-data"
        >
          @csrf

          @if(!empty($p) && $p)
            <input type="hidden" name="id" value="{{ $buku->id }}">
          @endif

          <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul', $buku->judul ?? '') }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Penulis</label>
            <select name="penulis_id" class="form-select" required>
              <option value="">Pilih Penulis</option>
              @foreach($penulis as $pen)
                <option 
                  value="{{ $pen->id }}" 
                  {{ (!empty($buku) && $buku->penulis_id == $pen->id) ? 'selected' : '' }}
                >
                  {{ $pen->nama_penulis }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Penerbit</label>
            <select name="penerbit_id" class="form-select" required>
              <option value="">Pilih Penerbit</option>
              @foreach($penerbit as $pn)
                <option 
                  value="{{ $pn->id }}" 
                  {{ (!empty($buku) && $buku->penerbit_id == $pn->id) ? 'selected' : '' }}
                >
                  {{ $pn->nama_penerbit }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Tahun</label>
            <input 
              type="number" 
              name="tahun" 
              class="form-control" 
              value="{{ old('tahun', $buku->tahun ?? '') }}" 
              required
            >
          </div>

          <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-select" required>
              <option value="">Pilih Kategori</option>
              @foreach($kategori as $k)
                <option 
                  value="{{ $k->id }}" 
                  {{ (!empty($buku) && $buku->kategori_id == $k->id) ? 'selected' : '' }}
                >
                  {{ $k->nama_kategori }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Stok</label>
            <input 
              type="number" 
              name="stok" 
              class="form-control" 
              value="{{ old('stok', $buku->stok ?? '') }}" 
              required
            >
          </div>

          <div class="mb-3">
            <label class="form-label">Foto</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
            @if(!empty($buku) && $buku->foto)
              <div class="mt-2">
                <img src="{{ asset('storage/'.$buku->foto) }}" width="100" class="img-thumbnail" alt="Cover Buku">
              </div>
            @endif
          </div>

          <div class="d-flex justify-content-between">
            <a href="/databuku" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success">
              {{ !empty($p) && $p ? 'Update' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
