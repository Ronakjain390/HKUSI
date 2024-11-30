<div class="card custom-card profile-details">
	<div class="basic-details">
		<h6 class="card-heading">Basic Info</h6>
	</div>
	<div class="table-responsive table-details">
		<table class="table">
			<tbody>
				<tr>
                    <th class="t-basic">Create Date</th>
                    <td>@if(isset($AppVersionInfo->created_at) && !empty($AppVersionInfo->created_at)) {{date('Y-m-d' , strtotime($AppVersionInfo->created_at))}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Create Time</th>
                    <td>@if(isset($AppVersionInfo->created_at) && !empty($AppVersionInfo->created_at)) {{date('h:i:s' , strtotime($AppVersionInfo->created_at))}} @endif</td>
                </tr>
				<tr>
					<th class="t-basic">iOS Release Date</th>
					<td>@if(isset($AppVersionInfo->ios_release_date) && !empty($AppVersionInfo->ios_release_date)) {{date('Y-m-d' , strtotime($AppVersionInfo->ios_release_date))}} @endif</td>
				</tr>
				<tr>
					<th class="t-basic">iOS Version</th>
					<td>@if(isset($AppVersionInfo->ios_version) && !empty($AppVersionInfo->ios_version)) {{$AppVersionInfo->ios_version}} @endif</td>
				</tr>
				<tr>
					<th class="t-basic">iOS App Store URL</th>
					<td>@if(isset($AppVersionInfo->ios_app_store_url) && !empty($AppVersionInfo->ios_app_store_url)) {{$AppVersionInfo->ios_app_store_url}} @endif</td>
				</tr>
				<tr>
					<th class="t-basic">iOS Force Update</th>
					<td>@if(isset($AppVersionInfo->ios_force_update) && !empty($AppVersionInfo->ios_force_update)) {{$AppVersionInfo->ios_force_update}} @endif</td>
				</tr>
				<tr>
					<th class="t-basic">Android Release Date</th>
					<td>@if(isset($AppVersionInfo->android_release_date) && !empty($AppVersionInfo->android_release_date)) {{date('Y-m-d' , strtotime($AppVersionInfo->android_release_date))}} @endif</td>
				</tr>
				<tr>
					<th class="t-basic">Android Version</th>
					<td>@if(isset($AppVersionInfo->android_version) && !empty($AppVersionInfo->android_version)) {{$AppVersionInfo->android_version}} @endif</td>
				</tr>
				<tr>
					<th class="t-basic">Android App Store URL</th>
					<td>@if(isset($AppVersionInfo->android_app_store_url) && !empty($AppVersionInfo->android_app_store_url)) {{$AppVersionInfo->android_app_store_url}} @endif</td>
				</tr>
				<tr>
					<th class="t-basic">Android Force Update</th>
					<td>@if(isset($AppVersionInfo->android_force_update) && !empty($AppVersionInfo->android_force_update)) {{$AppVersionInfo->android_force_update}} @endif</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>