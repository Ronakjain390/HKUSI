{!! Form::model($hallBookingInfo, ['method' => 'PATCH','route' => ['admin.hallbooking.update', $hallBookingInfo->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
    <input type="hidden" name="user_id" value="{{$hallBookingInfo->user_id}}">
<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Basic Info</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                 <tr>
                    <th class="t-basic">Create Date</th>
                    <td>@if(isset($hallBookingInfo->created_at) && !empty($hallBookingInfo->created_at)){{date('Y-m-d' , strtotime($hallBookingInfo->created_at))}}@endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Create Time</th>
                    <td>@if(isset($hallBookingInfo->created_at) && !empty($hallBookingInfo->created_at)){{date('h:i:s' , strtotime($hallBookingInfo->created_at))}}@endif</td>
                </tr>
                 <tr>
                    <th class="t-basic">Year</th>
                    <td>@if(isset($hallBookingInfo->getHallsetting->year) && !empty($hallBookingInfo->getHallsetting->year)) {{$hallBookingInfo->getHallsetting->year}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Booking #</th>
                    <td>@if(isset($hallBookingInfo->booking_number) && !empty($hallBookingInfo->booking_number)) #{{$hallBookingInfo->booking_number}}@endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Quota #</th>
                    <td>@if(isset($hallBookingInfo->quota_id) && !empty($hallBookingInfo->quota_id)) #{{$hallBookingInfo->quota_id}}@endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Start Date</th>
                    <td>
                        @if(isset($hallBookingInfo->start_date) && !empty($hallBookingInfo->start_date)) {{date('Y-m-d',$hallBookingInfo->start_date)}}@endif
                    </td>
                    
                </tr>
                <tr>
                    <th class="t-basic">End Date</th>
                    <td>
                        @if(isset($hallBookingInfo->end_date) && !empty($hallBookingInfo->end_date)) {{date('Y-m-d',$hallBookingInfo->end_date)}}@endif
                    </td>
                </tr>
                <tr>
                    <th class="t-basic">Check-in Date</th>
                    <td>
                        @if(isset($hallBookingInfo->getQuotaDetail->check_in_date) && !empty($hallBookingInfo->getQuotaDetail->check_in_date)) {{date('Y-m-d',$hallBookingInfo->getQuotaDetail->check_in_date)}}@endif
                    </td>
                </tr>
                <tr>
                    <th class="t-basic">Check-in Time</th>
                    <td>
                        @if(isset($hallBookingInfo->getQuotaHallDetail->check_in_time) && !empty($hallBookingInfo->getQuotaHallDetail->check_in_time)) {{date('H:i',$hallBookingInfo->getQuotaHallDetail->check_in_time)}} @else N/A @endif
                    </td>
                </tr>
                <tr>
                    <th class="t-basic">Check-out Date</th>
                    <td>
                        @if(isset($hallBookingInfo->getQuotaDetail->check_out_date) && !empty($hallBookingInfo->getQuotaDetail->check_out_date)) {{date('Y-m-d',$hallBookingInfo->getQuotaDetail->check_out_date)}}@endif
                    </td>
                </tr>
                <tr>
                    <th class="t-basic">Check-out Time</th>
                    <td>
                        @if(isset($hallBookingInfo->getQuotaHallDetail->check_out_time) && !empty($hallBookingInfo->getQuotaHallDetail->check_out_time)) {{date('H:i',$hallBookingInfo->getQuotaHallDetail->check_out_time)}} @else N/A @endif
                    </td>
                </tr>
                <tr>
                    <th class="t-basic">Nights</th>
                    <td>
                        @php 
                            $days = 0;
                            $date1 = $hallBookingInfo->getQuotaDetail->check_in_date - 86400;
                            $date2 = $hallBookingInfo->getQuotaDetail->check_out_date;
                            $days = (int)(($date2 - $date1)/86400);
                        @endphp
                        @if(isset($days) && !empty($days)) {{$days - 1}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Amount</th>
                    <td>
                        @if(isset($hallBookingInfo->amount) && !empty($hallBookingInfo->amount)) {{$hallBookingInfo->amount}}@endif
                </tr>
                <!--<tr>
                    <th class="t-basic">Grouped Records</th>
                    <td> <input type="text" name="passport_no"  value="@if(isset($hallBookingInfo->passport_no) && !empty($hallBookingInfo->passport_no)) #{{$hallBookingInfo->passport_no}}@endif" class="form-control" placeholder="Grouped Records"></td>
                </tr>-->
            </tbody>
        </table>
    </div>
</div>
<div class="card custom-card profile-details">
    <div class="basic-details">
        <h6 class="card-heading">Member Info</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                @php 
                    $programmeCode = $programmeName = '';
                    if (isset($hallBookingInfo->booking_type) && $hallBookingInfo->booking_type == 'g') {
                        if (isset($hallBookingInfo->getGroupHallInfo) && count($hallBookingInfo->getGroupHallInfo)) {
                            foreach ($hallBookingInfo->getGroupHallInfo as $key => $groupHallInfo) {
                                if (!empty($programmeCode)) {
                                    $programmeCode .= " , " . $groupHallInfo->programme_code;
                                }else{
                                    $programmeCode .= $groupHallInfo->programme_code;
                                }
                                if (!empty($programmeName)) {
                                    $programmeName .= " , " . $groupHallInfo->getProgrammeDetail->programme_name;
                                }else{
                                    $programmeName .= $groupHallInfo->getProgrammeDetail->programme_name;
                                }
                            }
                        }
                    }else{
                        $programmeCode = $hallBookingInfo->programme_code;
                        $programmeName = $hallBookingInfo->getProgrammeDetail->programme_name;
                    }
                @endphp
                <tr>
                    <th class="t-basic">Programe Code</th>
                    <td>@if(isset($programmeCode) && !empty($programmeCode)){{$programmeCode}}@endif</td>                    
                </tr>
                <tr>
                    <th class="t-basic">Programe Name</th>
                    <td>@if(isset($programmeName) && !empty($programmeName)) {{$programmeName}} @endif</td>
                    
                </tr>
                <tr>
                    <th class="t-basic">Application #</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->application_number) && !empty($hallBookingInfo->getMemberdata->application_number)){{$hallBookingInfo->getMemberdata->application_number}}@endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Email Address</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->email_address) && !empty($hallBookingInfo->getMemberdata->email_address)){{$hallBookingInfo->getMemberdata->email_address}}@endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Title</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->title) && !empty($hallBookingInfo->getMemberdata->title)){{$hallBookingInfo->getMemberdata->title}}@endif</td>                   
                </tr>
                <tr>
                    <th class="t-basic">Gender</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->gender) && !empty($hallBookingInfo->getMemberdata->gender)){{$hallBookingInfo->getMemberdata->gender}}@endif</td>                    
                </tr>
                <tr>
                    <th class="t-basic">Surname</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->surname) && !empty($hallBookingInfo->getMemberdata->surname)){{$hallBookingInfo->getMemberdata->surname}}@endif</td>                   
                </tr>
                <tr>
                    <th class="t-basic">Given Name</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->given_name) && !empty($hallBookingInfo->getMemberdata->given_name)){{$hallBookingInfo->getMemberdata->given_name}}@endif</td>                   
                </tr>
                <tr>
                    <th class="t-basic">Chiness Name</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->chinese_name) && !empty($hallBookingInfo->getMemberdata->chinese_name)){{$hallBookingInfo->getMemberdata->chinese_name}}@endif</td>
                </tr>
                <tr>
                    <th class="t-basic">HKID</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->hkid_card_no) && !empty($hallBookingInfo->getMemberdata->hkid_card_no)){{$hallBookingInfo->getMemberdata->hkid_card_no}}@endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Passport No.</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->passport_no) && !empty($hallBookingInfo->getMemberdata->passport_no)){{$hallBookingInfo->getMemberdata->passport_no}}@endif</td>                    
                </tr>
                <tr>
                    <th class="t-basic">Nationality</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->nationality) && !empty($hallBookingInfo->getMemberdata->nationality)){{$hallBookingInfo->getMemberdata->nationality}}@endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Date Of Birth</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->date_of_birth) && !empty($hallBookingInfo->getMemberdata->date_of_birth)){{date('Y-m-d' , $hallBookingInfo->getMemberdata->date_of_birth)}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Mobile No.</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->mobile_tel_no) && !empty($hallBookingInfo->getMemberdata->mobile_tel_no)){{$hallBookingInfo->getMemberdata->mobile_tel_no}}@endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Study Country</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->study_country) && !empty($hallBookingInfo->getMemberdata->study_country)){{$hallBookingInfo->getMemberdata->study_country}}@endif</td>
                </tr>
                {{--<tr>
                    <th class="t-basic">Activation</th>
                    <td>@if(isset($hallBookingInfo->status) && !empty($hallBookingInfo->status) && $hallBookingInfo->status == '1') Yes @else No @endif</td>
                </tr>--}}
            </tbody>
        </table>
    </div>
