<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImportantNotice;
use Auth;
use Illuminate\Support\Facades\DB;


class ImportantNoticeController extends Controller
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
        $headerTitle = "ImportantNotice Setting";
        return view('admin.important-notice.index',compact('headerTitle'));
    }

    public function create(Request $request){
        $headerTitle = "ImportantNotice Create";
        return view('admin.important-notice.create',compact('headerTitle'));
    }
     public function importantnoticesetting(Request $request , $id ,$type){ 
        $dataId = $id;        
        $dataType = $type;
        $data = '';
        $ImportantNotice = ImportantNotice::find($id);
        if (!empty($ImportantNotice)) {
            if($type=="edit"){
                $headerTitle = "ImportantNotice Details";
                $data = ImportantNotice::where('id',$id)->first();
            }elseif($type=="show"){
                $headerTitle = "ImportantNotice Details";
            }
            return view('admin.important-notice.comman',compact('headerTitle','ImportantNotice','dataType'));
        }else{
            return redirect()->route('admin.importantnotice.index');
        }        
    }

    public function store(Request $request){
        $input               = $request->all();
        $ImportantNotice             = new ImportantNotice();
        $ImportantNotice['title']     =  $input['title'];
        $ImportantNotice['description']     =  $input['description'];
        $ImportantNotice['status']   =  $input['status'];
        $ImportantNotice->save(); 
        return redirect()->route('admin.importantnotice.index')->with('success','ImportantNotice created successfully');
    }
    public function edit($id){
        $headerTitle = "ImportantNotice Details";
        $data = ImportantNotice::where('id',$id)->first();
        return view('admin.important-notice.edit',compact('data','headerTitle'));

    }
    public function update(Request $request, $id){
        $input                                     =  $request->all();
        $ImportantNoticedata                               =  [];
        $ImportantNoticedata['title']                      =  $input['title'];
        $ImportantNoticedata['description']                =  $input['description'];
        $ImportantNoticedata['status']                     =  $input['status'];
        ImportantNotice::where('id',$id)->update($ImportantNoticedata);
        return redirect()->route('admin.importantnotice.index')->with('success','ImportantNotice update successfully');
    }

    public function statusChange(Request $request, $id, $status) {      
        $programme = ImportantNotice::where('id',$id)->first();
        if (isset($programme) && !empty($programme)) {
            $programme->update(['status' => $status]);
        }
        return redirect()->route('admin.programmeDetail',[$id,'show'])->with('success', 'MemberInfo status updated successfully!');        
    }
    public function multipleimportentnoticedelete(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $getImportantNotice) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete'){
                    ImportantNotice::where('id', $getImportantNotice)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'inactive'){
                    $ImportantNoticeInfo = ImportantNotice::select('id','status')->where('id', $getImportantNotice)->first();
                    ImportantNotice::where('id',$ImportantNoticeInfo->id)->update(['status'=>'0']);
                }else{
                    $ImportantNoticeInfo = ImportantNotice::select('id','status')->where('id', $getImportantNotice)->first();
                    ImportantNotice::where('id',$ImportantNoticeInfo->id)->update(['status'=>'1']);
                }
            }
        }
        return redirect()->back();
    }
    public function destroy($id)
    {
        ImportantNotice::find($id)->delete();
        return redirect()->route('admin.importantnotice.index')->with('success', 'ImportantNotice deleted successfully');
    }

}
