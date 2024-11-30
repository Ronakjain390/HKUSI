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
use App\Models\MemberInfo;
use App\Models\QuotaRoom;
use App\Models\ImportRoomDetail;
use App\Models\HallBookingInfo;
use App\Jobs\SendEmailJob;
use DB;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '60000');

class ImportRoom implements ToCollection  ,WithHeadingRow 
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
              if(isset($this->mappingRow[0]) && !empty($this->mappingRow[0]) && isset($this->mappingRow[1]) && !empty($this->mappingRow[1])  && isset($this->mappingRow[2]) && !empty($this->mappingRow[2]) && isset($this->mappingRow[3]) && !empty($this->mappingRow[3]) && !empty($valuemamber[$this->mappingRow[0]]) && !empty($valuemamber[$this->mappingRow[1]]) && !empty($valuemamber[$this->mappingRow[2]]) && !empty($valuemamber[$this->mappingRow[3]])){
                    $quotaHallDetails = QuotaHall::where('id',$valuemamber[$this->mappingRow[0]])->first();
                    //dd( $quotaHallDetails);
                    if(!empty($quotaHallDetails)){
                        $importRoomData = new QuotaRoom();
                        $importDatabaseFields = $importRoomData->getQoutaRoomtable();
                        if(isset($importDatabaseFields) && count($importDatabaseFields)){
                            $importRoomData['hall_setting_id'] = $this->year;
                            $importRoomData['quota_hall_id'] = $quotaHallDetails->id;
                            $importRoomData['quota_id'] = $quotaHallDetails->quota_id;
                            $importRoomData['start_date'] = $quotaHallDetails->start_date;
                            $importRoomData['end_date'] = $quotaHallDetails->end_date;
                            $importRoomData['college_name'] = $quotaHallDetails->college_name;
                           
                            foreach($importDatabaseFields as $key => $importDatabaseFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){ 
                                    if($importDatabaseFieldsData=="status"){
                                        $status  = $valuemamber[$this->mappingRow[3]];
                                        if($status=="Enabled"){
                                            $importRoomData[$importDatabaseFieldsData] ='1';
                                        }else{
                                              $importRoomData[$importDatabaseFieldsData] = '0';
                                        }
                                    }else{
                                        $importRoomData[$importDatabaseFieldsData] = trim($valuemamber[$this->mappingRow[$key]]);
                                    }
                                }
                            }
                        }
                       $importRoomData->save();
                        /** End **/
                         if(isset($quotaHallDetails->id) && !empty($quotaHallDetails->id)){
                            if ($valuemamber[$this->mappingRow[2]] == 'Male') {
                                $getGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->orderBy('hall_booking_infos.id','ASC')->where('quota_hall_id',$quotaHallDetails->id)->whereNotNull('quota_room_id')->count();
                                if (isset($getGenderMaleBooking) && ($quotaHallDetails->male >= $getGenderMaleBooking)) {
                                    $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                            })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->orderBy('hall_booking_infos.id','ASC')->where('quota_hall_id',$quotaHallDetails->id)->whereNull('quota_room_id')->first();
                                    if(!empty($totalGenderMaleBooking)){
                                        $totalGenderMaleBooking->update(['quota_room_id'=>$importRoomData->id]);
                                        $mailInfo = [
                                            'given_name'            => $totalGenderMaleBooking->getMemberdata->given_name,
                                            'application_number'    => $totalGenderMaleBooking->getMemberdata->application_number,                        
                                            'accommodation'         => $totalGenderMaleBooking->getHallSettingDetail,                        
                                            'quotahall'             => $quotaHallDetails,             
                                            'booking'               => $totalGenderMaleBooking,                      
                                            'memberinfo'            => $totalGenderMaleBooking->getMemberdata,                        
                                        ];
                                        $details = ['type'=>'HallInfoUpdate','email' =>$totalGenderMaleBooking->getMemberdata->email_address,'mailInfo' => $mailInfo];
                                        //dd($details);
                                        SendEmailJob::dispatch($details);
                                    }
                                }
                            }else{
                                $getGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->orderBy('hall_booking_infos.id','ASC')->where('quota_hall_id',$quotaHallDetails->id)->whereNotNull('quota_room_id')->count();
                                if (isset($getGenderFemaleBooking) && ($quotaHallDetails->female >= $getGenderFemaleBooking)) {
                                    $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                            })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->orderBy('hall_booking_infos.id','ASC')->where('quota_hall_id',$quotaHallDetails->id)->whereNull('quota_room_id')->first();
                                    if(!empty($totalGenderFemaleBooking)){
                                        $totalGenderFemaleBooking->update(['quota_room_id'=>$importRoomData->id]);
                                        $mailInfo = [
                                            'given_name'            => $totalGenderFemaleBooking->getMemberdata->given_name,
                                            'application_number'    => $totalGenderFemaleBooking->getMemberdata->application_number,
                                            'accommodation'         => $totalGenderFemaleBooking->getHallSettingDetail,                        
                                            'quotahall'             => $quotaHallDetails,                        
                                            'booking'               => $totalGenderFemaleBooking,                        
                                            'memberinfo'            => $totalGenderFemaleBooking->getMemberdata,                        
                                        ];
                                        $details = ['type'=>'HallInfoUpdate','email' =>$totalGenderFemaleBooking->getMemberdata->email_address,'mailInfo' => $mailInfo];
                                        SendEmailJob::dispatch($details);
                                    }
                                }
                            }
                        }
                         /** Import Data**/ 
                        $ImportRoomDetails = new ImportRoomDetail();
                        $importDatabaseFields = $ImportRoomDetails->getQoutaRoomtable();
                        if(isset($importDatabaseFields) && count($importDatabaseFields)){
                            $ImportRoomDetails['import_data_info_id'] = $this->importDataId;
                            $ImportRoomDetails['hall_setting_id'] = $this->year;
                            $ImportRoomDetails['quota_hall_id'] = $quotaHallDetails->id;
                            $ImportRoomDetails['start_date'] = $quotaHallDetails->start_date;
                            $ImportRoomDetails['end_date'] = $quotaHallDetails->end_date;
                            $ImportRoomDetails['college_name'] = $quotaHallDetails->college_name;
                            $ImportRoomDetails['status'] = '1';
                            foreach($importDatabaseFields as $key => $importDatabaseFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    if($importDatabaseFieldsData=="status"){
                                        $ImportRoomDetails[$importDatabaseFieldsData] ='1';
                                    }else{
                                        $ImportRoomDetails[$importDatabaseFieldsData] = trim($valuemamber[$this->mappingRow[$key]]);
                                    }

                                    
                                }
                            }
                        }
                        //dd($ImportRoomDetails);
                        $ImportRoomDetails->save();
                        /** End **/
                    }else{
                        $ImportRoomDetails = new ImportRoomDetail();
                        $importDatabaseFields = $ImportRoomDetails->getQoutaRoomtable();
                        if(isset($importDatabaseFields) && count($importDatabaseFields)){
                            $ImportRoomDetails['import_data_info_id'] = $this->importDataId;
                            $ImportRoomDetails['hall_setting_id'] = $this->year;
                            $ImportRoomDetails['reason'] = 'QuotaHall  Id Not Match.';
                            $ImportRoomDetails['status'] = '0';
                            foreach($importDatabaseFields as $key => $importDatabaseFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    $ImportRoomDetails[$importDatabaseFieldsData] = trim($valuemamber[$this->mappingRow[$key]]);
                                }
                            }
                        }
                        $ImportRoomDetails->save();
                    }
                }else{
                    if(!empty($this->mappingRow[0])){
                        $ImportRoomDetails = new ImportRoomDetail();
                        $importDatabaseFields = $ImportRoomDetails->getQoutaRoomtable();
                        if(isset($importDatabaseFields) && count($importDatabaseFields)){
                            $ImportRoomDetails['import_data_info_id'] = $this->importDataId;
                            $ImportRoomDetails['hall_setting_id'] = $this->year;
                            if(empty($valuemamber[$this->mappingRow[0]])){
                                $ImportRoomDetails['reason'] = 'QuotaHall Id Empty';
                            }elseif(empty($valuemamber[$this->mappingRow[1]])) {
                               $ImportRoomDetails['reason'] = 'Room Code Empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[2]])){
                                $ImportRoomDetails['reason'] = 'Gender empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[3]])){
                                $ImportRoomDetails['reason'] = 'Status Empty.';
                            }
                            $ImportRoomDetails['status'] = '0';
                            foreach($importDatabaseFields as $key => $importDatabaseFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    $ImportRoomDetails[$importDatabaseFieldsData] = trim($valuemamber[$this->mappingRow[$key]]);
                                }
                            }
                        }
                        $ImportRoomDetails->save();
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
