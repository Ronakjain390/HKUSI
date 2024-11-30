<?php

namespace App\Imports;

use App\Models\QuotaCountry;
use App\Models\Country;
use App\Models\ImportDataInfo;
use App\Models\ImportCountry;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendEmailJob;
use Auth;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '60000');

class ImportCountryCont implements ToCollection  ,WithHeadingRow 
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
                if(isset($this->mappingRow[0]) && !empty($this->mappingRow[0]) && !empty($valuemamber[$this->mappingRow[0]])){
                    $countries = Country::where('name',$valuemamber[$this->mappingRow[0]])->first();
                    if(empty($countries)){
                        $countrydata = new Country();
                        $name = '';
                        if(isset($this->mappingRow[0]) && !empty($this->mappingRow[0])){
                            $name = $valuemamber[$this->mappingRow[0]];
                        }
                        $countrydata['name']       = $name;
                        $countrydata['status']     = '1';
                        $countrydata->save();

                        /** Import Data**/ 
                        $importmemberData = new ImportCountry();
                        $importDatabaseFields = $importmemberData->getCountryTableColumns();
                        if(isset($importDatabaseFields) && count($importDatabaseFields)){
                            $importmemberData['import_data_info_id'] = $this->importDataId;
                            foreach($importDatabaseFields as $key => $importDatabaseFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    $importmemberData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                }
                            }
                        }
                        $importmemberData->save();
                        /** End **/
                    }else{
                        $failedMemberData = new ImportCountry();
                        $databaseFields = $failedMemberData->getCountryTableColumns();
                        if(isset($databaseFields) && count($databaseFields)){
                            // $failedMemberData['user_id'] = $userdata->id;
                            $failedMemberData['import_data_info_id'] = $this->importDataId;                           
                            $failedMemberData['reason'] = 'Country duplicate entry.';
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
                }else{
                    $failedMemberData = new ImportCountry();
                    $databaseFields = $failedMemberData->getCountryTableColumns();
                    if(isset($databaseFields) && count($databaseFields)){
                        // $failedMemberData['user_id'] = $userdata->id;
                        $failedMemberData['import_data_info_id'] = $this->importDataId;
                        $failedMemberData['reason'] = 'County name is empty.';
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
