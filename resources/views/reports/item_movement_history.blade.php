@extends("layouts.master")

@section('page_css')
<style>


</style>
@endsection

@section('content-title')
Item Movement History
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
                            <th>Date</th>
                            <th>Action</th>
                            <th>Quantity</th>
                            <th>QOH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                            <tr>
                                <td>{{$d->Date}}</td>
                                <td>{{$d->Action}}</td>
                                <td>{{$d->Qty}}</td>
                                <td>{{$d->QOH}}</td>

                            </tr>
                        @endforeach
                    </tbody>
            
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
    document.title = title.concat(" | Reports - Item Movement History");
</script>

<script>

    $('#product_list').show();


    $('#fixed-header1').DataTable({
      bAutoWidth: true,
      ordering: false
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