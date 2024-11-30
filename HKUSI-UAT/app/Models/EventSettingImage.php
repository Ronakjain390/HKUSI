<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSettingImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['event_setting_id', 'main_image' ,'thumb_image'];
    
    protected $guarded = ['id']; 

}
