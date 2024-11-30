<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\ImportMemberDetail;
use App\Models\User;
use App\Models\Country;
use App\Models\ImportProgramme;
use Auth;

class ImportProgrammeDetailManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false ,$searchSubmit=false , $status=null,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countPrograme,$is_import,$totalCompleted,$totalFailed,$programe_code; 

    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
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
    public function render()
    {
        $programme = (new ImportProgramme)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $programme = $programme->whereBetween('created_at',[$form_date,$to_date]);
                
            }
        }

        if($this->daterange1 == true){
            if (isset($this->status) && !empty($this->status)) {
                $programme = $programme->where('status',$this->status);
            }
        }

        if (isset($this->status) && $this->status == 0) {
            $users =  $programme->where(function ($o) {
                $o->where('status','0');
            });
        }elseif(isset($this->status) && $this->status == 1){
            $users =  $programme->where(function ($o) {
                $o->where('status','1');
            });
        }


        
        if (isset($this->status) && $this->status != '') {
            $users =  $programme->where('status',$this->status);
        }        
        if ($this->searchSubmit == true) {
            $programme = $programme->where('programme_code', 'like', '%' . trim($this->search) . '%');            
        }
        if (isset($this->order_type) && $this->order_type == 'email') {
            $programme = $programme->whereHas('getUserDetail', function ($o) {$o->orderBy($this->order_type,$this->order_by);});
        }else{
            $programme = $programme->orderBy($this->order_type,$this->order_by);
        }
        $programme = $programme->where('import_data_info_id',$this->import_data_info_id)->paginate($this->paginate);
        $this->totalEnabled =  ImportProgramme::select('id','status')->where('import_data_info_id',$this->import_data_info_id)->where('status','1')->count();
        $this->totalDisabled = ImportProgramme::select('id','status')->where('import_data_info_id',$this->import_data_info_id)->where('status','0')->count();
        $this->programe_codedata = ImportProgramme::orderBy('id','ASC')->where('import_data_info_id',$this->import_data_info_id)->get();
        $this->countPrograme = ImportProgramme::where('import_data_info_id',$this->import_data_info_id)->count();
        return view('livewire.admin.programme.import-index',compact('programme'));
    }

}
