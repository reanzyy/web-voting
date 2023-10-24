@extends('layouts.app')

@section('title', 'Ubah Siswa')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Ubah Siswa</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('classrooms.index') }}">Kelas</a></div>
                    <div class="breadcrumb-item"><a
                            href="{{ route('classrooms.students.index', $classroom->id) }}">{{ $classroom->name }}</a>
                    </div>
                    <div class="breadcrumb-item"><a
                            href="{{ route('classrooms.students.index', $classroom->id) }}">Siswa</a>
                    </div>
                    <div class="breadcrumb-item">Ubah Siswa</div>
                </div>
            </div>

            <x-alert />

            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <form
                                action="{{ route('classrooms.students.update', ['id_classroom' => $classroom->id, 'id_student' => $student->id]) }}"
                                method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-group row mb-3">
                                    <label class="col-lg-3 col-form-label">NIS <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="identity"
                                            class="form-control @error('identity') is-invalid @enderror"
                                            value="{{ old('identity', $student->identity) }}">
                                        <div class="invalid-feedback">
                                            @error('identity')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-lg-3 col-form-label">Nama <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $student->name) }}">
                                        <div class="invalid-feedback">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-lg-3 col-form-label">Jenis Kelamin <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                            <option value="{{ $student->gender }}">{{ $student->gender }}</option>
                                            <option disabled>-------------------</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('gender')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end border-top">
                                    <span class="text-muted float-start">
                                        <strong class="text-danger">*</strong> Harus diisi
                                    </span>
                                    <div style="float: right">
                                        <a class="btn btn-secondary"
                                            href="{{ route('classrooms.students.index', $classroom->id) }}">Kembali</a>
                                        <button class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection
