<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Http\Request;
use DB;

class ActivityLogController extends Controller
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
        $headerTitle = "Activity Logs";
        return view('admin.activity-log.index', compact('headerTitle'));
    }

    public function multiplActivityLogs(Request $request)
    {
        $input = $request->all();   
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $programe) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    DB::table('activity_log')->where('id', $programe)->delete();
                }
            }
        }
        return redirect()->back();
    }
}
