{!! Form::model($quotaHallInfo, ['method' => 'PATCH','route' => ['admin.quota-hall.update', $quotaHallInfo->id],'id' => 'quickForm','autocomplete' => 'off','class'=>'edit-form','files'=>'true']) !!}
    @php 
        use App\Models\HallBookingInfo;
    @endphp
    <input type="hidden" name="quote_id" value="{{$quotaHallInfo->id}}">
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Create Date</th>
                        <td>@if(isset($quotaHallInfo->created_at) && !empty($quotaHallInfo->created_at)) {{date('Y-m-d' , strtotime($quotaHallInfo->created_at))}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Create Time</th>
                        <td>@if(isset($quotaHallInfo->created_at) && !empty($quotaHallInfo->created_at)) {{date('h:i:s' , strtotime($quotaHallInfo->created_at))}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Qouta #</th>
                        <td>@if(isset($quotaHallInfo->quota_id) && !empty($quotaHallInfo->quota_id)) #{{$quotaHallInfo->quota_id}} @endif</td>
                    </tr> <tr>
                        <th class="t-basic">Start Date</th>
                        <td>
                             @if(isset($quotaHallInfo->start_date) && !empty($quotaHallInfo->start_date)) {{date('Y-m-d' ,$quotaHallInfo->start_date)}} @endif <input type="hidden" name="start_date" @if(isset($quotaHallInfo->start_date) && !empty($quotaHallInfo->start_date)) value="{{date('Y-m-d' ,$quotaHallInfo->start_date)}}" @endif>
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">End Date</th>
                        <td> @if(isset($quotaHallInfo->start_date) && !empty($quotaHallInfo->end_date)) {{date('Y-m-d' ,$quotaHallInfo->end_date)}} @endif <input type="hidden" name="end_date" @if(isset($quotaHallInfo->start_date) && !empty($quotaHallInfo->end_date)) value="{{date('Y-m-d' ,$quotaHallInfo->end_date)}}" @endif>
                        @if ($message = Session::get('error'))
                        <span class="error">{{ $message }}</span>
                        @endif
                        </td>
                    </tr>
                    @php 
                    $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');})->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$quotaHallInfo->quota_id)->where('quota_hall_id',$quotaHallInfo->id)->count();
                    $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');})->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$quotaHallInfo->quota_id)->where('quota_hall_id',$quotaHallInfo->id)->count();
                    @endphp
                    <tr>
                        <th class="t-basic">Male</th>
                        <td><input type="text" onchange="totalQuota(this.value , 'male')" onkeypress="return isNumber(event);" name="male" min="{{$totalGenderMaleBooking}}" id="male" class="form-control" @if(isset($quotaHallInfo->male)) {{$quotaHallInfo->male}}  value="{{$quotaHallInfo->male}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Female</th>
                        <td> <input type="text" name="female" id="female" onchange="totalQuota(this.value , 'female')" onkeypress="return isNumber(event);" min="{{$totalGenderFemaleBooking}}" class="form-control" @if(isset($quotaHallInfo->female)) {{$quotaHallInfo->female}} value="{{$quotaHallInfo->female}}" @endif> </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Total Quota</th>
                        <td>@if(isset($quotaHallInfo->total_quotas) && !empty($quotaHallInfo->total_quotas))<span id="totalQuotas">{{$quotaHallInfo->total_quotas}}</span> <input type="hidden" readonly name="total_quotas" onkeypress="return isNumber(event);" id="total_quotas" class="form-control" placeholder="Total Quota"  value="{{$quotaHallInfo->total_quotas}}"> @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Hall/College</th>
                        <td><input type="text" name="college_name" class="form-control" placeholder="Hall/Collage" @if(isset($quotaHallInfo->college_name) && !empty($quotaHallInfo->college_name)) value="{{$quotaHallInfo->college_name}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Address</th>
                        <td><input type="text" name="address" class="form-control" placeholder="Address" @if(isset($quotaHallInfo->address) && !empty($quotaHallInfo->address)) value="{{$quotaHallInfo->address}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Room Type</th>
                        <td>
                        <select class="form-control" name="room_type">
                                <option value="">Select Room Type</option>
                                <option value="Single" @if(isset($quotaHallInfo->room_type) && $quotaHallInfo->room_type == 'Single') selected @endif >Single</option>
                                <option value="Shared" @if(isset($quotaHallInfo->room_type) && $quotaHallInfo->room_type == 'Shared') selected @endif >Shared</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="t-basic">Check In Date</th>
                        <td>@if(isset($quotaHallInfo->check_in_date) && !empty($quotaHallInfo->check_in_date)) {{date('Y-m-d',$quotaHallInfo->check_in_date)}} @else  @endif<input type="hidden" name="check_in_date" readonly @if(isset($quotaHallInfo->check_in_date) && !empty($quotaHallInfo->check_in_date)) value="{{$quotaHallInfo->check_in_date}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Check In Time</th>
                        <td><input type="time" name="check_in_time"  class="form-control " @if(isset($quotaHallInfo->check_in_time) && !empty($quotaHallInfo->check_in_time)) value="{{date('H:i',$quotaHallInfo->check_in_time)}}" @endif></td>
                    </tr> 
                    <tr>
                        <th class="t-basic">Check Out Date</th> 
                        <td>@if(isset($quotaHallInfo->check_out_date) && !empty($quotaHallInfo->check_out_date)) {{date('Y-m-d',$quotaHallInfo->check_out_date)}} @endif<input type="hidden" name="check_out_date" readonly  @if(isset($quotaHallInfo->check_out_date) && !empty($quotaHallInfo->check_out_date)) value="{{$quotaHallInfo->check_out_date}}" @endif></td>
                    </tr>    
                    <tr>
                        <th class="t-basic">Check Out Time</th>
                        <td><input type="time" name="check_out_time"  class="form-control " @if(isset($quotaHallInfo->check_out_time) && !empty($quotaHallInfo->check_out_time)) value="{{date('H:i',$quotaHallInfo->check_out_time)}}" @else  @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">PDF</th>
                        <td>
                            @if(isset($quotaHallInfo->pdf) && !empty($quotaHallInfo->pdf) && Storage::disk($DISK_NAME)->exists($quotaHallInfo->pdf))
                                <a style="color:blue;"  target="_blank" href="{{asset(Storage::url($quotaHallInfo->pdf))}}">Download</a>
                            @endif
                            <input type="file" name="pdf" accept="application/pdf" class="form-control">
                        </td>
                    </tr>
                     <tr>
                        <th class="t-basic">Room key Location</th>
                        <td><input type="text" name="room_key_location" class="form-control" placeholder="Room key Location" @if(isset($quotaHallInfo->room_key_location) && !empty($quotaHallInfo->room_key_location)) value="{{$quotaHallInfo->room_key_location}}" @endif></td>
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
                        <td><input type="text" name="ass_name" class="form-control" placeholder="Assistant Name" @if(isset($quotaHallInfo->ass_name) && !empty($quotaHallInfo->ass_name)) value="{{$quotaHallInfo->ass_name}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Assistant Mobile</th>
                        <td><input type="text" onkeypress="return isNumber(event);" name="ass_mobile" class="form-control" placeholder="Assistant Mobile" @if(isset($quotaHallInfo->ass_mobile) && !empty($quotaHallInfo->ass_mobile)) value="{{$quotaHallInfo->ass_mobile}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Assistant Email</th>
                        <td><input type="text" name="ass_email" class="form-control" placeholder="Assistant Email" @if(isset($quotaHallInfo->ass_email) && !empty($quotaHallInfo->ass_email)) value="{{$quotaHallInfo->ass_email}}" @endif></td>
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
                            <select name="programmes[]" class="form-select"  multiple multiselect-search="true" multiselect-select-all="true">  
                                @if(isset($programme) && count($programme))  
                                    @foreach($programme as $programmeData)
                                        <option value="{{$programmeData->id}}" @if(isset($quotaProgramme) && !empty($quotaProgramme) && in_array($programmeData->id,$quotaProgramme)) Selected @endif>{{$programmeData->programme_code}} / {{$programmeData->programme_name}}</option>
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
                            <select @if(isset($quotaHallInfo->status) && $quotaHallInfo->status == '1') disabled @endif class="form-control" name="status" style="background-color: #fff;">
                                <option value="">Select Status</option>
                                <option value="1" @if(isset($quotaHallInfo->status) && $quotaHallInfo->status == '1') selected @endif >Release</option>
                                <option value="0" @if(isset($quotaHallInfo->status) && $quotaHallInfo->status == '0') selected @endif >Pending</option>
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
            <button type="submit" class="btn cancel-btn">Delete</button>
        </div>
    </div>
{!! Form::close() !!}

@push('foorterscript')
<script>
    $(document).ready(function () {
        $("#quickForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                male: {
                    required: true,
                },
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
                ass_name: "required",
                ass_mobile: "required",
                ass_email: "required",
                
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                male: {
                    required: "This field is required",
                },female: {
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
        $('#totalQuotas').html(totalVlaue);
    }
    $('#start_date,#end_date').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        @if(isset($quotaInfo->getHallSettingDetail) && !empty($quotaInfo->getHallSettingDetail->start_date))
        startDate:  new Date('{{date("Y-m-d",$quotaInfo->getHallSettingDetail->start_date)}}'), // Set start Date
        @endif
        @if(isset($quotaInfo->getHallSettingDetail) && !empty($quotaInfo->getHallSettingDetail->end_date))
        endDate: new Date('{{date("Y-m-d",$quotaInfo->getHallSettingDetail->end_date)}}'), // Set end Date
        @endif
    });
</script>
@endpush
