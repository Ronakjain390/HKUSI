{!! Form::model($quotaInfo, ['method' => 'PATCH','route' => ['admin.quota.update', $quotaInfo->id],'id' => 'quickForm','autocomplete' => 'off','class'=>'edit-form']) !!}
    <input type="hidden" name="hall_setting_id" value="{{$quotaInfo->hall_setting_id}}">
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                     <tr>
                        <th class="t-basic">Create Date</th>
                        <td>@if(isset($quotaInfo->created_at) && !empty($quotaInfo->created_at)){{date('Y-m-d' , strtotime($quotaInfo->created_at))}}@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Create Time</th>
                        <td>@if(isset($quotaInfo->created_at) && !empty($quotaInfo->created_at)){{date('h:i:s' , strtotime($quotaInfo->created_at))}}@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Start Date</th>
                        <td><input type="text" name="start_date" id="start_date" readonly class="form-control" placeholder="Start Date" @if(isset($quotaInfo->getHallSettingDetail->start_date) && !empty($quotaInfo->start_date)) value="{{date('Y-m-d' , $quotaInfo->start_date)}}">@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">End Date</th>
                        <td><input type="text" name="end_date" id="end_date" readonly class="form-control" placeholder="End Date" @if(isset($quotaInfo->end_date) && !empty($quotaInfo->end_date)) value="{{date('Y-m-d' , $quotaInfo->end_date)}}">@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Check In Date</th>
                        <td> <span id="checkInDate">@if(isset($quotaInfo->check_in_date) && !empty($quotaInfo->check_in_date)) {{date('Y-m-d' , $quotaInfo->check_in_date)}} @endif</span></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Check Out Date</th>
                        <td> <span id="checkOutDate">@if(isset($quotaInfo->check_out_date) && !empty($quotaInfo->check_out_date)) {{date('Y-m-d' , $quotaInfo->check_out_date)}} @endif</span></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Actual Male</th>
                        <td><input type="text" name="male" id="male" onchange="totalQuota(this.value , 'male')" onkeypress="return isNumber(event);" class="form-control" placeholder="Actual Male" @if(isset($quotaInfo->male)) value="{{$quotaInfo->male}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Actual Female</th>
                        <td><input type="text" id="female" name="female" onchange="totalQuota(this.value , 'female')" onkeypress="return isNumber(event);" class="form-control" placeholder="Actual Female" @if(isset($quotaInfo->female)) value="{{$quotaInfo->female}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Actual Total Quota</th>
                        <td><span id="totalQuotas">@if(isset($quotaInfo->total_quotas) && $quotaInfo->total_quotas != '') {{$quotaInfo->total_quotas}} @endif</span><input type="hidden" id="total_quotas" readonly name="total_quotas" onkeypress="return isNumber(event);" class="form-control" placeholder="Actual Total Quota" @if(isset($quotaInfo->total_quotas) && $quotaInfo->total_quotas != '') value="{{$quotaInfo->total_quotas}}" @endif></td>
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
                        $totalMaleBookingReleasedSpan = $maleQuotaActual - $totalMaleBookingReleased;
                        $totalFemaleBookingReleasedSpan = $femaleQuotaActual - $totalFemaleBookingReleased;
                        $totalGenderBookingReleasedSpan = $balanceActual - $totalGenderBookingReleased;
                    @endphp
                        <tr>
                            <th class="t-basic">Actual Quota Balance </th>
                            <td><span id="totalGenderBookingReleasedTotal">@if($totalGenderBookingReleasedSpan < 0) 0 @else {{$totalGenderBookingReleasedSpan}} @endif</span></td>
                        </tr>
                        <tr>
                            <th class="t-basic">Actual Male Quota Balance </th>
                            <td><span id="totalMaleBookingReleasedTotal">@if($totalMaleBookingReleasedSpan < 0) 0 @else {{$totalMaleBookingReleasedSpan}} @endif</span></td>
                        </tr>
                        <tr>
                            <th class="t-basic">Actual Female Quota Balance</th>
                            <td><span id="totalFemaleBookingReleasedTotal">@if($totalFemaleBookingReleasedSpan < 0) 0 @else {{$totalFemaleBookingReleasedSpan}} @endif</span></td>
                        </tr>
                        @php  
                            $maleQuotaBalance = $femaleQuotaBalance = $QuotaBalance= $balance = $maleQuota = $femaleQuota = 0;
                            $i = $k = $j = 0;
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
                                        $j++;
                                    }
                                    if(isset($bookingInfos->getMemberdata) && !empty($bookingInfos->getMemberdata) && $bookingInfos->getMemberdata->gender == 'Female' && $bookingInfos->status != 'Cancelled' && $bookingInfos->status != 'Rejected'){
                                        $k++;
                                        $j++;
                                    }
                                }
                            }

                            $maleQuotaBalance = $maleQuota - $i;
                            $femaleQuotaBalance = $femaleQuota - $k;
                            $QuotaBalance = $balance - $j;
                        @endphp
                    <tr>
                        <th class="t-basic">Max Male Quota Limit</th>
                        <td><input type="text" name="male_max_quota" id="male_max_quota" onkeypress="return isNumber(event);" class="form-control" min="{{$i}}" onchange="return totalQuotaBalance(this.value , 'max_male')" placeholder="Male Quota Limit" @if(isset($quotaInfo->male_max_quota) && $quotaInfo->male_max_quota != '') value="{{$quotaInfo->male_max_quota}}" @endif ></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Max Female Quota Limit</th>
                        <td><input type="text" min="{{$k}}" name="female_max_quota" id="female_max_quota" onkeypress="return isNumber(event);" onchange="return totalQuotaBalance(this.value , 'max_female')" class="form-control" placeholder="Female Quota Limit" @if(isset($quotaInfo->female_max_quota) && $quotaInfo->female_max_quota != '') value="{{$quotaInfo->female_max_quota}}" @endif ></td>
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
                    <tr>
                        <th class="t-basic">Max Quota Limit Balance</th>
                        <td><span id="totalQuotaBalanceSpan">@if($QuotaBalance < 0) 0 @else {{$QuotaBalance}} @endif</span> <input type="hidden" name="quota_balance" id="quota_balance" onkeypress="return isNumber(event);" class="form-control" placeholder="Quota Balance" value="{{$QuotaBalance}}"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Max Male Quota Limit Balance </th>
                        <td><span id="totalQuotaMaleBalanceSpan">@if($maleQuotaBalance < 0) 0 @else {{$maleQuotaBalance}} @endif</span></td>
                    </tr>
                    <tr>
                        <th class="t-basic" style="width: 250px;">Max Female Quota Limit Balance </th>
                        <td><span id="totalQuotaFemaleBalanceSpan">@if($femaleQuotaBalance < 0) 0 @else {{$femaleQuotaBalance}} @endif</span></td>
                    </tr>
                    <tr>
                        <th class="t-basic"  style="width: 180px;">Hall Confirmation Days</th>
                        <td><input type="text" name="hall_confirmation_date" class="form-control datepicker" placeholder="Hall Confirmation Days" @if(isset($quotaInfo->hall_confirmation_date) && !empty($quotaInfo->hall_confirmation_date)) value="{{date('Y-m-d' ,$quotaInfo->hall_confirmation_date)}}">@endif</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Status Country</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Status Country</th>
                        <td>
                            <select name="countries[]" class="form-select"  multiple multiselect-search="true" multiselect-select-all="true">
                                @if(isset($countries) && count($countries))  
                                    @foreach($countries as $countryData)
                                        <option value="{{$countryData->id}}" @if(isset($quotaCountries) && !empty($quotaCountries) && in_array($countryData->id,$quotaCountries)) Selected @endif>{{$countryData->name}}</option>
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
                            <select @if(isset($quotaInfo->status) && $quotaInfo->status == '1') disabled @endif class="form-control" name="status" style="background-color: #fff;">
                                <option value="">Select Status</option>
                                <option value="1" @if(isset($quotaInfo->status) && $quotaInfo->status == '1') selected @endif >Released</option>
                                <option value="0" @if(isset($quotaInfo->status) && $quotaInfo->status == '0') selected @endif >Pending</option>
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

