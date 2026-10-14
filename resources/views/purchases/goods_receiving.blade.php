@extends("layouts.master")

@section('content-title')

    Purchase

@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="bi bi-house"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Purchase Management / Purchase</a></li>
@endsection
@section("content")

    <div class="col-sm-5">
            <div class="card">
            <div class="card-header">
                <h3 class="card-title">Products</h3>
            </div>
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
                                                    <button class="btn btn-sm btn-info selectproduct"
                                                            data-id="{{$prod->id}}"
                                                            data-name="{{$prod->name}}"
                                                            data-bs-toggle="button">Select
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
            <div class="card-header">
                <h3 class="card-title">Receive Goods</h3>
            </div>
                <div class="card-body">
                        <div>
                                <form method="POST" action="{{ route('goods-receiving.store') }}" >
                                    @csrf
                                    <input type="hidden" name="id" id="id">


                                    <div class="row mb-3">
                                        <label for="product_name" class="col-md-4 col-form-label text-md-end">Date<font color="red">*</font></label>
                                        <div class="col-md-8">
                                            <div id="date" style="border: 2px solid white; border-radius: 6px;">
                                                <input type="text" name="purchase_date" class="form-control" id="purchase_date" value="{{ old('purchase_date') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="category" class="col-md-4 col-form-label text-md-end">Supplier<font color="red">*</font></label>

                                        <div class="col-md-8">
                                            <select class="form-select select2"  class="form-select" name="supplier" required data-width="100%">
                                                    <option value="">Select Supplier</option>

                                                    @foreach($suppliers as $supp)
                                                    <option value="{{ $supp->id }}" {{ (old('supplier')==$supp->id ? "selected":"") }}>{{ $supp->name }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                </div>
                                    <div class="row mb-3">
                                        <label for="product_name" class="col-md-4 col-form-label text-md-end">Product</label>

                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="name" name="name" maxlength="50" minlength="2"
                                                placeholder="" required readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                            <label for="category" class="col-md-4 col-form-label text-md-end" >Quantity<font color="red">*</font></label>
                                            <div class="col-md-8">
                                                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                                            </div>
                                    </div>
                                    <div class="row mb-3">
                                            <label for="category" class="col-md-4 col-form-label text-md-end">Total Price<font color="red">*</font></label>
                                            <div class="col-md-8">
                                                    <input  class="form-control" id="total_cost" name="total_cost" min="1" required placeholder="Total Purchase Price">
                                            </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="category" class="col-md-4 col-form-label text-md-end">Unit Price<font color="red">*</font></label>
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
            <div class="card-header">
                <h3 class="card-title">Product Purchase History</h3>
            </div>
            <div class="card-body">
                    <div>

                        <div  class="table-responsive">
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
