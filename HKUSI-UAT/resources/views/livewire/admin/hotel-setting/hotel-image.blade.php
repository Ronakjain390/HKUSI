<div class="card custom-card  margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Thumbnail</h6>
    </div>
    <div class="file-upload-image">
        <input type="hidden" name="submit_type" value="thumb">
        <div class="multi-img-upload" id="thumb_image">
            <div class="img-thumbs" id="img_preview">
                @if(!empty($hotelData->thumbnail) && Storage::disk($DISK_NAME)->exists($hotelData->thumbnail))
                <div class="wrapper-thumb divremove "style="margin-bottom:10px ;">
                    <img src="{{asset(Storage::url($hotelData->thumbnail))}}" class="img-preview-thumb">
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card custom-card  margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Map Photo</h6>
    </div>
    <div class="file-upload-image">
        <input type="hidden" name="submit_type" value="thumb">
        <div class="multi-img-upload" id="thumb_image">
            <div class="img-thumbs" id="img_preview">
                @if(!empty($hotelData->map_photo) && Storage::disk($DISK_NAME)->exists($hotelData->map_photo))
                <div class="wrapper-thumb divremove "style="margin-bottom:10px ;">
                    <img src="{{asset(Storage::url($hotelData->map_photo))}}" class="img-preview-thumb">
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card custom-card margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Hotel Images</h6>
    </div>
    <div class="file-upload-image">
            <input type="hidden" name="submit_type" value="images">
            <div class="multi-img-upload" id="multiple-images">
                <div class="img-thumbs" id="img_preview_event">
                    @if(isset($hotelData->getHotelImages) && count($hotelData->getHotelImages))
                        @foreach($hotelData->getHotelImages as $images)
                            @if(!empty($images->image) && Storage::disk($DISK_NAME)->exists($images->image))
                            <div class="wrapper-thumb" style="margin-bottom:10px ;">
                                <input type="hidden" name="old_images[]" value="{{$images->image}}">
                                <img src="{{asset(Storage::url($images->image))}}" class="img-preview-thumb">
                            </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
    </div>
</div>

<div class="card custom-card  margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Room Thumbnail 1</h6>
    </div>
    <div class="file-upload-image">
        <input type="hidden" name="submit_type" value="thumb">
        <div class="multi-img-upload" id="thumb_image">
            <div class="img-thumbs" id="img_preview">
                @if(!empty($hotelData->room_type_thumbnail_1) && Storage::disk($DISK_NAME)->exists($hotelData->room_type_thumbnail_1))
                <div class="wrapper-thumb divremove "style="margin-bottom:10px ;">
                    <img src="{{asset(Storage::url($hotelData->room_type_thumbnail_1))}}" class="img-preview-thumb">
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<div class="card custom-card  margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Room Thumbnail 2</h6>
    </div>
    <div class="file-upload-image">
        <input type="hidden" name="submit_type" value="thumb">
        <div class="multi-img-upload" id="thumb_image">
            <div class="img-thumbs" id="img_preview">
               

                @if(!empty($hotelData->room_type_thumbnail_2) && Storage::disk($DISK_NAME)->exists($hotelData->room_type_thumbnail_2))
                <div class="wrapper-thumb divremove "style="margin-bottom:10px ;">
                    <img src="{{asset(Storage::url($hotelData->room_type_thumbnail_2))}}" class="img-preview-thumb">
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<div class="card custom-card  margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Room Thumbnail 3</h6>
    </div>
    <div class="file-upload-image">
        <input type="hidden" name="submit_type" value="thumb">
        <div class="multi-img-upload" id="thumb_image">
            <div class="img-thumbs" id="img_preview">

                @if(!empty($hotelData->room_type_thumbnail_3) && Storage::disk($DISK_NAME)->exists($hotelData->room_type_thumbnail_3))
                <div class="wrapper-thumb divremove "style="margin-bottom:10px ;">
                    <img src="{{asset(Storage::url($hotelData->room_type_thumbnail_3))}}" class="img-preview-thumb">
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

