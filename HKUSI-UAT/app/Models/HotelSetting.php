<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelSetting extends Model
{
    use HasFactory;

    // Hotel Setting model created by Akash
    public function getHotelImages()
    {
    	return $this->hasMany(HotelSettingImage::class, 'hotel_id', 'id');
    }

    public function getYearDetails(){
        return $this->hasOne(HallSetting::class,'id','hall_setting_id');
    }

    public function getHotelTableColumns() {
        return ['hotel_name','short_description','description', 'location', 'distance', 'price_range', 'website', 'download_form_url', 'remark', 'property_amenities_description', 'transportation_method_description', 'notes_description', 'map_url', 'status' ,'room_type_name_1', 'room_type_description_1', 'room_type_name_2', 'room_type_description_2', 'room_type_name_3', 'room_type_description_3'];
    }
}
