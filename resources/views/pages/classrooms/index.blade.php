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
                            <a href="{{ route('classrooms.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Data Kelas Baru
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th width="30%">Aksi</th>
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
                                                    <a href="{{ route('classrooms.edit', $classroom->id) }}"
                                                        class="btn btn-sm btn-warning">Ubah</a>
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
