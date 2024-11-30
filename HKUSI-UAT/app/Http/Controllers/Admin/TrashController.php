<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\MemberInfo;
use App\Models\ImageBank;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailJob;
use App\Traits\UploadTraits;
use Auth;

class TrashController extends Controller
{
    use UploadTraits;
    //
    function __construct()
    {
        $this->middleware('permission:trash-list|trash-delete', ['only' => ['index']]);
        $this->middleware('permission:trash-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Trash List";
        return view('admin.trash.index',compact('headerTitle'));
    }
    public function show($id){
        $headerTitle = "Trash Details";
        $TrashInfo = MemberInfo::find($id);
        return view('admin.trash.show',compact('headerTitle','TrashInfo'));
    }
    
    
    public function destroy($id)
    {
        if(isset($id)){
         $member = MemberInfo::where('id',$id)->first();
         if(isset($member->user_id)){
                User::where('id',$member->user_id)->delete();
            }
        }
        MemberInfo::where('id',$id)->delete();
        return redirect()->route('admin.members.index')->with('success', 'Member deleted successfully');
    }

    public function multipleusersdelete(Request $request)
	{
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $member) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    MemberInfo::where('id', $member)->forceDelete();
                }
            }
        }
		return redirect()->back();
	}

  

   
}
