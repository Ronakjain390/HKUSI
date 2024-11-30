<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\ExportDataInfo;
use App\Models\ImportMemberDetail;
use App\Models\Country;
use Auth;

class ExportManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false ,$searchSubmit=false , $status=null,$totalEnabled,$totalDisabled,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$countMember;

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
        $export = (new ExportDataInfo)->newQuery();
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date   = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $export = $export->whereBetween('created_at',[$form_date,$to_date]);
               
            }
        }

        if($this->daterange1 == true){
            if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Basic') {
                $export = $export->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 00:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $export = $export->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $a = date('w');
                $export = $export->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('- '.$a.' days')),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d',strtotime('- '.$a.' days'));
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                $export = $export->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')]);
               $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                    $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                    $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                    $export = $export->whereBetween('created_at',[$form_date,$to_date]);
                }
            }
           
        }

        if (isset($this->status) && $this->status != '') {
            $export = $export->where('status',$this->status);
        }
        if ($this->searchSubmit == true) {
            $export = $export->where(function ($k) {
                $k->where('type', 'like', '%' . trim($this->search) . '%');
            });
        }
        if (isset($this->order_type) && $this->order_type == 'name') {
            $export = $export->whereHas('getUserDetail', function ($o) {$o->orderBy($this->order_type,$this->order_by);});
        }else{
            $export = $export->orderBy($this->order_type,$this->order_by);
        }
        $export = $export->paginate($this->paginate);
        $this->totalEnabled = ExportDataInfo::where('status','1')->count();
        $this->totalDisabled = ExportDataInfo::where('status','0')->count();
        $this->countMember = ExportDataInfo::count();
        return view('livewire.admin.export.index',compact('export'));
    }


    public function destroy($id)
    {
        if (isset($id) && !empty($id)) {
            ExportDataInfo::find($id)->delete();
            // ImportMemberDetail::where('import_data_info_id',$id)->delete();
        }
        session()->flash('success', 'Export Data delete successfully.');
    }

    public function destroyAll()
    {
        // MemberInfo::query()->delete();
        // session()->flash('success', 'All member delete successfully.');
    }

    public function statusChange($id) 
    {   
        $status = ExportDataInfo::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $status['status']=='1' ? '0' : '1';
        ExportDataInfo::where('id', $id)->update($data);
        session()->flash('message', 'Export Data status updated successfully.');        
    }

}
