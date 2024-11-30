
    <div class="container-xxl flex-grow-1 container-p-y">
        <form class="edit-form">
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
                    <h6 class="card-heading">Basic Info</h6>
                </div>
                <div class="table-responsive table-details" style="width: 450px;">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Member Name</th> 
                                <td wire:ignore> 
                                    <select class="form-select" id="selectmemberid" name="member" wire:model.defer="member">
                                        <option value="">Select Member</option>
                                        @if(isset($memberInfo) && !empty($memberInfo))
                                            @foreach($memberInfo as $member)
                                                <option value="{{$member->id}}">{{$member->surname}} {{$member->given_name}} ({{$member->application_number}})</option>
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
                    <h6 class="card-heading">Member Programe Info</h6>
                </div>
                <div class="table-responsive table-details">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Member Programe</th> 
                                <td> 
                                    <select class="form-control" name="checkprograme" wire:model="checkprograme">
                                        <option value="">Select Programe</option>
                                        @if($booking)
                                            @if(isset($data) && !empty($data))
                                                @foreach($data as $programedata)
                                                    <option value="{{$programedata->id}}">{{$programedata->programme_code}} / {{$programedata->programme_name}}</option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                    @if($message = Session::get('error'))
                                        <label style="color:red;" >{{ $message }}</label> 
                                    @endif
                                  @error('checkprograme')  
                                    <label style="color:red;" >{{ $message }}</label> 
                                  @enderror
                                  @error('error')  
                                    <label style="color:red;" >{{ $message }}</label> 
                                  @enderror
                                </td>
                                <tr>
                                    <th class="t-basic">Start Date</th> 
                                     <td>
                                        <input type="text" name="start_date" wire:model="start_date" value="@if(isset($start_date) && !empty($start_date)){{$start_date}} @endif" required readonly class="form-control" placeholder="Start Date">
                                         @error('start_date')  
                                             <label style="color:red;" >{{ $message }}</label> 
                                         @enderror
                                     </td>
                                </tr>
                               <tr>
                                <th class="t-basic">End Date</th> 
                                    <td>
                                        <input type="text" name="end_date" wire:model="end_date" required readonly value="@if(isset($end_date) && !empty($end_date)){{$end_date}} @endif" class="form-control" placeholder="End Date">
                                        @error('end_date')  
                                            <label style="color:red;" >{{ $message }}</label> 
                                        @enderror
                                    </td>
                               </tr>
                               
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
$("#selectmemberid").select2({
});
$('#selectmemberid').on('change', function (e) {
    var data = $('#selectmemberid').select2("val");
    @this.set('member', data);
    @this.call('refers');
});
</script>
@endpush