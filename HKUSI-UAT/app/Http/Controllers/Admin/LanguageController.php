<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use Auth;

class LanguageController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:language-list|language-create|language-edit|language-delete', ['only' => ['index','store']]);
        $this->middleware('permission:language-create', ['only' => ['create','store']]);
        $this->middleware('permission:language-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:language-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Event Language Setting";
        return view('admin.language.index',compact('headerTitle'));
    }

    public function create(Request $request){

        $headerTitle = "Event Language Create";
        return view('admin.language.create',compact('headerTitle'));
    }

    public function store(Request $request){
         $this->validate($request, [
           'name'                     => 'required',
        ]); 
        $input          = $request->all();
        $language      = new Language();
        $language['name']               =  $input['name'];
        $language['status']             =  $input['status'];
        $language->save();
        return redirect()->route('admin.language.index')->with('success','programme created successfully');
    }
    public function edit($id){
        $headerTitle = "Event Language Details";
        $language = Language::where('id',$id)->first();
        return view('admin.language.edit',compact('language','headerTitle'));
    }

    public function update(Request $request, $id){
        $this->validate($request, [
           'name'                     => 'required',
        ]); 
        $input                        =  $request->all();
        $eventType                    =  [];
        $eventType['name']            =  $input['name'];
        $eventType['status']          =  $input['status'];
        Language::where('id',$id)->update($eventType);
        return redirect()->route('admin.language.index')->with('success','Event Type update successfully');
    }

    public function multipleLangeuage(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $programe) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    Language::where('id', $programe)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'disable'){
                    $programeInof = Language::select('id','status')->where('id', $programe)->first();
                    Language::where('id',$programeInof->id)->update(['status'=>'0']);
                }else{
                    $programeInof = Language::select('id','status')->where('id', $programe)->first();
                    Language::where('id',$programeInof->id)->update(['status'=>'1']);
                }
            }
        }
        return redirect()->back();
    }

}
