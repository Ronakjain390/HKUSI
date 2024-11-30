<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\ImportPrivateEventOrderDetail;
use App\Models\User;
use App\Models\Country;
use Auth;

class ImportPrivateEventDetailsManagement extends Component
{
    use WithPagination;
    // Private Event Details Management By Akash
    public $search ,$from , $to ,$daterange=false , $daterange1=false ,$searchSubmit=false , $status, $countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countprivateEvents,$is_import,$totalCompleted,$totalFailed; 
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

    public function destroy($delete_id)
    {
        ImportPrivateEventOrderDetail::where('id', $delete_id)->delete();

        $this->resetPage();
        
    }

    public function render()
    {
        $privateEvents = (new ImportPrivateEventOrderDetail)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $privateEvents = $privateEvents->whereBetween('created_at',[$form_date,$to_date]);
                 
            }
        }
        if($this->daterange1 == true){
            
            
            if (isset($this->status)) {
                $privateEvents = $privateEvents->where('status',$this->status);
            }
           
        }
        if (isset($this->is_import) && $this->is_import != '') {
            $users =  $privateEvents->where('status',$this->is_import);
        }
       
        if (isset($this->import_data_info_id) && $this->import_data_info_id != '') {
            $privateEvents = $privateEvents->where('import_data_info_id',$this->import_data_info_id);
        $this->totalCompleted = ImportPrivateEventOrderDetail::where('import_data_info_id',$this->import_data_info_id)->where('status','1')->count();
        $this->totalFailed = ImportPrivateEventOrderDetail::where('import_data_info_id',$this->import_data_info_id)->where('status','0')->count();
        }
        
        if ($this->searchSubmit == true) {
            $privateEvents = $privateEvents->where(function ($k) {
                $k->where('booking_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('application_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('event_id', 'like', '%' . trim($this->search) . '%');
            });
        }
        if (isset($this->order_type) && !empty($this->order_type)) {
            $privateEvents = $privateEvents->orderBy($this->order_type,$this->order_by);
        }
        $privateEvents = $privateEvents->paginate($this->paginate);
        $this->countprivateEvents = ImportPrivateEventOrderDetail::where('import_data_info_id',$this->import_data_info_id)->count();
        return view('livewire.admin.private-event-order.import-index',compact('privateEvents'));
    }

}
