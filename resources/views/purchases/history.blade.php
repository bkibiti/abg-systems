@extends("layouts.master")

@section('page_css')
    <style>

    </style>
@endsection

@section('content-title')
    Purchase History
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Purchase / Purchase History </a></li>
@endsection

@section("content")


    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">

                <form id="expense_form" action="{{route('goods-receiving.search')}}" method="GET">
                    @csrf()

                    <div class="form-group row">
                        <div class="col-md-3">
                            <select class="form-control select2"  class="form-control" name="supplier" data-width="100%">
                                    <option value="0" {{ (old('supplier')==0 ? "selected":"") }}>All Suppliers</option>

                                    @foreach($suppliers as $supp)
                                    <option value="{{ $supp->id }}" {{ (old('supplier')==$supp->id ? "selected":"") }}>{{ $supp->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control select2"  class="form-control" name="product" data-width="100%">
                                    <option value="1" {{ (old('product')==0 ? "selected":"") }}>All Products</option>

                                    @foreach($products as $p)
                                    <option value="{{ $p->id }}" {{ (old('product')==$p->id ? "selected":"") }}>{{ $p->name }}</option>
                                    @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <div id="date" style="border: 2px solid white; border-radius: 6px;">
                                <input type="text" name="from_date" class="form-control" id="from_date" value="{{ old('from_date') }}" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div id="date" style="border: 2px solid white; border-radius: 6px;">
                                <input type="text" name="to_date" class="form-control" id="to_date" value="{{ old('to_date') }}" required>
                            </div>
                        </div>
                       
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success">Filter</button>
                        </div>
                    </div>

                </form>
                <hr>

                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">
                        <div class="col-md-9">
                        </div>
                        <div class="col-md-3">
                          
                        </div>
                    </div>


                    <div id="product-table" class="table-responsive">
                        <table id="fixed-header1" class="display table nowrap table-striped table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>Purchase Date</th>
                                <th>Item</th>
                                <th>Purchase Qty</th>
                                <th>Purchase Unit</th>
                                <th>Qty per Unit</th>
                                <th>Unit Cost</th>
                                <th>Total Cost</th>
                                <th>Supplier</th>
                                <th>User</th>
                                @can('Edit Purchase')
                                <th>Action</th>
                                @endcan
                            </tr>
                            </thead>
                                <tbody>
                                    @foreach($purchases as $p)
                                    <tr>
                                            <td>{{date_format($p->created_at,'d M Y')}}</td>
                                            <td>{{$p->product->name}}</td>
                                            <td>{{number_format($p->quantity,0) }}</td>
                                            <td>{{$p->purchase_uom}}</td>
                                            <td>{{$p->quantity_per_unit}}</td>
                                            <td>{{number_format($p->unit_cost,2)}}</td>
                                            <td>{{number_format(($p->unit_cost * $p->quantity), 2)}}</td>
                                            <td>{{$p->supplier->name}}</td>
                                            <td>{{$p->user->name}}</td>

                                            @can('Edit Purchase')
                                            <td>
                                                <a href="#">
                                                    <button class="btn btn-sm btn-rounded btn-info"
                                                            data-id="{{$p->id}}"
                                                            data-name="{{$p->product->name}}"
                                                            data-quantity="{{$p->quantity}}"
                                                            data-created_at="{{$p->created_at}}"
                                                            data-unit_cost="{{$p->unit_cost}}"
                                                            data-supplier_id="{{$p->supplier_id}}"
                                                            type="button"
                                                            data-toggle="modal" data-target="#edit">Edit
                                                    </button>
                                                </a>
                                          
                                            </td>
                                            @endcan
                                    </tr>

                                    
                                    @endforeach
                                    
                                </tbody>
                        </table>
                        
                    </div>
                 <hr>
                    <div class="row">
             
                            <div class="col-md-12">
                                <h4>Total Purchases (Tshs): {{number_format($total,2)}}</h4>
                            </div>
                    </div>

                </div>
            </div>
        </div>

        @endsection

@push("page_scripts")
@include('partials.notification')
@include('purchases.edit_purchase_modal')

<script>
    var title = document.title;
    document.title = title.concat(" | Purchase History");
</script>

<script>

    $('#fixed-header1').DataTable({
        bAutoWidth: true,
        order: [[0, "desc"]]
    });

    $(function () {
        var start = moment();
        var end = moment();

        $('#from_date').daterangepicker({
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

        $('#to_date').daterangepicker({
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

        $('#created_at').daterangepicker({
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
        var button = $(event.relatedTarget);
        var modal = $(this);

        modal.find('.modal-body #id').val(button.data('id'));
        modal.find('.modal-body #created_at').val(button.data('created_at'));
        modal.find('.modal-body #name').val(button.data('name'));
        modal.find('.modal-body #unit_cost').val(button.data('unit_cost'))
        modal.find('.modal-body #quantity').val(button.data('quantity'))
        modal.find('.modal-body #supplier_id').val(button.data('supplier_id'))
    });



</script>

    @endpush
