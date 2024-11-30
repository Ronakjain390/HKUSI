<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ExportPaymentInfo extends Model
{
    use HasFactory,LogsActivity;
    protected $fillable = ['export_data_info_id','payment_status','order_no','service_type','transaction_id','application_id','booking_id', 'payment_id','reference_no', 'card_no', 'approval_code', 'merchant_id','expiry_time','pay_time','amount','payment_method','pay_type','pay_result','status'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['export_data_info_id','payment_status','order_no','service_type','transaction_id','application_id','booking_id', 'payment_id','reference_no', 'card_no', 'approval_code', 'merchant_id','expiry_time','pay_time','amount','payment_method','pay_type','pay_result','status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['export_data_info_id','payment_status','order_no','service_type','transaction_id','application_id','booking_id', 'payment_id','reference_no', 'card_no', 'approval_code', 'merchant_id','expiry_time','pay_time','amount','payment_method','pay_type','pay_result','status']);
        // Chain fluent methods for configuration options
    }
}
