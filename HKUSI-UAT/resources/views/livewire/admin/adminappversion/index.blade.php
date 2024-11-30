<div class="container-xxl flex-grow-1 container-p-y">
	<div class="card custom-card">
		<div class="filter-card">
			<div class="row">
				<div class="col-sm-12 col-md-7">
					<div class=" fliter-flex d-flex align-items-center">
						<div class="filter-label ">
							<label>
								<h6>Filter</h6>
							</label>
						</div>

						<div class="flex-filter-box">
							<div class="input-calender fx">
								<input class="form-control" type="date" wire:model.defer="from" placeholder="from" id="html5-date-input">
							</div>
							<div class="input-calender fx">
								<input class="form-control" type="date" wire:model.defer="to" placeholder="to " id="html5-date-input">
							</div>
							<div class="filter-button fx dropdown">
								<button type="submit" wire:click="$set('daterange', 'true')" class="btn btn-label-info">Filter</button>
							</div>
						</div>
						
					</div>
				</div>
				<div class="col-sm-12 col-md-5">
					<div class="search-flex d-flex justify-content-end">
						<div class="search-box">
							<input class="form-control" type="text" wire:model.defer="search" value="{{ $search }}"
								placeholder="Search">
							<span><svg width="10" height="10" viewBox="0 0 10 10" fill="none"
									xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd"
										d="M7.6 6.9L9.85 9.15C10.05 9.35 10.05 9.65 9.85 9.85C9.75 9.95 9.6 10 9.5 10C9.4 10 9.25 9.95 9.15 9.85L6.9 7.6C6.15 8.15 5.25 8.5 4.25 8.5C1.9 8.5 0 6.6 0 4.25C0 1.9 1.9 0 4.25 0C6.6 0 8.5 1.9 8.5 4.25C8.5 5.25 8.15 6.2 7.6 6.9ZM4.25 1C2.45 1 1 2.45 1 4.25C1 6.05 2.45 7.5 4.25 7.5C5.15 7.5 5.95 7.15 6.55 6.55C7.15 5.95 7.5 5.15 7.5 4.25C7.5 2.45 6.05 1 4.25 1Z"
										fill="#6E6B7B"></path>
								</svg>

							</span>
						</div>
						<div class="filter-button">
							<button type="submit" wire:click="$set('searchSubmit' , 'true')" class="btn btn-label-info">Search</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<form method="post" action="{{ route('admin.adminappversion.multipleAdminAppVersion') }}">
			{{ csrf_field() }}
			<input type="hidden" name="select_type" id='myhidden' value=''>
			<div class="filter-card action-card">
				<div class="row">
					<div class="col-sm-12 col-md-4">
						<div class=" fliter-flex d-flex align-items-center">
							<div class="filter-label ">
								<label>
									<h6>Action</h6>
								</label>
							</div>
							<div class="flex-filter-box" wire:ignore>
								<div class="filter-dropdown fx">
									<div class="select-custom">
										<select id="selectpickerBasic" class="selectpicker w-100" data-style="btn-default" tabindex="null"
											onchange="return selectDataAction(event)">
											<option value="">Action</option>
											<option value="delete">Delete</option>
										</select>
									</div>
								</div>
								<div class="filter-button fx">
									<button type="submit" class="btn btn-label-info m-0" onclick="updateStatus()">Confirm</button>
								</div>
								<div class="filter-button fx plus-button">
									<a href="{{ route('admin.adminappversion.create') }}">
										<button type="button" class="btn btn-label-info ">
											<svg width="12" height="12" viewBox="0 0 12 12" fill="none"
												xmlns="http://www.w3.org/2000/svg">
												<path
													d="M12 6C12 6.45 11.7 6.75 11.25 6.75H6.75V11.25C6.75 11.7 6.45 12 6 12C5.55 12 5.25 11.7 5.25 11.25V6.75H0.75C0.3 6.75 0 6.45 0 6C0 5.55 0.3 5.25 0.75 5.25H5.25V0.75C5.25 0.3 5.55 0 6 0C6.45 0 6.75 0.3 6.75 0.75V5.25H11.25C11.7 5.25 12 5.55 12 6Z"
													fill="black"></path>
											</svg>
										</button>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-datatable dataTable_select text-nowrap table-responsive custom-table ">
				<div id="DataTables_Table_3_wrapper " class="dataTables_wrapper dt-bootstrap5 no-footer ">
					<table class="dt-select-table table dataTable no-footer dt-checkboxes-select " id="DataTables_Table_3 "
						aria-describedby="DataTables_Table_3_info ">
						<thead>
							<tr>
								<th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all " rowspan="1 " colspan="1 "
									data-col="0 " aria-label=" "><input type="checkbox" class="form-check-input " id="checkAll"></th>
								<th onclick="return shorting('created_at','{{ $order_by }}');"
									class="sorting @if (isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif "
									tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending "
									aria-label="Release Date: activate to sort column ascending ">Create Date</th>
								<th onclick="return shorting('ios_release_date','{{ $order_by }}');"
									class="sorting @if (isset($order_type) && $order_type == 'ios_release_date' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'ios_release_date' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif "
									tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending "
									aria-label="Release Date: activate to sort column ascending ">iOS Release Date</th>
								<th onclick="return shorting('ios_version','{{ $order_by }}');"
									class="sorting @if (isset($order_type) && $order_type == 'ios_version' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'ios_version' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif "
									tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending "
									aria-label="iOS Version: activate to sort column ascending ">iOS version</th>
								<th onclick="return shorting('ios_app_store_url','{{ $order_by }}');"
									class="sorting @if (isset($order_type) && $order_type == 'ios_app_store_url' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'ios_app_store_url' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif "
									tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending "
									aria-label="iOS Version: activate to sort column ascending ">iOS App Store URL</th>
								<th onclick="return shorting('ios_force_update','{{ $order_by }}');"
									class="sorting @if (isset($order_type) && $order_type == 'ios_force_update' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'ios_force_update' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif "
									tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending "
									aria-label="iOS Version: activate to sort column ascending ">iOS Force Update</th>
								<th onclick="return shorting('android_release_date','{{ $order_by }}');"
									class="sorting @if (isset($order_type) && $order_type == 'android_release_date' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'android_release_date' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif "
									tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending "
									aria-label="iOS Version: activate to sort column ascending ">Android Release Date</th>
								<th onclick="return shorting('android_version','{{ $order_by }}');"
									class="sorting @if (isset($order_type) && $order_type == 'android_version' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'android_version' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif "
									tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending "
									aria-label="iOS Version: activate to sort column ascending ">Android version</th>
								<th onclick="return shorting('android_app_store_url','{{ $order_by }}');"
									class="sorting @if (isset($order_type) && $order_type == 'android_app_store_url' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'android_app_store_url' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif "
									tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending "
									aria-label="iOS Version: activate to sort column ascending ">Android App Store URL</th>
								<th onclick="return shorting('android_force_update','{{ $order_by }}');"
									class="sorting @if (isset($order_type) && $order_type == 'android_force_update' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'android_force_update' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif "
									tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending "
									aria-label="iOS Version: activate to sort column ascending ">Android Force Update</th>
								<th rowspan="1" colspan="1">action</th>
							</tr>
						</thead>
						<tbody>
							@if (count($language))
								@foreach ($language as $key => $programeData)
									<tr>
										<td valign="top" colspan="1" class="dt-checkboxes-cell dt-checkboxes-select-all "><input
												type="checkbox" name='id[]' class="form-check-input" value="{{ $programeData->id }}"></td>
										<td valign="top" colspan="1" class="dataTables_empty">
											<a href="{{ route('admin.adminappversion.versionDetail', [$programeData->id, 'show']) }}">
												@if (isset($programeData->created_at) && !empty($programeData->created_at))
													{{ date('Y-m-d H:i:s', strtotime($programeData->created_at)) }}
												@endif
											</a>
										</td>
										<td valign="top" colspan="1" class="dataTables_empty">
											<a href="{{ route('admin.adminappversion.versionDetail', [$programeData->id, 'show']) }}">
												@if (isset($programeData->ios_release_date) && !empty($programeData->ios_release_date))
													{{ date('Y-m-d', strtotime($programeData->ios_release_date)) }}
												@endif
											</a>
										</td>
										<td valign="top" colspan="1" class="dataTables_empty">
											<a href="{{ route('admin.adminappversion.versionDetail', [$programeData->id, 'show']) }}">
												@if (isset($programeData->ios_version) && !empty($programeData->ios_version))
													{{ $programeData->ios_version }}
												@endif
											</a>
										</td>
										<td valign="top" colspan="1" class="dataTables_empty">
											<a href="{{ route('admin.adminappversion.versionDetail', [$programeData->id, 'show']) }}">
												@if (isset($programeData->ios_app_store_url) && !empty($programeData->ios_app_store_url))
													{{ $programeData->ios_app_store_url }}
												@endif
											</a>
										</td>
										<td valign="top" colspan="1" class="dataTables_empty">
											<div class="select_status_filter">
												<span class="badge rounded-pill badge-custom  @if ($programeData->ios_force_update == 'Yes') green @else gray @endif">
													{{ $programeData->ios_force_update }}
												</span>
											</div>
										</td>
										<td valign="top" colspan="1" class="dataTables_empty">
											<a href="{{ route('admin.adminappversion.versionDetail', [$programeData->id, 'show']) }}">
												@if (isset($programeData->android_release_date) && !empty($programeData->android_release_date))
													{{ date('Y-m-d', strtotime($programeData->android_release_date)) }}
												@endif
											</a>
										</td>
										<td valign="top" colspan="1" class="dataTables_empty">
											<a href="{{ route('admin.adminappversion.versionDetail', [$programeData->id, 'show']) }}">
												@if (isset($programeData->android_version) && !empty($programeData->android_version))
													{{ $programeData->android_version }}
												@endif
											</a>
										</td>
										<td valign="top" colspan="1" class="dataTables_empty">
											<a href="{{ route('admin.adminappversion.versionDetail', [$programeData->id, 'show']) }}">
												@if (isset($programeData->android_app_store_url) && !empty($programeData->android_app_store_url))
													{{ $programeData->android_app_store_url }}
												@endif
											</a>
										</td>
										<td valign="top" colspan="1" class="dataTables_empty">
											<div class="select_status_filter">
												<span class="badge rounded-pill badge-custom  @if ($programeData->android_force_update == 'Yes') green @else gray @endif">
													{{ $programeData->android_force_update }}
												</span>
											</div>
										</td>
										<td valign="top" colspan="1" class="dataTables_empty">
											<div class="dropdown text-center">
												<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
													aria-expanded="false">
													<i class="ti ti-dots"></i>
												</button>
												<div class="dropdown-menu table-dropdown">
													<a class="dropdown-item" href="{{ route('admin.adminappversion.versionDetail', [$programeData->id,'edit']) }}">Edit</a>
													<a wire:click="$emit('triggerDelete',{{ $programeData->id }})" class="dropdown-item"
														href="javascript:void(0);">Delete</a>
												</div>
											</div>
										</td>

									</tr>
								@endforeach
							@else
								<td valign="top" colspan="11" class="text-center dataTables_empty">{{ $notfoundlabel }}</td>
							@endif
						</tbody>
					</table>
					<div class="bottom-show-padding">
						<div class="row custom-show-drop-down ">
							<div class="col-sm-12 col-md-6">
								<div class="bottom-dropdown">
									@if (isset($paginate) && !empty($paginate) && $countMember > $paginate)
										<div class="show-bottom">
											<div class="show-text">
												<p>Show</p>
											</div>
											<div class="filter-dropdown">
												<div class="btn-group dropup">
													<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"
														aria-expanded="false">
														@if (isset($paginate) && !empty($paginate))
															{{ $paginate }}
														@endif
													</button>
													<ul class="dropdown-menu custom-dropdown">
														<li><a class="dropdown-item" wire:click="$set('paginate', '20')" href="javascript:void(0);">20</a></li>
														<li><a class="dropdown-item" wire:click="$set('paginate', '50')" href="javascript:void(0);">50</a></li>
														<li><a class="dropdown-item" wire:click="$set('paginate', '100')" href="javascript:void(0);">100</a>
														</li>
														<li><a class="dropdown-item" wire:click="$set('paginate', '500')" href="javascript:void(0);">500</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
										<div class="dataTables_info " id="DataTables_Table_3_info " role="status " aria-live="polite ">
											<p>
												Showing <span>
													@if (isset($page) && !empty($page))
														{{ $page }}
													@endif
												</span> to <span>
													@if (isset($paginate) && !empty($paginate))
														{{ $paginate }}
													@endif
												</span> of <span>
													@if (isset($countMember) && !empty($countMember))
														{{ $countMember }}
													@endif
												</span> entries
											</p>
										</div>
									@endif
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<nav aria-label="Page navigation ">
									{{ $language->links() }}
								</nav>
							</div>
						</div>
					</div>
				</div>

			</div>
		</form>
		<!-- / Content -->
		<div class="content-backdrop fade "></div>
		<!-- Content wrapper -->
	</div>
	@push('foorterscript')
		<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function(e) {
				@this.on('triggerStatus', id => {
					Swal.fire({
						title: "Are you sure?",
						text: "Are you sure to change to status?",
						icon: "warning",
						showCancelButton: true,
						confirmButtonColor: "#6fc5e0",
						cancelButtonColor: "#d33",
						confirmButtonText: "Yes, Change it.",
					}).then((result) => {
						if (result.value) {
							@this.call('statusChange', id)
						} else {
							console.log("Canceled");
						}
					});
				});
			});


			document.addEventListener('DOMContentLoaded', function(e) {
				@this.on('triggerDelete', id => {
					Swal.fire({
						title: "Are you sure?",
						text: "Are you sure delete?",
						icon: "warning",
						showCancelButton: true,
						confirmButtonColor: "#6fc5e0",
						cancelButtonColor: "#d33",
						confirmButtonText: "Delete",
					}).then((result) => {
						if (result.value) {
							@this.call('destroy', id)
						} else {
							console.log("Canceled");
						}
					});
				});
			});

			function shorting(order_type, order_by) {
				if (order_by == 'DESC') {
					var order = 'ASC';
					@this.set('order_type', order_type);
					@this.set('order_by', order);
				} else {
					var order = 'DESC';
					@this.set('order_type', order_type);
					@this.set('order_by', order);
				}
			}


			function checkstatus(value, id) {
				Swal.fire({
					title: "Are you sure?",
					text: "Are you sure change status.",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes, Update it.",
				}).then((result) => {
					if (result.value) {
						@this.call('statusChange', id)
					} else {
						console.log("Canceled");
					}
				});
			}

			function selectDataAction(event) {
				var selectElement = event.target;
				var value = selectElement.value;
				$('#myhidden').val(value);
			}
			$("#checkAll").click(function() {
				$('input:checkbox').not(this).prop('checked', this.checked);
			});
		</script>
	@endpush
