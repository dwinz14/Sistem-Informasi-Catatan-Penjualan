@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="pagetitle">
        <h1>Forecasting</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Forecasting</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="container">
            <h2>Peramalan Penjualan</h2>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('forecasting.forecast') }}" method="POST">
                        @csrf
                        <div class="form">
                            <label for="barang_id">Pilih Barang</label>
                            <select name="barang_id" id="barang_id" class="form-control" required>
                                <option value="">...</option>
                                @foreach($barang as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
