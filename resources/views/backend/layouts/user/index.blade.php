@extends('backend.app')


@section('title')
    {{ env('APP_NAME') }} || User
@endsection

@push('styles')
    <style>
        .dt-info {
            display: flex;
            justify-content: end;
            padding-bottom: 10px;
            padding-right: 25px;
        }

        .paging_full_numbers {
            display: flex;
            justify-content: end;
            padding-bottom: 10px;
            padding-right: 20px;
        }
    </style>
@endpush

@section('content')
    <div id="overlay"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0, 0, 0, 0.5); z-index:9999;">
    </div>
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div>
                    <!-- row -->
                    <div class="row">
                        <div class="col-12">
                            <!-- card -->
                            <div class="card mb-4">
                                <div class="card-header  ">
                                    <div class="row justify-content-between">
                                        <div class=" col-lg-4 col-md-6">
                                            <h3 class="mb-0 ">Users</h3>
                                        </div>
                                        <div class="col-md-4 mb-3 d-flex gap-3">
                                            <input type="search" id="search-input" class="form-control"
                                                placeholder="Search for user">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive table-card">
                                        <table class="table mb-0 text-nowrap table-centered" id="data-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Username</th>
                                                    <th scope="col">Email </th>
                                                    <th scope="col">Gender</th>
                                                    <th scope="col">Status </th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
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
    {{-- Datatable --}}
    <script>
        let dTable;

        $(document).ready(function() {
            /**
             * Initializing the DataTable with custom configurations
             */
            try {
                if (!$.fn.DataTable.isDataTable('#data-table')) {
                    dTable = $('#data-table').DataTable({
                        ordering: false,
                        lengthMenu: [
                            [10, 25, 50, 100, 200, 500, -1],
                            [10, 25, 50, 100, 200, 500, "All"]
                        ],
                        processing: true,
                        responsive: true,
                        serverSide: true,
                        searching: false,
                        language: {
                            processing: ''
                        },
                        scroller: {
                            loadingIndicator: true
                        },
                        pagingType: "full_numbers",
                        dom: "<'row justify-content-between table-topbar'<'col-md-2 col-sm-4 px-0'f>>tipr",
                        ajax: {
                            url: "{{ route('admin.user.index') }}",
                            type: "GET",
                            data: (d) => {
                                d.search = $('#search-input').val(); // Send custom search input value
                            }
                        },
                        columns: [
                            {
                                data: 'name',
                                name: 'name',
                                orderable: false,
                                searchable: true
                            },
                            {
                                data: 'handle',
                                name: 'handle',
                                orderable: false,
                                searchable: true
                            },
                            {
                                data: 'email',
                                name: 'email',
                                orderable: false,
                                searchable: true
                            },
                            {
                                data: 'gender',
                                name: 'gender',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'status',
                                name: 'status',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            },
                        ]
                    });
                    // Custom search functionality for the DataTable
                    $('#search-input').on('keyup', function() {
                        dTable.draw(); // Redraw the table with the updated search input value
                    });
                }
            } catch (e) {
                toastr.error('Something went wrong while initializing DataTable');
                console.error(e);
            }
        });
    </script>
@endpush
