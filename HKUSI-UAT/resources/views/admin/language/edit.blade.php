  @extends('admin.layouts.index')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
  {!! Form::model($language, ['method' => 'PATCH','route' => ['admin.language.update', $language->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
    <input type="hidden" name="user_id" value="{{$language->user_id}}">
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
          
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Create Date</th>
                        <td>@if(isset($language->created_at) && !empty($language->created_at)){{date('Y-m-d' , strtotime($language->created_at))}}@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Create Time</th>
                        <td>@if(isset($language->created_at) && !empty($language->created_at)){{date('h:i:s' , strtotime($language->created_at))}}@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Name</th>
                        <td><input type="text" name="name" value="@if(isset($language->name) && !empty($language->name)){{$language->name}}@endif" class="form-control" placeholder="Name"></td>
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
                                <option value="1" @if(isset($language->status) && $language->status == '1') selected @endif>Active</option>
                                <option value="0" @if(isset($language->status) && $language->status == '0') selected @endif>Inactive</option>
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
    </div>
{!!Form::close()!!}
@endsection
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