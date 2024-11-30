<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class HallBookingAttendance extends Model
{
    use HasFactory,LogsActivity;

    protected $fillable = ['hall_booking_info_id', 'actual_check_in_date', 'actual_check_in_time' ,'check_in_operator', 'actual_check_out_date', 'actual_check_out_time','check_out_operator','status'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['hall_booking_info_id', 'actual_check_in_date', 'actual_check_in_time' ,'check_in_operator', 'actual_check_out_date', 'actual_check_out_time','check_out_operator','status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['hall_booking_info_id', 'actual_check_in_date', 'actual_check_in_time' ,'check_in_operator', 'actual_check_out_date', 'actual_check_out_time','check_out_operator','status']);
        // Chain fluent methods for configuration options
    }

	public function getCheckInOperator()
    {
        return $this->hasOne(User::class, 'id', 'check_in_operator');
    }

    public function getCheckOutOperator()
    {
        return $this->hasOne(User::class, 'id', 'check_out_operator');
    }
	
}
