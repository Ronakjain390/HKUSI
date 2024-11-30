<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class QuotaRoom extends Model
{
    use HasFactory,LogsActivity;
    protected $fillable = ['quota_id','room_code','hall_setting_id', 'quota_hall_id', 'college_name','start_date', 'end_date', 'gender', 'status'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['quota_id','room_code','hall_setting_id', 'quota_hall_id', 'college_name','start_date', 'end_date', 'gender', 'status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['quota_id','room_code','hall_setting_id', 'quota_hall_id', 'college_name','start_date', 'end_date', 'gender', 'status']);
        // Chain fluent methods for configuration options
    }

    public function getQuotaHallDetail(){
        return $this->belongsTo(QuotaHall::class , 'quota_hall_id' , 'id');
    }
    
    public function getHallSettingDetail(){
        return $this->belongsTo(HallSetting::class , 'hall_setting_id' , 'id');
    }
    public function getQoutaRoomtable() {
        return ['quota_hall_id','room_code','gender','status'];
    }
}
