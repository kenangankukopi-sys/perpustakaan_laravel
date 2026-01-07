<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Elibyy\TCPDF\Facades\TCPDF;
use Spatie\SimpleExcel\SimpleExcelWriter;

class MyController extends Controller
{
    // ------------------- LOGIN -------------------
    public function showlogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $user = DB::table('users')
        ->where('email', $request->email)
        ->where('password', $request->password)
        ->first();

        if ($user) {
            Session::put('id', $user->id);
            Session::put('name', $user->name);
            Session::put('level', $user->level_id);
            return redirect('/home');
        } else {
            return back()->with('error', 'Email atau password salah');
        }
    }

    public function home()
    {
        if (session('id') > 0) {
            echo view('header');
            echo view('menu');
            echo view('home');
            echo view('footer');
        } else {
            echo view('404');
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }

    // ------------------- REGISTER -------------------
    public function showRegister()
    {
        return view('register');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3',
        ]);

        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'level_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('peminjams')->insert([
            'user_id' => $userId,
            'alamat' => $request->alamat ?? null,
            'no_hp' => $request->no_hp ?? null,
            'jenis_kelamin' => $request->jenis_kelamin ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function storeUser(Request $request)
    {
        $user_id = DB::table('users')->insertGetId([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => bcrypt('default123'), 
            'level_id'   => $request->level,
            'created_at' => now()
        ]);

        if ($request->level == 2) {
            DB::table('petugas')->insert([
                'user_id' => $user_id,
                'created_at' => now()
            ]);
        }

        if ($request->level == 3) {
            DB::table('peminjam')->insert([
                'user_id' => $user_id,
                'created_at' => now()
            ]);
        }

        return redirect('/datauser')->with('success','User added!');
    }

    // ------------------- BUKU -------------------
    public function books()
    {
        if (session('id') > 0) {
            $buku = DB::table('books')
            ->join('penulis', 'books.penulis_id', '=', 'penulis.id')
            ->join('penerbit', 'books.penerbit_id', '=', 'penerbit.id')
            ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
            ->select('books.*', 'penulis.nama_penulis', 'penerbit.nama_penerbit', 'kategori.nama_kategori')
            ->get();

            $penulis = DB::table('penulis')->get();
            $penerbit = DB::table('penerbit')->get();
            $kategori = DB::table('kategori')->get();

            echo view('header');
            echo view('menu');
            echo view('books', compact('buku', 'penulis', 'penerbit', 'kategori'));
            echo view('footer');
        } else {
            echo view('404');
        }
    }

    public function tambah()
    {
        if (session('id') > 0) {
            $penulis = DB::table('penulis')->get();
            $penerbit = DB::table('penerbit')->get();
            $kategori = DB::table('kategori')->get();

            return view('form', compact('penulis', 'penerbit', 'kategori'));
        } else {
            return view('404');
        }
    }

    public function simpant(Request $request)
    {
        if (session('id') > 0) {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('covers', 'public');
            }

            DB::table('books')->insert([
                'judul' => $request->judul,
                'penulis_id' => $request->penulis_id,
                'penerbit_id' => $request->penerbit_id,
                'tahun' => $request->tahun,
                'kategori_id' => $request->kategori_id,
                'stok' => $request->stok,
                'foto' => $fotoPath
            ]);

            return redirect('/databuku')->with('success', 'Buku berhasil ditambahkan!');
        } else {
            return view('404');
        }
    }

    public function edit($id)
    {
        if (session('id') > 0) {
            $buku = DB::table('books')->where('id', $id)->first();
            $penulis = DB::table('penulis')->get();
            $penerbit = DB::table('penerbit')->get();
            $kategori = DB::table('kategori')->get();

            return view('form', compact('buku', 'penulis', 'penerbit', 'kategori'));
        } else {
            return view('404');
        }
    }

    public function simpane(Request $request)
    {
        if (session('id') > 0) {
            $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('covers', 'public') : null;

            $updateData = [
                'judul' => $request->judul,
                'penulis_id' => $request->penulis_id,
                'penerbit_id' => $request->penerbit_id,
                'tahun' => $request->tahun,
                'kategori_id' => $request->kategori_id,
                'stok' => $request->stok,
            ];

            if ($fotoPath) {
                $updateData['foto'] = $fotoPath;
            }

            DB::table('books')->where('id', $request->id)->update($updateData);

            return redirect('/databuku')->with('success', 'Buku berhasil diperbarui!');
        } else {
            return view('404');
        }
    }

    public function delete($id)
    {
        if (session('id') > 0) {
            DB::table('books')->where('id', $id)->delete();
            return redirect('/databuku')->with('success', 'Buku berhasil dihapus!');
        } else {
            return view('404');
        }
    }

    // ------------------- DATA MASUK BUKU -------------------
    public function dataMasukBuku()
    {
        if (session('id') > 0) {
            $data = DB::table('data_masuk_buku')
            ->join('books', 'data_masuk_buku.book_id', '=', 'books.id')
            ->select(
                'data_masuk_buku.id',
                'books.judul',
                'data_masuk_buku.jumlah',
                'data_masuk_buku.tanggal_masuk',
                'data_masuk_buku.book_id'
            )
            ->get();

            $books = DB::table('books')->select('id', 'judul')->get();

            echo view('header');
            echo view('menu');
            echo view('databukumasuk', compact('data', 'books'));
            echo view('footer');
        } else {
            echo view('404');
        }
    }

    public function tambahDataMasuk()
    {
        if (session('id') > 0) {
            $books = DB::table('books')->get();
            $data = null;

            echo view('header');
            echo view('menu');
            echo view('form_datamasuk', compact('books', 'data'));
            echo view('footer');
        } else {
            echo view('404');
        }
    }

    public function simpanDataMasuk(Request $request)
    {
        if (session('id') > 0) {
            DB::table('data_masuk_buku')->insert([
                'book_id' => $request->book_id,
                'jumlah' => $request->jumlah,
                'tanggal_masuk' => $request->tanggal_masuk
            ]);

            DB::table('books')->where('id', $request->book_id)->increment('stok', $request->jumlah);

            return redirect('/datamasuk')->with('success', 'Data buku masuk ditambahkan!');
        } else {
            echo view('404');
        }
    }

    public function editDataMasuk($id)
    {
        if (session('id') > 0) {
            $data = DB::table('data_masuk_buku')->where('id', $id)->first();
            $books = DB::table('books')->get();

            echo view('header');
            echo view('menu');
            echo view('form_datamasuk', compact('data', 'books'));
            echo view('footer');
        } else {
            echo view('404');
        }
    }

    public function updateDataMasuk(Request $request)
    {
        if (session('id') > 0) {
            DB::table('data_masuk_buku')->where('id', $request->id)->update([
                'book_id' => $request->book_id,
                'jumlah' => $request->jumlah,
                'tanggal_masuk' => $request->tanggal_masuk
            ]);

            return redirect('/datamasuk')->with('success', 'Data berhasil diperbarui!');
        } else {
            echo view('404');
        }
    }

    public function hapusDataMasuk($id)
    {
        if (session('id') > 0) {
            DB::table('data_masuk_buku')->where('id', $id)->delete();

            return redirect('/datamasuk')->with('success', 'Data dihapus!');
        } else {
            echo view('404');
        }
    }

    // ------------------- PEMINJAMAN -------------------
    public function peminjaman()
    {
        if (session('id') > 0) {
            $data = DB::table('peminjaman_buku')
            ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
            ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
            ->join('users', 'peminjaman_buku.user_id', '=', 'users.id')
            ->select(
                'peminjaman_buku.id',
                'books.judul',
                'users.name',
                'peminjaman_buku.tanggal_pinjam',
                'peminjaman_buku.tanggal_kembali',
                'peminjaman_buku.status',
                'peminjaman_buku.book_id',
                'peminjaman_buku.user_id'
            )
            ->get();

            $books = DB::table('books')->select('id', 'judul')->get();
            $users = DB::table('users')->select('id', 'name')->get();

            echo view('header');
            echo view('menu');
            echo view('datapeminjamanbuku', compact('data', 'books', 'users'));
            echo view('footer');
        } else {
            echo view('404');
        }
    }

    public function tambahPeminjaman()
    {
        if (session('id') > 0) {
            $books = DB::table('books')->get();
            $users = DB::table('users')->get();
            $data = null;

            echo view('header');
            echo view('menu');
            echo view('form_peminjaman', compact('books', 'users', 'data'));
            echo view('footer');
        } else {
            echo view('404');
        }
    }

    public function simpanPeminjaman(Request $request)
    {
        if (session('id') > 0) {
            DB::table('peminjaman_buku')->insert([
                'book_id' => $request->book_id,
                'user_id' => $request->user_id,
                'tanggal_pinjam' => now()->format('Y-m-d'),
                'tanggal_kembali' => null,
                'status' => 'dipinjam'
            ]);

            return redirect('/peminjaman')->with('success', 'Peminjaman ditambahkan!');
        } else {
            echo view('404');
        }
    }

    public function editPeminjaman($id)
    {
        if (session('id') > 0) {
            $data = DB::table('peminjaman_buku')->where('id', $id)->first();
            $books = DB::table('books')->get();
            $users = DB::table('users')->get();

            echo view('header');
            echo view('menu');
            echo view('form_peminjaman', compact('data', 'books', 'users'));
            echo view('footer');
        } else {
            echo view('404');
        }
    }

    public function updatePeminjaman(Request $request)
    {
        if (session('id') > 0) {
            DB::table('peminjaman_buku')->where('id', $request->id)->update([
                'book_id' => $request->book_id,
                'user_id' => $request->user_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => $request->status
            ]);

            return redirect('/peminjaman')->with('success', 'Data diperbarui!');
        } else {
            echo view('404');
        }
    }

    public function hapusPeminjaman($id)
    {
        if (session('id') > 0) {
            DB::table('peminjaman_buku')->where('id', $id)->delete();
            return redirect('/peminjaman')->with('success', 'Data dihapus!');
        } else {
            echo view('404');
        }
    }

    public function kembalikanPeminjaman($id)
    {
        if (session('id') > 0) {
            DB::table('peminjaman_buku')->where('id', $id)->update([
                'tanggal_kembali' => now(),
                'status' => 'dikembalikan'
            ]);

            return redirect('/peminjaman')->with('success', 'Buku dikembalikan!');
        } else {
            echo view('404');
        }
    }

    // ------------------- DATA USER -------------------
    public function dataUser()
    {
        if (session('id') > 0) {
            $users = DB::table('users')
            ->leftJoin('levels', 'users.level_id', '=', 'levels.id')
            ->select('users.*', 'levels.nama_level')
            ->orderBy('users.id', 'desc')
            ->get();

            $levels = DB::table('levels')->get();

            echo view('header');
            echo view('menu');
            echo view('datauser', compact('users', 'levels'));
            echo view('footer');
        } else {
            echo view('404');
        }
    }

    public function resetPassword($id)
    {
        DB::table('users')->where('id', $id)->update([
            'password' => ('12345678'),
            'updated_at' => now()
        ]);

        return redirect('/datauser')->with('success', 'Password berhasil direset!');
    }

    public function editUser($id)
    {
        if (!session('id')) {
            return view('404');
        }

        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return abort(404);
        }

        return view('form_datauser', compact('user'));
    }

    public function storeUsers(Request $request)
    {
        $newUserId = DB::table('users')->insertGetId([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => $request->password,
            'level_id'   => $request->level_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $levelName = DB::table('levels')->where('id', $request->level_id)->value('nama_level');

        if ($levelName == 'penjaga') {
            DB::table('penjagas')->insert([
                'user_id'    => $newUserId,
                'jabatan_id' => 1,
                'no_hp'      => '-',
                'created_at' => now(),
                'updated_at' => now()
            ]);

        } elseif ($levelName == 'peminjam') {
            DB::table('peminjams')->insert([
                'user_id'       => $newUserId,
                'alamat'        => '-',
                'no_hp'         => '-',
                'jenis_kelamin' => '-',
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
        }

        return redirect('/datauser')->with('success', 'User berhasil ditambahkan!');
    }
  
    public function updateUser(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            
           $userData = [
                'name'       => $request->name,
                'email'      => $request->email,
                'level_id'   => $request->level_id,
                'updated_at' => now()
            ];

            if ($request->filled('password')) {
                $userData['password'] = $request->password;
            }

            DB::table('users')->where('id', $id)->update($userData);

            $levelRaw = DB::table('levels')->where('id', $request->level_id)->value('nama_level');
            $levelName = strtolower(trim($levelRaw)); // Biar aman (jadi huruf kecil semua)

            
            if ($levelName == 'penjaga') {
                $exist = DB::table('penjagas')->where('user_id', $id)->first();
                
                if (!$exist) {
                    $jabatan = DB::table('jabatans')->first();
                    $jabatanId = $jabatan ? $jabatan->id : 1;

                    DB::table('penjagas')->insert([
                        'user_id'    => $id,
                        'jabatan_id' => $jabatanId,
                        'no_hp'      => '-',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                DB::table('peminjams')->where('user_id', $id)->delete();

            } elseif ($levelName == 'peminjam') {
                $exist = DB::table('peminjams')->where('user_id', $id)->first();

                if (!$exist) {
                    DB::table('peminjams')->insert([
                        'user_id'       => $id,
                        'alamat'        => '-',
                        'no_hp'         => '-',
                        'jenis_kelamin' => '-',
                        'created_at'    => now(),
                        'updated_at'    => now()
                    ]);
                }

                DB::table('penjagas')->where('user_id', $id)->delete();

            } else {
                DB::table('penjagas')->where('user_id', $id)->delete();
                DB::table('peminjams')->where('user_id', $id)->delete();
            }

            return redirect('/datauser')->with('success', 'Data User berhasil diperbarui dan disinkronkan!');
        });
    }

    public function destroy($id)
    {
        DB::table('penjagas')->where('user_id', $id)->delete();
        DB::table('peminjams')->where('user_id', $id)->delete();

        DB::table('users')->where('id', $id)->delete();

        return redirect('/datauser')->with('success', 'User berhasil dihapus selamanya!');
    }

    // ================= KOLEKSI & RIWAYAT (UNTUK ANGGOTA) ==================
    public function koleksiBuku(Request $request)
    {
        if (!session('id')) return view('404');

        $kategoriList = DB::table('kategori')->orderBy('nama_kategori')->get();

        $query = DB::table('books')
        ->join('penulis', 'books.penulis_id', '=', 'penulis.id')
        ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
        ->join('penerbit', 'books.penerbit_id', '=', 'penerbit.id')
        ->select(
            'books.*',
            'penulis.nama_penulis',
            'penerbit.nama_penerbit',
            'kategori.nama_kategori'
        )
        ->orderBy('books.id', 'desc');

        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('books.kategori_id', $request->kategori_id);
        }

        $buku = $query->get();

        echo view('header');
        echo view('menu');
        echo view('koleksibuku', compact('kategoriList', 'buku'));
        echo view('footer');
    }

    public function riwayatPeminjaman()
    {
        if (!session('id')) return view('404');

        $riwayat = DB::table('peminjaman_buku')
        ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
        ->select(
            'peminjaman_buku.id',
            'books.judul',
            'peminjaman_buku.tanggal_pinjam',
            'peminjaman_buku.tanggal_kembali',
            'peminjaman_buku.status'
        )
        ->where('peminjaman_buku.user_id', session('id'))
        ->orderBy('peminjaman_buku.id', 'desc')
        ->get();

        echo view('header');
        echo view('menu');
        echo view('riwayatpeminjaman', compact('riwayat'));
        echo view('footer');
    }

    public function pinjamBuku($id)
    {
        if (!session('id')) return view('404');

        $buku = DB::table('books')->where('id', $id)->first();

        if ($buku && $buku->stok > 0) {
            DB::table('peminjaman_buku')->insert([
                'book_id'        => $id,
                'user_id'        => session('id'),
                'tanggal_pinjam' => now(),
                'tanggal_kembali'=> null,
                'status'         => 'dipinjam'
            ]);

            DB::table('books')->where('id', $id)->decrement('stok', 1);

            return redirect('/riwayat')->with('success', 'Buku berhasil dipinjam!');
        }

        return redirect('/koleksi')->with('error', 'Buku tidak tersedia.');
    }

    public function kembalikanBukuAnggota($id)
    {
        if (!session('id')) return view('404');

        $pinjam = DB::table('peminjaman_buku')->where('id', $id)->first();

        if ($pinjam && $pinjam->status == 'dipinjam') {
            DB::table('peminjaman_buku')->where('id', $id)->update([
                'tanggal_kembali' => now(),
                'status'           => 'dikembalikan'
            ]);

            DB::table('books')->where('id', $pinjam->book_id)->increment('stok', 1);

            return redirect('/riwayat')->with('success', 'Buku berhasil dikembalikan!');
        }

        return redirect('/riwayat')->with('error', 'Data peminjaman tidak valid.');
    }

    // ========================= LAPORAN PEMINJAMAN =========================

    public function laporan(Request $request)
    {
        $query = DB::table('data_masuk_buku')
        ->join('books', 'data_masuk_buku.book_id', '=', 'books.id')
        ->select('data_masuk_buku.*', 'books.judul');

        if ($request->from && $request->to) {
            $query->whereBetween('tanggal_masuk', [$request->from, $request->to]);
        }

        $data = $query->get();

        return view('laporanpeminjaman', ['laporan' => $data]);
    }

    public function laporanPeminjaman(Request $request)
    {
        if (session('id') > 0) {
            $query = DB::table('peminjaman_buku')
            ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
            ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
            ->join('users', 'peminjaman_buku.user_id', '=', 'users.id')
            ->select(
                'peminjaman_buku.id',
                'users.name as nama_peminjam',
                'kategori.nama_kategori as kategori',
                'books.judul as judul_buku',
                'peminjaman_buku.tanggal_pinjam',
                'peminjaman_buku.tanggal_kembali',
                'peminjaman_buku.status'
            )
            ->orderBy('peminjaman_buku.id', 'desc');

        // filter tanggal jika diisi
            if ($request->from && $request->to) {
                $query->whereBetween('peminjaman_buku.tanggal_pinjam', [$request->from, $request->to]);
            }

            $laporan = $query->get();

            echo view('header');
            echo view('menu');
            echo view('laporanpeminjaman', [
                'laporan' => $laporan,
                'from' => $request->from,
                'to' => $request->to
            ]);
            echo view('footer');
        } else {
            echo view('404');
        }
    }

    public function printPeminjaman(Request $request)
    {
        $query = DB::table('peminjaman_buku')
        ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
        ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
        ->join('users', 'peminjaman_buku.user_id', '=', 'users.id')
        ->select(
            'users.name as nama_peminjam', 
            'books.judul as judul_buku',
            'peminjaman_buku.tanggal_pinjam', 
            'peminjaman_buku.tanggal_kembali',
            'peminjaman_buku.status', 
            'kategori.nama_kategori as kategori'
        )
        ->orderBy('peminjaman_buku.id', 'desc');

        if ($request->from && $request->to) {
            $query->whereBetween('peminjaman_buku.tanggal_pinjam', [$request->from, $request->to]);
        }

        $laporan = $query->get();

        echo view('header');
        return view('print_peminjaman', [
            'laporan' => $laporan,
            'from' => $request->from,
            'to' => $request->to
        ]);
    }

    public function pdfPeminjaman(Request $request)
    {
        $query = DB::table('peminjaman_buku')
        ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
        ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
        ->join('users', 'peminjaman_buku.user_id', '=', 'users.id')
        ->select(
            'users.name as nama_peminjam',
            'books.judul as judul_buku',
            'peminjaman_buku.tanggal_pinjam',
            'peminjaman_buku.tanggal_kembali',
            'peminjaman_buku.status',
            'kategori.nama_kategori as kategori'
        )
        ->orderBy('peminjaman_buku.id', 'desc');

        if ($request->from && $request->to) {
            $query->whereBetween('peminjaman_buku.tanggal_pinjam', [$request->from, $request->to]);
        }

        $laporan = $query->get();

        $pdf = new \TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Laporan Peminjaman Buku', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', '', 10);
        $html = '
        <table border="1" cellpadding="5" width="100%">
        <tr style="font-weight:bold; background-color:#f2f2f2;">
        <th width="5%">No</th>
        <th width="20%">Nama Peminjam</th>
        <th width="20%">Judul Buku</th>
        <th width="10%">Kategori</th>
        <th width="15%">Tanggal Pinjam</th>
        <th width="15%">Tanggal Kembali</th>
        <th width="15%">Status</th>
        </tr>';

        $no = 1;
        foreach ($laporan as $data) {
            $html .= '
            <tr>
            <td>'.$no++.'</td>
            <td>'.$data->nama_peminjam.'</td>
            <td>'.$data->judul_buku.'</td>
            <td>'.$data->kategori.'</td>
            <td>'.$data->tanggal_pinjam.'</td>
            <td>'.$data->tanggal_kembali.'</td>
            <td>'.$data->status.'</td>
            </tr>';
        }

        $html .= '</table>';
        $pdf->writeHTML($html, true, false, false, false, '');
        $pdf->Output('laporan_peminjaman.pdf', 'I');
    }

    public function excelPeminjaman()
    {
        $laporan = DB::table('peminjaman_buku')
        ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
        ->join('users', 'peminjaman_buku.user_id', '=', 'users.id')
        ->select(
            'users.name as Nama_Peminjam',
            'books.judul as Judul_Buku',
            'peminjaman_buku.tanggal_pinjam as Tanggal_Pinjam',
            'peminjaman_buku.tanggal_kembali as Tanggal_Kembali',
            'peminjaman_buku.status as Status'
        )
        ->orderBy('peminjaman_buku.id', 'desc')
        ->get()
        ->map(function ($item) {
            return (array) $item;
        })
        ->toArray();

        $filePath = storage_path('app/public/laporan_peminjaman.xlsx');

        SimpleExcelWriter::create($filePath)
        ->addRows($laporan);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    // ========================= LAPORAN BUKU MASUK =========================
    public function laporanMasuk()
    {
        if (session('id') > 0) {
            $laporan = DB::table('data_masuk_buku')
            ->join('books', 'data_masuk_buku.book_id', '=', 'books.id')
            ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
            ->leftJoin('penulis', 'books.penulis_id', '=', 'penulis.id')
            ->select(
                'data_masuk_buku.id',
                'books.judul',
                'penulis.nama_penulis',
                'kategori.nama_kategori',
                'data_masuk_buku.tanggal_masuk',
                'data_masuk_buku.jumlah'
            )
            ->orderBy('data_masuk_buku.id', 'desc')
            ->get();

            echo view('header');
            echo view('menu');
            echo view('laporanmasuk', compact('laporan'));
            echo view('footer');
        } else {
            echo view('404');
        }
    }

    public function printMasuk()
    {
        $laporan = DB::table('data_masuk_buku')
        ->join('books', 'data_masuk_buku.book_id', '=', 'books.id')
        ->join('penulis', 'books.penulis_id', '=', 'penulis.id')
        ->select('books.judul', 'penulis.nama_penulis', 'data_masuk_buku.tanggal_masuk', 'data_masuk_buku.jumlah')
        ->orderBy('data_masuk_buku.id', 'desc')
        ->get();

        return view('print_masuk', compact('laporan'));
    }

    public function pdfMasuk()
    {
        $laporan = DB::table('data_masuk_buku')
        ->join('books', 'data_masuk_buku.book_id', '=', 'books.id')
        ->join('penulis', 'books.penulis_id', '=', 'penulis.id')
        ->select('books.judul', 'penulis.nama_penulis', 'data_masuk_buku.tanggal_masuk', 'data_masuk_buku.jumlah')
        ->orderBy('data_masuk_buku.id', 'desc')
        ->get();

        $pdf = Pdf::loadView('print_masuk', compact('laporan'));
        return $pdf->download('laporan_buku_masuk.pdf');
    }

    public function excelMasuk()
    {
        $laporan = DB::table('data_masuk_buku')
        ->join('books', 'data_masuk_buku.book_id', '=', 'books.id')
        ->join('penulis', 'books.penulis_id', '=', 'penulis.id')
        ->select(
            'books.judul as Judul_Buku',
            'penulis.nama_penulis as Penulis',
            'data_masuk_buku.tanggal_masuk as Tanggal_Masuk',
            'data_masuk_buku.jumlah as Jumlah'
        )
        ->orderBy('data_masuk_buku.id', 'desc')
        ->get()
        ->map(function ($item) {
            return (array) $item;
        })
        ->toArray();

        $filePath = storage_path('app/public/laporan_buku_masuk.xlsx');

        SimpleExcelWriter::create($filePath)
        ->addRows($laporan);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}