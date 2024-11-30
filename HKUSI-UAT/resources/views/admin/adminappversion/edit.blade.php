@extends('admin.layouts.index')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		{!! Form::model($AppVersionInfo, [
		    'method' => 'PATCH',
		    'route' => ['admin.adminappversion.update', $AppVersionInfo->id],
		    'id' => 'quickForm',
		    'autocomplete' => 'off',
		    'files' => 'true',
		    'class' => 'edit-form',
		]) !!}
		<input type="hidden" name="user_id" value="{{ $AppVersionInfo->user_id }}">
		<div class="card custom-card profile-details">
			<div class="basic-details">
				<h6 class="card-heading">Basic Info</h6>
			</div>
			<div class="table-responsive table-details">
				<table class="table">
					<tbody>
						<tr>
							<th class="t-basic">IOS Release Date</th>
							<td><input type="text" name="ios_release_date" id="iOs_Release_Date" required class="form-control"
									placeholder="IOS Release Date" value="{{ $AppVersionInfo->ios_release_date }}"></td>
						</tr>
						<tr>
							<th class="t-basic">iOS Version</th>
							<td><input type="text" name="ios_version" required class="form-control" placeholder="iOS Version"
									value="{{ $AppVersionInfo->ios_version }}"></td>
						</tr>
						<tr>
							<th class="t-basic">iOS App Store URL</th>
							<td><input type="text" name="ios_app_store_url" required class="form-control" placeholder="iOS App Store URL"
									value="{{ $AppVersionInfo->ios_app_store_url }}"></td>
						</tr>
						<tr>
							<th class="t-basic">IOS Force Update</th>
							<td>
								<select class="form-control" name="ios_force_update">
									<option value="No" {{ $AppVersionInfo->ios_force_update == 'No' ? 'selected' : '' }}>No</option>
									<option value="Yes" {{ $AppVersionInfo->ios_force_update == 'Yes' ? 'selected' : '' }}>Yes</option>
								</select>
							</td>
						</tr>
						<tr>
							<th class="t-basic">Android Release Date</th>
							<td><input type="text" name="android_release_date" id="Android_Release_Date" required class="form-control"
									placeholder="Android Release Date" value="{{ $AppVersionInfo->android_release_date }}"></td>
						</tr>
						<tr>
							<th class="t-basic">Android Version</th>
							<td><input type="text" name="android_version" required class="form-control" placeholder="Android Version"
									value="{{ $AppVersionInfo->android_version }}"></td>
						</tr>
						<tr>
							<th class="t-basic">Android App Store URL</th>
							<td><input type="text" name="android_app_store_url" required class="form-control"
									placeholder="Android App Store URL" value="{{ $AppVersionInfo->android_app_store_url }}"></td>
						</tr>
						<tr>
							<th class="t-basic">Android Force Update</th>
							<td>
								<select class="form-control" name="android_force_update">
									<option value="No" {{ $AppVersionInfo->android_force_update == 'No' ? 'selected' : '' }}>No</option>
									<option value="Yes" {{ $AppVersionInfo->android_force_update == 'Yes' ? 'selected' : '' }}>Yes</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="card custom-card">
			<div class="form-btn">
				<button type="submit" class="btn action-btn">Save Changes</button>
				<button type="reset" class="btn cancel-btn">Delete</button>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
@endsection
@push('foorterscript')
	<script>
		$().ready(function() {
			// Add custom URL validation method
			$.validator.addMethod("url", function(value, element) {
				// Regular expression pattern to match a URL
				var urlPattern = /^(http(s)?:\/\/)?(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})+(\/[^\s]*)?$/;

				// Test the value against the URL pattern
				return this.optional(element) || urlPattern.test(value);
			}, "Please enter a valid URL.");

			$("#quickForm").validate({
				// in 'rules' user have to specify all the constraints for respective fields
				rules: {
					ios_release_date: "required",
					ios_version: "required",
					ios_app_store_url: {
						required: true,
						url: true
					},
					ios_force_update: "required",
					android_release_date: "required",
					android_version: "required",
					android_app_store_url: {
						required: true,
						url: true
					},
					android_force_update: "required",
				},
				// in 'messages' user have to specify message as per rules
				// messages: {
				//     ios_release_date: "Please enter a name",
				//     status: "Please select a status",
				// }
			});

			$('#iOs_Release_Date,#Android_Release_Date').datepicker({
				format: "yyyy-mm-dd",
				autoclose: true,
			});
		});
	</script>
@endpush
