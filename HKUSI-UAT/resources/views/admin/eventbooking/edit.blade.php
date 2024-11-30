<style type="text/css">
    .select2{
        max-width: 245px;
    }
</style>
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
                    <td>{{date('Y-m-d' , strtotime($eventbookingInfo->updated_at)) ?? '' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@if(isset($eventbookingInfo->getEventBookingDetails) && !empty($eventbookingInfo->getEventBookingDetails))
@foreach($eventbookingInfo->getEventBookingDetails as $key=> $valuedeata)
{!! Form::model($valuedeata, ['method' => 'PATCH','route' => ['admin.eventbooking.update', $valuedeata->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
<input type="hidden" name="id[]" value="{{$valuedeata->id}}">
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
                    <th class="t-basic">Seassion</th>
                    <td>{{ date("H:i",$valuedeata->getEventSetting->time) ?? 'N/A'}}</td>
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
                    $unit_price = (isset($valuedeata->unit_price) && !empty($valuedeata->unit_price))?$valuedeata->unit_price:'0';
                    $amount = $valuedeata->no_of_seats * $unit_price ;
                @endphp
                <tr>
                    <th class="t-basic">Amount</th>
                    <td>{{$amount}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Actual Check-in Date</th>
                    <td><input type="text" name="check_in_date[]" readonly required class="form-control datepicker" placeholder="Actual Check-in Date" @if(isset($valuedeata->check_in_date) && !empty($valuedeata->check_in_date)) value="{{date('Y-m-d' , $valuedeata->check_in_date)}}" @endif></td>
                </tr>
                <tr>
                    <th class="t-basic">Actual Check-in Date</th>
                    <td><input type="text" name="check_in_time[]" readonly required class="form-control timepicker" placeholder="Actual Check-in Date" @if(isset($valuedeata->check_in_time) && !empty($valuedeata->check_in_time)) value="{{date('H:i' , $valuedeata->check_in_time)}}" @endif></td>
                </tr>
                 <tr>
                    <th class="t-basic">Check-out Operator</th> 
                    <td> 
                        <select class="form-select" id="selectmemberid" name="check_operater[]">
                            <option value="">Select Check Operator</option>
                            @if(isset($users) && !empty($users))
                                @foreach($users as $checkorperatordata)
                                    <option value="{{$checkorperatordata->id}}" @if(isset($valuedeata->check_operater) && $valuedeata->check_operater == $checkorperatordata->id) selected @endif>{{ $checkorperatordata->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </td>
                </tr> 
                <tr>
                    <th class="t-basic">Status</th>
                    <td>
                        <select @if(isset($valuedeata->booking_status) && $valuedeata->booking_status == '1') disabled @endif class="form-control" name="status[]" style="background-color: #fff;">
                            <option value="">Select Status</option>
                            <option value="Completed" @if(isset($valuedeata->booking_status) && $valuedeata->booking_status == 'Completed') selected @endif>Completed 
                            </option>
                            <option value="Updated" @if(isset($valuedeata->booking_status) && $valuedeata->booking_status == 'Updated') selected @endif>Updated</option>
                            <option value="Cancelled" @if(isset($valuedeata->booking_status) && $valuedeata->booking_status == 'Cancelled') selected @endif>Cancelled</option>
                            <option value="Paid" @if(isset($valuedeata->booking_status) && $valuedeata->booking_status == 'Paid') selected @endif>Enrolled and Confirmed </option>
                            <option value="Pending" @if(isset($valuedeata->booking_status) && $valuedeata->booking_status == 'Pending') selected @endif>Pending</option>
                        </select>
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
                    <td>@if(isset($memberinfo->date_of_birth) && !empty($memberinfo->date_of_birth)){{date('Y-m-d',$memberinfo->date_of_birth)}} @else N/A @endif</td>
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

<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Order Status</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Status</th>
                    <td>
                        <select class="form-control" name="event_payment_status" style="background-color: #fff;">
                            <option value="">Select Status</option>
                            <option value="Cancelled" @if(isset($eventbookingInfo->event_payment_status) && $eventbookingInfo->event_payment_status == 'Cancelled') selected @endif>Cancelled</option>
                            <option value="Paid" @if(isset($eventbookingInfo->event_payment_status) && $eventbookingInfo->event_payment_status == 'Paid') selected @endif>Paid </option>
                            <option value="Pending" @if(isset($eventbookingInfo->event_payment_status) && $eventbookingInfo->event_payment_status == 'Pending') selected @endif>Pending</option>
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
        <button type="reset" class="btn cancel-btn">Delete</button>
    </div>
</div>
{!!Form::close()!!}
@push('foorterscript')
<script>
    $('#selectmemberid').select2({});
</script>
@endpush