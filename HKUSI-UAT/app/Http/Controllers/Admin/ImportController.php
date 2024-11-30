<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ImportDataInfo;
use App\Models\ImportEventDetail;
use App\Models\ImportMemberDetail;
use App\Models\ImportProgramme;
use App\Models\ImportCountry;
use App\Models\ImportHallDetail;
use App\Models\ImportRoomDetail;
use Auth;

class ImportController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('permission:import-history-list|import-history-create|import-history-edit|import-history-delete', ['only' => ['index','store']]);
        $this->middleware('permission:import-history-create', ['only' => ['create','store']]);
        $this->middleware('permission:import-history-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:import-history-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Import History";
        return view('admin.import.index',compact('headerTitle'));
    }

    public function importData(Request $request , $type=''){
        $headerTitle = "New Import";
        $dataType = $type;
        return view('admin.import.create',compact('headerTitle','dataType'));
    }

    public function importDetail(Request $request , $id , $type){        
        $dataId = $id;
        $dataType = $type;
        $headerTitle = "Import Details";
        $importDataInfo = ImportDataInfo::find($id);
        if(!empty($importDataInfo)) {           
            return view('admin.import.show',compact('headerTitle','importDataInfo','dataType','dataId'));
        }else{
            return redirect()->route('admin.import.index');
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
        ImportDataInfo::find($id)->delete();
        ImportMemberDetail::where('import_data_info_id',$id)->delete();
        return redirect()->route('admin.import.index')->with('success', 'Import Detail delete successfully');
    }
    
    public function multipleImportDataDelete(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $alldata) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Delete') {
                    $evnettype = ImportDataInfo::where('id',$alldata)->first();
                        if($evnettype->type=="Event"){
                            if(isset($evnettype->getImportEventReportDetail) && count($evnettype->getImportEventReportDetail)){
                                    foreach ($evnettype->getImportEventReportDetail as $keyEvent => $valueEvent) {
                                        $multiActions = ImportEventDetail::where('id',$valueEvent->id)->first();
                                        if(!empty($multiActions)){
                                            $multiActions->delete();
                                        }
                                    }
                            }
                            ImportDataInfo::where('id',$alldata)->delete();
                        }elseif($evnettype->type=="Member"){
                            if(isset($evnettype->getImportMemberReportDetail) && count($evnettype->getImportMemberReportDetail)){
                                    foreach ($evnettype->getImportMemberReportDetail as $keyEvent => $valueEvent) {
                                        $multiActions = ImportMemberDetail::where('id',$valueEvent->id)->first();
                                        if(!empty($multiActions)){
                                            $multiActions->delete();
                                        }
                                    }
                            }
                            ImportDataInfo::where('id',$alldata)->delete();
                        }elseif($evnettype->type=="Programme"){
                            if(isset($evnettype->getImportProgramReportDetail) && count($evnettype->getImportProgramReportDetail)){
                                    foreach ($evnettype->getImportProgramReportDetail as $keyEvent => $valueEvent) {
                                        $multiActions = ImportProgramme::where('id',$valueEvent->id)->first();
                                        if(!empty($multiActions)){
                                            $multiActions->delete();
                                        }
                                    }
                            }
                            ImportDataInfo::where('id',$alldata)->delete();
                        }elseif($evnettype->type=="Country"){
                            if(isset($evnettype->getImportCountryReportDetail) && count($evnettype->getImportCountryReportDetail)){
                                    foreach ($evnettype->getImportCountryReportDetail as $keyEvent => $valueEvent) {
                                        $multiActions = ImportCountry::where('id',$valueEvent->id)->first();
                                        if(!empty($multiActions)){
                                            $multiActions->delete();
                                        }
                                    }
                            }
                            ImportDataInfo::where('id',$alldata)->delete();
                        }elseif($evnettype->type=="Hall"){
                            if(isset($evnettype->getImportHallDetail) && count($evnettype->getImportHallDetail)){
                                    foreach ($evnettype->getImportHallDetail as $keyEvent => $valueEvent) {
                                        $multiActions = ImportHallDetail::where('id',$valueEvent->id)->first();
                                        if(!empty($multiActions)){
                                            $multiActions->delete();
                                        }
                                    }
                            }
                            ImportDataInfo::where('id',$alldata)->delete();
                        }elseif($evnettype->type=="Room"){
                        if(isset($evnettype->getImportRoomDetail) && count($evnettype->getImportRoomDetail)){
                                foreach ($evnettype->getImportRoomDetail as $keyEvent => $valueEvent) {
                                    $multiActions = ImportRoomDetail::where('id',$valueEvent->id)->first();
                                    if(!empty($multiActions)){
                                        $multiActions->delete();
                                    }
                                }
                        }
                        ImportDataInfo::where('id',$alldata)->delete();
                    }
                }
            }
        }
        return redirect()->back();
    }
   

}
