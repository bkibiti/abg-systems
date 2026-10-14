@extends("layouts.master")

@section('page_css')
<style>


</style>
@endsection

@section('content-title')
Daily Sale Report
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#"> Sales Management / Daily Sale Report </a></li>
@endsection

@section("content")



<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <form id="expense_form" action="{{route('sales.daily-search')}}" method="post">
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
                            @can('Manage Daily Report')
                                <button type="button" name="create_report" value="Add Report" style="float: right; margin-bottom: 1%;" class="btn btn-icon btn-rounded btn-warning btn-sm" data-toggle="modal" 
                                    title="Add Report" data-target="#create"><i class="feather icon-plus"></i>
                                </button>
                            @endcan
                        </div>
                    </div>

                </form>
<hr>
             
                <div class="table-responsive">
                    <table id="fixed-header1" class="display table nowrap table-striped table-hover" style="width:100%">

                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Sales</th>
                                <th>Income</th>
                                <th>Expenses</th>
                                <th>Purchases</th>
                                <th>Cash on Hand</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($totals->count() > 0)
                                
                                @foreach($DailySale as $s)
                                <tr>
                                    <td>{{$s->report_date}}</td>
                                    <td>{{number_format($s->sales,0)}}</td>
                                    <td>{{number_format($s->other_income,2)}}</td>
                                    <td>{{number_format($s->expenses,2)}}</td>
                                    <td>{{number_format($s->purchases,2)}}</td>
                                    <td>{{number_format($s->cash_on_hand,2)}}</td>
                                    <td>{{$s->status}}</td>
                                    <td>
                                        <a href="#">
                                            <button class="btn btn-success btn-rounded btn-sm" data-submission_remarks="{{$s->submission_remarks}}"
                                                data-submitted_by="{{$s->submitter->name}}" data-submitted_at="{{$s->submitted_at}}"
                                                data-approver_remarks="{{$s->approver_remarks}}"   
                                                type="button" data-toggle="modal" data-target="#details">Details</button>
                                        </a>
                                        @can('Manage Daily Report')
                                            @if ($s->status=="Pending" or $s->status=="Rejected")
                                                <a href="#">
                                                    <button class="btn btn-info btn-rounded btn-sm" data-submission_remarks="{{$s->submission_remarks}}"
                                                        data-id="{{$s->id}}" 
                                                        data-other_income="{{$s->other_income}}"    data-report_date="{{$s->report_date}}"
                                                        type="button" data-toggle="modal" data-target="#edit">Edit</button>
                                                </a>
                                            @endif
                                        @endcan

                                        @can('Approve Daily Report')
                                            @if ($s->status=="Pending")
                                                <a href="#">
                                                    <button class="btn btn-secondary btn-rounded btn-sm" data-submission_remarks="{{$s->submission_remarks}}"
                                                        data-id="{{$s->id}}"  data-submitted_by="{{$s->submitter->name}}" data-submitted_at="{{$s->submitted_at}}"
                                                        type="button" data-toggle="modal" data-target="#Review">Review</button>
                                                </a>
                                            @endif
                                        @endcan

                    
                                    </td>

                                </tr>
                                @endforeach
                            @endif

                        </tbody>
                        <tfoot>
                            <tr>
                                @if ($totals->count() > 0)

                                    <td><h5>Total</h5> </td>
                                    <td><h5>{{number_format($totals[0]->sales)}}</h5></td>
                                    <td><h5>{{number_format($totals[0]->income)}}</h5></td>
                                    <td><h5>{{number_format($totals[0]->expenses)}}</h5></td>
                                    <td><h5>{{number_format($totals[0]->purchases)}}</h5></td>
                                    <td><h5>{{number_format($totals[0]->coh)}}</h5></td>
                                @endif

                            </tr>
                        </tfoot>
                    </table>
                    <div >
                       
                    </div>
    
                </div>
                
                
      
            </div>
        </div>
    </div>
</div>

@include("sales.daily_report.create")
@include("sales.daily_report.edit")
@include("sales.daily_report.review")
@include("sales.daily_report.details")


@endsection

@push("page_scripts")
<script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
<script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>
@include('partials.notification')

<script>
    var title = document.title;
    document.title = title.concat(" | Sales | Daily Sale Report");
</script>
<script>
    $('#fixed-header1').DataTable({
      bAutoWidth: true,
      order: [[0, "desc"]]
    });


    $(function () {
    var start = moment();
    var end = moment();

    $('#report_date').daterangepicker({
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


    $('#details').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var modal = $(this)

        modal.find('.modal-body #at').text(button.data('submitted_at'));
        modal.find('.modal-body #by').text(button.data('submitted_by'));
        modal.find('.modal-body #submission_remarks').text(button.data('submission_remarks'));
        modal.find('.modal-body #approved_by').text(button.data('approved_By'));
        modal.find('.modal-body #approver_remarks').text(button.data('approver_remarks'));
      });//edn details

      $('#edit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var modal = $(this)
    
        modal.find('.modal-body #report_date_2').val(button.data('report_date'));
        modal.find('.modal-body #report_date_edit').val(button.data('report_date'));
        modal.find('.modal-body #other_income_edit').val(button.data('other_income'));
        modal.find('.modal-body #submission_remarks_edit').val(button.data('submission_remarks'));
        modal.find('.modal-body #id').val(button.data('id'));
      });//end edit

      $('#Review').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var modal = $(this)

        modal.find('.modal-body #at2').text(button.data('submitted_at'));
        modal.find('.modal-body #by2').text(button.data('submitted_by'));
        modal.find('.modal-body #submission_remarks2').text(button.data('submission_remarks'));
        modal.find('.modal-body #id2').val(button.data('id'));

      });//end reveiw


      
</script>

@endpush