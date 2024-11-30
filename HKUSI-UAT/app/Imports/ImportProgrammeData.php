<?php

namespace App\Imports;

use App\Models\Programme;
use App\Models\ImportProgramme;
use App\Models\ImportDataInfo;
use App\Models\MemberInfo;
use App\Models\MemberProgramme;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Storage;
use Auth,DB;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '60000');

class ImportProgrammeData implements ToCollection ,WithHeadingRow , WithChunkReading , WithStartRow
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
                $valuemamber[$this->mappingRow[3]] = strtotime($this->transformDate($valuemamber[$this->mappingRow[3]]));
                $valuemamber[$this->mappingRow[4]] = strtotime($this->transformDate($valuemamber[$this->mappingRow[4]]));
                if (isset($this->mappingRow[1]) && !empty($this->mappingRow[1]) &&  empty($valuemamber[$this->mappingRow[0]])) {
                    if (!empty($valuemamber[$this->mappingRow[1]])) {
                        $existProgramme = DB::table('programme_hall_settings')->select('programmes.id','programmes.programme_code')->leftJoin('programmes' , function ($join){$join->on('programme_hall_settings.programme_id','=','programmes.id');})->where('programmes.programme_code',$valuemamber[$this->mappingRow[1]])->first();
                        if (empty($existProgramme)) {
                            $programmeData = new Programme();
                            $importDatabaseFields = $programmeData->getProgrammeTableColumns();
                            if (isset($importDatabaseFields) && count($importDatabaseFields)) {
                                foreach($importDatabaseFields as $key => $importDatabaseFieldsData){
                                    if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                        $programmeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                    }
                                }
                            }
                            $programmeData->save(); 

                            /** Member Year Data**/ 
                            $data = [];
                            $data['hall_setting_id'] = $this->year;
                            $data['programme_id']  = $programmeData->id;
                            DB::table('programme_hall_settings')->insert($data);
                            /** End **/

                            $importProgrammeData = new ImportProgramme();
                            $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                            if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                                $importProgrammeData['import_data_info_id'] = $this->importDataId;
                                foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                    if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                        $importProgrammeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                    }
                                }
                            }
                            $importProgrammeData->save();
                        }else{
                            // $programmeYearAccording = DB::table('programme_hall_settings')->leftJoin('programmes' , function ($join){$join->on('programme_hall_settings.programme_id','=','programmes.id');})->where('programme_hall_settings.hall_setting_id',$this->year)->where('programme_hall_settings.programme_id',$existProgramme->id)->first();
                            // if (empty($programmeYearAccording)) {
                            //     /** Member Year Data**/ 
                            //     $data = [];
                            //     $data['hall_setting_id'] = $this->year;
                            //     $data['programme_id']  = $existProgramme->id;
                            //     DB::table('programme_hall_settings')->insert($data);
                            //     /** End **/

                            //     $importProgrammeData = new ImportProgramme();
                            //     $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                            //     if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                            //         $importProgrammeData['import_data_info_id'] = $this->importDataId;
                            //         foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                            //             if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                            //                 $importProgrammeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                            //             }
                            //         }
                            //     }
                            //     $importProgrammeData->save();
                            // }else{
                                $importProgrammeData = new ImportProgramme();
                                $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                                if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                                    $importProgrammeData['import_data_info_id'] = $this->importDataId;
                                    $importProgrammeData['reason'] = 'Programme code duplicate entry.';
                                    $importProgrammeData['status'] = '0';
                                    foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                        if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                            $importProgrammeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                        }
                                    }
                                }
                                $importProgrammeData->save();
                            }
                        // }
                    }else{
                        $importProgrammeData = new ImportProgramme();
                        $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                        if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                            $importProgrammeData['import_data_info_id'] = $this->importDataId;
                            $importProgrammeData['reason'] = 'Programme code empty.';
                            $importProgrammeData['status'] = '0';
                            foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    $importProgrammeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                }
                            }
                        }
                        $importProgrammeData->save();
                    }
                }else{
                    $memberData = MemberInfo::select('id','application_number','hkid_card_no')->where('application_number',$valuemamber[$this->mappingRow[0]])->first();
                    $existProgramme = DB::table('programme_hall_settings')->select('programmes.id','programmes.programme_code')->leftJoin('programmes' , function ($join){$join->on('programme_hall_settings.programme_id','=','programmes.id');})->where('programmes.programme_code',$valuemamber[$this->mappingRow[1]])->first();

                    if (isset($memberData) && !empty($memberData)) {
                        if (!empty($valuemamber[$this->mappingRow[1]])) {
                            if (empty($existProgramme)) {
                                $programmeData = new Programme();
                                $importDatabaseFields = $programmeData->getProgrammeTableColumns();
                                if (isset($importDatabaseFields) && count($importDatabaseFields)) {
                                    foreach($importDatabaseFields as $key => $importDatabaseFieldsData){
                                        if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                            $programmeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                        }
                                    }
                                }
                                $programmeData->save(); 

                                /** Member Year Data**/ 
                                $data = [];
                                $data['hall_setting_id'] = $this->year;
                                $data['programme_id']  = $programmeData->id;
                                DB::table('programme_hall_settings')->insert($data);
                                /** End **/  

                                if(MemberProgramme::where('member_info_id',$memberData->id)->where('programme_id',$programmeData->id)->doesntExist()){
                                    MemberProgramme::create(['member_info_id'=>$memberData->id,'programme_id'=>$programmeData->id]);
                                    $importProgrammeData = new ImportProgramme();
                                    $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                                    if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                                        $importProgrammeData['import_data_info_id'] = $this->importDataId;
                                        foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                            if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                                $importProgrammeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                            }
                                        }
                                    }
                                    $importProgrammeData->save();
                                }else{
                                    $importProgrammeData = new ImportProgramme();
                                    $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                                    if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                                        $importProgrammeData['import_data_info_id'] = $this->importDataId;
                                        $importProgrammeData['reason'] = 'Programme code and Application Number duplicate entry.';
                                        $importProgrammeData['status'] = '0';
                                        foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                            if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                                $importProgrammeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                            }
                                        }
                                    }
                                    $importProgrammeData->save();
                                }
                            }else{
                                $programmeYearAccording = DB::table('programme_hall_settings')->leftJoin('programmes' , function ($join){$join->on('programme_hall_settings.programme_id','=','programmes.id');})->where('programmes.start_date',$valuemamber[$this->mappingRow[3]])->where('programmes.end_date',$valuemamber[$this->mappingRow[4]])->where('programmes.id',$existProgramme->id)->first();
                                // if (empty($programmeYearAccording)) {
                                //     MemberProgramme::create(['member_info_id'=>$memberData->id,'programme_id'=>$existProgramme->id]);
                                //     /** Member Year Data**/ 
                                //     $data = [];
                                //     $data['hall_setting_id'] = $this->year;
                                //     $data['programme_id']    = $existProgramme->id;
                                //     DB::table('programme_hall_settings')->insert($data);
                                //     /** End **/

                                //     $importProgrammeData = new ImportProgramme();
                                //     $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                                //     if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                                //         $importProgrammeData['import_data_info_id'] = $this->importDataId;
                                //         foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                //             if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                //                 $importProgrammeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                //             }
                                //         }
                                //     }
                                //     $importProgrammeData->save();
                                // }else{
                                    // $importProgrammeData = new ImportProgramme();
                                    // $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                                    // if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                                    //     $importProgrammeData['import_data_info_id'] = $this->importDataId;
                                    //     $importProgrammeData['reason'] = 'Programme code duplicate entry.';
                                    //     $importProgrammeData['status'] = '0';
                                    //     foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                    //         if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                    //             $importProgrammeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                    //         }
                                    //     }
                                    // }
                                    // $importProgrammeData->save();
                                // }
                                if (!empty($programmeYearAccording)) {
                                    if(MemberProgramme::where('member_info_id',$memberData->id)->where('programme_id',$existProgramme->id)->doesntExist()){
                                        MemberProgramme::create(['member_info_id'=>$memberData->id,'programme_id'=>$existProgramme->id]);
                                        $importProgrammeData = new ImportProgramme();
                                        $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                                        if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                                            $importProgrammeData['import_data_info_id'] = $this->importDataId;
                                            foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                                    $importProgrammeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                                }
                                            }
                                        }
                                        $importProgrammeData->save();
                                    }else{
                                        $importProgrammeData = new ImportProgramme();
                                        $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                                        if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                                            $importProgrammeData['import_data_info_id'] = $this->importDataId;
                                            $importProgrammeData['reason'] = 'Programme code and Application Number duplicate entry.';
                                            $importProgrammeData['status'] = '0';
                                            foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                                if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                                    $importProgrammeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                                }
                                            }
                                        }
                                        $importProgrammeData->save();
                                    }
                                }else{
                                    $importProgrammeData = new ImportProgramme();
                                    $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                                    if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                                        $importProgrammeData['import_data_info_id'] = $this->importDataId;
                                        $importProgrammeData['reason'] = 'Date period not matched.';
                                        $importProgrammeData['status'] = '0';
                                        foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                            if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                                $importProgrammeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                            }
                                        }
                                    }
                                    $importProgrammeData->save();
                                }
                            }
                        }else{
                            $importProgrammeData = new ImportProgramme();
                            $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                            if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                                $importProgrammeData['import_data_info_id'] = $this->importDataId;
                                $importProgrammeData['reason'] = 'Programme code empty.';
                                $importProgrammeData['status'] = '0';
                                foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                    if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                        $importProgrammeData[$importDatabaseFieldsData] = (isset($valuemamber[$this->mappingRow[$key]]) && !empty($valuemamber[$this->mappingRow[$key]]))?$valuemamber[$this->mappingRow[$key]]:null;
                                    }
                                }
                            }
                            $importProgrammeData->save();
                        }
                    }else{
                        if (!empty($valuemamber[$this->mappingRow[1]])) {
                            if (empty($existProgramme)) {
                                $programmeData = new Programme();
                                $importDatabaseFields = $programmeData->getProgrammeTableColumns();
                                if (isset($importDatabaseFields) && count($importDatabaseFields)) {
                                    foreach($importDatabaseFields as $key => $importDatabaseFieldsData){
                                        if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                            $programmeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                        }
                                    }
                                }
                                $programmeData->save(); 

                                /** Member Year Data**/ 
                                $data = [];
                                $data['hall_setting_id'] = $this->year;
                                $data['programme_id']  = $programmeData->id;
                                DB::table('programme_hall_settings')->insert($data);
                                /** End **/  

                                $importProgrammeData = new ImportProgramme();
                                $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                                if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                                    $importProgrammeData['import_data_info_id'] = $this->importDataId;
                                    foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                        if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                            $importProgrammeData[$importDatabaseFieldsData] = $valuemamber[$this->mappingRow[$key]];
                                        }
                                    }
                                }
                                $importProgrammeData->save();

                            }else{
                                $importProgrammeData = new ImportProgramme();
                                $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                                if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                                    $importProgrammeData['import_data_info_id'] = $this->importDataId;
                                    $importProgrammeData['reason'] = 'Member not found.';
                                    $importProgrammeData['status'] = '0';
                                    foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                        if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                            $importProgrammeData[$importDatabaseFieldsData] = (isset($valuemamber[$this->mappingRow[$key]]) && !empty($valuemamber[$this->mappingRow[$key]]))?$valuemamber[$this->mappingRow[$key]]:null;
                                        }
                                    }
                                }
                                $importProgrammeData->save();
                            }
                        }else{
                            $importProgrammeData = new ImportProgramme();
                            $importProgrammeDatabaseFields = $importProgrammeData->getImportProgrammeTableColumns();
                            if (isset($importProgrammeDatabaseFields) && count($importProgrammeDatabaseFields)) {
                                $importProgrammeData['import_data_info_id'] = $this->importDataId;
                                $importProgrammeData['reason'] = 'Programme code empty.';
                                $importProgrammeData['status'] = '0';
                                foreach($importProgrammeDatabaseFields as $key => $importDatabaseFieldsData){
                                    if(isset($this->mappingRow[$key]) && !empty($this->mappingRow[$key])){
                                        $importProgrammeData[$importDatabaseFieldsData] = (isset($valuemamber[$this->mappingRow[$key]]) && !empty($valuemamber[$this->mappingRow[$key]]))?$valuemamber[$this->mappingRow[$key]]:null;
                                    }
                                }
                            }
                            $importProgrammeData->save();
                        }
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

    /**
     * Transform a date value into a Carbon object.
     *
     * @return \Carbon\Carbon|null
     */
    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}
