<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use DB;

class ImportEventDetail extends Model
{
   use HasFactory,LogsActivity;

    protected $fillable = ['import_data_info_id','hall_setting_id','event_category_id','event_name', 'description', 'location', 'date','start_time','end_time', 'application_deadline','quota','unit_price','additional_info','notes','booking_limit','thumbanil','main_image','thumb_image','status','language_id','qouta_status','assembly_time','assembly_start_time','assembly_end_time','assembly_location','terms_condition','terms_link','pre_arrival','pre_link','reason'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['import_data_info_id','hall_setting_id','event_category_id','event_name', 'description', 'location', 'date','start_time','end_time', 'application_deadline','quota','unit_price','additional_info','notes','booking_limit','thumbanil','main_image','thumb_image','status','language_id','qouta_status','assembly_time','assembly_start_time','assembly_end_time','assembly_location','terms_condition','terms_link','pre_arrival','pre_link','reason'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['import_data_info_id','hall_setting_id','event_category_id','event_name', 'description', 'location', 'date','start_time','end_time', 'application_deadline','quota','unit_price','additional_info','notes','booking_limit','thumbanil','main_image','thumb_image','status','language_id','qouta_status','assembly_time','assembly_start_time','assembly_end_time','assembly_location','terms_condition','terms_link','pre_arrival','pre_link','reason']);
        // Chain fluent methods for configuration options
    }

    public function getProgrammeDetail(){
        return $this->belongsTo(Programme::class , 'programme_id' ,'id')->where('status',1);
    }


    public function getProgrammeDetailAll(){
        return $this->belongsTo(Programme::class , 'programme_id' ,'id');
    }


    public function getEventImages(){
        return $this->hasMany(EventSettingImage::class ,'event_setting_id', 'id');
    }

    public function getLanguage(){
        return $this->hasOne(Language::class,'id','language_id');
    }
    
    public function getCategoryDetails(){
        return $this->hasOne(Category::class,'id','event_category_id');
    }


    public function getEventProgrammes(){
        return $this->hasMany(EventProgramme::class,'event_id','id');
    }

    public function checkMemberEvent($application_id=''){
        $envetnBook = EventBooking::where('application_id',$application_id)->where('booking_status','!=','Cancelled')->where('event_id',$this->id)->first();
        return $envetnBook;
    }

    public function getBookingsQouta(){
        return $this->hasMany(EventBooking::class,'event_id','id')->whereIn('booking_status',['Paid','Updated','Pending','Completed','Cancelled'])->sum('no_of_seats');
    }


    public function getEventTableColumns() {
        return ['event_name','short_description', 'description', 'location', 'assembly_location','assembly_start_time','assembly_end_time','date','start_time','end_time','quota','unit_price','additional_info','booking_limit','event_category_id','language_id','application_deadline','status','terms_condition','terms_link','pre_arrival','pre_link','notes'];
    }
}