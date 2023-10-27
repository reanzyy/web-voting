@extends('layouts.app')

@section('title', 'Daftar Laporan')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Laporan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></div>
                    <div class="breadcrumb-item">Daftar Laporan</div>
                </div>
            </div>

            <x-alert />

            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="pt-4 pr-4" style="float: right;">
                            <a href="{{ route('reports.report') }}" {{ $emptyCandidate == false ? 'target="_blank"' : '' }}
                                class="btn btn-primary"><i class="fa fa-print"></i> Cetak Laporan</a>
                        </div>
                        <div class="pt-4 pl-4" style="float: left; margin-bottom: 30px;">
                            <div class="d-flex" style="gap: 5px">
                                <select name="year" class="form-control">
                                    @foreach ($schoolYears as $year)
                                        <option value="{{ $year->id }}"
                                            {{ request('year', $defaultYearId) == $year->id ? 'selected' : '' }}>
                                            {{ $year->name }} {{ $year->is_active ? '' : '(Non-Aktif)' }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" id="btn-filter" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="10%">No</th>
                                            <th>Nama</th>
                                            <th>Jumlah Suara</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($candidates as $candidate)
                                            <tr>
                                                <td class="py-2">{{ $loop->iteration }}</td>
                                                <td class="py-2">
                                                    {{ $candidate->chairman . ' - ' . $candidate->deputy_chairman }}</td>
                                                <td class="py-2">{{ $candidate->votes->count() }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('select[name="year"]').change(function() {
                var selectedYear = $(this).val();
                var Filter = "{{ route('reports.index') }}" + "?year=" + selectedYear;
                $('#btn-filter').attr('href', Filter);
            });

            $('#btn-filter').click(function() {
                var selectedYear = $('select[name="year"]').val();
                var Filter = "{{ route('reports.index') }}" + "?year=" + selectedYear;
                window.location.href = Filter;
            });
        });
    </script>
@endpush
