<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Auth;
use Illuminate\Support\Facades\DB;


class EmailTemplateController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:importantnotice-list|importantnotice-create|importantnotice-edit|importantnotice-delete', ['only' => ['index','store']]);
        $this->middleware('permission:importantnotice-create', ['only' => ['create','store']]);
        $this->middleware('permission:importantnotice-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:importantnotice-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "EmailTemplate Setting";
        return view('admin.email-template.index',compact('headerTitle'));
    }

    public function create(Request $request){
        $headerTitle = "EmailTemplate Create";
        return view('admin.email-template.create',compact('headerTitle'));
    }
     public function emailtemplatesetting(Request $request , $id ,$type){ 
        $dataId = $id;        
        $dataType = $type;
        $data = '';
        $headerTitle = "EmailTemplate Details";
        $EmailTemplate = EmailTemplate::find($id);
        if (!empty($EmailTemplate)) {
            if($type=="edit"){
                $headerTitle = "EmailTemplate Details";
                $data = EmailTemplate::where('id',$id)->first();
            }elseif($type=="show"){
                $headerTitle = "EmailTemplate Details";
            }
            return view('admin.email-template.comman',compact('headerTitle','EmailTemplate','dataType'));
        }else{
            return redirect()->route('admin.email-template.index');
        }        
    }

    public function store(Request $request){
        $input               = $request->all();
        $EmailTemplate             = new EmailTemplate();
        $EmailTemplate['title']     =  $input['title'];
        $EmailTemplate['description'] =  $input['description'];
        $EmailTemplate['status']   =  $input['status'];
        $EmailTemplate->save(); 
        return redirect()->route('admin.email-setting.index')->with('success','EmailTemplate created successfully');
    }
    public function edit($id){
        $headerTitle = "EmailTemplate Details";
        $data = EmailTemplate::where('id',$id)->first();
        return view('admin.email-template.edit',compact('data','headerTitle'));

    }
    public function update(Request $request, $id){
        $input                                     =  $request->all();
        $EmailTemplatedata                               =  [];
        $EmailTemplatedata['title']                      =  $input['title'];
        $EmailTemplatedata['description']                =  $input['description'];
        $EmailTemplatedata['status']                     =  $input['status'];
        EmailTemplate::where('id',$id)->update($EmailTemplatedata);
        return redirect()->route('admin.email-setting.index')->with('success','EmailTemplate update successfully');
    }

    public function emailtemplatesettingmultiple(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $getEmailTemplate) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete'){
                    EmailTemplate::where('id', $getEmailTemplate)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'inactive'){
                    $EmailTemplateInfo = EmailTemplate::select('id','status')->where('id', $getEmailTemplate)->first();
                    EmailTemplate::where('id',$EmailTemplateInfo->id)->update(['status'=>'0']);
                }else{
                    $EmailTemplateInfo = EmailTemplate::select('id','status')->where('id', $getEmailTemplate)->first();
                    EmailTemplate::where('id',$EmailTemplateInfo->id)->update(['status'=>'1']);
                }
            }
        }
        return redirect()->back();
    }
    public function destroy($id)
    {
        EmailTemplate::find($id)->delete();
        return redirect()->route('admin.email-template.index')->with('success', 'EmailTemplate deleted successfully');
    }

}
