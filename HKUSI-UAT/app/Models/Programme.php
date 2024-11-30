<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions; 


class Programme extends Model
{
    use HasFactory,LogsActivity;
    protected $fillable = ['application_number','programme_code', 'programme_name', 'start_date', 'end_date', 'status'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['application_number','programme_code', 'programme_name', 'start_date', 'end_date', 'status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['application_number','programme_code', 'programme_name', 'start_date', 'end_date', 'status']);
        // Chain fluent methods for configuration options
    }

    public function getProgrammeTableColumns() {
        return ['application_number','programme_code','programme_name','start_date','end_date'];
        //return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function checkMemberProgramme($member_id=''){
        if(!empty($member_id)){
            if(MemberProgramme::where('programme_id',$this->id)->where('member_info_id',$member_id)->exists()){
                return true;
            }else{
                 return false;
            }
        }else{
            return false;
        }
    }

    public function getQuotaProgrammeDetail(){
        return $this->hasOne(QuotaProgramme::class , 'programme_id' , 'id');
    }
    
    public function getProgrammeHallSetting(){
        return $this->hasMany(ProgrammeHallSetting::class , 'programme_id' ,'id');
    }
}



