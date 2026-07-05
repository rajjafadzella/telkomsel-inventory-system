<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    // 1. Menampilkan Riwayat Peminjaman (Riwayat Peminjaman) [cite: 65]
    public function index()
    {
        $borrowings = Borrowing::with(['user', 'details.product'])->latest()->get();
        return view('borrowings.index', compact('borrowings'));
    }

    // 2. Menampilkan Form Tambah Peminjaman [cite: 63]
    public function create()
    {
        $users = User::all(); // Mengambil daftar user sebagai peminjam [cite: 68]
        $products = Product::where('stock', '>', 0)->get(); // Hanya ambil barang yang tersedia [cite: 77]
        return view('borrowings.create', compact('users', 'products'));
    }

    // 3. Memproses Transaksi Peminjaman (Stok Berkurang Otomatis)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'borrow_date' => 'required|date',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Cek kecukupan stok barang [cite: 8]
        if ($product->stock < $request->qty) {
            return redirect()->back()->withErrors(['qty' => 'Stok barang tidak mencukupi untuk dipinjam!']);
        }

        // Jalankan Database Transaction untuk menjaga validitas data [cite: 13]
        DB::transaction(function () use ($request, $product) {
            // A. Buat Header Peminjaman
            $borrowing = Borrowing::create([
                'user_id' => $request->user_id,
                'borrow_date' => $request->borrow_date,
                'status' => 'Dipinjam', // [cite: 72]
            ]);

            // B. Buat Detail Peminjaman
            BorrowingDetail::create([
                'borrowing_id' => $borrowing->id,
                'product_id' => $request->product_id,
                'qty' => $request->qty,
            ]);

            // C. Potong Stok Barang Otomatis
            $product->decrement('stock', $request->qty);
        });

        return redirect()->route('borrowings.index')->with('success', 'Transaksi peminjaman berhasil dicatat!');
    }

    // 4. Memproses Pengembalian Barang (Stok Kembali Otomatis) [cite: 64]
    public function returnAsset(Borrowing $borrowing)
    {
        DB::transaction(function () use ($borrowing) {
            // A. Ubah Status dan Tanggal Kembali [cite: 71, 72]
            $borrowing->update([
                'status' => 'Dikembalikan',
                'return_date' => now(),
            ]);

            // B. Kembalikan Stok Barang Otomatis ke Tabel Products
            foreach ($borrowing->details as $detail) {
                $detail->product->increment('stock', $detail->qty);
            }
        });

        return redirect()->route('borrowings.index')->with('success', 'Barang telah berhasil dikembalikan!');
    }
}