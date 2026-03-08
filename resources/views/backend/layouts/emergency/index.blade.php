@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Emergencies
@endsection

@push('styles')
    <style>
        .dataTables_filter {
            margin-bottom: 15px;
        }

        .dataTables_filter input {
            max-width: 180px;
            height: 38px;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .dataTables_filter input:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }
    </style>
@endpush

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-11 col-md-12 col-sm-12 col-12">
                        <div class="row">
                            <div class="col-xl-12">
                                <div id="validation" class="mb-4">
                                    <h2 class="h3 mb-1">Emergencies</h2>
                                    <p>List of all emergency reports in the system.</p>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-body table-responsive">
                                        <table class="table table-bordered w-100" id="datatable">
                                            <thead>
                                                <tr>
                                                    <th class="column-id">#</th>
                                                    <th class="column-content">Name</th>
                                                    <th class="column-content">Property Name</th>
                                                    <th class="column-content">Category</th>
                                                    <th class="column-content">Description</th>
                                                    <th class="column-content">Location</th>
                                                    <th class="column-action">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            if (!$.fn.DataTable.isDataTable('#datatable')) {
                var table = $('#datatable').DataTable({
                    responsive: true,
                    order: [],
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"],
                    ],
                    processing: true,
                    serverSide: true,
                    pagingType: "full_numbers",
                    ajax: {
                        url: "{{ route('admin-emergency.index') }}",
                        type: "GET",
                    },
                    dom: "<'row table-topbar'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>>" +
                        "<'row'<'col-12'tr>>" +
                        "<'row table-bottom'<'col-md-5 dataTables_left'i><'col-md-7'p>>",
                    language: {
                        search: "",
                        searchPlaceholder: "Search...",
                        lengthMenu: "Show _MENU_ entries",
                        processing: `
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>`,
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'user_name',
                            name: 'user_name',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'property_name',
                            name: 'property_name',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'category_name',
                            name: 'category_name',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'description',
                            name: 'description',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'location',
                            name: 'location',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                    ],
                });

                $('#datatable').on('draw.dt', function() {
                    $('td.column-action').each(function() {
                        let buttonCount = $(this).find('a').length;
                        let width = 5 + (buttonCount - 1) * 5;
                        $(this).css('width', width + '%');
                    });
                });
            }
        });
    </script>
@endpush
