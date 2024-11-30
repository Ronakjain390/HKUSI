{!! Form::model($quotaRoomInfo, ['method' => 'PATCH','route' => ['admin.room.update', $quotaRoomInfo->id],'id' => 'quickForm','autocomplete' => 'off','class'=>'edit-form']) !!}
    <input type="hidden" name="hall_setting_id" value="{{$quotaRoomInfo->hall_setting_id}}">
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Create Date</th>
                        <td>@if(isset($quotaRoomInfo->created_at) && !empty($quotaRoomInfo->created_at)) {{date('Y-m-d' , strtotime($quotaRoomInfo->created_at))}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Create Time</th>
                        <td>@if(isset($quotaRoomInfo->created_at) && !empty($quotaRoomInfo->created_at)) {{date('h:i:s' , strtotime($quotaRoomInfo->created_at))}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Room Code</th>
                        <td><input type="text" name="room_code" value="{{$quotaRoomInfo->room_code}}" class="form-control" placeholder="Room Code"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Hall/College Name</th>
                        <td>@if(isset($quotaRoomInfo->college_name) && !empty($quotaRoomInfo->college_name)) {{$quotaRoomInfo->college_name}} <input type="hidden" name="college_name" class="form-control" placeholder="Quota ID" value="{{$quotaRoomInfo->college_name}}" >@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Start Date</th>
                        <td>@if(isset($quotaRoomInfo->getQuotaHallDetail->start_date) && !empty($quotaRoomInfo->getQuotaHallDetail->start_date)) {{date('Y-m-d' , $quotaRoomInfo->getQuotaHallDetail->start_date)}} <input type="hidden" name="start_date" class="form-control" placeholder="Start Date" value="{{date('Y-m-d' , $quotaRoomInfo->getQuotaHallDetail->start_date)}}" >@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">End Date</th>
                        <td>@if(isset($quotaRoomInfo->getQuotaHallDetail->end_date) && !empty($quotaRoomInfo->getQuotaHallDetail->end_date)) {{date('Y-m-d' , $quotaRoomInfo->getQuotaHallDetail->end_date)}} <input type="hidden" name="end_date" class="form-control" placeholder="End Date" value="{{date('Y-m-d' , $quotaRoomInfo->getQuotaHallDetail->end_date)}}" >@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Gender</th>
                        <td>
                            <select name="gender" class="form-control">
                                <option value="Male" @if(isset($quotaRoomInfo->gender) && $quotaRoomInfo->gender == 'Male') Selected @endif>Male</option>
                                <option value="Female" @if(isset($quotaRoomInfo->gender) && $quotaRoomInfo->gender == 'Female') Selected @endif>Female</option>
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
                            <select  class="form-control" name="status" style="background-color: #fff;">
                                <option value="">Select Status</option>
                                <option value="1" @if(isset($quotaRoomInfo->status) && $quotaRoomInfo->status == '1') selected @endif >Enabled</option>
                                <option value="0" @if(isset($quotaRoomInfo->status) && $quotaRoomInfo->status == '0') selected @endif >Disabled</option>
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
            <button type="refresh" class="btn cancel-btn">Delete</button>
        </div>
    </div>
{!! Form::close() !!}

@push('foorterscript')

<script>
        $().ready(function () {
 
            $("#quickForm").validate({
                // in 'rules' user have to specify all the constraints for respective fields
                rules: {
                    college_name: "required",
                    start_date: "required",
                    end_date: "required",
                    gender: "required",
                },
                // in 'messages' user have to specify message as per rules
                messages: {
                    college_name: "Please select a College name",
                    start_date: "Please select a start date",
                    end_date: "Please select a end date",
                    gender: {
                        required: "Please select a male field"
                    },
                    
                }
            });
        });
 
    </script>

@endpush