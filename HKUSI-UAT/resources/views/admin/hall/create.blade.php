@extends('admin.layouts.index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
{!! Form::open(array('route' => 'admin.accommondation-setting.store','method'=>'POST','class'=>'edit-form','autocomplete' => 'off','files' =>false, 'id'=>'signupForm')) !!}
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Year</th>
                        <td><input type="text" name="year"  class="form-control" placeholder="Year"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Start Date</th>
                        <td><input type="text" readonly name="start_date" class="form-control datepicker" placeholder="Start Date"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">End Date</th>
                        <td><input type="text" readonly class="form-control datepicker" name="end_date"  placeholder="End Date"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Deadline</th>
                        <td><input type="text" readonly name="application_deadline" class="form-control datepicker" placeholder="Application Deadline"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Unit Price</th>
                        <td><input type="text" name="unit_price" onkeypress="return isNumber(event);" class="form-control" placeholder="Unit Price"></td>
                    </tr>
                    
                    <tr>
                        <th class="t-basic">Hall Result Days</th>
                        <td><input type="text" name="hall_result_days" onkeypress="return isNumber(event);" class="form-control" placeholder="Hall Result Days"></td>
                    </tr>
                    
                    <tr>
                        <th class="t-basic">Hall Payment Days</th>
                        <td><input type="text" name="hall_payment_days" onkeypress="return isNumber(event);" class="form-control" placeholder="Hall Payment Days"></td>
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
    <div class="content-backdrop fade "></div>
</div>
@endsection

@push('foorterscript')

<script>
        $().ready(function () {
 
            $("#signupForm").validate({
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