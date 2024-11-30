<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\ImportRoomDetail;
use App\Models\User;
use App\Models\Country;
use Auth;

class ImportRoomDetailsManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false ,$searchSubmit=false , $status, $countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$is_import,$totalCompleted,$totalFailed; 
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
        $members = (new ImportRoomDetail)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $members = $members->whereBetween('created_at',[$form_date,$to_date]);
                 
            }
        }
        if($this->daterange1 == true){
            if (isset($this->nationality) && !empty($this->nationality)) {
                $members = $members->where('nationality',$this->nationality);
            }
            if (isset($this->gender) && !empty($this->gender)) {
                $members = $members->where('gender',$this->gender);
            }
            if (isset($this->status)) {
                $members = $members->where('status',$this->status);
            }
            if (isset($this->study_country) && !empty($this->study_country)) {
                $members = $members->where('study_country',$this->study_country);
            }
        }
        if (isset($this->is_import) && $this->is_import != '') {
            $users =  $members->where('status',$this->is_import);
        }
        if (isset($this->language) && $this->language != '') {
            $members = $members->where('language',$this->language);
        }
        if (isset($this->import_data_info_id) && $this->import_data_info_id != '') {
            $members = $members->where('import_data_info_id',$this->import_data_info_id);
        $this->totalCompleted = ImportRoomDetail::where('import_data_info_id',$this->import_data_info_id)->where('status','1')->count();
        $this->totalFailed = ImportRoomDetail::where('import_data_info_id',$this->import_data_info_id)->where('status','0')->count();
        }
        
        if ($this->searchSubmit == true) {
            $members = $members->where(function ($k) {
                $k->where('email_address', 'like', '%' . trim($this->search) . '%')
                ->orwhere('title', 'like', '%' . trim($this->search) . '%')
                ->orwhere('chinese_name', 'like', '%' . trim($this->search) . '%')
                ->orwhere('nationality', 'like', '%' . trim($this->search) . '%')
                ->orwhere('study_country', 'like', '%' . trim($this->search) . '%')
                ->orwhere('contact_english_name', 'like', '%' . trim($this->search) . '%')
                ->orwhere('contact_chinese_name', 'like', '%' . trim($this->search) . '%')
                ->orwhere('contact_relationship', 'like', '%' . trim($this->search) . '%')
                ->orwhere('given_name', 'like', '%' . trim($this->search) . '%');
            });
        }
        if (isset($this->order_type) && !empty($this->order_type)) {
            $members = $members->orderBy($this->order_type,$this->order_by);
        }
        $members = $members->paginate($this->paginate);
        $this->countMember = ImportRoomDetail::where('import_data_info_id',$this->import_data_info_id)->count();
        $this->countries = Country::where('status','1')->orderBy('name','ASC')->get();
        return view('livewire.admin.quota-room.import-index',compact('members'));
    }

    
}
