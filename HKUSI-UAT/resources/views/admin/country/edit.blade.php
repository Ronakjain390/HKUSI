@extends('admin.layouts.index')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
  {!! Form::model($data, ['method' => 'PATCH','route' => ['admin.country.update', $data->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
    <input type="hidden" name="user_id" value="{{$data->user_id}}">
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
          
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Create Date</th>
                        <td>@if(isset($data->created_at) && !empty($data->created_at)){{date('Y-m-d' , strtotime($data->created_at))}}@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Create time</th>
                        <td>@if(isset($data->created_at) && !empty($data->created_at)){{date('H:i:s' , strtotime($data->created_at))}}@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Name</th>
                        <td><input type="text" name="name" value="@if(isset($data->name) && !empty($data->name)){{$data->name}}@endif" class="form-control" placeholder="Programe Name"></td>
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
                                <option value="1" @if(isset($data->status) && $data->status == '1') selected @endif>Enable</option>
                                <option value="0" @if(isset($data->status) && $data->status == '0') selected @endif>Disable</option>
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
<!-- / Content -->
<div class="content-backdrop fade "></div>
<!-- Content wrapper -->
</div>
    <!-- / Layout page -->
@endsection

@push('foorterscript')
<script>
    $().ready(function () {
        $("#quickForm").validate({
            rules: {
                name: "required",
                status: {
                    required: true,
                },
            },
            messages: {
                name: "Please enter a country name",
                status: {
                    required: "Please select a status",
                },
            }
        });
    });
</script>
@endpush