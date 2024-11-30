<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateEventSettingImage extends Model
{
    use HasFactory;
    // Private Event Image Model Created By Akash
    public $timestamps = false;

    protected $fillable = ['event_setting_id', 'main_image' ,'thumb_image'];
    
    protected $guarded = ['id']; 
}
