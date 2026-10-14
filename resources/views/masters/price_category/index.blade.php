@extends("layouts.master")

@section('page_css')
    <style>


    </style>
@endsection

@section('content-title')
    Price Categories
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#"> Masters / Price Categories </a></li>
@endsection

@section("content")

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                    <div class="table-responsive">
                        <table id="fixed-header-1" class="display table nowrap table-striped table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($price_category as $price)
                                <tr>
                                    <td>{{$price->name}}</td>
                                    <td>{{$price->description}}</td>
                                    <td>{{$price->active}}</td>

                                    <td>
                                        <a href="#">
                                            <button class="btn btn-sm btn-rounded btn-info" data-id="{{$price->id}}"
                                                    data-name="{{$price->name}}"
                                                    data-description="{{$price->description}}"
                                                    data-active="{{$price->active}}"

                                                    type="button"
                                                    data-toggle="modal" data-target="#edit">Edit
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

    @include('masters.price_category.edit')

@endsection

@push("page_scripts")

    @include('partials.notification')

    <script>
        var title = document.title;
        document.title = title.concat(" | Price Category");
    </script>
    <script>

        $('#edit').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('.modal-body #price_category_id').val(button.data('id'));
            modal.find('.modal-body #name').val(button.data('name'));
            modal.find('.modal-body #description').val(button.data('description'))
            modal.find('.modal-body #active').val(button.data('active'))
            
        });


        $('#fixed-header-1').DataTable({
            bAutoWidth: false,

        });


    </script>

@endpush
