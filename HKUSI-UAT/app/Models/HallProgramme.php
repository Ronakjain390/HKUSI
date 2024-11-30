<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HallProgramme extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['qouta_hall_id', 'programme_id'];
    
    protected $guarded = ['id']; 

    public function getQoutaHallDetail(){
        return $this->belongsTo(Quota::class , 'qouta_hall_id' , 'id');
    }
    public function getProgrammeDetail(){
        return $this->belongsTo(Programme::class , 'programme_id' , 'id');
    }

}
