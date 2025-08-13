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

        // Tentukan start date
        if ($periode == 365) {
            $startDate = $today->copy()->subYear()->addDay();
            $interval = 'month'; // chart per bulan
        } elseif ($periode == 30) {
            $startDate = $today->copy()->subDays(29);
            $interval = 'day';
        } else {
            $startDate = $today->copy()->subDays(6);
            $interval = 'day';
        }

        $periodeData = [];
        $dates = [];

        if ($interval == 'day') {
            $days = $today->diffInDays($startDate) + 1;
            for ($i = 0; $i < $days; $i++) {
                $date = $startDate->copy()->addDays($i);
                $dates[] = $date->format('Y-m-d');
            }
        } else { // per bulan
            $months = $today->diffInMonths($startDate) + 1;
            for ($i = 0; $i < $months; $i++) {
                $date = $startDate->copy()->addMonths($i);
                $dates[] = $date->format('Y-m');
            }
        }

        $labels = [];
        $penjualan = [];
        $pengeluaran = [];

        foreach ($dates as $date) {
            if ($interval == 'day') {
                $sumPenjualan = TransaksiPenjualan::whereDate('tanggal', $date)->sum('total_harga');
                $sumPengeluaran = Pengeluaran::whereDate('tanggal', $date)->sum('jumlah');
                $labels[] = Carbon::parse($date)->format('d-m');
            } else { // month
                $sumPenjualan = TransaksiPenjualan::whereYear('tanggal', explode('-', $date)[0])
                    ->whereMonth('tanggal', explode('-', $date)[1])
                    ->sum('total_harga');
                $sumPengeluaran = Pengeluaran::whereYear('tanggal', explode('-', $date)[0])
                    ->whereMonth('tanggal', explode('-', $date)[1])
                    ->sum('jumlah');
                $labels[] = Carbon::parse($date . '-01')->format('M-Y');
            }

            $penjualan[] = $sumPenjualan;
            $pengeluaran[] = $sumPengeluaran;
        }

        return response()->json([
            'labels' => $labels,
            'penjualan' => $penjualan,
            'pengeluaran' => $pengeluaran
        ]);
    }
}
