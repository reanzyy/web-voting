@extends('layouts.app')

@section('title', 'Ubah Kandidat')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Ubah Kandidat</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('candidates.index') }}">Kandidat</a></div>
                    <div class="breadcrumb-item">Ubah Kandidat</div>
                </div>
            </div>

            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <form action="{{ route('candidates.update', $candidate->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group row mb-3">
                                    <label class="col-lg-3 col-form-label">Nomer Urut <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="number" name="sequence"
                                            class="form-control @error('sequence') is-invalid @enderror"
                                            value="{{ old('sequence', $candidate->sequence) }}">
                                        <div class="invalid-feedback">
                                            @error('sequence')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-lg-3 col-form-label">Calon Ketua <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="chairman"
                                            class="form-control @error('chairman') is-invalid @enderror"
                                            value="{{ old('chairman', $candidate->chairman) }}">
                                        <div class="invalid-feedback">
                                            @error('chairman')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-lg-3 col-form-label">Calon Wakil Ketua <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="deputy_chairman"
                                            class="form-control @error('deputy_chairman') is-invalid @enderror"
                                            value="{{ old('deputy_chairman', $candidate->deputy_chairman) }}">
                                        <div class="invalid-feedback">
                                            @error('deputy_chairman')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-lg-3 col-form-label">Foto Calon Ketua <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <div class="custom-file">
                                            <input type="file" accept=".png, .jpg, .jpeg" name="photo_chairman"
                                                class="custom-file-input"
                                                value="{{ old('photo_chairman', $candidate->photo_chairman) }}">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <div class="form-text text-muted">Ukuran foto maksimal 2MB
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-lg-3 col-form-label">Foto Calon Wakil Ketua <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <div class="custom-file">
                                            <input type="file" accept=".png, .jpg, .jpeg" name="photo_deputy_chairman"
                                                class="custom-file-input"
                                                value="{{ old('photo_deputy_chairman', $candidate->photo_deputy_chairman) }}">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <div class="form-text text-muted">Ukuran foto maksimal 2MB
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end border-top">
                                    <span class="text-muted float-start">
                                        <strong class="text-danger">*</strong> Harus diisi
                                    </span>
                                    <div style="float: right">
                                        <a class="btn btn-secondary" href="{{ route('candidates.index') }}">Kembali</a>
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
