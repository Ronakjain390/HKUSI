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
use Auth;
use App\Jobs\SendEmailJob;

class HallQuotasDetailsManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false ,$searchSubmit=false , $status=null,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$is_import,$totalCompleted,$totalFailed,$hall_setting_id; 

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
        $quotas = (new Quota)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $quotas = $quotas->whereBetween('created_at',[$form_date,$to_date]);
                
            }
        }

        if($this->daterange1 == true){
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
        $this->totalCompleted = Quota::where('status','1')->where('hall_setting_id',$this->hall_setting_id)->count();
        $this->totalFailed = Quota::where('status','0')->where('hall_setting_id',$this->hall_setting_id)->count();
        
        if ($this->searchSubmit == true) {
            $quotas = $quotas->where(function ($k) {
                $k->where('total_quotas', 'like', '%' . trim($this->search) . '%')
                ->orwhere('quota_balance', 'like', '%' . trim($this->search) . '%')
                ->orwhere('male', 'like', '%' . trim($this->search) . '%')
                ->orwhere('female', 'like', '%' . trim($this->search) . '%')
                ->orwhere('male_max_quota', 'like', '%' . trim($this->search) . '%')
                ->orwhere('female_max_quota', 'like', '%' . trim($this->search) . '%');
            });
        }
        if (isset($this->order_type) && !empty($this->order_type)) {
            $quotas = $quotas->orderBy($this->order_type,$this->order_by);
        }
        $quotas = $quotas->where('hall_setting_id',$this->hall_setting_id)->paginate($this->paginate);
        $this->countMember = Quota::where('hall_setting_id',$this->hall_setting_id)->count();
        $this->countries = Country::where('status','1')->orderBy('name','ASC')->get();
        return view('livewire.admin.quota.index',compact('quotas'));
    }

    public function statusChangeHallQuota($id) 
    {           
        $status = Quota::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $status['status']=='1' ? '0' : '1';
        $data['release_date'] = time();
            if ($data['status']=="1") {
                $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                            })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->where('hall_booking_infos.status','Pending')->where('quota_id',$status->id)->orderBy('hall_booking_infos.id','ASC')->limit($status->male)->get();
                //dd($totalGenderMaleBooking->count(),$totalGenderFemaleBooking->count());
                if (isset($totalGenderMaleBooking) && count($totalGenderMaleBooking)) {
                    foreach ($totalGenderMaleBooking as $key => $valueData) {
                        $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                        $accetptstauts['status'] = "Accepted";
                        $accetptstauts['payment_deadline_date'] = time();
                        $mailInfo = [
                            'given_name'         => $datauser->given_name,
                            'application_number' => $datauser->application_number,                        
                            'hall_payment_days'  => (isset($status->getHallSettingDetail->hall_payment_days) && !empty($status->getHallSettingDetail->hall_payment_days))?$status->getHallSettingDetail->hall_payment_days:null,                        
                        ];
                        $details = ['type'=>'HallReservationConfirm','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                        HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                    }
                }    
                $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                            })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->where('hall_booking_infos.status','Pending')->where('quota_id',$status->id)->orderBy('hall_booking_infos.id','ASC')->limit($status->female)->get();
                if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                    foreach ($totalGenderFemaleBooking as $key => $valueData) {
                        $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                        $accetptstauts = [];
                        $accetptstauts['status'] = "Accepted";
                        $accetptstauts['payment_deadline_date'] = time();
                        $mailInfo = [
                            'given_name'         => $datauser->given_name,
                            'application_number' => $datauser->application_number,                        
                            'hall_payment_days'  => (isset($status->getHallSettingDetail->hall_payment_days) && !empty($status->getHallSettingDetail->hall_payment_days))?$status->getHallSettingDetail->hall_payment_days:null,                      
                        ];
                        $details = ['type'=>'HallReservationConfirm','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                        HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                    }
                }       
            }    
        Quota::where('id', $id)->update($data);
        session()->flash('message', 'Hall Quota status updated successfully.');        
    }

    public function destroy($id)
    {
        Quota::find($id)->delete();
        session()->flash('success', 'Quota delete successfully.');
    }

}
