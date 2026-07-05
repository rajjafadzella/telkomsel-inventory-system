<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // 1. Hitung Ringkasan Data Utama
        $totalProducts = Product::count();
        $totalAvailable = Product::where('stock', '>', 0)->count();
        
        // Menghitung barang yang sedang dipinjam (berdasarkan relasi details qty)
        $totalBorrowed = DB::table('borrowing_details')
            ->join('borrowings', 'borrowings.id', '=', 'borrowing_details.borrowing_id')
            ->where('borrowings.status', 'Dipinjam')
            ->sum('borrowing_details.qty');

        // 2. Ambil Data Grafik Peminjaman per Bulan (Tahun Berjalan)
        $monthlyBorrowings = Borrowing::select(
                DB::raw('MONTH(borrow_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('borrow_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Siapkan array kosong untuk 12 bulan
        $chartData = array_fill(1, 12, 0);
        foreach ($monthlyBorrowings as $data) {
            $chartData[$data->month] = $data->total;
        }

        return view('dashboard', [
            'totalProducts' => $totalProducts,
            'totalBorrowed' => $totalBorrowed,
            'totalAvailable' => $totalAvailable,
            'chartData' => array_values($chartData) // Array indeks 0-11 untuk Chart.js
        ]);
    }
}