<div class="content-backdrop fade "></div> 
@push('foorterscript')
@if($message = Session::get('programmeQuatoError'))
<script type="text/javascript"> 
    // $( document ).ready(function() {
        Swal.fire({
              text: "{{$message}}",
              icon: "error",
              showCancelButton: false,
              showConformButton: true,
              confirmButtonColor: "#6fc5e0",
              conformButtonColor: "#2dcb2d",
              confirmButtonText: "Ok",
              timer: 5000,
        });
    // });
</script>
@endif

<script>
  $(document).ready(function(){
        $("#quickForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                start_date: "required",
                end_date: {
                    required: true,
                },
                check_in_date: {
                    required: true,
                },
                check_out_date: {
                    required: true,
                },
                
                total_quotas: {
                    required: true,
                },
                quota_balance: {
                    required: true,
                },
                male: {
                    required: true,
                    // min: {{$totalGenderMaleBooking}},
                },
                female: {
                    required: true,
                    // min: {{$totalGenderFemaleBooking}},
                },
                male_max_quota:{
                    required: true,
                },
                female_max_quota:{
                    required: true,
                },
                hall_confirmation_date:{
                    required: true,
                },
                status:{
                    required: true,
                },
                'countries[]': "required",
                'programmes[]': "required",
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                start_date: "Please Choose a start date",
                end_date: {
                    required: "Please Choose a end date",
                },
                check_in_date: "Please Choose a check in date",
                check_out_date: {
                    required: "Please Choose a check out date",
                },
                total_quotas: {
                    required: "Please select total quotas",
                },
                quota_balance: {
                    required: "Please select a quota balance",
                },
                male: {
                    required: "Please enter a male field",
                    // min: "Invalid qty.",
                },
                female:{
                    required: "Please enter a female field",
                    // min: "Invalid qty.",
                },
                male_max_quota: {
                    required: "Please enter male quota limit",
                },
                female_max_quota: {
                    required: "Please enter female quota limit",
                },
                hall_confirmation_date: {
                    required: "Please select hall confirmation days",
                },
                status: {
                    required: "Please select a status",
                },
                'countries[]': "Please select any one",
                'programmes[]': "Please select any one",
            }
        });
    });
 
    function totalQuota(val , type){
        @if(isset($totalGenderBookingReleased) && !empty($totalGenderBookingReleased))
            var totalBalance = {{$totalGenderBookingReleased}};
        @else 
            var totalBalance = 0;
        @endif
        @if(isset($totalFemaleBookingReleased) && !empty($totalFemaleBookingReleased))
            var totalFemaleBalance = {{$totalFemaleBookingReleased}};
        @else 
            var totalFemaleBalance = 0;
        @endif
        @if(isset($totalMaleBookingReleased) && !empty($totalMaleBookingReleased))
            var totalMaleBalance = {{$totalMaleBookingReleased}};
        @else 
            var totalMaleBalance = 0;
        @endif
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
            var totalValue = parseInt(maleValue) + parseInt(femaleValue);
            // var totalValueBalance = parseInt(maleValue) + parseInt(femaleValue) - parseInt(totalBalance);
            var totalFemaleValue = parseInt(femaleValue) - parseInt(totalFemaleBalance);
            if (totalFemaleValue > 0) {
                $('#totalFemaleBookingReleasedTotal').html(totalFemaleValue);
            }else{
                $('#totalFemaleBookingReleasedTotal').html(0);
            }
            var totalMaleValue = parseInt(maleValue) - parseInt(totalMaleBalance);
            if (totalMaleValue > 0) {
                $('#totalMaleBookingReleasedTotal').html(totalMaleValue);
            }else{
                $('#totalMaleBookingReleasedTotal').html(0);
            }
            var totalValueBalance = parseInt(totalFemaleValue) + parseInt(totalMaleValue);
            // alert(totalValueBalance);
        }else {
            if(femaleValue != ''){
                var totalValue = parseInt(femaleValue);
                var totalValueBalance = parseInt(femaleValue) - parseInt(totalBalance);
                var totalFemaleValue = parseInt(femaleValue) - parseInt(totalFemaleBalance);
                if (totalFemaleValue > 0) {
                    $('#totalFemaleBookingReleasedTotal').html(totalFemaleValue);
                }else{
                    $('#totalFemaleBookingReleasedTotal').html(0);
                }
            }else{
                if (maleValue != '') {
                    var totalValue = parseInt(maleValue);
                    var totalValueBalance = parseInt(maleValue) - parseInt(totalBalance);
                    var totalMaleValue = parseInt(maleValue) - parseInt(totalMaleBalance);
                    if (totalMaleValue > 0) {
                        $('#totalMaleBookingReleasedTotal').html(totalMaleValue);
                    }else{
                        $('#totalMaleBookingReleasedTotal').html(0);
                    }
                }
            }
        }
        $('#total_quotas').val(totalValue);
        $('#totalQuotas').html(totalValue);
        if (totalValueBalance > 0) {
            $('#totalGenderBookingReleasedTotal').html(totalValueBalance);
        }else{
            $('#totalGenderBookingReleasedTotal').html(0);
        }
    }

    function totalQuotaBalance(val , type){
        @if(isset($QuotaBalance) && !empty($QuotaBalance))
            var totalBalance = {{$QuotaBalance}};
        @else 
            var totalBalance = 0;
        @endif
        @if(isset($k) && !empty($k))
            var totalFemaleBalance = {{$k}};
        @else 
            var totalFemaleBalance = 0;
        @endif
        @if(isset($i) && !empty($i))
            var totalMaleBalance = {{$i}};
        @else 
            var totalMaleBalance = 0;
        @endif
        if (type == 'max_female') {
            var femaleValue = val;
        }else{
            var femaleValue = $('#female_max_quota').val();
        }
        if (type == 'max_male') {
            var maleValue = val;
        }else{
            var maleValue = $('#male_max_quota').val();
        }
        if (femaleValue != '' && maleValue != '') {
            var totalValue = parseInt(maleValue) + parseInt(femaleValue) - parseInt(totalBalance);
            var totalFemaleValue = parseInt(femaleValue) - parseInt(totalFemaleBalance);
            if (totalFemaleValue > 0) {
                $('#totalQuotaFemaleBalanceSpan').html(totalFemaleValue);
            }else{
                $('#totalQuotaFemaleBalanceSpan').html(0);
            }
            var totalMaleValue = parseInt(maleValue) - parseInt(totalMaleBalance);
            if (totalMaleValue > 0) {
                $('#totalQuotaMaleBalanceSpan').html(totalMaleValue);
            }else{
                $('#totalQuotaMaleBalanceSpan').html(0);
            }
            var totalValue = parseInt(totalFemaleValue) + parseInt(totalMaleValue);
            var totalMaxValue = parseInt(maleValue) + parseInt(femaleValue);
        }else {
            if(femaleValue != ''){
                var totalValue = parseInt(femaleValue) - parseInt(totalBalance);
                var totalFemaleValue = parseInt(femaleValue) - parseInt(totalFemaleBalance);
                if (totalFemaleValue > 0) {
                    $('#totalQuotaFemaleBalanceSpan').html(totalFemaleValue);
                }else{
                    $('#totalQuotaFemaleBalanceSpan').html(0);
                }
                var totalMaxValue = parseInt(femaleValue);
            }else{
                if (maleValue != '') {
                    var totalValue = parseInt(maleValue) - parseInt(totalBalance);
                    var totalMaleValue = parseInt(maleValue) - parseInt(totalMaleBalance);
                    if (totalMaleValue > 0) {
                        $('#totalQuotaMaleBalanceSpan').html(totalMaleValue);
                    }else{
                        $('#totalQuotaMaleBalanceSpan').html(0);
                    }
                    var totalMaxValue = parseInt(maleValue);
                }
            }
        }

            
        if (totalMaxValue > 0) {
            $('#maxMaleTotalQuota').html(totalMaxValue);
        }else{
            $('#maxMaleTotalQuota').html(0);
        }
        if (totalValue > 0) {
            $('#totalQuotaBalanceSpan').html(totalValue);
            $('#quota_balance').val(totalValue);
        }else{
            $('#totalQuotaBalanceSpan').html(0);
            $('#quota_balance').val(0);
        }
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
        $("#checkInDate").html(datechange);
       
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
        $("#checkOutDate").html(datechange);
       
    });

</script>
@endpush