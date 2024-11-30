<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use App\Models\MemberInfo;
use App\Models\PrivateEventOrder;
use App\Models\PrivateEventSetting;
use App\Models\User;
use App\Models\MemberProgramme;
use App\Jobs\SendEmailJob;
use Auth,Storage,Config,DB,DateTime;

class AddPrivateEventOrder extends Component
{
	use WithFileUploads;
	public $member,$event_id, $event_date, $event_time, $assembly_time, $booking_status, $event_group, $no_of_seats, $data;
    //  Livewire component added by Akash
    protected $listeners = [
        'selectedEventItem', 'selectedMemberItem'
    ];

    public function hydrate()
    {
        $this->emit('select2');
    }

    public function render(){
    	$memberInfo = MemberInfo::where('status', 1)->orderBy('given_name','ASC')->get();
 
        return view('livewire.admin.private-event-order.create',compact('memberInfo'));
    }

    public function selectedEventItem($event_id)
    {
        $this->event_id = $event_id;
        if (isset($this->event_id) && !empty($this->event_id)) {

            $event = PrivateEventSetting::where('id',$this->event_id)->first();

            $this->event_date = date('Y-m-d',$event->date);
            $this->event_time = date('H:i',$event->start_time). '-' .date('H:i',$event->end_time);
            $this->assembly_time = date('H:i',$event->assembly_start_time). '-' .date('H:i',$event->assembly_end_time);
        }
    }

    public function selectedMemberItem($member_id)
    {
        $this->member = $member_id;

        if ( isset($this->member) && !empty($this->member)) {
            
            $memberInfo = MemberInfo::where('application_number', $this->member)->first();
            if ( !empty($memberInfo)) {
               
                $programme_id = MemberProgramme::where('member_info_id', $memberInfo->id)->pluck('programme_id')->toArray();
                $this->data = PrivateEventSetting::with(['getProgrammeDetailMany'])
                                              ->whereHas('getProgrammeDetailMany', function($query) use($programme_id)
                                              {
                                                  $query->whereIn('program_id', $programme_id);
                                              })
                                              ->where('status','Enabled')
                                              ->get();
            }
        }
    }


    public function save(Request $request){
    	
        $input = $request->all();
        //dd($input);
         $this->validate([
           'event_id'           => 'required',
           'member'     => 'required',
           'booking_status' => 'required'
        ]); 

        $eventData                           =  new PrivateEventOrder();


        $eventData['booking_id'] = $eventData->generatePrivateEventId();
        $eventData['event_id']               =  $this->event_id;
        $eventData['event_group']            =  $this->event_group;
        $eventData['no_of_seats']            =  $this->no_of_seats;
        $eventData['application_id']         =  $this->member;
        $eventData['booking_status']         =  ucfirst(strtolower($this->booking_status));
        $eventData->save();

        return redirect()->route('admin.private-event-order.index')->with('message', 'Private Event Booking created successfully.');
        
    }

    public function refers(){
       
        $this->event_date = '';
        $this->event_time = '';
        $this->assembly_time = '';
        $this->event_id = '';
    }

}