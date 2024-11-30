<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\MemberInfo;
use App\Models\ImportMemberDetail;
use App\Models\User;
use App\Models\Country;
use App\Models\HallSetting;
use Auth;
use App\Jobs\SendEmailJob;

class MemberManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false ,$searchSubmit=false , $tabSearch=4 , $status=null, $trash=false,$totalEnabled,$totalTrash,$totalDisabled,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$countMember,$hall_setting_id;

    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    public $years = [];

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
        $members = (new MemberInfo)->newQuery();

        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $members = $members->whereBetween('created_at',[$form_date,$to_date]);
            }
        }

        if($this->daterange1 == true){
                if((isset($this->hall_setting_id) && !empty($this->hall_setting_id))){
                $members = $members->join('member_hall_settings', 'member_hall_settings.member_info_id', '=', 'member_infos.id')->join('hall_settings', 'hall_settings.id', '=', 'member_hall_settings.hall_setting_id')->Where('hall_settings.id', 'like', '%' . $this->hall_setting_id . '%');
            }
            if (isset($this->nationality) && !empty($this->nationality)) {
                $members = $members->where('nationality',$this->nationality);
            }
            if (isset($this->gender) && !empty($this->gender)) {
                $members = $members->where('gender',$this->gender);
            }
            if (isset($this->study_country) && !empty($this->study_country)) {
                $members = $members->where('study_country',$this->study_country);
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

            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month'){
                $members = $members->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')]);
                $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                    $form_start_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                    $to_end_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                    $members = $members->whereBetween('created_at',[$form_start_date,$to_end_date]);
                }
            }
        }
        if (isset($this->tabSearch) && $this->tabSearch == 1) {
            $users =  $members->whereHas('getUserDetail', function ($o) {
                $o->where('status','0');
            });
        }elseif(isset($this->tabSearch) && $this->tabSearch == 2){
            $users =  $members->whereHas('getUserDetail', function ($o) {
                $o->where('status','1');
            });
        }elseif(isset($this->tabSearch) && $this->tabSearch == 3){
            $members = $members->onlyTrashed();
        }

        if (isset($this->is_import) && $this->is_import != '') {
            $users =  $members->where('is_import',$this->is_import);
        }
        if (isset($this->language) && $this->language != '') {
            $members = $members->where('language',$this->language);
        }
       if (isset($this->status) && $this->status != '') {
            $users =  $members->whereHas('getUserDetail',function($s){
                $s->where('status',$this->status);
            });
        }

        if ($this->searchSubmit == true) {
            $date = date('Y-m-d h:i:s' , strtotime($this->search));
            $members = $members->whereHas('getUserDetail', function ($k) {
                $k->where('application_number', 'like', '%' . $this->search . '%')
                ->orwhere('email', 'like', '%' . trim($this->search) . '%')
                ->orwhere('title', 'like', '%' . trim($this->search) . '%')
                ->orwhere('gender', 'like', '%' . trim($this->search) . '%')
                ->orwhere('surname', 'like', '%' . trim($this->search) . '%')
                ->orwhere('given_name', 'like', '%' . trim($this->search) . '%')
                ->orwhere('mobile_tel_no', 'like', '%' . trim($this->search) . '%')
                ->orwhere('chinese_name', 'like', '%' . trim($this->search) . '%')
                ->orwhere('nationality', 'like', '%' . trim($this->search) . '%')
                ->orwhere('contact_tel_no', 'like', '%' . trim($this->search) . '%')
                ->orwhere('study_country', 'like', '%' . trim($this->search) . '%');
            });

        }
        if((isset($this->hall_setting_id) && !empty($this->hall_setting_id))) {
            $members = $members->select('member_infos.*')->groupBy('member_infos.id');
        }
        if (isset($this->order_type) && $this->order_type == 'email') {
            $members = $members->whereHas('getUserDetail', function ($o) {$o->orderBy($this->order_type,$this->order_by);});
        }else{
            $members = $members->orderBy($this->order_type,$this->order_by);
        }

        $members = $members->paginate($this->paginate);
        $this->totalEnabled = User::Role('Member')->where('status','1')->count();
        $this->totalDisabled = User::Role('Member')->where('status','0')->count();
        $this->totalTrash = MemberInfo::onlyTrashed()->count();
        $this->countMember = MemberInfo::count();
        $this->countries = Country::where('status','1')->orderBy('name','ASC')->get();
        $this->years = HallSetting::where('status','1')->get();
        return view('livewire.admin.members.index',compact('members'));
    }


    public function destroy($id)
    {
        $memberInfo = MemberInfo::withTrashed()->find($id);
        $userInfo = User::withTrashed()->find($memberInfo->user_id);
        if ($memberInfo->deleted_at != '') {
            $memberInfo->update(['deleted_at'=>null]);
            $userInfo->update(['deleted_at'=>null]);
        }else{
            $memberInfo->delete();
            $userInfo->delete();
        }
        session()->flash('success', 'Member delete successfully.');
    }

    public function forceDelete($id)
    {

        $memberInfo = MemberInfo::withTrashed()->find($id);
        $userInfo = User::withTrashed()->find($memberInfo->user_id);
        $memberInfo->forceDelete();
        $userInfo->forceDelete();
        session()->flash('success', 'Force delete successfully.');
    }

    public function statusChange($id)
    {
        $status = MemberInfo::find($id);

        if (empty($id)) {
            return $this->InvalidUrl();
        }

        $data['status'] = $status['status']=='1' ? '0' : '1';
        if ($data['status'] =='1') {
            $mailInfo = [
                        'given_name'     => $status->given_name,
                        'application_number' => $status->application_number,
                    ];
            $welcome = ['type'=>'AccountActivation','email' =>$status->getUserDetail->email,'mailInfo' => $mailInfo];
            SendEmailJob::dispatchNow($welcome);
        }
        MemberInfo::where('id', $id)->update($data);
        session()->flash('message', 'Member status updated successfully.');
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
