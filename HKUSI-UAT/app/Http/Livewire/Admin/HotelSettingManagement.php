<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\HotelSetting;
use App\Models\HallSetting;
use Auth,DB;
use App\Exports\HallBookingExport;
use Maatwebsite\Excel\Facades\Excel;

class HotelSettingManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false ,$daterange1=false ,$searchSubmit=false , $status=null,$Completed,$Pending,$Accepted,$Paid,$Cancelled,$Updated,$Rejected,$countries=[],$collages=[],$hall_setting_id,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$statusfind=null,$user_type_id,$clickRowId,$hideRow,$booking_type,$programme,$inFrom,$outFrom,$outTo,$inTo,$Yeardata=[]; 

    public $createMode = false; 
    public $hallbookingExport;

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
        $hotelData = (new HotelSetting)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $hotelData = $hotelData->whereBetween('created_at',[$form_date,$to_date]);
                
            }
        }

        if($this->daterange1 == true){
            
            if (isset($this->hall_setting_id) && !empty($this->hall_setting_id)) {
                $hotelData = $hotelData->where('hall_setting_id',$this->hall_setting_id);
            }
        }

        if (isset($this->statusfind) && $this->statusfind != '') {
            $users =  $hotelData->where('status',$this->statusfind);
        }
       

        if ($this->searchSubmit == true) {
            $hotelData = $hotelData->where(function ($k) {
                $k->where('hotel_name', 'like', '%' . trim($this->search) . '%')
                  ->orwhere('location', 'like', '%' . trim($this->search) . '%')
                  ->orwhere('id', 'like', '%' . trim($this->search) . '%');
            });
        }
        
        if (isset($this->order_type) && $this->order_type != '') {
            $hotelData = $hotelData->orderBy($this->order_type,$this->order_by);
        }
            $hotelData = $hotelData->paginate($this->paginate);
        
       
        $this->enabled = HotelSetting::where('status','Enabled')->count();
        $this->disabled = HotelSetting::where('status','Disabled')->count();
        $this->Yeardata = HallSetting::where('status','1')->get();
        $this->countMember = HotelSetting::count();

        return view('livewire.admin.hotel-setting.index',compact('hotelData'));
    }


    public function destroy($id)
    {
        HotelSetting::find($id)->delete();
        session()->flash('success', 'Hotel delete successfully.');
    }

    public function statusChange($id , $status) 
    {   
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        HotelSetting::where('id', $id)->update(['status' => $status]);
        session()->flash('message', 'Hotel status updated successfully.'); 
    }

}
