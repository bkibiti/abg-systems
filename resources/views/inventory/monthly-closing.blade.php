@extends("layouts.master")

@section('page_css')
    <style>


    </style>
@endsection

@section('content-title')
    Month End Closing Stock
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Inventory /Month End Closing Stock </a></li>
@endsection

@section("content")


    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form id="expense_form" action="{{route('monthly-closing-stock.filter')}}" method="get">
                    @csrf()

                    <div class="form-group row">

                        <div class="col-md-2">
                            <select class="form-control select2"  class="form-control" name="month"  data-placeholder="Select Month" required data-width="100%">
                                <option value="1" {{ (old('month')==1 ? "selected":"") }} >Jan</option>
                                <option value="2" {{ (old('month')==2 ? "selected":"") }} >Feb</option>
                                <option value="3" {{ (old('month')==3 ? "selected":"") }} >Mar</option>
                                <option value="4" {{ (old('month')==4 ? "selected":"") }} >Apr</option>
                                <option value="5" {{ (old('month')==5 ? "selected":"") }} >May</option>
                                <option value="6" {{ (old('month')==6 ? "selected":"") }} >Jun</option>
                                <option value="7" {{ (old('month')==7 ? "selected":"") }} >Jul</option>
                                <option value="8" {{ (old('month')==8 ? "selected":"") }} >Aug</option>
                                <option value="9" {{ (old('month')==9 ? "selected":"") }} >Sep</option>
                                <option value="10" {{ (old('month')==10 ? "selected":"") }}>Oct</option>
                                <option value="11" {{ (old('month')==11 ? "selected":"") }}>Nov</option>
                                <option value="12" {{ (old('month')==12 ? "selected":"") }}>Dec</option>


                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control select2"  class="form-control" name="year"  data-placeholder="Select Year" required data-width="100%">
                                <option value="2020" {{ (old('year')==2020 ? "selected":"") }} >2020</option>
                                <option value="2021" {{ (old('year')==2021 ? "selected":"") }} >2021</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success">Filter</button>
                        </div>
                        <div class="col-md-4">
                        </div>

                        <div class="col-md-2">
                            <button type="button" style="float: right; margin-bottom: 1%;" class="btn btn-icon btn-rounded btn-warning btn-sm" data-toggle="modal" 
                                    title="Add Monthly Closing Stock" data-target="#create"><i class="feather icon-plus"></i>
                            </button>
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
                                <th>Month</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Value Purchase</th>
                                <th>Value Selling</th>
                            </tr>
                            </thead>
                                <tbody>
                                    @foreach($products as $prod)
                                    <tr>
                                            <td>
                                                @php
                                                    $monthName = date("M", mktime(0, 0, 0, $prod->month, 10));
                                                    $month = $monthName . ' - '. $prod->year;
                                                    echo $month;
                                                @endphp
                                            </td>
                                            <td>{{$prod->product->name}}</td>
                                            <td>{{$prod->qty}}</td>
                                            <td>{{number_format($prod->value_purchase,2) }}</td>
                                            <td>{{number_format($prod->value_selling,2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><h6>Total </h6> </td>
                                        <td></td>
                                        <td></td>
                                        <td><h6>{{number_format($value_purchase,2)}}</h6></td>
                                        <td><h6>{{number_format($value_selling,2)}}</h6></td>

                                    </tr>
                                </tfoot>
                        </table>
                    </div>


                </div>
            </div>
        </div>

        @endsection

        @push("page_scripts")

            @include('partials.notification')
            @include('inventory.monthly-closing-modal')

            <script>
                var title = document.title;
                document.title = title.concat(" | Inventory | Monthly Closing Stock");
            </script>


            <script>

                $('#fixed-header1').DataTable({
                    bAutoWidth: true,
                });


            </script>

    @endpush
