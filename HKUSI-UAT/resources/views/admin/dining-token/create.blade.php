@extends('admin.layouts.index')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
            {!! Form::open(array('route' => 'admin.dining-token.store','method'=>'POST','class'=>'edit-form','autocomplete' => 'off','files' =>false,'id'=>'quickForm')) !!}
            <div class="card custom-card profile-details">
                <div class="basic-details">
                    <h6 class="card-heading">Basic Info</h6>
                </div>
                <div class="table-responsive table-details">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Quanninty</th> 
                                <td><input type="text" name="quantity" required class="form-control quantity" placeholder="Quanninty"></td>
                            </tr>
							 <tr>
                                <th class="t-basic">Unit Price</th> 
                                <td><input type="text" name="unit_price" required class="form-control unit_price" placeholder="Unit Price"></td>
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
                                        <option value="1">Enable</option>
                                        <option value="0">Disable</option>
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
                    <button type="reset" class="btn cancel-btn">Reset</button>
                </div>
            </div>
        {!! Form::close() !!}
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
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                name: "required",
                status: {
                    required: true,
                },
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                name: "Please enter a  name",
                
                status: {
                    required: "Please select a status",
                },
            }

             
             
        });
    });


</script>
@endpush