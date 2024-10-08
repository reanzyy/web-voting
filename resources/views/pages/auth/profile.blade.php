@extends('layouts.app')

@section('title', 'Profile')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Profile</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></div>
                    <div class="breadcrumb-item">Ubah Profile</div>
                </div>
            </div>

            <x-alert/>

            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <form action="{{ route('profile.update') }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Nama <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $user->name) }}">
                                            <div class="invalid-feedback">
                                                @error('name')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Username <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="text" name="username"
                                                class="form-control @error('username') is-invalid @enderror"
                                                value="{{ old('username', $user->username) }}">
                                            <div class="invalid-feedback">
                                                @error('username')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Password <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="password" name="old_password"
                                                class="form-control @error('old_password') is-invalid @enderror">
                                            <div class="invalid-feedback">
                                                @error('old_password')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <div class="col-lg-12">
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah
                                                password</small>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Password Baru</label>
                                        <div class="col-lg-9">
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror">
                                            <div class="invalid-feedback">
                                                @error('password')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-lg-3 col-form-label">Konfirmasi Password</label>
                                        <div class="col-lg-9">
                                            <input type="password" name="password_confirmation"
                                                class="form-control @error('password_confirmation') is-invalid @enderror">
                                            <div class="invalid-feedback">
                                                @error('password_confirmation')
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
