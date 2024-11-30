<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\Country;
use App\Models\HallSetting;
use App\Models\ImageBank;
use App\Models\MemberInfo;
use App\Models\MemberProgramme;
use App\Models\Programme;
use App\Models\User;
use App\Traits\UploadTraits;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    use UploadTraits;
    //
    public function __construct()
    {
        $this->middleware('permission:members-list|member-create|member-edit|member-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:member-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:member-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:member-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Members List";
        return view('admin.members.index', compact('headerTitle'));
    }

    public function show($id)
    {
        $headerTitle = "Member Details";
        $MemberInfo = MemberInfo::find($id);
        return view('admin.members.show', compact('headerTitle', 'MemberInfo'));
    }

    public function create(Request $request)
    {
        $headerTitle = "Member Create";
        $countries = Country::where('status', 1)->get();
        $years = HallSetting::where('status', 1)->get();
        $programme = Programme::select('id', 'programme_code', 'programme_name')->orderBy('programme_name', 'ASC')->get();
        return view('admin.members.create', compact('headerTitle', 'countries', 'programme', 'years'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'email' => 'required|email|unique:users,email',
        ]);
        $name = '';
        if (isset($input['given_name']) && !empty($input['given_name'])) {
            $name .= $input['given_name'];
        }
        if (isset($input['surname']) && !empty($input['surname'])) {
            $name .= ' ' . $input['surname'];
        }
        $data = new User();
        $data['name'] = $name;
        $data['email'] = $input['email'];
        $data['password'] = Hash::make($input['name']);
        $data['status'] = $input['status'];
        $data->save();
        $data->assignRole('Member');
        if (!empty($data->id)) {
            $memberData = new MemberInfo();
            $memberData['title'] = $input['name'];
            $memberData['user_id'] = $data->id;
            $memberData['application_number'] = $input['application_number'];
            $memberData['given_name'] = $input['given_name'];
            $memberData['email_address'] = $data->email;
            $memberData['surname'] = $input['surname'];
            $memberData['chinese_name'] = $input['chinese_name'];
            $memberData['gender'] = $input['gender'];
            $memberData['date_of_birth'] = strtotime($input['dob']);
            $memberData['hkid_card_no'] = $input['hkid'];
            $memberData['passport_no'] = $input['passport_no'];
            $memberData['mobile_tel_no'] = $input['mobile_tel_no'];
            $data['contact_email'] = $input['contact_email'];
            $memberData['contact_english_name'] = $input['contact_english_name'];
            $memberData['contact_relationship'] = $input['contact_relationship'];
            $memberData['contact_chinese_name'] = $input['contact_chinese_name'];
            $memberData['contact_tel_no'] = $input['contact_tel_no'];
            $memberData['nationality_id'] = $input['nationality'];
            $memberData['study_country_id'] = $input['study_country'];
            $memberData['status'] = $input['activation'];
            $memberData->save();
            if (isset($memberData->id)) {
                if (isset($input['programmes']) && !empty($input['programmes'])) {
                    foreach ($input['programmes'] as $key => $programmeValue) {
                        MemberProgramme::insert(['member_info_id' => $memberData->id, 'programme_id' => $programmeValue]);
                    }
                }
                if (isset($input['year']) && !empty($input['year'])) {
                    foreach ($input['year'] as $key => $yearvalue) {
                        DB::table('member_hall_settings')->insert(['member_info_id' => $memberData->id, 'hall_setting_id' => $yearvalue]);
                    }
                }
            }
        }

        $url = env('FRONT_LOGIN_URL');
        $mailInfo = [
            'given_name' => $input['given_name'],
            'application_id' => $input['application_number'],
            'url' => $url,
        ];
        $details = ['type' => 'RegisterTemplate', 'email' => $data->email, 'mailInfo' => $mailInfo];
        SendEmailJob::dispatchNow($details);
        return redirect()->route('admin.members.index')->with('message', 'Member create successfully.');

    }

    public function edit(Request $request, $id)
    {
        $headerTitle = "Member Details";
        $MemberInfo = MemberInfo::find($id);
        return view('admin.members.edit', compact('headerTitle', 'MemberInfo'));
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $CustomImgErrMsg = array(
            'profile_image.mimes' => 'Profile image type must be JPEG',
            'profile_image.max' => 'The profile image must be 5MB or below',
            'profile_image.dimensions' => 'Profile image dimensions must be at least 1200Px(W) X 1600px(H)',
        );
        $this->validate($request, [

            // 'profile_image' => 'mimes:jpeg,jpg,png|max:5120',
            'profile_image' => 'mimes:jpeg|max:5120|dimensions:min_width=1200,min_height=1600|nullable',
        ], $CustomImgErrMsg);

        $name = '';
        if (isset($input['given_name']) && !empty($input['given_name'])) {
            $name .= $input['given_name'];
        }
        if (isset($input['surname']) && !empty($input['surname'])) {
            $name .= ' ' . $input['surname'];
        }
        $user = [];
        $user['name'] = $name;
        $user['status'] = $input['status'];
        User::where('id', $input['user_id'])->update($user);

        // Update profile
        $image_bank_id = null;
        $userinfo = MemberInfo::where('user_id', $input['user_id'])->first();
        if (isset($input["profile_image"]) && !empty($input['profile_image'])) {
            $files = $request->file('profile_image');
            $profile_image = $this->uploadSingleImage($files, 'profile', '', @$userinfo->application_number);
            if ($profile_image != "") {
                if (isset($userinfo->image_bank_id) && !empty(@$userinfo->image_bank_id)) {
                    $exitImage = ImageBank::where('id', @$userinfo->image_bank_id)->delete();
                }
                $imagesave = ImageBank::create([
                    'profile_image' => $profile_image,
                    'application_id' => @$userinfo->application_number,
                ]);
                $image_bank_id = $imagesave->id;
            }
        } else if (empty($input['old_images'])) {
            $image_bank_id = null;
        } else {
            $image_bank_id = $userinfo->image_bank_id;
        }
        // Update profile

        if (isset($input['user_id'])) {
            $data = [];
            $data['user_id'] = $input['user_id'];
            $data['title'] = $input['title'];
            $data['given_name'] = $input['given_name'];
            $data['image_bank_id'] = $image_bank_id;
            $data['surname'] = $input['surname'];
            $data['chinese_name'] = $input['chinese_name'];
            $data['gender'] = $input['gender'];
            $data['date_of_birth'] = strtotime($input['dob']);
            $data['hkid_card_no'] = $input['hkid'];
            $data['passport_no'] = $input['passport_no'];
            $data['nationality_id'] = $input['nationality'];
            $data['study_country_id'] = $input['study_country'];
            $data['mobile_tel_no'] = $input['mobile_tel_no'];
            $data['contact_email'] = $input['contact_email'];
            $data['contact_english_name'] = $input['contact_english_name'];
            $data['contact_relationship'] = $input['contact_relationship'];
            $data['contact_chinese_name'] = $input['contact_chinese_name'];
            $data['contact_tel_no'] = $input['contact_tel_no'];
            $data['status'] = $input['activation'];
            MemberInfo::where('id', $id)->update($data);
            DB::table('member_hall_settings')->where('member_info_id', '=', $id)->delete();
            if (isset($input['year']) && !empty($input['year'])) {
                foreach ($input['year'] as $key => $yearval) {
                    DB::table('member_hall_settings')->insert(['member_info_id' => $id, 'hall_setting_id' => $yearval]);
                }
            }
            if (isset($input['programmes']) && !empty($input['programmes'])) {
                MemberProgramme::where('member_info_id',$id)->delete();
                foreach ($input['programmes'] as $key => $programmeValue) {
                        MemberProgramme::insert(['member_info_id'=>$id,'programme_id'=>$programmeValue]);
                }
            }
        }
        return redirect()->route('admin.members.index')->with('message', 'Member update successfully.');
    }

    public function updateMemberPassword(Request $request, $id)
    {

        $member = MemberInfo::select('id', 'user_id')->where('id', $id)->first();
        $user = User::where('id', $member->user_id)->first();
        if (isset($user) && !empty($user)) {
            $user->update(['password' => Hash::make($request->password)]);
        }
        if (isset($request->send_email) && !empty($request->send_email)) {
            $details = ['type' => 'UpdatePassword', 'email' => $user->email, 'mailInfo' => $user];
            SendEmailJob::dispatchNow($details);
        }
        return redirect()->route('admin.memberDetail', [$id, 'account'])->with('success', 'MemberInfo password updated successfully!');
    }

    public function updateMemberSettings(Request $request, $id)
    {
		$member = MemberInfo::find($id);
		if(!empty($member)) {
			$push_notification = $request->push_notification??'No';
			$member->language=$request->language;
			$member->push_notification=$push_notification;
			$member->save();
			User::where('id', $member->user_id)->update(['push_notification'=>$push_notification]);
		}
        return redirect()->route('admin.memberDetail', [$id, 'settings'])->with('success', 'MemberInfo settings updated successfully!');
    }

    public function userstatusChange(Request $request, $id, $status)
    {
        $member = MemberInfo::select('id', 'user_id')->where('id', $id)->first();
        $user = User::where('id', $member->user_id)->first();
        if (isset($user) && !empty($user)) {
            $user->update(['status' => $request->status]);
        }
        return redirect()->route('admin.memberDetail', [$id, 'show'])->with('success', 'MemberInfo status updated successfully!');
    }

    public function memberstatusChange(Request $request, $id, $status)
    {
        $member = MemberInfo::select('id', 'user_id')->where('id', $id)->first();
        if (isset($member) && !empty($member)) {
            $member->update(['status' => $request->status]);
        }
        return redirect()->route('admin.memberDetail', [$id, 'show'])->with('success', 'MemberInfo status updated successfully!');
    }


   public function memberDetail(Request $request , $id ,$type){
    $dataId = $id;
    $dataType = $type;
    if ($dataType=="show") {
        $headerTitle = "Member Account Details";
    }elseif($dataType=="edit"){
        $headerTitle = "Member Account Details";
    }elseif($dataType=="account"){
        $headerTitle = "Member Account Details";
    }elseif($dataType=="programme"){
        $headerTitle = "Member Programme Details";
    }elseif($dataType=="hall-booking"){
        $headerTitle = "Member Hall Booking Details";
    }elseif($dataType=="evnet-booking"){
        $headerTitle = "Member Event Booking Details";
    }elseif($dataType=="private-event-booking"){
        $headerTitle = "Member Private Event Booking Details";
    }elseif($dataType=="settings"){
        $headerTitle = "Member Profile";
    }
	else{
        return redirect()->route('admin.members.index');
    }

        $MemberInfo = MemberInfo::find($id);
        $years = HallSetting::where('status', 1)->get();
        $findyear = DB::table('member_hall_settings')->where('member_info_id', '=', $id)->get();

        $yaearhallsettingdata = DB::table('member_hall_settings')->where('member_info_id', $id)->distinct()->pluck('hall_setting_id')->toArray();
        if (count($yaearhallsettingdata)) {
            $yeardata = HallSetting::whereIn('id', $yaearhallsettingdata)->get();
        }
        $yeardata = HallSetting::get();
        $getMemberprogramme = MemberProgramme::where('member_info_id', '=', $id)->pluck('programme_id')->toArray();
        if(count($getMemberprogramme)){
             $programme = Programme::whereIn('id', $getMemberprogramme)->get();
        }
        $programme = Programme::get();
        if (!empty($MemberInfo)) {
            $countries = Country::where('status', 1)->get();
            return view('admin.members.comman', compact('headerTitle', 'MemberInfo', 'dataId', 'dataType', 'countries', 'yeardata', 'yaearhallsettingdata','getMemberprogramme','programme'));
        } else {
            return redirect()->route('admin.members.index');
        }
    }

    public function destroy($id)
    {
        if (isset($id)) {
            $member = MemberInfo::where('id', $id)->first();
            if (isset($member->user_id)) {
                User::where('id', $member->user_id)->delete();
            }
        }
        MemberInfo::where('id', $id)->delete();
        return redirect()->route('admin.members.index')->with('success', 'Member deleted successfully');
    }

    public function multipleusersdelete(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $member) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    $membsers = MemberInfo::withTrashed()->where('id', $member)->first();
                    $users = User::withTrashed()->where('id', $membsers->user_id)->first();
                    $membsers->delete();
                    $users->delete();
                } elseif (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'fdelete') {
                    $membsers = MemberInfo::withTrashed()->where('id', $member)->first();
                    $users = User::withTrashed()->where('id', $membsers->user_id)->first();
                    $membsers->forceDelete();
                    $users->forceDelete();
                } elseif (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'restore') {
                    $membsers = MemberInfo::withTrashed()->where('id', $member)->first();
                    $users = User::withTrashed()->where('id', $membsers->user_id)->first();
                    $membsers->update(['deleted_at' => null]);
                    $users->update(['deleted_at' => null]);
                } elseif (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'inactive') {
                    MemberInfo::withTrashed()->where('id', $member)->update(['status' => '0']);
                } elseif (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'enable') {
                    $memberInof = MemberInfo::withTrashed()->select('id', 'status', 'user_id')->where('id', $member)->first();
                    User::where('id', $memberInof->user_id)->update(['status' => '1']);
                } elseif (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'disable') {
                    $memberInof = MemberInfo::withTrashed()->select('id', 'status', 'user_id')->where('id', $member)->first();
                    User::where('id', $memberInof->user_id)->update(['status' => '0']);
                } else {
                    MemberInfo::withTrashed()->where('id', $member)->update(['status' => '1']);
                }
            }
        }
        return redirect()->back();
    }

    public function memberimageChange(Request $request, $id)
    {
        $member = MemberInfo::where('id', $id)->first();
        $image = '';
        if (!empty($request->file('file'))) {
            $files = $request->file('file');
            $image = $this->uploadSingleImage($files, 'member', '');
            if (isset($member->getImageBankDetail->id) && !empty($member->getImageBankDetail->id)) {
                $imageProfile = ImageBank::where('id', $member->getImageBankDetail->id)->first();
                $imageProfile->update(['profile_image' => $image]);
            } else {
                $imagemember = new ImageBank();
                $imagemember['application_id'] = $member->application_number;
                $imagemember['qr_code'] = $member->application_number;
                $imagemember['profile_image'] = $image;
                $imagemember->save();
                $member->update(['image_bank_id' => $imagemember->id]);
            }

        }
        return $image;
    }

    public function memberselectstatuschange(Request $request, $id)
    {
        $member = MemberInfo::select('id', 'user_id')->where('id', $id)->first();
        $user = User::where('id', $member->user_id)->first();
        if (isset($user) && !empty($user)) {
            $user->update(['status' => $request->status]);
        }
        return redirect()->route('admin.memberDetail', [$id, 'show'])->with('success', 'MemberInfo status updated successfully!');
    }
    public function memberinfostatus(Request $request, $id)
    {
        $member = MemberInfo::select('id', 'user_id')->where('id', $id)->first();
        if (isset($member) && !empty($member)) {
            $member->update(['status' => $request->status]);
        }
        return redirect()->route('admin.memberDetail', [$id, 'show'])->with('success', 'MemberInfo status updated successfully!');
    }

    public function imageRenameFolder()
    {
        $memberList = MemberInfo::get();
        if (!empty($memberList)) {
            foreach ($memberList as $member) {
                if (!empty($member->image_bank_id) && !is_null($member->image_bank_id)) {
                    $imageBank = ImageBank::where('id', $member->image_bank_id)->first();
                    $application_id = @$imageBank->application_id;
                    $imgArray = explode(DIRECTORY_SEPARATOR, @$imageBank->profile_image);
                    if (isset($imgArray[2]) && !empty($imgArray[2]) && isset($application_id) && !empty($application_id)) {
                        $imgExtArray = explode(".", @$imgArray[2]);
                        if (isset($imgExtArray[1]) && !empty($imgExtArray[1])) {
                            $imgExt = $imgExtArray[1];
                            $newImageName = $application_id . "." . $imgExt;

                            $oldPath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR . $imgArray[2]);
                            $newPath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR . $newImageName);
                            if (file_exists($oldPath)) {
                                rename($oldPath, $newPath);
                            }

                            $data = array();
                            $data['profile_image'] = DIRECTORY_SEPARATOR . "profile" . DIRECTORY_SEPARATOR . $newImageName; // \profile\abc.jpg
                            ImageBank::where('application_id', $application_id)->update($data);
                        }
                    }
                }
            }
        }
        @dd('script end');
    }

}
