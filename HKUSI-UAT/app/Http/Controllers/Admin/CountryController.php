<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use Auth;
use Illuminate\Support\Facades\DB;


class CountryController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:country-list|country-create|country-edit|country-delete', ['only' => ['index','store']]);
        $this->middleware('permission:country-create', ['only' => ['create','store']]);
        $this->middleware('permission:country-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:country-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Study Country Setting";
        return view('admin.country.index',compact('headerTitle'));
    }

    public function create(Request $request){
        $headerTitle = "Study Country Create";
        return view('admin.country.create',compact('headerTitle'));
    }

    public function store(Request $request){
         $this->validate($request, [
           'name'                     => 'required',
        ]); 
        $input               = $request->all();
        $country             = new Country();
        $country['name']     =  $input['name'];
        $country['status']   =  $input['status'];
        $country->save(); 
        return redirect()->route('admin.country.index')->with('success','Country created successfully');
    }
    public function edit($id){
        $headerTitle = "Study Country Details";
        $data = country::where('id',$id)->first();
        return view('admin.country.edit',compact('data','headerTitle'));

    }
    public function update(Request $request, $id){
        $this->validate($request, [
           'name'                     => 'required',
        ]); 
        $input                                     =  $request->all();
        $countrydata                               =  [];
        $countrydata['name']                       =  $input['name'];
        $countrydata['status']                     =  $input['status'];
        Country::where('id',$id)->update($countrydata);
        return redirect()->route('admin.country.index')->with('success','Country update successfully');
    }

    public function statusChange(Request $request, $id, $status) {      
        $programme = Country::where('id',$id)->first();
        if (isset($programme) && !empty($programme)) {
            $programme->update(['status' => $status]);
        }
        return redirect()->route('admin.programmeDetail',[$id,'show'])->with('success', 'MemberInfo status updated successfully!');        
    }
    public function multipleCountrydelete(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $getcountry) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete'){
                    Country::where('id', $getcountry)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'inactive'){
                    $countryInfo = Country::select('id','status')->where('id', $getcountry)->first();
                    Country::where('id',$countryInfo->id)->update(['status'=>'0']);
                }else{
                    $countryInfo = Country::select('id','status')->where('id', $getcountry)->first();
                    Country::where('id',$countryInfo->id)->update(['status'=>'1']);
                }
            }
        }
        return redirect()->back();
    }
}
