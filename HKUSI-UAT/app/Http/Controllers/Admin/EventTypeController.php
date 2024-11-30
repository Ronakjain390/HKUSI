<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Auth;

class EventTypeController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:eventtype-list|eventtype-create|eventtype-edit|eventtype-delete', ['only' => ['index','store']]);
        $this->middleware('permission:eventtype-create', ['only' => ['create','store']]);
        $this->middleware('permission:eventtype-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:eventtype-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Event Type Details";
        return view('admin.event-type.index',compact('headerTitle'));
    }

    public function create(Request $request){

        $headerTitle = "Event Type Details";
        return view('admin.event-type.create',compact('headerTitle'));
    }

    public function store(Request $request){
         $this->validate($request, [
           'name'                     => 'required',
        ]); 
        $input          = $request->all();
        $eventType      = new Category();
        $eventType['name']               =  $input['name'];
        $eventType['status']             =  $input['status'];
        $eventType->save();
        return redirect()->route('admin.event-type.index')->with('success','programme created successfully');
    }
    public function edit($id){
        $headerTitle = "Event Type Details";
        $eventType = Category::where('id',$id)->first();
        return view('admin.event-type.edit',compact('eventType','headerTitle'));
    }

    public function update(Request $request, $id){
        $this->validate($request, [
           'name'                     => 'required',
        ]); 
        $input                                     =  $request->all();
        $eventType                           =  [];
        $eventType['name']                =  $input['name'];
        $eventType['status']                 =  $input['status'];
        Category::where('id',$id)->update($eventType);
        return redirect()->route('admin.event-type.index')->with('success','Event Type update successfully');
    }

    public function eventypemultiple(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $programe) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    Category::where('id', $programe)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'disable'){
                    $programeInof = Category::select('id','status')->where('id', $programe)->first();
                    Category::where('id',$programeInof->id)->update(['status'=>'0']);
                }else{
                    $programeInof = Category::select('id','status')->where('id', $programe)->first();
                    Category::where('id',$programeInof->id)->update(['status'=>'1']);
                }
            }
        }
        return redirect()->back();
    }

}
