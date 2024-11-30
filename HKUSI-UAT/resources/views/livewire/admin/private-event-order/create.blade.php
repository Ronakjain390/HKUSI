{{-- Private Event Order Create View created By Akash --}}
    <div class="container-xxl flex-grow-1 container-p-y">
        <form class="edit-form" id="create-private-event">
            @if (session()->has('success')) 
              <div style="color:green;">
                  {{ session('success') }}
              </div>
            @endif
            @if (session()->has('message')) 
              <div style="color: red;">
                  {{ session('message') }}
              </div>
            @endif
            <div class="card custom-card profile-details">
                <div class="basic-details">
                    <h6 class="card-heading">Member Info</h6>
                </div>
                <div class="table-responsive table-details" style="width: 450px;">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Member Name <span class="text-danger">*</span></th> 
                                <td> 
                                    <select class="form-select" id="selectmemberid" name="member" wire:model="member">
                                        <option value="">Select Member</option>
                                        @if(isset($memberInfo) && !$memberInfo->isEmpty())
                                            @foreach($memberInfo as $members)
                                                <option value="{{$members->application_number}}">{{$members->surname}} {{$members->given_name}} ({{$members->application_number}})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                     @error('member')  
                                        <label style="color:red;" >{{ $message }}</label> 
                                      @enderror
                                    @if($message = Session::get('ageError'))
                                        <label style="color:red;" >{{ $message }}</label> 
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card custom-card profile-details">
                <div class="basic-details">
                    <h6 class="card-heading">Event Info</h6>
                </div>
                <div class="table-responsive table-details" style="width: 450px;">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Private Event <span class="text-danger">*</span></th> 
                                <td> 
                                    <select class="form-control" id="event_id" name="event_id" wire:model="event_id">
                                        <option value="">Select Event</option>
                                            @if(isset($data) && !empty($data))
                                                @foreach($data as $eventData)
                                                    <option value="{{$eventData->id}}" >{{$eventData->event_name}} ( #{{$eventData->id }} )</option>
                                                @endforeach
                                            @endif
                                    </select>
                                    @if($message = Session::get('error'))
                                        <label style="color:red;" >{{ $message }}</label> 
                                    @endif
                                  @error('event_id')  
                                    <label style="color:red;" >{{ $message }}</label> 
                                  @enderror
                                  @error('error')  
                                    <label style="color:red;" >{{ $message }}</label> 
                                  @enderror
                                </td>
                                <tr>
                                    <th class="t-basic">Group</th> 
                                     <td>
                                        <input type="text" name="event_group" wire:model="event_group" value="@if(isset($event_group) && !empty($event_group)){{ $event_group }} @endif" class="form-control" placeholder="Event Group">
                                         @error('event_group')  
                                             <label style="color:red;" >{{ $message }}</label> 
                                         @enderror
                                     </td>
                                </tr>

                                <tr>
                                    <th class="t-basic">No of Ticket(s)</th> 
                                     <td>
                                        <input type="text" name="no_of_seats" wire:model="no_of_seats" onkeypress="return isNumber(event);" value="@if(isset($no_of_seats) && !empty($no_of_seats)){{ $no_of_seats }} @endif" class="form-control" placeholder="No of Ticket(s)">
                                         @error('no_of_seats')  
                                             <label style="color:red;" >{{ $message }}</label> 
                                         @enderror
                                     </td>
                                </tr>

                                <tr>
                                    <th class="t-basic">Event Date</th> 
                                     <td>
                                        <input type="text" name="event_date" wire:model="event_date" value="@if(isset($event_date) && !empty($event_date)){{ $event_date }} @endif" required readonly class="form-control" placeholder="Event Date">
                                         @error('event_date')  
                                             <label style="color:red;" >{{ $message }}</label> 
                                         @enderror
                                     </td>
                                </tr>

                                <tr>
                                    <th class="t-basic">Event Time</th> 
                                     <td>
                                        <input type="text" name="event_time" wire:model="event_time" value="@if(isset($event_time) && !empty($event_time)){{$event_time}} @endif" required readonly class="form-control" placeholder="Event Time">
                                         @error('event_time')  
                                             <label style="color:red;" >{{ $message }}</label> 
                                         @enderror
                                     </td>
                                </tr>

                                <tr>
                                    <th class="t-basic">Assembly Time</th> 
                                     <td>
                                        <input type="text" name="assembly_time" wire:model="assembly_time" value="@if(isset($assembly_time) && !empty($assembly_time)){{$assembly_time}} @endif" required readonly class="form-control" placeholder="Assembly Time">
                                         @error('assembly_time')  
                                             <label style="color:red;" >{{ $message }}</label> 
                                         @enderror
                                     </td>
                                </tr>
                               
                               
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card custom-card profile-details">
                <div class="basic-details">
                    <h6 class="card-heading">Order Status</h6>
                </div>
                <div class="table-responsive table-details" style="width: 450px;">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Status</th> 
                                <td> 
                                    <select class="form-select" id="booking_status" name="booking_status" wire:model="booking_status">
                                        <option value="">Select Status</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Paid" >Paid</option>
                                        <option value="Pending" >Pending</option>
                                    </select>
                                     @error('booking_status')  
                                        <label style="color:red;" >{{ $message }}</label> 
                                      @enderror
                                    @if($message = Session::get('ageError'))
                                        <label style="color:red;" >{{ $message }}</label> 
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card custom-card">
                <div class="form-btn">
                    <button type="submit" wire:click.prevent="save()" class="btn action-btn">Save Changes</button>
                    <button type="reset" class="btn cancel-btn">Delete</button>
                </div>
            </div>
        </form>
        <!-- / Content -->
        <div class="content-backdrop fade "></div>
        <!-- Content wrapper -->
    </div>
    <!-- / Layout page -->
@push('foorterscript')
<script type="text/javascript">

window.initSelectEventDrop=()=>{
    $('#event_id').select2({});
    $("#selectmemberid").select2({
    });
}

initSelectEventDrop();

$('#event_id').on('change', function (e) {
    livewire.emit('selectedEventItem', e.target.value)
});

$('#selectmemberid').on('change', function (e) {
    livewire.emit('selectedMemberItem', e.target.value)
    @this.call('refers')
});
window.livewire.on('select2',()=>{
    initSelectEventDrop();
});

</script>
@endpush