<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes; 

class ImageBank extends Model
{
     use HasFactory, LogsActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_id',
        'qr_code',
        'profile_image',
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
    protected static $logAttributes = ['application_id', 'qr_code', 'profile_image'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnly(['application_id', 'qr_code', 'profile_image']);
        // Chain fluent methods for configuration options
    }
	
    public function getMemberProgrammeDetail(){
        return $this->hasMany(MemberProgramme::class , 'member_info_id' ,'id')->select('programme_id');
    }
    
	
}
