<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\Programme;
use App\Models\HallSetting;
use Auth;

class ProgrammeManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false ,$searchSubmit=false , $status=null,$totalEnabled,$totalDisabled,$programe_codedata=[],$nationality,$gender,$programe_code,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$countMember ,$startFrom ,$startTo,$endFrom ,$endTo,$hall_setting_id; 
    public $years = [];

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
        $programme = (new Programme)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $programme = $programme->whereBetween('created_at',[$form_date,$to_date]);
               
            }
        }
        if($this->daterange1 == true){
             if((isset($this->hall_setting_id) && !empty($this->hall_setting_id))){
                $programme = $programme->join('programme_hall_settings', 'programme_hall_settings.programme_id', '=', 'programmes.id')->join('hall_settings', 'hall_settings.id', '=', 'programme_hall_settings.hall_setting_id')->Where('hall_settings.id', 'like', '%' . $this->hall_setting_id . '%');
            }
            if (isset($this->startTo) && !empty($this->startTo) && isset($this->startFrom) && !empty($this->startFrom)) {
                $programme = $programme->whereBetween('start_date',[strtotime($this->startFrom),strtotime($this->startTo)]);
            }
            if (isset($this->endTo) && !empty($this->endTo) && isset($this->endFrom) && !empty($this->endFrom)) {
                $programme = $programme->whereBetween('end_date',[strtotime($this->endFrom),strtotime($this->endTo)]);
            }
            if (isset($this->nationality) && !empty($this->nationality)) {
                $programme = $programme->where('nationality',$this->nationality);
            }
            if (isset($this->gender) && !empty($this->gender)) {
                $programme = $programme->where('gender',$this->gender);
            }
            if (isset($this->programe_code) && !empty($this->programe_code)) {
                $programme = $programme->where('programme_code',$this->programe_code);
            }
            if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Basic') {
                $programme = $programme->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 00:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $programme = $programme->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $a = date('w');
                $programme = $programme->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('- '.$a.' days')),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d',strtotime('- '.$a.' days'));
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                $programme = $programme->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')]);
                $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->start_date) && !empty($this->start_date) && isset($this->end_date) && !empty($this->end_date)) {
                    $form_start_date  = date('Y-m-d',strtotime($this->start_date)).' 00:00:00';
                    $to_end_date    = date('Y-m-d',strtotime($this->end_date)).' 23:59:59';
                    $programme = $programme->whereBetween('created_at',[$form_start_date,$to_end_date]);
                }
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
            $programme = $programme->where('programme_code', 'like', '%' . trim($this->search) . '%')
            ->orwhere('programme_name', 'like', '%' . trim($this->search) . '%');              
        }
        if((isset($this->hall_setting_id) && !empty($this->hall_setting_id))) {
            $programme = $programme->select('programmes.*')->groupBy('programmes.id');
        }
        if (isset($this->order_type) && $this->order_type == 'email') {
            $programme = $programme->whereHas('getUserDetail', function ($o) {$o->orderBy($this->order_type,$this->order_by);});
        }else{
            $programme = $programme->orderBy($this->order_type,$this->order_by);
        }
        $programme = $programme->paginate($this->paginate);
        $this->totalEnabled =  Programme::select('id','status')->where('status','1')->count();
        $this->totalDisabled = Programme::select('id','status')->where('status','0')->count();
        $this->programe_codedata = Programme::orderBy('id','ASC')->get();
        $this->countMember = Programme::count();
        $this->years = HallSetting::where('status','1')->get();
        return view('livewire.admin.programme.index',compact('programme'));
    }


    public function destroy($id)
    {
        Programme::find($id)->delete();
        session()->flash('success', 'Member delete successfully.');
    }

    public function destroyAll()
    {
        // MemberInfo::query()->delete();
        // session()->flash('success', 'All member delete successfully.');
    }

    public function statusChange($id) 
    {   
        $status = Programme::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $status['status']=='1' ? '0' : '1';
        Programme::where('id', $id)->update($data);
        session()->flash('message', 'Member status updated successfully.');        
    }

}
