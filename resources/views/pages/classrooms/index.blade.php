@extends('layouts.app')

@section('title', 'Kelas')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kelas</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('classrooms.index') }}">Kelas</a></div>
                    <div class="breadcrumb-item">Daftar Kelas</div>
                </div>
            </div>

            <x-alert />

            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="pt-4 pr-4" style="float: right;">
                            <button class="btn btn-primary" id="AddClassroom"><i class="fa fa-plus"></i> Tambah Kelas
                                Baru</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="10%">No</th>
                                            <th>Nama</th>
                                            <th width="25%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classrooms as $classroom)
                                            <tr>
                                                <td class="py-2">{{ $loop->iteration }}</td>
                                                <td class="py-2">{{ $classroom->name }}</td>
                                                <td class="py-2">
                                                    <a href="{{ route('classrooms.students.index', $classroom->id) }}"
                                                        class="btn btn-sm btn-primary">Lihat Siswa</a>
                                                    <button class="btn btn-sm btn-warning EditClassroom"
                                                        data-classroom-id="{{ $classroom->id }}"
                                                        data-classroom-name="{{ $classroom->name }}">Ubah</button>
                                                    <button type="button"
                                                        data-action="{{ route('classrooms.destroy', $classroom->id) }}"
                                                        data-confirm-text="Anda yakin menghapus data kelas ini?"
                                                        class="btn btn-sm btn-danger btn-delete">
                                                        Hapus
                                                    </button>
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
        $('#AddClassroom').click(function() {
            $('#addClassroomModal').remove();

            var modal = `
                <div class="modal fade" id="addClassroomModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Kelas Baru</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('classrooms.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Nama Kelas <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror">
                                            <div class="invalid-feedback">
                                                @error('name')
                                                    {{ $message }}
                                                @enderror
                                            </div>
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
            $('#addClassroomModal').modal('show');
        });

        $('.EditClassroom').click(function() {
            $('#editClassroomModal').remove();
            var classroomId = $(this).data('classroom-id');
            var classroomName = $(this).data('classroom-name');

            var modal = `
                <div class="modal fade" id="editClassroomModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Kelas</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('classrooms.update', ['id' => 'classroomId']) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Nama Kelas <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="text" name="name" value="${classroomName}" class="form-control @error('name') is-invalid @enderror">
                                            <div class="invalid-feedback">
                                                @error('name')
                                                {{ $message }}
                                                @enderror
                                            </div>
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

            modal = modal.replace('classroomId', classroomId);

            $('body').append(modal);
            $('#editClassroomModal').modal('show');
        });
    </script>
@endpush
