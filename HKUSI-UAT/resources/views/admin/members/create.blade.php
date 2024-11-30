@extends('admin.layouts.index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {!! Form::open(array('route' => 'admin.members.store','method'=>'POST','class'=>'edit-form','autocomplete' => 'off','files' =>false, 'id'=>'quickForm')) !!}
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
          
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Application #</th>
                        <td><input type="text" name="application_number"  value="" class="form-control" placeholder="Application #"></td>
                    </tr>
                     <tr>
                        <th class="t-basic">Title</th>
                        <td><input type="text" name="name"  value="" class="form-control" placeholder="Title"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Given Name</th>
                        <td><input type="text" name="given_name"  value="" class="form-control" placeholder="Given Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Surname</th>
                        <td><input type="text" class="form-control"  name="surname" value="" placeholder="Surname"></td>
                    </tr>
                     <tr>
                        <th class="t-basic">Chi. Name</th>
                        <td><input type="text" name="chinese_name" value="" class="form-control" placeholder="Chi. Name"></td>
                    </tr>                    
                    <tr>
                        <th class="t-basic">Gender</th>
                        <td>
                            <select name="gender" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Date of Birth</th>
                        <td><input type="text" readonly name="dob" value="" class="form-control datepicker1" placeholder="Date of Birth"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">HKID</th>
                        <td><input type="text" name="hkid" value="" class="form-control" placeholder="HKID"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Passport</th>
                        <td><input type="text" name="passport_no" value="" class="form-control" placeholder="Passport"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Nationality</th>
                        <td>
                            <select name="nationality" class="form-control">
                                <option value="">Select Nationality</option>
                                @if(isset($countries) && !empty($countries))
                                    @foreach($countries as $nationalty)
                                        <option value="{{$nationalty->id}}">{{$nationalty->name}}</option>
                                    @endforeach
                                @endif  
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Study Country</th>
                        <td><select name="study_country" class="form-control">
                                <option value="">Select Country</option>
                                @if(isset($countries) && !empty($countries))
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                @endif  
                            </select></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Mobile No.</th>
                        <td><input type="text" name="mobile_tel_no" value="" class="form-control" placeholder="Mobile No."></td>
                    </tr>
					<tr>
                        <th class="t-basic">Contact Email Address</th>
                        <td><input type="text" name="contact_email" value="" class="form-control" placeholder="Contact Email Address"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Contact Eng. Name</th>
                        <td><input type="text" name="contact_english_name" value="" class="form-control" placeholder="Contact Eng. Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Contact Chi. Name</th>
                        <td><input type="text" name="contact_chinese_name" value="" class="form-control" placeholder="Contact Chi. Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Contact Relation</th>
                        <td><input type="text" name="contact_relationship" value="" class="form-control" placeholder="Contact Relation"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Contact Tel. No.</th>
                        <td><input type="text" name="contact_tel_no" value="" class="form-control" placeholder="Contact Tel. No."></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Email</th>
                        <td><input type="email" class="form-control"  name="email" value="" placeholder="Email">
                        @error('email')
                        <label class="error" for="email">{{$message}}</label>
                        @enderror
                        </td>
                    </tr>                  
                </tbody>
            </table>            
        </div>
    </div>
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Year</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Year</th>
                        <td>
                            <select name="year[]" class="form-select" multiple multiselect-search="true" multiselect-select-all="true">  
                                @if(isset($years) && count($years))  
                                    @foreach($years as $yeardata)
                                        <option value="{{$yeardata->id}}">{{$yeardata->year}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Programme</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Programme</th>
                        <td>
                            <select name="programmes[]" class="form-select" multiple multiselect-search="true" multiselect-select-all="true">  
                                @if(isset($programme) && count($programme))  
                                    @foreach($programme as $programmeData)
                                        <option value="{{$programmeData->id}}">{{$programmeData->programme_code}} / {{$programmeData->programme_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Activation & Status</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Activation</th>
                        <td>
                            <select name="activation" class="form-control">
                                <option value="">Select Activation</option>
                                <option value="1"> Active</option>
                                <option value="0">Inactive</option>  
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Status</th>
                        <td>
                            <select name="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="1">Enabled </option>
                                <option value="0">Disabled</option>  
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="form-btn">
            <button type="submit" class="btn action-btn">Save Changes</button>
        </div>
    </div>
{!! Form::close() !!}

<div class="content-backdrop fade "></div>
    <!-- Content wrapper -->
</div>
<script type="text/javascript">
    
</script>
@endsection
@push('foorterscript')
<script>
    $(document).ready(function () {                
        $('.datepicker1').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true  
        });
        $("#quickForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                ignore: "[]",
                email: {
                    required: true,
                    email:true,
                }, 
                name: {
                    required: true,
                }, 
                surname: {
                    required: true,
                },
                given_name: {
                    required: true,
                },
                status: {
                    required: true,
                },
                activation: {
                    required: true,
                },
                gender: {
                    required: true,
                },
                application_number: {
                    required: true,
                }, 
                country_id: {
                    required: true,
                },
                nationality: {
                    required: true,
                },
                 study_country: {
                    required: true,
                },
                'year[]': "required",
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                email: {
                    required: "Please enter email",
                },
                name: {
                    required: "Please enter name",
                }, 
                surname: {
                    required: "Please enter surname",
                },
                given_name: {
                    required: "Please enter givenname",
                },
                status: {
                    required: "Please select status",
                },
                activation: {
                    required: "Please select activation",
                },
                gender: {
                    required: "Please select gender",
                }, 
                application_number: {
                    required: "Please enter application number",
                }, 
                country_id: {
                    required: "Please select country",
                }, 
                nationality: {
                    required: "Please select nationality",
                },
                study_country: {
                    required: "Please select study country",
                },
                'year[]': "Please select year",
            }
        });
    });
</script>
@endpush

