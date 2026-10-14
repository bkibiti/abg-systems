@extends("layouts.master")

@section('page_css')
<style>
    #daily_sales {
        width: 100%;
        height: 500px;
    }

    #sale_by_day {
        width: 100%;
        height: 400px;
    }

    #sales_by_month {
        width: 100%;
        height: 400px;
    }

    #monthly-sales {
        width: 100%;
        height: 400px;
    }
</style>
@endsection





@section("content")

@can('View Advanced Dashboard')

<div class="col-sm-12">

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="pill" href="#pills-home" role="tab" aria-selected="true">Dashboard</a>
        </li>

    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <!-- start row 1-->
            <div class="row">
                <div class="col">
                    <div class="card statistial-visit">
                        <div class="card-header">
                            <h5 class="card-title">This Month</h5>
                        </div>
                        <div class="card-body">
                            <span class="d-block">Net Profit</span>
                            <h4 class="fw-light">TZS {{ number_format( ((double)$thisMonth['profit']- (double)$thisMonth['expense']), 2) }}  
                                @if (((double)$thisMonth['profit']- (double)$thisMonth['expense']) > 0)
                                    <i class="bi bi-emoji-smile text-success fs-5"></i>
                                @else
                                    <i class="bi bi-emoji-frown text-danger fs-5"></i>
                                @endif
                            </h4>
                           
                            <hr>
                            <div class="d-flex align-items-center mb-2">
                                <div class="photo-table">
                                    <h6> Purchases</h6>
                                </div>
                                <div class="flex-grow-1">
                                    <span
                                        class="badge text-bg-primary fs-6 fw-normal float-end">{{ number_format($thisMonth['purchase'],2) }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="photo-table">
                                    <h6> Sales</h6>
                                </div>
                                <div class="flex-grow-1">
                                    <label
                                        class="badge text-bg-info fs-6 fw-normal float-end">{{ number_format($thisMonth['sales'], 2) }}</label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="photo-table">
                                    <h6> Expenses</h6>
                                </div>
                                <div class="flex-grow-1">
                                    <label
                                        class="badge text-bg-danger fs-6 fw-normal float-end">{{ number_format($thisMonth['expense'], 2) }}</label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="photo-table">
                                    <h6> Gross Profit</h6>
                                </div>
                                <div class="flex-grow-1">
                                    <label
                                        class="badge text-bg-success fs-6 fw-normal float-end">{{ number_format($thisMonth['profit'], 2) }}</label>
                                </div>
                            </div>
                     
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card statistial-visit">
                        <div class="card-header">
                            <h5 class="card-title">Last Month</h5>
                        </div>
                        <div class="card-body">
                            <span class="d-block">Net Profit</span>
                            <h4 class="fw-light">TZS {{ number_format( ((double)$lastMonth['profit']- (double)$lastMonth['expense']), 2) }}
                                @if (((double)$lastMonth['profit']- (double)$lastMonth['expense']) > 0)
                                    <i class="bi bi-emoji-smile text-success fs-5"></i>
                                @else
                                    <i class="bi bi-emoji-frown text-danger fs-5"></i>
                                @endif
                            </h4>
                            <hr>

                            <div class="d-flex align-items-center mb-2">
                                <div class="photo-table">
                                    <h6> Purchases</h6>
                                </div>
                                <div class="flex-grow-1">
                                    <span
                                        class="badge text-bg-primary fs-6 fw-normal float-end">{{ number_format($lastMonth['purchase'],2) }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="photo-table">
                                    <h6> Sales</h6>
                                </div>
                                <div class="flex-grow-1">
                                    <label
                                        class="badge text-bg-info fs-6 fw-normal float-end">{{ number_format($lastMonth['sales'], 2) }}</label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="photo-table">
                                    <h6> Expenses</h6>
                                </div>
                                <div class="flex-grow-1">
                                    <label
                                        class="badge text-bg-danger fs-6 fw-normal float-end">{{ number_format($lastMonth['expense'], 2) }}</label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="photo-table">
                                    <h6> Gross Profit</h6>
                                </div>
                                <div class="flex-grow-1">
                                    <label
                                        class="badge text-bg-success fs-6 fw-normal float-end">{{ number_format($lastMonth['profit'], 2) }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card statistial-visit">
                        <div class="card-header">
                            <h5 class="card-title">Daily Averages</h5>
                        </div>
                        <div class="card-body">
                            <span class="d-block">Net Profit</span>
                            <h4 class="fw-light">TZS {{ number_format( ((double)$dailyAverage['profit']- (double)$dailyAverage['expense']), 2) }}
                                @if (((double)$dailyAverage['profit']- (double)$dailyAverage['expense']) > 0)
                                    <i class="bi bi-emoji-smile text-success fs-5"></i>
                                @else
                                    <i class="bi bi-emoji-frown text-danger fs-5"></i>
                                @endif
                            </h4>
                            <hr>

                            <div class="d-flex align-items-center mb-2">
                                <div class="photo-table">
                                    <h6> Purchases</h6>
                                </div>
                                <div class="flex-grow-1">
                                    <span
                                        class="badge text-bg-primary fs-6 fw-normal float-end">{{ number_format($dailyAverage['purchase'],2) }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="photo-table">
                                    <h6> Sales</h6>
                                </div>
                                <div class="flex-grow-1">
                                    <label
                                        class="badge text-bg-info fs-6 fw-normal float-end">{{ number_format($dailyAverage['sales'], 2) }}</label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="photo-table">
                                    <h6> Expenses</h6>
                                </div>
                                <div class="flex-grow-1">
                                    <label
                                        class="badge text-bg-danger fs-6 fw-normal float-end">{{ number_format($dailyAverage['expense'], 2) }}</label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="photo-table">
                                    <h6> Gross Profit</h6>
                                </div>
                                <div class="flex-grow-1">
                                    <label
                                        class="badge text-bg-success fs-6 fw-normal float-end">{{ number_format($dailyAverage['profit'], 2) }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

            </div>
            <!-- end row 1 -->

            <!-- start row 2-->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Sales and Expenses </h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-maximize">
                                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="monthly-sales"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                <h3 class="card-title">Stock Value</h3>
            </div>
                        <div class="card-body" style="height: 520px;">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-auto">
                                    <h3 class="fw-light mt-3">TZS {{ number_format($stockValue,2) }}

                                </div>
                                <div class="col text-end">
                                    <i class="bi bi-wallet2 fs-2 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- end row 12 -->

            {{-- row 3 start --}}
            <div class="row">
                <div class="col-md-6 col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div id='sale_by_day'></div>
                        </div>
                    </div>

                </div>

                <div class="col-md-6 col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div id='sales_by_month'></div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- row 4 start --}}
            <div class="row">
                <div class="card col-md-12">
                    <div class="card-header">
                        <h5 class="card-title">Daily Sales</h5>
                        <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-maximize">
                                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit"></i>
                                </button>
                            </div>
                    </div>
                    <div class="card-body">
                        <div id='daily_sales'></div>
                    </div>
                </div>

            </div>

        </div>
        {{-- /Tab 1 --}}



    </div>
</div>




@else
<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <h4>Welcome, {{ Auth::user()->name }}</h4>
            <p class="text-muted mb-0">Use the menu on the left to get started.</p>
        </div>
    </div>
</div>
@endcan

@endsection



@push("page_scripts")

@can('View Advanced Dashboard')

<script src="{{asset("assets/plugins/amcharts4/core.js")}}"></script>
<script src="{{asset("assets/plugins/amcharts4/charts.js")}}"></script>
<script src="{{asset("assets/plugins/amcharts4/themes/animated.js")}}"></script>
<script src="{{asset("assets/plugins/amcharts4/themes/dataviz.js")}}"></script>

<script>
    var title = document.title;
    document.title = title.concat(" | Dashboard");
</script>

{{-- daily sales chart  --}}
<script>
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("daily_sales", am4charts.XYChart);

    // Add data
    chart.data = @json($totalDailySales);

    // Set input format for the dates
    chart.dateFormatter.inputDateFormat = "dd-MM-yyyy";

    // Create axes
    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    //title
    var title = chart.titles.create();
    // title.text = "Daily Sales";
    title.fontSize = 16;
    title.marginBottom = 15;

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = "value";
    series.dataFields.dateX = "date";
    series.tooltipText = "{value}"
    series.strokeWidth = 2;
    series.minBulletDistance = 5;
    series.fillOpacity = 0.8;

    // Drop-shaped tooltips
    series.tooltip.background.cornerRadius = 20;
    series.tooltip.background.strokeOpacity = 0;
    series.tooltip.pointerOrientation = "vertical";
    series.tooltip.label.minWidth = 40;
    series.tooltip.label.minHeight = 40;
    series.tooltip.label.textAlign = "middle";
    series.tooltip.label.textValign = "middle";

    // Make bullets grow on hover
    var bullet = series.bullets.push(new am4charts.CircleBullet());
    bullet.circle.strokeWidth = 2;
    bullet.circle.radius = 4;
    bullet.circle.fill = am4core.color("#fff");

    var bullethover = bullet.states.create("hover");
    bullethover.properties.scale = 1.3;

    // Make a panning cursor
    chart.cursor = new am4charts.XYCursor();
    chart.cursor.behavior = "panXY";
    chart.cursor.xAxis = dateAxis;
    chart.cursor.snapToSeries = series;

    // Create vertical scrollbar and place it before the value axis
    chart.scrollbarY = new am4core.Scrollbar();
    chart.scrollbarY.parent = chart.leftAxesContainer;
    chart.scrollbarY.toBack();

    chart.events.on("ready", function () {
        dateAxis.zoom({start:0.1, end:1});
    });

</script>


<!-- Sales by Day of a week -->
<script>
    am4core.ready(function() {
    
    // Themes begin
    am4core.useTheme(am4themes_animated);
    
    var chart = am4core.create("sale_by_day", am4charts.PieChart3D);
    chart.hiddenState.properties.opacity = 0; 
    
     //title
    var title = chart.titles.create();
        title.text = "Sales by Day of a Week";
        title.fontSize = 16;
        title.marginBottom = 15;
    
    chart.data = @json($saleByDay)
  
    var series = chart.series.push(new am4charts.PieSeries3D());
    series.dataFields.value = "Amount";
    series.dataFields.category = "DayName";
    
    }); // end
</script>

<!-- Sales by Month -->
<script>
    am4core.ready(function() {
    
    // Themes begin
    am4core.useTheme(am4themes_dataviz);
    
    var chart = am4core.create("sales_by_month", am4charts.PieChart3D);
    chart.hiddenState.properties.opacity = 0; 
    
     //title
    var title = chart.titles.create();
        title.text = "Sales by Month";
        title.fontSize = 16;
        title.marginBottom = 15;
    
    chart.data = @json($saleByMonth)
  
    var series = chart.series.push(new am4charts.PieSeries3D());
    series.dataFields.value = "Amount";
    series.dataFields.category = "Month";
    
    }); // end
</script>

<!-- Sales Monthly trend -->
<script>
    am4core.ready(function() {
    
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end
    
    // Create chart instance
    var chart = am4core.create("monthly-sales", am4charts.XYChart);
    
    // Add data
    chart.data = @json($monthlyTrends)

    // Create category axis
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "month";
    
    // Create value axis
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.title.text = "Amount";
    
    // Create series
    var series1 = chart.series.push(new am4charts.LineSeries());
    series1.dataFields.valueY = "sales";
    series1.dataFields.categoryX = "month";
    series1.name = "Sales";
    series1.strokeWidth = 3;
    series1.bullets.push(new am4charts.CircleBullet());
    series1.legendSettings.valueText = "{valueY}";
    
    
    var series2 = chart.series.push(new am4charts.LineSeries());
    series2.dataFields.valueY = "expenses";
    series2.dataFields.categoryX = "month";
    series2.name = 'Expenses';
    series2.strokeWidth = 3;
    series2.bullets.push(new am4charts.CircleBullet());
    series2.legendSettings.valueText = "{valueY}";
    
    // Add chart cursor
    chart.cursor = new am4charts.XYCursor();
    chart.cursor.behavior = "zoomY";
    
    // Add legend
    chart.legend = new am4charts.Legend();
    
    }); // end am4core.ready()
</script>

@endcan
@endpush
