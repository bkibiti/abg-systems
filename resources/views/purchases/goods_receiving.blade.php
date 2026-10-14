@extends("layouts.master")

@section('content-title')

    Purchase

@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Purchase Management / Purchase</a></li>
@endsection
@section("content")

    <div class="col-sm-5">
            <div class="card">
                    <div class="card-body">
                        <div id="product-table" class="table-responsive">
                            <table id="products" class="display table nowrap table-striped table-hover" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>QOH</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                    <tbody>
                                        @foreach($products as $prod)
                                        <tr>
                                            <td>{{$prod->name}}</td>
                                            <td>{{$prod->quantity}}</td>
                                            <td>
                                                <a href="#">
                                                    <button class="btn btn-sm btn-rounded btn-info selectproduct"
                                                            data-id="{{$prod->id}}"
                                                            data-name="{{$prod->name}}"
                                                            data-category_id="{{$prod->category->id}}"
                                                            data-purchase_uom="{{$prod->purchase_uom}}"
                                                            data-quantity_per_unit="{{$prod->quantity_per_unit}}"
                                                            data-sold_by="{{$prod->sold_by}}"
                                                            data-toggle="button">Select
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
    <div class="col-sm-4">
          <div class="card">
                <div class="card-body">
                        <div class="panel-body">
                                <form method="POST" action="{{ route('goods-receiving.store') }}" >
                                    @csrf
                                    <input type="hidden" name="id" id="id">
                                    <input type="hidden" name="purchaseUOM" id="purchaseUOM">


                                    <div class="form-group row">
                                        <label for="product_name" class="col-md-4 col-form-label text-md-right">Date<font color="red">*</font></label>
                                        <div class="col-md-8">
                                            <div id="date" style="border: 2px solid white; border-radius: 6px;">
                                                <input type="text" name="purchase_date" class="form-control" id="purchase_date" value="{{ old('purchase_date') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="category" class="col-md-4 col-form-label text-md-right">Supplier<font color="red">*</font></label>

                                        <div class="col-md-8">
                                            <select class="form-control select2"  class="form-control" name="supplier" required data-width="100%">
                                                    <option value="">Select Supplier</option>

                                                    @foreach($suppliers as $supp)
                                                    <option value="{{ $supp->id }}" {{ (old('supplier')==$supp->id ? "selected":"") }}>{{ $supp->name }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                </div>
                                    <div class="form-group row">
                                        <label for="product_name" class="col-md-4 col-form-label text-md-right">Product</label>

                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="name" name="name" maxlength="50" minlength="2"
                                                placeholder="" required readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                            <label for="category" class="col-md-4 col-form-label text-md-right">Purchase Unit</label>
                                            <div class="col-md-8">
                                                    <input type="text" class="form-control" id="purchase_uom" readonly>
                                            </div>
                                    </div>

                                    <input type="hidden" class="form-control" id="quantity_per_unit" name="quantity_per_unit" >


                                    <div class="form-group row">
                                            <label for="category" class="col-md-4 col-form-label text-md-right" id="numberofunits"># of Units<font color="red">*</font></label>
                                            <div class="col-md-8">
                                                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                            <label for="category" class="col-md-4 col-form-label text-md-right">Total Price<font color="red">*</font></label>
                                            <div class="col-md-8">
                                                    <input  class="form-control" id="total_cost" name="total_cost" min="1" required placeholder="Total Purchase Price">
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="category" class="col-md-4 col-form-label text-md-right">Unit Price<font color="red">*</font></label>
                                        <div class="col-md-8">
                                                <input  class="form-control" id="unit_cost" name="unit_cost" min="1" required readonly>
                                        </div>
                                </div>


                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>



                        </div>
                </div>

          </div>
    </div>

    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                    <div class="panel-body">

                        <div  class="table-responsive">
                            <h6>Product Purchase History</h6>
                            <table id="purchase_history" class="display table nowrap table-striped table-hover" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Unit Price</th>
                                    <th>Supplier</th>
                                </tr>
                                </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                            </table>
                        </div>

                    </div>
            </div>

      </div>
    </div>

@endsection
@push("page_scripts")
    @include('partials.notification')
    <script>
        var title = document.title;
        document.title = title.concat(" | Purchases");
    </script>

    <script>
        $('#products').DataTable({
            bAutoWidth: true,
            lengthChange: false,
            scrollY:  "400px",
            scrollCollapse: true,
            paging: false,
            bInfo: false,

        });
        $(function () {
        var start = moment();
        var end = moment();

        $('#purchase_date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                maxDate: end,
                autoUpdateInput: true,
                locale: {
                    format: 'DD-M-YYYY'
                }
            });
        });

        var lastPurchasePrice = 0;

        $('.selectproduct').click(function() {
            $('#id').val($(this).data('id'));
            $('#name').val($(this).data('name'))
            $('#purchaseUOM').val($(this).data('purchase_uom'))

            var purchaseUnit = '';
            var pu = purchaseUnit.concat($(this).data('purchase_uom'),' (',$(this).data('quantity_per_unit'),' ',$(this).data('sold_by'),')');
            $('#purchase_uom').val(pu);
            var units='';
            $('#numberofunits').text(units.concat('# of ',$(this).data('purchase_uom'),'(s)'));
            $('#quantity_per_unit').val($(this).data('quantity_per_unit'));


            $('#purchase_history').find('tbody').detach();
            $('#purchase_history').append($('<tbody>'));
            var _token = $('input[name="_token"]').val();
            var prod_id = $(this).data('id');
            $.ajax({
                    url:"{{route('goods-receiving.item-history')}}",
                    method:"POST",
                    data:{prod_id:prod_id,_token:_token},
                    success:function(result)
                    {

                        for(i=0;i<result.length;i++){
                            $("#purchase_history > tbody:first").append("<tr><td>"+ $.date(result[i].created_at) + "</td><td>"+result[i].unit_cost+"</td><td>"+result[i].supplier.name+"</td></tr>");
                            if (i == 0){
                                lastPurchasePrice = result[i].unit_cost;
                            }
                        }
                    }
            });

        });

        $('#total_cost').change(function () {
            // alert($(this).val());
            // alert( $('#quantity_per_unit').val());
            var unitcost = $(this).val()/$('#quantity').val();
            $('#unit_cost').val(unitcost);

        });

        $('#quantity').change(function () {
            var totalC = $(this).val() * lastPurchasePrice;
            $('#unit_cost').val(lastPurchasePrice);
            $('#total_cost').val(totalC);
        });



        $.date = function(dateObject) {
            var d = new Date(dateObject);
            var day = d.getDate();
            var month = d.getMonth() + 1;
            var year = d.getFullYear();
            if (day < 10) {
                day = "0" + day;
            }
            if (month < 10) {
                month = "0" + month;
            }
            var date = day + "/" + month + "/" + year;

            return date;
        };

    </script>

@endpush
