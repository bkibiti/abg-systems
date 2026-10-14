<div class="modal fade" id="edit" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Information</h5>
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
                    <form id="expense_form" action="{{route('losses.update')}}" method="post">
                        @csrf()

                        <div class="modal-body">
                            <input type="hidden" id="id" name="id">

                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">{{ __('Date') }}<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <div style="border: 2px solid white; border-radius: 6px;">
                                        <input type="text" id="date1" name="date" class="form-control my-date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">{{ __('Staff') }}<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select2" class="form-control" id="user1" name="user" data-placeholder="Select Staff" required data-width="100%">
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
                                    <input type="text" id="amount1" class="form-control" name="amount" required>
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">{{ __('Expense Type') }}<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select2" class="form-control" id="type1" name="type" required data-width="100%">
                                            <option value="Loss">Loss</option>
                                            <option value="Advance">Advance</option>
                                    </select>
                                </div>
                            </div> --}}
                     
                 
                            <div class="form-group row">
                                <label for="expense_description"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Remarks') }}</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="remarks1" name="remarks" rows="3" ></textarea>
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