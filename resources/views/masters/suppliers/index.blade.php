@extends("layouts.master")

@section('page_css')
@endsection

@section('content-title')
   Suppliers
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Masters / Suppliers</a> </li>
@endsection

@section("content")

<div class="col-sm-12">
    <div class="card-block">
        <div class="col-sm-12">
                <div class="card" >
                    <div class="card-body" id="home" role="tabpanel" aria-labelledby="home-tab">
                      
                        <button style="float: right; margin-bottom: 1%;" class="btn btn-icon btn-rounded btn-warning btn-sm" data-toggle="modal" 
                                title="Add Supplier" data-target="#create"><i class="feather icon-plus"></i>
                        </button>

                        <div class="table-responsive">
                            <table id="fixed-header" class="display table nowrap table-striped table-hover"style="width:100%">
                            <thead>
                                <tr>
                                   <th>Name</th>
                                   <th>Contact Person</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                   <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                                 <tbody>
                                    @foreach($suppliers as $supplier)
                                        <tr>
                                            <td>{{$supplier->name}}</td>
                                            <td>{{$supplier->contact_person}}</td>
                                            <td>{{$supplier->mobile}}</td>
                                            <td>{{$supplier->email}}</td>
                                            <td>{{$supplier->address}}</td>
                                            <td>
                                             <a href="#">
                                                 <button class="btn btn-sm btn-rounded btn-info"
                                                         data-id="{{$supplier->id}}"
                                                         data-name="{{$supplier->name}}"
                                                         data-contact_person="{{$supplier->contact_person}}"
                                                         data-address="{{$supplier->address}}"
                                                         data-phone="{{$supplier->mobile}}"
                                                         data-email="{{$supplier->email}}"
                                                         type="button"
                                                         data-toggle="modal" data-target="#edit">Edit
                                             </button>
                                             </a>
                                                <a href="#">
                                            <button class="btn btn-sm btn-rounded btn-danger"
                                            data-id="{{$supplier->id}}"
                                            data-name="{{$supplier->name}}"
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

    @include('masters.suppliers.create')
    @include('masters.suppliers.delete')
    @include('masters.suppliers.edit')

@endsection


@push("page_scripts")
@include('partials.notification')
<script>
    var title = document.title;
    document.title = title.concat(" | Suppliers");
</script>
 <script>

              $('#edit').on('show.bs.modal', function (event) {
                  var button = $(event.relatedTarget);
                  var modal = $(this);

                  modal.find('.modal-body #id').val(button.data('id'));
                  modal.find('.modal-body #name_edit').val(button.data('name'));
                  modal.find('.modal-body #address_edit').val(button.data('address'));
                  modal.find('.modal-body #contact_edit').val(button.data('contact_person'));
                  modal.find('.modal-body #phone_edit').val(button.data('phone'));
                modal.find('.modal-body #email_edit').val(button.data('email'))

            });

            $('#delete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var message = "Are you sure you want to delete '".concat(button.data('name'), "'?");
                var modal = $(this);
                modal.find('.modal-body #message').text(message);
                modal.find('.modal-body #id').val(button.data('id'));

            })
        </script>

          <!-- Input mask Js -->
    <script src="{{asset("assets/plugins/inputmask/js/inputmask.min.js")}}"></script>
    <script src="{{asset("assets/plugins/inputmask/js/jquery.inputmask.min.js")}}"></script>
    <script src="{{asset("assets/plugins/inputmask/js/autoNumeric.js")}}"></script>

    <!-- select2 Js -->
    <script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>
    <!-- form-select-custom Js -->
    <script src="{{asset("assets/js/pages/form-select-custom.js")}}"></script>


    <!-- form-picker-custom Js -->
    <script src="{{asset("assets/js/pages/form-masking-custom.js")}}"></script>

    @endpush
