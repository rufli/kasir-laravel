<?php

namespace App\Http\Controllers;

use App\Models\LaporanKeuangan;
use Illuminate\Http\Request;
use App\Models\TransaksiPenjualan;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class LaporanKeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Default periode: bulan ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Ambil data penjualan
        $penjualan = TransaksiPenjualan::whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->get();

        // Ambil data pengeluaran
        $pengeluaran = Pengeluaran::with('kategori')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->get();

        // Hitung total
        $totalPenjualan = $penjualan->sum('total_harga');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $labaBersih = $totalPenjualan - $totalPengeluaran;

        return view('laporan_keuangan.index', compact(
            'penjualan',
            'pengeluaran',
            'totalPenjualan',
            'totalPengeluaran',
            'labaBersih',
            'startDate',
            'endDate'
        ));
    }

    public function harian()
    {
        $tanggal = request('tanggal', Carbon::today()->format('Y-m-d'));

        $penjualan = TransaksiPenjualan::whereDate('tanggal', $tanggal)
            ->orderBy('created_at')
            ->get();

        $pengeluaran = Pengeluaran::with('kategori')
            ->whereDate('tanggal', $tanggal)
            ->orderBy('created_at')
            ->get();

        $totalPenjualan = $penjualan->sum('total_harga');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $labaBersih = $totalPenjualan - $totalPengeluaran;

        return view('laporan.harian', compact(
            'penjualan',
            'pengeluaran',
            'totalPenjualan',
            'totalPengeluaran',
            'labaBersih',
            'tanggal'
        ));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LaporanKeuangan $laporanKeuangan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaporanKeuangan $laporanKeuangan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaporanKeuangan $laporanKeuangan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaporanKeuangan $laporanKeuangan)
    {
        //
    }

    
}
