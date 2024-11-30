<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeHallSetting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['hall_setting_id', 'programme_id'];
    
    protected $guarded = ['id']; 

    public function getProgrammeDetail(){
        return $this->hasOne(Programme::class,'id','programme_id');
    }

    public function getHallSettingDetail(){
        return $this->hasOne(HallSetting::class,'id','hall_setting_id');
    }

}
