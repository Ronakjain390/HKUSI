<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiningToken;
use Auth;

class DiningTokenController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:diningtoken-list|diningtoken-create|diningtoken-edit|diningtoken-delete', ['only' => ['index','store']]);
        $this->middleware('permission:diningtoken-create', ['only' => ['create','store']]);
        $this->middleware('permission:diningtoken-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:diningtoken-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Dining Token";
        return view('admin.dining-token.index',compact('headerTitle'));
    }

    public function create(Request $request){

        $headerTitle = "Dining Token Create";
        return view('admin.dining-token.create',compact('headerTitle'));
    }

    public function store(Request $request){
         $this->validate($request, [
           'quantity' => 'required|numeric',
		   'unit_price' => 'required|numeric',
		   'status' => 'required|numeric',
        ]);
		
        $input = $request->all();
        $diningTokens = new Diningtoken();
        $diningTokens['quantity'] =  $input['quantity'];
		$diningTokens['unit_price'] =  $input['unit_price'];
        $diningTokens['status'] =  $input['status'];
        $diningTokens->save();
        return redirect()->route('admin.dining-token.index')->with('success','Dining Token created successfully');
    }
	
    public function edit($id){
        $headerTitle = "Edit Dining Token";
        $diningTokens = Diningtoken::where('id',$id)->first();
        return view('admin.dining-token.edit',compact('diningTokens','headerTitle'));
    }

    public function update(Request $request, $id){
        $this->validate($request, [
           'quantity' => 'required|numeric',
		   'unit_price' => 'required|numeric',
		   'status' => 'required|numeric',
        ]);
		
        $input  =  $request->all();
        $diningTokens =  [];
        $diningTokens['quantity']  =  $input['quantity'];
		$diningTokens['unit_price']  =  $input['unit_price'];
        $diningTokens['status'] =  $input['status'];
        DiningToken::where('id',$id)->update($diningTokens);
        return redirect()->route('admin.dining-token.index')->with('success','Dining Token update successfully');
    }


}
