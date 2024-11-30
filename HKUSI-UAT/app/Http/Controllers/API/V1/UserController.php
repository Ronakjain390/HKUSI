<?php
namespace App\Http\Controllers\API\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;
use App\Traits\VerifyTokenStatus;
use App\Traits\UploadTraits;
use App\Models\User;
use App\Models\MemberInfo;
use App\Models\StudentAppVersion;
use App\Models\Country;
use App\Models\ImageBank;
use App\Models\StudentNotification;
use App\Models\StudentNotificationInfo;
use App\Jobs\SendEmailJob;
use DateTime;
use Carbon\Carbon;
use Exception, Validator, DB, Storage, Config;

class UserController extends Controller
{
    use VerifyTokenStatus;
    use UploadTraits;
    protected $loginField;
    protected $loginValue;

    public function __construct()
    {
        $this->apiArray = array();
        $this->apiArray['error'] = true;
        $this->apiArray['message'] = '';
        $this->apiArray['errorCode'] = 4;
        // $this->DISK_NAME = Config::get('DISK_NAME');
    }

    public function getAppVersion()
    {
        try {
            $this->apiArray['state'] = 'AppVersion';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/

            $appVersion = StudentAppVersion::latest()->first();
            if(!empty($appVersion)){
				$data = $appVersion;            
				$this->apiArray['data'] = $data;
				$this->apiArray['message'] = 'Success';
				$this->apiArray['errorCode'] = 0;
				$this->apiArray['error'] = false;
				return response()->json($this->apiArray, 200);
            }

            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);


            // $this->response['data'] = StudentAppVersion::latest()->first();
            // $this->response['message'] = 'App Version';
            // return response()->json($this->response);
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }

    /* User Login API on 10/11/2022 by Ashish Gupta */
	public function loginUser(Request $request){
		// echo $password = Hash::make('Password!1');  die;
        //try{
    		$this->apiArray['state'] = 'userLogin';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $inputs = $request->all();
    		$validator = Validator::make($inputs, [
    			'email' => 'required|email',
                'password' => 'required',
    	    ]);
    		if($validator->fails()){
    			$this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
    			$this->apiArray['error'] = true;
    			return response()->json($this->apiArray, 200);
    		}

            if (!Auth::attempt(array_merge($request->only('email', 'password')))){
                $this->apiArray['message'] = 'Email & Password does not match with our record.';
                $this->apiArray['errorCode'] = 3;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            $loginuserinfo = Auth::user();
            if ($loginuserinfo->hasRole('Member')){
                $memberdetails = MemberInfo::where('user_id',$loginuserinfo->id)->first();
                if (!isset($memberdetails->status)) {
                    Auth::user()->tokens()->delete();
                    $this->apiArray['message'] = 'Email & Password does not match with our record.';
                    $this->apiArray['errorCode'] = 3;
                    $this->apiArray['error'] = true;
                    return response()->json($this->apiArray, 200);
                }
                if ($memberdetails->status == '0') {
                    Auth::user()->tokens()->delete();
                    $this->apiArray['message'] = 'Your account is inactive. Please active your account.';
                    $this->apiArray['errorCode'] = 3;
                    $this->apiArray['error'] = true;
                    return response()->json($this->apiArray, 200);
                }
                if ($loginuserinfo->status == '0') {
                    Auth::user()->tokens()->delete();
                    $this->apiArray['message'] = 'Your account is disabled';
                    $this->apiArray['errorCode'] = 3;
                    $this->apiArray['error'] = true;
                    return response()->json($this->apiArray, 200);
                }
                $message = 'User login sucessfully.';
                $loginuserinfo->tokens->each(function($token, $key) {
                    $token->delete();
                });
                // dummy token
                $token = $loginuserinfo->createToken($inputs['email']);
                $expireDate = $expire_date = '';
                if(isset($loginuserinfo->getMemberInfo->getMemberProgrammeDetail) && count($loginuserinfo->getMemberInfo->getMemberProgrammeDetail)){
                    foreach ($loginuserinfo->getMemberInfo->getMemberProgrammeDetail as $key => $value) {
                        if(isset($value->getProgrammeDetail) && !empty($value->getProgrammeDetail->programme_code)){
                            $code[] = $value->getProgrammeDetail->programme_code;
                            if(!empty($value->getProgrammeDetail->end_date) && $value->getProgrammeDetail->end_date>$expire_date){
                                $expire_date = $value->getProgrammeDetail->end_date;
                            }
                        }
                    }
                }
                if(!empty($expire_date)){
                    $expireDate = date('Y-m-d',$expire_date);
                }
                $data = array(
                    'name'           => $loginuserinfo->name,
                    'surname'        => $loginuserinfo->getMemberInfo->surname,
                    'mobile_number'  => $loginuserinfo->getMemberInfo->mobile_tel_no,
                    'gender'         => $loginuserinfo->getMemberInfo->gender,
                    'expire_date'    => $expireDate,
                    'chinese_name'   => $loginuserinfo->getMemberInfo->chinese_name,
                    'email'          => $loginuserinfo->email,
                );
                $booking = false;
                $age = 0;
                if (isset($userinfo->getMemberInfo->date_of_birth) && !empty($userinfo->getMemberInfo->date_of_birth)) {
                    $dob = date('Y-m-d',Auth::user()->getMemberInfo->date_of_birth);
                    if(!empty($dob)){
                        $birthdate = new DateTime($dob);
                        $today   = new DateTime('today');
                        $age = $birthdate->diff($today)->y;
                    }
                    if ($age >= 15) {
                        $booking = true;
                    }else{
                        $booking = false;
                    }
                }
                $this->apiArray['data'] = $data;
                $this->apiArray['ageLimit'] = $booking;
                $this->apiArray['token'] = $token->plainTextToken;
                $this->apiArray['message'] = 'Login sucessfully.';
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }
            $this->apiArray['message'] = 'You are not authorized to login in this site.';
            $this->apiArray['errorCode'] = 3;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);                      
        /*}catch (\Exception $e){
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }*/
	}
    /* End */

