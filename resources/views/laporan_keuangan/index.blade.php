@extends('layouts.app')
@section('title', 'Laporan Keuangan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/laporan_keuangan/index.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="container">
    <h2>Laporan Keuangan</h2>

    <div class="card mb-4">
        <div>
        <a href="#" onclick="printDataOnly()" class="btn-print">
                        <i class="fas fa-print"></i> Cetak Laporan
                    </a>
        </div>

        <div class="card-header">
            <form method="GET" class="filter-form">
                <div class="filter-group">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="filter-group">
                    <label>Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="filter-button">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>

        </div>

        <div class="card-body">

            <div class="stats-grid">
                <div class="stat-card sales">
                    <h5>Total Penjualan</h5>
                    <h4>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h4>
                </div>
                <div class="stat-card expenses">
                    <h5>Total Pengeluaran</h5>
                    <h4>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
                </div>
                <div class="stat-card balance">
                    <h5>Saldo Bersih</h5>
                    <h4>Rp {{ number_format($labaBersih, 0, ',', '.') }}</h4>
                </div>
            </div>

            <div class="printable-area">
                {{-- KOREKSI: Logika untuk menampilkan pesan "Tidak ada data" --}}
                @if($penjualan->isEmpty() && $pengeluaran->isEmpty())
                    <div class="alert alert-info text-center">
                        Tidak ada data transaksi untuk periode yang dipilih.
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Detail Penjualan</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Metode</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($penjualan as $item)
                                        <tr>
                                            <td>{{ $item->tanggal }}</td>
                                            <td>{{ ucfirst($item->metode_pembayaran) }}</td>
                                            <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Detail Pengeluaran</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama</th>
                                            <th>Kategori</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pengeluaran as $item)
                                        <tr>
                                            <td>{{ $item->tanggal }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->kategori->nama ?? '-' }}</td>
                                            <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    function printDataOnly() {
        const printableContent = document.querySelector('.printable-area').innerHTML;
        const statsContent = document.querySelector('.stats-grid').innerHTML;
        const originalContent = document.body.innerHTML;

        // Create print-specific HTML
        const printWindow = window.open('', '', 'width=900,height=650');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Laporan Keuangan {{ $startDate }} hingga {{ $endDate }}</title>
                <link rel="stylesheet" href="{{ asset('css/laporan_keuangan/index.css') }}">
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
                    .print-header { text-align: center; margin-bottom: 20px; }
                    .print-header h2 { margin-bottom: 5px; }
                    .print-header p { color: #666; }
                    .stats-grid { display: flex; justify-content: space-between; margin-bottom: 30px; }
                    .stat-card { flex: 1; padding: 15px; border-radius: 5px; margin: 0 10px; text-align: center; }
                    .stat-card.sales { background-color: #e6f7ff; border: 1px solid #91d5ff; }
                    .stat-card.expenses { background-color: #fff7e6; border: 1px solid #ffd591; }
                    .stat-card.balance { background-color: #e6ffed; border: 1px solid #91ffb3; }
                    .stat-card h4 { margin-top: 10px; font-size: 1.5em; }
                    .stat-card h5 { margin-bottom: 5px; color: #666; }
                    @page { size: auto; margin: 10mm; }
                    @media print {
                        .no-print, .btn-print { display: none !important; }
                        body { margin: 0; padding: 0; }
                    }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h2>Laporan Keuangan</h2>
                    <p>Periode: {{ $startDate }} hingga {{ $endDate }}</p>
                </div>

                <div class="stats-grid">
                    ${statsContent}
                </div>

                ${printableContent}
                <script>
                    window.onload = function() {
                        setTimeout(function() {
                            window.print();
                            window.close();
                        }, 200);
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    // Skrip untuk menampilkan pesan error Toastr
    $(document).ready(function() {
        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
    });
</script>
@endsection
