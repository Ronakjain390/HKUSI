@extends('admin.layouts.index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {!! Form::open(array('route' => 'admin.hotel-setting.store','method'=>'POST','class'=>'edit-form','autocomplete' => 'off','files' =>true,'id'=>'form-upload')) !!}
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Hotel Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Hotel Name</th>
                        <td><input type="text" name="hotel_name" required class="form-control" placeholder="Hotel Name"></td>
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
                        <th class="t-basic">Short Description</th>
                        <td><textarea name="short_description" required class="form-control" placeholder="Short Description"></textarea>
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
                        <th class="t-basic">Distance</th>
                        <td><input type="text" name="distance" required class="form-control" placeholder="Distance">
                            @error('distance')
                            <label class="error" for="distance">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Price Range</th>
                        <td><input type="text" name="price_range" required class="form-control" placeholder="Price Range">
                            @error('price_range')
                            <label class="error" for="price_range">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Website</th>
                        <td><input type="text" name="website" required class="form-control" placeholder="Website">
                            @error('website')
                            <label class="error" for="website">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Download Form Url</th>
                        <td><input type="text" name="download_form_url" class="form-control" placeholder="Download Form Url">
                            @error('download_form_url')
                            <label class="error" for="download_form_url">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Remark</th>
                        <td><input type="text" name="remark" required class="form-control" placeholder="Remark">
                            @error('remark')
                            <label class="error" for="remark">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Property Amenities Description</th>
                        <td><textarea name="property_amenities_description" required class="form-control" placeholder="Property Amenities Description"></textarea>
                            @error('property_amenities_description')
                            <label class="error" for="property_amenities_description">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Transportation Method Description</th>
                        <td><textarea name="transportation_method_description" required class="form-control" placeholder="Transportation Method Description"></textarea>
                            @error('transportation_method_description')
                            <label class="error" for="transportation_method_description">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Notes Description</th>
                        <td><textarea name="notes_description" required class="form-control" placeholder="Notes Description"></textarea>
                            @error('notes_description')
                            <label class="error" for="notes_description">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Map Url</th>
                        <td><input type="text" name="map_url" required class="form-control" placeholder="Map Url">
                            @error('map_url')
                            <label class="error" for="map_url">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
					
                   
                </tbody>
            </table>
        </div>
    </div>

        <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Room Info 1</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Type Name</th>
                        <td><input type="text" name="room_type_name_1" class="form-control" placeholder="Type Name"></td>
                    </tr>
                   
                    <tr>
                        <th class="t-basic">Description</th>
                        <td><textarea name="room_type_description_1" class="form-control" placeholder="Description"></textarea>
                            @error('room_type_description_1')
                            <label class="error" for="room_type_description_1">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">
                           Thumbnail
                        </th>
                        <td>
                            <div class="file-upload-image" style="padding-left: 0px !important;">
                                <div class="multi-img-upload" id="room_thumb_image_1">
                                    <div class="form-group">
                                        <input type="file" style="width:100% !important;" name="room_type_thumbnail_1" id="room_upload_img_1" />
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
                                    <div class="img-thumbs img-thumbs-hidden" id="room_img_preview_1"></div>
                                </div>
                                <div class="form-btn">
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    
                   
                </tbody>
            </table>
        </div>
    </div>

    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Room Info 2</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Type Name</th>
                        <td><input type="text" name="room_type_name_2" class="form-control" placeholder="Type Name"></td>
                    </tr>
                   
                    <tr>
                        <th class="t-basic">Description</th>
                        <td><textarea name="room_type_description_2" class="form-control" placeholder="Description"></textarea>
                            @error('room_type_description_2')
                            <label class="error" for="room_type_description_2">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">
                            Thumbnail
                        </th>
                        <td>
                            <div class="file-upload-image" style="padding-left: 0px !important;">
                                <div class="multi-img-upload" id="room_thumb_image_2">
                                    <div class="form-group">
                                        <input type="file" style="width:100% !important;" name="room_type_thumbnail_2" id="room_upload_img_2" />
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
                                    <div class="img-thumbs img-thumbs-hidden" id="room_img_preview_2"></div>
                                </div>
                                <div class="form-btn">
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    
                   
                </tbody>
            </table>
        </div>
    </div>

    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Room Info 3</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Type Name</th>
                        <td><input type="text" name="room_type_name_3" class="form-control" placeholder="Type Name"></td>
                    </tr>
                   
                    <tr>
                        <th class="t-basic">Description</th>
                        <td><textarea name="room_type_description_3" class="form-control" placeholder="Description"></textarea>
                            @error('room_type_description_3')
                            <label class="error" for="room_type_description_3">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">
                            Thumbnail
                        </th>
                        <td>
                            <div class="file-upload-image" style="padding-left: 0px !important;">
                                <div class="multi-img-upload" id="room_thumb_image_3">
                                    <div class="form-group">
                                        <input type="file" style="width:100% !important;" name="room_type_thumbnail_3" id="room_upload_img_3" />
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
                                    <div class="img-thumbs img-thumbs-hidden" id="room_img_preview_3"></div>
                                </div>
                                <div class="form-btn">
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
            <h6 class="card-heading">Thumbnail</h6>
        </div>
        <div class="file-upload-image">
            <div class="multi-img-upload" id="thumb_image">
                <div class="form-group">
                    <input type="file" style="width:100% !important;" name="thumbnail" id="upload_img" />
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
            <h6 class="card-heading">Map Photo</h6>
        </div>
        <div class="file-upload-image">
            <div class="multi-img-upload" id="thumb_image_map">
                <div class="form-group">
                    <input type="file" style="width:100% !important;" name="map_photo" id="upload_img_map" />
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
                <div class="img-thumbs img-thumbs-hidden" id="img_preview_map"></div>
            </div>
            <div class="form-btn">
            </div>
        </div>
    </div>

    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Hotel Images</h6>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.min.css">

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
                hotel_name: "required",
               
                description: {
                    required: true,
                },
                location: {
                    required: true,
                },
                
                status: {
                    required: true,
                },
                download_form_url: {
                    url: true,
                },
                thumbnail: {
                    required: true,
                },  
                "images[]": {
                    required: true,
                }
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                hotel_name: "Please enter a hotel name",
               

                description: {
                    required: "Please enter description",
                },
                status: {
                    required: "Please select a status",
                },
                thumbnail: {
                    required: "Please select a thumbnail image",
                },
                "images[]": {
                    required: "Please select a image",
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
        $('#img_preview').html(''); 

        console.log(totalFiles, imgUpload)
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


    //===============Map Image upload by Akash============================
    var imgUploadMap = document.getElementById("upload_img_map"),
        imgPreviewMap = document.getElementById("img_preview_map"),
        imgUploadForm = document.getElementById("thumb_image_map"),
        totalFiles,
        previewTitle,
        previewTitleText,
        img;

    imgUploadMap.addEventListener("change", previewImgsMap, true);

    function previewImgsMap(event) {
        $('#img_preview_map').html(''); 
        
        totalFiles = imgUploadMap.files.length;

        if (!!totalFiles) {
            imgPreviewMap.classList.remove("img-thumbs-hidden");
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
            imgPreviewMap.appendChild(wrapper);

            $(".remove-btn").click(function() {
                $(this).parent(".wrapper-thumb").remove();
            });
        }
    }
    //================import-file==================================


    //===============Room thumb image 1 upload By Akash============================
    var imgUploadRoom1 = document.getElementById("room_upload_img_1"),
        imgPreviewRoom1 = document.getElementById("room_img_preview_1"),
        imgUploadForm = document.getElementById("room_thumb_image_1"),
        totalFiles,
        previewTitle,
        previewTitleText,
        img;

    imgUploadRoom1.addEventListener("change", previewImgsRoom1, true);

    function previewImgsRoom1(event) {
        $('#room_img_preview_1').html(''); 
        totalFiles = imgUploadRoom1.files.length;

        if (!!totalFiles) {
            imgPreviewRoom1.classList.remove("img-thumbs-hidden");
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
            imgPreviewRoom1.appendChild(wrapper);

            $(".remove-btn").click(function() {
                $(this).parent(".wrapper-thumb").remove();
            });
        }
    }
    //================Room thumb image 1==================================


    //===============Room thumb image 1 upload By Akash============================
    var imgUploadRoom2 = document.getElementById("room_upload_img_2"),
        imgPreviewRoom2 = document.getElementById("room_img_preview_2"),
        imgUploadForm = document.getElementById("room_thumb_image_2"),
        totalFiles,
        previewTitle,
        previewTitleText,
        img;

    imgUploadRoom2.addEventListener("change", previewImgsRoom2, true);

    function previewImgsRoom2(event) {
        $('#room_img_preview_2').html(''); 
        totalFiles = imgUploadRoom2.files.length;

        if (!!totalFiles) {
            imgPreviewRoom2.classList.remove("img-thumbs-hidden");
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
            imgPreviewRoom2.appendChild(wrapper);

            $(".remove-btn").click(function() {
                $(this).parent(".wrapper-thumb").remove();
            });
        }
    }
    //================Room thumb image 1==================================


    //===============Room thumb image 1 upload By Akash============================
    var imgUploadRoom3 = document.getElementById("room_upload_img_3"),
        imgPreviewRoom3 = document.getElementById("room_img_preview_3"),
        imgUploadForm = document.getElementById("room_thumb_image_3"),
        totalFiles,
        previewTitle,
        previewTitleText,
        img;

    imgUploadRoom3.addEventListener("change", previewImgsRoom3, true);

    function previewImgsRoom3(event) {
        $('#room_img_preview_3').html(''); 

        totalFiles = imgUploadRoom3.files.length;

        if (!!totalFiles) {
            imgPreviewRoom3.classList.remove("img-thumbs-hidden");
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
            imgPreviewRoom3.appendChild(wrapper);

            $(".remove-btn").click(function() {
                $(this).parent(".wrapper-thumb").remove();
            });
        }
    }
    //================Room thumb image 1==================================



    //===============multiimage upload============================
    var imgUploads = document.getElementById("upload_img_event"),
        imgPreviewImages = document.getElementById("img_preview_event"),
        imgUploadForm = document.getElementById("quickFormImages"),
        totalFiles,
        previewTitle,
        previewTitleText,
        img;

    imgUploads.addEventListener("change", previewImgnew, true);

    function previewImgnew(event) {
        totalFiles = imgUploads.files.length;

        if (!!totalFiles) {
            imgPreviewImages.classList.remove("img-thumbs-hiddens");
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
            imgPreviewImages.appendChild(wrapper);

            $(".remove-btn").click(function() {
                $(this).parent(".wrapper-thumb").remove();
            });
        }
    }
    //================import-file==================================

	
	
</script>
@endpush