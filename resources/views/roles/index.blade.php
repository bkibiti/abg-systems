@extends("layouts.master")


@section('page_css')
@endsection

@section('content-title')
    Roles

@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#"> User Management / Roles </a> </li>
@endsection



@section("content")

<div class="col-sm-12">

<div class="card">



    <div class="card-body">
            <a href="{{route('roles.create')}}">
                <button type="button" style="float: right; margin-bottom: 1%;" class="btn btn-icon btn-rounded btn-warning btn-sm" data-toggle="modal" 
                        title="Add Role" data-target="#register"><i class="feather icon-plus"></i>
                </button>
            </a>
        <div class="table-responsive">
            <table id="fixed-header" class="display table nowrap table-striped table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                <td>{{$role->name}}</td>
                <td>{{$role->description}}</td>
                <td>
                    <a href="{{route('roles.edit',$role->id)}}">
                        <button class="btn btn-info btn-rounded btn-sm"  type="button">Edit</button>
                    </a>
                    <a href="#">
                        <button class="btn btn-danger btn-rounded btn-sm" data-id="{{$role->id}}" data-name="{{$role->name}}" type="button" data-toggle="modal" data-target="#deleteModal" > Delete</button>
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

@endsection

@push("page_scripts")
@include('roles.delete')

@include('partials.notification')
<script>
    var title = document.title;
    document.title = title.concat(" | Roles");
</script>

<script>

   $('#deleteModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget)
      var id = button.data('id')
      var message =  "Are you sure you want to delete Role '".concat(button.data('name'), "'?") ;
      var modal = $(this)
      modal.find('.modal-body #message').text(message);
      modal.find('.modal-body #role_id').val(id)
    })

</script>

@endpush
