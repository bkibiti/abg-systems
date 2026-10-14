@extends("layouts.master")

@section('page_css')
    <style>


    </style>
@endsection

@section('content-title')
    Stores
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#"> Masters / Stores </a></li>
@endsection

@section("content")

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div>
                    <button style="float: right;margin-bottom: 2%;" type="button" class="btn btn-primary btn-sm"
                            data-toggle="modal"
                            data-target="#create">
                        Add Store
                    </button>

                    <div class="table-responsive">
                        <table id="fixed-header-1" class="display table nowrap table-striped table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>

                                @foreach($stores as $store)

                                    <td>{{$store->name}}</td>
                                    <td>
                                        <a href="#">
                                            <button class="btn btn-sm btn-rounded btn-info" data-id="{{$store->id}}"
                                                    data-name="{{$store->name}}" type="button"
                                                    data-toggle="modal" data-target="#edit">Edit
                                            </button>
                                        </a>
                                        <a href="#">
                                            <button class="btn btn-sm btn-rounded btn-danger"
                                                    data-id="{{$store->id}}"
                                                    data-name="{{$store->name}}" type="button"
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
                    <!-- [ configuration table ] end -->
                </div>
            </div>
        </div>
        @include('masters.stores.create')
        @include('masters.stores.delete')
        @include('masters.stores.edit')

        @endsection

        @push("page_scripts")
            @include('partials.notification')
            <script>
                var title = document.title;
                document.title = title.concat(" | Stores");
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
                    modal.find('.modal-body #id').val(button.data('id'))
                });

                $('#fixed-header-1').DataTable({
                    bAutoWidth: false,
                    aoColumns: [
                        {"sWidth": "70%"},
                        {"sWidth": "30%"}
                    ],
                    columnDefs: [
                        {
                            "targets": 1,
                            "className": "text-center"
                        }
                    ]
                });


            </script>
    @endpush
