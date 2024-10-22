@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="pagetitle">
        <h1>Hasil Peramalan Penjualan : {{ $barang->nama }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Peramalan</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Grafik Peramalan: {{ $barang->nama }}</h5>
                        <canvas id="forecastChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Historis</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Bulan</th>
                                        <th>Kuantitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historicalData as $month => $quantity)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($month)->format('M Y') }}</td>
                                        <td>{{ number_format($quantity) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Hasil Peramalan</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Bulan</th>
                                        <th>Peramalan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($forecastData as $month => $quantity)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($month)->format('M Y') }}</td>
                                        <td>{{ number_format($quantity, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Metrik Error</h5>
                    <table class="table table-striped table-hover">
                        <tr>
                            <th>Metrik</th>
                            <th>Nilai</th>
                        </tr>
                        <tr>
                            <td>MAD</td>
                            <td>{{ number_format($errorMetrics['MAD'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>MSE</td>
                            <td>{{ number_format($errorMetrics['MSE'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>MAPE</td>
                            <td>{{ number_format($errorMetrics['MAPE'], 2) }}%</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('forecastChart').getContext('2d');
    const chartData = @json($chartData);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(data => data.month),
            datasets: [{
                label: 'Data Aktual',
                data: chartData.map(data => data.actual),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                fill: true
            }, {
                label: 'Peramalan',
                data: chartData.map(data => data.forecast),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Perbandingan Data Aktual dan Peramalan'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Kuantitas'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
});
</script>
@endpush