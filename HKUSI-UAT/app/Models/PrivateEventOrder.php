<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateEventOrder extends Model
{
    use HasFactory;
    // Model Created By Akash
    public function getPrivateEventOrderTableColumns() {
        return ['application_id','event_id', 'event_group', 'no_of_seats', 'booking_status', 'event_status'];
    }

    public function getMemberInfo()
    {
    	return $this->belongsTo(MemberInfo::class, 'application_id', 'application_number');
    }

    public function getEventDetails()
    {
        return $this->belongsTo(PrivateEventSetting::class, 'event_id', 'id');
    }

    public function getEventSetting()
    {
        return $this->belongsTo(PrivateEventSetting::class, 'event_id', 'id');
    }

    public function getOperatorDetails()
    {
        return $this->belongsTo(User::class, 'check_operator', 'id');
    }

    public static function generatePrivateEventId($length = 7)
    {
        $latest_id = \DB::select("SHOW TABLE STATUS LIKE 'private_event_orders'")[0]->Auto_increment;
        if ( $latest_id == null ) {
            
            $id = 1;       
        }else{

            $id = $latest_id + 1;
        }

        $id = 'PE'.str_pad($id, $length, '0', STR_PAD_LEFT);
        return $id;
    }
}
