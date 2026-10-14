@extends("layouts.master")

@section('page_css')
    <style>


    </style>
@endsection

@section('content-title')
    Products
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="bi bi-house"></i></a></li>
    <li class="breadcrumb-item"><a href="#"> Masters / Products </a></li>
@endsection

@section("content")


    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Product List</h3>
                <div class="card-tools">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" title="Add Product" data-bs-target="#create"><i class="bi bi-plus-lg"></i> Add Product</button>
                </div>
            </div>
            <div class="card-body">
                <div>


                    <div id="product-table" class="table-responsive">
                        <table id="fixed-header1" class="display table nowrap table-striped table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                {{-- <th>Id #</th> --}}
                                <th>Name</th>
                                <th>Min Qty</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                                <tbody>
                                    @foreach($products as $prod)
                                    <tr>
                                            {{-- <td>{{$prod->id}}</td> --}}
                                            <td>{{$prod->name}}</td>
                                            <td>{{$prod->min_quantinty}}</td>
                                            <td>
                                                <a href="#">
                                                    <button class="btn btn-sm btn-info"
                                                            data-id="{{$prod->id}}"
                                                            data-name="{{$prod->name}}"
                                                            data-min_quantinty="{{$prod->min_quantinty}}"
                                                            type="button"
                                                            data-bs-toggle="modal" data-bs-target="#edit">Edit
                                                    </button>
                                                </a>
                                                <a href="#">
                                                    <button class="btn btn-sm btn-danger"
                                                            data-id="{{$prod->id}}"
                                                            data-name="{{$prod->name}}"
                                                            type="button"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#delete">
                                                        Delete
                                                    </button>
                                                </a>
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

        @include('masters.products.create')
        @include('masters.products.edit')
        @include('masters.products.delete')

        @endsection

        @push("page_scripts")

            @include('partials.notification')

            <script>
                var title = document.title;
                document.title = title.concat(" | Products");
            </script>
            <script>

                $('#fixed-header1').DataTable({
                    bAutoWidth: true,
                });

                $('#edit').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var modal = $(this);

                    modal.find('.modal-body #id').val(button.data('id'));
                    modal.find('.modal-body #name_edit').val(button.data('name'))
                    modal.find('.modal-body #min_quantinty_edit').val(button.data('min_quantinty'))
                });

                $('#delete').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var message = "Are you sure you want to delete '".concat(button.data('name'), "'?");
                    var modal = $(this);

                    modal.find('.modal-body #message').text(message);
                    modal.find('.modal-body #product_id').val(button.data('id'));
                });



            </script>

    @endpush
