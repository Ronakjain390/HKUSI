{!! Form::model($bookingInfo, ['method' => 'PATCH','route' => ['admin.private-event-order.update', $bookingInfo->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
<input type="hidden" name="submit_type" value="basic">
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        @php
            $unit_price = (isset($eventInfo->unit_price) &&
            !empty($eventInfo->unit_price))?$eventInfo->unit_price:'0';
            $amount = $bookingInfo->no_of_seats * $unit_price ;
        @endphp
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Create Date</th>
                        <td>@if(isset($bookingInfo->created_at) && !empty($bookingInfo->created_at)) {{date('Y-m-d' , strtotime($bookingInfo->created_at))}} @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Create Time</th>
                        <td> @if(isset($bookingInfo->created_at) && !empty($bookingInfo->created_at)) {{date('h:i:s' , strtotime($bookingInfo->created_at))}} @endif</td>
                    </tr>
                
                    <tr>
                        <th class="t-basic">Booking #</th>
                        <td>@if(isset($bookingInfo->booking_id) && !empty($bookingInfo->booking_id)) #{{$bookingInfo->booking_id}} @else N/A @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Amount</th>
                        <td>{{ $amount ?? ''}}</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Updated Date</th>
                        <td>@if(isset($bookingInfo->updated_at) && !empty($bookingInfo->updated_at)) {{date('Y-m-d' , strtotime($bookingInfo->updated_at))}} @endif</td>
                    </tr>
                   
                </tbody>
            </table>
        </div>
    </div>  

    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Event Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    
                    <tr>
                        <th class="t-basic">Event #</th>
                        <td>@if(isset($bookingInfo->event_id) && !empty($bookingInfo->event_id)) #{{$bookingInfo->event_id}} @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Event Name </th>
                        <td>@if(isset($eventInfo->event_name) && !empty($eventInfo->event_name)) {{$eventInfo->event_name}} @else N/A @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Date </th>
                        <td>@if(isset($eventInfo->date) && !empty($eventInfo->date)) {{date('Y-m-d',$eventInfo->date)}} @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Session </th>
                        <td>@if(isset($eventInfo->start_time) && !empty($eventInfo->start_time)) {{date('H:i',$eventInfo->start_time)}} @endif @if(isset($eventInfo->end_time) && !empty($eventInfo->end_time)) - {{date('H:i',$eventInfo->end_time)}} @endif</td>
                    </tr>

                    
                    <tr>
                        <th class="t-basic">Location</th>
                        <td>@if(isset($eventInfo->location) && !empty($eventInfo->location)) {{$eventInfo->location}} @else N/A @endif</td>
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
                        <th class="t-basic">Assembly Location</th>
                        <td>{{$eventInfo->assembly_location ?? 'N/A'}}</td>
                    </tr>

                    <tr>
                        <th class="t-basic">No. of Ticket(s)</th>
                        <td>@if(isset($bookingInfo->no_of_seats) && !empty($bookingInfo->no_of_seats)) {{$bookingInfo->no_of_seats}} @else 0 @endif</td>
                    </tr>
                    
                    <tr>
                        <th class="t-basic">Unit Price</th>
                        <td>@if(isset($eventInfo->unit_price) && !empty($eventInfo->unit_price)) {{number_format($eventInfo->unit_price , 2)}} @else Free @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Amount</th>
                        <td>{{$amount}}</td>
                    </tr>

                    @hasanyrole('Super Admin|Admin')
                        <tr>
                            <th class="t-basic">Group</th>
                            <td><input type="text" name="event_group" id="date" class="form-control" @if(isset($bookingInfo->event_group) && !empty($bookingInfo->event_group)) value="{{ $bookingInfo->event_group }}" @endif placeholder="Group"></td>
                            @error('event_group')
                                <label class="error" for="event_group">{{$message}}</label>
                            @enderror
                        </tr>

                        <tr>
                            <th class="t-basic">Actual Check-in Date</th>
                            <td><input type="text" name="check_in_date" id="date" class="form-control datepicker" @if(isset($bookingInfo->check_in_date) && !empty($bookingInfo->check_in_date)) value="{{date('Y-m-d' , $bookingInfo->check_in_date)}}" @endif placeholder="Actual Check-in Date"></td>
                            @error('check_in_date')
                                <label class="error" for="check_in_date">{{$message}}</label>
                            @enderror
                        </tr>

                        <tr>
                            <th class="t-basic">Actual Check-in Time</th>
                            <td><input type="time" name="check_in_time" class="form-control" placeholder="Actual Check-in Time" @if(isset($bookingInfo->check_in_time) && !empty($bookingInfo->check_in_time)) value="{{date('H:i' , $bookingInfo->check_in_time)}}" @endif></td>
                            @error('check_in_time')
                                <label class="error" for="check_in_time">{{$message}}</label>
                            @enderror
                        </tr>

                        <tr>
                            <th class="t-basic">Check-in Operator</th>
                            <td>
                                <select class="form-select" id="selectmemberid" name="check_operator" >
                                    <option value="">Select Admin</option>
                                    @if(isset($memberInfo) && !$memberInfo->isEmpty())
                                        @foreach($memberInfo as $members)
                                            <option value="{{$members->id}}" @if( isset($bookingInfo) && $bookingInfo->check_operator == $members->id) selected @endif>{{$members->name}} ({{$members->given_name}})</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('check_operator')
                                    <label class="error" for="check_operator">{{$message}}</label>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th class="t-basic">Status</th>
                            <td>
                                <select @if(isset($bookingInfo->event_status) && $bookingInfo->event_status == '1') disabled @endif class="form-control" name="event_status" style="background-color: #fff;">
                                    <option value="">Select Status</option>
                                    <option value="Completed" @if(isset($bookingInfo->event_status) && $bookingInfo->event_status == 'Completed') selected @endif>Completed
                                    </option>
                                    <option value="Updated" @if(isset($bookingInfo->event_status) && $bookingInfo->event_status == 'Updated') selected @endif>Updated</option>
                                    <option value="Cancelled" @if(isset($bookingInfo->event_status) && $bookingInfo->event_status == 'Cancelled') selected @endif>Cancelled</option>
                                    <option value="Paid" @if(isset($bookingInfo->event_status) && $bookingInfo->event_status == 'Paid') selected @endif>Enroled and Confirmed </option>
                                    <option value="Pending" @if(isset($bookingInfo->event_status) && $bookingInfo->event_status == 'Pending') selected @endif>Pending</option>
                                </select>
                            </td>
                        </tr>
                    @endhasanyrole


                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card custom-card profile-details margin-b-20">
        <div class="basic-details">
            <h6 class="card-heading">Member Info</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                @php
                    $memberInfo = $bookingInfo->getMemberInfo;
                @endphp 
                <tbody>
                    <tr>
                        <th class="t-basic">Applicaton #</th>
                       <td> @if(isset($bookingInfo->application_id) && !empty($bookingInfo->application_id)) #{{$bookingInfo->application_id}} @else N/A @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Email Address </th>
                       <td> @if(isset($memberInfo->email_address) && !empty($memberInfo->email_address)) {{$memberInfo->email_address }} @else N/A @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Title </th>
                       <td> @if(isset($memberInfo->title) && !empty($memberInfo->title)) {{$memberInfo->title}} @else N/A @endif </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Gender </th>
                       <td> @if(isset($memberInfo->gender) && !empty($memberInfo->gender)) {{$memberInfo->gender }} @else N/A @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Surname </th>
                       <td> @if(isset($memberInfo->surname) && !empty($memberInfo->surname)) {{$memberInfo->surname }} @else N/A @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Given Name </th>
                       <td> @if(isset($memberInfo->given_name) && !empty($memberInfo->given_name)) {{$memberInfo->given_name }} @else N/A @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Chinese Name </th>
                       <td> @if(isset($memberInfo->chinese_name) && !empty($memberInfo->chinese_name)) {{$memberInfo->chinese_name }} @else N/A @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">HKID </th>
                       <td> @if(isset($memberInfo->hkid_card_no) && !empty($memberInfo->hkid_card_no)) {{$memberInfo->hkid_card_no }} @else N/A @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Passport No. </th>
                       <td> @if(isset($memberInfo->passport_no) && !empty($memberInfo->passport_no)) {{$memberInfo->passport_no }} @else N/A @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Nationality </th>
                       <td> @if(isset($memberInfo->nationality) && !empty($memberInfo->nationality)) {{$memberInfo->nationality }} @else N/A @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Date of Birth </th>
                       <td> @if(isset($memberInfo->date_of_birth) && !empty($memberInfo->date_of_birth) ) {{date('Y-m-d',$memberInfo->date_of_birth)}} @else N/A @endif</td>
                    </tr>

                    <tr>
                        <th class="t-basic">Mobile No. </th>
                       <td> @if(isset($memberInfo->mobile_tel_no) && !empty($memberInfo->mobile_tel_no)) {{$memberInfo->mobile_tel_no }} @else N/A @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Study Country</th>
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
        <div class="table-details select-table-custom">
            <table class="table">
                
                <tbody>
                    <tr>
                        <th class="t-basic">Status</th>
                        <td>
                            <select class="form-control" name="booking_status">
                                <option value="">Select Status</option>
                                <option value="Cancelled" @if(isset($bookingInfo->booking_status) && $bookingInfo->booking_status == 'Cancelled') selected @endif>Cancelled</option>
                                <option value="Paid" @if(isset($bookingInfo->booking_status) && $bookingInfo->booking_status == 'Paid') selected @endif>Paid</option>
                                <option value="Pending" @if(isset($bookingInfo->booking_status) && $bookingInfo->booking_status == 'Pending') selected @endif>Pending</option>
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
{!! Form::close() !!}

@push('foorterscript')

<style>
    .select2-container{
        max-width:245px !important;
    }
</style>
<script>
    
    $(document).ready(function() {
        
        $("#selectmemberid").select2({
        });
    });
</script>
@endpush

