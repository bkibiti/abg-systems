@extends("layouts.master")

@section('page_css')
<style>
    #daily_sales {
        width: 100%;
        height: 500px;
    }

 
</style>
@endsection





@section("content")

<div class="col-sm-12">

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="pill" href="#pills-home" role="tab"
                aria-selected="true">Dashboard</a>
        </li>

    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="row">
                <div class="col-md-6 col-xl-4">
                    <div class="card theme-bg">
                        <div class="card-block">
                            <h5 class="text-white mb-2">Last Purchase: {{date_format(date_create($lastPurchase[0]->date),"d M Y")}} </h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card theme-bg2">
                        <div class="card-block">
                            <h5 class="text-white mb-2">Last Sale: {{date_format(date_create($lastSale[0]->date),"d M Y")}}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xl-4">
                    <div class="card bg-c-blue">
                        <div class="card-block">
                            <h5 class="text-white mb-2">Last Expense: {{date_format(date_create($lastExpense[0]->date),"d M Y")}}</h5>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-12 col-xl-4">
                    <div class="card">
                        <div class="card-block border-bottom">
                            <h5 class="m-0">Out of Stock</h5>
                        </div>
                        <div class="card-block">
                            <div class="row  align-items-center">
                                <div class="col-8">
                                    <h2 class=" m-0">{{ outofstock()}}</h2>
                                    <span class="text-muted">Items</span>
                                </div>
                                <div class="col-4 text-right">
                                    <h5 class="text-c-red f-w-400">{{ number_format(stockItems() > 0 ? outofstock() / stockItems() * 100 : 0, 1) }}%</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-block border-bottom">
                            <h5 class="m-0">Below Minimum</h5>
                        </div>
                        <div class="card-block">
                            <div class="row  align-items-center">
                                <div class="col-8">
                                    <h2 class=" m-0">{{ belowMin()}}</h2>
                                    <span class="text-muted">Items</span>
                                </div>
                                <div class="col-4 text-right">
                                    <h5 class="text-c-green f-w-400">{{ number_format(stockItems() > 0 ? belowMin() / stockItems() * 100 : 0, 1) }}%</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-block border-bottom">
                            <h5 class="m-0">Total Stock</h5>
                        </div>
                        <div class="card-block">
                            <div class="row  align-items-center">
                                <div class="col-8">
                                    <h2 class=" m-0">{{ stockItems() }}</h2>
                                    <span class="text-muted">Items</span>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- row 2 start --}}
            <div class="row">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                      
                        <div class="card-block">
                            <div class="table-responsive">
                                <table id="table1" class="display table nowrap table-striped table-hover"
                                       style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Item</th>
                                    </tr>
                                    </thead>
                                        <tbody>
                                            @foreach($StockOut as $prod)
                                            <tr>
                                                    <td>{{$prod->name}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                     
                        <div class="card-block">
                            <div class="table-responsive">
                                <table id="table2" class="display table nowrap table-striped table-hover"
                                       style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>QOH</th>
                                        <th>Mini Qty</th>
                                    </tr>
                                    </thead>
                                        <tbody>
                                            @foreach($Products as $prod)
                                            <tr>
                                                    <td>{{$prod->name}}</td>
                                                    <td>{{$prod->quantity}}</td>
                                                    <td>{{$prod->min_quantinty}}</td>
        
                                            </tr>
                                            @endforeach
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>


        </div>
        {{-- /Tab 1 --}}



    </div>
</div>


@endsection



@push("page_scripts")

<script src="{{asset("assets/plugins/amcharts4/core.js")}}"></script>
<script src="{{asset("assets/plugins/amcharts4/charts.js")}}"></script>
<script src="{{asset("assets/plugins/amcharts4/themes/animated.js")}}"></script>

<script>
    var title = document.title;
    document.title = title.concat(" | Home");

    $('#table1').DataTable({
        searching: false,
        scrollY:  "250px",
        bPaginate: false,
    });

    $('#table2').DataTable({
        searching: false,
        scrollY:  "250px",
        bPaginate: false,
    });


</script>



@endpush