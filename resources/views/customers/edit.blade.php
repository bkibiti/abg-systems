  <div class="modal fade" id="edit" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <div class="modal-header">
                    <h5 class="modal-title" >Update Customer Information </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">

                <div class="panel-body">
                    <form method="POST" action="{{ route('customers.update') }}" >
                        @csrf

                        <input type="hidden" id="id" name="id">
                        
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name<font color="red">*</font></label>

                            <div class="col-md-8">
                                <input id="name2" type="text" name="name" value="{{ old('name') }}" class="form-control" required >

                            </div>
                        </div>

                        <div class="form-group row">
                            <label  class="col-md-4 col-form-label text-md-right">Mobile<font color="red">*</font></label>

                            <div class="col-md-8">
                                <input id="mobile2" type="text" name="mobile" value="{{ old('mobile') }} " class="form-control" required >
                            </div>
                        </div>

               
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }} </label>

                            <div class="col-md-8">
                                <input id="email2" type="email" class="form-control" name="email" value="{{ old('email') }}" >

                            </div>
                        </div>
              
                        <div class="form-group row">
                            <label  class="col-md-4 col-form-label text-md-right">Date of Birth </span></label>
                            <div class="col-md-8">
                                <div style="border: 2px solid white; border-radius: 6px;">
                                    <input type="text" id="dob2" name="dob" class="form-control" >
                                </div>
                            </div>
                        </div>     

                        <div class="form-group row">
                            <label  class="col-md-4 col-form-label text-md-right">Status </span></label>
                            <div class="col-md-8">
                                <select class="form-control select2"  class="form-control" id='status2' name="status"  required data-width="100%">
                                    <option value="Active" {{ (old('status')=='Active' ? "selected":"") }} >Active</option>
                                    <option value="Inactive" {{ (old('status')=='Inactive' ? "selected":"") }} >Inactive</option>
                                </select>
                            </div>
                        </div>   


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>


            </div>

          </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
