@extends('admin.layouts.index')
@section('content')

{{-- This view created by Akash --}}

<div class="container-xxl flex-grow-1 container-p-y">
    {!! Form::open(array('route' => 'admin.private-event-setting.store','method'=>'POST','class'=>'edit-form','autocomplete' => 'off','files' =>true,'id'=>'form-upload')) !!}
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Event Name</th>
                        <td><input type="text" name="event_name" required class="form-control" placeholder="Event Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Type</th>
                        <td>
                            <select class="form-control" name="event_category_id">
                                <option value="">Select type</option>
                                @if(isset($category) && count($category))
                                    @foreach($category as $category_type)
                                        <option value="{{$category_type->id}}">{{$category_type->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('event_category_id')
                            <label class="error" for="event_category_id">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                     <tr>
                        <th class="t-basic">Year</th>
                        <td>
                            <select class="form-control"  name="hall_setting_id">
                                <option value="">Select Year</option>
                                @if(isset($HallSetting) && count($HallSetting))
                                    @foreach($HallSetting as $halldata)
                                        <option value="{{$halldata->id}}">{{$halldata->year}}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('hall_setting_id')
                            <label class="error" for="hall_setting_id">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Language</th>
                        <td>
                            <select class="form-control" name="language_id">
                                <option value="">Select Lnaguage</option>
                                @if(isset($language) && count($language))
                                    @foreach($language as $datalanguage)
                                        <option value="{{$datalanguage->id}}">{{$datalanguage->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('language_id')
                            <label class="error" for="language_id">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Short Description</th>
                        <td><textarea name="short_description" required class="form-control" placeholder="Short Description" maxlength="100"></textarea>
                            @error('short_description')
                            <label class="error" for="short_description">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Description</th>
                        <td><textarea name="description" required class="form-control" placeholder="Description"></textarea>
                            @error('description')
                            <label class="error" for="description">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Location</th>
                        <td><input type="text" name="location" required class="form-control" placeholder="Location">
                            @error('location')
                            <label class="error" for="location">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Assembly Location</th>
                        <td><input type="text" name="assembly_location" required class="form-control" placeholder="Assembly Location">
                            @error('assembly_location')
                            <label class="error" for="assembly_location">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Assembly Start Time</th>
                        <td><input type="time" name="assembly_start_time"  required class="form-control" placeholder="Assembly Start Time"></td>
                    </tr>

                    <tr>
                        <th class="t-basic">Assembly End Time</th>
                        <td><input type="time" name="assembly_end_time"  required class="form-control" placeholder="Assembly End Time"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Date</th>
                        <td><input type="text" readonly name="date" id="date" required class="form-control datepicker" placeholder="Date"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Start Time</th>
                        <td><input type="time" name="start_time"  required class="form-control" placeholder="Start Time"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">End Time</th>
                        <td><input type="time" name="end_time" required class="form-control" placeholder="End Time"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Quota(s)</th>
                        <td><input type="text" name="quota" id="quota" onkeypress="return isNumber(event);" class="form-control" placeholder="Quota(s)"  maxlength="9" min="0" max="999999999"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Quota Balance</th>
                        <td><span id="get_quota_balance"></span> <input type="hidden"  name="quota_balance" id="quota_balance" class="form-control" placeholder="Quota balance" maxlength="9" min="0" max="999999999"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Unit Price</th>
                        <td><input type="text" name="unit_price" id="unit_price" onkeypress="return isNumber(event);" class="form-control" placeholder="Unit Price" maxlength="9" min="0" max="999999999"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Additional Info</th>
                        <td><textarea type="text" name="additional_info" required class="form-control" placeholder="Additional Info"></textarea>
                            @error('additional_info')
                            <label class="error" for="additional_info">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Booking Limit</th>
                        <td><input type="text" name="booking_limit" id="booking_limit" onkeypress="return isNumber(event);" class="form-control" placeholder="Booking Limit" maxlength="9" min="0" max="999999999"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Deadline</th>
                        <td><input type="text" readonly name="application_deadline" id="application_deadline" required class="form-control datepicker" placeholder="Deadline"></td>
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
                        <select class="form-control form-select" name="programme_id[]" multiple multiselect-search="true" multiselect-select-all="true">
                              @if(isset($programme) && count($programme))
                                @foreach($programme as $programmeData)
                                <option value="{{$programmeData->id}}">{{$programmeData->programme_name . " / " . $programmeData->programme_code}}</option>
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
            <h6 class="card-heading">Note</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Terms & Condition</th>
                       <td><input type="text" name="terms_condition"  class="form-control" placeholder="Terms Condition" ></td>
                    </tr>
                     <tr>
                        <th class="t-basic">Terms Link</th>
                       <td><input type="text" name="terms_link"  class="form-control" placeholder="Terms Link" ></td>
                    </tr>
                     <tr>
                        <th class="t-basic">Pre-arrival</th>
                       <td><input type="text" name="pre_arrival"  class="form-control" placeholder="Pre-arrival" ></td>
                    </tr>
                     <tr>
                        <th class="t-basic">Pre-arrival Link</th>
                       <td><input type="text" name="pre_link"  class="form-control" placeholder="Pre-arrival Link" ></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Notes</th>
                        <td><textarea name="notes" required class="form-control" placeholder="Notes"></textarea>
                            @error('notes')
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
            <h6 class="card-heading">Thumbnail</h6>
        </div>
        <div class="file-upload-image">
            <div class="multi-img-upload" id="thumb_image">
                <div class="form-group">
                    <input type="file" style="width:100% !important;" name="main_image" id="upload_img" />
                    <div class="file-message">
                        <span>
                            <svg width="36" height="40" viewBox="0 0 36 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.25 6.66297L12.225 10.6925C11.525 11.3933 10.475 11.3933 9.775 10.6925C9.075 9.99175 9.075 8.94056 9.775 8.23976L16.775 1.2318C16.8625 1.1442 16.95 1.1004 17.0375 1.0566C17.125 1.0128 17.2125 0.968999 17.3 0.881399C17.825 0.7062 18.35 0.7062 18.7 0.881399C18.875 0.881399 19.05 1.0566 19.225 1.2318L26.225 8.23976C26.925 8.94056 26.925 9.99175 26.225 10.6925C25.875 11.0429 25.525 11.2181 25 11.2181C24.475 11.2181 24.125 11.0429 23.775 10.6925L19.75 6.66297V26.9861C19.75 28.0373 19.05 28.7381 18 28.7381C16.95 28.7381 16.25 28.0373 16.25 26.9861V6.66297ZM35.5 33.994V28.7381C35.5 27.6869 34.8 26.9861 33.75 26.9861C32.7 26.9861 32 27.6869 32 28.7381V33.994C32 35.0452 31.3 35.746 30.25 35.746H5.75C4.7 35.746 4 35.0452 4 33.994V28.7381C4 27.6869 3.3 26.9861 2.25 26.9861C1.2 26.9861 0.5 27.6869 0.5 28.7381V33.994C0.5 36.9724 2.775 39.25 5.75 39.25H30.25C33.225 39.25 35.5 36.9724 35.5 33.994Z" fill="#696868" />
                            </svg>
                        </span>
                        <span>Drag and Drop here</span>
                        <span>or</span>
                        <span> Browse files</span>
                    </div>
                </div>
                <div class="img-thumbs img-thumbs-hidden" id="img_preview"></div>
            </div>
            <div class="form-btn">
            </div>
        </div>
    </div>

    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Event Image</h6>
        </div>
        <div class="file-upload-image">
            <div class="multi-img-upload" id="multiple-images">
                <div class="form-group">
                    <input type="file" style="width:100% !important;" name="images[]" multiple id="upload_img_event" />
                    <div class="file-message">
                        <span>
                            <svg width="36" height="40" viewBox="0 0 36 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.25 6.66297L12.225 10.6925C11.525 11.3933 10.475 11.3933 9.775 10.6925C9.075 9.99175 9.075 8.94056 9.775 8.23976L16.775 1.2318C16.8625 1.1442 16.95 1.1004 17.0375 1.0566C17.125 1.0128 17.2125 0.968999 17.3 0.881399C17.825 0.7062 18.35 0.7062 18.7 0.881399C18.875 0.881399 19.05 1.0566 19.225 1.2318L26.225 8.23976C26.925 8.94056 26.925 9.99175 26.225 10.6925C25.875 11.0429 25.525 11.2181 25 11.2181C24.475 11.2181 24.125 11.0429 23.775 10.6925L19.75 6.66297V26.9861C19.75 28.0373 19.05 28.7381 18 28.7381C16.95 28.7381 16.25 28.0373 16.25 26.9861V6.66297ZM35.5 33.994V28.7381C35.5 27.6869 34.8 26.9861 33.75 26.9861C32.7 26.9861 32 27.6869 32 28.7381V33.994C32 35.0452 31.3 35.746 30.25 35.746H5.75C4.7 35.746 4 35.0452 4 33.994V28.7381C4 27.6869 3.3 26.9861 2.25 26.9861C1.2 26.9861 0.5 27.6869 0.5 28.7381V33.994C0.5 36.9724 2.775 39.25 5.75 39.25H30.25C33.225 39.25 35.5 36.9724 35.5 33.994Z" fill="#696868" />
                            </svg>
                        </span>
                        <span>Drag and Drop here</span>
                        <span>or</span>
                        <span> Browse files</span>
                    </div>
                </div>
                <div class="img-thumbs img-thumbs-hiddens" id="img_preview_event"></div>
            </div>
            <div class="form-btn">
            </div>
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
                                <option value="Enabled">Enabled</option>
                                <option value="Disabled">Disabled</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="form-btn">
            <button type="submit" id="submit" class="btn action-btn">Save Changes</button>
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
@push('foorterscript')
<script>
    $(document).ready(function() {
         $("#quota").keyup(
           function()
           {
               $("#get_quota_balance").html(this.value);
               $("#quota_balance").val(this.value);
           });

        $(".smartsearch_keyword").select2({
            multiple: true,
        });
        $.validator.addMethod('lesthen', function(value, element, param) {
              return this.optional(element) || value < $(param).val();
        }, 'Invalid value');
        $.validator.addMethod('gretherthen', function(value, element, param) {
              return this.optional(element) || value > $(param).val();
        }, 'Invalid value');

        
        $("#form-upload").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                event_name: "required",
               
                event_category_id: {
                    required: true,
                },
                short_description: {
                    required: true,
                },
                description: {
                    required: true,
                },
                location: {
                    required: true,
                },
                assembly_location: {
                    required: true,
                },
                assembly_time: {
                    required: true,
                },
                hall_setting_id: {
                    required: true,
                },
                date: {
                    required: true,
                    gretherthen: "#application_deadline",
                },
                time: {
                    required: true,
                },
                quota: {
                    required: true,
                },
                unit_price: {
                    required: false,
                },
                additional_info: {
                    required: true,
                },
                notes: {
                    required: true,
                },
                terms_condition: {
                    required: true,
                },
                terms_link: {
                    required: true,
                },
                pre_arrival: {
                    required: true,
                },
                pre_link: {
                    required: true,
                },
                booking_limit: {
                    required: true,
                },
                type: {
                    required: true,
                },
                application_deadline: {
                    required: true,
                    lesthen:"#date"
                },
                status: {
                    required: true,
                },  
                language_id: {
                    required: true,
                },
                
                "programme_id[]": {
                    required: true,
                },
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                event_name: "Please enter a event name",
                programme_id: {
                    required: "Please select a programme",
                },
                hall_setting_id: {
                    required: "Please select a Year",
                },
                event_category_id: {
                    required: "Please select a type", //change label to event category to type
                }, 
                language_id: {
                    required: "Please select a language", //change label to event category to type
                },
                short_description: "Please enter a short description",
                description: "Please enter a description",
                location: {
                    required: "Please enter a location",
                },
                assembly_location: {
                    required: "Please enter a assembly location",
                },
                assembly_time: {
                    required: "Please enter a assembly time",
                },
                date: {
                    required: "Please choose a date",
                    gretherthen : "it should be always later than the Deadline"
                },
                time: {
                    required: "Please choose a time",
                },
                quota: {
                    required: "Please enter a quota",
                },
                additional_info: {
                    required: "Please enter a additional info",
                },
                unit_price: {
                    required: "Please enter a unit price",
                },
                notes: {
                    required: "Please enter a notes",
                },
                terms_condition: {
                    required: "Please enter a terms condition",
                },
                terms_link: {
                    required: "Please enter a terms link",
                },
                pre_arrival: {
                    required: "Please enter a pre-arrival",
                },
                pre_link: {
                    required: "Please enter a pre-arrival link",
                },
                booking_limit: {
                    required: "Please enter a booking limit",
                },
                // type: {
                //     required: "Please enter a type",
                // },
                application_deadline: {
                    required: "Please choose a deadline",
                    lesthen: "it should be always earlier than event date",
                },
                status: {
                    required: "Please select a status",
                },
                
            },
            submitHandler: function(form) {
                $('#submit').attr('disabled', 'disabled');
                form.submit();
            }
        });
    });


    //===============thumb image upload============================
    var imgUpload = document.getElementById("upload_img"),
        imgPreview = document.getElementById("img_preview"),
        imgUploadForm = document.getElementById("thumb_image"),
        totalFiles,
        previewTitle,
        previewTitleText,
        img;

    imgUpload.addEventListener("change", previewImgs, true);

    function previewImgs(event) {
        totalFiles = imgUpload.files.length;

        if (!!totalFiles) {
            imgPreview.classList.remove("img-thumbs-hidden");
        }

        for (var i = 0; i < totalFiles; i++) {
            wrapper = document.createElement("div");
            wrapper.classList.add("wrapper-thumb");
            removeBtn = document.createElement("span");
            nodeRemove = document.createTextNode("x");
            removeBtn.classList.add("remove-btn");
            removeBtn.appendChild(nodeRemove);
            img = document.createElement("img");
            img.src = URL.createObjectURL(event.target.files[i]);
            img.classList.add("img-preview-thumb");
            wrapper.appendChild(img);
            wrapper.appendChild(removeBtn);
            imgPreview.appendChild(wrapper);

            $(".remove-btn").click(function() {
                $(this).parent(".wrapper-thumb").remove();
            });
        }
    }
    //================import-file==================================


    //===============thumb image upload============================
    var imgUpload = document.getElementById("upload_img"),
        imgPreviews = document.getElementById("img_preview"),
        imgUploadForm = document.getElementById("quickForm"),
        totalFile,
        previewTitle,
        previewTitleText,
        img;

    imgUpload.addEventListener("change", previewImgs, true);

    function previewImgs(event) {
        $('.divremove').remove();
        totalFile = imgUpload.files.length;

        if (!!totalFile) {
            imgPreviews.classList.remove("img-thumbs-hidden");
        }

        for (var i = 0; i < totalFile; i++) {
            wrappers = document.createElement("div");
            wrappers.classList.add("wrapper-thumb");
            wrappers.classList.add("divremove");
            removeBtn = document.createElement("span");
            nodeRemove = document.createTextNode("x");
            removeBtn.classList.add("remove-btn");
            removeBtn.appendChild(nodeRemove);
            img = document.createElement("img");
            img.src = URL.createObjectURL(event.target.files[i]);
            img.classList.add("img-preview-thumb");
            wrappers.appendChild(img);
            wrappers.appendChild(removeBtn);
            imgPreviews.appendChild(wrappers);

            $(".remove-btn").click(function() {
                $(this).parent(".wrapper-thumb").remove();
            });
        }
    }
    //================import-file==================================



    //===============multiimage upload============================
    var imgUploads = document.getElementById("upload_img_event"),
        imgPreview = document.getElementById("img_preview_event"),
        imgUploadForm = document.getElementById("quickFormImages"),
        totalFiles,
        previewTitle,
        previewTitleText,
        img;

    imgUploads.addEventListener("change", previewImgnew, true);

    function previewImgnew(event) {
        totalFiles = imgUploads.files.length;

        if (!!totalFiles) {
            imgPreview.classList.remove("img-thumbs-hiddens");
        }

        for (var i = 0; i < totalFiles; i++) {
            wrapper = document.createElement("div");
            wrapper.classList.add("wrapper-thumb");
            removeBtn = document.createElement("span");
            nodeRemove = document.createTextNode("x");
            removeBtn.classList.add("remove-btn");
            removeBtn.appendChild(nodeRemove);
            img = document.createElement("img");
            img.src = URL.createObjectURL(event.target.files[i]);
            img.classList.add("img-preview-thumb");
            wrapper.appendChild(img);
            wrapper.appendChild(removeBtn);
            imgPreview.appendChild(wrapper);

            $(".remove-btn").click(function() {
                $(this).parent(".wrapper-thumb").remove();
            });
        }
    }
    //================import-file==================================

    function getProgrammeFilterdata(id){
        // if (id != '') {
        //     $.ajax({
        //         url: "{{route('admin.event-setting.getYearProgramme')}}",
        //         type: "GET",
        //         data: {
        //             'id': id,
        //             _token: '{{csrf_token()}}'
        //         },
        //         dataType: 'json',
        //         success: function(data){
        //             var selectOpt = '<div class="basic-details"><h6 class="card-heading">Programme</h6> </div><div class="table-details select-table-custom" ><table class="table"><tbody><tr><th class="t-basic">Programme</th><td ><select class="form-control form-select programmeselect" name="programme_id[]" multiple multiselect-search="true" multiselect-select-all="true">';
        //             $.each(data, function (key, value) {
        //                  selectOpt +='<option value="' + value.id + '">' + value.programme_name + ' '+ "/ "+' '+ value.programme_code+'</option>';                   
        //             });
        //            selectOpt += '</select></td></tr></tbody></table></div>';
        //            $('#appndiddata').html(selectOpt);
        //         }
        //   });
        // }
    }
</script>
@endpush