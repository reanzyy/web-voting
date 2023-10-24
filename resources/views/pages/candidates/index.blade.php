@extends('layouts.app')

@section('title', 'Daftar Kandidat')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kandidat</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('candidates.index') }}">Kandidat</a></div>
                    <div class="breadcrumb-item">Daftar Kandidat</div>
                </div>
            </div>

            <x-alert />

            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="pt-4 pr-4" style="float: right;">
                            <a href="{{ route('candidates.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Data Kandidat Baru
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="10%">No</th>
                                            <th>Calon Ketua</th>
                                            <th>Calon Wakil Ketua</th>
                                            <th>Foto Calon Ketua</th>
                                            <th>Foto Calon Wakil Ketua</th>
                                            <th width="20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($candidates as $candidate)
                                            <tr>
                                                <td class="py-2">{{ $candidate->sequence }}</td>
                                                <td class="py-2">{{ $candidate->chairman }}</td>
                                                <td class="py-2">{{ $candidate->deputy_chairman }}</td>
                                                <td class="py-2"><img src="{{ Storage::url($candidate->photo_chairman) }}"
                                                        class="img-fluid" width="100" alt=""></td>
                                                <td class="py-2"><img
                                                        src="{{ Storage::url($candidate->photo_deputy_chairman) }}"
                                                        class="img-fluid" width="100" alt=""></td>
                                                <td class="py-2">
                                                    <div class="d-flex" style="gap: 5px">
                                                        <a href="{{ route('candidates.visions.index', $candidate->id) }}"
                                                            class="btn btn-sm btn-primary">Visi</a>
                                                        <a href="{{ route('candidates.missions.index', $candidate->id) }}"
                                                            class="btn btn-sm btn-primary">Misi</a>
                                                        <a href="{{ route('candidates.edit', $candidate->id) }}"
                                                            class="btn btn-sm btn-warning">Ubah</a>
                                                        <button type="button"
                                                            data-action="{{ route('candidates.destroy', $candidate->id) }}"
                                                            data-confirm-text="Anda yakin menghapus data kandidat ini?"
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
