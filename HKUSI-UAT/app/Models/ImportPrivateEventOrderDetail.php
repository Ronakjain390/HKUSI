<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportPrivateEventOrderDetail extends Model
{
    use HasFactory;
    // Model Created By Akash
    public function getPrivateEventOrderTableColumns() {
        return ['application_id','event_id', 'event_group', 'no_of_seats', 'booking_status', 'event_status' ];
    }

    public static function generatePrivateEventId($length = 7)
    {
        $latest_id = self::select('id')->latest()->first();

        if ( $latest_id == null ) {
            
            $id = 1;       
        }else{

            $id = $latest_id->id + 1;
        }

        $id = 'PE'.str_pad($id, $length, '0', STR_PAD_LEFT);
        return $id;
    }
}
