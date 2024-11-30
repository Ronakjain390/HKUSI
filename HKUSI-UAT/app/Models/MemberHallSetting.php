<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberHallSetting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['hall_setting_id', 'member_id'];
    
    protected $guarded = ['id']; 

    public function getMemberInfoDetail(){
        return $this->hasOne(MemberInfo::class,'id','member_id');
    }

    public function getHallSettingDetail(){
        return $this->hasOne(HallSetting::class,'id','hall_setting_id');
    }
}
