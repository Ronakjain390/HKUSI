<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailSetting;
use Auth,Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Env;


class EmailSettingController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:email-setting-list|email-setting-create|email-setting-edit|email-setting-delete', ['only' => ['index','store']]);
        $this->middleware('permission:email-setting-create', ['only' => ['create','store']]);
        $this->middleware('permission:email-setting-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:email-setting-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "EmailSetting Setting";
        $EmailSetting = EmailSetting::first();
        $dataType ='';
        return view('admin.email-setting.index',compact('headerTitle','EmailSetting','dataType'));
    }

    public function create(Request $request){
        $headerTitle = "EmailSetting Create";
        return view('admin.email-setting.create',compact('headerTitle'));
    }
     public function emailsettingDetails(Request $request , $id ,$type){ 
        $dataId = $id;        
        $dataType = $type;
        $data = '';
        $EmailSetting = EmailSetting::find($id);
        if (!empty($EmailSetting)) {
            if($type=="email-template"){
                $headerTitle = "EmailSetting Details";
                $data = EmailSetting::where('id',$id)->first();
            }elseif($type=="show"){
                $headerTitle = "EmailSetting Details";
            }
            return view('admin.email-setting.index',compact('headerTitle','EmailSetting','dataType'));
        }else{
            return redirect()->route('admin.email-setting.index');
        }        
    }

    public function store(Request $request){

        $input                              = $request->all();
        $EmailSetting                       = new EmailSetting();
        $EmailSetting['host_name']        =  $input['host_name'];
        $EmailSetting['port']               =  $input['port'];
        $EmailSetting['connection_security'] =  $input['connection_security'];
        $EmailSetting['email']              =  $input['email'];
        $EmailSetting['password']           =  $input['password'];
        $EmailSetting->save(); 
        return redirect()->route('admin.email-setting.index')->with('success','EmailSetting created successfully');
    }
    public function edit($id){
        $headerTitle = "EmailSetting Details";
        $data = EmailSetting::where('id',$id)->first();
        return view('admin.email-setting.edit',compact('data','headerTitle'));

    }
    public function update(Request $request, $id){
        $input                                     =  $request->all();
        $EmailSettingdata                               =  [];
        $EmailSettingdata['host_name']          =  $input['host_name'];
        $EmailSettingdata['port']               =  $input['port'];
        if(isset($input['connection_security']) && !empty($input['connection_security'])){
            $EmailSettingdata['connection_security']=  $input['connection_security'];

        }else{
            $EmailSettingdata['connection_security']=  'off';
        }
        $EmailSettingdata['email']              =  $input['email'];
        $EmailSettingdata['password']           =  $input['password'];
        EmailSetting::where('id',$id)->update($EmailSettingdata);
        $chekcsetting = EmailSetting::where('id',$id)->first();
        if(!empty($chekcsetting)){
            Env::getRepository()->set('MAIL_HOST',$chekcsetting->host_name);
            Env::getRepository()->set('MAIL_PORT',$chekcsetting->port);
            Env::getRepository()->set('MAIL_USERNAME',$chekcsetting->email);
            Env::getRepository()->set('MAIL_PASSWORD',$chekcsetting->password);
        }
        return redirect()->route('admin.email-setting.index')->with('success','EmailSetting update successfully');
    }

    public function statusChange(Request $request, $id, $status) {      
        $programme = EmailSetting::where('id',$id)->first();
        if (isset($programme) && !empty($programme)) {
            $programme->update(['status' => $status]);
        }
        return redirect()->route('admin.programmeDetail',[$id,'show'])->with('success', 'MemberInfo status updated successfully!');        
    }
    public function multipleimportentnoticedelete(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $getEmailSetting) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete'){
                    EmailSetting::where('id', $getEmailSetting)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'inactive'){
                    $EmailSettingInfo = EmailSetting::select('id','status')->where('id', $getEmailSetting)->first();
                    EmailSetting::where('id',$EmailSettingInfo->id)->update(['status'=>'0']);
                }else{
                    $EmailSettingInfo = EmailSetting::select('id','status')->where('id', $getEmailSetting)->first();
                    EmailSetting::where('id',$EmailSettingInfo->id)->update(['status'=>'1']);
                }
            }
        }
        return redirect()->back();
    }
    public function destroy($id)
    {
        EmailSetting::find($id)->delete();
        return redirect()->route('admin.email-setting.index')->with('success', 'EmailSetting deleted successfully');
    }

}
