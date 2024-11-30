<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\HallBookingInfo;
use App\Models\EventBooking;
use App\Models\EventSetting;
use App\Models\HallSetting;
use App\Models\MemberInfo;
use App\Models\User;
use App\Models\Country;
use App\Models\EventPayment;
use App\Jobs\SendEmailJob;
use Auth,DB;
use App\Exports\EventBookingExport;
use Maatwebsite\Excel\Facades\Excel;

class EventBookingManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false ,$daterange1=false ,$searchSubmit=false , $status=null,$Completed,$Pending,$Accepted,$Paid,$Cancelled,$Updated,$Rejected,$countries=[],$collages=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$statusfind=null,$user_type_id,$clickRowId,$hideRow,$booking_type,$collage,$member_id,$hall_setting_id; 

    public $createMode = false; 
    public $eventbookingExport;

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

        $payment = (new EventPayment)->newQuery();      
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $payment = $payment->whereBetween('created_at',[$form_date,$to_date]);
               
            }
        }

        if($this->daterange1 == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $payment = $payment->whereBetween('created_at',[$form_date,$to_date]);
            }
            if (isset($this->servics) && !empty($this->servics)) {
                $payment = $payment->where('service_type',$this->servics);
            }
            if (isset($this->method) && !empty($this->method)) {
                $payment = $payment->where('payment_method',$this->method);
            }
            if (isset($this->pay_type) && !empty($this->pay_type)) {
                $payment = $payment->where('pay_type',$this->pay_type);
            }
            if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Basic') {
                $payment = $payment->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 00:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $payment = $payment->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $a = date('w');
                $payment = $payment->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('- '.$a.' days')),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d',strtotime('- '.$a.' days'));
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                $payment = $payment->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')]);
              $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                    $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                    $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                    $payment = $payment->whereBetween('created_at',[$form_date,$to_date]);
                }
            }
           
        }

        if (isset($this->is_import) && $this->is_import != '') {
            $users =  $payment->where('is_import',$this->is_import);
        }
        if (isset($this->statusfind) && $this->statusfind != '') {
            $users = $payment->where(function($v){                    
                $v->where('event_payment_status',$this->statusfind);
            });
        }
        if (isset($this->language) && $this->language != '') {
            $payment = $payment->where('language',$this->language);
        }

        if ($this->searchSubmit == true) {
            $payment = $payment->where(function ($k) {
                $k->where('transaction_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('application_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('payment_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('card_no', 'like', '%' . trim($this->search) . '%')
                ->orwhere('order_no', 'like', '%' . trim($this->search) . '%')
                ->orwhere('service_type', 'like', '%' .trim($this->search) . '%')
                ->orwhere('payment_method', 'like', '%' . trim($this->search) . '%');
            });
        }
        if (isset($this->hall_setting_id) && !empty($this->hall_setting_id)) {
            $payment = $payment->whereHas('getyearSilgle',function($querys){
                $querys->whereHas('getEventSetting',function($sts){
                    $sts->where('hall_setting_id',$this->hall_setting_id);
                });
            });
        }
        
        
        if (isset($this->order_type) && $this->order_type == 'email') {
            $payment = $payment->whereHas('getUserDetail', function ($o) {$o->orderBy($this->order_type,$this->order_by);});
        }else{
            $payment = $payment->orderBy($this->order_type,$this->order_by);
        }

        if (isset($this->member_id) && !empty($this->member_id)) {
            $memberInfo = MemberInfo::where('id',$this->member_id)->first();
            $getbookings = EventBooking::select('payment_id')->pluck('payment_id')->toArray();
            $exportdata = EventBooking::where('application_id',$memberInfo->application_number)->get();
            $payment = $payment->whereIn('payment_id',$getbookings)->where('application_id',$memberInfo->application_number)->paginate($this->paginate);
        }else{
            $getbookings = EventBooking::select('payment_id')->pluck('payment_id')->toArray();
            $exportdata = EventBooking::get();
            $payment = $payment->whereIn('payment_id',$getbookings)->where('service_type','Event Booking')->paginate($this->paginate);
        }
        $getbookings = EventBooking::select('payment_id')->pluck('payment_id')->toArray();
        if (isset($this->member_id) && !empty($this->member_id)) {
            $memberInfo = MemberInfo::where('id',$this->member_id)->first();
            $this->Cancelled =EventPayment::where('application_id',$memberInfo->application_number)->whereIn('payment_id',$getbookings)->where('event_payment_status','Cancelled')->count();
            $this->Paid =EventPayment::where('application_id',$memberInfo->application_number)->whereIn('payment_id',$getbookings)->where('event_payment_status','Paid')->count();
            $this->Pending =EventPayment::where('application_id',$memberInfo->application_number)->whereIn('payment_id',$getbookings)->whereIn('event_payment_status',['Pending',''])->count();
            $this->countMember =EventPayment::where('application_id',$memberInfo->application_number)->count();
        }else{
            $this->Cancelled =EventPayment::whereIn('payment_id',$getbookings)->where('event_payment_status','Cancelled')->count();
            $this->Paid =EventPayment::whereIn('payment_id',$getbookings)->where('event_payment_status','Paid')->count();
            $this->Pending =EventPayment::whereIn('payment_id',$getbookings)->whereIn('event_payment_status',['Pending',''])->count();
            $this->countMember =EventPayment::whereIn('payment_id',$getbookings)->count();

        }
        $this->eventbookingExport = $exportdata;
        $Yeardata = HallSetting::where('status','1')->get();
        $paymentMethod = EventPayment::select('payment_method')->distinct()->orderBy('payment_method','ASC')->get();
        $paymentType = EventPayment::select('pay_type')->distinct()->orderBy('pay_type','ASC')->get();
        $this->countries = Country::where('status','1')->orderBy('name','ASC')->get();
        return view('livewire.admin.eventbooking.index',compact('payment','exportdata','Yeardata'));
    }


    public function destroy($id)
    {
        $eventbookings =  EventPayment::where('id',$id)->first();
            if(isset($eventbookings->getEventBookingDetails) && count($eventbookings->getEventBookingDetails)){
                foreach ($eventbookings->getEventBookingDetails as $keyEvent => $valueEvent) {
                    if($valueEvent->booking_status=='Paid'){
                        $eventBooking = EventBooking::where('id',$valueEvent->id)->first();
                        if(!empty($eventBooking)){
                            $eventData = EventSetting::find($eventBooking->event_id);
                            if(!empty($eventData)){
                                $eventData->increment('quota_balance',$eventBooking->no_of_seats);
                                $eventBooking->delete();
                            }
                        }
                    }else{
                        $eventBooking = EventBooking::where('id',$valueEvent->id)->delete();
                    }
                }
            }
       session()->flash('success', 'Event Booking Info deleted successfull.');  
    }

    public function statusChange($id) 
    {        
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $paymentdata = EventPayment::where('id',$id)->where('service_type','Event Booking')->first();
        if(!empty($paymentdata)){
            $memberinfo = MemberInfo::where('application_number',$paymentdata->application_id)->first();
            if (!empty($memberinfo)) {
                if($this->status=="PAID"){
                    if(isset($paymentdata->getEventBookingDetails) && count($paymentdata->getEventBookingDetails)){
                        foreach ($paymentdata->getEventBookingDetails as $keyEvent => $valueEvent) {
                            $mailInfo = [
                                'given_name'            => $memberinfo->given_name,
                                'application_number'    => $memberinfo->application_number,
                                'event_details'         => $valueEvent->getEventSetting,
                            ];
                            $paymentsuccess = ['type'=>'EventPaymentSuccessfull','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($paymentsuccess);
                        }
                    }
                }elseif($this->status=="CANCELLED"){
                      if(isset($paymentdata->getEventBookingDetails) && count($paymentdata->getEventBookingDetails)){
                        foreach ($paymentdata->getEventBookingDetails as $keyEvent => $valueEvent) {
                            $mailInfo = [
                                'given_name'            => $memberinfo->given_name,
                                'application_number'    => $memberinfo->application_number,
                                'event_name'            => $valueEvent->getEventSetting->event_name,
                                'date'                  => $valueEvent->getEventSetting->date,
                            ];
                            $eventcancelled = ['type'=>'EventCancelled','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($eventcancelled);

                            $eventData = EventSetting::find($valueEvent->event_id);
                            if(!empty($eventData)){
                                $eventData->increment('quota_balance',$valueEvent->no_of_seats);
                            }
                        }
                    }
                }
            }
            if(isset($paymentdata->getEventBookingDetails) && count($paymentdata->getEventBookingDetails)){
                foreach ($paymentdata->getEventBookingDetails as $keyEvent => $valueEvent) {                     
                    $eventBooking = EventBooking::find($valueEvent->id);
                    if(!empty($eventBooking)){
                        $eventBooking->update(['booking_status'=>ucfirst(strtolower($this->status))]);
                    }
                }
            }
            $data =[];
            $data['payment_status'] = $this->status;           
            EventPayment::where('id', $paymentdata->id)->update($data);
        }
        session()->flash('message', 'Event booking status updated successfully.');        
    }

    public function showRecord($id){
        if (!empty($this->hideRow) && $this->hideRow == $id) {
            $this->clickRowId = '';
            $this->hideRow = '';
        }else{
            $this->clickRowId = $id;
            $this->hideRow = $id;
        }
    }

    public function eventBookingExport()
    {
        $data = $this->eventbookingExport;
        if (count($data) >0) {
            return Excel::download(new EventBookingExport($data), 'EventBooking_'.date("d-m-Y-h-i-s").'.xlsx');
        }else{
            return redirect()->back()->with('eventbooking','Data Not found');
        }
        
    }
}
