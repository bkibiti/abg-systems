@extends("layouts.master")

@section('page_css')
<style>
 

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

            <!-- start row 2-->
            <div class="row">
                <div class="col-md-12">
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

         

            </div>
            <!-- end row 12 -->

      
 

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
