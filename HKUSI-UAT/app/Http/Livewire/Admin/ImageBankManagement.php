<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\ImageBank;
use App\Models\Country;
use App\Models\MemberInfo;
use App\Models\HallSetting;
use App\Models\Programme;
use Auth;
use ZipArchive,Response,File;

class ImageBankManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false ,$searchSubmit=false , $status=null,$totalEnabled,$totalDisabled,$applicationData=[],$nationality,$programe_code,$application_id,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$imagebankWithoutPagination,$countMember,$hall_setting_id,$programme_code;

    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    public $selectcheckbox = [];
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFrom()
    {
        $this->resetPage();
    }

    public function updatingTo()
    {
        $this->resetPage();
    }

    public function updatingPaginate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $imagebank = (new ImageBank)->newQuery();
		
		if ($this->searchSubmit == true) {
			$imagebank = $imagebank->join('member_infos', 'member_infos.image_bank_id', '=', 'image_banks.id')
						->join('member_programmes', 'member_programmes.member_info_id', '=', 'member_infos.id')
						->join('programmes', 'programmes.id', '=', 'member_programmes.programme_id')
						->join('member_hall_settings', 'member_hall_settings.member_info_id', '=', 'member_infos.id')
						->join('hall_settings', 'hall_settings.id', '=', 'member_hall_settings.hall_setting_id')
						->where('programmes.programme_code', 'like', '%' . $this->search . '%')
						->orWhere('programmes.programme_name', 'like', '%' . $this->search . '%')
						->orWhere('hall_settings.year', 'like', '%' . $this->search . '%')
						->orWhere('image_banks.application_id', 'like', '%' . $this->search . '%');
		}
        if($this->daterange == true){
			if( (isset($this->hall_setting_id) && !empty($this->hall_setting_id)) || (isset($this->programme_code) && !empty($this->programme_code))  ) {
            	$imagebank = $imagebank->join('member_infos', 'member_infos.image_bank_id', '=', 'image_banks.id')
						->join('member_programmes', 'member_programmes.member_info_id', '=', 'member_infos.id')
						->join('programmes', 'programmes.id', '=', 'member_programmes.programme_id')
						->join('member_hall_settings', 'member_hall_settings.member_info_id', '=', 'member_infos.id')
						->join('hall_settings', 'hall_settings.id', '=', 'member_hall_settings.hall_setting_id');
				if(isset($this->hall_setting_id) && !empty($this->hall_setting_id)) {
						$imagebank->Where('hall_settings.id', 'like', '%' . $this->hall_setting_id . '%');
				}
				if( isset($this->programme_code) && !empty($this->programme_code)){
						$imagebank->where('programmes.programme_code', 'like', '%' . $this->programme_code . '%');
				}
			}
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00'; 
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $imagebank = $imagebank->whereBetween('image_banks.created_at',[$form_date,$to_date]);
            }
            if (isset($this->application_id) && !empty($this->application_id)) {
                $imagebank = $imagebank->where('image_banks.application_id',$this->application_id);
			}
			if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Basic') {
                $imagebank = $imagebank->whereBetween('image_banks.created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 00:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $imagebank = $imagebank->whereBetween('image_banks.created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $a = date('w');
                $imagebank = $imagebank->whereBetween('image_banks.created_at',[date('Y-m-d 00:00:00',strtotime('- '.$a.' days')),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d',strtotime('- '.$a.' days'));
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                $imagebank = $imagebank->whereBetween('image_banks.created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')]);
                $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->start_date) && !empty($this->start_date) && isset($this->end_date) && !empty($this->end_date)) {
                    $form_start_date  = date('Y-m-d',strtotime($this->start_date)).' 00:00:00';
                    $to_end_date    = date('Y-m-d',strtotime($this->end_date)).' 23:59:59';
                    $imagebank = $imagebank->whereBetween('image_banks.created_at',[$form_start_date,$to_end_date]);
                }
            }
        }
		
		if($this->searchSubmit == true){
			$imagebank = $imagebank->select('image_banks.*')->groupBy('image_banks.id');
		}
		if( (isset($this->hall_setting_id) && !empty($this->hall_setting_id)) || (isset($this->programme_code) && !empty($this->programme_code))  ) {
			$imagebank = $imagebank->select('image_banks.*')->groupBy('image_banks.id');
		}
		//$imagebankObj = $imagebank;
		//$this->imagebankWithoutPagination = $imagebankObj->get();
        $imagebank = $imagebank->groupBy('image_banks.id')->orderBy('id','DESC')->paginate($this->paginate);
        $this->countMember = ImageBank::count();
        $Yeardata = HallSetting::where('status','1')->get();
		$programme = Programme::where('status','1')->orderBy('id','DESC')->get();
        return view('livewire.admin.imagebank.index',compact('imagebank','Yeardata','programme'));
	}

	public function imgDownload(){
    	$imagebank = (new ImageBank)->newQuery();
    	if ($this->searchSubmit == true) {
			$imagebank = $imagebank->join('member_infos', 'member_infos.image_bank_id', '=', 'image_banks.id')
						->join('member_programmes', 'member_programmes.member_info_id', '=', 'member_infos.id')
						->join('programmes', 'programmes.id', '=', 'member_programmes.programme_id')
						->join('member_hall_settings', 'member_hall_settings.member_info_id', '=', 'member_infos.id')
						->join('hall_settings', 'hall_settings.id', '=', 'member_hall_settings.hall_setting_id')
						->where('programmes.programme_code', 'like', '%' . $this->search . '%')
						->orWhere('programmes.programme_name', 'like', '%' . $this->search . '%')
						->orWhere('hall_settings.year', 'like', '%' . $this->search . '%')
						->orWhere('image_banks.application_id', 'like', '%' . $this->search . '%');
		}
		
        if($this->daterange == true){
			if( (isset($this->hall_setting_id) && !empty($this->hall_setting_id)) || (isset($this->programme_code) && !empty($this->programme_code))  ) {
            	$imagebank = $imagebank->join('member_infos', 'member_infos.image_bank_id', '=', 'image_banks.id')
						->join('member_programmes', 'member_programmes.member_info_id', '=', 'member_infos.id')
						->join('programmes', 'programmes.id', '=', 'member_programmes.programme_id')
						->join('member_hall_settings', 'member_hall_settings.member_info_id', '=', 'member_infos.id')
						->join('hall_settings', 'hall_settings.id', '=', 'member_hall_settings.hall_setting_id');
				if(isset($this->hall_setting_id) && !empty($this->hall_setting_id)) {
						$imagebank->Where('hall_settings.id', 'like', '%' . $this->hall_setting_id . '%');
				}
				if( isset($this->programme_code) && !empty($this->programme_code)){
						$imagebank->where('programmes.programme_code', 'like', '%' . $this->programme_code . '%');
				}
			}
		}
		if($this->searchSubmit == true){
			$imagebank = $imagebank->select('image_banks.*')->groupBy('image_banks.id');
		}
		if((isset($this->hall_setting_id) && !empty($this->hall_setting_id)) || (isset($this->programme_code) && !empty($this->programme_code))) {
			$imagebank = $imagebank->select('image_banks.*')->groupBy('image_banks.id');
		}
		$imagebankWithoutPagination = $imagebank->get();
        $imageApplicationIdArr = array();
		if(!empty($imagebankWithoutPagination)){
			foreach($imagebankWithoutPagination as $each){
				$imageApplicationIdArr[] = @$each->application_id;
			}
		}
		if(!empty($imageApplicationIdArr)){
			$folder_path = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'profile');
			$folderPathsTemp = public_path("profilezip");
			File::makeDirectory($folderPathsTemp, $mode = 0777, true, true);
			$zip_file_name = public_path('profilezip/profile-images.zip');
			$zip_file = $this->zipAndDownload($folder_path,$zip_file_name,$imageApplicationIdArr);
			if(file_exists($zip_file)){
				return response()->download($zip_file);
				//return redirect()->back()->with('success','File downloaded successfully.');
			} else {
				return redirect()->back()->with('notFoundError','Data Not found');
			}
		} else {
			 return redirect()->back()->with('notFoundError','Data Not found');
		}
    }
	
	public function zipAndDownload($folder_path,$zip_file_name,$imageApplicationIdArr){ 
		$zip = new ZipArchive;
		$zip->open($zip_file_name, ZipArchive::CREATE | ZipArchive::OVERWRITE); 
		$files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder_path));
		foreach ($files as $name => $file){ 
			if (!$file->isDir()) {
				$filePath  = $file->getRealPath();
				$relativePath = substr($filePath, strlen($folder_path) + 1);
				$application_id_arr = explode('.',$relativePath);
				if(isset($application_id_arr[0])){
					$application_id = $application_id_arr[0];
				}
				if(in_array($application_id,$imageApplicationIdArr)){
					$profile_image_obj = ImageBank::select('profile_image')->where('application_id',$application_id)->first();
					if(!empty($profile_image_obj->profile_image)){
						$profile_image =  str_replace(DIRECTORY_SEPARATOR."profile".DIRECTORY_SEPARATOR,"",$profile_image_obj->profile_image); 
						if(isset($profile_image) && isset($relativePath) && $profile_image==$relativePath){
							$zip->addFile($filePath, $relativePath);
						}
					}
				}
			}
		} 
		$zip->close(); 
		ob_end_clean();
		return $zip_file_name;
	}

    public function destroy($id)
    {
        ImageBank::find($id)->delete();
        session()->flash('success', 'ImageBank Data delete successfully.');
    }
}
