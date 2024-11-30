<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use DB;

class HallSetting extends Model
{
    use HasFactory,LogsActivity;

    protected $fillable = ['year' ,'start_date', 'end_date', 'application_deadline', 'hall_result_days','hall_payment_days', 'unit_price','status'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['year' ,'start_date', 'end_date', 'application_deadline' , 'unit_price', 'hall_result_days','hall_payment_days','status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['year' ,'start_date', 'end_date', 'application_deadline' , 'unit_price', 'hall_result_days','hall_payment_date', 'status']);
        // Chain fluent methods for configuration options
    }

    public function getQuotaDetail(){
        return $this->hasMany(Quota::class , 'hall_setting_id' ,'id');
    }
}
