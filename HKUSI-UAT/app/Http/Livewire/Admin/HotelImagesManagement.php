<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\HotelSetting;
use Auth,DB;
use Maatwebsite\Excel\Facades\Excel;

class HotelImagesManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false ,$daterange1=false ,$searchSubmit=false , $status=null,$Completed,$Pending,$Accepted,$Paid,$Cancelled,$Updated,$Rejected,$countries=[],$collages=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$statusfind=null,$user_type_id,$clickRowId,$hideRow,$booking_type,$programme,$inFrom,$outFrom,$outTo,$inTo,$hotel_id; 

    public $createMode = false; 

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
       
        if (isset($this->statusfind) && $this->statusfind != '') {
            $users =  $hotelData->where('status',$this->statusfind);
        }
       
        if ($this->searchSubmit == true) {
            $hotelData = $hotelData->where('hotel_name', 'like', '%' . trim($this->search) . '%')
                                    ->orwhere('location', 'like', '%' . trim($this->search) . '%')
                                    ->orwhere('price_range', 'like', '%' . trim($this->search) . '%')
                                    ->orwhere('id', 'like', '%' . trim($this->search) . '%');           
        }
        
        if (isset($this->order_type) && $this->order_type != '') {
            $hotelData = $hotelData->orderBy($this->order_type,$this->order_by);
        }
            $hotelData = $hotelData->where('id',$this->hotel_id)->first();
       

        return view('livewire.admin.hotel-setting.hotel-image',compact('hotelData'));
    }


}
