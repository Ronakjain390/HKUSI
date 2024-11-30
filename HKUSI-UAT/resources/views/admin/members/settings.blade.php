<div class="card custom-card profile-details">
    <div class="basic-details">
        <h6 class="card-heading">Settings</h6>
    </div>
    <div class="acoount-page form-setting">
        <form action="{{route('admin.members.updateMemberSettings',$MemberInfo->id)}}" method="post" id="quickForm" autocomplete="off">
        @csrf
            <div class="form-flex">
                <label class="form-label" for="multicol-password">Push Notification</label>
                <div class="col-input">
                    <div class="switch-check-settings">
						<label class="switch">
							<input {{($MemberInfo->push_notification == 'Yes'? "checked":"")}} type="checkbox" class="switch-input" name="push_notification" value="Yes"/>
							<span class="switch-toggle-slider"> <span class="switch-label"></span> 
						</label>
					</div>
                </div>
            </div>

            <div class="form-btn">
                <button class="btn action-btn" type="submit">Save Changes</button>
                <button class="btn cancel-btn">Cancel</button>
            </div>
        </form>
    </div>
@push('foorterscript')
    <script>
         
    </script>
@endpush
</div>

