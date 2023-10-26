@extends('layouts.app')

@section('title', 'Daftar Tahun Pelajaran')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tahun Pelajaran</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('school-years.index') }}">Tahun Pelajaran</a></div>
                    <div class="breadcrumb-item">Daftar Tahun Pelajaran</div>
                </div>
            </div>

            <x-alert />

            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="pt-4 pr-4" style="float: right;">
                            <button class="btn btn-primary" id="AddSchoolYears">
                                <i class="fa fa-plus"></i> Tambah Tahun Pelajaran Baru
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped datatable" style="width: 100%;"
                                    id="schoolYearsTable">
                                    <thead>
                                        <tr>
                                            <th width="10%">No</th>
                                            <th>Nama</th>
                                            <th>Status</th>
                                            <th width="25%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($school_years as $school_year)
                                            <tr>
                                                <td class="py-2">{{ $loop->iteration }}</td>
                                                <td class="py-2">{{ $school_year->name }}</td>
                                                <td>
                                                    @if ($school_year->is_active)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-info">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="py-2">
                                                    <button class="btn btn-sm btn-warning EditSchoolYears"
                                                        data-id="{{ $school_year->id }}"
                                                        data-name="{{ $school_year->name }}"
                                                        data-status="{{ $school_year->is_active }}">Ubah</button>
                                                    <button type="button"
                                                        data-action="{{ route('school-years.destroy', $school_year->id) }}"
                                                        data-confirm-text="Anda yakin menghapus data Tahun Pelajaran ini?"
                                                        class="btn btn-sm btn-danger btn-delete">Hapus</button>
                                                </td>
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
        $('#AddSchoolYears').click(function() {
            $('#addSchoolYearModal').remove();

            var modal = `
            <div class="modal fade" id="addSchoolYearModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Tahun Pelajaran</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('school-years.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group row mb-3">
                                    <label class="col-lg-3 col-form-label">Nama Tahun Pelajaran <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="name" class="form-control @error('name') is-invalid @enderror" required>
                                            @php
                                                $currentYear = date('Y');
                                                $yearsRange = range($currentYear, $currentYear + 5);
                                            @endphp
                                            @foreach ($yearsRange as $year)
                                                @php
                                                    $nextYear = $year + 1;
                                                    $academicYear = $year . '/' . $nextYear;
                                                @endphp
                                                <option value="{{ $academicYear }}" {{ old('name') == $academicYear ? 'selected' : '' }}>
                                                    {{ $academicYear }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('name')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-lg-3 col-form-label">Status <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select class="form-control" name="is_active">
                                            <option value="0">Tidak Aktif</option>
                                            <option value="1">Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;

            $('body').append(modal);
            $('#addSchoolYearModal').modal('show');
        });

        $('.EditSchoolYears').click(function() {
            $('#editSchoolYearsModal').remove();
            var schoolYearId = $(this).data('id');
            var schoolYearName = $(this).data('name');
            var schoolYearStatus = $(this).data('status');

            var modal = `
                <div class="modal fade" id="editSchoolYearsModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Tahun Pelajaran</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('school-years.update', ['id' => 'school_year_id']) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Nama Tahun Pelajaran <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <select name="name" class="form-control @error('name') is-invalid @enderror" required>
                                                <option value="${schoolYearName}">${schoolYearName}</option>
                                                <option disabled>------------</option>
                                                @php
                                                    $currentYear = date('Y');
                                                    $yearsRange = range($currentYear, $currentYear + 10);
                                                @endphp
                                                @foreach ($yearsRange as $year)
                                                    @php
                                                        $nextYear = $year + 1;
                                                        $academicYear = $year . '/' . $nextYear;
                                                    @endphp
                                                    <option value="{{ $academicYear }}" {{ old('name', 'schoolYearName') == $academicYear ? 'selected' : '' }}>
                                                        {{ $academicYear }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Status <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <select class="form-control" name="is_active">
                                                <option value="${ schoolYearStatus }">${schoolYearStatus ? 'Aktif' : 'Tidak Aktif' }</option>
                                                <option disabled>------------</option>
                                                <option value="0">Tidak Aktif</option>
                                                <option value="1">Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            modal = modal.replace('school_year_id', schoolYearId);
            modal = modal.replace('schoolYearName', schoolYearName);

            $('body').append(modal);
            $('#editSchoolYearsModal').modal('show');
        });
    </script>
@endpush
