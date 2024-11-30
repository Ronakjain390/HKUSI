<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\ImportMemberDetail;
use App\Models\User;
use App\Models\HallBookingInfo;
use App\Models\QuotaHall;
use App\Models\Country;
use App\Models\MemberInfo;
use App\Jobs\SendEmailJob;
use Auth,DB;

class QuotasHallManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false ,$searchSubmit=false , $status=null,$countries=[],$nationality,$gender,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$is_import,$totalCompleted,$totalFailed,$quotas_id; 

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
        $quotas = (new QuotaHall)->newQuery();
        
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

        if (isset($this->is_import) && $this->is_import == 0) {
            $quotas =  $quotas->where('status','0');
        }elseif(isset($this->is_import) && $this->is_import == 1){
            $quotas =  $quotas->where('status','1');
        }

        if (isset($this->language) && $this->language != '') {
            $quotas = $quotas->where('language',$this->language);
        }
        if (isset($this->type_id) && $this->type_id != '') {
                $this->totalCompleted = QuotaHall::where($this->field_name,$this->type_id)->where('status','1')->count();
                $this->totalFailed = QuotaHall::where($this->field_name,$this->type_id)->where('status','0')->count();
        }
        if ($this->searchSubmit == true) {
            $quotas = $quotas->where(function ($k) {
                $k->where('total_quotas', 'like', '%' . trim($this->search) . '%')
                ->orwhere('male', 'like', '%' . trim($this->search) . '%')
                ->orwhere('room_type', 'like', '%' . trim($this->search) . '%')
                ->orwhere('address', 'like', '%' . trim($this->search) . '%')
                ->orwhere('female', 'like', '%' . trim($this->search) . '%')
                ->orwhere('college_name', 'like', '%' . trim($this->search) . '%');
            });
        }
        if (isset($this->order_type) && !empty($this->order_type)) {
            $quotas = $quotas->orderBy($this->order_type,$this->order_by);
        }
        $quotas = $quotas->where($this->field_name,$this->type_id)->paginate($this->paginate);
        $this->countMember = ImportMemberDetail::count();
        return view('livewire.admin.quota-hall.index',compact('quotas'));
    }

    public function statusChangeHallQuota($id) 
    {   
        $status = QuotaHall::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $status['status']=='1' ? '0' : '1';
        if ($data['status']=="1") {
            $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$status->quota_id)->limit($status->male)->whereNull('quota_hall_id')->get();
            if (isset($totalGenderMaleBooking) && count($totalGenderMaleBooking)) {
                foreach ($totalGenderMaleBooking as $key => $valueData) {
                    $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                    $accetptstauts = [];
                    $accetptstauts['status']        = "Updated";
                    $accetptstauts['quota_hall_id'] = $status->id;
                    $mailInfo = [
                        'given_name'            => $datauser->given_name,
                        'application_number'    => $datauser->application_number,                        
                        'accommodation'         => $valueData->getHallSettingDetail,                        
                        'quotahall'             => $status,                        
                        'booking'               => $valueData,                    
                        'memberinfo'            => $datauser,                        
                    ];
                    $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];

                    SendEmailJob::dispatch($details);
                    HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                }
            }    
            $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$status->quota_id)->limit($status->female)->whereNull('quota_hall_id')->get();
            if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                foreach ($totalGenderFemaleBooking as $key => $valueData) {
                    $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                    $accetptstauts = [];
                    $accetptstauts['status']        = "Updated";
                    $accetptstauts['quota_hall_id'] = $status->id;
                    $mailInfo = [
                        'given_name'            => $datauser->given_name,
                        'application_number'    => $datauser->application_number,                        
                        'accommodation'         => $valueData->getHallSettingDetail,                        
                        'quotahall'             => $status,                        
                        'booking'             => $valueData,                        
                        'memberinfo'            => $datauser,                        
                    ];
                    $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                    SendEmailJob::dispatch($details);
                    dd($details);
                    HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                }
            }    
        }
        QuotaHall::where('id', $id)->update($data);
        session()->flash('message', 'Hall QuotaHall status updated successfully.');        
    }

    public function destroy($id)
    {
        if (isset($id) && !empty($id)) {
            QuotaHall::find($id)->delete();
        }
        session()->flash('success', 'QuotaHall delete successfully.');
    }

}
