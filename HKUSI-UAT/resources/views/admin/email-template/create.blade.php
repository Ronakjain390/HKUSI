@extends('admin.layouts.index')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
            {!! Form::open(array('route' => 'admin.email-template.store','method'=>'POST','class'=>'edit-form','autocomplete' => 'off','files' =>false,'id'=>'quickForm')) !!}
            <div class="card custom-card profile-details">
                <div class="basic-details">
                    <h6 class="card-heading">Basic Info</h6>
                </div>
                <div class="table-responsive table-details">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Title</th> 
                                <td><input type="text" name="title" required class="form-control" placeholder="Title"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive table-details">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Description</th> 
                                <td><textarea  name="description" required class="form-control article-ckeditor"  placeholder="Title"></textarea></td>
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
                    <button type="reset" class="btn cancel-btn">Delete</button>
                </div>
            </div>
        {!! Form::close() !!}
        <!-- / Content -->
        <div class="content-backdrop fade "></div>
        <!-- Content wrapper -->
    </div>
    <!-- / Layout page -->
@endsection
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
@push('foorterscript')
<script>
     CKEDITOR.replaceClass="article-ckeditor";
    $().ready(function () {
        $("#quickForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                title: "required",
                status: {
                    required: true,
                },
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                title: "Please enter a title",
                status: {
                    required: "Please select a status",
                },
            }
        });
    });

</script>
@endpush