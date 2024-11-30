<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use App\Models\Country;
use App\Models\MemberInfo;
use App\Models\MemberProgramme;
use App\Models\Programme;
use App\Models\QuotaProgramme;
use App\Models\HallBookingInfo;
use App\Models\HallBookingGroup;
use App\Models\User;
use App\Jobs\SendEmailJob;
use Auth,Storage,Config,DB,DateTime;

class AddHallBookingManagement extends Component
{
	use WithFileUploads;
	public $member,$memberPrograme,$programe,$checkprograme,$getalldata,$start_date,$end_date,$booking,$memberInfo;
    public function mount(){
        $this->memberInfo = MemberInfo::where('status','1')->orderBy('given_name','ASC')->get();
    }
    public function render(){
    	$data = [];

    	if (isset($this->member) && !empty($this->member)) {
    		$member = MemberInfo::where('id',$this->member)->first();

            $this->booking = false;
            $age = 0;
            if (isset($member->date_of_birth) && !empty($member->date_of_birth)) {
                $dob = date('Y-m-d',$member->date_of_birth);
                if(!empty($dob)){
                    $birthdate = new DateTime($dob);
                    $today   = new DateTime('today');
                    $age = $birthdate->diff($today)->y;
                }
                if($age >= 15) {
                    $this->booking = true;
                } else {
                    $this->booking = false;
                    session()->flash('ageError', 'This member is younger than 15 years.');
                    return view('livewire.admin.hallbooking.create',compact('data'));
                }
            } else {
                 session()->flash('ageError', 'This member age not define.');
                return view('livewire.admin.hallbooking.create',compact('data'));
            }

            $checkQuota = QuotaProgramme::select('programme_id')->whereIn('programme_id',$member->getMemberProgrammeDetail)->get();
            if (isset($checkQuota) && count($checkQuota)) {
                $existProgrammes = [];
                $bookUserProgramme = HallBookingInfo::select('programme_code')->where('user_type_id',$member->id)->whereNotIn('status',['Cancelled','Rejected'])->get();
                $bookUserRroupProgramme = HallBookingGroup::select('programme_code')->where('user_type_id',$member->id)->whereNotIn('status',['Cancelled','Rejected'])->get();
                if (isset($bookUserRroupProgramme) && count($bookUserRroupProgramme)) {
                    foreach ($bookUserRroupProgramme as $key => $groupProgramme) {
                        $existProgrammes[] = $groupProgramme->programme_code;
                    }
                }
                if (isset($bookUserProgramme) && count($bookUserProgramme)) {
                    foreach ($bookUserProgramme as $key => $programme) {
                        $existProgrammes[] = $programme->programme_code;
                    }
                }
                $data = Programme::select('id','application_number','programme_code','programme_name','start_date','end_date')->whereIn('id',$checkQuota);
                if(count($existProgrammes)){
                    $data = $data->whereNotIn('programme_code',$existProgrammes);
                }
                $data = $data->where('status',1)->orderBy("id", "DESC")->get();
                // $this->checkprograme = $data;
            }
    	}
    	 
    	if (isset($this->checkprograme) && !empty($this->checkprograme)) {
           $programme = Programme::select('id','application_number','programme_code','programme_name','start_date','end_date')->where('id',$this->checkprograme)->where('status',1)->orderBy("id", "DESC")->first();
            if (!empty($programme)) {
                // foreach($data as $key=>$value){
                $startDate = $endDate = '';
                if (isset($programme->start_date) && !empty($programme->start_date)) {
                    $start_date =  $programme->start_date - 86400;
                    $startDate = date('Y-m-d',$start_date);
                }
                if (isset($programme->end_date) && !empty($programme->end_date)) {
                    $end_date =  $programme->end_date + 86400;
                    $endDate = date('Y-m-d',$end_date);
                }
                $this->start_date = $startDate;
                //dd($data[$key]->start_date);
                $this->end_date = $endDate;
                // }
                $this->getalldata = $data;
            }
    	}
        return view('livewire.admin.hallbooking.create',compact('data'));
    }


