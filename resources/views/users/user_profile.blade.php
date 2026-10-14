@extends("layouts.master")


@section('content-title')

@endsection

@section("content")

<div class="row" >
    
    <div class="col-sm-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">My Profile </h3>
            </div>
            <div class="box-body">
                <form method="POST" action="{{route('update-profile') }}" >
                    @csrf
                    
      
                    <div class="form-group row">
                        <div class="col-md-1"></div>
                        <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('Name') }}</label>
                        
                        <div class="col-md-7">
                            <input id="name" type="text" class="form-control" name="name" value="{{ $users->name}}" required>
                            
                            <span class="text-danger">
                                <strong id="name-error"></strong>
                            </span>
                        </div>
                    </div>
                    
                    
                    <div class="form-group row">
                        <div class="col-md-1"></div>
                        <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                        
                        <div class="col-md-7">
                            <input id="email" type="email" class="form-control" name="email" value="{{ $users->email }}" disabled>
                            
                            <span class="text-danger">
                                <strong id="email-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-1"></div>
                        <label for="mobile1" class="col-md-3 col-form-label text-md-right">{{ __('Mobile Number') }}</label>
                        
                        <div class="col-md-7">
                            <input id="mobile" type="text" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" name="mobile" value="{{ $users->mobile }}" data-inputmask='"mask": "0999-999-9999"' data-mask>
                            
                            <span class="text-danger">
                                <strong id="mobile-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-1"></div>
                        <label for="job" class="col-md-3 col-form-label text-md-right">{{ __('Job Title') }}</label>
                        
                        <div class="col-md-7">
                            <input id="job" type="text" class="form-control" name="job" value="{{ $users->job_title }}">
                            
                            <span class="text-danger">
                                <strong id="job-error"></strong>
                            </span>
                        </div>
                    </div>
              

                    <div class="form-group row">
                        <div class="col-md-1"></div>
                        <label for="role" class="col-md-3 col-form-label text-md-right">{{ __('Role') }}</label>
                        <div class="col-md-7">
                            <input  type="text" class="form-control" name="role" value="{{ implode(",",auth()->user()->getRoleNames()->toArray()) }}" disabled>
                        </div>
                    </div>  

        
           
                    <div class="form-group row">
                        <div class="col-md-3"></div>
                        <div class="col-sm-8">
                            <div class="pull-right">
                                <button type="submit" id="update" class="btn btn-success">Update</button>
                                
                            </div>
                        </div>
                    </div>         
                    
                </form>
            </div>
        </div>
    </div>
    
</div>


<div class="row">
   
    <div class="col-sm-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Change Password </h3>
            </div>
            <div class="box-body">
                <form method="POST" action="{{ route('change-password') }}">
                    @csrf
                    
                    <div class="form-group row">
                        <div class="col-md-1"></div>
                        <label for="current_password" class="col-md-3 col-form-label text-md-right">Current Password</label>
                        
                        <div class="col-md-7">
                            <input type="password" class="form-control" name="current_password"  required autofocus>
                            
                            <span class="text-danger">
                                <strong id="firstname-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-1"></div>
                        <label for="new_password" class="col-md-3 col-form-label text-md-right">New Password</label>
                        
                        <div class="col-md-7">
                            <input  type="password" class="form-control" name="new_password"    required>
                            
                            <span class="text-danger">
                                <strong id="lastname-error"></strong>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-md-1"></div>
                        <label for="username" class="col-md-3 col-form-label text-md-right">Confirm New Password</label>
                        
                        <div class="col-md-7">
                            <input  type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation"  required>
                            
                            <span class="text-danger">
                                <strong id="username-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"></div>
                        <div class="col-sm-8">
                            <div class="pull-right">
                                <button type="submit" id="save" class="btn btn-success">Change</button>
                                
                            </div>
                        </div>
                    </div>
                    
                    
                </form> 
            </div>
        </div>
    </div>
    @endsection 
    
    
    @push("bk_script")
    
    @include('partials.notification')
    
    <script src="{{asset("dist/Inputmask5/jquery.inputmask.js")}}"></script>
    
    <script>
        $(document).ready(function(){
            
            $('[data-mask]').inputmask();
            
            
            
        });
        
        
    </script>
    @endpush