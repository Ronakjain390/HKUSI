<?php

namespace App\Http\Livewire\Admin;

use App\Models\StudentAppVersion;
use Livewire\Component;
use Livewire\WithPagination;

class StudentappversionManagement extends Component
{
    use WithPagination;

    public $search, $from, $to, $daterange = false, $daterange1 = false, $searchSubmit = false, $status = null, $record_period, $start_date, $end_date, $order_by = 'DESC', $order_type = 'created_at', $paginate = '20', $delete, $countMember, $startFrom, $endFrom;

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
        $language = (new StudentAppVersion)->newQuery();

        if ($this->daterange == true) {
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date = date('Y-m-d', strtotime($this->from)) . ' 00:00:00';
                $to_date = date('Y-m-d', strtotime($this->to)) . ' 23:59:59';
                $language = $language->whereBetween('created_at', [$form_date, $to_date]);

            }
        }
        if ($this->daterange1 == true) {

            if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Basic') {
                $language = $language->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 00:59:59')]);
                $this->from = date('Y-m-d');
                $this->to = date('Y-m-d');
            } elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $language = $language->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d');
                $this->to = date('Y-m-d');
            } elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $a = date('w');
                $language = $language->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime('- ' . $a . ' days')), date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d', strtotime('- ' . $a . ' days'));
                $this->to = date('Y-m-d');
            } elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                $language = $language->whereBetween('created_at', [date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59')]);
                $this->from = date('Y-m-01');
                $this->to = date('Y-m-t');
            } elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->start_date) && !empty($this->start_date) && isset($this->end_date) && !empty($this->end_date)) {
                    $form_start_date = date('Y-m-d', strtotime($this->start_date)) . ' 00:00:00';
                    $to_end_date = date('Y-m-d', strtotime($this->end_date)) . ' 23:59:59';
                    $language = $language->whereBetween('created_at', [$form_start_date, $to_end_date]);
                }
            }

        }

        if ($this->searchSubmit == true) {
            $language = $language->where('ios_version', 'like', '%' . trim($this->search) . '%')
                ->orwhere('ios_app_store_url', 'like', '%' . trim($this->search) . '%')
                ->orwhere('ios_force_update', 'like', '%' . trim($this->search) . '%')
                ->orwhere('android_release_date', 'like', '%' . trim($this->search) . '%')
                ->orwhere('android_version', 'like', '%' . trim($this->search) . '%')
                ->orwhere('android_app_store_url', 'like', '%' . trim($this->search) . '%')
                ->orwhere('android_force_update', 'like', '%' . trim($this->search) . '%');
        }

		if (isset($this->order_type) && $this->order_type != '') {
            $language = $language->orderBy($this->order_type,$this->order_by);
        }
        $language = $language->paginate($this->paginate);
        $this->countMember = StudentAppVersion::count();
        return view('livewire.admin.studentappversion.index', compact('language'));
    }

    public function destroy($id)
    {
        StudentAppVersion::find($id)->delete();
        session()->flash('success', 'App Version delete successfully.');
    }

    public function statusChange($id)
    {
        $status = StudentAppVersion::find($id);
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $data['status'] = $status['status'] == '1' ? '0' : '1';
        StudentAppVersion::where('id', $id)->update($data);
        session()->flash('message', 'App Version status updated successfully.');
    }

}