</div>
<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Hall/Room</h6>
    </div>
    <div class="table-responsive table-details">
        <div style="width:40%;">
			<table class="table">
				<tbody>
					<tr>
						<th class="t-basic">Hall/College Name</th>
						<td> 
							<select class="form-select quota_hall_id" id="selectmemberid1" name="quota_hall_id">
								<option value="">Select Hall/College Name</option>
								@if(isset($allHall) && !empty($allHall))
									@foreach($allHall as $hall)
										<option value="{{$hall->id}}" @if(isset($hallBookingInfo->quota_hall_id) && $hallBookingInfo->quota_hall_id == $hall->id) selected @endif>{{$hall->college_name}}</option>
									@endforeach
								@endif
							</select>
						</td>
					</tr>
					<tr>
						<th class="t-basic">Address</th>
						<td><span class="quotaHallAddress">@if(isset($hallBookingInfo->getQuotaHallDetail->address) && !empty($hallBookingInfo->getQuotaHallDetail->address))
							{{$hallBookingInfo->getQuotaHallDetail->address}} @else N/A @endif
						</span>
						</td>
					</tr>
					<tr>
						<th class="t-basic">Room Type</th>
						<td><span class="quotaHallRoomType">@if(isset($hallBookingInfo->getQuotaHallDetail->room_type) &&
							!empty($hallBookingInfo->getQuotaHallDetail->room_type))
							{{$hallBookingInfo->getQuotaHallDetail->room_type}} @else N/A @endif</span>
						</td>
					</tr>
					<tr>
						<th class="t-basic">Room #</th> 
						<td> 
							<select class="form-select" id="selectmemberid" name="quota_room_id">
								<option value="">Select Room</option>
								@if(isset($allHallQuota) && !empty($allHallQuota))
									@foreach($allHallQuota as $collages)
										<option value="{{$collages->id}}" @if(isset($hallBookingInfo->quota_room_id) && $hallBookingInfo->quota_room_id == $collages->id) selected @endif>{{$collages->room_code}}</option>
									@endforeach
								@endif
							</select>
							@error('collages')  
								<label style="color:red;" >{{ $message }}</label> 
							@enderror
							@if($message = Session::get('ageError'))
								<label style="color:red;" >{{ $message }}</label> 
							@endif
						</td>
					</tr>
				</tbody>
			</table>
        </div>
    </div>
