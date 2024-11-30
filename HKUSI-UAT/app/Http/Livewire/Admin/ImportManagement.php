<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\ImportDataInfo;
use App\Models\ImportMemberDetail;
use App\Models\Country;
use Auth;

class ImportManagement extends Component
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
        $import = (new ImportDataInfo)->newQuery();
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $import = $import->whereBetween('created_at',[$form_date,$to_date]);
                
            }
        }

        if($this->daterange1 == true){
            if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Basic') {
                $import = $import->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 00:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $import = $import->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]);
                 $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $a = date('w');
                $import = $import->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('- '.$a.' days')),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d',strtotime('- '.$a.' days'));
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                $import = $import->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')]);
                $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                    $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                    $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                    $import = $import->whereBetween('created_at',[$form_date,$to_date]);
                }
            }
            
            
        }

        if (isset($this->status) && $this->status != '') {
            $import = $import->where('status',$this->status);
        }
        if ($this->searchSubmit == true) {
            $import = $import->where(function ($k) {
                $k->where('type', 'like', '%' . trim($this->search) . '%');
            });
        }
        if (isset($this->order_type) && $this->order_type == 'name') {
            $import = $import->whereHas('getUserDetail', function ($o) {$o->orderBy($this->order_type,$this->order_by);});
        }else{
            $import = $import->orderBy($this->order_type,$this->order_by);
        }
        $import = $import->paginate($this->paginate);
        $this->totalEnabled = ImportDataInfo::where('status','1')->count();
        $this->totalDisabled = ImportDataInfo::where('status','0')->count();
        $this->countMember = ImportDataInfo::count();
        $this->countries = Country::where('status','1')->orderBy('name','ASC')->get();
        return view('livewire.admin.import.index',compact('import'));
    }


    public function destroy($id)
    {
        if (isset($id) && !empty($id)) {
            ImportDataInfo::find($id)->delete();
            ImportMemberDetail::where('import_data_info_id',$id)->delete();
        }
        session()->flash('success', 'Import Data delete successfully.');
    }

    public function destroyAll()
    {
        // MemberInfo::query()->delete();
        // session()->flash('success', 'All member delete successfully.');
    }

    public function statusChange($id) 
    {   
        $status = ImportDataInfo::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $status['status']=='1' ? '0' : '1';
        ImportDataInfo::where('id', $id)->update($data);
        session()->flash('message', 'Import Data status updated successfully.');        
    }

}
