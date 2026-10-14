<div class="modal fade" id="create" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" >Add Reminder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
                <div class="modal-body">
                    <div class="panel-body">
                      <form action="{{ route('reminders.store') }}" method="post">
                       @csrf()
                      <div class="form-group row">
                          <label for="name" class="col-md-4 col-form-label text-md-right">Reminder <font color="red">*</font></label>
                           <div class="col-md-8">
                              <input type="text" class="form-control" id="name" name="name"
                                     required maxlength="200">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label  class="col-md-4 col-form-label text-md-right">Start Date<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <div style="border: 2px solid white; border-radius: 6px;">
                                    <input type="text" id="start_date" name="start_date" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label  class="col-md-4 col-form-label text-md-right">End Date<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <div style="border: 2px solid white; border-radius: 6px;">
                                    <input type="text" id="end_date" name="end_date" class="form-control" required>
                                </div>
                            </div>
                        </div>

                         <div class="form-group row">
                          <label for="days" class="col-md-4 col-form-label text-md-right">Days <font color="red">*</font></label>
                           <div class="col-md-8">
                              <input type="number" min="1" class="form-control" id="days" name="days" placeholder="# of days to get reminder" required>
                            </div>
                        </div>

                                          
                      <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                         <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                   </form>
                    </div>
                </div>
         </div>
     </div>
</div>
