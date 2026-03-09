@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Rule Regulations
@endsection

@push('styles')
    <style>
        .dataTables_filter {
            text-align: right;
        }

        .dataTables_filter input {
            max-width: 250px;
            display: inline-block;
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
                                    <h2 class="h3 mb-1">Rule Regulations</h2>
                                    <p>List of all rule regulations and their details.</p>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-body table-responsive">
                                        <table class="table table-bordered w-100" id="datatable">
                                            <thead>
                                                <tr>
                                                    <th class="column-id">#</th>
                                                    <th class="column-content">Property Name</th>
                                                    <th class="column-content">Title</th>
                                                    <th class="column-content">Description</th>
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
                        url: "{{ route('admin-rule-regulation.index') }}",
                        type: "GET",
                    },
                    dom: "<'row table-topbar'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>>" +
                        "<'row'<'col-12'tr>>" +
                        "<'row table-bottom'<'col-md-5 dataTables_left'i><'col-md-7'p>>",
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search records...",
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
                            data: 'property_name',
                            name: 'property_name',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'short_title',
                            name: 'short_title',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'short_description',
                            name: 'short_description',
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
            }
        });
    </script>
@endpush
