<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Barang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ForecastingController extends Controller
{
    // Menampilkan form pemilihan barang
    public function index()
    {
        $barang = Barang::all();
        return view('forecasting.index', compact('barang'));
    }

    public function forecast(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
        ]);

        $barangId = $request->barang_id;
        $barang = Barang::findOrFail($barangId);

        // Ambil semua data historis
        $historicalData = $this->getHistoricalSales($barangId);

        // Hitung peramalan
        $forecastData = $this->calculateForecast($historicalData);

        // Hitung error metrics
        $errorMetrics = $this->calculateErrorMetrics($historicalData, $forecastData);

        // Siapkan data untuk chart
        $chartData = $this->prepareChartData($historicalData, $forecastData);

        // // Hitung statistik tambahan
        // $stats = $this->calculateAdditionalStats($historicalData);

        return view('forecasting.result', compact('barang', 'historicalData', 'forecastData', 'chartData', 'errorMetrics'));
    }

    private function getHistoricalSales($barangId)
    {
        return DetailTransaksi::join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
        ->where('detail_transaksi.barang_id', $barangId)
        ->select(
            DB::raw('DATE_FORMAT(transaksi.tanggal_transaksi, "%Y-%m") as month'),
            DB::raw('SUM(detail_transaksi.kuantitas) as total_quantity')
        )
        ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->map(function ($item) {
                return (int) $item->total_quantity;
            });
    }

    private function calculateForecast($historicalData)
    {
        $forecast = collect();
        $months = array_keys($historicalData->toArray());
        $values = array_values($historicalData->toArray());

        // Minimal butuh 3 bulan data untuk Moving Average 3 periode
        if (count($values) < 3) {
            return $forecast;
        }

        // Hitung Moving Average untuk setiap periode
        for ($i = 3; $i < count($values); $i++) {
            $ma3 = ($values[$i - 3] + $values[$i - 2] + $values[$i - 1]) / 3;
            $forecast[$months[$i]] = number_format($ma3, 3);
        }

        // Forecasting untuk 6 bulan ke depan
        $lastThreeValues = array_slice($values, -3);
        $nextMonth = Carbon::parse(end($months))->addMonth();

        for ($i = 1; $i <= 3; $i++) {
            $ma3 = array_sum($lastThreeValues) / 3;
            $monthKey = $nextMonth->format('Y-m');
            $forecast[$monthKey] = number_format($ma3, 3);

            array_shift($lastThreeValues);
            array_push($lastThreeValues, $ma3);
            $nextMonth->addMonth();
        }

        return $forecast;
    }

    private function calculateErrorMetrics($actual, $forecast)
    {
        $errors = [];
        $sumError = 0;
        $sumAbsError = 0;
        $sumSquaredError = 0;
        $sumActualPercentageError = 0;
        $n = 0;

        foreach ($actual as $month => $value) {
            if (isset($forecast[$month])) {
                $error = $value - $forecast[$month];
                $sumError += $error;
                $sumAbsError += abs($error);
                $sumSquaredError += $error * $error;
                $sumActualPercentageError += $value != 0 ? abs($error / $value) : 0;
                $n++;
            }
        }

        $errors['ME'] = $n > 0 ? $sumError / $n : 0;
        $errors['MAD'] = $n > 0 ? $sumAbsError / $n : 0;
        $errors['MSE'] = $n > 0 ? $sumSquaredError / $n : 0;
        $errors['MAPE'] = $n > 0 ? ($sumActualPercentageError / $n) * 100 : 0;

        return $errors;
    }

    private function prepareChartData($historicalData, $forecastData)
    {
        $chartData = [];

        $allMonths = collect(array_merge(
            array_keys($historicalData->toArray()),
            array_keys($forecastData->toArray())
        ))->unique()->sort();

        foreach ($allMonths as $month) {
            $chartData[] = [
                'month' => Carbon::parse($month)->format('M Y'),
                'actual' => $historicalData[$month] ?? null,
                'forecast' => $forecastData[$month] ?? null,
            ];
        }

        return $chartData;
    }

    // private function calculateAdditionalStats($historicalData)
    // {
    //     $stats = [];
    //     $values = array_values($historicalData->toArray());

    //     $stats['total_sales'] = array_sum($values);
    //     $stats['average_monthly_sales'] = count($values) > 0 ? $stats['total_sales'] / count($values) : 0;
    //     $stats['min_sales'] = min($values);
    //     $stats['max_sales'] = max($values);

    //     // Calculate trend
    //     $trend = $this->calculateTrend($values);
    //     $stats['trend'] = $trend['slope'] > 0 ? 'Upward' : ($trend['slope'] < 0 ? 'Downward' : 'Stable');
    //     $stats['trend_value'] = $trend['slope'];

    //     // Identify seasonality (simple method, can be improved)
    //     $stats['seasonality'] = $this->identifySeasonality($values);

    //     return $stats;
    // }

    // private function calculateTrend($values)
    // {
    //     $n = count($values);
    //     $sum_x = 0;
    //     $sum_y = 0;
    //     $sum_xy = 0;
    //     $sum_xx = 0;

    //     for ($i = 0; $i < $n; $i++) {
    //         $sum_x += $i;
    //         $sum_y += $values[$i];
    //         $sum_xy += $i * $values[$i];
    //         $sum_xx += $i * $i;
    //     }

    //     $slope = ($n * $sum_xy - $sum_x * $sum_y) / ($n * $sum_xx - $sum_x * $sum_x);
    //     $intercept = ($sum_y - $slope * $sum_x) / $n;

    //     return ['slope' => $slope, 'intercept' => $intercept];
    // }

    // private function identifySeasonality($values)
    // {
    //     // Simple method to check for seasonality, can be improved
    //     $n = count($values);
    //     if ($n < 12
    //     ) return 'Insufficient data';

    //     $correlations = [];
    //     for ($lag = 1; $lag <= 12; $lag++) {
    //         $correlation = $this->autocorrelation($values, $lag);
    //         $correlations[$lag] = $correlation;
    //     }

    //     $max_correlation = max($correlations);
    //     $max_lag = array_search($max_correlation, $correlations);

    //     if ($max_correlation > 0.5 && ($max_lag == 3 || $max_lag == 6 || $max_lag == 12)) {
    //         return "Possible {$max_lag}-month seasonality";
    //     }

    //     return 'No clear seasonality detected';
    // }

    // private function autocorrelation($values, $lag)
    // {
    //     $n = count($values);
    //     $mean = array_sum($values) / $n;
    //     $variance = 0;
    //     $covariance = 0;

    //     for ($i = 0; $i < $n - $lag; $i++) {
    //         $variance += pow($values[$i] - $mean, 2);
    //         $covariance += ($values[$i] - $mean) * ($values[$i + $lag] - $mean);
    //     }

    //     // Tambahkan pengecekan untuk varians
    //     if ($variance == 0) {
    //         return 0; // Atau bisa mengembalikan nilai lain yang sesuai
    //     }

    //     return $covariance / $variance;
    // }
       
}
