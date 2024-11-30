<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotaCountry extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['quota_id', 'country_id'];
    
    protected $guarded = ['id']; 


    public function getCountryQuota(){
        return $this->hasOne(Country::class,'id','country_id');
    }
}
