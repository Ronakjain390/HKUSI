<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\HallSetting;
use App\Models\User;
use App\Jobs\SendEmailJob;
use App\Models\Country;
use Auth;

class HallManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false ,$searchSubmit=false , $status=null,$totalEnabled,$totalDisabled,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$hallsup,$halls, $unit_price, $year; 

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
        $members = (new HallSetting)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $members = $members->whereBetween('created_at',[$form_date,$to_date]);
            }
           
        }

        if($this->daterange1 == true){
            if (isset($this->year) && !empty($this->year)) {
                $members = $members->where('year',$this->year);
            }
            if (isset($this->unit_price) && !empty($this->unit_price)) {
                $members = $members->where('unit_price',$this->unit_price);
            }
            if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Basic') {
                $members = $members->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 00:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $members = $members->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]);
                 $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $a = date('w');
                $members = $members->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('- '.$a.' days')),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d',strtotime('- '.$a.' days'));
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                $members = $members->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')]);
                $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->start_date) && !empty($this->start_date) && isset($this->end_date) && !empty($this->end_date)) {
                    $form_start_date  = date('Y-m-d',strtotime($this->start_date)).' 00:00:00';
                    $to_end_date    = date('Y-m-d',strtotime($this->end_date)).' 23:59:59';
                    $members = $members->whereBetween('created_at',[$form_start_date,$to_end_date]);
                }
            }
           
        }


        if (isset($this->status) && $this->status == 0) {
            $users =  $members->where(function ($o) {
                $o->where('status','0');
            });
        }elseif(isset($this->status) && $this->status == 1){
            $users =  $members->where(function ($o) {
                $o->where('status','1');
            });
        }



        if (isset($this->status) && $this->status != '') {
            $users =  $members->where('status',$this->status);
        }
        if (isset($this->language) && $this->language != '') {
            $members = $members->where('language',$this->language);
        }
        if (isset($this->import_data_info_id) && $this->import_data_info_id != '') {
            $members = $members->where('import_data_info_id',$this->import_data_info_id);
        }
        
        if ($this->searchSubmit == true) {
            $members = $members->where(function ($k) {
                $k->where('year', 'like', '%' . trim($this->search) . '%')
                ->orwhere('unit_price', '%' . trim($this->search) . '%');
            });
        }
        if (isset($this->order_type) && !empty($this->order_type) && $this->order_type == 'total_quotas') {
            $members = $members->whereHas('getQuotaDetail' ,function ($s) {
                $s->orderBy($this->order_type ,$this->order_by);
            });
        }elseif(isset($this->order_type) && !empty($this->order_type) && $this->order_type == 'quota_balance'){
            $members = $members->whereHas('getQuotaDetail' ,function ($s) {
                $s->orderBy($this->order_type ,$this->order_by);
            });
            
        }elseif(isset($this->order_type) && !empty($this->order_type) && $this->order_type == 'male'){
            $members = $members->whereHas('getQuotaDetail' ,function ($s) {
                $s->orderBy($this->order_type ,$this->order_by);
            });
        }elseif(isset($this->order_type) && !empty($this->order_type) && $this->order_type == 'female'){
            $members = $members->whereHas('getQuotaDetail' ,function ($s) {
                $s->orderBy($this->order_type ,$this->order_by);
            });
        }else{
            $members = $members->orderBy($this->order_type,$this->order_by);
        }
        $members = $members->paginate($this->paginate);
        $this->totalEnabled = HallSetting::where('status','1')->count();
        $this->totalDisabled = HallSetting::where('status','0')->count();
        $this->countMember = HallSetting::count();
        $this->halls = HallSetting::where('status','1')->orderBy('year','ASC')->get();
        $this->hallsup = HallSetting::where('status','1')->orderBy('unit_price','ASC')->get();
        return view('livewire.admin.hall.index',compact('members'));
    }


    public function destroy($id)
    {
        HallSetting::find($id)->delete();
        session()->flash('success', 'Member delete successfully.');
    }

    public function destroyAll()
    {
        // HallSetting::query()->delete();
        // session()->flash('success', 'All member delete successfully.');
    }

    public function statusChange($id) 
    {   
        $status = HallSetting::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $status['status']=='1' ? '0' : '1';
        HallSetting::where('id', $id)->update($data);
        session()->flash('message', 'Hall Setting status updated successfully.');        
    }

    public function userstatusChange($id) 
    {   
        $userstatus = User::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $userstatus['status']=='1' ? '0' : '1';
        User::where('id', $id)->update($data);
        session()->flash('message', 'User status updated successfully.');        
    }

}
