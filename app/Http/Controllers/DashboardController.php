<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\TransaksiPenjualan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $sevenDaysAgo = $today->copy()->subDays(6); // 7 hari terakhir termasuk hari ini

        // Card Bulan Ini
        $totalPenjualanBulan = TransaksiPenjualan::whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->sum('total_harga');

        $totalPengeluaranBulan = Pengeluaran::whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->sum('jumlah');

        $labaBulan = $totalPenjualanBulan - $totalPengeluaranBulan;

        // Data per hari selama 7 hari terakhir
        $hariTerakhir = [];
        for ($i = 0; $i < 7; $i++) {
            $tanggal = $sevenDaysAgo->copy()->addDays($i)->format('Y-m-d');

            $penjualan = TransaksiPenjualan::whereDate('tanggal', $tanggal)->sum('total_harga');
            $pengeluaran = Pengeluaran::whereDate('tanggal', $tanggal)->sum('jumlah');
            $laba = $penjualan - $pengeluaran;

            $hariTerakhir[] = [
                'tanggal' => $tanggal,
                'penjualan' => $penjualan,
                'pengeluaran' => $pengeluaran,
                'laba' => $laba,
            ];
        }

        return view('dashboard.index', compact(
            'totalPenjualanBulan',
            'totalPengeluaranBulan',
            'labaBulan',
            'hariTerakhir'
        ));
    }

    public function data(Request $request)
    {
        $periode = $request->periode ?? 7; // default 7 hari
        $today = Carbon::today();
        $startDate = null;
        $interval = 'day';

        // Tentukan start date berdasarkan periode
        if ($periode == 365) {
            // 12 bulan terakhir dari bulan saat ini
            $startDate = $today->copy()->subMonths(11)->startOfMonth();
            $interval = 'month';
        } elseif ($periode == 30) {
            $startDate = $today->copy()->subDays(29); // 30 hari termasuk hari ini
        } else {
            // Default 7 hari
            $startDate = $today->copy()->subDays(6); // 7 hari termasuk hari ini
        }

        $labels = [];
        $penjualan = [];
        $pengeluaran = [];

        if ($interval == 'month') {
            // Data per bulan untuk 12 bulan terakhir
            for ($i = 0; $i < 12; $i++) {
                $currentDate = $startDate->copy()->addMonths($i);
                $year = $currentDate->year;
                $month = $currentDate->month;
                
                $sumPenjualan = TransaksiPenjualan::whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month)
                    ->sum('total_harga');
                    
                $sumPengeluaran = Pengeluaran::whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month)
                    ->sum('jumlah');
                
                $labels[] = $currentDate->format('M Y');
                $penjualan[] = $sumPenjualan;
                $pengeluaran[] = $sumPengeluaran;
            }
        } else {
            // Data per hari
            $days = $periode;
            for ($i = 0; $i < $days; $i++) {
                $currentDate = $startDate->copy()->addDays($i);
                $dateString = $currentDate->format('Y-m-d');
                
                $sumPenjualan = TransaksiPenjualan::whereDate('tanggal', $dateString)->sum('total_harga');
                $sumPengeluaran = Pengeluaran::whereDate('tanggal', $dateString)->sum('jumlah');
                
                $labels[] = $currentDate->format('d M');
                $penjualan[] = $sumPenjualan;
                $pengeluaran[] = $sumPengeluaran;
            }
        }

        return response()->json([
            'labels' => $labels,
            'penjualan' => $penjualan,
            'pengeluaran' => $pengeluaran
        ]);
    }
}