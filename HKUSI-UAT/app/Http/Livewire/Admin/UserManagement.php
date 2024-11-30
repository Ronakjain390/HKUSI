<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MemberInfo;
use App\Models\User;
use App\Models\Country;
use App\Jobs\SendEmailJob;

class UserManagement extends Component
{
    use WithPagination;

    public $search, $from, $to, $daterange = false, $daterange1 = false, $searchSubmit = false, $tabSearch = 4, $status = null, $trash = false, $totalEnabled, $totalTrash, $totalDisabled, $nationality, $gender, $study_country, $record_period, $start_date, $end_date, $order_by = 'DESC', $order_type = 'created_at', $paginate = '20', $language, $delete, $countMember;

    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFrom()
    {
        $this->resetPage();
    }

    public function updatingTo()
    {
        $this->resetPage();
    }

    public function render()
    {
        $members = (new User)->newQuery()->role('Super Admin')->withTrashed();

        if ($this->daterange == true) {
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d', strtotime($this->from)) . ' 00:00:00';
                $to_date    = date('Y-m-d', strtotime($this->to)) . ' 23:59:59';
                $members = $members->whereBetween('created_at', [$form_date, $to_date]);
            }
        }

        if ($this->daterange1 == true) {
            if (isset($this->nationality) && !empty($this->nationality)) {
                $members = $members->where('nationality', $this->nationality);
            }
            if (isset($this->gender) && !empty($this->gender)) {
                $members = $members->where('gender', $this->gender);
            }
            if (isset($this->study_country) && !empty($this->study_country)) {
                $members = $members->where('study_country', $this->study_country);
            }
            if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Basic') {
                $members = $members->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 00:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            } elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $members = $members->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            } elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $a = date('w');
                $members = $members->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime('- ' . $a . ' days')), date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d', strtotime('- ' . $a . ' days'));
                $this->to   = date('Y-m-d');
            } elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                $members = $members->whereBetween('created_at', [date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59')]);
                $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            } elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                    $form_start_date  = date('Y-m-d', strtotime($this->from)) . ' 00:00:00';
                    $to_end_date    = date('Y-m-d', strtotime($this->to)) . ' 23:59:59';
                    $members = $members->whereBetween('created_at', [$form_start_date, $to_end_date]);
                }
            }
        }
        if (isset($this->tabSearch) && $this->tabSearch == 1) {
            $members =  $members->where('status', '0');
        } elseif (isset($this->tabSearch) && $this->tabSearch == 2) {
            $members =  $members->where('status', '1');
        } elseif (isset($this->tabSearch) && $this->tabSearch == 3) {
            $members = $members->onlyTrashed();
        }

        if (isset($this->is_import) && $this->is_import != '') {
            $members =  $members->where('is_import', $this->is_import);
        }
        if (isset($this->language) && $this->language != '') {
            $members = $members->where('language', $this->language);
        }
        if (isset($this->status) && $this->status != '') {
            $members =  $members->where('status', $this->status);
        }

        if ($this->searchSubmit == true) {
            $date = date('Y-m-d h:i:s', strtotime($this->search));
            $members = $members->where('application_number', 'like', '%' . $this->search . '%')
                ->orwhere('email', 'like', '%' . trim($this->search) . '%')
                ->orwhere('title', 'like', '%' . trim($this->search) . '%')
                ->orwhere('gender', 'like', '%' . trim($this->search) . '%')
                ->orwhere('surname', 'like', '%' . trim($this->search) . '%')
                ->orwhere('given_name', 'like', '%' . trim($this->search) . '%')
                ->orwhere('mobile_tel_no', 'like', '%' . trim($this->search) . '%')
                ->orwhere('department', 'like', '%' . trim($this->search) . '%');
        }

        $members = $members->orderBy($this->order_type, $this->order_by);

        $members = $members->paginate($this->paginate);
        $this->totalEnabled = User::Role('Super Admin')->where('status', '1')->count();
        $this->totalDisabled = User::Role('Super Admin')->where('status', '0')->count();
        $this->totalTrash = User::onlyTrashed()->count();
        $this->countMember = User::count();
        return view('livewire.admin.users.index', compact('members'));
    }


    public function destroy($id)
    {
        $UserInfo = User::withTrashed()->find($id);
        if ($UserInfo->deleted_at != '') {
            $UserInfo->update(['deleted_at' => null]);
        } else {
            $UserInfo->delete();
        }

        session()->flash('success', 'User delete successfully.');
    }

    public function forceDelete($id)
    {
        $UserInfo = User::withTrashed()->find($id);
        $UserInfo->forceDelete();
        session()->flash('success', 'Force delete successfully.');
    }

    public function statusChange($id)
    {
        $status = User::find($id);

        if (empty($id)) {
            return $this->InvalidUrl();
        }

        $data['status'] = $status['status'] == '1' ? '0' : '1';
        User::where('id', $id)->update($data);
        session()->flash('message', 'User status updated successfully.');
    }

    public function userstatusChange($id)
    {
        $members = User::find($id);
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $members['status'] == '1' ? '0' : '1';
        User::where('id', $id)->update($data);
        session()->flash('message', 'User status updated successfully.');
    }
}
