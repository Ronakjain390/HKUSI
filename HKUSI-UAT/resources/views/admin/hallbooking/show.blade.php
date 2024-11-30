<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Basic Info</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Create Date</th>
                    <td>@if(isset($hallBookingInfo->created_at) && !empty($hallBookingInfo->created_at))
                        {{date('Y-m-d' , strtotime($hallBookingInfo->created_at))}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Create Time</th>
                    <td>@if(isset($hallBookingInfo->created_at) && !empty($hallBookingInfo->created_at))
                        {{date('h:i:s' , strtotime($hallBookingInfo->created_at))}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Year</th>
                    <td>@if(isset($hallBookingInfo->getHallsetting->year) && !empty($hallBookingInfo->getHallsetting->year)) {{$hallBookingInfo->getHallsetting->year}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Booking #</th>
                    <td>@if(isset($hallBookingInfo->booking_number) && !empty($hallBookingInfo->booking_number))
                        #{{$hallBookingInfo->booking_number}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Quota #</th>
                    <td>@if(isset($hallBookingInfo->quota_id) && !empty($hallBookingInfo->quota_id))
                        #{{$hallBookingInfo->quota_id}}@endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Start Date</th>
                    <td>@if(isset($hallBookingInfo->start_date) && !empty($hallBookingInfo->start_date))
                        {{date('Y-m-d',$hallBookingInfo->start_date)}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">End Date</th>
                    <td>@if(isset($hallBookingInfo->end_date) && !empty($hallBookingInfo->end_date))
                        {{date('Y-m-d',$hallBookingInfo->end_date)}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Check-in Date</th>
                    <td>@if(isset($hallBookingInfo->getQuotaDetail->check_in_date) &&
                        !empty($hallBookingInfo->getQuotaDetail->check_in_date))
                        {{date('Y-m-d',$hallBookingInfo->getQuotaDetail->check_in_date)}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Check-in Time</th>
                    <td>@if(isset($hallBookingInfo->getQuotaHallDetail->check_in_time) &&
                        !empty($hallBookingInfo->getQuotaHallDetail->check_in_time))
                        {{date('H:i',$hallBookingInfo->getQuotaHallDetail->check_in_time)}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Check-out Date</th>
                    <td>@if(isset($hallBookingInfo->getQuotaDetail->check_out_date) &&
                        !empty($hallBookingInfo->getQuotaDetail->check_out_date))
                        {{date('Y-m-d',$hallBookingInfo->getQuotaDetail->check_out_date)}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Check-out Time</th>
                    <td>@if(isset($hallBookingInfo->getQuotaHallDetail->check_out_time) &&
                        !empty($hallBookingInfo->getQuotaHallDetail->check_out_time))
                        {{date('H:i',$hallBookingInfo->getQuotaHallDetail->check_out_time)}} @else N/A @endif</td>
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
                    <td>@if(isset($hallBookingInfo->amount) && !empty($hallBookingInfo->amount))
                        {{$hallBookingInfo->amount}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Grouped Records</th>
                    <td>@if(isset($hallBookingInfo->passport_no) && !empty($hallBookingInfo->passport_no))
                        {{$hallBookingInfo->passport_no}} @endif</td>
                </tr>
                @php
                $hall_result_days = (isset($hallBookingInfo->getHallsetting->hall_result_days) &&
                !empty($hallBookingInfo->getHallsetting->hall_result_days))?$hallBookingInfo->getHallsetting->hall_result_days:'0';
                $hall_confirmation_date = $hallBookingInfo->hall_result_date + ($hall_result_days * 86400);

                $hall_payment_days = (isset($hallBookingInfo->getHallsetting->hall_payment_days) &&
                !empty($hallBookingInfo->getHallsetting->hall_payment_days))?$hallBookingInfo->getHallsetting->hall_payment_days:'0';

                $payment_deadline_date = $hallBookingInfo->payment_deadline_date + ($hall_payment_days * 86400);

                @endphp
                <tr>
                    <th class="t-basic">Hall Result Date</th>
                    <td>@if(isset($hallBookingInfo->hall_result_date) && !empty($hallBookingInfo->hall_result_date))
                        {{date('Y-m-d',$hall_confirmation_date)}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Hall Payment Date</th>
                    <td>@if(isset($hallBookingInfo->payment_deadline_date) &&
                        !empty($hallBookingInfo->payment_deadline_date)) {{date('Y-m-d',$payment_deadline_date)}} @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="card custom-card profile-details margin-b-20">
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
                    <td>@if(isset($programmeCode) && !empty($programmeCode)) {{$programmeCode}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Programe Name</th>
                    <td>@if(isset($programmeName) && !empty($programmeName)) {{$programmeName}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Application #</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->application_number) &&
                        !empty($hallBookingInfo->getMemberdata->application_number))
                        {{$hallBookingInfo->getMemberdata->application_number}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Email Address</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->email_address) &&
                        !empty($hallBookingInfo->getMemberdata->email_address))
                        {{$hallBookingInfo->getMemberdata->email_address}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Title</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->title) &&
                        !empty($hallBookingInfo->getMemberdata->title)) {{$hallBookingInfo->getMemberdata->title}}
                        @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Gender</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->gender) &&
                        !empty($hallBookingInfo->getMemberdata->gender)) {{$hallBookingInfo->getMemberdata->gender}}
                        @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Surname</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->surname) &&
                        !empty($hallBookingInfo->getMemberdata->surname)) {{$hallBookingInfo->getMemberdata->surname}}
                        @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Given Name</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->given_name) &&
                        !empty($hallBookingInfo->getMemberdata->given_name))
                        {{$hallBookingInfo->getMemberdata->given_name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Chiness Name</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->chinese_name) &&
                        !empty($hallBookingInfo->getMemberdata->chinese_name))
                        {{$hallBookingInfo->getMemberdata->chinese_name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">HKID</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->hkid_card_no) &&
                        !empty($hallBookingInfo->getMemberdata->hkid_card_no))
                        {{$hallBookingInfo->getMemberdata->hkid_card_no}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Passport No.</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->passport_no) &&
                        !empty($hallBookingInfo->getMemberdata->passport_no))
                        {{$hallBookingInfo->getMemberdata->passport_no}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Nationality</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->nationality) &&
                        !empty($hallBookingInfo->getMemberdata->nationality))
                        {{$hallBookingInfo->getMemberdata->nationality}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Date Of Birth</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->date_of_birth) &&
                        !empty($hallBookingInfo->getMemberdata->date_of_birth))
                        {{date('Y-m-d' , $hallBookingInfo->getMemberdata->date_of_birth)}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Mobile No.</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->mobile_tel_no) &&
                        !empty($hallBookingInfo->getMemberdata->mobile_tel_no))
                        {{$hallBookingInfo->getMemberdata->mobile_tel_no}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Studey Country</th>
                    <td>@if(isset($hallBookingInfo->getMemberdata->study_country) &&
                        !empty($hallBookingInfo->getMemberdata->study_country))
                        {{$hallBookingInfo->getMemberdata->study_country}} @endif</td>
                </tr>
                {{--<tr>
                    <th class="t-basic">Activation</th>
                    <td>@if(isset($hallBookingInfo->status) && !empty($hallBookingInfo->status) && $hallBookingInfo->status == '1') Yes @else No @endif</td>
                </tr>--}}
            </tbody>
        </table>
    </div>
</div>
<!--<div class="card custom-card  profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Status</h6>
    </div>
    <div class="table-details select-table-custom">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Status</th>
                    <td>
                        <select disabled @if(isset($hallBookingInfo->status) && $hallBookingInfo->status == '1')
                            disabled @endif class="form-control" name="status" style="background-color: #fff;width:
                            32%;">
                            <option value="">Select Status</option>
                            <option value="Completed" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status ==
                                'Completed') selected @endif>Completed
                            </option>
                            <option value="Pending" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status ==
                                'Pending') selected @endif>Pending</option>
                            <option value="Accepted" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status ==
                                'Accepted') selected @endif>Accepted</option>
                            <option value="Paid" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status ==
                                'Paid') selected @endif>Paid</option>
                            <option value="Cancelled" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status ==
                                'Cancelled') selected @endif>Cancelled</option>
                            <option value="Updated" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status ==
                                'Updated') selected @endif>Updated</option>
                            <option value="Rejected" @if(isset($hallBookingInfo->status) && $hallBookingInfo->status ==
                                'Rejected') selected @endif>Rejected</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>-->
