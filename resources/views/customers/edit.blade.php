  <div class="modal fade" id="edit" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <div class="modal-header">
                    <h5 class="modal-title" >Update Customer Information </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">

                <div>
                    <form method="POST" action="{{ route('customers.update') }}" >
                        @csrf

                        <input type="hidden" id="id" name="id">
                        
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Name<font color="red">*</font></label>

                            <div class="col-md-8">
                                <input id="name2" type="text" name="name" value="{{ old('name') }}" class="form-control" required >

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label  class="col-md-4 col-form-label text-md-end">Mobile<font color="red">*</font></label>

                            <div class="col-md-8">
                                <input id="mobile2" type="text" name="mobile" value="{{ old('mobile') }} " class="form-control" required >
                            </div>
                        </div>

               
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('E-Mail') }} </label>

                            <div class="col-md-8">
                                <input id="email2" type="email" class="form-control" name="email" value="{{ old('email') }}" >

                            </div>
                        </div>
              
                        <div class="row mb-3">
                            <label  class="col-md-4 col-form-label text-md-end">Date of Birth </span></label>
                            <div class="col-md-8">
                                <div style="border: 2px solid white; border-radius: 6px;">
                                    <input type="text" id="dob2" name="dob" class="form-control" >
                                </div>
                            </div>
                        </div>     

                        <div class="row mb-3">
                            <label  class="col-md-4 col-form-label text-md-end">Status </span></label>
                            <div class="col-md-8">
                                <select class="form-select select2"  class="form-select" id='status2' name="status"  required data-width="100%">
                                    <option value="Active" {{ (old('status')=='Active' ? "selected":"") }} >Active</option>
                                    <option value="Inactive" {{ (old('status')=='Inactive' ? "selected":"") }} >Inactive</option>
                                </select>
                            </div>
                        </div>   


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>


            </div>

          </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
