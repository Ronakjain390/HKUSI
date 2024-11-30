<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportHotelDetail extends Model
{
    use HasFactory;
    // Import Hotel Details Model Created by Akash

    public function getHotelTableColumns() {
        return ['hotel_name','short_description','description', 'location', 'distance', 'price_range', 'website','download_form_url', 'remark', 'property_amenities_description', 'transportation_method_description', 'notes_description', 'map_url','status' ,'room_type_name_1', 'room_type_description_1', 'room_type_name_2', 'room_type_description_2', 'room_type_name_3', 'room_type_description_3'];
    }
}
