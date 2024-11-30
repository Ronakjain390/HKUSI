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
use App\Models\Programme;
use App\Models\ImportProgramme;
use App\Models\HallSetting;
use App\Imports\ImportProgrammeData;
use Auth,Storage,Config;

class ImportProgrammeManagement extends Component
{
	use WithFileUploads;
	public $xlsxFile,$headerRow,$year,$filePath,$importStep=1,$databaseFields=[],$mappingRow=[],$selectedFile,$getyear=[];
	public $type;

    public function render(){
		$this->getyear = HallSetting::select('id','year')->get();
        return view('livewire.admin.import.programme-import');
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
    	$file = $this->xlsxFile->store('programmes', 'public');
    	$this->filePath = base_path('public/storage/').$file;
    	$meberFilds = new Programme();
    	$this->databaseFields = $meberFilds->getProgrammeTableColumns();
    	if (isset($this->filePath) && !empty($this->filePath)) {
			$this->headerRow = (new HeadingRowImport)->toArray($this->filePath);
			foreach ($this->headerRow[0][0] as $key => $headerRowValue) {
				$this->mappingRow[$key]  = $headerRowValue;
			}
    	}
		// dd($this->databaseFields);
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
        $importData['type'] = 'Programme';
        $importData['status'] = '1';
        $importData->save();

        Excel::import(new ImportProgrammeData($this->mappingRow,$this->headerRow[0][0],$importData->id,$this->year), $this->filePath);

        $allImportRecord = ImportProgramme::select('id','status')->where('import_data_info_id',$importData->id)->get();
        $allImportFailedRecord = ImportProgramme::select('id','status')->where('import_data_info_id',$importData->id)->where('status','0')->get();
        if (count($allImportRecord) == count($allImportFailedRecord)) {
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