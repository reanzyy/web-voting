@extends('layouts.auth')

@section('title', 'Login')

@section('main')
    <div class="py-5">

        <x-alert />

        <div class="card card-primary">
            <div class="card-header">
                <h4>Silahkan login terlebih dahulu</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('login.process') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input id="username" type="text" class="form-control "form-control
                            @error('username') is-invalid @enderror"" name="username" value="{{ old('username') }}"
                            tabindex="1" autofocus>
                        <div class="invalid-feedback">
                            @error('username')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="d-block">
                            <label for="password" class="control-label">Password</label>
                        </div>
                        <input id="password" type="password" class="form-control" name="password" tabindex="2">
                        <div class="invalid-feedback">
                            please fill in your password
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
