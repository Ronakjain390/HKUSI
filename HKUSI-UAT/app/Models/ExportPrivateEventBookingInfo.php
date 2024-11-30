<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ExportPrivateEventBookingInfo extends Model
{
   use HasFactory,LogsActivity;

    protected $fillable = ['export_data_info_id','event_id','event_name','event_date','location','assembly_location','assembly_start_time','assembly_end_time', 'check_in_date' ,'check_in_time', 'check_operator','booking_status','order_status','booking_id','application_number', 'event_status', 'start_time', 'end_time'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['export_data_info_id','event_id','event_name','event_date','location','assembly_location','assembly_start_time','assembly_end_time', 'check_in_date' ,'check_in_time', 'check_operator','booking_status','booking_id','application_number'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['export_data_info_id','event_id','event_name','event_date','location','assembly_location','assembly_start_time','assembly_end_time', 'check_in_date' ,'check_in_time', 'check_operator','booking_status','booking_id','application_number']);
        // Chain fluent methods for configuration options
    } 
   public function getEventApplication()
   {
      return $this->hasOne(MemberInfo::class,'id','application_id');
   }

   public function getEventSetting()
   {
      return $this->hasOne(EventSetting::class,'id','event_id');
   }

  
}
