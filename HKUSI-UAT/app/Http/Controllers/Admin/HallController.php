<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\QuotaHall;
use App\Models\Quota;
use App\Models\HallSetting;
use App\Models\QuotaCountry;
use App\Models\Country;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailJob;
use App\Traits\UploadTraits;
use Auth;

class HallController extends Controller
{
    use UploadTraits;
    function __construct()
    {
        $this->middleware('permission:hall-list|hall-create|hall-edit|hall-delete', ['only' => ['index','store']]);
        $this->middleware('permission:hall-create', ['only' => ['create','store']]);
        $this->middleware('permission:hall-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:hall-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Accommodation Setting";
        return view('admin.hall.index',compact('headerTitle'));
    }

    public function create(Request $request)
    {
        $headerTitle = "Settings (New Accommodation)";
        return view('admin.hall.create',compact('headerTitle'));
    }
    
    public function show($id){
        $headerTitle = "Year Profile";
        $HallInfo = HallSetting::find($id);
        return view('admin.hall.show',compact('headerTitle','HallInfo'));
    }

    public function store(Request $request){
        $input = $request->all();
        $addhall                            = new HallSetting();
        $addhall['start_date']              =  strtotime($input['start_date']);
        $addhall['end_date']                =  strtotime($input['end_date']);
        $addhall['year']                    =  $input['year'];
        $addhall['application_deadline']    =  strtotime($input['application_deadline'].'23:59:59');
        $addhall['unit_price']              =  $input['unit_price'];
        $addhall['hall_result_days']        =  $input['hall_result_days'];
        $addhall['hall_payment_days']       =  $input['hall_payment_days'];
        $addhall['status']                  =  1;
        $addhall->save();
        return redirect()->route('admin.accommondation-setting.index')->with('success','Accommodation create successfully.');
    }

    public function edit(Request $request , $id){
        $headerTitle = "Year Profile";
        $HallInfo = HallSetting::find($id);
        return view('admin.hall.edit',compact('headerTitle','HallInfo'));
    }

    public function update(Request $request, $id){
        $input                          = $request->all();
        $data                           =  [];
        $data['start_date']             =  strtotime($input['start_date']);
        $data['end_date']               =  strtotime($input['end_date']);
        $data['application_deadline']   =  strtotime($input['application_deadline'].'23:59:59');
        $data['year']                   =  $input['year'];
        $data['unit_price']             =  $input['unit_price'];
        $data['hall_result_days']       =  $input['hall_result_days'];
        $data['hall_payment_days']      =  $input['hall_payment_days'];
        $data['status']                 =  $input['status'];
        HallSetting::where('id',$id)->update($data);        
        return redirect()->route('admin.accommondation-setting.index')->with('success','Accommodation update successfully.');
    }

    public function account(Request $request , $id){
        $headerTitle = "Hall (Settings)";
        $HallInfo = HallSetting::find($id);
        return view('admin.hall.account',compact('headerTitle','HallInfo'));
    }


    public function settingtatusChange(Request $request, $id, $status) {  
        $member = HallSetting::select('id','user_id')->where('id',$id)->first();      
        if (isset($member) && !empty($member)) {
            $member->update(['status' => $request->status]);
        }
        return redirect()->route('admin.hallDetails',[$id,'show'])->with('success', 'HallInfo status updated successfully!');        
    }


   public function hallDetails(Request $request , $id ,$type){
        $dataId = $id;
        $dataType = $type;
        $qoutaid = '';
        $dataidget = '';
         $quotaIds = $countrys = [];
        $HallInfo = HallSetting::find($id);
		if (isset($HallInfo) && !empty($HallInfo)) {
                if ($dataType == 'details') {
                    $headerTitle = "Accommodation Setting ";
                }elseif($dataType == 'edit'){
                    $headerTitle = "Accommodation Setting ";
                }elseif ($dataType == 'quotas') {
                    $headerTitle = "Accommodation Setting ";
                }elseif($dataType == 'halls'){
                    $headerTitle = "Accommodation Setting ";
                }elseif($dataType == 'rooms'){
                    $headerTitle = "Accommodation Setting ";
                }elseif($dataType == 'country'){
                    $headerTitle = "Accommodation Setting ";
                    if (!empty($dataId)) {
                       $dataidget = $HallInfo->getQuotaDetail[0]->id;
                    }                   
                    // if (isset($HallInfo->getQuotaDetail) && count($HallInfo->getQuotaDetail)) {
                    //     foreach ($HallInfo->getQuotaDetail as $key => $quotaInfo) {
                    //         $quotaIds[] = $quotaInfo->id;
                    //     }
                    //     if (isset($quotaIds) && count($quotaIds)) {
                    //         $getCountrys = QuotaCountry::whereIn('quota_id',$quotaIds)->pluck('country_id')->toArray();
                    //         if (isset($getCountrys) && count($getCountrys)) {
                    //             $countrys = Country::whereIn('id',$getCountrys)->get();
                    //         }
                    //     }
                    // }                    
                }else{
                    return redirect()->route('admin.accommondation-setting.index');
                }
            return view('admin.hall.comman',compact('headerTitle','HallInfo','dataId','dataType','countrys','quotaIds','dataidget'));
        }else{
             return redirect()->route('admin.accommondation-setting.index');
        }
   }

    public function destroy($id)
    {
        HallSetting::find($id)->delete();
        return redirect()->route('admin.accommondation-setting.index')->with('success', 'Member deleted successfully');
    }

    public function multipleHallDelete(Request $request)
	{
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $member) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    HallSetting::where('id', $member)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'disable'){
                    HallSetting::where('id',$member)->update(['status'=>'0']);
                }else{
                    HallSetting::where('id', $member)->update(['status'=>'1']);
                }
            }
        }
		return redirect()->back();
	}

    public function hallselectstatuschange(Request $request, $id) {  
        $user = HallSetting::where('id',$id)->first();
            if (isset($user) && !empty($user)) {
                $user->update(['status' => $request->status]);
        }
        return redirect()->route('admin.memberDetail',[$id,'edit'])->with('success', 'Accomodation Setting status updated successfully!');  
    }
   
}
