<div class="card custom-card profile-details">
	<div class="basic-details">
		<h6 class="card-heading">Settings</h6>
	</div>
	<div class="acoount-page form-setting">
		<form action="{{ route('admin.users.updateSettings', $UserInfo->id) }}" method="post" id="quickForm" autocomplete="off">
			@csrf
			<div class="form-flex">
				<label class="form-label" for="multicol-password">Push Notification</label>
				<div class="col-input">
					<div class="switch-check-settings">
						<label class="switch">
							<input name="push_notification" value="Yes" type="checkbox" class="switch-input"
								{{ $UserInfo->push_notification == 'Yes' ? 'checked' : '' }}>
							<span class="switch-toggle-slider"> <span class="switch-label"></span>
						</label>
					</div>
				</div>
			</div>

			<div class="form-flex">
				<label class="form-label" for="multicol-password">Admin App Permission</label>
				<div class="col-input">
					<div class="switch-check-settings">
						<label class="switch">
							<input name="admin_app_permission" value="1" type="checkbox" class="switch-input"
								{{ $UserInfo->admin_app_permission == 1 ? 'checked' : '' }}>
							<span class="switch-toggle-slider"> <span class="switch-label"></span>
						</label>
					</div>
				</div>
			</div>

			<div class="form-flex">
				<label class="form-label" for="multicol-password">Admin Panel Permission</label>
				<div class="col-input">
					<div class="switch-check-settings">
						<label class="switch">
							<input name="admin_panel_permission" value="1" type="checkbox" class="switch-input"
								{{ $UserInfo->admin_panel_permission == 1 ? 'checked' : '' }}>
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
		<script></script>
	@endpush
</div>
