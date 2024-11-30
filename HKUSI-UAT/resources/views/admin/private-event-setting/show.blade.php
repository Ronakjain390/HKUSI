{{-- This view created by Akash --}}
<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Basic Info</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Create Date</th>
                    <td>@if(isset($eventInfo->created_at) && !empty($eventInfo->created_at)) {{date('Y-m-d' , strtotime($eventInfo->created_at))}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Create Time</th>
                    <td>@if(isset($eventInfo->created_at) && !empty($eventInfo->created_at)) {{date('h:i:s' , strtotime($eventInfo->created_at))}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Event #</th>
                    <td>@if(isset($eventInfo->id) && !empty($eventInfo->id)) #{{$eventInfo->id}} @endif</td>
                </tr>  
                <tr>
                    <th class="t-basic">Year</th>
                    <td>{{$eventInfo->getYearDetails->year ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Event Name</th>
                    <td>@if(isset($eventInfo->event_name) && !empty($eventInfo->event_name)) {{$eventInfo->event_name}} @endif</td>
                </tr>
                 <tr>
                    <th class="t-basic">Short Description</th>
                    <td>{{$eventInfo->short_description ?? ''}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Description</th>
                    <td>@if(isset($eventInfo->description) && !empty($eventInfo->description)) {{$eventInfo->description}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Location</th>
                    <td>@if(isset($eventInfo->location) && !empty($eventInfo->location)) {{$eventInfo->location}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Assembly Location</th>
                    <td>{{$eventInfo->assembly_location ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Assembly Start Time</th>
                    <td> @if(isset($eventInfo->assembly_start_time) && !empty($eventInfo->assembly_start_time)) {{date('H:i',$eventInfo->assembly_start_time)}} @else N/A @endif </td>
                </tr>
                <tr>
                    <th class="t-basic">Assembly End Time</th>
                    <td> @if(isset($eventInfo->assembly_end_time) && !empty($eventInfo->assembly_end_time)) {{date('H:i',$eventInfo->assembly_end_time)}} @else N/A @endif </td>
                </tr>
                <tr>
                    <th class="t-basic">Date</th>
                    <td>@if(isset($eventInfo->date) && !empty($eventInfo->date)) {{date('Y-m-d',$eventInfo->date)}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Start Time</th>
                    <td>@if(isset($eventInfo->start_time) && !empty($eventInfo->start_time)) {{date('H:i',$eventInfo->start_time)}} @else N/A @endif</td>
                </tr>
				 <tr>
                    <th class="t-basic">End Time</th>
                    <td>@if(isset($eventInfo->end_time) && !empty($eventInfo->end_time)) {{date('H:i',$eventInfo->end_time)}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Quota(s)</th>
                    <td>@if(isset($eventInfo->quota) && !empty($eventInfo->quota)) {{$eventInfo->quota}} @else 0 @endif</td>
                </tr>
                 <tr>
                    <th class="t-basic">Quota Balance</th>
                    <td>@if(isset($eventInfo->quota_balance) && !empty($eventInfo->quota_balance)) {{$eventInfo->quota_balance}} @else 0 @endif </td>
                </tr>
                <tr>
                    <th class="t-basic">Unit Price</th>
                    <td>@if(isset($eventInfo->unit_price) && !empty($eventInfo->unit_price)) ${{number_format($eventInfo->unit_price , 2)}} @else Free @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Additional Info</th>
                    <td>@if(isset($eventInfo->additional_info) && !empty($eventInfo->additional_info)) {{$eventInfo->additional_info}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Booking Limit</th>
                    <td>@if(isset($eventInfo->booking_limit) && !empty($eventInfo->booking_limit)) {{$eventInfo->booking_limit}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Type</th>
                    <td>@if(isset($eventInfo->getCategoryDetails->name) && !empty($eventInfo->getCategoryDetails->name)) {{$eventInfo->getCategoryDetails->name}} @endif</td>
                </tr>
                 <tr>
                    <th class="t-basic">Language</th>
                    <td>@if(isset($eventInfo->getLanguage->name) && !empty($eventInfo->getLanguage->name)) {{$eventInfo->getLanguage->name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Deadline</th>
                    <td>@if(isset($eventInfo->application_deadline) && !empty($eventInfo->application_deadline)) {{date('Y-m-d',$eventInfo->application_deadline)}} @endif</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Note</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Terms & Condition</th>
                       <td> @if(isset($eventInfo->terms_condition) && !empty($eventInfo->terms_condition)) {{$eventInfo->terms_condition}} @endif</td>
                    </tr>
                     <tr>
                        <th class="t-basic">Terms Link</th>
                       <td>@if(isset($eventInfo->terms_link) && !empty($eventInfo->terms_link)) {{$eventInfo->terms_link}} @endif</td>
                    </tr>
                     <tr>
                        <th class="t-basic">Pre-arrival</th>
                       <td>@if(isset($eventInfo->pre_arrival) && !empty($eventInfo->pre_arrival)) {{$eventInfo->pre_arrival}} @endif</td>
                    </tr>
                     <tr>
                        <th class="t-basic">Pre-arrival Link</th>
                       <td>@if(isset($eventInfo->pre_link) && !empty($eventInfo->pre_link)) {{$eventInfo->pre_link}} @endif</td>
                    </tr>
                     <tr>
                        <th class="t-basic">Notes</th>
                        <td>@if(isset($eventInfo->notes) && !empty($eventInfo->notes))  {!! nl2br($eventInfo->notes)!!} @endif
                        </td>
                    </tr> 

                </tbody>
            </table>
        </div>
    </div>
