<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentAppVersion;
use Illuminate\Http\Request;

class StudentAppVersionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:studentappversion-list|studentappversion-create|studentappversion-edit|studentappversion-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:studentappversion-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:studentappversion-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:studentappversion-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Student App Version";
        return view('admin.studentappversion.index', compact('headerTitle'));
    }

    public function create(Request $request)
    {

        $headerTitle = "Student App Version Details";
        return view('admin.studentappversion.create', compact('headerTitle'));
    }

    public function versionDetail(Request $request, $id, $type)
    {
        $dataId = $id;
        $dataType = $type;
        if ($dataType == "show") {
            $headerTitle = "Student App Version Details";
        } elseif ($dataType == "edit") {
            $headerTitle = "Student App Version Details";
        } else {
            return redirect()->route('admin.studentappversion.index');
        }
        $AppVersionInfo = StudentAppVersion::find($id);
        if (!empty($AppVersionInfo)) {
            return view('admin.studentappversion.comman', compact('headerTitle', 'AppVersionInfo', 'dataId', 'dataType'));
        } else {
            return redirect()->route('admin.studentappversion.index');
        }
    }

    public function show($id)
    {

        $headerTitle = "Student App Version Details";
        $AppVersionInfo = StudentAppVersion::find($id);
        return view('admin.studentappversion.show', compact('headerTitle', 'AppVersionInfo'));
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
        StudentAppVersion::create($inputs);
        return redirect()->route('admin.studentappversion.index')->with('success', 'Student App Version created successfully');
    }
    public function edit($id)
    {
        $headerTitle = "Edit App Version";
        $language = StudentAppVersion::where('id', $id)->first();
        return view('admin.studentappversion.edit', compact('language', 'headerTitle'));
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
        StudentAppVersion::where('id', $id)->update($inputs);
        return redirect()->route('admin.studentappversion.index')->with('success', 'Student App Version update successfully');
    }

    public function multipleStudentAppVersion(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $programe) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    StudentAppVersion::where('id', $programe)->delete();
                }
            }
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        StudentAppVersion::where('id', $id)->delete();
        return redirect()->route('admin.studentappversion.index')->with('success', 'Student App Version deleted successfully');
    }

}
