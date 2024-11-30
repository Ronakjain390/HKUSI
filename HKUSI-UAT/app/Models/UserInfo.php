<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes; 

class UserInfo extends Model
{
     use HasFactory, LogsActivity, SoftDeletes;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'surname',
        'given_name',
        'title',
        'department',
        'gender',
        'mobile_tel_no',
        'location',
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
    protected static $logAttributes = ['surname', 'given_name', 'title', 'department', 'gender', 'mobile_tel_no', 'location', 'status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnly(['surname', 'given_name', 'title', 'department', 'gender', 'mobile_tel_no', 'location', 'status']);
        // Chain fluent methods for configuration options
    }

    public function getUserInfo(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
