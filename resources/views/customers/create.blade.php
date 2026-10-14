  <div class="modal fade" id="create" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <div class="modal-header">
                    <h5 class="modal-title" >New Customer </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">

                <div class="panel-body">
                    <form id='create-form' method="POST" action="{{ route('customers.store') }}" >
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name<font color="red">*</font></label>

                            <div class="col-md-8">
                                <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" required >

                            </div>
                        </div>

                        <div class="form-group row">
                            <label  class="col-md-4 col-form-label text-md-right">Mobile<font color="red">*</font></label>

                            <div class="col-md-8">
                                <input id="mobile" type="text" name="mobile" value="{{ old('mobile') }} " class="form-control" required >
                            </div>
                        </div>

               
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }} </label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" >

                            </div>
                        </div>
              
                        <div class="form-group row">
                            <label  class="col-md-4 col-form-label text-md-right">Date of Birth </span></label>
                            <div class="col-md-8">
                                <div style="border: 2px solid white; border-radius: 6px;">
                                    <input type="text" id="dob" name="dob" class="form-control" >
                                </div>
                            </div>
                        </div>     


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>


            </div>

          </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
