<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EmailSetting extends Model
{
       use HasFactory, LogsActivity;

    protected $fillable = ['host_name','connection_security','port','email','password'];
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static $logAttributes = ['host_name','connection_security','port','email','password'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['host_name','description','port','email','password']);
        // Chain fluent methods for configuration options
    }
}
