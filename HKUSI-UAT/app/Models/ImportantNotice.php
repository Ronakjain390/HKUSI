<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ImportantNotice extends Model
{
     use HasFactory, LogsActivity;

    protected $fillable = ['title','description','status'];
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static $logAttributes = ['title','description','status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['title','description','status']);
        // Chain fluent methods for configuration options
    }
}
