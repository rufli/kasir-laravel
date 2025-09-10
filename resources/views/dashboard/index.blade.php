@extends('layouts.app')
@section('title', 'Dhasboard')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <div class="dashboard-container">
        <h2 class="dashboard-title">
            Dashboard,
            @if (Auth::user()->role == 'admin')
                Admin {{ Auth::user()->nama }}
            @elseif(Auth::user()->role == 'pegawai')
                Pegawai {{ Auth::user()->nama }}
            @else
                {{ Auth::user()->nama }}
            @endif
        </h2>

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

            <!-- Filter container dengan class yang sesuai -->
            <div class="filter-container">
                <select id="periodeFilter" class="dashboard-filter">
                    <option value="7" selected>7 Hari Terakhir</option>
                    <option value="30">30 Hari Terakhir</option>
                    <option value="365">1 Tahun Terakhir</option>
                </select>
            </div>

            <div class="chart-canvas-container">
                <canvas id="dailyChart"></canvas>
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
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        fill: true
                    },
                    {
                        label: 'Pengeluaran',
                        data: dataPengeluaran,
                        borderColor: '#e74c3c',
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        fill: true
                    },
                    {
                        label: 'Laba Bersih',
                        data: dataLaba,
                        borderColor: '#2ecc71',
                        backgroundColor: 'rgba(46, 204, 113, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        fill: true
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
                            labels: {
                                boxWidth: 20,
                                usePointStyle: true
                            }
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
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            };

            if (dailyChart) dailyChart.destroy();
            dailyChart = new Chart(document.getElementById('dailyChart'), config);
        }

        // Inisialisasi chart awal
        const initialLabels = @json(array_map(fn($h) => \Carbon\Carbon::parse($h['tanggal'])->format('d M'), $hariTerakhir));
        const initialPenjualan = @json(array_map(fn($h) => $h['penjualan'], $hariTerakhir));
        const initialPengeluaran = @json(array_map(fn($h) => $h['pengeluaran'], $hariTerakhir));
        const initialLaba = initialPenjualan.map((v, i) => v - initialPengeluaran[i]);

        renderChart(initialLabels, initialPenjualan, initialPengeluaran, initialLaba);

        // Event filter periode
        document.getElementById('periodeFilter').addEventListener('change', function() {
            const periode = this.value;
            axios.get('/dashboard/data?periode=' + periode)
                .then(res => {
                    const labels = res.data.labels;
                    const penjualan = res.data.penjualan;
                    const pengeluaran = res.data.pengeluaran;
                    const laba = penjualan.map((v, i) => v - pengeluaran[i]);
                    renderChart(labels, penjualan, pengeluaran, laba);
                })
                .catch(err => {
                    console.error('Error fetching data:', err);
                    alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                });
        });
    </script>
@endsection
