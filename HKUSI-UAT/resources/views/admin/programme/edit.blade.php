  {!! Form::model($programmeInfo, ['method' => 'PATCH','route' => ['admin.programme-setting.update', $programmeInfo->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
    <input type="hidden" name="user_id" value="{{$programmeInfo->user_id}}">
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
          
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Create Date</th>
                        <td>@if(isset($programmeInfo->created_at) && !empty($programmeInfo->created_at)){{date('Y-m-d' , strtotime($programmeInfo->created_at))}}@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Create Time</th>
                        <td>@if(isset($programmeInfo->created_at) && !empty($programmeInfo->created_at)){{date('h:i:s' , strtotime($programmeInfo->created_at))}}@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Year</th>
                        <td>
                            @php $year = ''; @endphp
                            @if (isset($programmeInfo->getProgrammeHallSetting) && count($programmeInfo->getProgrammeHallSetting))
                            @foreach($programmeInfo->getProgrammeHallSetting as $programmeYear)
                                @if(!empty($year))
                                    @php $year .= ', ' . $programmeYear->getHallSettingDetail->year; @endphp
                                @else
                                    @php $year .= $programmeYear->getHallSettingDetail->year; @endphp
                                @endif 
                            @endforeach
                        @endif
                        {{$year}}
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Programme Code</th>
                        <td><input type="text" name="programme_code"  value="@if(isset($programmeInfo->programme_code) && !empty($programmeInfo->programme_code)){{$programmeInfo->programme_code}}@endif" class="form-control" placeholder="Programme Code">
                            @error('programme_code')
                            <label class="error" for="programme_code">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Programme Name</th>
                        <td><input type="text" name="programme_name" value="@if(isset($programmeInfo->programme_name) && !empty($programmeInfo->programme_name)){{$programmeInfo->programme_name}}@endif" class="form-control" placeholder="Programme Name"></td>
                    </tr> 
                    <tr>
                        <th class="t-basic">Start Date</th>
                        <td><input type="text" name="start_date" value="@if(isset($programmeInfo->start_date) && !empty($programmeInfo->start_date)){{date('Y-m-d',$programmeInfo->start_date)}}@endif" class="form-control datepicker" readonly placeholder="Start Date"></td>
                    </tr> 
                    <tr>
                        <th class="t-basic">End Date</th>
                        <td><input type="text" name="end_date" value="@if(isset($programmeInfo->end_date) && !empty($programmeInfo->end_date)){{date('Y-m-d',$programmeInfo->end_date)}}@endif" class="form-control datepicker" readonly placeholder="End Date"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Members</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Members</th>
                        <td>
                            <select name="member[]" class="form-select"  multiple multiselect-search="true" multiselect-select-all="true">
                                @if(isset($members) && count($members))  
                                    @foreach($members as $memberdata)
                                        <option value="{{$memberdata->id}}" @if(isset($memberprogram) && !empty($memberprogram) && in_array($memberdata->id,$memberprogram)) Selected @endif>{{$memberdata->given_name}}</option>
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
                            <select class="form-control" name="status">
                                <option value="">Select Status</option>
                                <option value="1" @if(isset($programmeInfo->status) && $programmeInfo->status == '1') selected @endif>Enabled</option>
                                <option value="0" @if(isset($programmeInfo->status) && $programmeInfo->status == '0') selected @endif>Disabled</option>
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
    $().ready(function () {
        $("#quickForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                programme_name: "required",
                programme_code: {
                    required: true,
                },
                start_date: {
                    required: true,
                },
                end_date: {
                    required: true,
                },
                'member[]': {
                    required: true,
                },
                status: {
                    required: true,
                },
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                programme_name: "Please enter a programme name",
                programme_code: {
                    required: "Please enter a programme code",
                },
                start_date: "Please Choose a start date",
                end_date: {
                    required: "Please Choose a end date",
                },
                'member[]': {
                    required: "Please select any member",
                },
                status: {
                    required: "Please select a status",
                },
            }
        });
    });
</script>
@endpush