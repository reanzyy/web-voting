@extends('layouts.app')

@section('title', 'Misi')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Misi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('candidates.index') }}">Kandidat</a></div>
                    <div class="breadcrumb-item"><a
                            href="{{ route('candidates.missions.index', $candidate->id) }}">{{ $candidate->name }} Kandidat {{ $candidate->sequence }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('candidates.missions.index', $candidate->id) }}">Misi</a></div>
                    <div class="breadcrumb-item">Daftar Misi</div>
                </div>
            </div>

            <x-alert />

            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="pt-4 pr-4" style="float: right;">
                            <button class="btn btn-primary" id="AddMissions"><i class="fa fa-plus"></i> Tambah Misi Baru</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="10%">Urutan</th>
                                            <th>Nama</th>
                                            <th width="25%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($missions as $mission)
                                            <tr>
                                                <td class="py-2">{{ $mission->sequence }}</td>
                                                <td class="py-2">{{ $mission->description }}</td>
                                                <td class="py-2">
                                                    <button class="btn btn-sm btn-warning EditMission"
                                                        data-mission-id="{{ $mission->id }}"
                                                        data-mission-sequence="{{ $mission->sequence }}"
                                                        data-mission-description="{{ $mission->description }}">Ubah</button>
                                                    <button type="button"
                                                        data-action="{{ route('candidates.missions.destroy', ['id_candidate' => $candidate->id, 'id_missions' => $mission->id]) }}"
                                                        data-confirm-text="Anda yakin menghapus misi ini?"
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
        $('#AddMissions').click(function() {
            $('#addMissionsModal').remove();

            var modal = `
                <div class="modal fade" id="addMissionsModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Misi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('candidates.missions.store', $candidate->id) }}" method="POST">
                                @csrf
                                <input type="hidden" value="{{ $candidate->id }}" name="candidate_id" class="form-control @error('description') is-invalid @enderror"></input>
                                <div class="modal-body">
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Urutan <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="number" name="sequence" class="form-control @error('sequence') is-invalid @enderror">
                                            <div class="invalid-feedback">
                                                @error('sequence')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Nama Misi <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror">
                                            <div class="invalid-feedback">
                                                @error('description')
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
            $('#addMissionsModal').modal('show');
        });

        $('.EditMission').click(function() {
            $('#editMissionModal').remove();
            var missionId = $(this).data('mission-id');
            var missionSequence = $(this).data('mission-sequence');
            var missionDescription = $(this).data('mission-description');

            var modal = `
                <div class="modal fade" id="editMissionModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Misi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('candidates.missions.update', ['id_candidate' => $candidate->id, 'id_missions' => 'missionId']) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" value="{{ $candidate->id }}" name="candidate_id" class="form-control @error('description') is-invalid @enderror"></input>
                                <div class="modal-body">
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Urutan <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="number" name="sequence" value="${missionSequence}" class="form-control @error('sequence') is-invalid @enderror">
                                            <div class="invalid-feedback">
                                                @error('sequence')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Nama Misi <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="text" name="description" value="${missionDescription}" class="form-control @error('description') is-invalid @enderror">
                                            <div class="invalid-feedback">
                                                @error('description')
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

            modal = modal.replace('missionId', missionId);

            $('body').append(modal);
            $('#editMissionModal').modal('show');
        });
    </script>
@endpush
