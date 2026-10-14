@extends("layouts.master")

@section('content-title')
    Product Categories
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="bi bi-house"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Masters / Product Categories</a></li>
@endsection

@section("content")

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Product Categories</h3>
                @can('Manage Products Categories')
                <div class="card-tools">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" title="Add Category" data-bs-target="#create"><i class="bi bi-plus-lg"></i> Add Category</button>
                </div>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="categories-table" class="display table nowrap table-striped table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->products_count }}</td>
                                <td>
                                    @can('Manage Products Categories')
                                    <button class="btn btn-sm btn-info"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            type="button"
                                            data-bs-toggle="modal" data-bs-target="#edit">Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            type="button"
                                            data-bs-toggle="modal" data-bs-target="#delete">Delete
                                    </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('masters.categories.create')
    @include('masters.categories.edit')
    @include('masters.categories.delete')
@endsection

@push("page_scripts")
    @include('partials.notification')
    <script>
        document.title = document.title.concat(" | Product Categories");

        $('#categories-table').DataTable({
            bAutoWidth: false,
            columnDefs: [{ targets: [1, 2], className: "text-center" }]
        });

        $('#edit').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('.modal-body #id_edit').val(button.data('id'));
            modal.find('.modal-body #name_edit').val(button.data('name'));
        });

        $('#delete').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            modal.find('.modal-body #message').text("Are you sure you want to delete '".concat(button.data('name'), "'?"));
            modal.find('.modal-body #id_delete').val(button.data('id'));
        });
    </script>
@endpush
