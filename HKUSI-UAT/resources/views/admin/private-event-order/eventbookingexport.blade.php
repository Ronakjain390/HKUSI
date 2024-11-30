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

                    <td> @if(isset($eventBooks->booking_id) && !empty($eventBooks->booking_id)) # {{$eventBooks->booking_id}} @endif</td>

                    <td> @if(isset($eventBooks->application_id) && !empty($eventBooks->application_id)) {{$eventBooks->application_id}} @else N/A @endif</td>

                    <td>{{$eventBooks->event_id ?? 'N/A'}}</td>

                    <td>{{ $eventBooks->getEventDetails->event_name ?? 'N/A'}}</td>

                    <td>{{ date("Y-m-d",$eventBooks->getEventDetails->date) ?? 'N/A'}}</td>

                    <td>{{ date("H:i",$eventBooks->getEventDetails->start_time) ?? 'N/A'}}</td>

                    <td>{{ date("H:i",$eventBooks->getEventDetails->end_time) ?? 'N/A'}}</td>

                    <td>{{ $eventBooks->getEventDetails->location ?? 'N/A'}}</td>

                    <td>{{ date("H:i",$eventBooks->getEventDetails->assembly_start_time) ?? 'N/A'}}</td>

                    <td>{{ date("H:i",$eventBooks->getEventDetails->assembly_end_time) ?? 'N/A'}}</td>

                    <td>{{ $eventBooks->getEventDetails->assembly_location ?? 'N/A'}}</td>

                    <td>@if(isset($eventBooks->check_in_date) && !empty($eventBooks->check_in_date)){{ date("Y-m-d",$eventBooks->check_in_date)}} @else N/A @endif</td>
                    <td>@if(isset($eventBooks->check_in_time) && !empty($eventBooks->check_in_time)){{ date("H:i",$eventBooks->check_in_time)}} @else N/A @endif</td>
                    <td>@if(isset($eventBooks->getOperatorDetails ) && !empty($eventBooks->getOperatorDetails )){{ $eventBooks->getOperatorDetails->name }} @else N/A @endif</td>
                    <td>@if($eventBooks->booking_status=="Paid") Enroled and Confirmed  @else {{$eventBooks->booking_status}} @endif</td>
                     <td>{{ $eventBooks->getMemberInfo->email_address ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getMemberInfo->title ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getMemberInfo->gender ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getMemberInfo->surname ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getMemberInfo->given_name ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getMemberInfo->chinese_name ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getMemberInfo->hkid_card_no ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getMemberInfo->passport_no ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getMemberInfo->nationality ?? 'N/A'}}</td>
                     <td>@if(isset($eventBooks->getMemberInfo->date_of_birth) && !empty($eventBooks->getMemberInfo->date_of_birth)){{date('Y-m-d',$eventBooks->getMemberInfo->date_of_birth)}} @else 'N/A' @endif</td>
                     <td>{{ $eventBooks->getMemberInfo->mobile_tel_no ?? 'N/A'}}</td>
                     <td>{{ $eventBooks->getMemberInfo->study_country ?? 'N/A'}}</td>
                
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