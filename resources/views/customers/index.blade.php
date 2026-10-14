@extends("layouts.master")


@section('page_css')
@endsection

@section('content-title')
    Customers

@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#"> Customers Management </a> </li>
@endsection



@section("content")

<div class="col-sm-12">

<div class="card">



    <div class="card-body">
        <form id="expense_form" action="{{route('users.search')}}" method="GET">
            @csrf()

            <div class="form-group row">

                <div class="col-md-2">
                    <select class="form-control select2"  class="form-control" name="status"  data-placeholder="Select Status" required data-width="100%">
                        <option value="0" {{ (old('status')==0 ? "selected":"") }} >All Customers</option>
                        <option value="1" {{ (old('status')==1 ? "selected":"") }} >Active Customers</option>
                        <option value="2" {{ (old('status')==2 ? "selected":"") }} >Inctive Customers</option>
                    </select>
                </div>
              
               
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>

                <div class="col-md-6"></div>
                <div class="col-md-2">
                    @can('Manage Customers')
                        <button type="button" style="float: right; margin-bottom: 1%;" class="btn btn-icon btn-rounded btn-warning btn-sm" data-toggle="modal" 
                                     title="Add Customer" data-target="#create"><i class="feather icon-plus"></i></button>
                    @endcan
                </div>
            </div>

        </form>

        <hr>
      
          
        <div class="table-responsive">
            <table id="fixed-header" class="display table nowrap table-striped table-hover" style="width:100%">
            <thead>
                <tr>
                <th>Customer No</th>
                <th>Name</th>
                <th>E-mail</th>
                <th>Mobile</th>
                <th>Date of Birth</th>
                <th>Status</th>
                @can('Manage Customers')
                <th>Actions</th>
                @endcan
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $c)
                    <tr>
                    <td>{{$c->cno}}</td>
                    <td>{{$c->name}} </td>
                    <td>{{$c->email}}</td>
                    <td>{{$c->mobile}}</td>
                    <td>{{$c->dob}}</td>
                    <td>
                        @if ($c->status == 'Active')
                            <h6 class="m-0 text-c-green">{{$c->status}} </h6>
                        @endif
                        @if ($c->status == 'Inactive')
                            <h6 class="m-0 text-c-red">{{$c->status}} </h6>
                        @endif
                     
                    </td>

                    @can('Manage Customers')
                        <td style='white-space: nowrap'>
                    
                            <a href="#">
                                <button class="btn btn-rounded btn-info btn-sm" data-name="{{$c->name}}"
                                    data-email="{{$c->email}}" data-id="{{$c->id}}" data-mobile="{{$c->mobile}}"
                                    data-status="{{$c->status}}"  data-dob="{{$c->dob}}"
                                    type="button" data-toggle="modal" data-target="#edit">Edit</button>
                            </a>

                        </td>
                    @endcan
                        
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>

    </div>

</div>
</div>

@include('customers.create')
@include('customers.edit')

@endsection

@push("page_scripts")
@include('partials.notification')

<script>
    var title = document.title;
    document.title = title.concat(" | Customers");
</script>

<script>

$(document).ready(function(){

    $(function () {
        var start = moment();
        var end = moment();

        $('#dob').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                maxDate: end,
                autoUpdateInput: true,
                locale: {
                    format: 'DD-M-YYYY'
                }
            });
    });

    $(function () {
        var start = moment();
        var end = moment();

        $('#dob2').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                maxDate: end,
                autoUpdateInput: true,
                locale: {
                    format: 'DD-M-YYYY'
                }
            });
    });



      $('#edit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var modal = $(this)

        modal.find('.modal-body #name2').val(button.data('name'));
        modal.find('.modal-body #status2').val(button.data('status'));
        modal.find('.modal-body #email2').val(button.data('email'));
        modal.find('.modal-body #dob2').val(button.data('dob'));
        modal.find('.modal-body #mobile2').val(button.data('mobile'));
        modal.find('.modal-body #id').val(button.data('id'));

      });//end edit


    //delete user
    $('#delete').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget)
          var id = button.data('id')
          var user = button.data('name')
          var modal = $(this)
          var message =  "Are you sure you want to delete '".concat(user);

          modal.find('.modal-body #user').val(id)
          modal.find('.modal-body #message_del').text(message)

      })//end



  });
</script>

@endpush
