@extends('layouts.app')

@section('title', 'Pengaturan')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pengaturan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('settings.index') }}">Pengaturan</a></div>
                    <div class="breadcrumb-item">Ubah Pengaturan</div>
                </div>
            </div>

            <x-alert />

            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <form action="{{ route('settings.update') }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Nama Sekolah <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="text" name="school_name"
                                                class="form-control @error('school_name') is-invalid @enderror"
                                                value="{{ old('school_name', $setting->school_name ?? '') }}">
                                            <div class="invalid-feedback">
                                                @error('school_name')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Kepala Sekolah <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="text" name="headmaster_name"
                                                class="form-control @error('headmaster_name') is-invalid @enderror"
                                                value="{{ old('headmaster_name', $setting->headmaster_name ?? '') }}">
                                            <div class="invalid-feedback">
                                                @error('headmaster_name')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Wakil Kepala Sekolah <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="text" name="deputy_headmaster_name"
                                                class="form-control @error('deputy_headmaster_name') is-invalid @enderror"
                                                value="{{ old('deputy_headmaster_name', $setting->deputy_headmaster_name ?? '') }}">
                                            <div class="invalid-feedback">
                                                @error('deputy_headmaster_name')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Waktu Mulai Pemilihan <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="datetime-local" name="start_date"
                                                class="form-control @error('start_date') is-invalid @enderror"
                                                value="{{ old('start_date', $setting->start_date ?? '') }}">
                                            <div class="invalid-feedback">
                                                @error('start_date')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Waktu Akhir Pemilihan <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="datetime-local" name="end_date"
                                                class="form-control @error('end_date') is-invalid @enderror"
                                                value="{{ old('end_date', $setting->end_date ?? '') }}">
                                            <div class="invalid-feedback">
                                                @error('end_date')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer border-top text-end">
                                    <span class="text-muted">
                                        <strong class="text-danger">*</strong> Harus diisi
                                    </span>
                                    <div style="float: right">
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
