<div class="card custom-card profile-details margin-b-20">
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
                    <td>@if(isset($quotaHallInfo->quota_id) && !empty($quotaHallInfo->quota_id)) # {{$quotaHallInfo->quota_id}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Hall #</th>
                    <td>@if(isset($quotaHallInfo->id) && !empty($quotaHallInfo->id)) # {{$quotaHallInfo->id}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Start Date</th>
                    <td>@if(isset($quotaHallInfo->start_date) && !empty($quotaHallInfo->start_date)) {{date('Y-m-d',$quotaHallInfo->start_date)}} @endif</td>
                </tr>
                 <tr>
                    <th class="t-basic">End Date</th>
                    <td>@if(isset($quotaHallInfo->end_date) && !empty($quotaHallInfo->end_date)) {{date('Y-m-d',$quotaHallInfo->end_date)}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Male</th>
                    <td>@if(isset($quotaHallInfo->male)) {{$quotaHallInfo->male}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Female</th>
                    <td>@if(isset($quotaHallInfo->female)) {{$quotaHallInfo->female}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Total Qoutas</th>
                    <td>@if(isset($quotaHallInfo->total_quotas)) {{$quotaHallInfo->total_quotas}} @endif</td>
                </tr> 
                <tr>
                    <th class="t-basic">Hall/Collage</th>
                    <td>@if(isset($quotaHallInfo->college_name) && !empty($quotaHallInfo->college_name)) {{$quotaHallInfo->college_name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Address</th>
                    <td>@if(isset($quotaHallInfo->address) && !empty($quotaHallInfo->address)) {{$quotaHallInfo->address}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Room Type</th>
                    <td>@if(isset($quotaHallInfo->room_type) && !empty($quotaHallInfo->room_type)) {{$quotaHallInfo->room_type}} @endif</td>
                </tr>
                
                <tr>
                    <th class="t-basic">Check-In-Date</th>
                    <td>@if(isset($quotaHallInfo->check_in_date) && !empty($quotaHallInfo->check_in_date)) {{date('Y-m-d',$quotaHallInfo->check_in_date)}} @else  @endif</td>
                </tr>
                
                <tr>
                    <th class="t-basic">Check-In-Time</th>
                   <td>@if(isset($quotaHallInfo->check_in_time) && !empty($quotaHallInfo->check_in_time)) {{date('H:i ',$quotaHallInfo->check_in_time)}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Check-out-Date</th>
                 <td>@if(isset($quotaHallInfo->check_out_date) && !empty($quotaHallInfo->check_out_date)) {{date('Y-m-d',$quotaHallInfo->check_out_date)}} @else  @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Check-out-Time</th>
                  <td>@if(isset($quotaHallInfo->check_out_time) && !empty($quotaHallInfo->check_out_time)) {{date('H:i ',$quotaHallInfo->check_out_time)}} @endif</td>
                </tr>
                @if(isset($quotaHallInfo->pdf) && !empty($quotaHallInfo->pdf) && Storage::disk($DISK_NAME)->exists($quotaHallInfo->pdf))
                <tr>
                    <th class="t-basic">PDF</th>
                    <td><a target="_blank" href="{{asset(Storage::url($quotaHallInfo->pdf))}}" download="QuotaHall">Download</a></td>
                </tr>
                @endif
               
            </tbody>
        </table>
    </div>
</div>

<div class="card custom-card margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Hall Assistant</h6>
    </div>
    <div class="table-details select-table-custom">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Assistant Name</th>
                    <td>@if(isset($quotaHallInfo->ass_name) && !empty($quotaHallInfo->ass_name)) {{$quotaHallInfo->ass_name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Assistant Mobile</th>
                    <td>@if(isset($quotaHallInfo->ass_mobile) && !empty($quotaHallInfo->ass_mobile)) {{$quotaHallInfo->ass_mobile}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Assistant Email</th>
                    <td>@if(isset($quotaHallInfo->ass_email) && !empty($quotaHallInfo->ass_email)) {{$quotaHallInfo->ass_email}} @endif</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
{{-- 
<div class="card custom-card">
    <div class="basic-details">
        <h6 class="card-heading">Status</h6>
    </div>
    <div class="table-details select-table-custom">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Status</th>
                    <td>@if(isset($quotaHallInfo->status) && $quotaHallInfo->status == '1') Release @else Pending @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div> --}}