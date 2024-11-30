<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ImageBank;
use App\Models\ProgrammeHallSetting;
use App\Models\Programme;
use Auth;
use Illuminate\Support\Facades\Redirect;

class ImageBankController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('permission:images-list|images-create|images-edit|images-delete', ['only' => ['index','store']]);
        $this->middleware('permission:images-create', ['only' => ['create','store']]);
        $this->middleware('permission:images-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:images-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Images";
        return view('admin.imagebank.index',compact('headerTitle'));
    }
	
    public function deleteall(Request $request){
        $inputs = $request->all();
        if (isset($inputs['selectedata'])) {
            ImageBank::whereIn('id', $request->input('selectedata'))->delete();          
            return Redirect::back()->with('success', 'ImageBank all data delete');
        }else{
            return redirect::back();
        }
        
    }

    public function getprogram(Request $request){
        $year_id = $request->id;
        if (isset($year_id) && !empty($year_id)) {
            $yearselectid =ProgrammeHallSetting::where('hall_setting_id',$year_id)->pluck('programme_id')->toArray();
            $datavalues = Programme::select('id','programme_name','programme_code')->whereIn('id',$yearselectid)->orderBy('id','DESC')->get();
            return response()->json($datavalues);
        }
    }
}

