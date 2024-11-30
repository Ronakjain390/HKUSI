<?php

namespace App\Imports;

use App\Models\MemberInfo;
use App\Models\User;
use App\Models\ImportDataInfo;
use App\Models\ImportMemberDetail;
use App\Models\Country;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendEmailJob;
use Auth,DB;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '60000');

class ImportMember implements ToCollection , WithHeadingRow , WithChunkReading , WithStartRow
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
                if(isset($this->mappingRow[1]) && !empty($this->mappingRow[1]) && isset($this->mappingRow[0]) && !empty($this->mappingRow[0]) && !empty($valuemamber[$this->mappingRow[1]]) && !empty($valuemamber[$this->mappingRow[0]])){
                    $users = User::where('email',$valuemamber[$this->mappingRow[1]])->first();
                    $member = MemberInfo::where('email_address',$valuemamber[$this->mappingRow[1]])->where('application_number',$valuemamber[$this->mappingRow[0]])->first();
                    $memberYear = DB::table('member_hall_settings')->leftJoin('member_infos' , function ($join){$join->on('member_hall_settings.member_info_id','=','member_infos.id');})->whereNull('member_infos.deleted_at')->where('member_infos.application_number',$valuemamber[$this->mappingRow[0]])->first();
                    $memberYearEmail = DB::table('member_hall_settings')->leftJoin('member_infos' , function ($join){$join->on('member_hall_settings.member_info_id','=','member_infos.id');})->whereNull('member_infos.deleted_at')->where('member_infos.email_address',$valuemamber[$this->mappingRow[1]])->first();
                    if (empty($users) && empty($member) && empty($memberYear) && empty($memberYearEmail)) {
                        $nationality = $study_country = null;
                        if (!empty($valuemamber[$this->mappingRow[9]])) {
                            $country = Country::where('name',$valuemamber[$this->mappingRow[9]])->first();
                            if (empty($country)) {
                                $newcountry = new Country();
                                $newcountry['name']     = $valuemamber[$this->mappingRow[9]];
                                $newcountry['status']   = '1';
                                $newcountry->save();
                                $nationality = $newcountry->id;
                            }else{
                                $nationality = $country->id;
                            }
                        }
                        if (!empty($valuemamber[$this->mappingRow[16]])) {
                            $country1 = Country::where('name',$valuemamber[$this->mappingRow[16]])->first();
                            if (empty($country1)) {
                                $newcountry1 = new Country();
                                $newcountry1['name']     = $valuemamber[$this->mappingRow[16]];
                                $newcountry1['status']   = '1';
                                $newcountry1->save();
                                $study_country = $newcountry1->id;
                            }else{
                                $study_country = $country1->id;
                            }
                        }
                        $userdata = new User();
                        $name = '';
                        if(isset($this->mappingRow[5]) && !empty($this->mappingRow[5])){
                            $name.= $valuemamber[$this->mappingRow[5]];
                        }
                        if(isset($this->mappingRow[4]) && !empty($this->mappingRow[4])){
                            $name.= ' '.$valuemamber[$this->mappingRow[4]];
                        }
                        $userdata['name']       = $name;
                        $userdata['email']      = $valuemamber[$this->mappingRow[1]];
                        $userdata['password']   = Hash::make($name);
                        $userdata['status']     = '1';
                        $userdata->save();
                        $userdata->assignRole('Member');

                        $memberData = new MemberInfo();
                        $databaseFields = $memberData->getMemberTableColumns();
                        if(isset($databaseFields) && count($databaseFields)){
                            $memberData['user_id'] = $userdata->id;
                            $memberData['nationality_id'] = $nationality;
                            $memberData['study_country_id'] = $study_country;
                            foreach($databaseFields as $key => $databaseFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    if($databaseFieldsData=='date_of_birth'){
                                        $date = $valuemamber[$this->mappingRow[$key]];
                                        $dob = strtotime($date);
                                        $memberData[$databaseFieldsData] = $dob;
                                    }else{
                                        $memberData[$databaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                    }
                                }
                            }
                        }
                        $memberData->save();

                        /** Member Year Data**/ 
                        $data = [];
                        $data['hall_setting_id'] = $this->year;
                        $data['member_info_id']  = $memberData->id;
                        DB::table('member_hall_settings')->insert($data);
                        /** End **/

                        /** Import Data**/ 
                        $importmemberData = new ImportMemberDetail();
                        $importDatabaseFields = $importmemberData->getMemberTableColumns();
                        if(isset($importDatabaseFields) && count($importDatabaseFields)){
                            $importmemberData['user_id'] = $userdata->id;
                            $importmemberData['import_data_info_id'] = $this->importDataId;
                            foreach($importDatabaseFields as $key => $importDatabaseFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    if($importDatabaseFieldsData=='date_of_birth'){
                                        $date = $valuemamber[$this->mappingRow[$key]];
                                        $dob = strtotime($date);
                                        $importmemberData[$importDatabaseFieldsData] = $dob;
                                    }else{
                                        $importmemberData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                    }
                                }
                            }
                        }
                        $importmemberData->save();
                        /** End **/

                        $url = env('FRONT_LOGIN_URL');  
                        $mailInfo = [
                            'given_name'     => $valuemamber[$this->mappingRow[5]],
                            'application_id' => $valuemamber[$this->mappingRow[0]],
                            'url'            => $url,
                        ];
                        $details = ['type'=>'RegisterTemplate','email' => $valuemamber[$this->mappingRow[1]],'mailInfo' => $mailInfo];
                        SendEmailJob::dispatchNow($details);
                    }else{
                        if (!empty($users) && !empty($member)) {
                            // $memberYear = DB::table('member_hall_settings')->where('hall_setting_id',$this->year)->where('member_info_id',$member->id)->first();
                            $memberYearAccordingApplicationNumber  = DB::table('member_hall_settings')->leftJoin('member_infos' , function ($join){$join->on('member_hall_settings.member_info_id','=','member_infos.id');})->whereNull('member_infos.deleted_at')->where('member_hall_settings.hall_setting_id',$this->year)->where('member_hall_settings.member_info_id',$member->id)->where('member_infos.application_number',$valuemamber[$this->mappingRow[0]])->first();
                            $memberYearAccordingEmail = DB::table('member_hall_settings')->leftJoin('member_infos' , function ($join){$join->on('member_hall_settings.member_info_id','=','member_infos.id');})->whereNull('member_infos.deleted_at')->where('member_hall_settings.hall_setting_id',$this->year)->where('member_hall_settings.member_info_id',$member->id)->where('member_infos.email_address',$valuemamber[$this->mappingRow[1]])->first();
                            if (empty($memberYearAccordingApplicationNumber) && empty($memberYearAccordingEmail)) {
                                /** Member Year Data**/ 
                                $data = [];
                                $data['hall_setting_id'] = $this->year;
                                $data['member_info_id']  = $member->id;
                                DB::table('member_hall_settings')->insert($data);
                                /** End **/

                                $failedMemberData = new ImportMemberDetail();
                                $databaseFields = $failedMemberData->getMemberTableColumns();
                                if(isset($databaseFields) && count($databaseFields)){
                                    $failedMemberData['import_data_info_id'] = $this->importDataId;      
                                    $failedMemberData['status'] = '1';
                                    foreach($databaseFields as $key => $databaseFields){
                                        if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                            if($databaseFields=='date_of_birth'){
                                                $date = $valuemamber[$this->mappingRow[$key]];
                                                $dob = strtotime($date);
                                                $failedMemberData[$databaseFields] = $dob;
                                            }else{
                                                $failedMemberData[$databaseFields] = $valuemamber[$this->mappingRow[$key]];
                                            }
                                        }
                                    }
                                }
                                $failedMemberData->save();

                                $url = env('FRONT_LOGIN_URL');  
                                $mailInfo = [
                                    'given_name'     => $member->given_name,
                                    'application_id' => $member->application_number,
                                    'url'            => $url,
                                ];
                                $details = ['type'=>'RegisterTemplate','email' => $member->email_address,'mailInfo' => $mailInfo];
                                SendEmailJob::dispatchNow($details);
                            }else{
                                if (!empty($memberYearAccordingApplicationNumber)) {
                                    $failedMemberData = new ImportMemberDetail();
                                    $databaseFields = $failedMemberData->getMemberTableColumns();
                                    if(isset($databaseFields) && count($databaseFields)){
                                    // $failedMemberData['user_id'] = $userdata->id;
                                        $failedMemberData['import_data_info_id'] = $this->importDataId;                           
                                        $failedMemberData['reason'] = 'Application number duplicate entry.';
                                        $failedMemberData['status'] = '0';
                                        foreach($databaseFields as $key => $databaseFields){
                                            if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                                if($databaseFields=='date_of_birth'){
                                                    $date = $valuemamber[$this->mappingRow[$key]];
                                                    $dob = strtotime($date);
                                                    $failedMemberData[$databaseFields] = $dob;
                                                }else{
                                                    $failedMemberData[$databaseFields] = $valuemamber[$this->mappingRow[$key]];
                                                }
                                            }
                                        }
                                    }
                                    $failedMemberData->save();
                                }else{
                                    $failedMemberData = new ImportMemberDetail();
                                    $databaseFields = $failedMemberData->getMemberTableColumns();
                                    if(isset($databaseFields) && count($databaseFields)){
                                    // $failedMemberData['user_id'] = $userdata->id;
                                        $failedMemberData['import_data_info_id'] = $this->importDataId;
                                        $failedMemberData['reason'] = 'Email address duplicate entry.';
                                        $failedMemberData['status'] = '0';
                                        foreach($databaseFields as $key => $databaseFields){
                                            if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                                if($databaseFields=='date_of_birth'){
                                                    $date = $valuemamber[$this->mappingRow[$key]];
                                                    $dob = strtotime($date);
                                                    $failedMemberData[$databaseFields] = $dob;
                                                }else{
                                                    $failedMemberData[$databaseFields] = $valuemamber[$this->mappingRow[$key]];
                                                }
                                            }
                                        }
                                    }
                                    $failedMemberData->save();
                                }
                            }
                        }else{
                            if (!empty($users)) {
                                $failedMemberData = new ImportMemberDetail();
                                $databaseFields = $failedMemberData->getMemberTableColumns();
                                if(isset($databaseFields) && count($databaseFields)){
                                // $failedMemberData['user_id'] = $userdata->id;
                                    $failedMemberData['import_data_info_id'] = $this->importDataId;                           
                                    $failedMemberData['reason'] = 'Email address duplicate entry.';
                                    $failedMemberData['status'] = '0';
                                    foreach($databaseFields as $key => $databaseFields){
                                        if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                            if($databaseFields=='date_of_birth'){
                                                $date = $valuemamber[$this->mappingRow[$key]];
                                                $dob = strtotime($date);
                                                $failedMemberData[$databaseFields] = $dob;
                                            }else{
                                                $failedMemberData[$databaseFields] = $valuemamber[$this->mappingRow[$key]];
                                            }
                                        }
                                    }
                                }
                                $failedMemberData->save();
                            }else{
                                $failedMemberData = new ImportMemberDetail();
                                $databaseFields = $failedMemberData->getMemberTableColumns();
                                if(isset($databaseFields) && count($databaseFields)){
                                    // $failedMemberData['user_id'] = $userdata->id;
                                    $failedMemberData['import_data_info_id'] = $this->importDataId;                            
                                    $failedMemberData['reason'] = 'Application Number duplicate entry.';
                                    $failedMemberData['status'] = '0';
                                    foreach($databaseFields as $key => $databaseFields){
                                        if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                            if($databaseFields=='date_of_birth'){
                                                $date = $valuemamber[$this->mappingRow[$key]];
                                                $dob = strtotime($date);
                                                $failedMemberData[$databaseFields] = $dob;
                                            }else{
                                                $failedMemberData[$databaseFields] = $valuemamber[$this->mappingRow[$key]];
                                            }
                                        }
                                    }
                                }
                                $failedMemberData->save();
                            }
                        }
                    }
                }else{
                    if (empty($valuemamber[$this->mappingRow[1]])) {
                        $failedMemberData = new ImportMemberDetail();
                        $databaseFields = $failedMemberData->getMemberTableColumns();
                        if(isset($databaseFields) && count($databaseFields)){
                            // $failedMemberData['user_id'] = $userdata->id;
                            $failedMemberData['import_data_info_id'] = $this->importDataId;
                            $failedMemberData['reason'] = 'Email address empty.';
                            $failedMemberData['status'] = '0';
                            foreach($databaseFields as $key => $databaseFields){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    if($databaseFields=='date_of_birth'){
                                        $date = $valuemamber[$this->mappingRow[$key]];
                                        $dob = strtotime($date);
                                        $failedMemberData[$databaseFields] = $dob;
                                    }else{
                                        $failedMemberData[$databaseFields] = $valuemamber[$this->mappingRow[$key]];
                                    }
                                }
                            }
                        }
                        $failedMemberData->save();
                    }else{
                        $failedMemberData = new ImportMemberDetail();
                        $databaseFields = $failedMemberData->getMemberTableColumns();
                        if(isset($databaseFields) && count($databaseFields)){
                            // $failedMemberData['user_id'] = $userdata->id;
                            $failedMemberData['import_data_info_id'] = $this->importDataId;
                            $failedMemberData['reason'] = 'Application number empty.';
                            $failedMemberData['status'] = '0';
                            foreach($databaseFields as $key => $databaseFields){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    if($databaseFields=='date_of_birth'){
                                        $date = $valuemamber[$this->mappingRow[$key]];
                                        $dob = strtotime($date);
                                        $failedMemberData[$databaseFields] = $dob;
                                    }else{
                                        $failedMemberData[$databaseFields] = $valuemamber[$this->mappingRow[$key]];
                                    }
                                }
                            }
                        }
                        $failedMemberData->save();
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

}
