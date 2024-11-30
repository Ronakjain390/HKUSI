<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Hotel Info</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Create Date</th>
                    <td>@if(isset($hotelInfo->created_at) && !empty($hotelInfo->created_at)) {{date('Y-m-d' , strtotime($hotelInfo->created_at))}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Create Time</th>
                    <td>@if(isset($hotelInfo->created_at) && !empty($hotelInfo->created_at)) {{date('h:i:s' , strtotime($hotelInfo->created_at))}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Year</th>
                    <td>@if(isset($hotelInfo->getYearDetails) && !empty($hotelInfo->getYearDetails)) {{$hotelInfo->getYearDetails->year}} @else N/A @endif</td>
                </tr>  

                <tr>
                    <th class="t-basic">Hotel #</th>
                    <td>@if(isset($hotelInfo->id) && !empty($hotelInfo->id)) #{{$hotelInfo->id}} @endif</td>
                </tr>  
                
                <tr>
                    <th class="t-basic">Hotel Name</th>
                    <td>@if(isset($hotelInfo->hotel_name) && !empty($hotelInfo->hotel_name)) {{$hotelInfo->hotel_name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Short Description</th>
                    <td>{{$hotelInfo->short_description ?? ''}}</td>
                </tr>
                <tr>
                    <th class="t-basic"> Description</th>
                    <td>{{$hotelInfo->description ?? ''}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Location</th>
                    <td>@if(isset($hotelInfo->location) && !empty($hotelInfo->location)) {{$hotelInfo->location}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Distance</th>
                    <td>@if(isset($hotelInfo->distance) && !empty($hotelInfo->distance)) {{$hotelInfo->distance}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Price Range</th>
                    <td>@if(isset($hotelInfo->price_range) && !empty($hotelInfo->price_range)) {{$hotelInfo->price_range}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Website</th>
                    <td>@if(isset($hotelInfo->website) && !empty($hotelInfo->website)) {{$hotelInfo->website}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Download Form Url</th>
                    <td>@if(isset($hotelInfo->download_form_url) && !empty($hotelInfo->download_form_url)) {{$hotelInfo->download_form_url}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Remark</th>
                    <td>@if(isset($hotelInfo->remark) && !empty($hotelInfo->remark)) {{$hotelInfo->remark}} @else N/A @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Property Amenities Description</th>
                    <td>@if(isset($hotelInfo->property_amenities_description) && !empty($hotelInfo->property_amenities_description)) {!! $hotelInfo->property_amenities_description !!} @else N/A @endif</td>
                </tr>

                <tr>
                    <th class="t-basic">Transportation Method Description</th>
                    <td>@if(isset($hotelInfo->transportation_method_description) && !empty($hotelInfo->transportation_method_description)) {!! $hotelInfo->transportation_method_description !!} @else N/A @endif</td>
                </tr>

                <tr>
                    <th class="t-basic">Notes Description</th>
                    <td>@if(isset($hotelInfo->notes_description) && !empty($hotelInfo->notes_description)) {!! $hotelInfo->notes_description !!} @else N/A @endif</td>
                </tr>

                <tr>
                    <th class="t-basic">Map URL</th>
                    <td>@if(isset($hotelInfo->map_url) && !empty($hotelInfo->map_url)) {!! $hotelInfo->map_url !!} @else N/A @endif</td>
                </tr>

                <tr>
                    <th class="t-basic">Status</th>
                    <td>@if(isset($hotelInfo->status) && !empty($hotelInfo->status)) {!! $hotelInfo->status !!} @else N/A @endif</td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>

<div class="card custom-card margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Room Info 1</h6>
    </div>
    <div class="table-details select-table-custom">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Type Name</th>
                   <td> @if(isset($hotelInfo->room_type_name_1) && !empty($hotelInfo->room_type_name_1)) {{$hotelInfo->room_type_name_1}} @endif</td>
                </tr>

                <tr>
                    <th class="t-basic">Description</th>
                   <td> @if(isset($hotelInfo->room_type_description_1) && !empty($hotelInfo->room_type_description_1)) {!! $hotelInfo->room_type_description_1!!} @endif</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

<div class="card custom-card margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Room Info 2</h6>
    </div>
    <div class="table-details select-table-custom">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Type Name</th>
                   <td> @if(isset($hotelInfo->room_type_name_2) && !empty($hotelInfo->room_type_name_2)) {{$hotelInfo->room_type_name_2}} @endif</td>
                </tr>

                <tr>
                    <th class="t-basic">Description</th>
                   <td> @if(isset($hotelInfo->room_type_description_2) && !empty($hotelInfo->room_type_description_2)) {!! $hotelInfo->room_type_description_2!!} @endif</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

<div class="card custom-card margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Room Info 3</h6>
    </div>
    <div class="table-details select-table-custom">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Type Name</th>
                   <td> @if(isset($hotelInfo->room_type_name_3) && !empty($hotelInfo->room_type_name_3)) {{$hotelInfo->room_type_name_3}} @endif</td>
                </tr>

                <tr>
                    <th class="t-basic">Description</th>
                   <td> @if(isset($hotelInfo->room_type_description_3) && !empty($hotelInfo->room_type_description_3)) {!! $hotelInfo->room_type_description_3!!} @endif</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
