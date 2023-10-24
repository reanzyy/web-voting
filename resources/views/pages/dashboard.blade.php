@extends('layouts.app')

@section('title', 'Beranda')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Beranda</h1>
            </div>

            <x-alert />

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Admin</h4>
                            </div>
                            <div class="card-body">
                                {{ $admin }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-user-group"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Kandidat OSIS</h4>
                            </div>
                            <div class="card-body">
                                {{ $candidate }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-arrow-right-to-bracket"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Hak Suara Masuk</h4>
                            </div>
                            <div class="card-body">
                                {{ $vote_in }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Hak Suara Tersisa</h4>
                            </div>
                            <div class="card-body">
                                {{ $vote }}
                <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header justify-content-center">
                            <h4 class="text-blue"><strong>Grafik Total Suara</strong></h4>
                        </div>
                        <div class="card-body">
                            {{-- <form method="GET" action="{{ route('dashboard') }}" id="yearForm">
                                <div class="form-group">
                                    <label for="selected_year">Pilih Tahun:</label>
                                    <input type="number" class="form-control" name="selected_year" id="selected_year"
                                        value="{{ request('selected_year', \Carbon\Carbon::now()->format('Y')) }}">
                                </div>
                            </form> --}}
                            <div class="chart-container mt-3">
                                <canvas id="votingChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        .chart-title {
            text-align: center;
            margin-top: 10px;
        }

        .card {
            width: 87%;
            margin: 0 auto;
        }

        .chart-container {
            position: relative;
            width: 100%;
            transition: height 1s ease-in-out;
            padding: 20px;
        }

        .chart {
            display: flex;
        }
    </style>
@endpush

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    {{-- <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script> --}}
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    {{-- <script src="{{ asset('js/page/index-0.js') }}"></script> --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var data = @json($chartData);

        var ctx = document.getElementById('votingChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Jumlah Suara',
                    data: data.counts,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    barThickness: 100,
                    borderWidth: 1,
                    borderRadius: 4,
                    minBarLength: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    title: {
                        display: false,
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        display: true,
                    }
                },
                tooltips: {
                    enabled: true,
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: tooltipItem => tooltipItem.yLabel,
                    }
                }
            }
        });

        document.getElementById('selected_year').addEventListener('change', () => {
            document.getElementById('yearForm').submit();
        });
    </script>
@endpush
