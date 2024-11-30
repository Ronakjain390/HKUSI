<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\EventSetting;
use App\Models\HallSetting;
use App\Models\ImportEventDetail;
use App\Models\Language;
use App\Models\QuotaHall;
use App\Models\Programme;
use App\Models\HallProgramme;
use App\Models\Quota;
use App\Models\MemberInfo;
use App\Models\HallBookingInfo;
use App\Models\ImportHallDetail;
use App\Jobs\SendEmailJob;
use DB;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '60000');

class ImportHall implements ToCollection  ,WithHeadingRow 
{
    protected $mappingRow,$headerRow,$importDataId,$year;

    public function __construct($mappingRow,$headerRow,$importDataId,$year='')
    {
        $this->mappingRow = $mappingRow;
        $this->headerRow = $headerRow;
        $this->importDataId = $importDataId;
        $this->year = $year;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        if(!empty($rows)){
            foreach ($rows as $keymember => $valuemamber){ 
                $valuemamber[$this->mappingRow[4]] = strtotime($this->transformDate($valuemamber[$this->mappingRow[4]]));
                $valuemamber[$this->mappingRow[5]] = strtotime($this->transformDate($valuemamber[$this->mappingRow[5]]));
                $valuemamber[$this->mappingRow[6]] = strtotime($this->transformDate($valuemamber[$this->mappingRow[6]]));
                $valuemamber[$this->mappingRow[7]] = strtotime($this->transformDate($valuemamber[$this->mappingRow[7]]));
              if(isset($this->mappingRow[0]) && !empty($this->mappingRow[0]) && isset($this->mappingRow[1]) && !empty($this->mappingRow[1]) && isset($this->mappingRow[2]) && !empty($this->mappingRow[2]) && isset($this->mappingRow[3]) && !empty($this->mappingRow[3]) && isset($this->mappingRow[4]) && !empty($this->mappingRow[4]) && isset($this->mappingRow[5]) && !empty($this->mappingRow[5]) && isset($this->mappingRow[6]) && !empty($this->mappingRow[6]) && isset($this->mappingRow[7]) && !empty($this->mappingRow[7])  && !empty($valuemamber[$this->mappingRow[0]]) && !empty($valuemamber[$this->mappingRow[1]]) && !empty($valuemamber[$this->mappingRow[2]]) && !empty($valuemamber[$this->mappingRow[3]]) && !empty($valuemamber[$this->mappingRow[4]]) && !empty($valuemamber[$this->mappingRow[5]]) && !empty($valuemamber[$this->mappingRow[6]]) && !empty($valuemamber[$this->mappingRow[7]])){
                    $qutoaData = Quota::where('id',$valuemamber[$this->mappingRow[0]])->first();
                    if(!empty($qutoaData)){
                        $importhallData = new QuotaHall();
                        $importDatabaseFields = $importhallData->getQoutaHalltable();
                        if(isset($importDatabaseFields) && count($importDatabaseFields)){
                            $importhallData['total_quotas'] = $valuemamber[$this->mappingRow[8]]+$valuemamber[$this->mappingRow[9]];
                            $importhallData['check_in_date'] = $qutoaData->check_in_date;
                            $importhallData['check_out_date'] = $qutoaData->check_out_date;
                            $status  = $valuemamber[$this->mappingRow[14]];
                            if($status=="Released"){
                                $importhallData['status'] = 1;
                            }else{
                                 $importhallData['status'] = 0;
                            }
                            $importhallData['hall_setting_id'] = $this->year;
                            foreach($importDatabaseFields as $key => $importDatabaseFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    if($importDatabaseFieldsData=='check_in_time'){
                                        $check_in_time = $valuemamber[$this->mappingRow[4]];;
                                        $importhallData[$importDatabaseFieldsData] = $check_in_time;
                                    }elseif($importDatabaseFieldsData=='check_out_time'){
                                        $check_out_time = $valuemamber[$this->mappingRow[5]];
                                        $importhallData[$importDatabaseFieldsData] = $check_out_time;
                                    }elseif($importDatabaseFieldsData=='start_date'){
                                        $start_date = $valuemamber[$this->mappingRow[6]];
                                        $importhallData[$importDatabaseFieldsData] = $start_date;
                                    }elseif($importDatabaseFieldsData=='end_date'){
                                        $end_date = $valuemamber[$this->mappingRow[7]];
                                        $importhallData[$importDatabaseFieldsData] = $end_date;
                                    }elseif($importDatabaseFieldsData=='status'){
                                        $status  = $valuemamber[$this->mappingRow[14]];
                                            if($status=="Released"){
                                                $importhallData['status'] = 1;
                                            }else{
                                                 $importhallData['status'] = 0;
                                            }
                                    }else{
                                        $importhallData[$importDatabaseFieldsData] = trim($valuemamber[$this->mappingRow[$key]]);
                                    }
                                }
                            }
                        }
                       // dd($importhallData);
                        $importhallData->save();
                        /** End **/

                        /*Import Programme*/
                        $qouta_hall_id = $importhallData->id;
                        if(!empty($valuemamber[$this->mappingRow[15]])){
                            $findprograme =  $valuemamber[$this->mappingRow[15]];
                            $explodedata = explode(',', $findprograme);
                            foreach($explodedata as $datagetPrograme){
                                $programmes = Programme::where('programme_code',$datagetPrograme)->first();
                                if(!empty($programmes)){
                                    HallProgramme::insert(['qouta_hall_id'=>$qouta_hall_id,'programme_id'=>$programmes->id]);
                                }
                            }
                        }
                        
                        
                        /*Import Programme*/
                         if(isset($valuemamber[$this->mappingRow[14]]) && $valuemamber[$this->mappingRow[14]] == 'Released'){
                            $addQuotaHall = QuotaHall::find($importhallData->id);
                            if(isset($this->mappingRow[15]) && !empty($this->mappingRow[15]) && !empty($valuemamber[$this->mappingRow[15]]) ){
                               $HallProgramme = HallProgramme::where('qouta_hall_id',$importhallData->id)->get();
                               $getAllProgramme =[];
                                foreach($HallProgramme as $getHllProgrammes){
                                    $getAllProgramme[] = $getHllProgrammes->getProgrammeDetail->programme_code;
                                }
                                $totalFemailLimit = $addQuotaHall->male;
                                $remaing = 0;
                                $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id','hall_booking_infos.programme_code')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$addQuotaHall->quota_id)->whereIn('programme_code',$getAllProgramme)->limit($totalFemailLimit)->whereNull('quota_hall_id')->get();
                                if($totalFemailLimit >0){
                                    if (isset($totalGenderMaleBooking) && count($totalGenderMaleBooking)) {
                                        foreach($totalGenderMaleBooking as $key => $valueData){
                                            $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                                            $accetptstauts = [];
                                            $accetptstauts['status']        = "Updated";
                                            $accetptstauts['quota_hall_id'] = $addQuotaHall->id;
                                            $mailInfo = [
                                                'given_name'            => $datauser->given_name,
                                                'application_number'    => $datauser->application_number,                        
                                                'accommodation'         => $valueData->getHallSettingDetail,                     
                                                'booking'               => $valueData,                       
                                                'quotahall'             => $addQuotaHall,                        
                                                'memberinfo'            => $datauser,                        
                                            ];
                                            $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                                            SendEmailJob::dispatch($details);
                                            HallBookingInfo::where('id',$valueData->id)->update($accetptstauts);
                                        }
                                        $remaing = $totalFemailLimit-count($totalGenderMaleBooking);
                                    }
                                }
                                if($remaing >0){
                                    $totalGenderMaleBooking1 = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id','hall_booking_infos.programme_code')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                    })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$addQuotaHall->quota_id)->limit($remaing)->whereNull('quota_hall_id')->get();
                                    if (isset($totalGenderMaleBooking1) && count($totalGenderMaleBooking1)) {
                                        foreach ($totalGenderMaleBooking1 as $key => $valueData) {
                                            $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                                            $accetptstauts = [];
                                            $accetptstauts['status']        = "Updated";
                                            $accetptstauts['quota_hall_id'] = $addQuotaHall->id;
                                            $mailInfo = [
                                                'given_name'            => $datauser->given_name,
                                                'application_number'    => $datauser->application_number,                        
                                                'accommodation'         => $valueData->getHallSettingDetail,                     
                                                'booking'               => $valueData,                       
                                                'quotahall'             => $addQuotaHall,                        
                                                'memberinfo'            => $datauser,                        
                                            ];
                                            $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                                            SendEmailJob::dispatch($details);
                                            HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                                        }
                                    }
                                }
                            }else{
                                $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$addQuotaHall->quota_id)->limit($addQuotaHall->male)->whereNull('quota_hall_id')->get();  
                                if (isset($totalGenderMaleBooking) && count($totalGenderMaleBooking)) {
                                    foreach ($totalGenderMaleBooking as $key => $valueData) {
                                        $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                                        $accetptstauts = [];
                                        $accetptstauts['status']        = "Updated";
                                        $accetptstauts['quota_hall_id'] = $addQuotaHall->id;
                                        $mailInfo = [
                                            'given_name'            => $datauser->given_name,
                                            'application_number'    => $datauser->application_number,                        
                                            'accommodation'         => $valueData->getHallSettingDetail,                     
                                            'booking'               => $valueData,                       
                                            'quotahall'             => $addQuotaHall,                        
                                            'memberinfo'            => $datauser,                        
                                        ];
                                        $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                                        SendEmailJob::dispatch($details);
                                        HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                                    }
                                }
                            }
                            if(isset($this->mappingRow[15]) && !empty($this->mappingRow[15]) && !empty($valuemamber[$this->mappingRow[15]]) ){
                                $HallProgramme = HallProgramme::where('qouta_hall_id',$importhallData->id)->get();
                                $getAllProgramme =[];
                                foreach($HallProgramme as $getHllProgrammes){
                                    $getAllProgramme[] = $getHllProgrammes->getProgrammeDetail->programme_code;
                                }
                                $totalFemailLimit = $addQuotaHall->female;
                                $remaing = 0;
                                $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id','hall_booking_infos.programme_code')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$addQuotaHall->quota_id)->whereIn('programme_code',$getAllProgramme)->limit($totalFemailLimit)->whereNull('quota_hall_id')->get();
                                if($totalFemailLimit >0){
                                    if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                                        foreach($totalGenderFemaleBooking as $key => $valueData){
                                            $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                                            $accetptstauts = [];
                                            $accetptstauts['status']        = "Updated";
                                            $accetptstauts['quota_hall_id'] = $addQuotaHall->id;
                                            $mailInfo = [
                                                'given_name'            => $datauser->given_name,
                                                'application_number'    => $datauser->application_number,                        
                                                'accommodation'         => $valueData->getHallSettingDetail,                     
                                                'booking'               => $valueData,                       
                                                'quotahall'             => $addQuotaHall,                        
                                                'memberinfo'            => $datauser,                        
                                            ];
                                            $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                                            SendEmailJob::dispatch($details);
                                            HallBookingInfo::where('id',$valueData->id)->update($accetptstauts);
                                            $remaing = $totalFemailLimit-count($totalGenderFemaleBooking);
                                        }
                                    }
                                }
                                if($remaing >0){
                                    $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id','hall_booking_infos.programme_code')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                    })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$addQuotaHall->quota_id)->limit($remaing)->whereNull('quota_hall_id')->get();
                                    if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                                        foreach ($totalGenderFemaleBooking as $key => $valueData) {
                                            $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                                            $accetptstauts = [];
                                            $accetptstauts['status']        = "Updated";
                                            $accetptstauts['quota_hall_id'] = $addQuotaHall->id;
                                            $mailInfo = [
                                                'given_name'            => $datauser->given_name,
                                                'application_number'    => $datauser->application_number,                        
                                                'accommodation'         => $valueData->getHallSettingDetail,                     
                                                'booking'               => $valueData,                       
                                                'quotahall'             => $addQuotaHall,                        
                                                'memberinfo'            => $datauser,                        
                                            ];
                                            $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                                            SendEmailJob::dispatch($details);
                                            HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                                        }
                                    }
                                }
                            }else{
                                $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$addQuotaHall->quota_id)->limit($addQuotaHall->female)->whereNull('quota_hall_id')->get();  
                                if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                                    foreach ($totalGenderFemaleBooking as $key => $valueData) {
                                        $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                                        $accetptstauts = [];
                                        $accetptstauts['status']        = "Updated";
                                        $accetptstauts['quota_hall_id'] = $addQuotaHall->id;
                                        $mailInfo = [
                                            'given_name'            => $datauser->given_name,
                                            'application_number'    => $datauser->application_number,                        
                                            'accommodation'         => $valueData->getHallSettingDetail,                     
                                            'booking'               => $valueData,                       
                                            'quotahall'             => $addQuotaHall,                        
                                            'memberinfo'            => $datauser,                        
                                        ];
                                        $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                                        SendEmailJob::dispatch($details);
                                        HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                                    }
                                }
                            }
                                
                        }
                         /** Import Data**/ 
                        $importHallDetails = new ImportHallDetail();
                        $importDatabaseFields = $importHallDetails->getQoutaHalltable();
                        if(isset($importDatabaseFields) && count($importDatabaseFields)){
                            $importHallDetails['import_data_info_id'] = $this->importDataId;
                            $importHallDetails['hall_setting_id'] = $this->year;
                            $importHallDetails['check_in_date'] = $qutoaData->check_in_date; 
                            $importHallDetails['check_out_date'] = $qutoaData->check_in_date;
                            $importHallDetails['total_quotas'] = $valuemamber[$this->mappingRow[8]]+$valuemamber[$this->mappingRow[9]];
                            foreach($importDatabaseFields as $key => $importDatabaseFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                     if($importDatabaseFieldsData=='check_in_time'){
                                        $check_in_time = $valuemamber[$this->mappingRow[4]];;
                                        $importHallDetails[$importDatabaseFieldsData] = $check_in_time;
                                    }elseif($importDatabaseFieldsData=='check_out_time'){
                                        $check_out_time = $valuemamber[$this->mappingRow[5]];
                                        $importHallDetails[$importDatabaseFieldsData] = $check_out_time;
                                    }elseif($importDatabaseFieldsData=='start_date'){
                                        $start_date = $valuemamber[$this->mappingRow[6]];
                                        $importHallDetails[$importDatabaseFieldsData] = $start_date;
                                    }elseif($importDatabaseFieldsData=='end_date'){
                                        $end_date = $valuemamber[$this->mappingRow[7]];
                                        $importHallDetails[$importDatabaseFieldsData] = $end_date;
                                    }elseif($importDatabaseFieldsData=='status'){
                                        $importHallDetails[$importDatabaseFieldsData] = '1';
                                    }else{
                                        $importHallDetails[$importDatabaseFieldsData] = trim($valuemamber[$this->mappingRow[$key]]);
                                    }
                                }
                            }
                        }
                        $importHallDetails->save();
                        /** End **/
                    }else{
                        $importHallDetails = new ImportHallDetail();
                        $importDatabaseFields = $importHallDetails->getQoutaHalltable();
                        if(isset($importDatabaseFields) && count($importDatabaseFields)){
                            $importHallDetails['import_data_info_id'] = $this->importDataId;
                            $importHallDetails['hall_setting_id'] = $this->year;
                            $importHallDetails['reason'] = 'Qouta Id Not Match.';
                            $importHallDetails['status'] = '0';
                            $importHallDetails['total_quotas'] = $valuemamber[$this->mappingRow[8]]+$valuemamber[$this->mappingRow[9]];
                            foreach($importDatabaseFields as $key => $importDatabaseFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                     if($importDatabaseFieldsData=='check_in_time'){
                                        $check_in_time = $valuemamber[$this->mappingRow[4]];;
                                        $importHallDetails[$importDatabaseFieldsData] = $check_in_time;
                                    }elseif($importDatabaseFieldsData=='check_out_time'){
                                        $check_out_time = $valuemamber[$this->mappingRow[5]];
                                        $importHallDetails[$importDatabaseFieldsData] = $check_out_time;
                                    }elseif($importDatabaseFieldsData=='start_date'){
                                        $start_date = $valuemamber[$this->mappingRow[6]];
                                        $importHallDetails[$importDatabaseFieldsData] = $start_date;
                                    }elseif($importDatabaseFieldsData=='end_date'){
                                        $end_date = $valuemamber[$this->mappingRow[7]];
                                        $importHallDetails[$importDatabaseFieldsData] = $end_date;
                                    }else{
                                        $importHallDetails[$importDatabaseFieldsData] = trim($valuemamber[$this->mappingRow[$key]]);
                                    }
                                }
                            }
                        }
                        $importHallDetails->save();
                    }
                }else{
                    if(!empty($this->mappingRow[0])){
                        $importHallDetails = new ImportHallDetail();
                        $importDatabaseFields = $importHallDetails->getQoutaHalltable();
                        if(isset($importDatabaseFields) && count($importDatabaseFields)){
                            $importHallDetails['total_quotas'] = $valuemamber[$this->mappingRow[8]]+$valuemamber[$this->mappingRow[9]];
                            $importHallDetails['import_data_info_id'] = $this->importDataId;
                            $importHallDetails['hall_setting_id'] = $this->year;
                            if(empty($valuemamber[$this->mappingRow[0]])){
                                $importHallDetails['reason'] = 'Qouta id empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[1]])) {
                               $importHallDetails['reason'] = 'College Name empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[2]])){
                                $importHallDetails['reason'] = 'Address empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[3]])){
                                $importHallDetails['reason'] = 'Room Type empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[4]])){
                                $importHallDetails['reason'] = 'Check In time empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[5]])){
                                $importHallDetails['reason'] = 'Check out time empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[6]])){
                                $importHallDetails['reason'] = 'Start date empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[7]])){
                                $importHallDetails['reason'] = 'End date empty.';
                            }
                            $importHallDetails['status'] = '0';
                            foreach($importDatabaseFields as $key => $importDatabaseFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                     if($importDatabaseFieldsData=='check_in_time'){
                                        $check_in_time = $valuemamber[$this->mappingRow[4]];;
                                        $importHallDetails[$importDatabaseFieldsData] = $check_in_time;
                                    }elseif($importDatabaseFieldsData=='check_out_time'){
                                        $check_out_time = $valuemamber[$this->mappingRow[5]];
                                        $importHallDetails[$importDatabaseFieldsData] = $check_out_time;
                                    }elseif($importDatabaseFieldsData=='start_date'){
                                        $start_date = $valuemamber[$this->mappingRow[6]];
                                        $importHallDetails[$importDatabaseFieldsData] = $start_date;
                                    }elseif($importDatabaseFieldsData=='end_date'){
                                        $end_date = $valuemamber[$this->mappingRow[7]];
                                        $importHallDetails[$importDatabaseFieldsData] = $end_date;
                                    }else{
                                        $importHallDetails[$importDatabaseFieldsData] = trim($valuemamber[$this->mappingRow[$key]]);
                                    }
                                }
                            }
                        }
                        $importHallDetails->save();
                    }
                   
                }
            }
        }else{
            ImportDataInfo::where('id',$this->importDataId)->update(['reason'=>'CSV File empty.','status'=>'0']);
        }
    }

    public function chunkSize(): int
    {
        return 10;
    }

    public function startRow(): int
    {
        return 2;
    }
    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}
