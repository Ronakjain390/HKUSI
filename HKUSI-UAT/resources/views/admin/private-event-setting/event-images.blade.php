{{-- This view created by Akash --}}
<div class="card custom-card  margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Thumbnail</h6>
    </div>
    <div class="file-upload-image">
        {!! Form::model($eventInfo, ['method' => 'PATCH','route' => ['admin.private-event-setting.update', $eventInfo->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
        <div class="multi-img-upload" id="thumb_image">
            <div class="form-group">
                <input type="file" style="width:100% !important;" name="main_image" id="upload_img" />
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
            <div class="img-thumbs margin-b-20" id="img_preview" >
                @if(!empty($eventInfo->main_image) && Storage::disk($DISK_NAME)->exists($eventInfo->main_image))
                <div class="wrapper-thumb divremove">
                    <img src="{{asset(Storage::url($eventInfo->main_image))}}" class="img-preview-thumb">
                    <span class="remove-btn">x</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="card custom-card  margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Event Image</h6>
    </div>
    <div class="file-upload-image">
		<input type="hidden" name="submit_type" value="images">
		<div class="multi-img-upload" id="multiple-images">
			<div class="form-group">
				<input type="file" style="width:100% !important;" name="images[]" multiple id="upload_img_event" />
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
			<div class="img-thumbs margin-b-20" id="img_preview_event">
				@if(isset($eventInfo->getEventImages) && count($eventInfo->getEventImages))
					@foreach($eventInfo->getEventImages as $images)
						@if(!empty($images->main_image) && Storage::disk($DISK_NAME)->exists($images->main_image))
						<div class="wrapper-thumb">
							<input type="hidden" name="old_images[]" value="{{$images->main_image}}">
							<img src="{{asset(Storage::url($images->main_image))}}" class="img-preview-thumb">
							<span class="remove-btn">x</span>
						</div>
						@endif
					@endforeach
				@endif
			</div>
		</div>
    </div>
</div>
 <div class="card custom-card edit-form">
        <div class="form-btn">
            <button type="submit" id="submit" class="btn action-btn">Save Changes</button>
        </div>
    </div>
{!! Form::close() !!}
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

$(".remove-btn").click(function() {
    $(this).parent(".wrapper-thumb").remove();
});
</script>
@endpush


