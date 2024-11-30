<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\MemberInfo;
use App\Models\User;
use App\Models\Country;
use Auth;

class TrashManagement extends Component
{
    use WithPagination;
    public $search ,$from , $to ,$daterange=false ,$searchSubmit=false , $status=null,$totalTrashed,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='14',$language, $delete ,$import_data_info_id,$countMember,$deleted_at; 


   

    public function render()
    {
        $trash = MemberInfo::onlyTrashed();
        if (isset($this->deleted_at) && $this->deleted_at != '') {
            $users =  $trash->whereHas('getUserDetail', function ($o) {$o->where('deleted_at',$this->deleted_at);});
        }
        $trash = $trash->paginate($this->paginate);
        $this->totalTrashed = User::Role('Member')->where('deleted_at','')->count();
        $this->countMember = MemberInfo::count();
        $this->countries = Country::where('status','1')->orderBy('name','ASC')->get();
        return view('livewire.admin.trash.index',compact('trash'));
    }

   
    public function destroy($id)
    {
        MemberInfo::onlyTrashed()->forceDelete();
        session()->flash('success', 'Member delete successfully.');
    }

    public function destroyAll()
    {
        // MemberInfo::query()->delete();
        // session()->flash('success', 'All member delete successfully.');
    }




}
