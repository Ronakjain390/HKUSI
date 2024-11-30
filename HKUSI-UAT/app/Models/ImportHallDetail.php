<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use DB;

class ImportHallDetail extends Model
{
    use HasFactory,LogsActivity;
    protected $fillable = ['import_data_info_id','hall_setting_id','quota_id', 'start_date', 'end_date','total_quotas', 'gender', 'college_name', 'address', 'room_type','ass_name', 'ass_mobile', 'ass_email', 'check_in_date', 'check_in_time', 'check_out_date', 'check_out_time','pdf','room_key_location','status','reason'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['import_data_info_id','hall_setting_id','quota_id', 'start_date', 'end_date','total_quotas', 'gender', 'college_name', 'address', 'room_type', 'ass_name', 'ass_mobile', 'ass_email', 'check_in_date', 'check_in_time', 'check_out_date', 'check_out_time', 'pdf','room_key_location', 'status','reason'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['import_data_info_id','hall_setting_id','quota_id', 'start_date', 'end_date','total_quotas', 'gender', 'college_name', 'address', 'room_type', 'ass_name', 'ass_mobile', 'ass_email', 'check_in_date', 'check_in_time', 'check_out_date', 'check_out_time', 'pdf','room_key_location', 'status','reason']);
        // Chain fluent methods for configuration options
    }

    public function getQuotaDetail(){
      return $this->belongsTo(Quota::class, 'quota_id','id');
    }
    public function getHallSettingDetail(){
      return $this->belongsTo(HallSetting::class, 'hall_setting_id','id');
    }

    public function getHallQuotaRoom(){
        return $this->hasOne(QuotaRoom::class , 'id' ,'quota_hall_id')->orderBy('id','DESC');
    }
    public function getQoutaHalltable() {
        return ['quota_id','college_name','address','room_type','check_in_time', 'check_out_time','start_date','end_date','male','female','ass_name','ass_mobile', 'ass_email','room_key_location', 'status'];
    }

}
