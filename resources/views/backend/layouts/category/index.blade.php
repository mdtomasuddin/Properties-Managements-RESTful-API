@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Categories
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
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="h3 mb-1">
                                    {{ request()->has('section') ? ucfirst(str_replace('_', ' ', request()->section)) : '' }}
                                    Categories
                                </h1>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li>Dashboard - </li>
                                        <li class="breadcrumb-item active" aria-current="page">Categories</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body table-responsive">
                                <table class="table table-bordered w-100" id="datatable">
                                    <thead>
                                        <tr>
                                            <th class="column-id">#</th>
                                            <th class="column-content">Name</th>
                                            <th class="column-content">Action</th>
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

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryForm">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" id="edit_category_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Category Name</label>
                            <input type="text" class="form-label form-control" id="edit_name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
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
                        url: "{{ route('categories.index') }}",
                        type: "GET",
                        data: function(d) {
                            let urlParams = new URLSearchParams(window.location.search);
                            d.section = urlParams.get('section');
                        }
                    },
                    dom: "<'row table-topbar'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>>" +
                        "<'row'<'col-12'tr>>" +
                        "<'row table-bottom'<'col-md-5 dataTables_left'i><'col-md-7'p>>",
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search records...",
                        lengthMenu: "Show _MENU_ entries",
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'name',
                            name: 'name',
                            orderable: false,
                            searchable: true,
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

            // Edit Category Click
            $(document).on('click', '.edit-category', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');

                $('#edit_category_id').val(id);
                $('#edit_name').val(name);

                $('#editCategoryModal').modal('show');
            });

            // Update Category
            $('#editCategoryForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#edit_category_id').val();
                let url = "{{ route('categories.update', ':id') }}".replace(':id', id);
                let formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    success: function(resp) {
                        if (resp.success) {
                            $('#editCategoryModal').modal('hide');
                            toastr.success(resp.message);
                            $('#datatable').DataTable().ajax.reload();
                        } else {
                            toastr.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'An error occurred.';
                        if (xhr.status === 422) {
                            errorMsg = Object.values(xhr.responseJSON.errors).flat().join(
                                '<br>');
                        }
                        toastr.error(errorMsg);
                    }
                });
            });
        });
    </script>
@endpush
