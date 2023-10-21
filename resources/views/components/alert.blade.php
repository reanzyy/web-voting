<div>
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
                <strong>Gagal!</strong> {{ session('error') }}
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
                <strong>Berhasil!</strong> {{ session('success') }}
            </div>
        </div>
    @endif
</div>
