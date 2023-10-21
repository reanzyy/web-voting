<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', env('APP_NAME'))</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    @stack('style')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- END GA -->
</head>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <!-- Header -->
            @include('components.header')

            <!-- Sidebar -->
            @include('components.sidebar')

            <!-- Content -->

            @yield('main')

            <!-- Footer -->
            {{-- @include('components.footer') --}}
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('library/tooltip.js/dist/umd/tooltip.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @stack('scripts')

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>


    <script>
        $('body').on('click', '.btn-delete', function() {
            const title = $(this).data('confirm-title') || "Anda yakin?";
            const text = $(this).data('confirm-text') || "Anda yakin menghapus data ini?";
            const icon = $(this).data('confirm-icon') || "warning";
            const action = $(this).data('action');

            if (!action) {
                return;
            }

            swal({
                    title,
                    text,
                    icon,
                    buttons: [
                        "Batalkan",
                        "Ya, Lakukan"
                    ]
                })
                .then(function(willDelete) {
                    if (willDelete) {
                        const form = $(`<form action="${action}" method="POST">
                            @csrf
                            @method('delete')
                        </form>`);
                        $('body').append(form);
                        form.submit();
                    }
                });
        });
    </script>

    <script>
        if ($('.datatable').length > 0) {
            // Append row on thead for place search box
            $('table.datatable:not(.noinit):not(.no-init):not(.nofilter) thead tr')
                .clone(true)
                .appendTo('table.datatable:not(.noinit) thead');

            // Add a search box in thead, to search by individual column
            $('table.datatable thead tr:eq(1) th').each(function(i) {
                let title = $(this).text();
                if ($(this).attr('datatable-skip-search')) {
                    return;
                }

                if (title && !['#', 'Aksi'].includes(title)) {
                    $(this).html(
                        '<input type="text" class="form-control form-control-sm" placeholder="&#x1F50D;" style="min-width:100px;">'
                    );
                    $('input', this).on('keyup change', function() {
                        if (table.column(i).search() !== this.value) {
                            table.column(i)
                                .search(this.value)
                                .draw();
                        }
                    });
                } else {
                    $(this).html('');
                }
            });

            // Init datatable with export buttons
            const dtLang = {
                config: {
                    "sEmptyTable": "Tidak ada data yang tersedia pada tabel ini",
                    "sProcessing": "Sedang memproses...",
                    "sLengthMenu": "&nbsp;_MENU_",
                    "sLoadingRecords": '<i class="fa fa-spinner fa-spin fa-fw"></i><span class="sr-only">Memuat</span> Memuat...',
                    "sProcessing": "Memproses...",
                    "sZeroRecords": "Tidak ditemukan data yang sesuai",
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "sInfoPostFix": "",
                    "sSearch": "Cari:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sPrevious": "Sebelumnya",
                        "sNext": "Selanjutnya",
                        "sLast": "Terakhir"
                    }
                },
                menu: {
                    entries: "entri",
                    allEntries: "Semua entri",
                }
            };

            const useKeyTable = $('.datatable.datatable-keytable').length > 0 || $('.datatable.datatable-scroll-x').length >
                0 ?
                true :
                false;

            let table = $('.datatable').DataTable({
                keys: useKeyTable,
                orderCellsTop: true,
                dom: '<"row"<"col-md-5 dt-feat-right"Bl><"col-md-7 dt-toolbar-right">>rtip',
                lengthMenu: [
                    [25, 50, 100, -1],
                    [`25 ${dtLang.menu.entries}`, `50 ${dtLang.menu.entries}`, `100 ${dtLang.menu.entries}`,
                        dtLang.menu.allEntries
                    ]
                ],
                oLanguage: dtLang.config,
            });
        }
    </script>
</body>

</html>
