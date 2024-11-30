<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StudentNotification extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['title', 'short_description', 'long_description', 'status'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static $logAttributes = ['title', 'short_description', 'long_description', 'status'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}")->logOnly(['title', 'short_description', 'long_description', 'status']);
        // Chain fluent methods for configuration options
    }

	public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }

	public function getStudentNotificationInfo(){
        return $this->hasMany(StudentNotificationInfo::class);
    }
}
