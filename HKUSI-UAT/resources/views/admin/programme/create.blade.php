@extends('admin.layouts.index')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
            {!! Form::open(array('route' => 'admin.programme-setting.store','method'=>'POST','class'=>'edit-form','autocomplete' => 'off','files' =>false,'id'=>'quickForm')) !!}
            <div class="card custom-card profile-details">
                <div class="basic-details">
                    <h6 class="card-heading">Basic Info</h6>
                </div>
                <div class="table-responsive table-details">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Programme Name</th> 
                                <td><input type="text" name="programme_name" required class="form-control" placeholder="Programme Name"></td>
                            </tr>
                            <tr>
                                <th class="t-basic">Programme Code</th>
                                <td><input type="text" name="programme_code" required class="form-control" placeholder="Programme Code">
                                @error('programme_code')
                                <label class="error" for="programme_code">{{$message}}</label>
                                @enderror
                                </td>
                            </tr> 
                            <tr>
                                <th class="t-basic">Start Date</th>
                                <td><input type="text" readonly name="start_date" required class="form-control datepicker" placeholder="Start Date"></td>
                            </tr> 
                            <tr>
                                <th class="t-basic">End Date</th>
                                <td><input type="text" name="end_date" readonly required class="form-control datepicker" placeholder="End Date"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card custom-card">
                <div class="basic-details">
                    <h6 class="card-heading">Year</h6>
                </div>
            </tr>
                <div class="table-details select-table-custom">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Year</th>
                                <td>
                                    <select name="year" id="getyearmember" class="form-select years"  style="width: 21%;">
                                        @if(isset($years) && count($years))
                                            <option value="">Select Year</option>
                                            @foreach($years as $yeardata)
                                            <option value="{{$yeardata->id}}" >{{$yeardata->year}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- <div class="card custom-card">
                <div class="basic-details">
                    <h6 class="card-heading">Member</h6>
                </div>
                <div class="table-details select-table-custom" >
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Member</th>
                                <td class="programme">
                                    <select name="member[]" class="form-select memberSelect" multiple multiselect-search="true" multiselect-select-all="true">
                                        @if(isset($members) && !empty($members))
                                            @foreach($members as $memberData)
                                                <option value="{{$memberData->id}}">{{$memberData->given_name . " / " .$memberData->application_number}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> --}}
            {{--<div class="card custom-card">
                <div class="basic-details">
                    <h6 class="card-heading">Member</h6>
                </div>
                <div class="table-details select-table-custom" >
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Member</th>
                                <td>
                                    <select name="member[]" id="members" class="form-select" multiple multiselect-search="true" multiselect-select-all="true">
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>--}}
            <div class="card custom-card">
                <div class="basic-details">
                    <h6 class="card-heading">Status</h6>
                </div>
                <div class="table-details select-table-custom">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Status</th>
                                <td>
                                    <select class="form-control" name="status">
                                        <option value="">Select Status</option>
                                        <option value="1">Enabled</option>
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
                    <button type="reset" class="btn cancel-btn">Delete</button>
                </div>
            </div>
        {!! Form::close() !!}
        <!-- / Content -->
        <div class="content-backdrop fade "></div>
        <!-- Content wrapper -->
    </div>
    <!-- / Layout page -->
@endsection
@push('foorterscript')
<script>
    $().ready(function () {
        $("#quickForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                programme_name: "required",
                programme_code: {
                    required: true,
                },
                start_date: {
                    required: true,
                },
                end_date: {
                    required: true,
                },
                'member[]': {
                    required: true,
                },
                status: {
                    required: true,
                },
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                programme_name: "Please enter a programme name",
                programme_code: {
                    required: "Please enter a programme code",
                },
                start_date: "Please Choose a start date",
                end_date: {
                    required: "Please Choose a end date",
                },
                'member[]': {
                    required: "Please select any member",
                },
                status: {
                    required: "Please select a status",
                },
            }

             
             
        });
    });


    function getfiltermemberdata(id){
        if (id != '') {
            $.ajax({
                url: "{{route('admin.programe.getfilterdata')}}",
                type: "GET",
                data: {
                    'id': id,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data){
                    $('.memberSelect').empty();
                    $('.memberSelect').html('<option value="">Select Member</option>');
                    $.each(data, function (key, value) {
                        $(".memberSelect").append('<option value="' + value.id + '">' + value.given_name + '</option>');
                        $(".multiselect-dropdown-list").append('<div><input type="checkbox"><label>' + value.given_name + '</label></div>');
                    });
                }
          });
        }
    }

</script>
@endpush