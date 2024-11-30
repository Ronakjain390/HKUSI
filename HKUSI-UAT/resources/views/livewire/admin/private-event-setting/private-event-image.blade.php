{{-- Private Event Image view By Akash --}}
<div class="card custom-card  margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Thumbnail</h6>
    </div>
    <div class="file-upload-image">
        <input type="hidden" name="submit_type" value="thumb">
        <div class="multi-img-upload" id="thumb_image">
            <div class="img-thumbs" id="img_preview">
                @if(!empty($eventData->main_image) && Storage::disk($DISK_NAME)->exists($eventData->main_image))
                <div class="wrapper-thumb divremove "style="margin-bottom:10px ;">
                    <img src="{{asset(Storage::url($eventData->main_image))}}" class="img-preview-thumb">
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card custom-card">
    <div class="basic-details">
        <h6 class="card-heading">Private Event Image</h6>
    </div>
    <div class="file-upload-image">
            <input type="hidden" name="submit_type" value="images">
            <div class="multi-img-upload" id="multiple-images">
                <div class="img-thumbs" id="img_preview_event">
                    @if(isset($eventData->getEventImages) && count($eventData->getEventImages))
                        @foreach($eventData->getEventImages as $images)
                            @if(!empty($images->main_image) && Storage::disk($DISK_NAME)->exists($images->main_image))
                            <div class="wrapper-thumb" style="margin-bottom:10px ;">
                                <input type="hidden" name="old_images[]" value="{{$images->main_image}}">
                                <img src="{{asset(Storage::url($images->main_image))}}" class="img-preview-thumb">
                            </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
    </div>
</div>

