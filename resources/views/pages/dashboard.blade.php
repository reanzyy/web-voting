@extends('layouts.app')

@section('title', 'Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>

            <x-alert />

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-school-flag"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Kelas</h4>
                            </div>
                            <div class="card-body">
                                {{ $classrooms }}
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
                                <h4>Siswa</h4>
                            </div>
                            <div class="card-body">
                                {{ $students }}
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
                                {{ $votes }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header justify-content-center">
                            <h4 class="text-blue"><strong>Grafik Total Suara</strong></h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container mt-3">
                                <form method="GET" action="{{ route('dashboard') }}" id="yearForm">
                                    <div class="form-group" style="width: 300px">
                                        <select name="year" class="form-control" id="selected_year">
                                            @foreach ($schoolYears as $year)
                                                <option value="{{ $year->id }}"
                                                    {{ request('year', $defaultYearId) == $year->id ? 'selected' : '' }}>
                                                    {{ $year->name }} {{ $year->is_active ? '' : '(Non-Aktif)' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
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
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
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
