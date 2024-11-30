<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Storage;
use App\Models\ImportPrivateEventOrderDetail;
use App\Models\PrivateEventOrder;
use App\Models\PrivateEventSetting;
use App\Models\MemberInfo;
use DB;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '60000');

class ImportPrivateEventOrder implements ToCollection  ,WithHeadingRow 
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
    * Private Event Import Booking By Akash
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        if(!empty($rows)){
            foreach ($rows as $keymember => $valuemamber){ 
                
                if(isset($this->mappingRow[0]) && !empty($valuemamber[$this->mappingRow[0]]) && isset($this->mappingRow[1]) && !empty($valuemamber[$this->mappingRow[1]]) && isset($this->mappingRow[2]) && !empty($valuemamber[$this->mappingRow[2]]) && isset($this->mappingRow[3]) && !empty($valuemamber[$this->mappingRow[3]]) && isset($this->mappingRow[4]) && !empty($valuemamber[$this->mappingRow[4]]) && isset($this->mappingRow[5]) && !empty($valuemamber[$this->mappingRow[5]]) ){
                    $booking_id = PrivateEventOrder::generatePrivateEventId();
                    $privateEventOrder = PrivateEventOrder::where('application_id',$valuemamber[$this->mappingRow[0]])->where('event_id', $valuemamber[$this->mappingRow[1]])->first();

                    $memberinfo = MemberInfo::where('application_number', $valuemamber[$this->mappingRow[0]])->first();
                    $privateEventExist = PrivateEventSetting::where('id', $valuemamber[$this->mappingRow[1]])->first();
                    if (empty($privateEventOrder) && !empty($privateEventExist) && !empty($memberinfo)) {
                        $privateEventOrderData = new PrivateEventOrder();
                        $privateEventOrderData->booking_id = $booking_id;
                        $databaseFields = $privateEventOrderData->getPrivateEventOrderTableColumns();

                        if(isset($databaseFields) && count($databaseFields)){
                            
                            foreach($databaseFields as $key => $getFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){

                                    if($getFieldsData=='event_status' && $valuemamber[$this->mappingRow[5]] == 'Enroled and Confirmed'){
                                        $privateEventOrderData[$getFieldsData] = 'Paid';
                                    }else{
                                        $privateEventOrderData[$getFieldsData] = trim($valuemamber[$this->mappingRow[$key]]);
                                    }
                                }
                            }
                        }
                        $privateEventOrderData->save();
                       

                        /** Import Data**/ 
                        $importEventDetails = new ImportPrivateEventOrderDetail();
                        $importEventDetails->booking_id = $booking_id;
                        $databaseFields = $importEventDetails->getPrivateEventOrderTableColumns();
                        if(isset($databaseFields) && count($databaseFields)){

                            $importEventDetails['import_data_info_id'] = $this->importDataId;

                            foreach($databaseFields as $key => $getTabledatavalues){

                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){

                                    if($getTabledatavalues=='status'){
                                        $importEventDetails[$getTabledatavalues] = '1';
                                    }elseif($getTabledatavalues=='event_status' && $valuemamber[$this->mappingRow[5]] == 'Enroled and Confirmed'){
                                        $importEventDetails[$getTabledatavalues] = 'Paid';
                                    }else{
                                        $importEventDetails[$getTabledatavalues] = $valuemamber[$this->mappingRow[$key]];
                                    }
                                }
                            }
                        }
                        $importEventDetails->save();
                    /** End **/                     
                    }else{
                        if (!empty($privateEventOrder) || empty($privateEventExist) || empty($memberinfo)) {
                            $failedMemberData = new ImportPrivateEventOrderDetail();
                            $failedMemberData->booking_id = $booking_id;
                            $databaseFields = $failedMemberData->getPrivateEventOrderTableColumns();
                            if(isset($databaseFields) && count($databaseFields)){
                                $failedMemberData['import_data_info_id'] = $this->importDataId;
                                $failedMemberData['status'] = '0';

                                if ( empty($memberinfo) ) {
                                    $failedMemberData['reason'] = 'Member does not exist';
                                }else if( !empty($privateEventOrder) ){
                                    $failedMemberData['reason'] = 'Booking already exist.';
                                }else{
                                    $failedMemberData['reason'] = 'Private Event Doesnot exist.';

                                }
                                foreach($databaseFields as $key => $getTableFieldsvalue){
                                    if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){

                                        $failedMemberData[$getTableFieldsvalue] = $valuemamber[$this->mappingRow[$key]];
                                    }
                                }
                            }
                            $failedMemberData->save();
                        }else{
                            $failedMemberData = new ImportPrivateEventOrderDetail();
                            $failedMemberData->booking_id = $booking_id;
                            $databaseFields = $failedMemberData->getPrivateEventOrderTableColumns();

                            if(isset($databaseFields) && count($databaseFields)){

                                $failedMemberData['import_data_info_id'] = $this->importDataId;

                                foreach($databaseFields as $key => $getTableFieldsvalus){

                                    if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                        if($getTableFieldsvalus=='status'){
                                            $failedMemberData[$getTableFieldsvalus] = '1';
                                        }elseif($getTableFieldsvalus=='event_status' && $valuemamber[$this->mappingRow[5]] == 'Enroled and Confirmed'){
                                            $failedMemberData[$getTableFieldsvalus] = 'Paid';
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

                        $failedMemberData = new ImportPrivateEventOrderDetail();
                        $failedMemberData->booking_id = PrivateEventOrder::generatePrivateEventId();
                        $databaseFields = $failedMemberData->getPrivateEventOrderTableColumns();
                        if(isset($databaseFields) && count($databaseFields)){

                            $failedMemberData['import_data_info_id'] = $this->importDataId;

                            if(empty($valuemamber[$this->mappingRow[0]])){
                                $failedMemberData['reason'] = 'Booking record already exist';
                            }elseif(empty($valuemamber[$this->mappingRow[1]])){
                                $failedMemberData['reason'] = 'Event Id is empty.';
                            }
                            $failedMemberData['status'] = '0';

                            foreach($databaseFields as $key => $getTableFieldsvalue){

                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){

                                    if($getTableFieldsvalue=='status'){
                                        $failedMemberData[$getTableFieldsvalue] = '0';
                                    }elseif($getTableFieldsvalue=='event_status' && $valuemamber[$this->mappingRow[5]] == 'Enroled and Confirmed'){
                                        $failedMemberData[$getTableFieldsvalue] = 'Paid';
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
                ImportDataInfo::where('id',$this->importDataId)->update(['reason'=>'CSV/Xlsx File empty.','status'=>'0']);
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