    /* User Register */ 
    public function createMember(Request $request){
        try{
            $this->apiArray['state'] = 'register';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $inputs = $request->all();            
            $validator = Validator::make($inputs, [
                'application_id' => ['required', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'password' => 'required',
            ]);
            if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            $existinguser=User::where('email',$inputs['email'])->first();
            if(!empty($existinguser)){
                $member = MemberInfo::where('user_id',$existinguser->id)->first();
                if ($request->application_id != $member->application_number) {
                    $this->apiArray['message'] = 'Application id does not match with our record.';
                    $this->apiArray['errorCode'] = 3;
                    $this->apiArray['error'] = true;
                    return response()->json($this->apiArray, 200);
                }
                if ($member->status == '1') {
                    $this->apiArray['message'] = 'Your account already activated. Please login.';
                    $this->apiArray['errorCode'] = 3;
                    $this->apiArray['error'] = true;
                    return response()->json($this->apiArray, 200);
                }
                $existinguser->update(['password' => Hash::make($request->password)]);
                $member->update(['status'=>'1']);
                event(new Registered($request));

                if (!Auth::attempt(array_merge($request->only('email', 'password')))){
                    $this->apiArray['message'] = 'Email & Password does not match with our record.';
                    $this->apiArray['errorCode'] = 3;
                    $this->apiArray['error'] = true;
                    $this->apiArray['data'] = null;
                    return response()->json($this->apiArray, 401);
                }
                $loginuserinfo = Auth::user();
                if ($loginuserinfo->hasRole('Member')){
                    if ($loginuserinfo->status == '0') {
                        Auth::user()->tokens()->delete();
                        $this->apiArray['message'] = 'Your account is disabled';
                        $this->apiArray['errorCode'] = 3;
                        $this->apiArray['error'] = true;
                        return response()->json($this->apiArray, 200);
                    }

                    $mailInfo = [
                        'given_name'     => $existinguser->getMemberInfo->given_name,
                        'application_number' => $existinguser->getMemberInfo->application_number,
                    ];
                    $welcome = ['type'=>'AccountActivation','email' => $request->email,'mailInfo' => $mailInfo];
                    SendEmailJob::dispatchNow($welcome);

                    $message = 'User login sucessfully.';
                    
                    $loginuserinfo->tokens->each(function($token, $key) {
                        $token->delete();
                    });
                    // dummy token
                    $token = $loginuserinfo->createToken($inputs['email']);
                    $data = array(
                        'name'           => $loginuserinfo->name,
                        'surname'        => $loginuserinfo->getMemberInfo->surname,
                        'mobile_number'  => $loginuserinfo->getMemberInfo->mobile_tel_no,
                        'gender'         => $loginuserinfo->getMemberInfo->gender,
                        'chinese_name'   => $loginuserinfo->getMemberInfo->chinese_name,
                        'email'          => $loginuserinfo->email,
                    );
                    $this->apiArray['data'] = $data;
                    $this->apiArray['token'] = $token->plainTextToken;
                    $this->apiArray['message'] = 'Account activated successfully.';
                    $this->apiArray['errorCode'] = 0;
                    $this->apiArray['error'] = false;
                    return response()->json($this->apiArray, 200);
                }
                // $this->apiArray['message'] = 'Account activated successfully.';              
                // $this->apiArray['errorCode'] = 0;
                // $this->apiArray['data'] = NULL;
                // $this->apiArray['error'] = false;
            }else{
                $this->apiArray['message'] = 'Details does not exist.';
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['data'] = NULL;
                $this->apiArray['error'] = true;
            }
            return response()->json($this->apiArray, 200);
        }catch (\Exception $e){
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 500);
        }
    }
    /* End */