    public function save(Request $request){
    	$inputs = $this->validate([
            'member'            => 'required',
            'checkprograme'     => 'required',
            'start_date'        => 'required',
            'end_date'          => 'required',
        ],[
            'checkprograme.required' => 'Programme field is required.',
        ]); 

        $userMemberInfos = MemberInfo::where('id',$this->member)->first();
    	$userInfo = User::where('id',$this->member)->first();
        $checkProgram = Programme::where('id',$this->checkprograme)->first();
            if(!$checkProgram->checkMemberProgramme($this->member)){
                return session()->flash('error', 'This programme is not allocated to you.');
            }
            if (isset($userMemberInfos) && !empty($userMemberInfos->date_of_birth)) {

                $dob = date('Y-m-d',$userMemberInfos->date_of_birth);
                $programDate = $checkProgram->start_date - 86400;

                $BirthDay   = date('d',$userMemberInfos->date_of_birth);
                $BirthMonth = date('m',$userMemberInfos->date_of_birth);
                $BirthYear  = date('Y',$userMemberInfos->date_of_birth);

                //convert the users DoB into UNIX timestamp
                $stampBirth = mktime(0, 0, 0, $BirthMonth, $BirthDay, $BirthYear);

                // fetch the current date (minus 18 years)
                $today['day']   = date('d',$programDate);
                $today['month'] = date('m',$programDate);
                $today['year']  = date('Y',$programDate) - 15;

                // generate todays timestamp
                $stampToday = mktime(0, 0, 0, $today['month'], $today['day'], $today['year']);

                if ($stampBirth < $stampToday) {
                } else {
                    return session()->flash('error', 'age over.');
                }
            }else{
                return session()->flash('error', 'age over.');
            }
            if (isset($checkProgram->getQuotaProgrammeDetail->getQuotaDetail) && !empty($checkProgram->getQuotaProgrammeDetail->getQuotaDetail)){
                if (empty($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail)){
                    return session()->flash('error', 'Hall booking is over');
                }

                if(HallBookingInfo::where('user_type_id',$this->member)->whereNotIn('status',['Cancelled','Rejected'])->where('programme_code',$checkProgram->programme_code)->where('hall_setting_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id)->exists()){
                     return session()->flash('error', 'You have already booked this programme.');
                }

                if (HallBookingInfo::rightJoin('hall_booking_groups', 'hall_booking_groups.hall_booking_info_id' , '=' , 'hall_booking_infos.id')->where('hall_booking_infos.user_type_id',$this->member)->where('hall_booking_infos.hall_setting_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id)->where('hall_booking_infos.hall_setting_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id)->where('hall_booking_groups.user_type_id',$this->member)->where('hall_booking_groups.programme_code',$checkProgram->programme_code)->whereNotIn('hall_booking_infos.status',['Cancelled','Rejected'])->exists()) {
                     return session()->flash('error', 'You have already booked this programme.');
                }

                // \DB::enableQueryLog();
                // HallBookingInfo::rightJoin('hall_booking_groups', 'hall_booking_groups.hall_booking_info_id' , '=' , 'hall_booking_infos.id')->where('hall_booking_infos.user_type_id',$this->member)->where('hall_booking_infos.hall_setting_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id)->where('hall_booking_infos.hall_setting_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id)->where('hall_booking_groups.user_type_id',$this->member)->where('hall_booking_groups.programme_code',$checkProgram->programme_code)->whereNotIn('hall_booking_infos.status',['Cancelled','Rejected'])->exists();
                // dd(\DB::getQueryLog(),$checkProgram->programme_code);

                if($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->application_deadline<time()){
					return session()->flash('error', 'Application deadline is over');
                }
                $userCountry = 'No';
                // dd($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getQuotaCountry);
                if(isset($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getQuotaCountry) && count($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getQuotaCountry)){
                    foreach ($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getQuotaCountry as $key => $value) {
                        if(Country::where('id',$value->country_id)->where('name',$userMemberInfos->getStudyCountry->name)->exists()){
                            $userCountry = 'Yes';
                        }
                    }
                }

                if($userCountry == 'No'){
                	return session()->flash('error', 'Country not matched');
                }
                $totalHallBooking = HallBookingInfo::whereIn('status',['Completed','Pending','Accepted','Paid','Updated','Unpaid'])->where('programme_code',$checkProgram->programme_code)->where('hall_setting_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id)->count();
               // dd(date('Y-m-d',$checkProgram->start_date),date('Y-m-d',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->start_date),date('Y-m-d',$checkProgram->end_date),date('Y-m-d',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->end_date));
                if($checkProgram->start_date!=$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->start_date || $checkProgram->end_date!=$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->end_date){

                    return session()->flash('error', 'Quota period not matched.');
                }
                $balance = $t = 0;
                $balance = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->female_max_quota + $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->male_max_quota;

                if(isset($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallBookinInfos) && count($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallBookinInfos)){
                    foreach($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallBookinInfos as $bookingInfos){
                        if($bookingInfos->status != 'Cancelled' && $bookingInfos->status != 'Rejected'){
                            $t++;
                        }
                    }
                }
                $QuotaBalance = $balance - $t;
                if($QuotaBalance<=0){
                    return session()->flash('error', 'Max quota limit is over.');
                }

                $type = strtolower($userMemberInfos->gender).'_max_quota';
                $totalGenderQuote = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->$type;

                $totalGenderBooking = HallBookingInfo::select('hall_booking_infos.id')->leftJoin('member_infos', function ($join) {
                    $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                })->whereNull('member_infos.deleted_at')->where('member_infos.gender',$userMemberInfos->gender)->whereIn('hall_booking_infos.status',['Completed','Pending','Accepted','Paid','Updated','Unpaid'])->where('quota_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->id)->where('quota_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->id)->count();

                if($totalGenderBooking>=$totalGenderQuote || $totalGenderQuote<=0){
                    return session()->flash('error', 'Gender quota limit is over.');
                }
                
                $date1 = $checkProgram->start_date - 172800;
                $date2 = $checkProgram->end_date + 86400;
                $days = (int)(($date2 - $date1)/86400);
                $amount = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->unit_price*($days-1);
                $lastBooking = HallBookingInfo::select('booking_number')->whereNotNull('booking_number')->orderBy('booking_number','DESC')->first();
                if (isset($lastBooking->id) && !empty($lastBooking->id)) {
                    $bookingNumber = $lastBooking->id + 1;
                }else{
                    $bookingNumber = 1;
                }
                $data                       = new HallBookingInfo();
                $data['booking_number']     = $bookingNumber;
                $data['user_type_id']       = $this->member;
                $data['hall_setting_id']    = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id;
                $data['quota_id']           = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->id;
                $data['user_type']          = "Member";
                $data['programme_code']     = $checkProgram->programme_code;
                $data['start_date']         = (isset($checkProgram->start_date) && !empty($checkProgram->start_date))?$checkProgram->start_date:null;
                $data['end_date']           = (isset($checkProgram->end_date) && !empty($checkProgram->end_date))?$checkProgram->end_date:null;
                $data['check_in_date']      = (isset($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_in_date) && !empty($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_in_date))?$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_in_date:null;
                $data['check_out_date']     = (isset($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_out_date) && !empty($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_out_date))?$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_out_date:null;
                $data['check_in_time']      = $checkProgram->start_date;                
                $data['check_out_time']     = $checkProgram->end_date;                
                $data['amount']             = $amount;                
                $data['status']             = 'Pending';                
                $data['application_id']     = $userMemberInfos->application_number;
                $data->save();

                if(!empty($data->id)){
                    $new_booking_id = $data->id;
                }else{
                    $new_booking_id = 1;
                }
                $bookingNumber = str_pad($new_booking_id, 7, "0", STR_PAD_LEFT);
                // $bookingNumber = "HKUSI".str_pad($new_booking_id, 7, "0", STR_PAD_LEFT);
                $data->update(['booking_number'=>$bookingNumber]);

                // $detail = [];
                // $detail['amount']           = $amount;
                // $detail['booking_number']   = $bookingNumber;
                // $detail['college_name']     = 'Test Collage';
                $textResponse = 'From '.date("Y-m-d", $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_in_date).' to '.date("Y-m-d", $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_out_date).' ('. $days - 1 .' Nights).';
                // $detail['days_text']        =  $textResponse;
                $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->updateBookingQuota('minus');
                
                if($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->status){
                    if ($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->status=='1') {
                        $type = strtolower($userMemberInfos->gender);                    
                        $totalGenderQuotaReleased = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->$type;
						$totalGenderBookingReleased = HallBookingInfo::select('hall_booking_infos.id')->leftJoin('member_infos', function ($join) {
                                $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                            })->whereNull('member_infos.deleted_at')->where('member_infos.gender',$userMemberInfos->gender)->whereIn('hall_booking_infos.status',['Accepted','Paid','Updated'])->where('hall_setting_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id)->count();
						
						if($totalGenderBookingReleased >= $totalGenderQuotaReleased || $totalGenderQuotaReleased <= 0){
                            $mailInfo = [
                                'given_name'         => $userMemberInfos->given_name,
                                'application_number' => $userMemberInfos->application_number,
                                'hall_result_days' => (isset($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->hall_result_days) && $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->hall_result_days != '')? $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->hall_result_days :null,
                            ];
                            $details = ['type'=>'HallReservation','email' => $userMemberInfos->email_address,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($details);
                            return redirect()->route('admin.hallbooking.index')->with('success','Your request has been successfully submitted.');
                        } else {
                            $data->update(['status' => 'Accepted']);
                            $data->update(['payment_deadline_date'=>time()]);
                            return redirect()->route('admin.hallbooking.index')->with('success',$textResponse);
                        }
                    } else {
                        $mailInfo = [
                            'given_name'         => $userMemberInfos->given_name,
                            'application_number' => $userMemberInfos->application_number,
                            'hall_result_days' => (isset($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->hall_result_days) && $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->hall_result_days != '')? $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->hall_result_days :null,
                        ];
                        $details = ['type'=>'HallReservation','email' => $userMemberInfos->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                        return redirect()->route('admin.hallbooking.index')->with('success','Your request has been successfully submitted.');
                    }
                }else{
                    $mailInfo = [
                        'given_name'         => $userMemberInfos->given_name,
                        'application_number' => $userMemberInfos->application_number,
                        'hall_result_days' => (isset($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->hall_result_days) && $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->hall_result_days != '')? $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->hall_result_days :null,
                    ];
                    $details = ['type'=>'HallReservation','email' => $userMemberInfos->email_address,'mailInfo' => $mailInfo];
                    SendEmailJob::dispatch($details);
                    return redirect()->route('admin.hallbooking.index')->with('success','Your request has been successfully submitted.');
                }
            }else{
            	return session()->flash('error', 'Quota not found.'); 
            }
    }

    public function refers(){
        $this->checkprograme = '';
        $this->start_date = '';
        $this->end_date = '';
    }

}