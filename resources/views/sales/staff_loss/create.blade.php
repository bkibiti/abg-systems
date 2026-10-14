<div class="modal fade" id="create" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Staff Loss</h5>
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
                    <form id="expense_form" action="{{route('losses.store')}}" method="post">
                        @csrf()

                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="expense_date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <div style="border: 2px solid white; border-radius: 6px;">
                                        <input type="text" id="date" name="date" class="my-date form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">{{ __('Staff') }}<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control"  name="user" required data-width="100%">
                                        <option value="">Select Staff</option>
            
                                        @foreach($users as $u)
                                            <option value="{{$u->id}}">{{$u->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                     
                            <div class="form-group row">
                                <label for="expense_amount" class="col-md-4 col-form-label text-md-right">{{ __('Amount
                                    ') }}<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" id="amount" class="form-control" name="amount" required>
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">{{ __('Expense Type') }}<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select2" class="form-control" name="type" required data-width="100%">
                                            <option value="">Select Type</option>
                                            <option value="Loss">Loss</option>
                                            <option value="Advance">Advance</option>
                                    </select>
                                </div>
                            </div>
                      --}}
                 
                            <div class="form-group row">
                                <label for="expense_description"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Remarks') }}</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="remarks" rows="3" ></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>