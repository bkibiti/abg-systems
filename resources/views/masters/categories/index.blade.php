@extends("layouts.master")

@section('content-title')
    Product Categories
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Masters / Product Categories</a></li>
@endsection

@section("content")

    <div class="col-sm-12">
        <div class="card-block">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                      
                            <button style="float: right; margin-bottom: 1%;" class="btn btn-icon btn-rounded btn-warning btn-sm" data-toggle="modal" 
                                    title="Add Category" data-target="#create"><i class="feather icon-plus"></i>
                            </button>
                
                        <div class="table-responsive">
                            <table id="fixed-header-1" class="display table nowrap table-striped table-hover"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>Id #</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @foreach($categories as $category)
                                        <td>{{$category->id}}</td>
                                        <td>{{$category->name}}</td>

                                        <td>
                                            <a href="#">
                                                <button class="btn btn-sm btn-rounded btn-info"
                                                        data-id="{{$category->id}}"
                                                        data-name="{{$category->name}}"
                                                        type="button"
                                                        data-toggle="modal" data-target="#edit">Edit
                                                </button>
                                            </a>
                                            <a href="#">
                                                <button class="btn btn-sm btn-rounded btn-danger"
                                                        data-id="{{$category->id}}"
                                                        data-name="{{$category->name}}"
                                                        type="button"
                                                        data-toggle="modal"
                                                        data-target="#delete">
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
    </div>
    @include('masters.categories.create')
    @include('masters.categories.delete')
    @include('masters.categories.edit')

@endsection



@push("page_scripts")
    @include('partials.notification')
    <script>
        var title = document.title;
        document.title = title.concat(" | Product Category");
    </script>

    <script>

        $('#edit').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('.modal-body #id_edit').val(button.data('id'));
            modal.find('.modal-body #name_edit').val(button.data('name'))
        });

        $('#delete').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var message = "Are you sure you want to delete '".concat(button.data('name'), "'?");
            var modal = $(this);

            modal.find('.modal-body #message').text(message);
            modal.find('.modal-body #id').val(button.data('id'));

        });

        $('#fixed-header-1').DataTable({
            bAutoWidth: true,

        });
    </script>
@endpush
