<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AppVersion extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['ios_release_date', 'ios_version', 'ios_app_store_url', 'ios_force_update', 'android_release_date', 'android_version', 'android_app_store_url', 'android_force_update', 'updates_remark'];
    protected $guarded = ['id', 'updates_remark', 'created_at', 'updated_at'];

    protected static $logAttributes = ['ios_release_date', 'ios_version', 'ios_app_store_url', 'ios_force_update', 'android_release_date', 'android_version', 'android_app_store_url', 'android_force_update'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}")->logOnly(['ios_release_date', 'ios_version', 'ios_app_store_url', 'ios_force_update', 'android_release_date', 'android_version', 'android_app_store_url', 'android_force_update']);
        // Chain fluent methods for configuration options
    }
}
