@extends("layouts.master")

@section('page_css')
<style>


</style>
@endsection

@section('content-title')
Sales
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#"> Lodge / Sales</a></li>
@endsection

@section("content")



<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <form id="expense_form" action="{{route('lodge-sales.search')}}" method="GET">
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
                        @can('Manage Lodge Sales')
           
                            <button type="button" style="float: right; margin-bottom: 1%;" class="btn btn-icon btn-rounded btn-warning btn-sm" data-toggle="modal" 
                                    name="issued_date" value="Add Expense" title="Add Expense" data-target="#create"><i class="feather icon-plus"></i></button>
                        @endcan
                    </div>
                </div>

            </form>
    <hr>
            <div class="table-responsive">
                <table id="fixed-header1" class="display table nowrap table-striped table-hover" style="width:100%">

                    <thead>
                            <tr>
                                <th>Sale Date</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>User</th>
                                <th>Action</th>
                            </tr>
                    </thead>
                    <tbody>

                        @foreach($sales as $x)
                        <tr>
                            <td>{{date_format(new DateTime($x->created_at),'d M Y')}}</td>
                            <td>{{number_format($x->amount,2)}}</td>
                            <td>{{ $x->description }}</td>
                            <td>{{$x->user->name}}</td>
                                @can('Manage Lodge Sales')
                                <td>

                                    <a href="#">
                                        <button class="btn btn-info btn-rounded btn-sm" data-expense_description="{{$x->description}}"
                                            data-id="{{$x->id}}"   data-date="{{$x->created_at}}"  
                                            data-amount="{{$x->amount}}" 
                                            type="button" data-toggle="modal" data-target="#edit">Edit</button>
                                    </a>
                                </td>

                                @endcan

                    
            

                        </tr>
                        @endforeach


                    </tbody>
            
                </table>
            

            </div>

            <hr>
            {{-- show staff summary --}}
            @if ($total > 0)
                
                <div class="form-group row">
                    <div class="col-md-2"> <h6> Total Sales</h6></div>
                    <div class="col-md-2">
                        <h6>{{number_format($total,2)}}</h6>
                    </div>
                </div>
            @endif
  
        </div>
    </div>
</div>

@include("lodge.sales.create")
@include("lodge.sales.edit")


@endsection

@push("page_scripts")
<script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
<script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>
@include('partials.notification')

<script>
    var title = document.title;
    document.title = title.concat(" | Expenses");
</script>

<script>

    $('#fixed-header1').DataTable({
      bAutoWidth: true,
      order: [[0, "desc"]]
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

    $(function () {
    var start = moment();
    var end = moment();

    $('#date').daterangepicker({
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

        $('#date2').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                maxDate: end,
                autoUpdateInput: true,
                locale: {
                    format: 'DD-M-YYYY'
                }
            });
    });


    $('#edit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var modal = $(this)
    
        modal.find('.modal-body #date2').val(button.data('date'));
        modal.find('.modal-body #expense_amount2').val(button.data('amount'));
        modal.find('.modal-body #expense_description2').val(button.data('expense_description'));
        modal.find('.modal-body #id').val(button.data('id'));
      });//end edit



</script>


@endpush