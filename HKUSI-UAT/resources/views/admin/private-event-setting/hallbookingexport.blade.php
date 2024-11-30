<table>
    <thead>
    <tr>
        <th>Created Date</th>
        <th>Created Time</th>
        <th>Booking #</th>
        <th>Qouta #</th>
        <th>Hall/Collage</th>
        <th>In Date</th>
        <th>Out Date</th>
        <th>Nights</th>
        <th>Application #</th>
        <th>Amount</th>
        <th>Booking Qty</th>
        <th>Type</th>
        <th>Status</th>
        <th>Programe Code</th>
        <th>Programe Name</th>
        <th>Application #</th>
        <th>Email Address</th>
        <th>Title</th>
        <th>Gender</th>
        <th>Surname</th>
        <th>Given Name</th>
        <th>Chiness Name</th>
        <th>HKID</th>
        <th>Passport No.</th>
        <th>Nationality</th>
        <th>Date Of Birth</th>
        <th>Mobile No.</th>
        <th>Studey Country</th>
        <th>Address</th>
        <th>Room Type</th>
        <th>Room #</th>
    </tr>
    </thead>
    <tbody>
        @if(count($hallbooking))
        @php  $i=1; @endphp        
            @foreach ($hallbooking as $key => $hallbookinData)

                @php 
                    $days = 0;
                    $date1 = $hallbookinData->getQuotaDetail->check_in_date - 86400;
                    $date2 = $hallbookinData->getQuotaDetail->check_out_date;
                    $days = (int)(($date2 - $date1)/86400);


                    $programmeCode = $programmeName = '';
                    if (isset($hallbookinData->booking_type) && $hallbookinData->booking_type == 'g') {
                        if (isset($hallbookinData->getGroupHallInfo) && count($hallbookinData->getGroupHallInfo)) {
                            foreach ($hallbookinData->getGroupHallInfo as $key => $groupHallInfo) {
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
                        $programmeCode = $hallbookinData->programme_code;
                        $programmeName = $hallbookinData->getProgrammeDetail->programme_name;
                    }
                @endphp

                @if($hallbookinData->booking_type == 'g')
                    @if(isset($hallbookinData->getGroupHallInfo) && count($hallbookinData->getGroupHallInfo))
                        @foreach($hallbookinData->getGroupHallInfo as $groupPayment)
                            @if(isset($groupPayment->allPaymentRecords) && count($groupPayment->allPaymentRecords))
                                @foreach($groupPayment->allPaymentRecords as $paymentGroup)
                                    <tr>
                                        <td>@if(isset($hallbookinData->created_at) && !empty($hallbookinData->created_at)) {{date('Y-m-d' , strtotime($hallbookinData->created_at))}} @endif</td>
                                        <td>@if(isset($hallbookinData->created_at) && !empty($hallbookinData->created_at)) {{date('h:i:s' , strtotime($hallbookinData->created_at))}} @endif</td>
                                        <td>@if(isset($hallbookinData->booking_number) && !empty($hallbookinData->booking_number)) # {{$hallbookinData->booking_number}} @endif</td>
                                        <td >@if(isset($hallbookinData->quota_id) && !empty($hallbookinData->quota_id)) # {{$hallbookinData->quota_id}} @endif</td>
                                        <td>@if(isset($hallbookinData->getQuotaHallDetail->college_name) && !empty($hallbookinData->getQuotaHallDetail->college_name)) {{$hallbookinData->getQuotaHallDetail->college_name}} @else N/A @endif</td>
                                        <td >@if(isset($hallbookinData->getQuotaDetail->check_in_date) && !empty($hallbookinData->getQuotaDetail->check_in_date)) {{date('Y-m-d',$hallbookinData->getQuotaDetail->check_in_date)}} @endif</td>
                                        <td> @if(isset($hallbookinData->getQuotaDetail->check_out_date) && !empty($hallbookinData->getQuotaDetail->check_out_date)) {{date('Y-m-d',$hallbookinData->getQuotaDetail->check_out_date)}} @endif</td>
                                        <td >{{$days - 1}}</td>
                                        <td >@if(isset($hallbookinData->application_id) && !empty($hallbookinData->application_id)) {{$hallbookinData->application_id}} @endif</td>
                                        <td>
                                        @if(isset($hallbookinData->amount) && !empty($hallbookinData->amount)) 
                                            {{$hallbookinData->amount}} 
                                        @endif</td>
                                        <td > @if($hallbookinData->booking_type == 'g') {{count($hallbookinData->getGroupHallInfo)}} @else 1 @endif</td>
                                        <td >
                                            @if(isset($hallbookinData->booking_type) && !empty($hallbookinData->booking_type))
                                                @if($hallbookinData->booking_type == 'g'){{ucfirst($hallbookinData->booking_type)}}
                                                @else 
                                                {{ucfirst($hallbookinData->booking_type)}}
                                                @endif 
                                            @endif
                                        </td>
                                        <td>  
                                            @if($paymentGroup->status != 'PAID')
                                                Accepted
                                            @else
                                                @if($hallbookinData->status == "Completed") Completed  @elseif($hallbookinData->status == "Pending") Pending @elseif($hallbookinData->status == "Accepted") Accepted  @elseif($hallbookinData->status == "Paid") Paid @elseif($hallbookinData->status == "Cancelled") Cancelled @elseif($hallbookinData->status == "Updated") Updated   @elseif($hallbookinData->status == "Rejected") Rejected @endif 
                                            @endif                               
                                        </td>
                                        <td >@if(isset($programmeCode) && !empty($programmeCode)) {{$programmeCode}} @endif</td>
                                        <td>@if(isset($programmeName) && !empty($programmeName)) {{$programmeName}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getMemberdata->application_number) && !empty($hallbookinData->getMemberdata->application_number)) {{$hallbookinData->getMemberdata->application_number}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getMemberdata->email_address) && !empty($hallbookinData->getMemberdata->email_address)) {{$hallbookinData->getMemberdata->email_address}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getMemberdata->title) && !empty($hallbookinData->getMemberdata->title)) {{$hallbookinData->getMemberdata->title}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getMemberdata->gender) && !empty($hallbookinData->getMemberdata->gender)) {{$hallbookinData->getMemberdata->gender}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getMemberdata->surname) && !empty($hallbookinData->getMemberdata->surname)) {{$hallbookinData->getMemberdata->surname}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getMemberdata->given_name) && !empty($hallbookinData->getMemberdata->given_name)) {{$hallbookinData->getMemberdata->given_name}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getMemberdata->chinese_name) && !empty($hallbookinData->getMemberdata->chinese_name)) {{$hallbookinData->getMemberdata->chinese_name}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getMemberdata->hkid_card_no) && !empty($hallbookinData->getMemberdata->hkid_card_no)) {{$hallbookinData->getMemberdata->hkid_card_no}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getMemberdata->passport_no) && !empty($hallbookinData->getMemberdata->passport_no)) {{$hallbookinData->getMemberdata->passport_no}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getMemberdata->nationality) && !empty($hallbookinData->getMemberdata->nationality)) {{$hallbookinData->getMemberdata->nationality}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getMemberdata->date_of_birth) && !empty($hallbookinData->getMemberdata->date_of_birth)) {{date('Y-m-d' , $hallbookinData->getMemberdata->date_of_birth)}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getMemberdata->mobile_tel_no) && !empty($hallbookinData->getMemberdata->mobile_tel_no)) {{$hallbookinData->getMemberdata->mobile_tel_no}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getMemberdata->getStudyCountry->name) && !empty($hallbookinData->getMemberdata->getStudyCountry->name)) {{$hallbookinData->getMemberdata->getStudyCountry->name}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getQuotaHallDetail->address) && !empty($hallbookinData->getQuotaHallDetail->address)) {{$hallbookinData->getQuotaHallDetail->address}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getQuotaHallDetail->room_type) && !empty($hallbookinData->getQuotaHallDetail->room_type)) {{$hallbookinData->getQuotaHallDetail->room_type}} @else N/A @endif</td>
                                        <td>@if(isset($hallbookinData->getQuotaRoomDetail->room_code) && !empty($hallbookinData->getQuotaRoomDetail->room_code)) # {{$hallbookinData->getQuotaRoomDetail->room_code}} @else N/A @endif</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    @endif

                @else
                    @if(isset($hallbookinData->allPaymentRecords) && count($hallbookinData->allPaymentRecords))
                        @foreach($hallbookinData->allPaymentRecords as $paymentData)
                            <tr>
                                <td>@if(isset($hallbookinData->created_at) && !empty($hallbookinData->created_at)) {{date('Y-m-d' , strtotime($hallbookinData->created_at))}} @endif</td>
                                <td>@if(isset($hallbookinData->created_at) && !empty($hallbookinData->created_at)) {{date('h:i:s' , strtotime($hallbookinData->created_at))}} @endif</td>
                                <td>@if(isset($hallbookinData->booking_number) && !empty($hallbookinData->booking_number)) # {{$hallbookinData->booking_number}} @endif</td>
                                <td >@if(isset($hallbookinData->quota_id) && !empty($hallbookinData->quota_id)) # {{$hallbookinData->quota_id}} @endif</td>
                                <td>@if(isset($hallbookinData->getQuotaHallDetail->college_name) && !empty($hallbookinData->getQuotaHallDetail->college_name)) {{$hallbookinData->getQuotaHallDetail->college_name}} @else N/A @endif</td>
                                <td >@if(isset($hallbookinData->getQuotaDetail->check_in_date) && !empty($hallbookinData->getQuotaDetail->check_in_date)) {{date('Y-m-d',$hallbookinData->getQuotaDetail->check_in_date)}} @endif</td>
                                <td> @if(isset($hallbookinData->getQuotaDetail->check_out_date) && !empty($hallbookinData->getQuotaDetail->check_out_date)) {{date('Y-m-d',$hallbookinData->getQuotaDetail->check_out_date)}} @endif</td>
                                <td >{{$days - 1}}</td>
                                <td >@if(isset($hallbookinData->application_id) && !empty($hallbookinData->application_id)) {{$hallbookinData->application_id}} @endif</td>
                                <td>
                                @if(isset($hallbookinData->amount) && !empty($hallbookinData->amount)) 
                                    {{$hallbookinData->amount}} 
                                @endif</td>
                                <td > @if($hallbookinData->booking_type == 'g') {{count($hallbookinData->getGroupHallInfo)}} @else 1 @endif</td>
                                <td >
                                    @if(isset($hallbookinData->booking_type) && !empty($hallbookinData->booking_type))
                                        @if($hallbookinData->booking_type == 'g'){{ucfirst($hallbookinData->booking_type)}}
                                        @else 
                                        {{ucfirst($hallbookinData->booking_type)}}
                                        @endif 
                                    @endif
                                </td>
                                <td>  
                                    @if($paymentData->status != 'PAID')
                                        Accepted
                                    @else
                                        @if($hallbookinData->status == "Completed") Completed  @elseif($hallbookinData->status == "Pending") Pending @elseif($hallbookinData->status == "Accepted") Accepted  @elseif($hallbookinData->status == "Paid") Paid @elseif($hallbookinData->status == "Cancelled") Cancelled @elseif($hallbookinData->status == "Updated") Updated   @elseif($hallbookinData->status == "Rejected") Rejected @endif 
                                    @endif                               
                                </td>
                                <td >@if(isset($programmeCode) && !empty($programmeCode)) {{$programmeCode}} @endif</td>
                                <td>@if(isset($programmeName) && !empty($programmeName)) {{$programmeName}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getMemberdata->application_number) && !empty($hallbookinData->getMemberdata->application_number)) {{$hallbookinData->getMemberdata->application_number}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getMemberdata->email_address) && !empty($hallbookinData->getMemberdata->email_address)) {{$hallbookinData->getMemberdata->email_address}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getMemberdata->title) && !empty($hallbookinData->getMemberdata->title)) {{$hallbookinData->getMemberdata->title}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getMemberdata->gender) && !empty($hallbookinData->getMemberdata->gender)) {{$hallbookinData->getMemberdata->gender}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getMemberdata->surname) && !empty($hallbookinData->getMemberdata->surname)) {{$hallbookinData->getMemberdata->surname}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getMemberdata->given_name) && !empty($hallbookinData->getMemberdata->given_name)) {{$hallbookinData->getMemberdata->given_name}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getMemberdata->chinese_name) && !empty($hallbookinData->getMemberdata->chinese_name)) {{$hallbookinData->getMemberdata->chinese_name}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getMemberdata->hkid_card_no) && !empty($hallbookinData->getMemberdata->hkid_card_no)) {{$hallbookinData->getMemberdata->hkid_card_no}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getMemberdata->passport_no) && !empty($hallbookinData->getMemberdata->passport_no)) {{$hallbookinData->getMemberdata->passport_no}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getMemberdata->nationality) && !empty($hallbookinData->getMemberdata->nationality)) {{$hallbookinData->getMemberdata->nationality}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getMemberdata->date_of_birth) && !empty($hallbookinData->getMemberdata->date_of_birth)) {{date('Y-m-d' , $hallbookinData->getMemberdata->date_of_birth)}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getMemberdata->mobile_tel_no) && !empty($hallbookinData->getMemberdata->mobile_tel_no)) {{$hallbookinData->getMemberdata->mobile_tel_no}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getMemberdata->getStudyCountry->name) && !empty($hallbookinData->getMemberdata->getStudyCountry->name)) {{$hallbookinData->getMemberdata->getStudyCountry->name}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getQuotaHallDetail->address) && !empty($hallbookinData->getQuotaHallDetail->address)) {{$hallbookinData->getQuotaHallDetail->address}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getQuotaHallDetail->room_type) && !empty($hallbookinData->getQuotaHallDetail->room_type)) {{$hallbookinData->getQuotaHallDetail->room_type}} @else N/A @endif</td>
                                <td>@if(isset($hallbookinData->getQuotaRoomDetail->room_code) && !empty($hallbookinData->getQuotaRoomDetail->room_code)) # {{$hallbookinData->getQuotaRoomDetail->room_code}} @else N/A @endif</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>@if(isset($hallbookinData->created_at) && !empty($hallbookinData->created_at)) {{date('Y-m-d' , strtotime($hallbookinData->created_at))}} @endif</td>
                            <td>@if(isset($hallbookinData->created_at) && !empty($hallbookinData->created_at)) {{date('h:i:s' , strtotime($hallbookinData->created_at))}} @endif</td>
                            <td>@if(isset($hallbookinData->booking_number) && !empty($hallbookinData->booking_number)) # {{$hallbookinData->booking_number}} @endif</td>
                            <td >@if(isset($hallbookinData->quota_id) && !empty($hallbookinData->quota_id)) # {{$hallbookinData->quota_id}} @endif</td>
                            <td>@if(isset($hallbookinData->getQuotaHallDetail->college_name) && !empty($hallbookinData->getQuotaHallDetail->college_name)) {{$hallbookinData->getQuotaHallDetail->college_name}} @else N/A @endif</td>
                            <td >@if(isset($hallbookinData->getQuotaDetail->check_in_date) && !empty($hallbookinData->getQuotaDetail->check_in_date)) {{date('Y-m-d',$hallbookinData->getQuotaDetail->check_in_date)}} @endif</td>
                            <td> @if(isset($hallbookinData->getQuotaDetail->check_out_date) && !empty($hallbookinData->getQuotaDetail->check_out_date)) {{date('Y-m-d',$hallbookinData->getQuotaDetail->check_out_date)}} @endif</td>
                            <td >{{$days - 1}}</td>
                            <td >@if(isset($hallbookinData->application_id) && !empty($hallbookinData->application_id)) {{$hallbookinData->application_id}} @endif</td>
                            <td>
                            @if(isset($hallbookinData->amount) && !empty($hallbookinData->amount)) 
                                {{$hallbookinData->amount}} 
                            @endif</td>
                            <td > @if($hallbookinData->booking_type == 'g') {{count($hallbookinData->getGroupHallInfo)}} @else 1 @endif</td>
                            <td >
                                @if(isset($hallbookinData->booking_type) && !empty($hallbookinData->booking_type))
                                    @if($hallbookinData->booking_type == 'g'){{ucfirst($hallbookinData->booking_type)}}
                                    @else 
                                    {{ucfirst($hallbookinData->booking_type)}}
                                    @endif 
                                @endif
                            </td>
                            <td>  
                                @if($hallbookinData->status == "Completed") Completed  @elseif($hallbookinData->status == "Pending") Pending @elseif($hallbookinData->status == "Accepted") Accepted  @elseif($hallbookinData->status == "Paid") Paid @elseif($hallbookinData->status == "Cancelled") Cancelled @elseif($hallbookinData->status == "Updated") Updated   @elseif($hallbookinData->status == "Rejected") Rejected @endif                                
                            </td>
                            <td >@if(isset($programmeCode) && !empty($programmeCode)) {{$programmeCode}} @endif</td>
                            <td>@if(isset($programmeName) && !empty($programmeName)) {{$programmeName}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getMemberdata->application_number) && !empty($hallbookinData->getMemberdata->application_number)) {{$hallbookinData->getMemberdata->application_number}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getMemberdata->email_address) && !empty($hallbookinData->getMemberdata->email_address)) {{$hallbookinData->getMemberdata->email_address}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getMemberdata->title) && !empty($hallbookinData->getMemberdata->title)) {{$hallbookinData->getMemberdata->title}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getMemberdata->gender) && !empty($hallbookinData->getMemberdata->gender)) {{$hallbookinData->getMemberdata->gender}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getMemberdata->surname) && !empty($hallbookinData->getMemberdata->surname)) {{$hallbookinData->getMemberdata->surname}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getMemberdata->given_name) && !empty($hallbookinData->getMemberdata->given_name)) {{$hallbookinData->getMemberdata->given_name}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getMemberdata->chinese_name) && !empty($hallbookinData->getMemberdata->chinese_name)) {{$hallbookinData->getMemberdata->chinese_name}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getMemberdata->hkid_card_no) && !empty($hallbookinData->getMemberdata->hkid_card_no)) {{$hallbookinData->getMemberdata->hkid_card_no}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getMemberdata->passport_no) && !empty($hallbookinData->getMemberdata->passport_no)) {{$hallbookinData->getMemberdata->passport_no}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getMemberdata->nationality) && !empty($hallbookinData->getMemberdata->nationality)) {{$hallbookinData->getMemberdata->nationality}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getMemberdata->date_of_birth) && !empty($hallbookinData->getMemberdata->date_of_birth)) {{date('Y-m-d' , $hallbookinData->getMemberdata->date_of_birth)}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getMemberdata->mobile_tel_no) && !empty($hallbookinData->getMemberdata->mobile_tel_no)) {{$hallbookinData->getMemberdata->mobile_tel_no}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getMemberdata->getStudyCountry->name) && !empty($hallbookinData->getMemberdata->getStudyCountry->name)) {{$hallbookinData->getMemberdata->getStudyCountry->name}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getQuotaHallDetail->address) && !empty($hallbookinData->getQuotaHallDetail->address)) {{$hallbookinData->getQuotaHallDetail->address}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getQuotaHallDetail->room_type) && !empty($hallbookinData->getQuotaHallDetail->room_type)) {{$hallbookinData->getQuotaHallDetail->room_type}} @else N/A @endif</td>
                            <td>@if(isset($hallbookinData->getQuotaRoomDetail->room_code) && !empty($hallbookinData->getQuotaRoomDetail->room_code)) # {{$hallbookinData->getQuotaRoomDetail->room_code}} @else N/A @endif</td>
                        </tr>
                    @endif
                @endif
                @php $i++; @endphp
            @endforeach
        @else
        <td colspan="4"></td>
        <td  colspan="1">{{$notfoundlabel}}</td>
        <td  colspan="10"></td>
        @endif
    </tbody>
</table>