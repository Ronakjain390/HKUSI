@extends('admin.layouts.index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="profile-img-box">
        <div class="row">
            <div class="col-6">
                <div class="profile-part">
                    <div class="profile-text">
                        <h4 class="Profile-name">Email Setting  </h4>
                    </div>
                </div>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <div class="profile-tab">
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="ti ti-dots"></i>
                </button>
                        <div class="dropdown-menu table-dropdown">
                          
                            <a class="dropdown-item" onclick="delete_member('')" href="javascript:void(0)">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="profile-page-buttons-section" id="active">
        <a href="{{route('admin.email-setting.index')}}"  class="btn btn-custom @if(Request::segment(2)=='email-setting' &&  ! Request::segment(4)=='email-template') active @endif">
              <span><svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.725 0.978039C0.95 0.753039 1.25 0.678039 1.55 0.828039C1.625 0.828039 1.7 0.903039 1.775 0.978039C1.925 1.12804 2 1.27804 2 1.50304C2 1.72804 1.925 1.87804 1.775 2.02804C1.625 2.17804 1.475 2.25304 1.25 2.25304H1.1C1.025 2.17804 1.025 2.17804 0.95 2.17804C0.9125 2.17804 0.89375 2.15929 0.875 2.14054C0.85625 2.12179 0.8375 2.10304 0.8 2.10304L0.725 2.02804C0.6875 1.99054 0.66875 1.95304 0.65 1.91554C0.63125 1.87804 0.6125 1.84054 0.575 1.80304C0.5 1.72804 0.5 1.57804 0.5 1.50304C0.5 1.42804 0.5 1.27804 0.575 1.20304C0.575 1.12804 0.65 1.05304 0.725 0.978039ZM5 0.753039C4.55 0.753039 4.25 1.05304 4.25 1.50304C4.25 1.95304 4.55 2.25304 5 2.25304H14.75C15.2 2.25304 15.5 1.95304 15.5 1.50304C15.5 1.05304 15.2 0.753039 14.75 0.753039H5ZM5 5.25304H14.75C15.2 5.25304 15.5 5.55304 15.5 6.00304C15.5 6.45304 15.2 6.75304 14.75 6.75304H5C4.55 6.75304 4.25 6.45304 4.25 6.00304C4.25 5.55304 4.55 5.25304 5 5.25304ZM14.75 9.75304H5C4.55 9.75304 4.25 10.053 4.25 10.503C4.25 10.953 4.55 11.253 5 11.253H14.75C15.2 11.253 15.5 10.953 15.5 10.503C15.5 10.053 15.2 9.75304 14.75 9.75304ZM1.925 5.70304C1.925 5.66554 1.90625 5.64679 1.8875 5.62804C1.86875 5.60929 1.85 5.59054 1.85 5.55304C1.85 5.47804 1.775 5.47804 1.775 5.47804C1.55 5.25304 1.25 5.17804 0.95 5.32804C0.9125 5.36554 0.875 5.38429 0.8375 5.40304C0.8 5.42179 0.7625 5.44054 0.725 5.47804L0.65 5.55304C0.65 5.59054 0.63125 5.60929 0.6125 5.62804C0.59375 5.64679 0.575 5.66554 0.575 5.70304C0.575 5.73491 0.575 5.75324 0.569245 5.76953C0.561456 5.79158 0.543129 5.80991 0.5 5.85304V6.00304C0.5 6.22804 0.575 6.37804 0.725 6.52804C0.875 6.67804 1.025 6.75304 1.25 6.75304C1.475 6.75304 1.625 6.67804 1.775 6.52804C1.925 6.37804 2 6.22804 2 6.00304V5.85304C2 5.81554 1.98125 5.79679 1.9625 5.77804C1.94375 5.75929 1.925 5.74054 1.925 5.70304ZM0.575 10.203C0.575 10.128 0.65 10.053 0.725 9.97804C1.025 9.67804 1.475 9.67804 1.775 9.97804C1.925 10.128 2 10.278 2 10.503C2 10.728 1.925 10.878 1.775 11.028C1.625 11.178 1.475 11.253 1.25 11.253C1.025 11.253 0.875 11.178 0.725 11.028C0.575 10.878 0.5 10.728 0.5 10.503C0.5 10.428 0.5 10.278 0.575 10.203Z" fill="black"></path>
                </svg>
                </span>Setting
         </a>
         @if(isset($EmailSetting) && !empty($EmailSetting))
         <a href="{{route('admin.email-settingdetails',[$EmailSetting->id,'email-template'])}}"  class="btn btn-custom @if(Request::segment(4) == 'email-template')  active @endif ">
              <span><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path d="M2.243 6.854L11.49 1.31a1 1 0 0 1 1.029 0l9.238 5.545a.5.5 0 0 1 .243.429V20a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7.283a.5.5 0 0 1 .243-.429zM4 8.133V19h16V8.132l-7.996-4.8L4 8.132zm8.06 5.565l5.296-4.463 1.288 1.53-6.57 5.537-6.71-5.53 1.272-1.544 5.424 4.47z" />
                </svg>
                </span>Email Template
         </a>
         @endif
       
    </button>
    </div>
    <!-- Content wrapper -->
</div>
 @if(isset($dataType) && !empty($dataType) && $dataType == 'email-template')
       <livewire:admin.email-template-management  />
@elseif(isset($EmailSetting) && !empty($EmailSetting))
 <div class="container-xxl flex-grow-1 container-p-y">
            {!! Form::model($EmailSetting, ['method' => 'PATCH','route' => ['admin.email-setting.update', $EmailSetting->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
            <div class="card custom-card profile-details">
                <div class="basic-details">
                    <h6 class="card-heading">Basic Info</h6>
                </div>
                <div class="table-responsive table-details">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Server Name/Host</th> 
                                <td><input type="text" name="host_name"  class="form-control" placeholder="Server Name/Host" @if(isset($EmailSetting->host_name) && !empty($EmailSetting->host_name)) value="{{$EmailSetting->host_name}}" @endif></td>
                            </tr>
                            <tr>
                                <th class="t-basic">Port</th> 
                                <td><input style="max-width: 75px;" type="number" name="port" required class="form-control" placeholder="Port" @if(isset($EmailSetting->port) && !empty($EmailSetting->port)) value="{{$EmailSetting->port}}" @endif></td>
                            </tr>
                            <tr>
                                <th class="t-basic">Connection Security</th> 
                                <td><input type="checkbox" name="connection_security"  @if($EmailSetting->connection_security=="on") checked @endif ></td>
                            </tr>
                            <tr>
                                <th class="t-basic">Mail ID</th> 
                                <td><input  type="email" name="email" required class="form-control" placeholder="Username" @if(isset($EmailSetting->email) && !empty($EmailSetting->email)) value="{{$EmailSetting->email}}" @endif></td>
                            </tr>
                            <tr>
                                <th class="t-basic">Password</th> 
                                <td><input  type="Password" name="password" required class="form-control" placeholder="Password" @if(isset($EmailSetting->password) && !empty($EmailSetting->password)) value="{{$EmailSetting->password}}" @endif></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card custom-card">
                <div class="form-btn">
                    <button type="submit" class="btn action-btn">Save Changes</button>
                    <button type="reset" class="btn cancel-btn">Delete</button>
                </div>
            </div>
        {!! Form::close() !!}
        <!-- / Content -->
        <div class="content-backdrop fade "></div>
        <!-- Content wrapper -->
    </div>
    @else
     
    <div class="container-xxl flex-grow-1 container-p-y">
            {!! Form::open(array('route' => 'admin.email-setting.store','method'=>'POST','class'=>'edit-form','autocomplete' => 'off','files' =>false,'id'=>'quickForm')) !!}
            <div class="card custom-card profile-details">
                <div class="basic-details">
                    <h6 class="card-heading">Basic Info</h6>
                </div>
                <div class="table-responsive table-details">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Server Name/Host</th> 
                                <td><input type="text" name="host_name" required class="form-control" placeholder="Server Name/Host"></td>
                            </tr>
                            <tr>
                                <th class="t-basic">Port</th> 
                                <td><input style="max-width: 75px;" type="number" name="port" required class="form-control" placeholder="Port"></td>
                            </tr>
                            <tr>
                                <th class="t-basic">Connection Security</th> 
                                <td><input type="checkbox" name="connection_security" ></td>
                            </tr>
                            <tr>
                                <th class="t-basic">Mail ID</th> 
                                <td><input  type="text" name="email" required class="form-control" placeholder="Username"></td>
                            </tr>
                            <tr>
                                <th class="t-basic">Password</th> 
                                <td><input  type="Password" name="password" required class="form-control" placeholder="Password"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card custom-card">
                <div class="form-btn">
                    <button type="submit" class="btn action-btn">Save Changes</button>
                    <button type="reset" class="btn cancel-btn">Delete</button>
                </div>
            </div>
        {!! Form::close() !!}
        <!-- / Content -->
        <div class="content-backdrop fade "></div>
        <!-- Content wrapper -->
    </div>
    @endif
    <!-- / Layout page -->
@endsection
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
@push('foorterscript')
<script>
     CKEDITOR.replaceClass="article-ckeditor";
    $().ready(function () {
        $("#quickForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                title: "required",
                status: {
                    required: true,
                },
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                title: "Please enter a title",
                status: {
                    required: "Please select a status",
                },
            }
        });
    });

</script>
@endpush