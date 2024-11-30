<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Storage;
use App\Models\ImportHotelDetail;
use App\Models\HotelSetting;
use App\Models\MemberInfo;
use DB;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '60000');

class ImportHotel implements ToCollection  ,WithHeadingRow 
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
    *  Import Hotel By Akash
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        if(!empty($rows)){
            foreach ($rows as $keymember => $valuemamber){ 
                
                if(isset($this->mappingRow[0]) && !empty($valuemamber[$this->mappingRow[0]]) && isset($this->mappingRow[2]) && !empty($valuemamber[$this->mappingRow[2]])){

                    $hotel = HotelSetting::where('hotel_name',$valuemamber[$this->mappingRow[0]])->first();

                    if (empty($hotel)) {
                        $hotelData = new HotelSetting();
                        $databaseFields = $hotelData->getHotelTableColumns();

                        if(isset($databaseFields) && count($databaseFields)){
                            $hotelData['hall_setting_id'] = $this->year;
                            
                            foreach($databaseFields as $key => $getFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){

                                    $hotelData[$getFieldsData] = trim($valuemamber[$this->mappingRow[$key]]);
                                }
                            }
                        }
                        $hotelData->save();
                       

                        /** Import Data**/ 
                        $importHotelDetails = new ImportHotelDetail();
                        $databaseFields = $importHotelDetails->getHotelTableColumns();
                        if(isset($databaseFields) && count($databaseFields)){

                            $importHotelDetails['import_data_info_id'] = $this->importDataId;

                            foreach($databaseFields as $key => $getTabledatavalues){

                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){

                                    if($getTabledatavalues=='status'){
                                        $importHotelDetails[$getTabledatavalues] = '1';
                                    }else{
                                        $importHotelDetails[$getTabledatavalues] = $valuemamber[$this->mappingRow[$key]];
                                    }
                                }
                            }
                        }
                        $importHotelDetails->save();
                    /** End **/                     
                    }else{
                        if (!empty($hotel)) {
                            $failedMemberData = new ImportHotelDetail();
                            $databaseFields = $failedMemberData->getHotelTableColumns();
                            if(isset($databaseFields) && count($databaseFields)){
                                $failedMemberData['import_data_info_id'] = $this->importDataId;
                                $failedMemberData['status'] = '0';

                                $failedMemberData['reason'] = 'Hotel duplicate entry.';
                                
                                foreach($databaseFields as $key => $getTableFieldsvalue){
                                    if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){

                                        $failedMemberData[$getTableFieldsvalue] = $valuemamber[$this->mappingRow[$key]];
                                    }
                                }
                            }
                            $failedMemberData->save();
                        }else{
                            $failedMemberData = new ImportHotelDetail();
                            $databaseFields = $failedMemberData->getHotelTableColumns();

                            if(isset($databaseFields) && count($databaseFields)){

                                $failedMemberData['import_data_info_id'] = $this->importDataId;

                                foreach($databaseFields as $key => $getTableFieldsvalus){

                                    if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                        if($getTableFieldsvalus=='status'){
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

                        $failedMemberData = new ImportHotelDetail();
                        $databaseFields = $failedMemberData->getHotelTableColumns();
                        if(isset($databaseFields) && count($databaseFields)){

                            $failedMemberData['import_data_info_id'] = $this->importDataId;

                            if(empty($valuemamber[$this->mappingRow[0]])){
                                $failedMemberData['reason'] = 'Hotel record already exist';
                            }
                            $failedMemberData['status'] = '0';

                            foreach($databaseFields as $key => $getTableFieldsvalue){

                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){

                                    if($getTableFieldsvalue=='status'){
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
