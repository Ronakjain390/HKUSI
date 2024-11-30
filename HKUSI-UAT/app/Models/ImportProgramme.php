<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ImportProgramme extends Model
{   
    use HasFactory,LogsActivity;
    protected $fillable = ['import_data_info_id','application_number','programme_code', 'programme_name','reason', 'start_date', 'end_date', 'status'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['import_data_info_id','application_number','programme_code', 'programme_name','reason', 'start_date', 'end_date', 'status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['import_data_info_id','application_number','programme_code', 'programme_name','reason', 'start_date', 'end_date', 'status']);
        // Chain fluent methods for configuration options
    }

     public function getImportProgrammeTableColumns() {
        return ['application_number','programme_code','programme_name','start_date','end_date'];
        //return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
