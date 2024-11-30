@extends('admin.layouts.index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {!! Form::open(array('route' => 'admin.room.store','method'=>'POST','class'=>'edit-form','autocomplete' => 'off','files' =>false, 'id'=>'signupForm')) !!}
        <input type="hidden" name="quota_hall_id" value="{{$dataId}}">
        <div class="card custom-card">
            <div class="basic-details">
                <h6 class="card-heading">Basic Info</h6>
            </div>
            <div class="table-responsive table-details">
                <table class="table">
                    <tbody>
                    
                        <tr>
                            <th class="t-basic">Room Code</th>
                            <td><input type="text" name="room_code" value="" class="form-control" placeholder="Room Code"></td>
                        </tr>
                        <tr>
                            <th class="t-basic">Hall/College Name</th>
                            <td>@if(isset($quotaHall->college_name) && !empty($quotaHall->college_name)) {{$quotaHall->college_name}} <input type="hidden" name="college_name" id="college_name" value="{{$quotaHall->college_name}}" class="form-control" placeholder="Hall/College Name" >@endif</td>
                        </tr>
                        <tr>
                            <th class="t-basic">Start Date</th>
                            <td>@if(isset($quotaHall->start_date) && !empty($quotaHall->start_date)) {{date('Y-m-d' , $quotaHall->start_date)}} <input type="hidden" name="start_date" id="start_date" class="form-control" value="{{date('Y-m-d' , $quotaHall->start_date)}}" placeholder="Start Date"> @endif</td>
                        </tr>
                        <tr>
                            <th class="t-basic">End Date</th>
                            <td>@if(isset($quotaHall->end_date) && !empty($quotaHall->end_date)) {{date('Y-m-d' , $quotaHall->end_date)}} <input type="hidden" name="end_date" id="end_date" class="form-control" placeholder="End Date" value="{{date('Y-m-d' , $quotaHall->end_date)}}">@endif</td>
                        </tr>
                        <tr>
                            <th class="t-basic">Gender</th>
                            <td>
                                <select name="gender" id="gender" class="form-control">
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card custom-card">
            <div class="form-btn">
                <button type="submit" class="btn action-btn">Save</button>
            </div>
        </div>
    {!! Form::close() !!}

    <div class="content-backdrop fade "></div>
    <!-- Content wrapper -->
</div>
@endsection

@push('foorterscript')

<script>
        $().ready(function () {
 
            $("#signupForm").validate({
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