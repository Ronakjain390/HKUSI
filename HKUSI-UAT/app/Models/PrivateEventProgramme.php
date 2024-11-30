<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PrivateEventProgramme extends Model
{
    use HasFactory,LogsActivity;
    //  Private Event Programme model created By Akash
    protected $fillable = ['event_id','program_id'];

   protected $guarded = ['id', 'created_at', 'updated_at'];

   protected static $logAttributes = ['event_id','program_id'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['event_id','program_id']);
        // Chain fluent methods for configuration options
    }

    public function getProgrammeDetail(){
        return $this->belongsTo(Programme::class , 'program_id' ,'id');
    }

    public function getProgrammeDetailApi(){
        return $this->belongsTo(Programme::class , 'program_id' ,'id')->where('status',1);
    }
}
