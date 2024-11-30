<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Programme;
use App\Models\HallProgramme;
use App\Models\Country;
use Auth;

class QuotaHallProgrammeManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false ,$searchSubmit=false , $status=null,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='id',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$is_import,$totalEnabled,$totalDisabled,$member_info_id ,$startFrom ,$startTo,$endFrom ,$endTo,$qouta_hall_id; 

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
        $QoutaHallProgramme = (new HallProgramme)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $QoutaHallProgramme = $QoutaHallProgramme->whereHas('getProgrammeDetail',function($f){
                    $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                    $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                    $f->whereBetween('created_at',[$form_date,$to_date]);
                });
               
            }
        }

        if($this->daterange1 == true){

            if (isset($this->startFrom) && !empty($this->startFrom) && isset($this->startTo) && !empty($this->startTo)) {
                $QoutaHallProgramme = $QoutaHallProgramme->whereHas('getProgrammeDetail',function ($t) { $t->whereBetween('start_date',[strtotime($this->startFrom),strtotime($this->startTo)]);});
            }
            if (isset($this->endFrom) && !empty($this->endFrom) && isset($this->endTo) && !empty($this->endTo)) {
                $QoutaHallProgramme = $QoutaHallProgramme->whereHas('getProgrammeDetail',function ($t) { $t->whereBetween('end_date',[strtotime($this->endFrom),strtotime($this->endTo)]);});
            }
            if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Basic') {
                $QoutaHallProgramme = $QoutaHallProgramme->whereHas('getProgrammeDetail',function($v){
                    $v->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 00:59:59')]);
                });
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $QoutaHallProgramme = $QoutaHallProgramme->whereHas('getProgrammeDetail',function($v){
                    $v->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]);
                });
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $QoutaHallProgramme = $QoutaHallProgramme->whereHas('getProgrammeDetail',function($v){
                     $a = date('w');
                    $v->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('- '.$a.' days')),date('Y-m-d 23:59:59')]);
                });
                $this->from = date('Y-m-d',strtotime('- '.$a.' days'));
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                 $QoutaHallProgramme = $QoutaHallProgramme->whereHas('getProgrammeDetail',function($v){
                    $v->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')]);
                });
                $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                    $QoutaHallProgramme = $QoutaHallProgramme->whereHas('getProgrammeDetail',function($f){
                        $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                        $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                        $f->whereBetween('created_at',[$form_date,$to_date]);
                    });
                }
            }
           
        }
        $this->totalCompleted = '';
        $this->totalFailed =  '';
        if (isset($this->status) && $this->status != '') {
            $QoutaHallProgramme =  $QoutaHallProgramme->whereHas('getProgrammeDetail',function($s){
                $s->where('status',$this->status);
            });
        }
        if ($this->searchSubmit == true) {
            $QoutaHallProgramme = $QoutaHallProgramme->whereHas('getProgrammeDetail',function ($k) {
                $k->where('programme_code', 'like', '%' . trim($this->search) . '%')
                ->orwhere('programme_name', 'like', '%' . trim($this->search) . '%')
                ->orwhere('application_number', 'like', '%' . trim($this->search) . '%');
            });
        }
        $QoutaHallProgramme = $QoutaHallProgramme->where('qouta_hall_id',$this->qouta_hall_id)->paginate($this->paginate);
        $getprogrammeid = HallProgramme::where('qouta_hall_id',$this->qouta_hall_id)->pluck('programme_id')->toArray();
        $this->totalEnabled = Programme::whereIn('id',$getprogrammeid)->where('status','1')->count();
        $this->totalDisabled = Programme::whereIn('id',$getprogrammeid)->where('status','0')->count();
        $this->countMember = '';
        return view('livewire.admin.quota-hall.programme-index',compact('QoutaHallProgramme'));
    }

    public function stautsMemberProgrameChange($id) 
    {   
        $status = HallProgramme::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $status['status']=='1' ? '0' : '1';
        Programme::where('id', $status->programme_id)->update($data);
        session()->flash('message', 'Member Programme update successfully.');
    }

     public function destroy($id)
    {
        $QoutaHallProgramme =HallProgramme::where('qouta_hall_id',$this->qouta_hall_id)->where('programme_id',$id)->first();
        if(isset($QoutaHallProgramme) && !empty($QoutaHallProgramme)){
            $QoutaHallProgramme->delete();
        }
        session()->flash('success', 'Member programme delete successfully.');
    }

    public function statusChange($id) 
    {
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $status = HallProgramme::find($id);
        $programme = Programme::where('id', $status->programme_id)->first();       
        // dd($programme);
        $data['status'] = $programme['status']=='1' ? '0' : '1';
        $programme->update($data);
        session()->flash('message', 'Event Programme update successfully.');
    }
}