    /* Get Profile API on 16/03/2023 by Vinod */
    public function getMemberProfile(Request $request){
        try {
            $inputs = $request->all();
             $userinfo = $request->user('sanctum');
            $this->apiArray['state'] = 'myAccount';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $DISK_NAME = Config::get('DISK_NAME');
            // $imageBank = ImageBank::where('application_id',$userinfo->getMemberInfo->application_id)->first();
            //dd($userinfo->getMemberInfo->getImageBankDetail->profile_image);
            $data = array(
                'user_id'        => $userinfo->id,
                'application_id' => $userinfo->getMemberInfo->application_number,
                'given_name'     => $userinfo->getMemberInfo->given_name,
                'surname'        => $userinfo->getMemberInfo->surname,
                'email'          => $userinfo->email,
                'contact_tel_no' => $userinfo->getMemberInfo->contact_tel_no,
                'customer_type'  => str_replace(array('"','[',']'),"",$userinfo->getRoleNames()),
                'gender'         => $userinfo->getMemberInfo->gender,
                'push_notification'=> $userinfo->getMemberInfo->push_notification,
                'profile_image'  => (isset($userinfo->getMemberInfo->getImageBankDetail->profile_image) && $userinfo->getMemberInfo->getImageBankDetail->profile_image != '' && Storage::disk($DISK_NAME)->exists($userinfo->getMemberInfo->getImageBankDetail->profile_image))?asset(Storage::url($userinfo->getMemberInfo->getImageBankDetail->profile_image)):asset('img/default-image.jpg'),
            );
            $this->apiArray['data'] = $data;
            $this->apiArray['message'] = 'Success';
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['error'] = false;
            return response()->json($this->apiArray, 200);
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    public function getNotification(Request $request, $type){
        try {

            $inputs = $request->all();
            $this->apiArray['state'] = 'getNotification';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/

			$user = $request->user();
			if($type == 'list'){
                $notifications = StudentNotificationInfo::with('getStudentNotification')->where('user_id', $user->id)->latest()->get();
				// Transform the collection to include the status in the main array
				$notifications = $notifications->map(function ($notification) {
					return [
						"id"=> $notification->id,
						"title"=> $notification->getStudentNotification->title,
						"short_description"=> $notification->getStudentNotification->short_description,
						"long_description"=> $notification->getStudentNotification->long_description,
						"status"=> $notification->getStudentNotification->status==0?"Disable":"Enable",
						"read"=> $notification->read,
						"created_at"=> $notification->created_at,
						"updated_at"=> $notification->updated_at
					];
				});
				
                $this->apiArray['data'] = $notifications;
                $this->apiArray['message'] = 'Success';
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }

            elseif($type == 'unread'){
                $notification = StudentNotificationInfo::where('user_id', $user->id)->where('read', 'No')->count();
                $data = array(
                    'unread'        => $notification,
                );
                $this->apiArray['data'] = $data;
                $this->apiArray['message'] = 'Success';
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }

            else{
                $notification = StudentNotificationInfo::with('getStudentNotification')->find($type);
				if(empty($notification)) {
					$this->apiArray['message'] = 'Notification info not found.';
					$this->apiArray['errorCode'] = 4;
					$this->apiArray['error'] = true;
					$this->apiArray['data'] = null;
					return response()->json($this->apiArray, 200);
				}

                $this->apiArray['data'] = [
					"id"=> $notification->id,
					"title"=> $notification->getStudentNotification->title,
					"short_description"=> $notification->getStudentNotification->short_description,
					"long_description"=> $notification->getStudentNotification->long_description,
					"status"=> $notification->getStudentNotification->status==0?"Disable":"Enable",
					"read"=> $notification->read,
					"created_at"=> $notification->created_at,
					"updated_at"=> $notification->updated_at
				];
                $this->apiArray['message'] = 'Success';
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }

            
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    /* End */

    public function updateNotification(Request $request)
    {
        try{
            $this->apiArray['state'] = 'updateNotification';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
			
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
			if ($validator->fails()) {
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
                      
            $inputs = $request->all();
            $data = StudentNotificationInfo::where('id',$inputs['id'])->update([ 'read' => 'Yes']);           
            $this->apiArray['message'] = 'Status updated successfully.';
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['error'] = false;
            return response()->json($this->apiArray, 200);

            }catch (\Exception $e){
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 500);
        }
    }

    /* Update Profile API on 16/03/2023 by Vinod */
    public function updateMemberProfile(Request $request)
    {
        try{
            $this->apiArray['state'] = 'updateProfile';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
			$CustomImgErrMsg = array(  
                'profile_image.mimes' => 'Profile image type must be JPEG',
				'profile_image.max' => 'The profile image must be 5MB or below',
				'profile_image.dimensions' => 'Profile image dimensions must be at least 1200Px(W) X 1600px(H)',
            );
            $validator = Validator::make($request->all(), [
                'contact_tel_no' => 'required',
                'profile_image'  => 'mimes:jpeg,jpg|max:5120|dimensions:min_width=1200,min_height=1600|nullable',
            ],$CustomImgErrMsg);
			if ($validator->fails()) {
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            $inputs = $request->all();
            $userinfo = $request->user('sanctum');
            $profile_image = '';
            $member  = [];
            $member['contact_tel_no']   = $inputs['contact_tel_no'];
            MemberInfo::where('id',$userinfo->getMemberInfo->id)->update($member);            
            $DISK_NAME = Config::get('DISK_NAME');
            if(isset($inputs["profile_image"]) && !empty($inputs['profile_image'])){
                $files = $request->file('profile_image');
                $profile_image = $this->uploadSingleImage($files,'profile','',$userinfo->getMemberInfo->application_number);
                if ($profile_image != "") {    
                    if (isset($userinfo->getMemberInfo->image_bank_id) && !empty($userinfo->getMemberInfo->image_bank_id)) {
                        $exitImage = ImageBank::where('id',$userinfo->getMemberInfo->image_bank_id)->delete();
                    }
                    $imagesave =  ImageBank::create([
                        'profile_image' => $profile_image,
                        'application_id' => $userinfo->getMemberInfo->application_number,
                    ]);
                   MemberInfo::where('user_id',$userinfo->id)->update(['image_bank_id'=>$imagesave->id]);
                }
            }else{
                $profile_image = isset($userinfo->getMemberInfo->getImageBankDetail->profile_image)?$userinfo->getMemberInfo->getImageBankDetail->profile_image:'';
            }
            $data = array(
                'id'                 => $userinfo->id,
                'given_name'         => $userinfo->getMemberInfo->given_name,
                'surname'            => $userinfo->getMemberInfo->surname,
                'email'              => $userinfo->email,
                'contact_tel_no'     => $inputs['contact_tel_no'],
                'gender'             => $userinfo->getMemberInfo->gender,
                'profile_image'  => ($profile_image != '' && Storage::disk($DISK_NAME)->exists($profile_image))?asset(Storage::url($profile_image)):asset('img/default-image.jpg'),
            );
            $this->apiArray['data'] = $data;
            $this->apiArray['message'] = 'Profile updated successfully.';
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['error'] = false;
            return response()->json($this->apiArray, 200);
        }catch (\Exception $e){
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 500);
        }
    }
    /* End */

    /* Update Profile image API on 16/03/2023  by Vinod*/
    public function updateProfileImage(Request $request)
    {
        try{
            $this->apiArray['state'] = 'updateProfileImage';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $userinfo = $request->user('sanctum');

            $validator = Validator::make($request->all(), [
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1028'
            ]);
            if ($validator->fails()) {
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            $imageBank = ImageBank::where('application_id',$userinfo->getMemberInfo->application_number)->first();
            $profile_image = '';
            $files = $request->file('profile_image');
            $profile_image = $this->uploadSingleImage($files,'profile','',$userinfo->getMemberInfo->application_number);
            if ($profile_image != "") {
                $imageBank->fill([
                    'profile_image' => $profile_image,
                ])->save();
            }
            $DISK_NAME = Config::get('DISK_NAME');      
            $data = array(               
                'profile_image'      => ($profile_image != '' && Storage::disk($DISK_NAME)->exists($profile_image))?asset(Storage::url($profile_image)):'',
            );
            $this->apiArray['data'] = $data;
            $this->apiArray['message'] = 'Profile Image updated successfully.';
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['error'] = false;
            return response()->json($this->apiArray, 200);
        }catch (\Exception $e){
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    
    /* End */

    public function updateSettings(Request $request)
    {
        try{
            $this->apiArray['state'] = 'updateSettings';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
			
            $validator = Validator::make($request->all(), [
                'push_notification' => 'required',
            ]);
			if ($validator->fails()) {
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            
            $userinfo = $request->user('sanctum');
            if(!empty($userinfo)){
            $inputs = $request->all();
            $member  = [];
            $member['push_notification']   = $inputs['push_notification'];
            MemberInfo::where('id',$userinfo->getMemberInfo->id)->update($member);           
            
            $user = $request->user();
            $user->push_notification = $request->push_notification;
            $user->save();

            $data = array(
                'push_notification'  =>$member['push_notification'],
            );
            
            $this->apiArray['data'] = $data;
            $this->apiArray['message'] = 'Profile updated successfully.';
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['error'] = false;
            return response()->json($this->apiArray, 200);
            }else{
                $this->apiArray['message'] = 'User Not Found';
                $this->apiArray['errorCode'] = 4;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 500);  
            }
        }catch (\Exception $e){
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 500);
        }
    }

    /* User Login API on 10/11/2022 by Ashish Gupta */
    public function forgotPassword(Request $request)
    {
        try{
            $this->apiArray['state'] = 'forgotPassword';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $inputs = $request->all();
            $validator = Validator::make($inputs, [
                'email' => 'required',
                'reset_link' => 'required'
            ]);
            if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }

            // $this->loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            // $this->loginValue = $request->email;
            // $request->merge([$this->loginField => $this->loginValue]);
            $userPassword = User::where('email',$request->email)->first();
            if(!empty($userPassword)){
                $token = Str::random(64);
                DB::table('password_resets')->where('email',$userPassword->email)->delete();
                DB::table('password_resets')->insert(['email'=>$userPassword->email,'token'=>$token]);
                $url = $inputs["reset_link"]."/?token=".$token;  
                $mailInfo = [
                    'email'          => $userPassword->email,
                    'given_name'     => $userPassword->getMemberInfo->given_name,
                    'application_number' => $userPassword->getMemberInfo->application_number,
                    'url'            => $url,
                ];
                $details = ['type'=>'ResetPasswordTemplate','email' => $userPassword->email,'mailInfo' => $mailInfo];
                //print_r($details);die();
                SendEmailJob::dispatchNow($details);
                $this->apiArray['message'] = 'An email sent to your email to reset password';
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }
            $this->apiArray['message'] = 'You are not register with us';
            $this->apiArray['errorCode'] = 2;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);                      
        }catch (\Exception $e){
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    /* End */

    
    /* resetPassword 24-01-2022 create by Rahul */
    public function resetPassword(Request $request)
    {
        try{
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*
            /*Check header */
            $inputs = $request->all();
            $validator = Validator::make($inputs, [
                'token'                 => 'required',
                'password'              => 'required|min:8',
                'confirm_password'      => 'required|min:8|same:password',
            ]);
            if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            $emailData = DB::table('password_resets')->where('token',$request->token)->first();
            if(!empty($emailData)){
                $user = User::where('email',$emailData->email)->first();
                if(!empty($user)){
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();
                    event(new PasswordReset($user));
                    DB::table('password_resets')->where('email',$emailData->email)->delete();
                }
                $this->apiArray['message'] = 'Your password is changed successfully.';
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }else{
                $this->apiArray['message'] = 'There is an error.';
                $this->apiArray['errorCode'] = 3;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }        
        }catch (\Exception $e){
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    /* End */

    /* changePassword 15-03-2023 create by vinod */
    public function changePassword(Request $request)
    {
        try{
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $userinfo = $request->user('sanctum');            
            $inputs = $request->all();   
            $validator = Validator::make($inputs, [
                'current_password'          => 'required',
                'password'                  => 'required|min:8',
                'confirm_password'          => 'required_with:password|same:password|min:8',
            ]);
            if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            if (Hash::check($request->current_password, $userinfo->password)) {
                $userinfo->fill(['password' => Hash::make($request->password)])->save();
                $details = ['type'=>'UpdatePassword','email' => $userinfo->email,'mailInfo' => $userinfo];
                SendEmailJob::dispatchNow($details);
                $this->apiArray['error'] = false;
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['message'] = "Password changed successfully.";
                return response()->json($this->apiArray, 200);
            }else {
                $this->apiArray['message'] = 'Current password not matched, please enter correct current password.';
                $this->apiArray['errorCode'] = 3;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
        }catch (\Exception $e){
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    /* End */

     /* Create get country data pi on 11-10-2022 by Ashish Gupta */
    public function countryList(Request $request)
    {
        try {
            $this->apiArray['state'] = 'getcountry';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $countryList = Country::select('id','name')->where('status','1')->get();
            $this->apiArray['data'] = $countryList;
            $this->apiArray['message'] = 'Success';
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['error'] = false;
            return response()->json($this->apiArray, 200);
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    /* End */ 

    /* Get Profile API on 16/03/2023 by Vinod */
    public function getProfileCard(Request $request){
        try {
            $inputs = $request->all();
             $userinfo = $request->user('sanctum');
            $this->apiArray['state'] = 'getProfileCard';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $DISK_NAME = Config::get('DISK_NAME');
            $code = array();
            $expireDate = $expire_date = '';
            if(isset($userinfo->getMemberInfo->getMemberProgrammeDetail) && count($userinfo->getMemberInfo->getMemberProgrammeDetail)){
                foreach ($userinfo->getMemberInfo->getMemberProgrammeDetail as $key => $value) {
                    if(isset($value->getProgrammeDetail) && !empty($value->getProgrammeDetail->programme_code)){
                        $code[] = $value->getProgrammeDetail->programme_code;
                        if(!empty($value->getProgrammeDetail->end_date) && $value->getProgrammeDetail->end_date>$expire_date){
                            $expire_date = $value->getProgrammeDetail->end_date;
                        }
                    }
                }
            }
            if(!empty($expire_date)){
                $expireDate = date('Y-m-d',$expire_date);
            }
            $data = array(
                'user_id'        => $userinfo->id,
                'application_id' => $userinfo->getMemberInfo->application_number,
                'given_name'     => $userinfo->getMemberInfo->given_name,
                'surname'        => $userinfo->getMemberInfo->surname,
                'code'           => $code,
                'expire_date'    => $expireDate,
                'profile_image'  => (isset($userinfo->getMemberInfo->getImageBankDetail->profile_image) && $userinfo->getMemberInfo->getImageBankDetail->profile_image != '' && !empty($userinfo->getMemberInfo->image_bank_id) && Storage::disk($DISK_NAME)->exists($userinfo->getMemberInfo->getImageBankDetail->profile_image))?asset(Storage::url($userinfo->getMemberInfo->getImageBankDetail->profile_image)):asset('img/default-image.jpg'),
                'logo_image'     => asset('img/logo.svg'),
            );
            $this->apiArray['data'] = $data;
            $this->apiArray['message'] = 'Success';
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['error'] = false;
            return response()->json($this->apiArray, 200);
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
	
	/* End */     
}   
