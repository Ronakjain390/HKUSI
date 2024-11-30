<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class HallBookingHallInfo extends Model
{
    use HasFactory,LogsActivity;

    protected $fillable = ['hall_booking_info_id', 'college_name', 'address' ,'room_type', 'status'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['hall_booking_info_id', 'college_name', 'address' ,'room_type', 'status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['hall_booking_info_id', 'college_name', 'address' ,'room_type', 'status']);
        // Chain fluent methods for configuration options
    }
   
}
