<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EventBooking extends Model
{
   use HasFactory, LogsActivity;
   protected $fillable = ['event_id', 'payment_id', 'application_id', 'no_of_seats', 'unit_price', 'booking_status', 'check_in_date', 'check_in_time', 'check_operater'];

   protected $guarded = ['id', 'created_at', 'updated_at'];

   protected static $logAttributes = ['event_id', 'payment_id', 'application_id', 'no_of_seats', 'unit_price', 'booking_status', 'check_in_date', 'check_in_time', 'check_operater'];
   //protected static $recordEvents = ['created','updated'];
   protected static $logOnlyDirty = true;

   public function getActivitylogOptions(): LogOptions
   {
      return LogOptions::defaults()
         ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}")->logOnly(['event_id', 'payment_id', 'application_id', 'no_of_seats', 'unit_price', 'booking_status', 'check_in_date', 'check_in_time', 'check_operater']);
      // Chain fluent methods for configuration options
   }

   public function getEventApplication()
   {
      return $this->hasOne(MemberInfo::class, 'application_number', 'application_id');
   }

   public function getEventSetting()
   {
      return $this->hasOne(EventSetting::class, 'id', 'event_id');
   }

   public function paymentBooking()
   {
      return $this->hasOne(EventPayment::class, 'payment_id', 'payment_id')->where('service_type', 'Event Booking');
   }

   public function getCheckOperator()
   {
      return $this->hasOne(User::class, 'id', 'check_operater');
   }
}
