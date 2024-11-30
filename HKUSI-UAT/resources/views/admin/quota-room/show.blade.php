<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Basic Info</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Create Date</th>
                    <td>@if(isset($quotaRoomInfo->created_at) && !empty($quotaRoomInfo->created_at)) {{date('Y-m-d' , strtotime($quotaRoomInfo->created_at))}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Create Time</th>
                    <td>@if(isset($quotaRoomInfo->created_at) && !empty($quotaRoomInfo->created_at)) {{date('h:i:s' , strtotime($quotaRoomInfo->created_at))}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic" style="width: 200px;">Room #</th>
                    <td>@if(isset($quotaRoomInfo->room_code) && !empty($quotaRoomInfo->room_code)) #{{$quotaRoomInfo->room_code}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Hall/College Name</th>
                    <td>@if(isset($quotaRoomInfo->college_name) && !empty($quotaRoomInfo->college_name)) {{$quotaRoomInfo->college_name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Start Date</th>
                    <td>@if(isset($quotaRoomInfo->getQuotaHallDetail->start_date) && !empty($quotaRoomInfo->getQuotaHallDetail->start_date)) {{date('Y-m-d',$quotaRoomInfo->getQuotaHallDetail->start_date)}} @endif</td>
                </tr>
                 <tr>
                    <th class="t-basic">End Date</th>
                    <td>@if(isset($quotaRoomInfo->getQuotaHallDetail->end_date) && !empty($quotaRoomInfo->getQuotaHallDetail->end_date)) {{date('Y-m-d',$quotaRoomInfo->getQuotaHallDetail->end_date)}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Gender</th>
                    <td>@if(isset($quotaRoomInfo->gender) && !empty($quotaRoomInfo->gender)) {{$quotaRoomInfo->gender}} @endif</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- <div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Attendance</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic" style="width: 200px;">Actual Check-in Date</th>
                    <td>N/A</td>
                </tr>
                <tr>
                    <th class="t-basic">Actual Check-in Time</th>
                    <td>N/A</td>
                </tr>
                <tr>
                    <th class="t-basic">Check-in Operater</th>
                    <td>N/A</td>
                </tr>
                 <tr>
                    <th class="t-basic">Actual Check-out Date</th>
                    <td>N/A</td>
                </tr>
                <tr>
                    <th class="t-basic">Actual Check-out Time</th>
                    <td>N/A</td>
                </tr>
                <tr>
                    <th class="t-basic">Check-Out Operater</th>
                    <td>N/A</td>
                </tr>
            </tbody>
        </table>
    </div>
</div> -->