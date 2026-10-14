<div class="modal fade" id="edit" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Sales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                    @endif
                    <form id="expense_form" action="{{route('lodge-sales.update')}}" method="post">
                        @csrf()
                        <div class="modal-body">
                            <input type="hidden" id="id" name="id">

                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">{{ __('Date') }}<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <div style="border: 2px solid white; border-radius: 6px;">
                                        <input type="text" id="date2" name="expense_date" class="form-control"
                                            required>
                                    </div>
                                </div>
                            </div>
                   
                            <div class="form-group row">
                                <label for="expense_amount" class="col-md-4 col-form-label text-md-right">{{ __('Sale
                                    Amount') }}<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" id="expense_amount2" class="form-control"
                                        onkeypress="return isNumberKey(event,this)" name="expense_amount" required>
                                </div>
                            </div>
                
                            <div class="form-group row">
                                <label for="expense_description"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="expense_description2" name="expense_description" rows="2"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>