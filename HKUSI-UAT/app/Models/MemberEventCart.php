<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberEventCart extends Model
{
    use HasFactory;
    protected $fillable = ['event_id','application_id','no_of_seats','unit_price'];

   protected $guarded = ['id', 'created_at', 'updated_at'];
}
