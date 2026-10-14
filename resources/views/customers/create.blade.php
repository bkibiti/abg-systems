  <div class="modal fade" id="create" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <div class="modal-header">
                    <h5 class="modal-title" >New Customer </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">

                <div>
                    <form id='create-form' method="POST" action="{{ route('customers.store') }}" >
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Name<font color="red">*</font></label>

                            <div class="col-md-8">
                                <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" required >

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label  class="col-md-4 col-form-label text-md-end">Mobile<font color="red">*</font></label>

                            <div class="col-md-8">
                                <input id="mobile" type="text" name="mobile" value="{{ old('mobile') }} " class="form-control" required >
                            </div>
                        </div>

               
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('E-Mail') }} </label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" >

                            </div>
                        </div>
              
                        <div class="row mb-3">
                            <label  class="col-md-4 col-form-label text-md-end">Date of Birth </span></label>
                            <div class="col-md-8">
                                <div style="border: 2px solid white; border-radius: 6px;">
                                    <input type="text" id="dob" name="dob" class="form-control" >
                                </div>
                            </div>
                        </div>     


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>


            </div>

          </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
