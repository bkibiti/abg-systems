
@extends("layouts.master")

@section('content-title')
  Adjustment Reasons
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="bi bi-house"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Masters / Adjustment Reasons</a></li>
@endsection

@section("content")

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Adjustment Reasons</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Add Reason </button>
                </div>
            </div>
            <div class="card-body">
                        <div class="table-responsive">
                            <table id="fixed-header-1" class="display table nowrap table-striped table-hover"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>Reason</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($adjustment as $adjustments)
                                    <tr>
                                        <td>{{$adjustments->reason}}</td>
                                        <td>
                                            <a href="#">
                                                <button class="btn btn-sm btn-info"
                                                        data-id="{{$adjustments->id}}"
                                                        data-name="{{$adjustments->reason}}"
                                                        type="button"
                                                        data-bs-toggle="modal" data-bs-target="#edit">Edit
                                                </button>
                                            </a>
                                            <a href="#">
                                                <button class="btn btn-sm btn-danger"
                                                        data-id="{{$adjustments->id}}"
                                                        data-name="{{$adjustments->reason}}" type="button"
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
@include('masters.adjustment_reason.create')
@include('masters.adjustment_reason.delete')
@include('masters.adjustment_reason.edit')

@endsection

@push("page_scripts")

    @include('partials.notification')

    <script>
        var title = document.title;
        document.title = title.concat(" | Adjustment Reason");
    </script>
    <script>

        $('#edit').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('.modal-body #price_category_id').val(button.data('id'));
            modal.find('.modal-body #name_edit').val(button.data('name'));
            modal.find('.modal-body #code_edit').val(button.data('code'));
            modal.find('.modal-body #status_edit').val(button.data('status'))

        });

        $('#delete').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);

            var message = "Are you sure you want to delete '".concat(button.data('name'), "'?");
            var modal = $(this);

            modal.find('.modal-body #message').text(message);
            modal.find('.modal-body #price_category_id').val(button.data('id'))
        });

        $('#fixed-header-1').DataTable({
            bAutoWidth: false,
            columnDefs: [
                {
                    "targets": 1,
                    "className": "text-center"
                }
            ]
        });


    </script>
@endpush
