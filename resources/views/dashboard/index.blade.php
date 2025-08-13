@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div class="dashboard-container">
    <h2 class="dashboard-title">Dashboard</h2>

    <div class="card-grid">
        <div class="info-card info-card-blue">
            <h3 class="card-title">Total Penjualan Bulan Ini</h3>
            <p class="card-value">Rp {{ number_format($totalPenjualanBulan, 0, ',', '.') }}</p>
        </div>

        <div class="info-card info-card-green">
            <h3 class="card-title">Total Pengeluaran Bulan Ini</h3>
            <p class="card-value">Rp {{ number_format($totalPengeluaranBulan, 0, ',', '.') }}</p>
        </div>

        <div class="info-card info-card-yellow">
            <h3 class="card-title">Laba Bersih Bulan Ini</h3>
            <p class="card-value">Rp {{ number_format($labaBulan, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="chart-section mt-4">
        <h3>Penjualan, Pengeluaran & Laba Bersih</h3>

        <!-- Filter periode -->
        <select id="periodeFilter" class="form-select w-auto mb-3">
            <option value="7" selected>7 Hari Terakhir</option>
            <option value="30">30 Hari Terakhir</option>
            <option value="365">1 Tahun Terakhir</option>
        </select>

        <div style="width:100%; max-width:900px;">
            <canvas id="dailyChart" style="width:100%; max-height:300px;"></canvas>
        </div>
    </div>
</div>

<script>
let dailyChart;

function renderChart(labels, dataPenjualan, dataPengeluaran, dataLaba) {
    const data = {
        labels: labels,
        datasets: [
            {
                label: 'Penjualan',
                data: dataPenjualan,
                borderColor: '#3498db',
                backgroundColor: 'transparent',
                borderWidth: 2,
                tension: 0.3,
                pointRadius: 3,
                pointHoverRadius: 5
            },
            {
                label: 'Pengeluaran',
                data: dataPengeluaran,
                borderColor: '#e74c3c',
                backgroundColor: 'transparent',
                borderWidth: 2,
                tension: 0.3,
                pointRadius: 3,
                pointHoverRadius: 5
            },
            {
                label: 'Laba Bersih',
                data: dataLaba,
                borderColor: '#2ecc71',
                backgroundColor: 'transparent',
                borderWidth: 2,
                tension: 0.3,
                pointRadius: 3,
                pointHoverRadius: 5
            }
        ]
    };

    const config = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { boxWidth: 20, usePointStyle: true }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            interaction: { mode: 'nearest', axis: 'x', intersect: false },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: { grid: { display: false } }
            }
        }
    };

    if(dailyChart) dailyChart.destroy();
    dailyChart = new Chart(document.getElementById('dailyChart'), config);
}

// Inisialisasi chart awal
const initialLabels = @json(array_map(fn($h) => \Carbon\Carbon::parse($h['tanggal'])->format('d-m'), $hariTerakhir));
const initialPenjualan = @json(array_map(fn($h) => $h['penjualan'], $hariTerakhir));
const initialPengeluaran = @json(array_map(fn($h) => $h['pengeluaran'], $hariTerakhir));
const initialLaba = initialPenjualan.map((v,i) => v - initialPengeluaran[i]);

renderChart(initialLabels, initialPenjualan, initialPengeluaran, initialLaba);

// Event filter periode
document.getElementById('periodeFilter').addEventListener('change', function() {
    const periode = this.value;
    axios.get('/dashboard/data?periode=' + periode)
        .then(res => {
            const labels = res.data.labels;
            const penjualan = res.data.penjualan;
            const pengeluaran = res.data.pengeluaran;
            const laba = penjualan.map((v,i) => v - pengeluaran[i]);

            renderChart(labels, penjualan, pengeluaran, laba);
        });
});
</script>
@endsection
