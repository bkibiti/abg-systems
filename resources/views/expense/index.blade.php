@extends("layouts.master")

@section('page_css')
<style>


</style>
@endsection

@section('content-title')
Expense
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#"> Expense Management / Expense </a></li>
@endsection

@section("content")

<style>
    .datepicker>.datepicker-days {
        display: block;
    }

    ol.linenums {
        margin: 0 0 0 -8px;
    }

    .ms-container {
        background: transparent url('../assets/plugins/multi-select/img/switch.png') no-repeat 50% 50%;
        width: 100%;
    }

    .ms-selectable,
    .ms-selection {
        background: #fff;
        color: #555555;
        float: left;
        width: 45%;
    }

    #loading {
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        position: fixed;
        display: none;
        opacity: 0.7;
        background-color: #fff;
        z-index: 99;
        text-align: center;
    }

    #loading-image {
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 100;
    }

    input[type=button]:focus {
        background-color: #748892;
        border-color: #748892;
        color: white;
    }
</style>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <form id="expense_form" action="{{route('expense.search')}}" method="GET">
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
                        <div id="category" style="border: 2px solid white; border-radius: 6px;">
                            <select name="expense_category" class="form-control">
                                <option value="0" {{ (old('expense_category')==0 ? "selected":"") }} >All Categories
                                </option>
                                @foreach($expense_category as $x)
                                    <option value="{{$x->id}}" {{ (old('expense_category')==$x->id ? "selected":"") }}>{{$x->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success">Filter</button>
                    </div>
                    <div class="col-md-2">
                    </div>

                    <div class="col-md-2">
                        @can('Add Expenses')
           
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
                                <th>Expense Date</th>
                                <th>Expense Category</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>User</th>
                                @can('Edit Expenses')
                                <th>Action</th>
                                @endcan
                            </tr>
                    </thead>
                    <tbody>

                        @foreach($Expenses as $x)
                        <tr>
                            {{-- <td>{{$x->created_at}}</td> --}}
                            <td>{{date_format(new DateTime($x->created_at),'d M Y')}}</td>
                            <td>{{$x->category->name}}</td>
                            <td>{{ $x->expense_description }}</td>
                            <td>{{number_format($x->amount,2)}}</td>
                            <td>{{$x->user->name}}</td>
                                @can('Edit Expenses')
                                <td>

                                    <a href="#">
                                        <button class="btn btn-info btn-rounded btn-sm" data-expense_description="{{$x->expense_description}}"
                                            data-id="{{$x->id}}"   data-date="{{$x->created_at}}"  
                                            data-amount="{{$x->amount}}"    data-expense_category="{{$x->expense_category_id}}"
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
                    <div class="col-md-2"> <h6> Total Expenses</h6></div>
                    <div class="col-md-2">
                        <h6>{{number_format($total,2)}}</h6>
                    </div>
                </div>
            @endif
  
        </div>
    </div>
</div>

@include("expense.create")
@include("expense.edit")


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
        modal.find('.modal-body #expense_category2').val(button.data('expense_category'));
        modal.find('.modal-body #expense_description2').val(button.data('expense_description'));
        modal.find('.modal-body #id').val(button.data('id'));
      });//end edit



</script>


@endpush