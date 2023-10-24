@extends('layouts.app')

@section('title', 'Visi')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Visi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('candidates.index') }}">Kandidat</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('candidates.index', $candidate->id) }}">Kandidat
                            {{ $candidate->sequence }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('candidates.visions.index', $candidate->id) }}">Visi</a>
                    </div>
                    <div class="breadcrumb-item">Daftar Visi</div>
                </div>
            </div>

            <x-alert />

            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="pt-4 pr-4" style="float: right;">
                            <button class="btn btn-primary" id="AddVisions"><i class="fa fa-plus"></i> Tambah Visi
                                Baru</button>
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
                                        @foreach ($visions as $vision)
                                            <tr>
                                                <td class="py-2">{{ $vision->sequence }}</td>
                                                <td class="py-2">{{ $vision->description }}</td>
                                                <td class="py-2">
                                                    <button class="btn btn-sm btn-warning EditVision"
                                                        data-vision-id="{{ $vision->id }}"
                                                        data-vision-sequence="{{ $vision->sequence }}"
                                                        data-vision-description="{{ $vision->description }}">Ubah</button>
                                                    <button type="button"
                                                        data-action="{{ route('candidates.visions.destroy', ['id_candidate' => $candidate->id, 'id_visions' => $vision->id]) }}"
                                                        data-confirm-text="Anda yakin menghapus visi ini?"
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
        $('#AddVisions').click(function() {
            $('#addVisionsModal').remove();

            var modal = `
                <div class="modal fade" id="addVisionsModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Visi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('candidates.visions.store', $candidate->id) }}" method="POST">
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
                                        <label class="col-lg-3 col-form-label">Nama Visi <span class="text-danger">*</span></label>
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
            $('#addVisionsModal').modal('show');
        });

        $('.EditVision').click(function() {
            $('#editVisionModal').remove();
            var visionId = $(this).data('vision-id');
            var visionSequence = $(this).data('vision-sequence');
            var visionDescription = $(this).data('vision-description');

            var modal = `
                <div class="modal fade" id="editVisionModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class = "modal-header">
                                <h5 class="modal-title">Edit Visi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('candidates.visions.update', ['id_candidate' => $candidate->id, 'id_visions' => 'visionId']) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" value="{{ $candidate->id }}" name="candidate_id" class="form-control @error('description') is-invalid @enderror"></input>
                                <div class="modal-body">
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Urutan <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="number" name="sequence" value="${visionSequence}" class="form-control @error('sequence') is-invalid @enderror">
                                            <div class="invalid-feedback">
                                                @error('sequence')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-lg-3 col-form-label">Nama Visi <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input type="text" name="description" value="${visionDescription}" class="form-control @error('description') is-invalid @enderror">
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

            modal = modal.replace('visionId', visionId);

            $('body').append(modal);
            $('#editVisionModal').modal('show');
        });
    </script>
@endpush
