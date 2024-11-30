  @extends('admin.layouts.index')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
  {!! Form::model($diningTokens, ['method' => 'PATCH','route' => ['admin.dining-token.update', $diningTokens->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
    <input type="hidden" name="user_id" value="{{$diningTokens->user_id}}">
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
          
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Create Date</th>
                        <td>@if(isset($diningTokens->created_at) && !empty($diningTokens->created_at)){{date('Y-m-d' , strtotime($diningTokens->created_at))}}@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Create Time</th>
                        <td>@if(isset($diningTokens->created_at) && !empty($diningTokens->created_at)){{date('h:i:s' , strtotime($diningTokens->created_at))}}@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Quanninty</th>
                        <td><input type="text" name="quantity" value="@if(isset($diningTokens->quantity) && !empty($diningTokens->quantity)){{$diningTokens->quantity}}@endif" class="form-control quantity" placeholder="Quanninty"></td>
                    </tr> 
					<tr>
                        <th class="t-basic">Unit Price</th>
                        <td><input type="text" name="unit_price" value="@if(isset($diningTokens->unit_price) && !empty($diningTokens->unit_price)){{$diningTokens->unit_price}}@endif" class="form-control unit_price" placeholder="Unit Price"></td>
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
                                <option value="1" @if(isset($diningTokens->status) && $diningTokens->status == '1') selected @endif>Enable</option>
                                <option value="0" @if(isset($diningTokens->status) && $diningTokens->status == '0') selected @endif>Disable</option>
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