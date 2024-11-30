<table>
    <thead>
    <tr>
        <th>Created Date</th>
        <th>Created Time</th>
        <th>Payment #</th>
        <th>Booking #</th>
        <th>Application #</th>
        <th>Order No.</th>
        <th>Service</th>
        <th>Amount</th>
        <th>Payment Method</th>
        <th>Payment Type</th>
        <th>Transaction #</th>
        <th>Booking Previous Status</th>
        <th>Booking Stauts</th>
        <th>Payment Previous Status</th>
        <th>Payment Stauts</th>
        <th>Title</th>
        <th>Surname</th>
        <th>Given Name</th>
        <th>Chiness Name</th>
        <th>Gender</th>
        <th>Date Of Birth</th>
        <th>Passport No.</th>
        <th>Nationality</th>
        <th>Mobile No.</th>
        <th>Email Address</th>
    </tr>
    </thead>
    <tbody>
	 @if(count($payment))
        @php  $i=1; @endphp        
            @foreach ($payment as $key => $paymentdata)
                <tr>
                    <td>@if(isset($paymentdata->pay_time) && !empty($paymentdata->pay_time)) {{date('Y-m-d' , $paymentdata->pay_time)}} 
                    @else {{date('Y-m-d' , strtotime($paymentdata->created_at))}} @endif</td>
                    <td>@if(isset($paymentdata->pay_time) && !empty($paymentdata->pay_time)) {{date('h:i:s' , $paymentdata->pay_time)}} 
                    @else {{date('h:i:s' , strtotime($paymentdata->created_at))}} @endif</td>
                    <td>@if(isset($paymentdata->id) && !empty($paymentdata->id)) # {{$paymentdata->id}} @endif</td>
                    <td>@if(isset($paymentdata->payment_id) && !empty($paymentdata->payment_id)) # {{$paymentdata->payment_id}} @endif</td>
                    <td>@if(isset($paymentdata->application_id) && !empty($paymentdata->application_id)) #{{$paymentdata->application_id}} @endif</td>
                    <td>@if(isset($paymentdata->order_no) && !empty($paymentdata->order_no)) {{$paymentdata->order_no}} @endif</td>
                    <td>@if(isset($paymentdata->service_type) && !empty($paymentdata->service_type)) {{$paymentdata->service_type}} @endif</td>
                    <td >@if(isset($paymentdata->amount) && !empty($paymentdata->amount)) ${{$paymentdata->amount}} @endif</td>
                    <td>@if(isset($paymentdata->payment_method) && !empty($paymentdata->payment_method)) {{$paymentdata->payment_method}} @endif</td>
                    <td>@if(isset($paymentdata->pay_type) && !empty($paymentdata->pay_type)) {{$paymentdata->pay_type}} @endif</td>
                    <td >@if(isset($paymentdata->transaction_id) && !empty($paymentdata->transaction_id)) #{{$paymentdata->transaction_id}} @endif</td> 
                    <td>@if(isset($paymentdata->getBookingDetails->previous_status) && !empty($paymentdata->getBookingDetails->previous_status)) {{$paymentdata->getBookingDetails->previous_status}} @endif</td>
                    <td>{{$paymentdata->getBookingDetails->status ?? 'N/A'}}</td>
                    <td>{{$paymentdata->previous_status ?? 'N/A'}} </td>
                    <td>@if(isset($paymentdata->payment_status) && !empty($paymentdata->payment_status)) {{$paymentdata->payment_status}} @else Processing @endif</td>
                    <td >@if(isset($paymentdata->getMemberInfos->title) && !empty($paymentdata->getMemberInfos->title)) {{$paymentdata->getMemberInfos->title}} @endif</td> 
                    <td >@if(isset($paymentdata->getMemberInfos->surname) && !empty($paymentdata->getMemberInfos->surname)) {{$paymentdata->getMemberInfos->surname}} @endif</td> 
                    <td >@if(isset($paymentdata->getMemberInfos->given_name) && !empty($paymentdata->getMemberInfos->given_name)) {{$paymentdata->getMemberInfos->given_name}} @endif</td> 
                    <td >@if(isset($paymentdata->getMemberInfos->chinese_name) && !empty($paymentdata->getMemberInfos->chinese_name)) {{$paymentdata->getMemberInfos->chinese_name}} @endif</td> 
                    <td >@if(isset($paymentdata->getMemberInfos->gender) && !empty($paymentdata->getMemberInfos->gender)) {{$paymentdata->getMemberInfos->gender}} @endif</td> 
                    <td >@if(isset($paymentdata->getMemberInfos->date_of_birth) && !empty($paymentdata->getMemberInfos->date_of_birth)) {{date('Y-m-d',$paymentdata->getMemberInfos->date_of_birth)}} @endif</td> 
                    <td >@if(isset($paymentdata->getMemberInfos->passport_no) && !empty($paymentdata->getMemberInfos->passport_no)) {{$paymentdata->getMemberInfos->passport_no}} @endif</td> 
                    <td >@if(isset($paymentdata->getMemberInfos->getStudyCountry->name) && !empty($paymentdata->getMemberInfos->getStudyCountry->name)) {{$paymentdata->getMemberInfos->getStudyCountry->name}} @endif</td> 
                    <td >@if(isset($paymentdata->getMemberInfos->mobile_tel_no) && !empty($paymentdata->getMemberInfos->mobile_tel_no)) {{$paymentdata->getMemberInfos->mobile_tel_no}} @endif</td> 
                    <td >@if(isset($paymentdata->getMemberInfos->email_address) && !empty($paymentdata->getMemberInfos->email_address)) {{$paymentdata->getMemberInfos->email_address}} @endif</td> 
                </tr>
            @endforeach
        @else
        <td colspan="3"></td>
        <td >{{$notfoundlabel}}</td>
        <td  colspan="3"></td>
        @endif
    </tbody>
</table>