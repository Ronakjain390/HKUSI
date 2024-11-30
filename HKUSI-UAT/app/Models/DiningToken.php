<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DiningToken extends Model
{
	use HasFactory,LogsActivity;
	protected $fillable = ['quantity','unit_price','status'];
	
	protected $guarded = ['id', 'created_at', 'updated_at'];

	protected static $logAttributes = ['quantity','unit_price','status'];
	protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['quantity','unit_price','status']);
        // Chain fluent methods for configuration options
    }

}
