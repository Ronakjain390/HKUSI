<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotaProgramme extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['quota_id', 'programme_id'];
    
    protected $guarded = ['id']; 

    public function getQuotaDetail(){
        return $this->belongsTo(Quota::class , 'quota_id' , 'id');
    }
	
    public function getProgrammeDetail(){
        return $this->belongsTo(Programme::class , 'programme_id' , 'id');
    }
}
