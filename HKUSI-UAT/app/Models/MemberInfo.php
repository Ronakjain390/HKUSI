<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes; 
use DB;

class MemberInfo extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

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
        'nationality_id',
        'study_country_id',
        'status',
		'contact_email',
        'contact_english_name',
        'contact_chinese_name',
        'contact_relationship',
        'contact_tel_no',
        'language',
        'push_notification',
        'deleted_at',
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
    protected static $logAttributes = ['image_bank_id','application_number', 'nationality_id', 'study_country_id','email_address', 'title', 'chinese_name', 'hkid_card_no', 'passport_no', 'date_of_birth', 'nationality', 'study_country', 'status', 'contact_english_name', 'contact_chinese_name', 'contact_relationship', 'contact_tel_no', 'language', 'push_notification'];
    //protected static $recordEvents = ['created','updated'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnly(['image_bank_id','application_number','email_address', 'title', 'chinese_name', 'hkid_card_no', 'nationality_id', 'study_country_id', 'passport_no', 'date_of_birth', 'nationality', 'study_country', 'status', 'contact_english_name', 'contact_chinese_name', 'contact_relationship', 'contact_tel_no', 'language', 'push_notification']);
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
        return $this->belongsTo(User::class , 'user_id' ,'id')->withTrashed();
    }

    public function getImageBankDetail(){
        return $this->hasOne(ImageBank::class , 'id' ,'image_bank_id');
    }

    public function getMemberProgrammeDetail(){
        return $this->hasMany(MemberProgramme::class , 'member_info_id' ,'id')->select('programme_id');
    }

    public function getStudyCountry(){
        return $this->hasOne(Country::class , 'id' ,'study_country_id');
    }

    public function getNationalty(){
        return $this->hasOne(Country::class , 'id' ,'nationality_id');
    }

    public function getMemberHallSettings(){
        return $this->hasMany(MemberHallSetting::class , 'member_info_id' ,'id');
    }

}
