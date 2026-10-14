@extends("layouts.master")

@section('page_css')
<style>

</style>

@endsection

@section('content-title')
Sales History
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Sales / Sales History </a></li>
@endsection

@section("content")


<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                <form id="expense_form" action="{{route('sales.history-search')}}" method="post">
                    @csrf()

                    <div class="form-group row">
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
                <div id="product-table" class="table-responsive">
                    <table id="fixed-header1" class="display table nowrap table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Receipt #</th>
                                <th>Date</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Unit Cost</th>
                                <th>Total Cost</th>
                                <th>User</th>
                                @can('Edit Sales')
                                <th>Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $s)
                            <tr>
                                <td>{{$s->receipt_number}}</td>
                                <td>{{date_format($s->created_at,'d M Y')}}</td>
                                <td>{{$s->product->name}}</td>
                                <td>{{number_format($s->quantity,0)}}</td>
                                <td>{{number_format($s->selling_price,2)}}</td>
                                <td>{{number_format(($s->selling_price * $s->quantity), 2)}}</td>
                                <td>{{$s->user->name}}</td>
                                @can('Edit Sales')
                                <td>
                                    <a href="#">
                                        <button class="btn btn-sm btn-rounded btn-info"
                                                data-id="{{$s->id}}"
                                                data-name="{{$s->product->name}}"
                                                data-created_at="{{$s->created_at}}"
                                                data-quantity="{{$s->quantity}}"
                                                data-selling_price="{{$s->selling_price}}"
                                                data-buying_price="{{$s->buying_price}}"
                                                type="button"
                                                data-toggle="modal" data-target="#edit">Edit
                                        </button>
                                    </a>
                                    @if ($s->remarks <> '')
                                        <button class="btn btn-sm btn-rounded btn-success"
                                                data-updated_by="{{'Updated by: '. $s->updater->name . '  at: '. $s->updated_at }}"
                                                data-updated_at="{{$s->updated_at}}"
                                                data-remarks="{{$s->remarks}}"
                                                type="button"
                                                data-toggle="modal" data-target="#view">View
                                        </button>
                                    @endif
                              
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
                        <h4>Total Sales (Tshs): {{number_format($total,2)}}</h4>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @endsection

    @push("page_scripts")

    @include('sales.edit_sale_modal')
    @include('sales.view_sale_modal')
    @include('partials.notification')

    <script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
    <script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>

    <script>
        var title = document.title;
        document.title = title.concat(" | Sales | History");
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

        $('#saledate').daterangepicker({
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
        modal.find('.modal-body #saledate').val(button.data('created_at'));
        modal.find('.modal-body #name').val(button.data('name'));
        modal.find('.modal-body #selling_price').val(button.data('selling_price'))
        modal.find('.modal-body #quantity').val(button.data('quantity'))
        modal.find('.modal-body #buying_price').val(button.data('buying_price'))
    });

    $('#view').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        modal.find('.modal-body #remarks').text(button.data('remarks'));
        modal.find('.modal-body #by').text(button.data('updated_by'));
        modal.find('.modal-body #at').text(button.data('updated_at'));


    });




    </script>
    @endpush