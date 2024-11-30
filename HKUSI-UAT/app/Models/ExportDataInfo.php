<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ExportDataInfo extends Model
{     use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    protected $guarded = ['id', 'created_at', 'updated_at']; 
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected static $logAttributes = ['user_id', 'type','status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnly(['user_id', 'type','status']);
        // Chain fluent methods for configuration options
    }
    
    public function getUserDetail(){
        return $this->belongsTo(User::class , 'user_id' ,'id');
    }

    public function getHallSetting(){
        return $this->belongsTo(HallSetting::class , 'hall_setting_id' ,'id');
    }
    
    public function getExportPaymentInfos(){
        return $this->hasMany(ExportPaymentInfo::class , 'export_data_info_id' ,'id');
    }

    public function getExportHallBookingsInfos(){
        return $this->hasMany(ExportHallBookingInfo::class , 'export_data_info_id' ,'id');
    }
    public function getExportEventBookingsInfos(){
        return $this->hasMany(ExportEventBookingInfo::class , 'export_data_info_id' ,'id');
    }
    public function getExportPrivateEventBookingsInfos(){
        return $this->hasMany(ExportPrivateEventBookingInfo::class , 'export_data_info_id' ,'id');
    }

}
