<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use Auth;

class RoleManagement extends Component
{
    use WithPagination;

    public $search;

    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $searchkey = trim($this->search);
        $pageInfo['title'] = "Role";
        $pageInfo['page_title'] = "Role";
        $userinfo = Auth::user();
        $userinfo = Auth::user();
        if ($userinfo->hasRole('Super Admin')){
            $roles = Role::whereNotIn('id',['1']);
        }elseif ($userinfo->hasRole('Admin')){
            $roles = Role::whereNotIn('id',['1','3','4','5','6']);
        }else{
            $roles = Role::whereNotIn('id',['1','3','5','6']);
        }
        $roles = $roles->Where(function ($q) use ($searchkey) {
            $q->where('name', 'like', '%' . $searchkey . '%');
        })->orderBy('id', 'DESC')->paginate(50);
        return view('livewire.role.index', compact('roles'));
    }
}
