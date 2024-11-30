<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Country extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name','status'];
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static $logAttributes = ['name','status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['name','status']);
        // Chain fluent methods for configuration options
    }

    public function getCountryTableColumns() {
        return ['name'];
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        /*$columnNames = collect(DB::getSchemaBuilder()->getColumnListing($this->getTable()));
        $columnNames = $columnNames->filter(function ($value, $key) {
            return in_array($value, ['id', 'created_at', 'updated_at']) === false;
        });*/
    }
}
