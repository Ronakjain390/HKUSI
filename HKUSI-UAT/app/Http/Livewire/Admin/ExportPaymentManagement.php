<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\ExportPaymentInfo;
use App\Models\MemberInfo;
use App\Models\User;
use App\Models\Country;
use App\Jobs\SendEmailJob;
use Auth;
use App\Exports\PaymentDetailsExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ExportPaymentManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false , $daterange1=false , $searchSubmit=false , $status=null,$Completed,$Cancelled,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$servics,$method,$export_data_info_id,$statusfind=null,$user_type_id,$booking_number,$pay_type; 
    public $dataexport;
    public $processing , $expired , $paid , $pending , $uatpaid , $rejected , $cancelled , $refunded = 0;
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
        $payment = (new ExportPaymentInfo)->newQuery();
        

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
                $users =  $payment->whereNotIn('payment_status',['EXPIRED','PAID','UATPAID','PENDING','REJECTED','CANCELLED','REFUNDED'])->orwhereNull('payment_status');
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
                ->orwhere('service_type', 'like', '%' . trim($this->search) . '%')
                ->orwhere('payment_method', 'like', '%' . trim($this->search) . '%');
            });
        }
        
        
        if (isset($this->order_type) && $this->order_type == 'email') {
            $payment = $payment->whereHas('getUserDetail', function ($o) {$o->orderBy($this->order_type,$this->order_by);});
        }else{
            $payment = $payment->orderBy($this->order_type,$this->order_by);
        }
        if (isset($this->booking_number) && !empty($this->booking_number)) {
            $exportdata = $payment->where('payment_id',$this->booking_number)->get();
            $payment = $payment->where('payment_id',$this->booking_number)->paginate($this->paginate);
        }else{
            $exportdata = $payment->get();
            $payment = $payment->where('export_data_info_id',$this->export_data_info_id)->paginate($this->paginate);
        }
        if (isset($this->booking_number) && !empty($this->booking_number)) {
            $this->processing = ExportPaymentInfo::where('payment_id',$this->booking_number)->where('export_data_info_id',$this->export_data_info_id)->whereNotIn('payment_status',['EXPIRED','PAID','UATPAID','PENDING','REJECTED','CANCELLED','REFUNDED'])->orwhereNull('payment_status')->count();
            $this->expired = ExportPaymentInfo::where('payment_id',$this->booking_number)->where('export_data_info_id',$this->export_data_info_id)->where('payment_status','EXPIRED')->count();
            $this->paid = ExportPaymentInfo::where('payment_id',$this->booking_number)->where('export_data_info_id',$this->export_data_info_id)->where('payment_status','PAID')->count();
            $this->uatpaid = ExportPaymentInfo::where('payment_id',$this->booking_number)->where('export_data_info_id',$this->export_data_info_id)->where('payment_status','UATPAID')->count();
            $this->pending = ExportPaymentInfo::where('payment_id',$this->booking_number)->where('export_data_info_id',$this->export_data_info_id)->where('payment_status','PENDING')->count();
            $this->rejected = ExportPaymentInfo::where('payment_id',$this->booking_number)->where('export_data_info_id',$this->export_data_info_id)->where('payment_status','REJECTED')->count();
            $this->cancelled = ExportPaymentInfo::where('payment_id',$this->booking_number)->where('export_data_info_id',$this->export_data_info_id)->where('payment_status','CANCELLED')->count();
            $this->refunded = ExportPaymentInfo::where('payment_id',$this->booking_number)->where('export_data_info_id',$this->export_data_info_id)->where('payment_status','REFUNDED')->count();
            $this->countMember = ExportPaymentInfo::where('payment_id',$this->booking_number)->where('export_data_info_id',$this->export_data_info_id)->where('payment_id',$this->booking_number)->count();
        }else{
            
            $this->processing = ExportPaymentInfo::whereNotIn('payment_status',['EXPIRED','PAID','UATPAID','PENDING','REJECTED','CANCELLED','REFUNDED'])->orwhereNull('payment_status')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->expired = ExportPaymentInfo::where('payment_status','EXPIRED')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->paid = ExportPaymentInfo::where('payment_status','PAID')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->uatpaid = ExportPaymentInfo::where('payment_status','UATPAID')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->pending = ExportPaymentInfo::where('payment_status','PENDING')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->rejected = ExportPaymentInfo::where('payment_status','REJECTED')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->cancelled = ExportPaymentInfo::where('payment_status','CANCELLED')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->refunded = ExportPaymentInfo::where('payment_status','REFUNDED')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->countMember = ExportPaymentInfo::where('export_data_info_id',$this->export_data_info_id)->count();
        }
        $paymentMethod = ExportPaymentInfo::select('payment_method')->where('export_data_info_id',$this->export_data_info_id)->distinct()->orderBy('payment_method','ASC')->get();
        $paymentType = ExportPaymentInfo::select('pay_type')->where('export_data_info_id',$this->export_data_info_id)->distinct()->orderBy('pay_type','ASC')->get();
        $this->dataexport = $exportdata;

        // dd($this->processing);

        return view('livewire.admin.payment.index',compact('payment','paymentMethod','paymentType'));
    }

}
