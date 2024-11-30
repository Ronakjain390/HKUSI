<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Http\Request;

class AdminAppVersionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:adminappversion-list|adminappversion-create|adminappversion-edit|adminappversion-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:adminappversion-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:adminappversion-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:adminappversion-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Admin App Version";
        return view('admin.adminappversion.index', compact('headerTitle'));
    }

    public function create(Request $request)
    {

        $headerTitle = "Admin App Version Details";
        return view('admin.adminappversion.create', compact('headerTitle'));
    }

    public function versionDetail(Request $request, $id, $type)
    {
        $dataId = $id;
        $dataType = $type;
        if ($dataType == "show") {
            $headerTitle = "Admin App Version Details";
        } elseif ($dataType == "edit") {
            $headerTitle = "Admin App Version Details";
        } else {
            return redirect()->route('admin.adminappversion.index');
        }
        $AppVersionInfo = AppVersion::find($id);
        if (!empty($AppVersionInfo)) {
            return view('admin.adminappversion.comman', compact('headerTitle', 'AppVersionInfo', 'dataId', 'dataType'));
        } else {
            return redirect()->route('admin.adminappversion.index');
        }
    }

    public function show($id)
    {

        $headerTitle = "Admin App Version Details";
        $AppVersionInfo = AppVersion::find($id);
        return view('admin.adminappversion.show', compact('headerTitle', 'AppVersionInfo'));
    }

    public function store(Request $request)
    {
        $inputs = $this->validate($request, [
            'ios_release_date' => 'required',
            'ios_version' => 'required',
            'ios_app_store_url' => 'required',
            'ios_force_update' => 'required',
            'android_release_date' => 'required',
            'android_version' => 'required',
            'android_app_store_url' => 'required',
            'android_force_update' => 'required',
        ]);
        AppVersion::create($inputs);
        return redirect()->route('admin.adminappversion.index')->with('success', 'Admin App Version created successfully');
    }
    public function edit($id)
    {
        $headerTitle = "Edit App Version";
        $language = AppVersion::where('id', $id)->first();
        return view('admin.adminappversion.edit', compact('language', 'headerTitle'));
    }

    public function update(Request $request, $id)
    {
        $inputs = $this->validate($request, [
            'ios_release_date' => 'required',
            'ios_version' => 'required',
            'ios_app_store_url' => 'required',
            'ios_force_update' => 'required',
            'android_release_date' => 'required',
            'android_version' => 'required',
            'android_app_store_url' => 'required',
            'android_force_update' => 'required',
        ]);
        AppVersion::where('id', $id)->update($inputs);
        return redirect()->route('admin.adminappversion.index')->with('success', 'Admin App Version update successfully');
    }

    public function multipleAdminAppVersion(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $programe) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    AppVersion::where('id', $programe)->delete();
                }
            }
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        AppVersion::where('id', $id)->delete();
        return redirect()->route('admin.adminappversion.index')->with('success', 'Admin App Version deleted successfully');
    }

}
