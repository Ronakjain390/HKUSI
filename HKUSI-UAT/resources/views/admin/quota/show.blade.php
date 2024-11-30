<form >
    <div class="card custom-card profile-details margin-b-20">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table ">
                <tbody>
                    <tr>
                        <th class="t-basic">Create Date</th>
                        <td>@if(isset($quotaInfo->created_at) && !empty($quotaInfo->created_at)) {{date('Y-m-d' , strtotime($quotaInfo->created_at))}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Create Time</th>
                        <td>@if(isset($quotaInfo->created_at) && !empty($quotaInfo->created_at)) {{date('h:i:s' , strtotime($quotaInfo->created_at))}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Quota #</th>
                        <td>@if(isset($quotaInfo->id) && !empty($quotaInfo->id)) #{{$quotaInfo->id}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Start Date</th>
                        <td>@if(isset($quotaInfo->start_date) && !empty($quotaInfo->start_date)) {{date('Y-m-d' , $quotaInfo->start_date)}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">End Date</th>
                        <td>@if(isset($quotaInfo->end_date) && !empty($quotaInfo->end_date)) {{date('Y-m-d' , $quotaInfo->end_date)}} @endif</td>
                    </tr> 
                    <tr>
                        <th class="t-basic">Check-in Date</th>
                        <td>@if(isset($quotaInfo->check_in_date) && !empty($quotaInfo->check_in_date)) {{date('Y-m-d' , $quotaInfo->check_in_date)}} @else  N/A @endif</td>
                    </tr> 
                    <tr>
                        <th class="t-basic">Check-out Date</th>
                        <td>@if(isset($quotaInfo->check_out_date) && !empty($quotaInfo->check_out_date)) {{date('Y-m-d' , $quotaInfo->check_out_date)}} @else N/A @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Actual Male</th>
                        <td>@if(isset($quotaInfo->male)) {{$quotaInfo->male}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Actual Female</th>
                        <td>@if(isset($quotaInfo->female)) {{$quotaInfo->female}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Actual Total Quota</th>
                        <td>@if(isset($quotaInfo->total_quotas) && $quotaInfo->total_quotas!='') {{$quotaInfo->total_quotas}} @endif</td>
                    </tr>
                    @php 
                    use App\Models\HallBookingInfo;
                    $totalMaleBookingReleased = $totalFemaleBookingReleased = $totalGenderBookingReleased = 0;

                        if(isset($quotaInfo->male) && $quotaInfo->male!=''){
                            $maleQuotaActual = $quotaInfo->male;
                        }
                        if(isset($quotaInfo->female) && $quotaInfo->female!=''){
                            $femaleQuotaActual = $quotaInfo->female;
                        }
                        if(isset($quotaInfo->female) && $quotaInfo->female!='' && isset($quotaInfo->male) && $quotaInfo->male!=''){
                            $balanceActual = $quotaInfo->female + $quotaInfo->male;
                        }
                        $totalMaleBookingReleased = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.quota_id')->leftJoin('member_infos', function ($join) {
                                    $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->whereIn('hall_booking_infos.status',['Accepted','Paid','Updated'])->where('hall_booking_infos.quota_id',$quotaInfo->id)->count();
                        $totalFemaleBookingReleased = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.quota_id')->leftJoin('member_infos', function ($join) {
                                    $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->whereIn('hall_booking_infos.status',['Accepted','Paid','Updated'])->where('hall_booking_infos.quota_id',$quotaInfo->id)->count();
                        $totalGenderBookingReleased = HallBookingInfo::select('id','quota_id')->whereIn('status',['Accepted','Paid','Updated'])->where('quota_id',$quotaInfo->id)->count();
                        $totalMaleBookingReleased = $maleQuotaActual - $totalMaleBookingReleased;
                        $totalFemaleBookingReleased = $femaleQuotaActual - $totalFemaleBookingReleased;
                        $totalGenderBookingReleased = $balanceActual - $totalGenderBookingReleased;
                    @endphp
                    <tr>
                        <th class="t-basic">Actual Quota Balance </th>
                        <td>@if($totalGenderBookingReleased < 0) 0 @else {{$totalGenderBookingReleased}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Actual Male Quota Balance </th>
                        <td>@if($totalMaleBookingReleased < 0) 0 @else {{$totalMaleBookingReleased}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Actual Female Quota Balance</th>
                        <td>@if($totalFemaleBookingReleased < 0) 0 @else {{$totalFemaleBookingReleased}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Max Male Quota Limit </th>
                        <td>@if(isset($quotaInfo->male_max_quota) && $quotaInfo->male_max_quota!='') {{$quotaInfo->male_max_quota}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Max Female Quota Limit </th>
                        <td>@if(isset($quotaInfo->female_max_quota) && $quotaInfo->female_max_quota!='') {{$quotaInfo->female_max_quota}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Max Total Quota Limit</th>
                        @php
                             $maxTotalQuota = $maxMaleTotalQuota = $maxFemaleTotalQuota = 0;
                             if(isset($quotaInfo->male_max_quota) && $quotaInfo->male_max_quota != ''){
                                $maxMaleTotalQuota = $quotaInfo->male_max_quota;
                             }
                             if(isset($quotaInfo->female_max_quota) && $quotaInfo->female_max_quota != ''){
                                $maxFemaleTotalQuota = $quotaInfo->female_max_quota;
                             }
                             if(isset($maxMaleTotalQuota) && isset($maxFemaleTotalQuota)){
                                $maxTotalQuota = $maxMaleTotalQuota + $maxFemaleTotalQuota;
                             }
                        @endphp
                        <td><span id="maxMaleTotalQuota">@if($maxTotalQuota < 0) 0 @else {{$maxTotalQuota}} @endif</span></td>
                    </tr>
                    @php  
                        $maleQuotaBalance = $femaleQuotaBalance = $QuotaBalance= $balance = $maleQuota = $femaleQuota = $i = $k= $t = 0;
                        if(isset($quotaInfo->male_max_quota) && $quotaInfo->male_max_quota!=''){
                            $maleQuota = $quotaInfo->male_max_quota;
                        }
                        if(isset($quotaInfo->female_max_quota) && $quotaInfo->female_max_quota!=''){
                            $femaleQuota = $quotaInfo->female_max_quota;
                        }
                        if(isset($quotaInfo->female_max_quota) && $quotaInfo->female_max_quota!='' && isset($quotaInfo->male_max_quota) && $quotaInfo->male_max_quota!=''){
                            $balance = $quotaInfo->female_max_quota + $quotaInfo->male_max_quota;
                        }
                        if(isset($quotaInfo->getHallBookinInfos) && count($quotaInfo->getHallBookinInfos)){
                            foreach($quotaInfo->getHallBookinInfos as $bookingInfos){
                                if(isset($bookingInfos->getMemberdata) && !empty($bookingInfos->getMemberdata) && $bookingInfos->getMemberdata->gender == 'Male' && $bookingInfos->status != 'Cancelled' && $bookingInfos->status != 'Rejected'){
                                    $i++;
                                }
                                if(isset($bookingInfos->getMemberdata) && !empty($bookingInfos->getMemberdata) && $bookingInfos->getMemberdata->gender == 'Female' && $bookingInfos->status != 'Cancelled' && $bookingInfos->status != 'Rejected'){
                                    $k++;
                                }
                                if($bookingInfos->status != 'Cancelled' && $bookingInfos->status != 'Rejected'){
                                    $t++;
                                }
                            }
                        }
                        $maleQuotaBalance = $maleQuota - $i;
                        $femaleQuotaBalance = $femaleQuota - $k;
                        $QuotaBalance = $balance - $t;
                    @endphp
                    <tr>
                        <th class="t-basic">Max Quota Limit Balance</th>
                        <td>
                            @if($QuotaBalance < 0) 0 @else {{$QuotaBalance}} @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Max Male Quota Limit Balance </th>
                        <td>
                            @if($maleQuotaBalance < 0) 0 @else {{$maleQuotaBalance}} @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic" style="width: 250px;">Max Female Quota Limit Balance </th>
                        <td>@if($femaleQuotaBalance < 0) 0 @else {{$femaleQuotaBalance}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Hall Confirmation Days</th>
                        <td>@if(isset($quotaInfo->hall_confirmation_date) && !empty($quotaInfo->hall_confirmation_date)) {{date('Y-m-d' ,$quotaInfo->hall_confirmation_date)}} @else N/A @endif</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>