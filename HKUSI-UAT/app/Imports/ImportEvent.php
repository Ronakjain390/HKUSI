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
use App\Models\EventProgramme;
use App\Models\Programme;
use DB;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '60000');

class ImportEvent implements ToCollection  ,WithHeadingRow 
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
                $valuemamber[$this->mappingRow[5]] = strtotime($this->transformDate($valuemamber[$this->mappingRow[5]]));
                $valuemamber[$this->mappingRow[6]] = strtotime($this->transformDate($valuemamber[$this->mappingRow[6]]));
                $valuemamber[$this->mappingRow[7]] = strtotime($this->transformDate($valuemamber[$this->mappingRow[7]]));
                $valuemamber[$this->mappingRow[8]] = strtotime($this->transformDate($valuemamber[$this->mappingRow[8]]));
                $valuemamber[$this->mappingRow[9]] = strtotime($this->transformDate($valuemamber[$this->mappingRow[9]]));
                $valuemamber[$this->mappingRow[16]] = strtotime($this->transformDate($valuemamber[$this->mappingRow[16]]));
                $categoryid = $languageid = null;
                if (!empty($valuemamber[$this->mappingRow[14]])) {
                        $category = Category::where('name',$valuemamber[$this->mappingRow[14]])->first();
                    if (empty($category)) {
                        $newcategory = new Category();
                        $newcategory['name']     = $valuemamber[$this->mappingRow[14]];
                        $newcategory['status']   = '1';
                        $newcategory->save();
                        $categoryid = $newcategory->id;
                    }else{
                        $categoryid = $category->id;
                    }
                }
                if (!empty($valuemamber[$this->mappingRow[15]])) {
                    $language = Language::where('name',$valuemamber[$this->mappingRow[15]])->first();
                    if (empty($language)) {
                        $newlanguage = new Language();
                        $newlanguage['name']     = $valuemamber[$this->mappingRow[15]];
                        $newlanguage['status']   = '1';
                        $newlanguage->save();
                        $languageid = $newlanguage->id;
                    }else{
                        $languageid = $language->id;
                    }
                }
                if(isset($this->mappingRow[0]) && !empty($this->mappingRow[0]) && isset($this->mappingRow[3]) && !empty($this->mappingRow[3]) && isset($this->mappingRow[5]) && !empty($this->mappingRow[5]) && isset($this->mappingRow[6]) && !empty($this->mappingRow[6]) && isset($this->mappingRow[7]) && !empty($this->mappingRow[7]) && isset($this->mappingRow[8]) && !empty($this->mappingRow[8]) && isset($this->mappingRow[9]) && !empty($this->mappingRow[9]) && isset($this->mappingRow[12]) && !empty($this->mappingRow[12]) && isset($this->mappingRow[14]) && !empty($this->mappingRow[14]) && isset($this->mappingRow[14])  && !empty($this->mappingRow[14]) && isset($this->mappingRow[16]) && !empty($this->mappingRow[16]) && !empty($valuemamber[$this->mappingRow[0]]) && !empty($valuemamber[$this->mappingRow[3]]) && !empty($valuemamber[$this->mappingRow[5]]) && !empty($valuemamber[$this->mappingRow[6]]) && !empty($valuemamber[$this->mappingRow[7]]) && !empty($valuemamber[$this->mappingRow[8]]) && !empty($valuemamber[$this->mappingRow[9]]) && !empty($valuemamber[$this->mappingRow[10]]) && !empty($valuemamber[$this->mappingRow[12]]) && !empty($valuemamber[$this->mappingRow[14]]) && !empty($valuemamber[$this->mappingRow[16]]) ){
                    $EventSetting = EventSetting::where('event_name',$valuemamber[$this->mappingRow[0]])->first();
                    if (empty($EventSetting)) {
                        $EventSettingData = new EventSetting();
                        $databaseFields = $EventSettingData->getEventTableColumns();
                        if(isset($databaseFields) && count($databaseFields)){
                            $EventSettingData['hall_setting_id'] = $this->year;
                            $EventSettingData['quota_balance'] = $valuemamber[$this->mappingRow[9]];
                            foreach($databaseFields as $key => $getFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    if($getFieldsData=='date'){
                                        $date = $valuemamber[$this->mappingRow[7]];;
                                        $EventSettingData[$getFieldsData] = $date;
                                    }elseif($getFieldsData=='assembly_start_time'){
                                        $assembly_start_time = $valuemamber[$this->mappingRow[5]];
                                        $EventSettingData[$getFieldsData] = $assembly_start_time;
                                    }elseif($getFieldsData=='assembly_end_time'){
                                        $assembly_end_time = $valuemamber[$this->mappingRow[6]];
                                        $EventSettingData[$getFieldsData] = $assembly_end_time;
                                    }elseif($getFieldsData=='start_time'){
                                        $start_time = $valuemamber[$this->mappingRow[8]];
                                        $EventSettingData[$getFieldsData] = $start_time;
                                    }elseif($getFieldsData=='end_time'){
                                        $end_time = $valuemamber[$this->mappingRow[9]];
                                        $EventSettingData[$getFieldsData] = $end_time;
                                    }elseif($getFieldsData=='application_deadline'){
                                        $deadline = $valuemamber[$this->mappingRow[16]];
                                        $EventSettingData[$getFieldsData] = $deadline;
                                    }elseif($getFieldsData=='event_category_id'){
                                        $eventTypeid = $categoryid;
                                        $EventSettingData[$getFieldsData] = $eventTypeid;
                                    }elseif($getFieldsData=='language_id'){
                                        $language = $languageid;
                                        $EventSettingData[$getFieldsData] = $language;
                                    }else{
                                        $EventSettingData[$getFieldsData] = trim($valuemamber[$this->mappingRow[$key]]);
                                    }
                                }
                            }
                        }
                        $EventSettingData->save();
                        /*Import Programme*/
                        $event_id = $EventSettingData->id;
                        $findprograme =  $valuemamber[$this->mappingRow[23]];
                        $explodedata = explode(',', $findprograme);
                        foreach($explodedata as $datagetPrograme){
                            $programmes = Programme::where('programme_code',$datagetPrograme)->first();
                            if(!empty($programmes)){
                                EventProgramme::insert(['event_id'=>$event_id,'program_id'=>$programmes->id]);
                            }
                        }
                        
                        /*Import Programme*/

                        /** Import Data**/ 
                        $importEventDetails = new ImportEventDetail();
                        $databaseFields = $importEventDetails->getEventTableColumns();
                        if(isset($databaseFields) && count($databaseFields)){
                             $importEventDetails['import_data_info_id'] = $this->importDataId;
                            foreach($databaseFields as $key => $getTabledatavalues){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    if($getTabledatavalues=='date'){
                                        $date = $valuemamber[$this->mappingRow[7]];;
                                        $importEventDetails[$getTabledatavalues] = $date;
                                    }elseif($getTabledatavalues=='assembly_start_time'){
                                        $assembly_start_time = $valuemamber[$this->mappingRow[5]];
                                        $importEventDetails[$getTabledatavalues] = $assembly_start_time;
                                    }elseif($getTabledatavalues=='assembly_end_time'){
                                        $assembly_end_time = $valuemamber[$this->mappingRow[6]];
                                        $importEventDetails[$getTabledatavalues] = $assembly_end_time;
                                    }elseif($getTabledatavalues=='start_time'){
                                        $start_time = $valuemamber[$this->mappingRow[8]];
                                        $importEventDetails[$getTabledatavalues] = $start_time;
                                    }elseif($getTabledatavalues=='end_time'){
                                        $end_time = $valuemamber[$this->mappingRow[9]];
                                        $importEventDetails[$getTabledatavalues] = $end_time;
                                    }elseif($getTabledatavalues=='application_deadline'){
                                        $deadline = $valuemamber[$this->mappingRow[16]];
                                        $importEventDetails[$getTabledatavalues] = $deadline;
                                    }elseif($getTabledatavalues=='event_category_id'){
                                        $eventTypeid = $categoryid;
                                        $importEventDetails[$getTabledatavalues] = $eventTypeid;
                                    }elseif($getTabledatavalues=='language_id'){
                                        $language = $languageid;
                                        $importEventDetails[$getTabledatavalues] = $language;
                                    }elseif($getTabledatavalues=='status'){
                                        $importEventDetails[$getTabledatavalues] = '1';
                                    }else{
                                        $importEventDetails[$getTabledatavalues] = $valuemamber[$this->mappingRow[$key]];
                                    }
                                }
                            }
                        }
                        $importEventDetails->save();
                    /** End **/                     
                    }else{
                        if (!empty($EventSetting)) {
                            $failedMemberData = new ImportEventDetail();
                            $databaseFields = $failedMemberData->getEventTableColumns();
                            if(isset($databaseFields) && count($databaseFields)){
                                $failedMemberData['import_data_info_id'] = $this->importDataId;
                                $failedMemberData['status'] = '0';
                                $failedMemberData['reason'] = 'Event duplicate entry.';
                                foreach($databaseFields as $key => $getTableFieldsvalue){
                                    if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                        if($getTableFieldsvalue=='date'){
                                            $date = $valuemamber[$this->mappingRow[7]];;
                                            $failedMemberData[$getTableFieldsvalue] = $date;
                                        }elseif($getTableFieldsvalue=='assembly_start_time'){
                                            $assembly_start_time = $valuemamber[$this->mappingRow[5]];
                                            $failedMemberData[$getTableFieldsvalue] = $assembly_start_time;
                                        }elseif($getTableFieldsvalue=='assembly_end_time'){
                                            $assembly_end_time = $valuemamber[$this->mappingRow[6]];
                                            $failedMemberData[$getTableFieldsvalue] = $assembly_end_time;
                                        }elseif($getTableFieldsvalue=='start_time'){
                                            $start_time = $valuemamber[$this->mappingRow[8]];
                                            $failedMemberData[$getTableFieldsvalue] = $start_time;
                                        }elseif($getTableFieldsvalue=='end_time'){
                                            $end_time = $valuemamber[$this->mappingRow[9]];
                                            $failedMemberData[$getTableFieldsvalue] = $end_time;
                                        }elseif($getTableFieldsvalue=='application_deadline'){
                                            $deadline = $valuemamber[$this->mappingRow[16]];
                                            $failedMemberData[$getTableFieldsvalue] = $deadline;
                                        }elseif($getTableFieldsvalue=='event_category_id'){
                                            $eventTypeid = $categoryid;
                                            $failedMemberData[$getTableFieldsvalue] = $eventTypeid;
                                        }elseif($getTableFieldsvalue=='language_id'){
                                            $language = $languageid;
                                            $failedMemberData[$getTableFieldsvalue] = $language;
                                        }elseif($getTableFieldsvalue=='status'){
                                            $failedMemberData[$getTableFieldsvalue] = '0';
                                        }else{
                                            $failedMemberData[$getTableFieldsvalue] = $valuemamber[$this->mappingRow[$key]];
                                        }
                                    }
                                }
                            }
                            $failedMemberData->save();
                        }else{
                            $failedMemberData = new ImportEventDetail();
                            $databaseFields = $failedMemberData->getEventTableColumns();
                            if(isset($databaseFields) && count($databaseFields)){
                                $failedMemberData['import_data_info_id'] = $this->importDataId;
                                foreach($databaseFields as $key => $getTableFieldsvalus){
                                    if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                        if($getTableFieldsvalus=='date'){
                                            $date = $valuemamber[$this->mappingRow[7]];;
                                            $failedMemberData[$getTableFieldsvalus] = $date;
                                        }elseif($getTableFieldsvalus=='assembly_start_time'){
                                            $assembly_start_time = $valuemamber[$this->mappingRow[5]];
                                            $failedMemberData[$getTableFieldsvalus] = $assembly_start_time;
                                        }elseif($getTableFieldsvalus=='assembly_end_time'){
                                            $assembly_end_time = $valuemamber[$this->mappingRow[5]];
                                            $failedMemberData[$getTableFieldsvalus] = $assembly_end_time;
                                        }elseif($getTableFieldsvalus=='start_time'){
                                            $start_time = $valuemamber[$this->mappingRow[8]];
                                            $failedMemberData[$getTableFieldsvalus] = $start_time;
                                        }elseif($getTableFieldsvalus=='end_time'){
                                            $end_time = $valuemamber[$this->mappingRow[9]];
                                            $failedMemberData[$getTableFieldsvalus] = $end_time;
                                        }elseif($getTableFieldsvalus=='application_deadline'){
                                            $deadline = $valuemamber[$this->mappingRow[16]];
                                            $failedMemberData[$getTableFieldsvalus] = $deadline;
                                        }elseif($getTableFieldsvalus=='event_category_id'){
                                            $eventTypeid = $categoryid;
                                            $failedMemberData[$getTableFieldsvalus] = $eventTypeid;
                                        }elseif($getTableFieldsvalus=='language_id'){
                                            $language = $languageid;
                                            $failedMemberData[$getTableFieldsvalus] = $language;
                                        }elseif($getTableFieldsvalus=='status'){
                                            $failedMemberData[$getTableFieldsvalus] = '1';
                                        }else{
                                            $failedMemberData[$getTableFieldsvalus] = $valuemamber[$this->mappingRow[$key]];
                                        }
                                    }
                                }
                            }
                            $failedMemberData->save();
                        }
                    }
                }else{
                    if(!empty($valuemamber[$this->mappingRow[0]])){
                        $failedMemberData = new ImportEventDetail();
                        $databaseFields = $failedMemberData->getEventTableColumns();
                        if(isset($databaseFields) && count($databaseFields)){
                            $failedMemberData['import_data_info_id'] = $this->importDataId;
                            if(empty($valuemamber[$this->mappingRow[0]])){
                                $failedMemberData['reason'] = 'Event name empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[3]])) {
                               $failedMemberData['reason'] = 'Event location empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[7]])){
                                $failedMemberData['reason'] = 'Event date empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[8]])){
                                $failedMemberData['reason'] = 'Event start time empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[9]])){
                                $failedMemberData['reason'] = 'Event end time empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[9]])){
                                $failedMemberData['reason'] = 'Event qouta empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[12]])){
                                $failedMemberData['reason'] = 'Booking Limit empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[14]])){
                                $failedMemberData['reason'] = 'Booking type empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[14]])){
                                $failedMemberData['reason'] = 'Language empty.';
                            }elseif(empty($valuemamber[$this->mappingRow[16]])){
                                $failedMemberData['reason'] = 'DeadLine empty.';
                            }
                            $failedMemberData['status'] = '0';
                            foreach($databaseFields as $key => $getTableFieldsvalue){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    if($getTableFieldsvalue=='date'){
                                        $date = $valuemamber[$this->mappingRow[7]];;
                                        $failedMemberData[$getTableFieldsvalue] = $date;
                                    }elseif($getTableFieldsvalue=='start_time'){
                                        $start_time = $valuemamber[$this->mappingRow[8]];
                                        $failedMemberData[$getTableFieldsvalue] = $start_time;
                                    }elseif($getTableFieldsvalue=='assembly_start_time'){
                                        $assembly_start_time = $valuemamber[$this->mappingRow[5]];
                                        $failedMemberData[$getTableFieldsvalue] = $assembly_start_time;
                                    }elseif($getTableFieldsvalue=='assembly_end_time'){
                                        $assembly_end_time = $valuemamber[$this->mappingRow[6]];
                                        $failedMemberData[$getTableFieldsvalue] = $assembly_end_time;
                                    }elseif($getTableFieldsvalue=='end_time'){
                                        $end_time = $valuemamber[$this->mappingRow[9]];
                                        $failedMemberData[$getTableFieldsvalue] = $end_time;
                                    }elseif($getTableFieldsvalue=='application_deadline'){
                                        $deadline = $valuemamber[$this->mappingRow[16]];
                                        $failedMemberData[$getTableFieldsvalue] = $deadline;
                                    }elseif($getTableFieldsvalue=='event_category_id'){
                                        $eventTypeid = $categoryid;
                                        $failedMemberData[$getTableFieldsvalue] = $eventTypeid;
                                    }elseif($getTableFieldsvalue=='language_id'){
                                        $language = $languageid;
                                        $failedMemberData[$getTableFieldsvalue] = $language;
                                    }elseif($getTableFieldsvalue=='status'){
                                        $failedMemberData[$getTableFieldsvalue] = '0';
                                    }else{
                                        $failedMemberData[$getTableFieldsvalue] = $valuemamber[$this->mappingRow[$key]];
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
    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}
