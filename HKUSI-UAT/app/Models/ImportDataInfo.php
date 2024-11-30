<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes; 

class ImportDataInfo extends Model
{
     use HasFactory, LogsActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'reason',
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
    protected static $logAttributes = ['user_id', 'type', 'reason', 'status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnly(['user_id', 'type', 'reason', 'status']);
        // Chain fluent methods for configuration options
    }
    
    public function getUserDetail(){
        return $this->belongsTo(User::class , 'user_id' ,'id');
    }
    public function getHallSetting(){
        return $this->belongsTo(HallSetting::class , 'hall_setting_id' ,'id');
    }

    public function getImportProgramReportDetail(){
        return $this->hasMany(ImportProgramme::class ,'import_data_info_id', 'id' );
    }

    public function getImportMemberReportDetail(){
        return $this->hasMany(ImportMemberDetail::class ,'import_data_info_id', 'id' );
    }

    public function getImportCountryReportDetail(){
        return $this->hasMany(ImportCountry::class ,'import_data_info_id', 'id' );
    }
    public function getImportEventReportDetail(){
        return $this->hasMany(ImportEventDetail::class ,'import_data_info_id', 'id' );
    }
    public function getImportPrivateEventReportDetail(){
        return $this->hasMany(ImportPrivateEventOrderDetail::class ,'import_data_info_id', 'id' );
    }
    public function getImportPrivateEventSettingReportDetail(){
        return $this->hasMany(ImportPrivateEventDetail::class ,'import_data_info_id', 'id' );
    }
    public function getImportHallDetail(){
        return $this->hasMany(ImportHallDetail::class ,'import_data_info_id', 'id' );
    }
    public function getImportRoomDetail(){
        return $this->hasMany(ImportRoomDetail::class ,'import_data_info_id', 'id' );
    }

    public function getImportHotelDetail(){
        return $this->hasMany(ImportHotelDetail::class ,'import_data_info_id', 'id' );
    }

}
