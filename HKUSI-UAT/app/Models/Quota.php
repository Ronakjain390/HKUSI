<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Quota extends Model
{
    use HasFactory,LogsActivity;
    protected $fillable = ['hall_setting_id','study_country_id', 'start_date', 'end_date','total_quotas' ,'quota_balance', 'gender', 'male_max_quota','female_max_quota' ,'hall_confirmation_date', 'release_date', 'programme_code', 'status','check_in_date','check_out_date'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['hall_setting_id','study_country_id', 'start_date', 'end_date','total_quotas' ,'quota_balance', 'gender', 'male_max_quota','female_max_quota' , 'hall_confirmation_date', 'release_date', 'programme_code', 'status','check_in_date','check_out_date'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['hall_setting_id','study_country_id', 'start_date', 'end_date','total_quotas' ,'quota_balance', 'gender', 'male_max_quota','female_max_quota' , 'hall_confirmation_date', 'release_date', 'programme_code', 'status','check_in_date','check_out_date']);
        // Chain fluent methods for configuration options
    }

    public function getQuotaHallDetail(){
        return $this->hasMany(QuotaHall::class , 'quota_id' ,'id');
    }

    public function getHallSettingDetail(){
        return $this->belongsTo(HallSetting::class , 'hall_setting_id' ,'id');
    }

    public function getQuotaCountry(){
        return $this->hasMany(QuotaCountry::class , 'quota_id' ,'id');
    }

    public function getQuotaProgrammes(){
        return $this->hasMany(QuotaProgramme::class , 'quota_id' ,'id');
    }

    public function updateBookingQuota($type){
        if(!empty($type)){
            if($type=='minus'){
                Quota::where('id',$this->id)->decrement('quota_balance', 1);
            }else{
                Quota::where('id',$this->id)->increment('quota_balance', 1);
            }
        }
    }

    // public function getHallBookinInfos(){
    //     return $this->hasOne(HallBookingHallInfo::class , 'quota_id' , 'id');
    // }

    
    public function getHallBookinInfos(){
        return $this->hasMany(HallBookingInfo::class , 'quota_id' ,'id');
    }

    public function getHallQuotaRoom(){
        return $this->hasOne(QuotaRoom::class , 'id' ,'quota_id')->orderBy('id','DESC');
    }
}
