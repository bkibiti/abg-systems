<div class="modal fade" id="create" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" >Add Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <div>
                      <form action="{{ route('suppliers.store') }}" method="post">
                       @csrf()
                      <div class="row mb-3">
                          <label for="name" class="col-md-4 col-form-label text-md-end">Name<font color="red">*</font></label>
                           <div class="col-md-8">
                              <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp"
                                     required maxlength="20">
                            </div>
                        </div>

                         <div class="row mb-3">
                          <label for="contact_person" class="col-md-4 col-form-label text-md-end">Contact Person</label>
                           <div class="col-md-8">
                              <input type="text" class="form-control" id="contact_person" name="contact_person" aria-describedby="emailHelp"
                                     required maxlength="20">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="mobile" class="col-md-4 col-form-label text-md-end">Phone<font
                                    color="red">*</font></label>
                          <div class="col-md-8">
                             <input type="text" class="form-control mob_no" id="phone_edit" name="phone" data-mask="9999 999 999"
                                    pattern="^0\d{3}[\-]\d{3}[\-]\d{3}$" title="Eg:0777-777-777" required>
                            </div>
                            </div>
                             <div class="row mb-3">
                                 <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
                               <div class="col-md-8">
                                   <input type="email" class="form-control" id="email" maxlength="40" name="email"
                                          aria-describedby="emailHelp">
                            </div>
                          </div>
                          <div class="row mb-3">
                          <label for="contact_person" class="col-md-4 col-form-label text-md-end">Address</label>
                           <div class="col-md-8">
                              <input type="text" class="form-control" id="address" name="address" aria-describedby="emailHelp"
                                     required maxlength="20">
                            </div>
                        </div>
                      <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                         <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                   </form>
                    </div>
                </div>
         </div>
     </div>
</div>
