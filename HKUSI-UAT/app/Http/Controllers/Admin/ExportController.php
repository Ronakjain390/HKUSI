<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ExportDataInfo;
use Auth;

class ExportController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('permission:export-history-list|export-history-create|export-history-edit|export-history-delete', ['only' => ['index','store']]);
        $this->middleware('permission:export-history-create', ['only' => ['create','store']]);
        $this->middleware('permission:export-history-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:export-history-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Export";
        return view('admin.export.index',compact('headerTitle'));
    }

    public function exportData(Request $request , $type=''){
        $headerTitle = "New Export";
        $dataType = $type;
        return view('admin.export.create',compact('headerTitle','dataType'));
    }

    public function exportDetail(Request $request , $id , $type){        
        $dataId = $id;
        $dataType = $type;
        $headerTitle = "Export Details";
        $exportDataInfo = ExportDataInfo::find($id);
        if(!empty($exportDataInfo)) {           
            return view('admin.export.show',compact('headerTitle','exportDataInfo','dataType','dataId'));
        }else{
            return redirect()->route('admin.export.index');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ExportDataInfo::find($id)->delete();
        // ImportMemberDetail::where('import_data_info_id',$id)->delete();
        return redirect()->route('admin.export.index')->with('success', 'Export Detail delete successfully');
    }
    
    public function multipleExportDataDelete(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $programe) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    ExportDataInfo::where('id', $programe)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Completed'){
                    $exportDataInfo = ExportDataInfo::select('id','status')->where('id', $programe)->first();
                    ExportDataInfo::where('id',$exportDataInfo->id)->update(['status'=>'1']);
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Failed'){
                    $exportDataInfo = ExportDataInfo::select('id','status')->where('id', $programe)->first();
                    ExportDataInfo::where('id',$exportDataInfo->id)->update(['status'=>'0']);
                }else{
                    ExportDataInfo::where('id', $programe)->update(['status'=>'1']);
                }
            }
        }
        return redirect()->back();
    }
   

}
