<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EventPayment extends Model
{
     use HasFactory,LogsActivity;
    protected $fillable = ['transaction_id','previous_status','payment_status','service_type','order_no','application_id','booking_id', 'payment_id','reference_no', 'card_no', 'approval_code', 'merchant_id','expiry_time','pay_time','amount','payment_method','pay_type','pay_result','status','event_payment_status'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['transaction_id','previous_status','payment_status','service_type','order_no','application_id','booking_id', 'payment_id','reference_no', 'card_no', 'approval_code', 'merchant_id','expiry_time','pay_time','amount','payment_method','pay_type','pay_result','status','event_payment_status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['transaction_id','previous_status','payment_status','service_type','order_no','application_id','booking_id', 'payment_id','reference_no', 'card_no', 'approval_code', 'merchant_id','expiry_time','pay_time','amount','payment_method','pay_type','pay_result','status','event_payment_status']);
        // Chain fluent methods for configuration options
    }

 public function getUser(){
        return $this->belongsTo(User::class , 'application_id' ,'id')->withTrashed();
    }

    public function getMemberInfos(){
        return $this->hasOne(MemberInfo::class , 'application_number' ,'application_id');
    }

    public function getBookingDetails(){
        return $this->hasOne(HallBookingInfo::class , 'booking_number' ,'payment_id');
    }

    public function getEventBookingDetails(){
        return $this->hasMany(EventBooking::class , 'payment_id' ,'payment_id');
    }

    public function getSeatNo(){
        return $this->hasMany(EventBooking::class,'payment_id','payment_id')->sum('no_of_seats');
    }
    public function getyearSilgle(){
        return $this->hasOne(EventBooking::class,'payment_id','payment_id');
    }
}
