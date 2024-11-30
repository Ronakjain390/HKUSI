@extends('admin.layouts.index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {!! Form::open(array('route' => 'admin.quota.store','method'=>'POST','class'=>'edit-form','autocomplete' => 'off','files' =>false, 'id'=>'quickForm')) !!}
    <input type="hidden" name="hall_setting_id" value="{{$dataId}}">
    <input type="hidden" name="url" value="{{Request::segment(2)}}">
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Start Date</th>
                        <td><input type="text" name="start_date" id="start_date" readonly class="form-control" placeholder="Start Date"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">End Date</th>
                        <td><input type="text" name="end_date" id="end_date" readonly class="form-control" placeholder="End Date"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Actual Male</th>
                        <td><input type="text" name="male" id="male" onchange="return totalQuota(this.value , 'male')" onkeypress="return isNumber(event);" class="form-control" placeholder="Male" maxlength="9" min="0" max="999999999"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Actual Female</th>
                        <td><input type="text" name="female" id="female" onchange="return totalQuota(this.value , 'female')" onkeypress="return isNumber(event);" class="form-control" placeholder="Female" maxlength="9" min="0" max="999999999"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Actual Total Quota</th>
                        <td><span id="totalQuotaSpan"></span> <input type="hidden" readonly name="total_quotas" onkeypress="return isNumber(event);" id="total_quotas" class="form-control" placeholder="Total Quota" ></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Max Male Quota Limit</th>
                        <td><input type="text" name="male_max_quota" onkeypress="return isNumber(event);" id="male_max_quota" onchange="return totalQuotaBalance(this.value , 'max_male')" class="form-control" placeholder="Male Quota Limit" maxlength="9" min="0" max="999999999"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Max Female Quota Limit</th>
                        <td><input type="text" name="female_max_quota" onkeypress="return isNumber(event);" id="female_max_quota" onchange="return totalQuotaBalance(this.value , 'max_female')" class="form-control" placeholder="Female Quota Limit" maxlength="9" min="0" max="999999999"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Quota Balance </th>
                        <td> <span id="totalQuotaBalanceSpan"></span> <input type="hidden" readonly id="quota_balance" name="quota_balance" onkeypress="return isNumber(event);" id="quota_balance" class="form-control" placeholder="Quota Balance" ></td>
                    </tr>
                    <tr>
                        <th class="t-basic" style="width: 180px;">Hall Confirmation Days</th>
                        <td><input type="text" name="hall_confirmation_date" class="form-control datepicker" placeholder="Hall Confirmation Date"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Status Country</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Status Country</th>
                        <td>
                            <select name="countries[]" class="form-select"  multiple multiselect-search="true" multiselect-select-all="true">
                                @if(isset($countries) && count($countries))  
                                    @foreach($countries as $countryData)
                                        <option value="{{$countryData->id}}" >{{$countryData->name}}</option>
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
            <h6 class="card-heading">Programme</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Programme</th>
                        <td>
                            <select name="programmes[]" class="form-select" multiple multiselect-search="true" multiselect-select-all="true">  
                                @if(isset($programme) && count($programme))  
                                    @foreach($programme as $programmeData)
                                        <option value="{{$programmeData->id}}">{{$programmeData->programme_code}} / {{$programmeData->programme_name}}</option>
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
                                <option value="1">Released </option>
                                <option value="0">Pending</option>
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
        </div>
    </div>
{!! Form::close() !!}
</div>
@endsection

@push('foorterscript')

<div class="content-backdrop fade "></div> 
@push('foorterscript')
<style>
.multiselect-dropdown { border:solid 1px #ced4da !important; }
</style>
@if($message = Session::get('programmeQuatoError'))
<script type="text/javascript"> 
    // $( document ).ready(function() {
        Swal.fire({
              text: "{{$message}}",
              icon: "error",
              showCancelButton: false,
              showConformButton: true,
              confirmButtonColor: "#6fc5e0",
              conformButtonColor: "#2dcb2d",
              confirmButtonText: "Ok",
              timer: 5000,
        });
    // });
</script>
@endif
<script>
    $().ready(function () {
        $("#quickForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                start_date: "required",
                end_date: {
                    required: true,
                },
                check_in_date: {
                    required: true,
                },
                check_out_date: {
                    required: true,
                },
                total_quotas: {
                    required: true,
                },
                quota_balance: {
                    required: true,
                },
                male: {
                    required: true,
                },
                female: {
                    required: true,
                },
                male_max_quota:{
                    required: true,
                },
                female_max_quota:{
                    required: true,
                },
                hall_confirmation_date:{
                    required: true,
                },
                status:{
                    required: true,
                },
                'countries[]': "required",
                'programmes[]': "required",
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                start_date: "Please Choose a start date",
                end_date: {
                    required: "Please Choose a end date",
                },
                check_in_date: "Please Choose a check in date",
                check_out_date: {
                    required: "Please Choose a check out date",
                },
                total_quotas: {
                    required: "Please select total quotas",
                },
                quota_balance: {
                    required: "Please select a quota balance",
                },
                male: {
                    required: "Please select a male field",
                },
                female: "Please select a female field",
                male_max_quota: {
                    required: "Please enter male quota limit",
                },
                female_max_quota: {
                    required: "Please enter female quota limit",
                },
                hall_confirmation_date: {
                    required: "Please select hall confirmation days",
                },
                status: {
                    required: "Please select a status",
                },
                'countries[]': "Please select any one",
                'programmes[]': "Please select any one",
            }
        });
    });

    function totalQuota(val , type){
        if (type == 'female') {
            var femaleValue = val ;
        }else{
            var femaleValue = $('#female').val() ;
        }
        if (type == 'male') {
            var maleValue = val ;
        }else{
            var maleValue = $('#male').val() ;
        }
        if (femaleValue != '' && maleValue != '') {
            var totalValue = parseInt(maleValue) + parseInt(femaleValue);
        }else {
            if(femaleValue != ''){
                var totalValue = parseInt(femaleValue);
            }else{
                if (maleValue != '') {
                    var totalValue = parseInt(maleValue);
                }
            }
        }
        $('#total_quotas').val(totalValue);
        $('#totalQuotaSpan').html(totalValue);
    }

    function totalQuotaBalance(val , type){
        if (type == 'max_female') {
            var femaleValue = val ;
        }else{
            var femaleValue = $('#female_max_quota').val() ;
        }
        if (type == 'max_male') {
            var maleValue = val ;
        }else{
            var maleValue = $('#male_max_quota').val() ;
        }
        if (femaleValue != '' && maleValue != '') {
            var totalValue = parseInt(maleValue) + parseInt(femaleValue);
        }else {
            if(femaleValue != ''){
                var totalValue = parseInt(femaleValue);
            }else{
                if (maleValue != '') {
                    var totalValue = parseInt(maleValue);
                }
            }
        }
        $('#totalQuotaBalanceSpan').html(totalValue);
        $('#quota_balance').val(totalValue);
    }


    $('#start_date,#end_date').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        @if(!empty($hallInfo->start_date))
        startDate:  new Date('{{date("Y-m-d",$hallInfo->start_date)}}'), // Set start Date
        @endif
        @if(!empty($hallInfo->end_date))
        endDate: new Date('{{date("Y-m-d",$hallInfo->end_date)}}'), // Set end Date
        @endif
    });
    $('#check_in_date,#check_out_date').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
    });

</script>
@endpush