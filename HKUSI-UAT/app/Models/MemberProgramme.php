<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberProgramme extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['member_info_id', 'programme_id'];
    
    protected $guarded = ['id']; 

    public function getProgrammeDetail(){
        return $this->belongsTo(Programme::class , 'programme_id' ,'id');
    }
}
