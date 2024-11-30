<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\EventPayment;
use App\Models\MemberInfo;
use App\Models\User;
use App\Models\HallSetting;
use App\Models\Country;
use App\Jobs\SendEmailJob;
use Auth;
use App\Exports\PaymentDetailsExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class EventPaymentManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false , $searchSubmit=false , $status=null,$Completed,$Cancelled,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$servics,$method,$statusfind=null,$user_type_id,$paymnet_id,$pay_type,$hall_setting_id; 
    public $dataexport;
    public $processing , $expired , $paid , $pending , $uatpaid , $rejected , $cancelled ,$failed ,$refunded = 0;
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
        $exportdata = [];
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


        if (isset($this->status) && $this->status == '1') {
            $users =  $payment->where(function ($o) {
                $o->where('status','1');
            });
        }elseif(isset($this->status) && $this->status == '0'){
            $users =  $payment->where(function ($o) {
                $o->where('status','0');
            });
        }



        if (isset($this->is_import) && $this->is_import != '') {
            $users =  $payment->where('is_import',$this->is_import);
        }
        if (isset($this->statusfind) && $this->statusfind != '') {
            if ($this->statusfind == 'Processing') {
                $users =  $payment->where(function ($use) {
                $use->whereNotIn('payment_status',['EXPIRED','PAID','UATPAID','PENDING','REJECTED','CANCELLED','REFUNDED']);
            });
            }else{
                $users =  $payment->where('payment_status',$this->statusfind);
            }
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
        if (isset($this->paymnet_id) && !empty($this->paymnet_id)) {
            $exportdata = $payment->where('id',$this->paymnet_id)->get();
            $payment = $payment->where('id',$this->paymnet_id)->get();
        }else{
            $exportdata = $payment->get();
            $payment = $payment->paginate($this->paginate);
        }
        if (isset($this->paymnet_id) && !empty($this->paymnet_id)) {  
            $this->expired = EventPayment::where('id',$this->paymnet_id)->where('payment_status','EXPIRED')->count();
            $this->paid = EventPayment::where('id',$this->paymnet_id)->where('payment_status','PAID')->count();
            $this->uatpaid = EventPayment::where('id',$this->paymnet_id)->where('payment_status','UATPAID')->count();
            $this->pending = EventPayment::where('id',$this->paymnet_id)->where('payment_status','PENDING')->count();
            $this->rejected = EventPayment::where('id',$this->paymnet_id)->where('payment_status','REJECTED')->count();
            $this->cancelled = EventPayment::where('id',$this->paymnet_id)->where('payment_status','CANCELLED')->count();
            $this->refunded = EventPayment::where('id',$this->paymnet_id)->where('payment_status','REFUNDED')->count();
            $this->processing = EventPayment::where('id',$this->paymnet_id)->where('payment_status','PROCESSING')->count();
            $this->failed = EventPayment::where('id',$this->paymnet_id)->where('payment_status','FAILED')->count();
            $this->countMember = EventPayment::where('id',$this->paymnet_id)->count();

        }else{
            $this->expired = EventPayment::where('payment_status','EXPIRED')->count();
            $this->paid = EventPayment::where('payment_status','PAID')->count();
            $this->uatpaid = EventPayment::where('payment_status','UATPAID')->count();
            $this->pending = EventPayment::where('payment_status','PENDING',)->count();
            $this->rejected = EventPayment::where('payment_status','REJECTED')->count();
            $this->cancelled = EventPayment::where('payment_status','CANCELLED')->count();
            $this->refunded = EventPayment::where('payment_status','REFUNDED')->count();
            $this->processing = EventPayment::where('payment_status','PROCESSING')->count();
            $this->failed = EventPayment::where('payment_status','FAILED')->count();
            $this->countMember = EventPayment::count();
        }
        $paymentMethod = EventPayment::select('payment_method')->distinct()->orderBy('payment_method','ASC')->get();
        $paymentType = EventPayment::select('pay_type')->distinct()->orderBy('pay_type','ASC')->get();
        $this->countries = Country::where('status','1')->orderBy('name','ASC')->get();
        $this->dataexport = $exportdata;
        $Yeardata = HallSetting::where('status','1')->get();
        return view('livewire.admin.event-payment.index',compact('payment','paymentMethod','paymentType','Yeardata'));
    }


    public function destroy($id)
    {
        EventPayment::find($id)->delete();
        session()->flash('success', 'Member delete successfully.');
    }

    public function statusChange($id) 
    {   
        $status = EventPayment::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $status['status']=='1' ? '0' : '1';
        EventPayment::where('id', $id)->update($data);
        session()->flash('message', 'Member status updated successfully.');        
    }


    public function paymentExportData()
    {
        $data = $this->dataexport;
        if (count($data)>0) {
            return Excel::download(new PaymentDetailsExport($data), 'Eventpayment_'.date("d-m-Y-h-i-s").'.xlsx');
        }else{
            return redirect()->back()->with('payment','Data Not found');
        }
    }  


}
