@extends("layouts.master")

@section('page_css')
<style>
    #daily_sales {
        width: 100%;
        height: 500px;
    }

    #staff_loss {
        width: 100%;
        height: 400px;
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
                                    <h5 class="text-c-red f-w-400">{{ number_format(outofstock()/stockItems()*100,1) }}%</h5>
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
                                    <h5 class="text-c-green f-w-400">{{ number_format(belowMin()/stockitems()*100,1) }}%</h5>
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
                <div class="col-md-6 col-xl-4">
                    <div id='staff_loss'></div>
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

<!-- Losses by user -->
<script>
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);

        // Create chart instance
        var chart = am4core.create("staff_loss", am4charts.XYChart);

        // Add data
        chart.data = @json($staffLoss);

        //title
        var title = chart.titles.create();
            title.text = "Losses by Staff this Month";
            title.fontSize = 16;
            title.marginBottom = 15;
        // Create axes
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "user";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 30;
        categoryAxis.renderer.labels.template.horizontalCenter = "right";
        categoryAxis.renderer.labels.template.verticalCenter = "middle";
        categoryAxis.renderer.labels.template.rotation = 270;
        categoryAxis.tooltip.disabled = true;
        categoryAxis.renderer.minHeight = 110;
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.minWidth = 50;

        // Create series
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.sequencedInterpolation = true;
        series.dataFields.valueY = "amount";
        series.dataFields.categoryX = "user";
        series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
        series.columns.template.strokeWidth = 0;

        series.tooltip.pointerOrientation = "vertical";

        series.columns.template.column.cornerRadiusTopLeft = 10;
        series.columns.template.column.cornerRadiusTopRight = 10;
        series.columns.template.column.fillOpacity = 0.8;

        // on hover, make corner radiuses bigger
        var hoverState = series.columns.template.column.states.create("hover");
        hoverState.properties.cornerRadiusTopLeft = 0;
        hoverState.properties.cornerRadiusTopRight = 0;
        hoverState.properties.fillOpacity = 1;

        series.columns.template.adapter.add("fill", function(fill, target) {
        return chart.colors.getIndex(target.dataItem.index);
        });

        // Cursor
        chart.cursor = new am4charts.XYCursor();




        }); 
</script>



@endpush