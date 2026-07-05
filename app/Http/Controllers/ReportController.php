<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Tarik data peminjaman dengan filter opsional rentang tanggal
        $reports = Borrowing::with(['user', 'details.product'])
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('borrow_date', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('borrow_date', '<=', $endDate);
            })
            ->latest()
            ->get();

        return view('reports.index', compact('reports', 'startDate', 'endDate'));
    }
}