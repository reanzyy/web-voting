<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Voting</title>

    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('/img/logo/favicon.png') }}" type="image/x-icon">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body class="antialiased">
    <div>
        <div class="d-flex justify-content-center mt-5" style="gap: 5%">
            @foreach ($candidates as $candidate)
                <div class="text-center card pt-2 px-4"
                    style="background-color: {{ $backgroundColor[$loop->index] }}; border:2px solid {{ $borderColor[$loop->index] }}">
                    <h5 style="font-weight: bold">Pasangan Calon {{ $candidate->sequence }}</h5>
                    <h1 style="font-family: 'Anton', sans-serif;">{{ $candidate->votes->count() }}</h1>
                    <p style="font-weight: bold">{{ $candidate->chairman }} <br>{{ $candidate->deputy_chairman }}</p>
                </div>
            @endforeach
        </div>
        <div class="container-fluid mt-4" style="width:80%">
            <canvas id="votingChart"></canvas>
        </div>
    </div>

    <style>
        .chart-title {
            text-align: center;
            margin-top: 10px;
        }

        .chart-container {
            position: relative;
            width: 80%;
            transition: height 1s ease-in-out;
            padding: 20px;
        }

        .chart {
            display: flex;
        }
    </style>

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
        setTimeout(function() {
            location.reload();
        }, 30000);
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
</body>

</html>