</div>
<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Attendance</h6>
    </div>
    <div class="table-responsive table-details">
          <div style="width:40%;">
        <table class="table">
            <tbody>
                 <tr>
                    <th class="t-basic"style="width: 200px;">Actual Check-in Date</th>
                    <td><input type="text" readonly name="actual_check_in_date"  class="form-control datepicker" placeholder="Actual Check-in Date" @if(isset($hallBookingInfo->getBookingAttendanceInfo->actual_check_in_date) && !empty($hallBookingInfo->getBookingAttendanceInfo->actual_check_in_date)) value="{{date('Y-m-d',$hallBookingInfo->getBookingAttendanceInfo->actual_check_in_date)}}" @endif>
                    </td>
                </tr> 
                <tr>
                    <th class="t-basic"style="width: 200px;">Actual Check-in Time</th>
                    <td><input type="time"  name="actual_check_in_time"  class="form-control" placeholder="Actual Check-in Time"@if(isset($hallBookingInfo->getBookingAttendanceInfo->actual_check_in_time) && !empty($hallBookingInfo->getBookingAttendanceInfo->actual_check_in_time)) value="{{date('H:i',$hallBookingInfo->getBookingAttendanceInfo->actual_check_in_time)}}" @endif>
                    </td>
                </tr> 
                    <tr>
                        <th class="t-basic">Check-in Operator</th> 
                        <td> 
                            <select class="form-select" id="selectmemberid2" name="check_in_operator">
                                <option value="">Select Check-in Operator</option>
                                @if(isset($user) && !empty($user))
                                    @foreach($user as $operaterIn)
                                        <option value="{{$operaterIn->id}}"@if(isset($hallBookingInfo->getBookingAttendanceInfo->check_in_operator) && $hallBookingInfo->getBookingAttendanceInfo->check_in_operator == $operaterIn->id) selected @endif>{{$operaterIn->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr>
                <!-- <tr>
                    <th class="t-basic"style="width: 200px;">Check-in Operator</th>
                    <td><input type="text"  name="check_in_operator"  class="form-control" placeholder="Check-in Operator"@if(isset($hallBookingInfo->getBookingAttendanceInfo->getCheckInOperator->name) && !empty($hallBookingInfo->getBookingAttendanceInfo->getCheckInOperator->name)) value="{{$hallBookingInfo->getBookingAttendanceInfo->getCheckInOperator->name}}" @endif>
                    </td>
                </tr>  -->
                 <tr>
                    <th class="t-basic"style="width: 200px;">Actual Check-out Date</th>
                    <td><input type="text" readonly name="actual_check_out_date"  class="form-control datepicker" placeholder="Actual Check-out Date" @if(isset($hallBookingInfo->getBookingAttendanceInfo->actual_check_out_date) && !empty($hallBookingInfo->getBookingAttendanceInfo->actual_check_out_date)) value="{{date('Y-m-d',$hallBookingInfo->getBookingAttendanceInfo->actual_check_out_date)}}" @endif>
                    </td>
                </tr> 
                 <tr>
                    <th class="t-basic"style="width: 200px;">Actual Check-out Time</th>
                    <td><input type="time"  name="actual_check_out_time"  class="form-control " placeholder="Actual Check-out Time" @if(isset($hallBookingInfo->getBookingAttendanceInfo->actual_check_out_time) && !empty($hallBookingInfo->getBookingAttendanceInfo->actual_check_out_time)) value="{{date('H:i',$hallBookingInfo->getBookingAttendanceInfo->actual_check_out_time)}}" @endif>
                    </td>
                </tr>
                 <tr>
                        <th class="t-basic">Check-out Operator</th> 
                        <td> 
                            <select class="form-select" id="selectmemberid3" name="check_out_operator">
                                <option value="">Select Check-out Operator</option>
                                @if(isset($user) && !empty($user))
                                    @foreach($user as $outOperator)
                                        <option value="{{$outOperator->id}}" @if(isset($hallBookingInfo->getBookingAttendanceInfo->check_out_operator) && $hallBookingInfo->getBookingAttendanceInfo->check_out_operator == $outOperator->id) selected @endif>{{$outOperator->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr> 
                    <tr>
                        <th class="t-basic">Status</th> 
                        <td> 
                            <select class="form-select"  name="attendence_status">
                                <option value="">Select status</option>
                                <option value="Check-in" @if(isset($hallBookingInfo->getBookingAttendanceInfo->status) && $hallBookingInfo->getBookingAttendanceInfo->status == 'Check-in') selected @endif>Check-in</option>
                                <option value="Check-out" @if(isset($hallBookingInfo->getBookingAttendanceInfo->status) && $hallBookingInfo->getBookingAttendanceInfo->status == 'Check-out') selected @endif>Check-out</option>
                            </select>
                        </td>
                    </tr>
            </tbody>
        </table>
        </div>
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
                        <select @if(isset($hallBookingInfo->status) && $hallBookingInfo->status == '1') disabled @endif class="form-control" name="status" style="background-color: #fff;">
                            <option value="">Select Status</option>
                            <option value="Completed" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status == 'Completed') selected @endif>Completed
                            </option>
                            <option value="Pending" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status == 'Pending') selected @endif>Pending</option>
                            <option value="Accepted" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status == 'Accepted') selected @endif>Accepted</option>
                            <option value="Paid" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status == 'Paid') selected @endif>Paid</option>
                            <option value="Cancelled" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status == 'Cancelled') selected @endif>Cancelled</option>
                            <option value="Updated" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status == 'Updated') selected @endif>Updated</option>
                            <option value="Rejected" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status == 'Rejected') selected @endif>Rejected</option>
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
{!!Form::close()!!}

@push('foorterscript')
<script>
    $(document).ready(function () {
        $("#quickForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                status: {
                    required: true,
                },
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                status: {
                    required: "Please select status",
                },
            }
        });
		
		$('.quota_hall_id').on('change', function () {
			var id = $(this).val();
			if (id != '') {
				$.ajax({
					url: "{{route('admin.quotahall.getquotahalldetails')}}",
					type: "GET",
					data: {
						'id': id,
						_token: '{{csrf_token()}}'
					},
					dataType: 'json',
					success: function(data){
						$('.quotaHallAddress').html(data.address);
						$('.quotaHallRoomType').html(data.room_type);
					}
			  });
			}
        });
    
	});
    $('#selectmemberid1').select2({});
    $('#selectmemberid2').select2({});
    $('#selectmemberid3').select2({});
	$('#selectmemberid').select2({});
</script>
@endpush


