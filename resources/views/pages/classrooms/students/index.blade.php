@extends('layouts.app')

@section('title', 'Siswa')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Siswa</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('classrooms.index') }}">Kelas</a></div>
                    <div class="breadcrumb-item"><a
                            href="{{ route('classrooms.students.index', $classroom->id) }}">{{ $classroom->name }}</a>
                    </div>
                    <div class="breadcrumb-item"><a
                            href="{{ route('classrooms.students.index', $classroom->id) }}">Siswa</a>
                    </div>
                    <div class="breadcrumb-item">Daftar Siswa</div>
                </div>
            </div>

            <x-alert />

            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="pt-4 pr-4" style="float: right;">
                            <button class="btn btn-primary" id="ImportStudents">
                                Import Data Siswa
                            </button>
                            <a href="{{ route('classrooms.students.create', $classroom->id) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Data Siswa Baru
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIS</th>
                                            <th>Nama</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td class="py-2">{{ $loop->iteration }}</td>
                                                <td class="py-2">{{ $student->identity }}</td>
                                                <td class="py-2">{{ $student->name }}</td>
                                                <td class="py-2">{{ $student->gender }}</td>
                                                <td class="py-2">
                                                    @switch($student->status)
                                                        @case('Belum Memilih')
                                                            <div class="badge badge-warning">
                                                                Belum Memilih
                                                            </div>
                                                        @break

                                                        @case('Sudah Memilih')
                                                            <div class="badge badge-success">
                                                                Sudah Memilih
                                                            </div>
                                                        @break

                                                        @default
                                                            -
                                                    @endswitch
                                                </td>
                                                <td class="py-2">
                                                    <div class="d-flex" style="gap: 5px">
                                                        <a href="" class="btn btn-sm btn-primary">Reset</a>
                                                        <a href="{{ route('classrooms.students.edit', ['id_classroom' => $classroom->id, 'id_student' => $student->id]) }}"
                                                            class="btn btn-sm btn-warning">Ubah</a>
                                                        <button type="button"
                                                            data-action="{{ route('classrooms.students.destroy', ['id_classroom' => $classroom->id, 'id_student' => $student->id]) }}"
                                                            data-confirm-text="Anda yakin menghapus data siswa ini?"
                                                            class="btn btn-sm btn-danger btn-delete">
                                                            Hapus
                                                        </button>
                                                    </div>
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
        $('#ImportStudents').click(function() {
            $('#ImportStudentsModal').remove();

            var modal = `
                <div class="modal fade" id="ImportStudentsModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Import Siswa</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('classrooms.students.import', $classroom->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="formFileSm" class="form-label">Pilih File</label>
                                        <input class="form-control" id="formFileSm" type="file" name="import_file"
                                            accept=".xls,.xlsx">
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <a href="{{ asset('files/Insert-Siswa.xlsx') }}">Unduh Template Excel</a>
                                    <button type="submit" class="btn btn-primary">Import</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            $('body').append(modal);
            $('#ImportStudentsModal').modal('show');
        });
    </script>
@endpush
