<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\ImportMemberDetail;
use App\Models\User;
use App\Models\Quota;
use App\Models\Country;
use App\Models\HallBookingInfo;
use App\Models\MemberInfo;
use App\Models\QuotaCountry;
use Auth;
use App\Jobs\SendEmailJob;

class QoutasCountryManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false ,$searchSubmit=false , $status=null,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='id',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$is_import,$totalEnabled,$totalDisabled,$quota_id; 

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

        $quotas = (new QuotaCountry)->newQuery();
        $quotaid = $this->quota_id;
        $quotaIds =[];
        if (isset($quotaid->getQuotaDetail) && count($quotaid->getQuotaDetail)) {
            foreach ($quotaid->getQuotaDetail as $key => $quotaInfo) {
                $quotaIds[] = $quotaInfo->id;
            }
            if (isset($quotaIds) && count($quotaIds)) {
                $getCountrys = $quotas->whereIn('quota_id',$quotaIds)->pluck('country_id')->toArray();
                if (isset($getCountrys) && count($getCountrys)) {
                    $countrys = Country::whereIn('id',$getCountrys);
                }
            }
        }
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $countrys = $countrys->whereBetween('created_at',[$form_date,$to_date]);
                
            }
        }

        if($this->daterange1 == true){
            if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Basic') {
                $countrys = $countrys->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 00:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $countrys = $countrys->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $a = date('w');
                $countrys = $countrys->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('- '.$a.' days')),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d',strtotime('- '.$a.' days'));
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                $countrys = $countrys->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')]);
                 $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                    $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                    $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                    $countrys = $countrys->whereBetween('created_at',[$form_date,$to_date]);
                }
            }
            
        }
         if (isset($this->status) && $this->status != '') {
            $countrys =  $countrys->where('status',$this->status);
        }
        $this->totalEnabled = Country::where('status','1')->whereIn('id',$getCountrys)->count();
        $this->totalDisabled = Country::where('status','0')->whereIn('id',$getCountrys)->count();
        
        if ($this->searchSubmit == true) {
            $countrys = $countrys->where(function ($k) {
                $k->where('name', 'like', '%' . trim($this->search) . '%');
            });
        }
        $countrys = $countrys->paginate($this->paginate);
        $this->countMember = Quota::count();
        $this->countries = Country::where('status','1')->orderBy('name','ASC')->get();
        // dd($this->quota_id);
        return view('livewire.admin.quota-country.index',compact('countrys'));
    }
    public function destroy($id)
    {
        QuotaCountry::where('country_id',$id)->delete();
        session()->flash('success', 'Quota country delete successfully.');
    }

    public function statusChange($id) 
    {   
        $status = Country::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $status['status']=='1' ? '0' : '1';
        Country::where('id', $id)->update($data);
        session()->flash('message', 'Country status updated successfully.');        
    }

}
