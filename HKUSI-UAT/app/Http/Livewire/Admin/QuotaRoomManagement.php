<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\ImportMemberDetail;
use App\Models\User;
use App\Models\Quota;
use App\Models\QuotaRoom;
use App\Models\Country;
use Auth;

class QuotaRoomManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false ,$searchSubmit=false , $status=null,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$is_import,$totalCompleted,$totalFailed,$quota_hall_id; 

    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    public $type_id , $field_name;
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
        $quotas = (new QuotaRoom)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $quotas = $quotas->whereBetween('created_at',[$form_date,$to_date]);
                
            }
        }


        if($this->daterange1 == true){
            if (isset($this->gender) && !empty($this->gender)) {
                $quotas = $quotas->where('gender',$this->gender);
            }
            if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Basic') {
                $quotas = $quotas->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 00:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $quotas = $quotas->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $a = date('w');
                $quotas = $quotas->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('- '.$a.' days')),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d',strtotime('- '.$a.' days'));
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                $quotas = $quotas->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')]);
                $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->start_date) && !empty($this->start_date) && isset($this->end_date) && !empty($this->end_date)) {
                    $form_date  = date('Y-m-d',strtotime($this->start_date)).' 00:00:00';
                    $to_date    = date('Y-m-d',strtotime($this->end_date)).' 23:59:59';
                    $quotas = $quotas->whereBetween('created_at',[$form_date,$to_date]);
                }
            }
            
        }
        if (isset($this->is_import) && $this->is_import != '') {
            $users =  $quotas->where('status',$this->is_import);
        }
        if (isset($this->language) && $this->language != '') {
            $quotas = $quotas->where('language',$this->language);
        }
        $this->totalCompleted = QuotaRoom::where('status','1')->where($this->field_name,$this->type_id)->count();
        $this->totalFailed = QuotaRoom::where('status','0')->where($this->field_name,$this->type_id)->count();
        
        if ($this->searchSubmit == true) {
            $quotas = $quotas->where(function ($k) {
                $k->where('room_code', 'like', '%' . trim($this->search) . '%')
                ->orwhere('college_name', 'like', '%' . trim($this->search) . '%')
                ->orwhere('gender', 'like', '%' . trim($this->search) . '%');
            });
        }
        if (isset($this->order_type) && !empty($this->order_type)) {
            $quotas = $quotas->orderBy($this->order_type,$this->order_by);
        }
        $quotas = $quotas->where($this->field_name,$this->type_id)->paginate($this->paginate);
        $this->countMember = QuotaRoom::where($this->field_name,$this->type_id)->count();
        $this->countries = Country::where('status','1')->orderBy('name','ASC')->get();
        return view('livewire.admin.quota-room.index',compact('quotas'));
    }

    public function statusChangeHallQuota($id) 
    {   
        $status = QuotaRoom::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $status['status']=='1' ? '0' : '1';
        QuotaRoom::where('id', $id)->update($data);
        session()->flash('message', 'Quota Room status updated successfully.');        
    }
    public function destroy($id)
    {
        if (isset($id) && !empty($id)) {
            QuotaRoom::find($id)->delete();
        }
        session()->flash('success', 'QuotaRoom delete successfully.');
    }

}
