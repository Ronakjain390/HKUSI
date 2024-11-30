  {!! Form::model($MemberInfo, ['method' => 'PATCH','route' => ['admin.members.update', $MemberInfo->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
    <input type="hidden" name="user_id" value="{{$MemberInfo->user_id}}">
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
		@if ($errors->any())
			<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin:10px">
				<ul class="p-0 m-0" style="list-style: none;">
					@foreach($errors->all() as $error)
					<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
		@if(Session::has('success'))
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert">
					<i class="fa fa-times"></i>
				</button>   
				
				<strong>Success!</strong> {{ session('success') }}
			</div>
		@endif
        <div class="table-responsive table-details">
          
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Registration Date</th>
                        <td>@if(isset($MemberInfo->created_at) && !empty($MemberInfo->created_at)){{date('Y-m-d' , strtotime($MemberInfo->created_at))}}@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Registration Time</th>
                        <td>@if(isset($MemberInfo->created_at) && !empty($MemberInfo->created_at)){{date('h:i:s' , strtotime($MemberInfo->created_at))}}@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">QR Code</th>
                        <td>@if($MemberInfo->application_number!='') {!! DNS2D::getBarcodeHTML($MemberInfo->application_number, 'QRCODE',1.5,1.5) !!} @endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Application #</th>
                        <td>@if(isset($MemberInfo->application_number) && !empty($MemberInfo->application_number)){{$MemberInfo->application_number}}@endif</td>
                    </tr>
                    <tr>
                        <th class="t-basic">Title</th>
                        <td><input type="text" name="title" value="@if(isset($MemberInfo->title) && !empty($MemberInfo->title)){{$MemberInfo->title}}@endif" class="form-control" placeholder="Title"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Given Name</th>
                        <td><input type="text" name="given_name" value="@if(isset($MemberInfo->given_name) && !empty($MemberInfo->given_name)){{$MemberInfo->given_name}}@endif" class="form-control" placeholder="Given Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Surname</th>
                        <td><input type="text" class="form-control" name="surname" value="@if(isset($MemberInfo->surname) && !empty($MemberInfo->surname)){{$MemberInfo->surname}}@endif" placeholder="Surname"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Chi. Name</th>
                        <td><input type="text" name="chinese_name" value="@if(isset($MemberInfo->chinese_name) && !empty($MemberInfo->chinese_name)){{$MemberInfo->chinese_name}}@endif" class="form-control" placeholder="陳偉林"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Gender</th>
                        <td>
                            <select name="gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="Male" @if($MemberInfo->gender=="Male") selected @endif>Male</option>
                                <option value="Female" @if($MemberInfo->gender=="Female") selected @endif>Female</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Date of Birth</th>
                        <td><input type="text" name="dob" value="@if(isset($MemberInfo->date_of_birth) && !empty($MemberInfo->date_of_birth)){{date('Y-m-d',$MemberInfo->date_of_birth)}}@endif" class="form-control datepicker" readonly placeholder="DOB"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">HKID</th>
                        <td><input type="text" name="hkid" value="@if(isset($MemberInfo->hkid_card_no) && !empty($MemberInfo->hkid_card_no)){{$MemberInfo->hkid_card_no}}@endif" class="form-control" placeholder="HKID"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Passport</th>
                        <td><input type="text" name="passport_no" value="@if(isset($MemberInfo->passport_no) && !empty($MemberInfo->passport_no)){{$MemberInfo->passport_no}}@endif" class="form-control" placeholder="Passport No."></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Nationality</th>
                        <td>
                            <select name="nationality" class="form-control">
                                <option value="">Select Nationality</option>
                                @if(isset($countries) && !empty($countries))
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}" @if($MemberInfo->nationality_id == $country->id) selected @endif>{{$country->name}}</option>
                                    @endforeach
                                @endif  
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Study Country</th>
                        <td>
                            <select name="study_country" class="form-control">
                                <option value="">Select Study Country</option>
                                @if(isset($countries) && !empty($countries))
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}" @if($MemberInfo->study_country_id == $country->id) selected @endif>{{$country->name}}</option>
                                    @endforeach
                                @endif  
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Mobile No.</th>
                        <td><input type="text" name="mobile_tel_no" value="@if(isset($MemberInfo->mobile_tel_no) && !empty($MemberInfo->mobile_tel_no)){{$MemberInfo->mobile_tel_no}}@endif" class="form-control" placeholder="Mobile No."></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Email Address</th>
                        <td>@if(isset($MemberInfo->getUserDetail->email) && !empty($MemberInfo->getUserDetail->email)){{$MemberInfo->getUserDetail->email}}@endif</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Year</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Year</th>
                        <td>
                            <select name="year[]" class="form-select"  multiple multiselect-search="true" multiselect-select-all="true">
                                @if(isset($yeardata) && count($yeardata))  
                                    @foreach($yeardata as $value)
                                        <option value="{{$value->id}}" @if(isset($yaearhallsettingdata) && !empty($yaearhallsettingdata) && in_array($value->id,$yaearhallsettingdata)) Selected @endif>{{$value->year}}</option>
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
                                        <option value="{{$programmeData->id}}" @if(isset($getMemberprogramme) && !empty($getMemberprogramme) && in_array($programmeData->id,$getMemberprogramme)) Selected @endif>{{$programmeData->programme_code}} / {{$programmeData->programme_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card profile-details margin-b-20">
        <div class="basic-details">
            <h6 class="card-heading">Contact Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Mobile No.</th>
                        <td><input type="text" name="mobile_tel_no" value="@if(isset($MemberInfo->mobile_tel_no) && !empty($MemberInfo->mobile_tel_no)){{$MemberInfo->mobile_tel_no}}@endif" class="form-control" placeholder="Mobile No"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Contact Email Address</th>
                        <td><input type="email" name="contact_email" value="@if(isset($MemberInfo->contact_email) && !empty($MemberInfo->contact_email)) {{$MemberInfo->contact_email}} @endif" class="form-control" value="" placeholder="Email Address"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Contact Eng. Name</th>
                        <td><input type="text" name="contact_english_name" value="@if(isset($MemberInfo->contact_english_name) && !empty($MemberInfo->contact_english_name)){{$MemberInfo->contact_english_name}}@endif" class="form-control" placeholder="English Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Contact Chi. Name</th>
                        <td><input type="text" name="contact_chinese_name" value="@if(isset($MemberInfo->contact_chinese_name) && !empty($MemberInfo->contact_chinese_name)){{$MemberInfo->contact_chinese_name}}@endif" class="form-control" placeholder="吳琬婷"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Contact Relation</th>
                        <td><input type="text" name="contact_relationship" value="@if(isset($MemberInfo->contact_relationship) && !empty($MemberInfo->contact_relationship)){{$MemberInfo->contact_relationship}}@endif" class="form-control" placeholder="Mother"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Contact Tel. No.</th>
                        <td><input type="text" name="contact_tel_no" value="@if(isset($MemberInfo->contact_tel_no) && !empty($MemberInfo->contact_tel_no)){{$MemberInfo->contact_tel_no}}@endif" class="form-control" placeholder="Contact Tel Number"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
	<div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Profile Image</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <!--<th class="t-basic">Upload Image</th>-->
                        <td>
							<div class="file-upload-image" style="padding:0px !important">
								<div class="multi-img-upload" id="multiple-images">
									<div class="form-group">
										<input type="file" style="width:100% !important;" name="profile_image" multiple id="upload_img_event" />
										<div class="file-message">
											<span>
												<svg width="36" height="40" viewBox="0 0 36 40" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path fill-rule="evenodd" clip-rule="evenodd" d="M16.25 6.66297L12.225 10.6925C11.525 11.3933 10.475 11.3933 9.775 10.6925C9.075 9.99175 9.075 8.94056 9.775 8.23976L16.775 1.2318C16.8625 1.1442 16.95 1.1004 17.0375 1.0566C17.125 1.0128 17.2125 0.968999 17.3 0.881399C17.825 0.7062 18.35 0.7062 18.7 0.881399C18.875 0.881399 19.05 1.0566 19.225 1.2318L26.225 8.23976C26.925 8.94056 26.925 9.99175 26.225 10.6925C25.875 11.0429 25.525 11.2181 25 11.2181C24.475 11.2181 24.125 11.0429 23.775 10.6925L19.75 6.66297V26.9861C19.75 28.0373 19.05 28.7381 18 28.7381C16.95 28.7381 16.25 28.0373 16.25 26.9861V6.66297ZM35.5 33.994V28.7381C35.5 27.6869 34.8 26.9861 33.75 26.9861C32.7 26.9861 32 27.6869 32 28.7381V33.994C32 35.0452 31.3 35.746 30.25 35.746H5.75C4.7 35.746 4 35.0452 4 33.994V28.7381C4 27.6869 3.3 26.9861 2.25 26.9861C1.2 26.9861 0.5 27.6869 0.5 28.7381V33.994C0.5 36.9724 2.775 39.25 5.75 39.25H30.25C33.225 39.25 35.5 36.9724 35.5 33.994Z" fill="#696868"/>
													</svg>
											</span>
											<span>Drag and Drop here</span>
											<span>or</span>
											<span> Browse files</span>
										</div>
									</div>
									<div class="img-thumbs margin-b-20" id="img_preview_profile">
										 @if(isset($MemberInfo->getImageBankDetail->profile_image) && $MemberInfo->getImageBankDetail->profile_image != '' && Storage::disk($DISK_NAME)->exists($MemberInfo->getImageBankDetail->profile_image))
											<div class="wrapper-thumb">
												<input type="hidden" name="old_images" class="old_images" value="{{$MemberInfo->getImageBankDetail->profile_image}}">
												<img src="{{asset(Storage::url($MemberInfo->getImageBankDetail->profile_image))}}" class="img-preview-thumb">
												<span class="remove-btn">x</span>
											</div>
										@endif
									</div>
									<div _ngcontent-hxt-c96="" class="photo-notes text-danger mb-2"> Photograph Requirement<br _ngcontent-hxt-c96=""> Image type: JPEG<br _ngcontent-hxt-c96=""> File Size: 5MB or below<br _ngcontent-hxt-c96=""> Image Dimensions: at least 1200Px(W) X 1600px(H) </div>
								</div>
							</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Activation & Status</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Activation</th>
                        <td>
                            <select name="activation" class="form-control">
                                <option value="">Select Activation</option>
                                <option value="1" @if(isset($MemberInfo->status) && $MemberInfo->status == '1') Selected @endif> Active</option>
                                <option value="0" @if(isset($MemberInfo->status) && $MemberInfo->status == '0') Selected @endif>Inactive</option>  
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Status</th>
                        <td>
                              
                            <select name="status" class="form-control">
                                <option value="">Select Status</option>
                                 <option value="1" @if(isset($MemberInfo->getUserDetail->status) && $MemberInfo->getUserDetail->status == '1') Selected @endif>Enabled </option>
                                 <option value="0" @if(isset($MemberInfo->getUserDetail->status) && $MemberInfo->getUserDetail->status == '0') Selected @endif >Disabled </option>
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
    
@push('foorterscript')

<script>
    $(document).ready(function () {
        $("#quickForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            main_image: {
                status: {
                    required: true,
                },
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                status: {
                    required: "Please select a image",
                },
            }
        });
        $("#quickFormImages").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                "images[]": {
                    required: false,
                },
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                "images[]": {
                    required: "Please select a image",
                },
            },
            submitHandler: function (form) {
                $('#submit').attr('disabled','disabled');
                form.submit();
            }
        });
    });


//===============thumb image upload============================
var imgUpload = document.getElementById("upload_img"),
    imgPreviews = document.getElementById("img_preview"),
    imgUploadForm = document.getElementById("quickForm"),
    totalFile,
    previewTitle,
    previewTitleText,
    img;
if(imgUpload) {
	imgUpload.addEventListener("change", previewImgs, true);
}

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
			$('.old_images').val('');
        });
    }
}
//================import-file==================================



//===============multiimage upload============================
var imgUploads = document.getElementById("upload_img_event"),
    imgPreview = document.getElementById("img_preview_profile"),
    imgUploadForm = document.getElementById("quickFormImages"),
    totalFiles,
    previewTitle,
    previewTitleText,
    img;
	
if(imgUploads) {
	imgUploads.addEventListener("change", previewImgnew, true);
}

function previewImgnew(event) { 
	$("#img_preview_profile").html(''); 
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
			$('.old_images').val('');
        });
    }
}
//================import-file==================================

$(".remove-btn").click(function() {
    $(this).parent(".wrapper-thumb").remove();
	$('.old_images').val('');
});
</script>
@endpush