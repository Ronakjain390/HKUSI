@extends('admin.layouts.index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {!! Form::open(array('route' => 'admin.quota-hall.store','method'=>'POST','class'=>'edit-form', 'id'=>'quickForm', 'autocomplete' => 'off','files' =>true)) !!}
    <input type="hidden" name="quote_id" @if($dataType != 'add') value="{{$dataId}}" @endif>
    <input type="hidden" name="type" value="{{$dataType}}">
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Start Date</th>
                        <td>
                             @if(isset($quotaInfo->start_date) && !empty($quotaInfo->start_date)) {{date('Y-m-d' ,$quotaInfo->start_date)}} @endif <input type="hidden" name="start_date" @if(isset($quotaInfo->start_date) && !empty($quotaInfo->start_date)) value="{{date('Y-m-d' ,$quotaInfo->start_date)}}" @endif>
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">End Date</th>
                        <td> @if(isset($quotaInfo->start_date) && !empty($quotaInfo->end_date)) {{date('Y-m-d' ,$quotaInfo->end_date)}} @endif <input type="hidden" name="end_date" @if(isset($quotaInfo->start_date) && !empty($quotaInfo->end_date)) value="{{date('Y-m-d' ,$quotaInfo->end_date)}}" @endif>
                        @if ($message = Session::get('error'))
                        <span class="error">{{ $message }}</span>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Male</th>
                        <td><input type="text" name="male" id="male" onchange="return totalQuota(this.value , 'male')" onkeypress="return isNumber(event);" class="form-control" placeholder="Male"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Female</th>
                        <td><input type="text" name="female" id="female" onchange="return totalQuota(this.value , 'female')" onkeypress="return isNumber(event);" class="form-control" placeholder="Female"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Total Quota</th>
                        <td><span id="totalQuotaSpan"></span> <input type="hidden" readonly name="total_quotas" onkeypress="return isNumber(event);" id="total_quotas" class="form-control" placeholder="Total Quota" ></td>
                    </tr>
                    <tr>
                        <th class="t-basic">College Name</th>
                        <td><input type="text" name="college_name" class="form-control" placeholder="College Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Address</th>
                        <td><input type="text" name="address" class="form-control" placeholder="Address"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Room Type</th>
                        <td>
                        <select class="form-control" name="room_type">
                                <option value="">Select Room Type</option>
                                <option value="Single">Single</option>
                                <option value="Shared">Shared</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Check In Date</th>
                        <td>
                            @if($dataType == 'add')
                                <span id="checkInDateSpan"></span> <input type="hidden" id="checkInDate" name="check_in_date">
                            @else
                                @if(isset($quotaInfo->check_in_date) && !empty($quotaInfo->check_in_date)) {{date('Y-m-d' ,$quotaInfo->check_in_date)}} @endif <input type="hidden" name="check_in_date" @if(isset($quotaInfo->check_in_date) && !empty($quotaInfo->check_in_date)) value="{{$quotaInfo->check_in_date}}" @endif>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Check In Time</th>
                        <td><input type="time" name="check_in_time"  class="form-control"  placeholder="Check In Time"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Check Out Date</th>
                        <td>
                        @if($dataType == 'add')
                            <span id="checkOutDateSpan"></span><input type="hidden" id="checkOutDate" name="check_out_date">
                        @else
                            @if(isset($quotaInfo->check_out_date) && !empty($quotaInfo->check_out_date)) {{date('Y-m-d' ,$quotaInfo->check_out_date)}} @endif <input type="hidden" name="check_out_date" @if(isset($quotaInfo->check_out_date) && !empty($quotaInfo->check_out_date)) value="{{$quotaInfo->check_out_date}}" @endif>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Check Out Time</th>
                        <td><input type="time" name="check_out_time" class="form-control" placeholder="Check Out Time"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">PDF</th>
                        <td><input type="file" name="pdf" accept="application/pdf" class="form-control" ></td>
                    </tr>
                     <tr>
                        <th class="t-basic">Room key Location</th>
                        <td><input type="text" name="room_key_location" class="form-control" placeholder="Room key Location"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Hall Assistant</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Assistant Name</th>
                        <td><input type="text" name="ass_name" class="form-control" placeholder="Assistant Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Assistant Mobile</th>
                        <td><input type="text" name="ass_mobile" class="form-control" onkeypress="return isNumber(event);" placeholder="Assistant Mobile"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Assistant Email</th>
                        <td><input type="text" name="ass_email" class="form-control" placeholder="Assistant Email"></td>
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
                                <option value="1">Release </option>
                                <option value="0">Pending</option>
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
@endsection
@push('foorterscript')
<script>
    $().ready(function () {
        $("#quickForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                male: "required",
                female: {
                    required: true,
                },
                
                college_name: {
                    required: true,
                },
                room_key_location: {
                    required: true,
                },
                address: {
                    required: true,
                },
                room_type: {
                    required: true,
                },
                check_in_time: {
                    required: true,
                },
                check_out_time: "required",
                check_in_date: "required",
                check_out_date: "required",
                pdf: "required",
                ass_name: "required",
                ass_mobile: "required",
                ass_email: "required",
                
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                male: "This field is required",
                female: {
                    required: "This field is required",
                },
                college_name: {
                    required: "Please Enter a college name",
                },
                room_key_location: {
                    required: "Please Enter a room key location",
                },
                address: {
                    required: "Please Enter a address",
                },
                room_type: {
                    required: "Please Enter a room type",
                },
                check_in_time: "Please select check-in time",
                check_out_time: "Please select check-out time",
                check_in_date: "Please select check-in date",
                check_out_date: "Please select check-out date",
                pdf: "Please select a pdf",
                ass_name: "Please enter assistant name",
                ass_mobile: "Please enter assistant mobile",
                ass_email: "Please enter assistant email",
            }
        });
    });

    function totalQuota(val , type){
        if (type == 'female') {
            var femaleValue = val ;
        }else{
            var femaleValue = $('#female').val() ;
        }
        if (type == 'male') {
            var maleValue = val ;
        }else{
            var maleValue = $('#male').val() ;
        }
        if (femaleValue != '' && maleValue != '') {
            var totalVlaue = parseInt(maleValue) + parseInt(femaleValue);
        }else {
            if(femaleValue != ''){
                var totalVlaue = parseInt(femaleValue);
            }else{
                if (maleValue != '') {
                    var totalVlaue = parseInt(maleValue);
                }
            }
        }
        $('#total_quotas').val(totalVlaue);
        $('#totalQuotaSpan').html(totalVlaue);
    }

    $('#start_date').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        @if(isset($quotaInfo->getHallSettingDetail) && !empty($quotaInfo->getHallSettingDetail->start_date))
        startDate:  new Date('{{date("Y-m-d",$quotaInfo->getHallSettingDetail->start_date)}}'), // Set start Date
        @endif
        @if(isset($quotaInfo->getHallSettingDetail) && !empty($quotaInfo->getHallSettingDetail->end_date))
        endDate: new Date('{{date("Y-m-d",$quotaInfo->getHallSettingDetail->end_date)}}'), // Set end Date
        @endif
    }).on('change', function(e){
        var temp = $(this).datepicker('getDate');
        var date = new Date(temp);
        date.setDate(date.getDate() - 1);
        var datechange = date.getFullYear() + '-' +((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate()));
        $("#checkInDate").val(datechange);
        $("#checkInDateSpan").html(datechange);
       
    });
    $('#end_date').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        @if(isset($quotaInfo->getHallSettingDetail) && !empty($quotaInfo->getHallSettingDetail->start_date))
        startDate:  new Date('{{date("Y-m-d",$quotaInfo->getHallSettingDetail->start_date)}}'), // Set start Date
        @endif
        @if(isset($quotaInfo->getHallSettingDetail) && !empty($quotaInfo->getHallSettingDetail->end_date))
        endDate: new Date('{{date("Y-m-d",$quotaInfo->getHallSettingDetail->end_date)}}'), // Set end Date
        @endif
    }).on('change', function(e){
        var temp = $(this).datepicker('getDate');
        var date = new Date(temp);
        date.setDate(date.getDate() + 1);
        var datechange = date.getFullYear() + '-' +((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate()));
        $("#checkOutDate").val(datechange);
        $("#checkOutDateSpan").html(datechange);
       
    });

</script>
@endpush