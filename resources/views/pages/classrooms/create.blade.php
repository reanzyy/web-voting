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
                    <div class="breadcrumb-item">Tambah Kelas</div>
                </div>
            </div>

            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <form action="{{ route('classrooms.store') }}" method="post">
                                @csrf
                                <div class="form-group row mb-3">
                                    <label class="col-lg-3 col-form-label">Nama <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}">
                                        <div class="invalid-feedback">
                                            @error('name')
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
                                        <a class="btn btn-secondary" href="{{ route('classrooms.index') }}">Kembali</a>
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
