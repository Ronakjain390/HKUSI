<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Basic Info</h6>
        @php $totalamount = 0;
        foreach($eventbookingInfo->getEventBookingDetails as $key=> $valuedeata){
        $unit = (isset($valuedeata->unit_price) && !empty($valuedeata->unit_price))?$valuedeata->unit_price:'0';
        $totalamount += $valuedeata->no_of_seats * $unit ;

        }
        @endphp
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Create Date</th>
                    <td>{{date('Y-m-d' , strtotime($eventbookingInfo->created_at)) ?? '' }} </td>
                </tr>
                <tr>
                    <th class="t-basic">Create Time</th>
                    <td>{{date('h:i:s' , strtotime($eventbookingInfo->created_at)) ?? ''}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Year</th>
                    <td>{{$eventbookingInfo->getyearSilgle->getEventSetting->getYearDetails->year ?? ''}} </td>
                </tr>
                <tr>
                    <th class="t-basic">Booking #</th>
                    <td>#{{$eventbookingInfo->payment_id ?? ''}} </td>
                </tr>
                <tr>
                    <th class="t-basic">Amount</th>
                    <td>{{$totalamount ?? ''}} </td>
                </tr>
                <tr>
                    <th class="t-basic">Updated Date</th>
                    <td>{{date('Y-m-d' , strtotime($eventbookingInfo->updated_at)) ?? '' }} </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
{{-- @php
    dd($eventbookingInfo->getEventBookingDetails->toArray());
@endphp --}}
@if(isset($eventbookingInfo->getEventBookingDetails) && !empty($eventbookingInfo->getEventBookingDetails))
@foreach($eventbookingInfo->getEventBookingDetails as $key=> $valuedeata)
<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Event Info</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Event #</th>
                    <td>{{$valuedeata->event_id ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Event Name</th>
                    <td>{{ $valuedeata->getEventSetting->event_name ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Date</th>
                    <td>{{ date("Y-m-d",$valuedeata->getEventSetting->date) ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Start Time</th>
                    <td>{{ date("H:i",$valuedeata->getEventSetting->start_time) ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">End Time</th>
                    <td>{{ date("H:i",$valuedeata->getEventSetting->end_time) ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Location</th>
                    <td>{{ $valuedeata->getEventSetting->location ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Assembly Start Time </th>
                    <td>{{ date("H:i",$valuedeata->getEventSetting->assembly_start_time) ?? 'N/A'}}</td>
                </tr>
                 <tr>
                    <th class="t-basic">Assembly End Time </th>
                    <td>{{ date("H:i",$valuedeata->getEventSetting->assembly_end_time) ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Assembly Location </th>
                    <td>{{ $valuedeata->getEventSetting->assembly_location ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">No Of Tickets</th>
                    <td>{{$valuedeata->no_of_seats ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Unit Price</th>
                    <td>{{ $valuedeata->unit_price ?? 'N/A'}}</td>
                </tr>
                @php
                $unit_price = (isset($valuedeata->unit_price) &&
                !empty($valuedeata->unit_price))?$valuedeata->unit_price:'0';
                $amount = $valuedeata->no_of_seats * $unit_price ;
                @endphp
                <tr>
                    <th class="t-basic">Amount</th>
                    <td>{{$amount}}</td>
                </tr>
                <tr>
                    <th class="t-basic" style="width: 163px;">Actual Check-in Date</th>
                    <td>@if(isset($valuedeata->check_in_date) &&
                        !empty($valuedeata->check_in_date)){{ date("Y-m-d",$valuedeata->check_in_date)}} @else N/A
                        @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Actual Check-in Time</th>
                    <td>@if(isset($valuedeata->check_in_time) &&
                        !empty($valuedeata->check_in_time)){{ date("H:i",$valuedeata->check_in_time)}} @else N/A
                        @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Check-in Operator</th>
                    <td>@if(isset($valuedeata->getCheckOperator->surname) &&
                        !empty($valuedeata->getCheckOperator->surname)){{ $valuedeata->getCheckOperator->surname }}
                        {{ $valuedeata->getCheckOperator->given_name }} @else N/A
                        @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Status</th>
                    <td>
                        @if($valuedeata->booking_status=="Paid") Enrolled and Confirmed @else
                        {{$valuedeata->booking_status}} @endif
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
@endforeach
@endif

<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Member Info</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Application #</th>
                    <td>{{ $memberinfo->application_number ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Email Address</th>
                    <td>{{ $memberinfo->email_address ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Title</th>
                    <td>{{ $memberinfo->title ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Gender</th>
                    <td>{{ $memberinfo->gender ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Surname</th>
                    <td>{{ $memberinfo->surname ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Given Name</th>
                    <td>{{ $memberinfo->given_name ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Chiness Name</th>
                    <td>{{ $memberinfo->chinese_name ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">HKID</th>
                    <td>{{ $memberinfo->hkid_card_no ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Passport No.</th>
                    <td>{{ $memberinfo->passport_no ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Nationality</th>
                    <td>{{ $memberinfo->nationality ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Date Of Birth</th>
                    <td>{{ !empty($memberinfo->date_of_birth) ? date('Y-m-d',$memberinfo->date_of_birth) : 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Mobile No.</th>
                    <td>{{ $memberinfo->mobile_tel_no ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Studey Country</th>
                    <td>{{ $memberinfo->study_country ?? 'N/A'}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>