<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageInfo['title'] = "Role";
        $pageInfo['page_title'] = "Role";
        $searchkey = $request->input('s');
        $userinfo = Auth::user();
        if ($userinfo->hasRole('Super Admin')){
            $roles = New Role;
        }elseif ($userinfo->hasRole('Admin')){
            $roles = Role::whereNotIn('id',['1']);
        }else{
            $roles = Role::whereNotIn('id',['1','2']);
        }
        $roles = $roles->Where(function ($q) use ($searchkey) {
            $q->where('name', 'like', '%' . $searchkey . '%');
        })->orderBy('id', 'DESC')->paginate(env('PAGINATION_VAL'));
        return view('admin.roles.index', compact('roles','searchkey'))->with('i', ($request->input('page', 1) - 1) * env('PAGINATION_VAL'))->with($pageInfo);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        $pageInfo['title'] = "Role";
        $pageInfo['page_title'] = "Role";
        return view('admin.roles.create', compact('permission'))->with($pageInfo);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleStoreRequest $request)
    {
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();
        $pageInfo['title'] = "Role";
        $pageInfo['page_title'] = "Role";

        return view('admin.roles.show', compact('role', 'rolePermissions'))->with($pageInfo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $pageInfo['title'] = "Role";
        $pageInfo['page_title'] = "Role";
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('admin.roles.edit', compact('role', 'permission', 'rolePermissions'))->with($pageInfo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, $id)
    {
        $check_name = Role::where('name',$request->name)->where('id','!=',$id)->first();
        if(empty($check_name)){
            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->save();
            $role->syncPermissions($request->input('permission'));
            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
        }else{
            return redirect()->back()->withError('This name is already exist');
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
        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully');
    }
}
