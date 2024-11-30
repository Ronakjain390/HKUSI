<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:update-profile', ['only' => ['getProfile', 'updateProfile']]);
    }

    public function getProfile(Request $request)
    {
        $pageInfo = array();
        $headerTitle = 'Update Profile';
        $UserInfo = Auth::user();
        return view('admin.profile.update-profile',compact('headerTitle','UserInfo'))->with($pageInfo);
    }

    public function updateProfile(Request $request)
    {
        $userInfo = Auth::user();
        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . $userInfo->id,
            'title' => 'required',
            'gender' => 'required',
            'surname' => 'required',
            'given_name' => 'required',
            'mobile_tel_no' => 'required',
            'department' => 'required',
        ]);

        $userInfo->update([
            'name' => $request->surname.' '.$request->given_name,
            'title' => $request->title,
            'gender' => $request->gender,
            'surname' => $request->surname,
            'given_name' => $request->given_name,
            'mobile_tel_no' => $request->mobile_tel_no,
            'department' => $request->department,
        ]);
        return redirect()->route('admin.getProfile')->with('success', 'Profile Information updated successfully');
    }
}
