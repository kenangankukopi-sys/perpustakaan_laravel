<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Elibyy\TCPDF\Facades\TCPDF;
use Spatie\SimpleExcel\SimpleExcelWriter;

class DataController extends Controller
{
    public function books()
    {
        $buku = DB::table('books')
        ->join('penulis', 'books.penulis_id', '=', 'penulis.id')
        ->join('penerbit', 'books.penerbit_id', '=', 'penerbit.id')
        ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
        ->select('books.*', 'penulis.nama_penulis', 'penerbit.nama_penerbit', 'kategori.nama_kategori')
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $buku
        ]);
    }

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
            ->orderBy('data_masuk_buku.tanggal_masuk', 'desc')
            ->get();

            $books = DB::table('books')->select('id', 'judul')->get();

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'options_buku' => $books
            ]);
        }
    }
}
