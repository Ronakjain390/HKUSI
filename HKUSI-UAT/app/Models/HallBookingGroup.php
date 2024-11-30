<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class HallBookingGroup extends Model
{
    use HasFactory,LogsActivity;

    protected $fillable = ['hall_setting_id','quota_id','booking_type','quota_hall_id','quota_room_id','booking_number','user_type_id', 'user_type', 'start_date' ,'end_date', 'check_in_date', 'check_in_time','check_out_date','check_out_time','amount','programme_code','application_id','status'];

    protected $guarded = ['id', 'created_at', 'updated_at']; 

    protected static $logAttributes = ['hall_setting_id','quota_id','booking_type','quota_hall_id','quota_room_id','booking_number','user_type_id', 'user_type', 'start_date' ,'end_date', 'check_in_date', 'check_in_time','check_out_date','check_out_time','amount','programme_code','application_id','status'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")->logOnly(['hall_setting_id','quota_id','booking_type','quota_hall_id','quota_room_id','booking_number','user_type_id', 'user_type', 'start_date' ,'end_date', 'check_in_date', 'check_in_time','check_out_date','check_out_time','amount','programme_code','application_id','status']);
        // Chain fluent methods for configuration options
    } 

    public function getProgrammeDetail(){
        return $this->hasOne(Programme::class , 'programme_code' , 'programme_code');
    }


    public function getQuotaDetail(){
        return $this->hasOne(Quota::class , 'id' , 'quota_id');
    }

    
    public function getMemberdata(){
         return $this->hasOne(MemberInfo::class , 'id' , 'user_type_id');
    }

    public function getPaymentData(){
         return $this->hasOne(Payment::class , 'payment_id' , 'booking_number')->latestOfMany();
    }

    public function getMemberTotalBookingQty($user_type_id = ''){
        if (!empty($user_type_id)) {
            $total =  HallBookingGroup::where('user_type_id',$user_type_id)->count();
            return $total;
        }else{
            $total =  HallBookingGroup::where('user_type_id',$this->user_type_id)->count();
            return $total;
        }
    }
	
	public function getHallsetting(){
         return $this->hasOne(HallSetting::class , 'id' , 'hall_setting_id');
    }
    
    public function allPaymentRecords(){
         return $this->hasMany(Payment::class , 'payment_id' , 'booking_number');
    }
}
