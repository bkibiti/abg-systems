@extends("layouts.master")

@section('page_css')
<style>


</style>
@endsection

@section('content-title')
Staff Losses
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#"> Sales / Staff Losses </a></li>
@endsection

@section("content")



<div class="col-sm-12">
    <div class="card">
        <div class="card-body">

                    <form id="expense_form" action="{{route('losses.search')}}" method="GET">
                        @csrf()
    
                        <div class="form-group row">
    
                            <div class="col-md-2">
                                <div style="border: 2px solid white; border-radius: 6px;">
                                    <input type="text" name="from_date" class="my-date form-control" id="from_date" value="{{ old('from_date') }}" required>
                                </div> 
                            </div>
                            <div class="col-md-2">
                                <div style="border: 2px solid white; border-radius: 6px;">
                                    <input type="text" name="to_date" class="my-date form-control" id="to_date" value="{{ old('to_date') }}" required>
                                </div>
                            </div>
                           
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success">Filter</button>
                            </div>
                            <div class="col-md-4">
                            </div>

                            <div class="col-md-2">
                                @can('Add Staff Loss')
                                    <button type="button" style="float: right; margin-bottom: 1%;" class="btn btn-icon btn-rounded btn-warning btn-sm" data-toggle="modal" 
                                        title="Add Staff Loss" data-target="#create"><i class="feather icon-plus"></i>
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
                                <th>Staff Name</th>
                                <th>Amount</th>
                                {{-- <th>Type</th> --}}
                                <th>Remarks</th>
                                @can('Edit Staff Loss')
                                <th>Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @if ($StaffLOss->count() > 0)
                                
                           
                            @foreach($StaffLOss as $s)
                            <tr>
                                <td>{{date_format(new DateTime($s->date),'d M Y')}}</td>
                                <td>{{$s->user->name}}</td>
                                <td>{{number_format($s->amount,2)}}</td>
                                {{-- <td>{{$s->type}}</td> --}}
                                <td>{{ $s->remarks }}</td>
                            
                                    @can('Edit Staff Loss')
                                    <td>

                                        <a href="#">
                                            <button class="btn btn-info btn-rounded btn-sm" data-remarks="{{$s->remarks}}"
                                                data-id="{{$s->id}}"   data-user="{{$s->user_id}}"    data-type="{{$s->type}}"
                                                data-amount="{{$s->amount}}"    data-date="{{$s->date}}"
                                                type="button" data-toggle="modal" data-target="#edit">Edit</button>
                                        </a>
                                    </td>

                                    @endcan

                        
                

                            </tr>
                            @endforeach

                            @endif

                        </tbody>
                
                    </table>
                
    
                </div>
                <hr>
                {{-- show staff summary --}}
                @if ($total > 0)
                    
                    @foreach ($staffLossTotal as $s)
                    <div class="form-group row">
                        <div class="col-md-2"></div>

                        <div class="col-md-2">
                            {{ $s->user }}
                        </div>
                        <div class="col-md-2 text-right">
                            {{number_format($s->amount,2)}}
                        </div>
                    </div>
                    @endforeach
                    <div class="form-group row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2"> <h6> Total Losses</h6></div>
                        <div class="col-md-2 text-right">
                            <h6>{{number_format($total,2)}}</h6>
                        </div>
                    </div>
                @endif
                
      
        </div>
    </div>
</div>

@include("sales.staff_loss.create")
@include("sales.staff_loss.edit")


@endsection

@push("page_scripts")
<script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
<script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>
@include('partials.notification')

<script>
    var title = document.title;
    document.title = title.concat(" | Sales | Staff Loss");
</script>
<script>
 
    
    $('#fixed-header1').DataTable({
      bAutoWidth: true,
      order: [[0, "desc"]]
    });


    $(function () {
    var start = moment();
    var end = moment();

    $('.my-date').daterangepicker({
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
    
        modal.find('.modal-body #date1').val(button.data('date'));
        modal.find('.modal-body #amount1').val(button.data('amount'));
        modal.find('.modal-body #type1').val(button.data('type'));
        modal.find('.modal-body #remarks1').val(button.data('remarks'));
        modal.find('.modal-body #user1').val(button.data('user'));
        modal.find('.modal-body #id').val(button.data('id'));
      });//end edit

  

      
</script>

@endpush