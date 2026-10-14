@extends("layouts.master")

@section('page_css')
<style>


</style>
@endsection

@section('content-title')
Current Stock Value
@endsection


@section("content")

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">

            @include('reports._search')
    <hr>
            <div class="table-responsive">
                <table id="fixed-header1" class="display table nowrap table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity on Hand</th>
                            <th>Value by Purchase Price</th>
                            <th>Value by Selling Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                            <tr>
                                <td>{{$d->name}}</td>
                                <td>{{$d->quantity}}</td>
                                <td>{{number_format($d->purchase_value,2)}}</td>
                                <td>{{number_format($d->sale_value,2)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><h6>Total Stock Value</h6> </td>
                            <td></td>
                            <td><h6>{{number_format($total[0]->total_purchase_value,2)}}</h6></td>
                            <td><h6>{{number_format($total[0]->total_sale_value,2)}}</h6></td>
                        </tr>
                    </tfoot>
            
                </table>
            

            </div>

            <hr>
 
  
        </div>
    </div>
</div>


@endsection

@push("page_scripts")
<script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
<script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>
@include('partials.notification')
@include('reports._hide_show_filters')

<script>
    var title = document.title;
    document.title = title.concat(" | Reports - Stock Value");
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