<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Hall/Room</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Hall #</th>
                    <td>
                        {{$hallBookingInfo->quota_hall_id ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Hall/College Name</th>
                    <td>@if(isset($hallBookingInfo->getQuotaHallDetail->college_name) &&
                        !empty($hallBookingInfo->getQuotaHallDetail->college_name))
                        {{$hallBookingInfo->getQuotaHallDetail->college_name}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Address</th>
                    <td>@if(isset($hallBookingInfo->getQuotaHallDetail->address) &&
                        !empty($hallBookingInfo->getQuotaHallDetail->address))
                        {{$hallBookingInfo->getQuotaHallDetail->address}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Room Type</th>
                    <td>@if(isset($hallBookingInfo->getQuotaHallDetail->room_type) &&
                        !empty($hallBookingInfo->getQuotaHallDetail->room_type))
                        {{$hallBookingInfo->getQuotaHallDetail->room_type}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic" style="width: 200px;">Room #</th>
                    <td>@if(isset($hallBookingInfo->getQuotaRoomDetail->room_code) &&
                        !empty($hallBookingInfo->getQuotaRoomDetail->room_code))
                        #{{$hallBookingInfo->getQuotaRoomDetail->room_code}} @else N/A @endif</td>
                </tr>
                
                
            </tbody>
        </table>
    </div>
</div>
<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Attendance</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic" style="width: 200px;">Actual Check-in Date</th>
                    <td> @if (!empty($hallBookingInfo->getBookingAttendanceInfo->actual_check_in_date)) {{date('Y-m-d',$hallBookingInfo->getBookingAttendanceInfo->actual_check_in_date)}} @else N/A @endif </td>
                </tr>
                <tr>
                    <th class="t-basic">Actual Check-in Time</th>
                    <td> @if (!empty($hallBookingInfo->getBookingAttendanceInfo->actual_check_in_time)) {{date('H:i',$hallBookingInfo->getBookingAttendanceInfo->actual_check_in_time)}} @else N/A @endif </td>
                </tr>
                <tr>
                    <th class="t-basic">Check-in Operator</th>
                    <td> {{ ($hallBookingInfo->getBookingAttendanceInfo->getCheckInOperator->name??'N/A')}} </td>
                </tr>
                <tr>
                    <th class="t-basic">Actual Check-out Date</th>
                    <td> @if (!empty($hallBookingInfo->getBookingAttendanceInfo->actual_check_out_date)) {{date('Y-m-d',$hallBookingInfo->getBookingAttendanceInfo->actual_check_out_date)}} @else N/A @endif </td>
                </tr>
                <tr>
                    <th class="t-basic">Actual Check-out Time</th>
                    <td> @if (!empty($hallBookingInfo->getBookingAttendanceInfo->actual_check_out_time)) {{date('H:i',$hallBookingInfo->getBookingAttendanceInfo->actual_check_out_time)}} @else N/A @endif </td>
                </tr>
                <tr>
                    <th class="t-basic">Check-Out Operator</th>
                    <td> {{ isset($hallBookingInfo->getBookingAttendanceInfo->getCheckOutOperator->name) ? $hallBookingInfo->getBookingAttendanceInfo->getCheckOutOperator->sarname.' '.$hallBookingInfo->getBookingAttendanceInfo->getCheckOutOperator->given_name :'N/A'}} </td>
                </tr>
                <tr>
                    <th class="t-basic">Status</th>
                    <td>
                        {{ $hallBookingInfo->getBookingAttendanceInfo->status?? 'N/A' }}                        
                </tr>
            </tbody>
        </table>
    </div>
</div>