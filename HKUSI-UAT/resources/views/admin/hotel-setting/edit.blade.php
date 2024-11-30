{!! Form::model($hotelInfo, ['method' => 'PATCH','route' => ['admin.hotel-setting.update', $hotelInfo->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
<input type="hidden" name="submit_type" value="basic">
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Hotel Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Hotel Name</th>
                        <td><input type="text" name="hotel_name" required class="form-control" @if(isset($hotelInfo->hotel_name) && !empty($hotelInfo->hotel_name)) value="{{$hotelInfo->hotel_name}}" @endif placeholder="Hotel Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Year</th>
                        <td>
                            <select class="form-control" name="hall_setting_id" >
                                <option value="">Select Year</option>
                                @if(isset($HallSetting) && count($HallSetting))
                                    @foreach($HallSetting as $hallsettingdata)
                                        <option value="{{$hallsettingdata->id}}"@if(isset($hotelInfo->hall_setting_id) && $hallsettingdata->id == $hotelInfo->hall_setting_id)  selected @endif>{{$hallsettingdata->year}}</option>
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
                        <td><textarea name="short_description" required class="form-control" placeholder="Short Description">@if(isset($hotelInfo->short_description) && !empty($hotelInfo->short_description)){{$hotelInfo->short_description}} @endif                         
                            </textarea>
                            @error('short_description')
                            <label class="error" for="short_description">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Description</th>
                        <td><textarea name="description" required class="form-control" placeholder="Description">@if(isset($hotelInfo->description) && !empty($hotelInfo->description)){{$hotelInfo->description}} @endif                         
                            </textarea>
                            @error('description')
                            <label class="error" for="description">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Location</th>
                        <td><input type="text" name="location" required class="form-control" @if(isset($hotelInfo->location) && !empty($hotelInfo->location)) value="{{$hotelInfo->location}}" @endif placeholder="Location">
                            @error('location')
                            <label class="error" for="location">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Distance</th>
                        <td><input type="text" name="distance" required class="form-control" @if(isset($hotelInfo->distance) && !empty($hotelInfo->distance)) value="{{$hotelInfo->distance}}" @endif placeholder="Distance">
                            @error('distance')
                            <label class="error" for="distance">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Price Range</th>
                        <td><input type="text" name="price_range" required class="form-control" @if(isset($hotelInfo->price_range) && !empty($hotelInfo->price_range)) value="{{$hotelInfo->price_range}}" @endif placeholder="Price Range">
                            @error('price_range')
                            <label class="error" for="price_range">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Website</th>
                        <td><input type="text" name="website" required class="form-control" @if(isset($hotelInfo->website) && !empty($hotelInfo->website)) value="{{$hotelInfo->website}}" @endif placeholder="Website">
                            @error('website')
                            <label class="error" for="website">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Download Form Url</th>
                        <td><input type="text" name="download_form_url" required class="form-control" @if(isset($hotelInfo->download_form_url) && !empty($hotelInfo->download_form_url)) value="{{$hotelInfo->download_form_url}}" @endif placeholder="Download Form Url">
                            @error('download_form_url')
                            <label class="error" for="download_form_url">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Remark</th>
                        <td><input type="text" name="remark" required class="form-control" @if(isset($hotelInfo->remark) && !empty($hotelInfo->remark)) value="{{$hotelInfo->remark}}" @endif placeholder="Remark">
                            @error('remark')
                            <label class="error" for="remark">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Property Amenities Description</th>
                        <td><textarea name="property_amenities_description" required class="form-control" placeholder="Property Amenities Description">@if(isset($hotelInfo->property_amenities_description) && !empty($hotelInfo->property_amenities_description)){{$hotelInfo->property_amenities_description}} @endif
                            </textarea>
                            @error('property_amenities_description')
                            <label class="error" for="property_amenities_description">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Transportation Method Description</th>
                        <td><textarea name="transportation_method_description" required class="form-control" placeholder="Transportation Method Description">@if(isset($hotelInfo->transportation_method_description) && !empty($hotelInfo->transportation_method_description)){{$hotelInfo->transportation_method_description}} @endif
                            
                            </textarea>
                            @error('transportation_method_description')
                            <label class="error" for="transportation_method_description">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Notes Description</th>
                        <td><textarea name="notes_description" required class="form-control" placeholder="Notes Description">@if(isset($hotelInfo->notes_description) && !empty($hotelInfo->notes_description)){{$hotelInfo->notes_description}} @endif

                            </textarea>
                            @error('notes_description')
                            <label class="error" for="notes_description">{{$message}}</label>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th class="t-basic">Map Url</th>
                        <td><input type="text" name="map_url" required class="form-control" @if(isset($hotelInfo->map_url) && !empty($hotelInfo->map_url)) value="{{$hotelInfo->map_url}}" @endif placeholder="Map Url">
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
                        <td><input type="text" name="room_type_name_1"  class="form-control"  @if(isset($hotelInfo->room_type_name_1) && !empty($hotelInfo->room_type_name_1)) value="{{$hotelInfo->room_type_name_1}}" @endif  placeholder="Type Name"></td>
                    </tr>
                   
                    <tr>
                        <th class="t-basic">Description</th>
                        <td><textarea name="room_type_description_1"  class="form-control" placeholder="Description">@if(isset($hotelInfo->room_type_description_1) && !empty($hotelInfo->room_type_description_1)){{$hotelInfo->room_type_description_1}} @endif 
                            </textarea>
                            @error('room_type_description_1')
                            <label class="error" for="room_type_description_1">{{$message}}</label>
                            @enderror
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
                        <td><input type="text" name="room_type_name_2"  class="form-control"  @if(isset($hotelInfo->room_type_name_2) && !empty($hotelInfo->room_type_name_2)) value="{{$hotelInfo->room_type_name_2}}" @endif placeholder="Type Name"></td>
                    </tr>
                   
                    <tr>
                        <th class="t-basic">Description</th>
                        <td><textarea name="room_type_description_2"  class="form-control" placeholder="Description">@if(isset($hotelInfo->room_type_description_2) && !empty($hotelInfo->room_type_description_2)){{$hotelInfo->room_type_description_2}} @endif                         
                            </textarea>
                            @error('room_type_description_2')
                            <label class="error" for="room_type_description_2">{{$message}}</label>
                            @enderror
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
                        <td><input type="text" name="room_type_name_3"  class="form-control"  @if(isset($hotelInfo->room_type_name_3) && !empty($hotelInfo->room_type_name_3)) value="{{$hotelInfo->room_type_name_3}}" @endif placeholder="Type Name"></td>
                    </tr>
                   
                    <tr>
                        <th class="t-basic">Description</th>
                        <td><textarea name="room_type_description_3"  class="form-control" placeholder="Description">@if(isset($hotelInfo->room_type_description_3) && !empty($hotelInfo->room_type_description_3)){{$hotelInfo->room_type_description_3}} @endif 
                                
                            </textarea>
                            @error('room_type_description_3')
                            <label class="error" for="room_type_description_3">{{$message}}</label>
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
                                <option value="Enabled" @if(isset($hotelInfo->status) && $hotelInfo->status == 'Enabled') selected @endif>Enabled</option>
                                <option value="Disabled" @if(isset($hotelInfo->status) && $hotelInfo->status == 'Disabled') selected @endif>Disabled</option>
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
      
        // $('.profile-tab a.dropdown-item:first-child').css('display','none');

        $.validator.addMethod('lesthen', function(value, element, param) {
              return this.optional(element) || value < $(param).val();
        }, 'Invalid value');
        $.validator.addMethod('gretherthen', function(value, element, param) {
              return this.optional(element) || value > $(param).val();
        }, 'Invalid value');

        $("#quickForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                hotel_name: "required",
               
                description: {
                    required: true,
                },
                location: {
                    required: true,
                },
                
                download_form_url: {
                    url: true,
                },
                status: {
                    required: true,
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
        });
    });

</script>
@endpush

