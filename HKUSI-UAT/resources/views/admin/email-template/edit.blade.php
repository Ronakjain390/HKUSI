{!! Form::model($EmailTemplate, ['method' => 'PATCH','route' => ['admin.email-template.update', $EmailTemplate->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
<input type="hidden" name="submit_type" value="basic">
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Title</th> 
                        <td><input type="text" name="title" required class="form-control" placeholder="Title" @if(isset($EmailTemplate->title) && !empty($EmailTemplate->title)) value="{{$EmailTemplate->title}}" @endif></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
 <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Content</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                     <tr>
                        <th class="t-basic">Description</th>
                        <td><textarea  name="description" required class="form-control article-ckeditor" placeholder="Description">@if(isset($EmailTemplate->description) && !empty($EmailTemplate->description)){{$EmailTemplate->description}} @endif</textarea>
                        @error('description')
                        <label class="error" for="notes">{{$message}}</label>
                        @enderror
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
                                <option value="1" @if(isset($EmailTemplate->status) && $EmailTemplate->status == '1') selected @endif>Enabled</option>
                                <option value="0" @if(isset($EmailTemplate->status) && $EmailTemplate->status == '0') selected @endif>Disabled</option>
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

@push('foorterscript')
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<script>
    CKEDITOR.replaceClass="article-ckeditor";
    $(document).ready(function () {       
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
                title: "Please enter a event name",
               
                status: {
                    required: "Please select a status",
                },
            }
        });
    });
    
</script>
@endpush

