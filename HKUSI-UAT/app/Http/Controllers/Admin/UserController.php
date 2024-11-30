<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Country;
use App\Jobs\SendEmailJob;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:admin-user-list|admin-user-create|admin-user-edit|admin-user-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:admin-user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:admin-user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:admin-user-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $headerTitle = "User List";
        return view('admin.users.index', compact('headerTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles  = Role::where('id', 1)->get();
        $headerTitle = "Create User";
        return view('admin.users.create', compact('roles', 'headerTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Validate the form data
        $this->validate($request, [
            'email' => 'required|email|unique:users,email',
            'title' => 'required',
            'gender' => 'required',
            'surname' => 'required',
            'given_name' => 'required',
            'mobile_tel_no' => 'required',
            'department' => 'required',
            'role' => 'required',
        ]);

        // Create a new user
        $newUser = [];
        $newUser['name'] = $request->surname.' '.$request->given_name;
        $newUser['email'] = $request->email;
        $newUser['password'] = Hash::make('Password!1');
        $newUser['title'] = $request->title;
        $newUser['gender'] = $request->gender;
        $newUser['surname'] = $request->surname;
        $newUser['given_name'] = $request->given_name;
        $newUser['mobile_tel_no'] = $request->mobile_tel_no;
        $newUser['department'] = $request->department;
        $newUser['location'] = $request->location;
        $user = User::create($newUser);

        // Assign the selected role to the user
        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user   = User::find($id);
        $roles  = Role::where('id', 1)->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . $id,
            'title' => 'required',
            'gender' => 'required',
            'surname' => 'required',
            'given_name' => 'required',
            'mobile_tel_no' => 'required',
            'department' => 'required',
            'role' => 'required',
        ]);

        $input = $request->all();
        $input['name'] = $input['surname'].' '.$input['given_name'];
        $user = User::find($id);
        $user->update($input);

        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($input['role']);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }

    public function statusChange($id, $status)
    {
        $data['status'] = $status;
        User::where('id', $id)->update($data);
        return redirect()->back()->with('success', 'Status updated successfully');
    }

    public function memberstatusChange(Request $request, $id)
    {
        $member = User::where('id', $id)->first();
        if (isset($member) && !empty($member)) {
            $member->update(['status' => $request->status]);
        }
        return redirect()->route('admin.userDetails', [$id, 'show'])->with('success', 'User status updated successfully!');
    }

	public function updateSettings(Request $request, $id)
    {
		$push_notification = $request->push_notification??'No';
		$admin_app_permission = $request->admin_app_permission??0;
		$admin_panel_permission = $request->admin_panel_permission??0;
		User::where('id', $id)->update(['push_notification'=>$push_notification,'admin_app_permission'=>$admin_app_permission,'admin_panel_permission'=>$admin_panel_permission]);
        return redirect()->route('admin.userDetails', [$id, 'settings'])->with('success', 'User settings updated successfully!');
    }

    public function updateUserPassword(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        if (isset($user) && !empty($user)) {
            $user->update(['password' => Hash::make($request->password)]);
        }
        if (isset($request->send_email) && !empty($request->send_email)) {
            $details = ['type' => 'UpdatePassword', 'email' => $user->email, 'mailInfo' => $user];
            SendEmailJob::dispatchNow($details);
        }
        return redirect()->route('admin.userDetails', [$id, 'account'])->with('success', 'User password updated successfully!');
    }


    public function userDetails(Request $request, $id, $type)
    {
        $dataId = $id;
        $dataType = $type;
        if ($dataType == "show") {
            $headerTitle = "User Profile";
        } elseif ($dataType == "edit") {
            $headerTitle = "User Account Details";
        } elseif ($dataType == "account") {
            $headerTitle = "User Account Details";
        } elseif ($dataType == "programme") {
            $headerTitle = "User Programme Details";
        } elseif ($dataType == "hall-booking") {
            $headerTitle = "User Hall Booking Details";
        } elseif ($dataType == "settings") {
            $headerTitle = "User Profile";
        } else {
            return redirect()->route('admin.users.index');
        }
        $UserInfo = User::find($id);
        if (!empty($UserInfo)) {
            $roles  = Role::where('id', 1)->get();
            return view('admin.users.comman', compact('headerTitle', 'UserInfo', 'dataId', 'dataType','roles'));
        } else {
            return redirect()->route('admin.users.index');
        }
    }

    public function userselectstatuschange(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        if (isset($user) && !empty($user)) {
            $user->update(['status' => $request->status]);
        }
        return redirect()->route('admin.memberDetail', [$id, 'show'])->with('success', 'User status updated successfully!');
    }

    public function userinfostatus(Request $request, $id)
    {
        $member = User::where('id', $id)->first();
        if (isset($member) && !empty($member)) {
            $member->update(['status' => $request->status]);
        }
        return redirect()->route('admin.memberDetail', [$id, 'show'])->with('success', 'User status updated successfully!');
    }

    public function multipleusersdelete(Request $request)
	{
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $member) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    $users = User::withTrashed()->where('id', $member)->first();
                    $users->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'fdelete') {
                    $users = User::withTrashed()->where('id', $member)->first();
                    $users->forceDelete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'restore'){
                    $users = User::withTrashed()->where('id', $member)->first();
                    $users->update(['deleted_at'=>null]);
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'inactive'){
                    User::withTrashed()->where('id', $member)->update(['status'=>'0']);
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'enable'){
                    User::where('id',$member)->update(['status'=>'1']);
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'disable'){
                    User::where('id',$member)->update(['status'=>'0']);
                }else{
                    User::withTrashed()->where('id', $member)->update(['status'=>'1']);
                }
            }
        }
		return redirect()->back();
	}

    public function userstatusChange($id, $status) {  
        $user = User::where('id',$id)->first();
        if (isset($user) && !empty($user)) {
            $user->update(['status' => $status]);
        }
        return redirect()->route('admin.userDetails',[$id,'show'])->with('success', 'User status updated successfully!');        
    }
}
