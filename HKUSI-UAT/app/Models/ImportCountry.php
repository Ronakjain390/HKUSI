<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use DB;

class ImportCountry extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name','import_data_info_id','status'];
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static $logAttributes = ['name','import_data_info_id','status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['name','import_data_info_id','status']);
        // Chain fluent methods for configuration options
    }

    public function getCountryTableColumns() {
        return ['name'];
    }

}
