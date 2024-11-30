<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class AdminNotificationInfo extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['user_id', 'admin_notification_id', 'read'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static $logAttributes = ['user_id', 'admin_notification_id', 'read'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}")->logOnly(['user_id', 'admin_notification_id', 'read']);
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

	public function getAdminNotification(){
        return $this->belongsTo(AdminNotification::class,'admin_notification_id','id');
    }
	
}
