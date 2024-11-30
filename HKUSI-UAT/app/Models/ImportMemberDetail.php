<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use DB;
class ImportMemberDetail extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image_bank_id',
        'application_number',
        'email_address',
        'title',
        'chinese_name',
        'hkid_card_no',
        'passport_no',
        'date_of_birth',
        'nationality',
        'study_country',
        'status',
        'contact_english_name',
        'contact_chinese_name',
        'contact_relationship',
        'reason',
        'contact_tel_no',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    protected $guarded = ['id', 'created_at', 'updated_at']; 
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected static $logAttributes = ['import_data_info_id','image_bank_id','application_number','email_address', 'title', 'chinese_name', 'hkid_card_no', 'passport_no', 'date_of_birth', 'nationality', 'study_country', 'status', 'contact_english_name', 'contact_chinese_name', 'contact_relationship','reason', 'contact_tel_no', 'push_notification'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnly(['import_data_info_id','image_bank_id','application_number','email_address', 'title', 'chinese_name', 'hkid_card_no', 'passport_no', 'date_of_birth', 'nationality', 'study_country', 'status', 'contact_english_name', 'contact_chinese_name', 'contact_relationship', 'contact_tel_no','reason', 'push_notification']);
        // Chain fluent methods for configuration options
    }

     public function getMemberTableColumns() {
        return ['application_number','email_address','title','gender','surname','given_name','chinese_name','hkid_card_no','passport_no','nationality','date_of_birth','mobile_tel_no','contact_english_name','contact_chinese_name','contact_relationship','contact_tel_no','study_country'];
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        /*$columnNames = collect(DB::getSchemaBuilder()->getColumnListing($this->getTable()));
        $columnNames = $columnNames->filter(function ($value, $key) {
            return in_array($value, ['id', 'created_at', 'updated_at']) === false;
        });*/
    }

    public function getUserDetail(){
        return $this->belongsTo(User::class , 'user_id' ,'id');
    }

    public function getImageBankDetail(){
        return $this->hasOne(ImageBank::class , 'id' ,'image_bank_id');
    }
}
