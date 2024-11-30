  {!! Form::model($HallInfo, ['method' => 'PATCH','route' => ['admin.accommondation-setting.update', $HallInfo->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">    
          
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Create Date</th>
                        <td>@if(isset($HallInfo->created_at) && !empty($HallInfo->created_at)) {{date('Y-m-d' , strtotime($HallInfo->created_at))}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Create Time</th>
                        <td>@if(isset($HallInfo->created_at) && !empty($HallInfo->created_at)) {{date('h:i:s' , strtotime($HallInfo->created_at))}} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Year</th>
                        <td><input type="text" name="year" value="@if(isset($HallInfo->year) && !empty($HallInfo->year)){{$HallInfo->year}}@endif" class="form-control" placeholder="Year"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Start Date</th>
                        <td><input type="text" name="start_date" value="@if(isset($HallInfo->start_date) && !empty($HallInfo->start_date)){{date('Y-m-d',$HallInfo->start_date)}}@endif" class="form-control datepicker" readonly placeholder="Given Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">End Date</th>
                        <td><input type="text" class="form-control datepicker" readonly name="end_date" value="@if(isset($HallInfo->end_date) && !empty($HallInfo->end_date)){{date('Y-m-d',$HallInfo->end_date)}}@endif" placeholder="End Date"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Deadline</th>
                        <td><input type="text" name="application_deadline" readonly value="@if(isset($HallInfo->application_deadline) && !empty($HallInfo->application_deadline)){{date('Y-m-d',$HallInfo->application_deadline)}}@endif" class="form-control datepicker" placeholder="Male"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Unit Price</th>
                        <td><input type="text" name="unit_price" value="@if(isset($HallInfo->unit_price) && !empty($HallInfo->unit_price)){{$HallInfo->unit_price}}@endif" onkeypress="return isNumber(event);" class="form-control" placeholder="Male"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Hall Result Days</th>
                        <td><input type="text" name="hall_result_days" value="@if(isset($HallInfo->hall_result_days) && !empty($HallInfo->hall_result_days)){{$HallInfo->hall_result_days}}@endif" onkeypress="return isNumber(event);" class="form-control" placeholder="Hall Result Days"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Hall Payment Days</th>
                        <td><input type="text" name="hall_payment_days" value="@if(isset($HallInfo->hall_payment_days) && !empty($HallInfo->hall_payment_days)){{$HallInfo->hall_payment_days}}@endif" onkeypress="return isNumber(event);" class="form-control" placeholder="Hall Payment Days"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Actual Male</th>
                        <td>@if(!empty($HallInfo->getQuotaDetail->sum('male'))) {{$HallInfo->getQuotaDetail->sum('male')}} @else 0 @endif</td>
                    </tr>
                     <tr>
                        <th class="t-basic">Actual Female</th>
                        <td>@if(!empty($HallInfo->getQuotaDetail->sum('female'))) {{$HallInfo->getQuotaDetail->sum('female')}} @else 0 @endif</td>
                    </tr>
                     <tr>
                        <th class="t-basic">Actual Total Quota</th>
                        <td>@if(!empty($HallInfo->getQuotaDetail->sum('total_quotas'))) {{$HallInfo->getQuotaDetail->sum('total_quotas')}} @else 0 @endif</td>
                    </tr>
                     <tr>
                        <th class="t-basic">Max Male Quota Limit </th>
                        <td>@if(!empty($HallInfo->getQuotaDetail->sum('male_max_quota'))) {{$HallInfo->getQuotaDetail->sum('male_max_quota')}} @else 0 @endif</td>
                    </tr>
                     <tr>
                        <th class="t-basic">Max Female Quota Limit</th>
                        <td>@if(!empty($HallInfo->getQuotaDetail->sum('female_max_quota'))) {{$HallInfo->getQuotaDetail->sum('female_max_quota')}} @else 0 @endif</td>
                    </tr>
                        @php
                            $malemax = $HallInfo->getQuotaDetail->sum('male_max_quota');
                            $femalemax = $HallInfo->getQuotaDetail->sum('female_max_quota');
                            $totalmax = $malemax + $femalemax;
                        @endphp
                    <tr>
                        <th class="t-basic">Max Quota Limit</th>
                        <td>{{$totalmax}}</td>
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
                                <option value="1" @if(isset($HallInfo->status) && $HallInfo->status == '1') selected @endif>Enabled</option>
                                <option value="0" @if(isset($HallInfo->status) && $HallInfo->status == '0') selected @endif>Disabled</option>
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
                    created_at: "required",
                    year: "required",
                    start_date: "required",
                    end_date: "required",
                    application_deadline: "required",
                    unit_price: "required",
                    hall_result_days: "required",
                    hall_payment_days: "required",
                },
                // in 'messages' user have to specify message as per rules
                messages: {
                    created_at: "Please select a create date",
                    year: "Please choose a Year",
                    start_date: "Please select a start date",
                    end_date: "Please select a end date",
                    application_deadline: {
                        required: "Please select a Application Deadline"
                    },
                    unit_price: {
                        required: "Please select a unit price"
                    },
                    hall_result_days: {
                        required: "Please enter hall result days"
                    },
                    hall_payment_days: {
                        required: "Please enter hall payment days"
                    },
                    
                }
            });
        });
 
    </script>

@endpush