<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class QuotaHallUpdate extends Model
{
    use HasFactory,LogsActivity;
    protected $fillable = ['hall_setting_id','quota_id','quota_hall_id', 'male_old_qty', 'male_new_qty','female_old_qty', 'female_new_qty'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['hall_setting_id','quota_id','quota_hall_id', 'male_old_qty', 'male_new_qty','female_old_qty', 'female_new_qty'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['hall_setting_id','quota_id','quota_hall_id', 'male_old_qty', 'male_new_qty','female_old_qty', 'female_new_qty']);
        // Chain fluent methods for configuration options
    }
}
