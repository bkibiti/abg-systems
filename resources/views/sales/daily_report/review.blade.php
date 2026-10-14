<div class="modal fade" id="Review" role="dialog">
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
           
                    <form id="expense_form" action="{{route('sales.daily-review')}}" method="post">
                        @csrf()

                        <div class="modal-body">
                            <input id="id2" name="id" type="hidden">

                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">Submitted By</label>
                                <div class="col-md-8">
                                    <div id="by2">                                     
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">Submitted At</label>
                                <div class="col-md-8">
                                    <div id="at2">                                     
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">Submission Remarks</label>
                                <div class="col-md-8">
                                    <div id="submission_remarks2">                                     
                                    </div>
                                </div>
                            </div>
                        
                 
                            <div class="form-group row">
                                <label for="expense_description"
                                    class="col-md-4 col-form-label text-md-right">Approval Remarks</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="approval_remarks" name="approval_remarks" rows="3"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="status" value="reject" class="btn btn-danger">Reject</button>
                            <button type="submit" name="status" value="approve"   class="btn btn-primary">Approve</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>