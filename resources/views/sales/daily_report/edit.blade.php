<div class="modal fade" id="edit" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row">
                <div class="col-md-12">
           
                    <form id="expense_form" action="{{route('sales.daily-update')}}" method="post">
                        @csrf()

                        <div class="modal-body">
                            <input id="id" name="id" type="hidden">
                            <input id="report_date_2" name="report_date" type="hidden">


                            <div class="form-group row">
                                <label for="expense_date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>
                                <div class="col-md-8">
                                    <input type="text" id="report_date_edit" class="form-control" readonly>

                                </div>
                            </div>
                     
                            <div class="form-group row">
                                <label for="expense_amount" class="col-md-4 col-form-label text-md-right">{{ __('Income (Not Sales)
                                    ') }}<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" id="other_income_edit" class="form-control" name="other_income" required>
                                </div>
                            </div>
                     
                 
                            <div class="form-group row">
                                <label for="expense_description"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Remarks') }}</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="submission_remarks_edit" name="submission_remarks" rows="3" required></textarea>
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