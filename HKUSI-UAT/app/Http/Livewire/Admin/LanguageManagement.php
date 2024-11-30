<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\Language;
use Auth;

class LanguageManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false ,$searchSubmit=false , $status=null,$totalEnabled,$totalDisabled,$programe_codedata=[],$nationality,$gender,$programe_code,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20', $delete ,$countMember ,$startFrom ,$startTo,$endFrom ,$endTo; 

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
        $language = (new Language)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $language = $language->whereBetween('created_at',[$form_date,$to_date]);
               
            }
        }
        if($this->daterange1 == true){

            if (isset($this->startTo) && !empty($this->startTo) && isset($this->startFrom) && !empty($this->startFrom)) {
                $language = $language->whereBetween('start_date',[strtotime($this->startFrom),strtotime($this->startTo)]);
            }
            if (isset($this->endTo) && !empty($this->endTo) && isset($this->endFrom) && !empty($this->endFrom)) {
                $language = $language->whereBetween('end_date',[strtotime($this->endFrom),strtotime($this->endTo)]);
            }
            if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Basic') {
                $language = $language->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 00:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $language = $language->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $a = date('w');
                $language = $language->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('- '.$a.' days')),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d',strtotime('- '.$a.' days'));
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                $language = $language->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')]);
                $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->start_date) && !empty($this->start_date) && isset($this->end_date) && !empty($this->end_date)) {
                    $form_start_date  = date('Y-m-d',strtotime($this->start_date)).' 00:00:00';
                    $to_end_date    = date('Y-m-d',strtotime($this->end_date)).' 23:59:59';
                    $language = $language->whereBetween('created_at',[$form_start_date,$to_end_date]);
                }
            }
          
        }

        if (isset($this->status) && $this->status == 0) {
            $users =  $language->where(function ($o) {
                $o->where('status','0');
            });
        }elseif(isset($this->status) && $this->status == 1){
            $users =  $language->where(function ($o) {
                $o->where('status','1');
            });
        }


        if (isset($this->status) && $this->status != '') {
            $users =  $language->where('status',$this->status);
        }        
        if ($this->searchSubmit == true) {
            $language = $language->where('name', 'like', '%' . trim($this->search) . '%');              
        }       
        $language = $language->orderBy($this->order_type,$this->order_by);
        $language = $language->paginate($this->paginate);
        $this->totalEnabled =  Language::select('id','status')->where('status','1')->count();
        $this->totalDisabled = Language::select('id','status')->where('status','0')->count();
        $this->programe_codedata = Language::orderBy('id','ASC')->get();
        $this->countMember = Language::count();
        return view('livewire.admin.language.index',compact('language'));
    }


    public function destroy($id)
    {
        Language::find($id)->delete();
        session()->flash('success', 'Member delete successfully.');
    }

    public function destroyAll()
    {
        // MemberInfo::query()->delete();
        // session()->flash('success', 'All member delete successfully.');
    }

    public function statusChange($id) 
    {   
        $status = Language::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $status['status']=='1' ? '0' : '1';
        Language::where('id', $id)->update($data);
        session()->flash('message', 'Member status updated successfully.');        
    }

}
