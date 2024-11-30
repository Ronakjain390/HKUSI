<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\ImportDataInfo;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Models\Country;
use App\Models\QuotaHall;
use App\Models\HallSetting;
use App\Models\ImportHallDetail;
use App\Imports\ImportHall;
use Auth,Storage,Config;

class ImportHallManagement extends Component
{
	use WithFileUploads;
	public $xlsxFile,$headerRow,$year,$filePath,$importStep=1,$databaseFields=[],$mappingRow=[],$selectedFile,$getyear=[];
	public $type;

    public function render(){
		$this->getyear = HallSetting::select('id','year')->get();
        return view('livewire.admin.import.hall-import');
    }

    public function importFrist(){
    	$this->validate([
            	'xlsxFile' => 'required',
            	'year' => 'required',
	        ],
	        [
	        	'xlsxFile.required'=> 'The csv file is required.'
	        ]
	    );
	    $this->selectedFile = $this->xlsxFile->getClientOriginalName();
    	$file = $this->xlsxFile->store('importhall', 'public');
    	$this->filePath = base_path('public/storage/').$file;
    	$meberFilds = new QuotaHall();
    	$this->databaseFields = $meberFilds->getQoutaHalltable();
    	if (isset($this->filePath) && !empty($this->filePath)) {
			$this->headerRow = (new HeadingRowImport)->toArray($this->filePath);
			foreach ($this->headerRow[0][0] as $key => $headerRowValue) {
				$this->mappingRow[$key]  = $headerRowValue;
			}
    	}
		
    	$this->importStep = 2;
    }

    public function importSecond(){

    	$this->validate([
            	'mappingRow.*' => 'required',
	        ],
	        [
	        	'mappingRow.*.required'=> 'This fild is required.'
	        ]
	    );
    	 $importData = new ImportDataInfo();
        $importData['user_id'] = Auth::user()->id;
        $importData['hall_setting_id'] = $this->year;
        $importData['type'] = 'Hall';
        $importData['status'] = '1';
        $importData->save();
        
        Excel::import(new ImportHall($this->mappingRow,$this->headerRow[0][0],$importData->id,$this->year), $this->filePath);

        $allImportRecord = ImportHallDetail::where('import_data_info_id',$importData->id)->count();
        $allImportFailedRecord = ImportHallDetail::where('status','0')->where('import_data_info_id',$importData->id)->count();
        if ($allImportRecord == $allImportFailedRecord) {
            ImportDataInfo::where('id',$importData->id)->update(['status'=>'0','reason'=>'All entry duplicate.']);
        }
		$this->importStep = 3;
    } 

    public function importThird(){
    	$this->importStep = 3;
    }

    public function importFourth(){
    	$this->importStep = 4;
    }

    public function finishStep(){
    	$this->reset();
    	$getlastDetails = ImportDataInfo::select('id','type')->orderBy('id','DESC')->first();
    	if (isset($getlastDetails) && !empty($getlastDetails)) {
    		return redirect()->route('admin.import.importDetail',[$getlastDetails->id,'record']);
    	}else{
    		return redirect()->route('admin.import.index');
    	}
    }

}