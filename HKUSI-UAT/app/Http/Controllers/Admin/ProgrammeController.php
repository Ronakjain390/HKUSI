<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Programme;
use App\Models\MemberInfo;
use App\Models\MemberProgramme;
use App\Models\Quota;
use App\Models\QuotaProgramme;
use App\Models\HallSetting;
use App\Models\ProgrammeHallSetting;
use App\Models\MemberHallSetting;
use Auth;

class ProgrammeController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:programmes-list|programme-create|programme-edit|programme-delete', ['only' => ['index','store']]);
        $this->middleware('permission:programme-create', ['only' => ['create','store']]);
        $this->middleware('permission:programme-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:programme-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Programme Setting";
        return view('admin.programme.index',compact('headerTitle'));
    }

    public function create(Request $request){
        $id = $request->id;
        $members = MemberInfo::select('id','given_name','application_number')->orderBy('given_name','ASC')->get();
        $years = HallSetting::where('status',1)->orderBy('year','ASC')->get();
        $memberget = MemberHallSetting::where('hall_setting_id',$id)->get();
        $headerTitle = "Programme Create";
        return view('admin.programme.create',compact('headerTitle','members','years','memberget'));
    }

    public function store(Request $request){
         $this->validate($request, [
           'programme_code'                     => 'required|unique:programmes,programme_code',
        ]); 
        $input          = $request->all();
        $programme      = new Programme();
        $programme['programme_code']     =  $input['programme_code'];
        $programme['programme_name']     =  $input['programme_name'];
        $programme['start_date']         =  strtotime($input['start_date']);
        $programme['end_date']           =  strtotime($input['end_date']);
        $programme['status']             =  $input['status'];
        $programme->save();
        if (isset($input['year']) && !empty($input['year'])) {
            ProgrammeHallSetting::insert(['hall_setting_id'=>$input['year'],'programme_id'=>$programme->id]);
           
        }
        if(isset($input['member']) && !empty($input['member'])){
             foreach ($input['member'] as $key => $programmeValue) {
                MemberProgramme::insert(['member_info_id'=>$programmeValue,'programme_id'=>$programme->id]);
            }
        }
        return redirect()->route('admin.programme-setting.index')->with('success','programme created successfully');
    }

    public function update(Request $request, $id){
        $this->validate($request, [
           'programme_code'                     => 'required|unique:programmes,programme_code,' . $id,
        ]); 
        $input                                     =  $request->all();
        $programmeUpdate                           =  [];
        $programmeUpdate['programme_code']         =  $input['programme_code'];
        $programmeUpdate['programme_name']         =  $input['programme_name'];
        $programmeUpdate['start_date']             =  strtotime($input['start_date']);
        $programmeUpdate['end_date']               =  strtotime($input['end_date']);
        $programmeUpdate['status']                 =  $input['status'];
        Programme::where('id',$id)->update($programmeUpdate);
        MemberProgramme::where('programme_id',$id)->delete();
        if (isset($input['member']) && !empty($input['member'])) {
            foreach ($input['member'] as $key => $programmeValue) {
                MemberProgramme::insert(['member_info_id'=>$programmeValue,'programme_id'=>$id]);
            }
        }
        return redirect()->route('admin.programme-setting.index')->with('success','Programme update successfully');
    }

    public function statusChange(Request $request, $id, $status) {      
        $programme = Programme::where('id',$id)->first();
        if (isset($programme) && !empty($programme)) {
            $programme->update(['status' => $status]);
        }
        return redirect()->route('admin.programmeDetail',[$id,'show'])->with('success', 'MemberInfo status updated successfully!');        
    }

    public function programmeDetail(Request $request , $id ,$type){
        $dataId = $id;
        $dataType = $type;
        $headerTitle = "Programme Details";
        $members  = $memberprogram = array();
        //$members = MemberInfo::get();
        $programmeInfo = Programme::find($id);
        if(isset($programmeInfo) && !empty($programmeInfo)){
            if ($dataType == 'details') {
                $headerTitle = "Programme Details";
            }elseif($dataType == 'edit'){
                $headerTitle = "Programme Details";
            }elseif($dataType == 'members'){
                $headerTitle = " Programme member Details";
            }elseif($dataType == 'hall-bookings'){
                $headerTitle = " Programme member Details";
            }elseif($dataType == 'event-bookings'){
                $headerTitle = " Programme member Details";
            }elseif($dataType == 'dining-tokens'){
                $headerTitle = " Programme member Details";
            }else{
                  return redirect()->route('admin.programme-setting.index');
            }
        }else{
            return redirect()->route('admin.programme-setting.index');
        }
        $thisYearMember = $programmeYear = [];
        $programmeYear = ProgrammeHallSetting::where('programme_id',$programmeInfo->id)->first();
        if (isset($programmeYear->hall_setting_id) && !empty($programmeYear->hall_setting_id)) {
            $thisYearMember = MemberHallSetting::where('hall_setting_id',$programmeYear->hall_setting_id)->distinct()->pluck('member_info_id')->toArray();
        }
        $memberprogram = MemberProgramme::where('programme_id',$id)->distinct()->pluck('member_info_id')->toArray();
        /*if(count($memberprogram)){
            $members = MemberInfo::whereIn('id',$memberprogram)->get();
        }*/
        if($dataType=='edit' || $dataType=='details'){
            $members = MemberInfo::whereIn('id',$thisYearMember)->get();
        }
        return view('admin.programme.comman',compact('headerTitle','members','programmeInfo','dataId','dataType','memberprogram'));
    }

    public function destroy($id)
    {
        Programme::find($id)->delete();
        return redirect()->route('admin.programme-setting.index')->with('success', 'Programme deleted successfully');
    }

    public function multipleprogramedelete(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $programe) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    Programme::where('id', $programe)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'enable'){
                    $programeInof = Programme::select('id','status')->where('id', $programe)->first();
                    Programme::where('id',$programeInof->id)->update(['status'=>'1']);
                }else{
                    $programeInof = Programme::select('id','status')->where('id', $programe)->first();
                    Programme::where('id',$programeInof->id)->update(['status'=>'0']);
                }
            }
        }
        return redirect()->back();
    }

    public function programmeselectstatuschange(Request $request, $id) {  
        $user = Programme::where('id',$id)->first();
            if (isset($user) && !empty($user)) {
                $user->update(['status' => $request->status]);
        }
        return redirect()->route('admin.programme-setting',[$id,'store'])->with('success', 'ProgrammeInfo status updated successfully!');  
    }


    public function getfilterdata(Request $request){
        $year_id = $request->id;
        if (isset($year_id) && !empty($year_id)) {
            $membersIds =MemberHallSetting::where('hall_setting_id',$year_id)->pluck('member_info_id')->toArray();
            $members = MemberInfo::select('id','given_name','surname')->whereIn('id',$membersIds)->orderBy('given_name','ASC')->get();
            return response()->json($members);
        }
    }
   
}
