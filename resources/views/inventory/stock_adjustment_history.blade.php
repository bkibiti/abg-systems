@extends("layouts.master")

@section('page_css')
    <style>


    </style>
@endsection

@section('content-title')
Stock Adjustment History
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#"> Inventory / Stock Adjustment History</a></li>
@endsection

@section("content")


    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form id="expense_form" action="{{route('adjustment.search')}}" method="post">
                    @csrf()
    
                    <div class="form-group row">
    
                        <div class="col-md-2">
                            <div style="border: 2px solid white; border-radius: 6px;">
                                <input type="text" name="from_date" class="form-control" id="from_date" value="{{ old('from_date') }}" required>
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div style="border: 2px solid white; border-radius: 6px;">
                                <input type="text" name="to_date" class="form-control" id="to_date" value="{{ old('to_date') }}" required>
                            </div>
                        </div>
               
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success">Filter</button>
                        </div>
                        <div class="col-md-4">
                        </div>
    
                        <div class="col-md-2">
                           
                        </div>
                    </div>
    
                </form>
        <hr>

                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    

                    <div id="product-table" class="table-responsive">
                        <table id="fixed-header1" class="display table nowrap table-striped table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Original Qty</th>
                                <th>Adjusted Qty</th>
                                <th>Remained Qty</th>
                                <th>Adjustment Reason</th>
                                <th>Adjusted By</th>
                            </tr>
                            </thead>
                                <tbody>
                                    @foreach($adjustments as $adjust)
                                    <tr>
                                            <td>{{date_format(new DateTime($adjust->created_at),'d M Y')}}</td>
                                            <td>{{$adjust->product->name}}</td>
                                            <td>{{$adjust->original_qty}}</td>
                                            <td>{{$adjust->adjusted_qty}}</td>
                                            <td>{{$adjust->remain_qty}}</td>
                                            <td>{{$adjust->reason}}</td>
                                            <td>{{$adjust->user->name}}</td>

                                                
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

            @include('partials.notification')


    <script>
        var title = document.title;
        document.title = title.concat(" | Inventory | Stock Adjustment");
    </script>


    <script>

                $('#fixed-header1').DataTable({
                    bAutoWidth: true,
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



            </script>

    @endpush
