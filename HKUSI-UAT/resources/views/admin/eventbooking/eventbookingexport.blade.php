<table>
    <thead>
    <tr>
        <th>Created Date</th>
        <th>Created Time</th>
        <th>Booking #</th>
        <th>Application #</th>
        <th>Event #</th>
        <th>Event Name</th>
        <th>Event Date</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Locaiton #</th>
        <th>Assembly Start Time</th>
        <th>Assembly End Time</th>
        <th>Assembly Location</th>
        <th>No Of Tickets</th>
        <th>Unit Price</th>
        <th>Amount</th>
        <th>Actual Check-in Date</th>
        <th>Actual Check-in Time</th>
        <th>Check-in Operator</th>
        <th>Status</th>
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
    </tr>
    </thead>
    <tbody>
          @if(count($eventbooking))
        @php  $i=1; @endphp        
            @foreach ($eventbooking as $key => $eventBooks)
                <tr>
                    <td>{{date('Y-m-d' , strtotime($eventBooks->created_at))}}</td>
                    <td>@if(isset($eventBooks->created_at) && !empty($eventBooks->created_at)) {{date('h:i:s' , strtotime($eventBooks->created_at))}} @endif</td>
                    <td> @if(isset($eventBooks->payment_id) && !empty($eventBooks->payment_id)) # {{$eventBooks->payment_id}} @endif</td>
                    <td> @if(isset($eventBooks->application_id) && !empty($eventBooks->application_id)) {{$eventBooks->application_id}} @else N/A @endif</td>
                    <td>{{$eventBooks->event_id ?? 'N/A'}}</td>
                    <td>{{ $eventBooks->getEventSetting->event_name ?? 'N/A'}}</td>
                    <td>{{ date("Y-m-d",$eventBooks->getEventSetting->date) ?? 'N/A'}}</td>
                    <td>{{ date("H:i",$eventBooks->getEventSetting->start_time) ?? 'N/A'}}</td>
                    <td>{{ date("H:i",$eventBooks->getEventSetting->end_time) ?? 'N/A'}}</td>
                    <td>{{ $eventBooks->getEventSetting->location ?? 'N/A'}}</td>
                    <td>{{ date("H:i",$eventBooks->getEventSetting->assembly_start_time) ?? 'N/A'}}</td>
                    <td>{{ date("H:i",$eventBooks->getEventSetting->assembly_end_time) ?? 'N/A'}}</td>
                    <td>{{ $eventBooks->getEventSetting->assembly_location ?? 'N/A'}}</td>
                    <td>{{$eventBooks->no_of_seats ?? 'N/A'}}</td>
                    <td>{{ $eventBooks->unit_price ?? 'N/A'}}</td>
                    @php
                    $unit_price = (isset($eventBooks->unit_price) && !empty($eventBooks->unit_price))?$eventBooks->unit_price:'0';
                    $amount = $eventBooks->no_of_seats * $unit_price ;
                    @endphp
                    <td>{{$amount}}</td>
                    <td>@if(isset($eventBooks->check_in_date) && !empty($eventBooks->check_in_date)){{ date("Y-m-d",$eventBooks->check_in_date)}} @else N/A @endif</td>
                    <td>@if(isset($eventBooks->check_in_time) && !empty($eventBooks->check_in_time)){{ date("H:i",$eventBooks->check_in_time)}} @else N/A @endif</td>
                    <td>@if(isset($eventBooks->check_operater) && !empty($eventBooks->check_operater)){{ date("Y-m-d",$eventBooks->check_operater)}} @else N/A @endif</td>
                    <td>@if($eventBooks->booking_status=="Paid") Enroled and Confirmed  @else {{$eventBooks->booking_status}} @endif</td>
                     <td>{{ $eventBooks->getEventApplication->email_address ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getEventApplication->title ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getEventApplication->gender ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getEventApplication->surname ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getEventApplication->given_name ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getEventApplication->chinese_name ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getEventApplication->hkid_card_no ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getEventApplication->passport_no ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getEventApplication->nationality ?? 'N/A'}}</td>
                     <td>@if(isset($eventBooks->getEventApplication->date_of_birth) && !empty($eventBooks->getEventApplication->date_of_birth)){{date('Y-m-d',$eventBooks->getEventApplication->date_of_birth)}} @else 'N/A' @endif</td>
                     <td>{{ $eventBooks->getEventApplication->mobile_tel_no ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getEventApplication->study_country ?? 'N/A'}}</td>
                
                </tr>
                @php $i++; @endphp
            @endforeach
        @else
        <td colspan="4"></td>
        <td>{{$notfoundlabel}}</td>
        <td  colspan="10"></td>
        @endif
    </tbody>
</table>