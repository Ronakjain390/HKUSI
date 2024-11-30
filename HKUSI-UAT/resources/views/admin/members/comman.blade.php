@extends('admin.layouts.index')
@section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="profile-img-box">
			<div class="row">
				<div class="col-6">
					<div class="profile-part">
						<div class="profile-img">
							<!--<a href="javascript:void(0);" class="Edit-img">-->
							<div class="img-circle" style="width:">
								<!--<input type="file" name="profile_image">-->
								@if (isset($MemberInfo->getImageBankDetail->profile_image) &&
										$MemberInfo->getImageBankDetail->profile_image != '' &&
										Storage::disk($DISK_NAME)->exists($MemberInfo->getImageBankDetail->profile_image))
									<img id="memberProfile" class="object-fit-contain" style="width: 108px !important;height: 95px !important" src="{{ asset(Storage::url($MemberInfo->getImageBankDetail->profile_image)) }}">
								@else
									<img id="memberProfile" class="object-fit-contain" style="width: 108px !important;height: 95px !important" src="{{ asset('img/default-image.jpg') }}">
								@endif
								@if (isset($dataType) && !empty($dataType) && $dataType == 'edit')
									<div class="upload-btn-wrapper1">
										<button class="btn">Edit</button>
										<input type="file" name="myfile" id="myfile" onchange="readURL(this);" />
									</div>
								@endif
							</div>
							<!--</a>-->
						</div>
						<div class="profile-text">
							<h4 class="Profile-name">
								@if (isset($MemberInfo->getUserDetail->name) && !empty($MemberInfo->getUserDetail->name))
									{{ $MemberInfo->getUserDetail->name }}
									@endif / @if (isset($MemberInfo->application_number) && !empty($MemberInfo->application_number))
										{{ $MemberInfo->application_number }}
									@endif
							</h4>

							<div class="profilte-btn">
								<span class="badge rounded-pill badge-custom @if (isset($MemberInfo->getUserDetail->status) && $MemberInfo->getUserDetail->status == '1') green @else gray @endif">
									@if (isset($MemberInfo->getUserDetail->status) && $MemberInfo->getUserDetail->status == '1')
										Enabled
									@else
										Disabled
									@endif
								</span>
								<span class="badge rounded-pill badge-custom @if (isset($MemberInfo->status) && $MemberInfo->status == '1') green @else gray @endif">
									@if (isset($MemberInfo->status) && $MemberInfo->status == '1')
										Active
									@else
										Inactive
									@endif
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-6 d-flex justify-content-end">
					<div class="profile-tab">
						<div class="dropdown">
							<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="ti ti-dots"></i>
							</button>
							<div class="dropdown-menu table-dropdown">
								<a class="dropdown-item" href="{{ route('admin.memberDetail', [$MemberInfo->id, 'edit']) }}">Edit</a>

								{!! Form::open([
								    'method' => 'DELETE',
								    'route' => ['admin.members.destroy', $MemberInfo->id],
								    'style' => 'display:inline',
								    'id' => 'delete_form_' . $MemberInfo->id,
								]) !!}
								{!! Form::close() !!}
								<a class="dropdown-item" onclick="delete_member('{{ $MemberInfo->id }}')" href="javascript:void(0)">Delete</a>

								{{-- <a class="dropdown-item {!! $MemberInfo->getUserDetail->status == '1' ? 'success' : 'danger' !!}" onclick="return change_user_status_info(event)"
									href="{!! route('admin.members.userstatuschange', [
									    $MemberInfo->id,
									    $MemberInfo->getUserDetail->status == '1' ? '0' : '1',
									]) !!}" title="{!! $MemberInfo->getUserDetail->status == '1' ? 'Disable' : 'Enable' !!}">{!! $MemberInfo->getUserDetail->status == '1' ? 'Disable' : 'Enable' !!}</a> --}}

								<a class="dropdown-item {!! $MemberInfo->status == '1' ? 'success' : 'danger' !!}" onclick="return change_member_status_info(event)"
									href="{!! route('admin.members.memberstatuschange', [$MemberInfo->id, $MemberInfo->status == '1' ? '0' : '1']) !!}" title="{!! $MemberInfo->status == '1' ? 'Inactive' : 'Active' !!}">{!! $MemberInfo->status == '1' ? 'Inactive' : 'Active' !!}</a>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="profile-page-buttons-section" id="active">
			<a href="{{ route('admin.memberDetail', [$MemberInfo->id, 'show']) }}"
				class="btn btn-custom @if (
					(isset($dataType) && !empty($dataType) && $dataType == 'show') ||
						(isset($dataType) && !empty($dataType) && $dataType == 'edit')) active @endif">
				<span><svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd"
							d="M0.725 0.978039C0.95 0.753039 1.25 0.678039 1.55 0.828039C1.625 0.828039 1.7 0.903039 1.775 0.978039C1.925 1.12804 2 1.27804 2 1.50304C2 1.72804 1.925 1.87804 1.775 2.02804C1.625 2.17804 1.475 2.25304 1.25 2.25304H1.1C1.025 2.17804 1.025 2.17804 0.95 2.17804C0.9125 2.17804 0.89375 2.15929 0.875 2.14054C0.85625 2.12179 0.8375 2.10304 0.8 2.10304L0.725 2.02804C0.6875 1.99054 0.66875 1.95304 0.65 1.91554C0.63125 1.87804 0.6125 1.84054 0.575 1.80304C0.5 1.72804 0.5 1.57804 0.5 1.50304C0.5 1.42804 0.5 1.27804 0.575 1.20304C0.575 1.12804 0.65 1.05304 0.725 0.978039ZM5 0.753039C4.55 0.753039 4.25 1.05304 4.25 1.50304C4.25 1.95304 4.55 2.25304 5 2.25304H14.75C15.2 2.25304 15.5 1.95304 15.5 1.50304C15.5 1.05304 15.2 0.753039 14.75 0.753039H5ZM5 5.25304H14.75C15.2 5.25304 15.5 5.55304 15.5 6.00304C15.5 6.45304 15.2 6.75304 14.75 6.75304H5C4.55 6.75304 4.25 6.45304 4.25 6.00304C4.25 5.55304 4.55 5.25304 5 5.25304ZM14.75 9.75304H5C4.55 9.75304 4.25 10.053 4.25 10.503C4.25 10.953 4.55 11.253 5 11.253H14.75C15.2 11.253 15.5 10.953 15.5 10.503C15.5 10.053 15.2 9.75304 14.75 9.75304ZM1.925 5.70304C1.925 5.66554 1.90625 5.64679 1.8875 5.62804C1.86875 5.60929 1.85 5.59054 1.85 5.55304C1.85 5.47804 1.775 5.47804 1.775 5.47804C1.55 5.25304 1.25 5.17804 0.95 5.32804C0.9125 5.36554 0.875 5.38429 0.8375 5.40304C0.8 5.42179 0.7625 5.44054 0.725 5.47804L0.65 5.55304C0.65 5.59054 0.63125 5.60929 0.6125 5.62804C0.59375 5.64679 0.575 5.66554 0.575 5.70304C0.575 5.73491 0.575 5.75324 0.569245 5.76953C0.561456 5.79158 0.543129 5.80991 0.5 5.85304V6.00304C0.5 6.22804 0.575 6.37804 0.725 6.52804C0.875 6.67804 1.025 6.75304 1.25 6.75304C1.475 6.75304 1.625 6.67804 1.775 6.52804C1.925 6.37804 2 6.22804 2 6.00304V5.85304C2 5.81554 1.98125 5.79679 1.9625 5.77804C1.94375 5.75929 1.925 5.74054 1.925 5.70304ZM0.575 10.203C0.575 10.128 0.65 10.053 0.725 9.97804C1.025 9.67804 1.475 9.67804 1.775 9.97804C1.925 10.128 2 10.278 2 10.503C2 10.728 1.925 10.878 1.775 11.028C1.625 11.178 1.475 11.253 1.25 11.253C1.025 11.253 0.875 11.178 0.725 11.028C0.575 10.878 0.5 10.728 0.5 10.503C0.5 10.428 0.5 10.278 0.575 10.203Z"
							fill="black"></path>
					</svg>
				</span>Details
			</a>
			<a href="{{ route('admin.memberDetail', [$MemberInfo->id, 'account']) }}"
				class="btn btn-custom @if (isset($dataType) && !empty($dataType) && $dataType == 'account') active @endif">
				<span><svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd"
							d="M13.25 7.5H12.5V5.25C12.5 2.775 10.475 0.75 8 0.75C5.525 0.75 3.5 2.775 3.5 5.25V7.5H2.75C1.475 7.5 0.5 8.475 0.5 9.75V15C0.5 16.275 1.475 17.25 2.75 17.25H13.25C14.525 17.25 15.5 16.275 15.5 15V9.75C15.5 8.475 14.525 7.5 13.25 7.5ZM5 5.25C5 3.6 6.35 2.25 8 2.25C9.65 2.25 11 3.6 11 5.25V7.5H5V5.25ZM13.25 15.75C13.7 15.75 14 15.45 14 15V9.75C14 9.3 13.7 9 13.25 9H2.75C2.3 9 2 9.3 2 9.75V15C2 15.45 2.3 15.75 2.75 15.75H13.25Z"
							fill="black"></path>
					</svg>
				</span>Account
			</a>
			<a href="{{ route('admin.memberDetail', [$MemberInfo->id, 'programme']) }}">
				<button type="button" class="btn btn-custom @if (isset($dataType) && !empty($dataType) && $dataType == 'programme') active @endif">
					<span><svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd"
								d="M13 0.75H2.875C1.45 0.75 0.25 1.95 0.25 3.375V14.625C0.25 16.05 1.45 17.25 2.875 17.25H13C13.45 17.25 13.75 16.95 13.75 16.5V1.5C13.75 1.05 13.45 0.75 13 0.75ZM2.875 2.25H12.25V12H2.875C2.5 12 2.125 12.075 1.75 12.3V3.375C1.75 2.775 2.275 2.25 2.875 2.25ZM1.75 14.625C1.75 15.225 2.275 15.75 2.875 15.75H12.25V13.5H2.875C2.275 13.5 1.75 14.025 1.75 14.625Z"
								fill="black"></path>
						</svg>
					</span>Programme
				</button></a>
			<a href="{{ route('admin.memberDetail', [$MemberInfo->id, 'hall-booking']) }}">
				<button type="button" class="btn btn-custom @if (isset($dataType) && !empty($dataType) && $dataType == 'hall-booking') active @endif">
					<span><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd"
								d="M15 3.5H12.75V2.75C12.75 1.475 11.775 0.5 10.5 0.5H7.5C6.225 0.5 5.25 1.475 5.25 2.75V3.5H3C1.725 3.5 0.75 4.475 0.75 5.75V13.25C0.75 14.525 1.725 15.5 3 15.5H15C16.275 15.5 17.25 14.525 17.25 13.25V5.75C17.25 4.475 16.275 3.5 15 3.5ZM6.75 2.75C6.75 2.3 7.05 2 7.5 2H10.5C10.95 2 11.25 2.3 11.25 2.75V3.5H6.75V2.75ZM11.25 14V5H6.75V14H11.25ZM2.25 13.25V5.75C2.25 5.3 2.55 5 3 5H5.25V14H3C2.55 14 2.25 13.7 2.25 13.25ZM15 14C15.45 14 15.75 13.7 15.75 13.25V5.75C15.75 5.3 15.45 5 15 5H12.75V14H15Z"
								fill="black"></path>
						</svg></span>
					Hall Booking
				</button></a>
			<a href="{{ route('admin.memberDetail', [$MemberInfo->id, 'evnet-booking']) }}">
				<button type="button" class="btn btn-custom @if (isset($dataType) && !empty($dataType) && $dataType == 'evnet-booking') active @endif">
					<span><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd"
								d="M15 0.5H3C1.725 0.5 0.75 1.475 0.75 2.75V7.25C0.75 11.825 4.425 15.5 9 15.5C13.575 15.5 17.25 11.825 17.25 7.25V2.75C17.25 1.475 16.275 0.5 15 0.5ZM15.75 7.25C15.75 11 12.75 14 9 14C5.25 14 2.25 11 2.25 7.25V2.75C2.25 2.3 2.55 2 3 2H15C15.45 2 15.75 2.3 15.75 2.75V7.25ZM12.525 7.025C12.825 6.725 12.825 6.275 12.525 5.975C12.225 5.675 11.775 5.675 11.475 5.975L9 8.45L6.525 5.975C6.225 5.675 5.775 5.675 5.475 5.975C5.175 6.275 5.175 6.725 5.475 7.025L8.475 10.025C8.625 10.175 8.775 10.25 9 10.25C9.225 10.25 9.375 10.175 9.525 10.025L12.525 7.025Z"
								fill="black"></path>
						</svg>

					</span>Event Booking
				</button>
			</a>
			<a href="{{route('admin.memberDetail',[$MemberInfo->id,'private-event-booking'])}}">
				<button type="button" class="btn btn-custom @if(isset($dataType) && !empty($dataType) && $dataType == 'private-event-booking') active @endif">
				<span><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M15 0.5H3C1.725 0.5 0.75 1.475 0.75 2.75V7.25C0.75 11.825 4.425 15.5 9 15.5C13.575 15.5 17.25 11.825 17.25 7.25V2.75C17.25 1.475 16.275 0.5 15 0.5ZM15.75 7.25C15.75 11 12.75 14 9 14C5.25 14 2.25 11 2.25 7.25V2.75C2.25 2.3 2.55 2 3 2H15C15.45 2 15.75 2.3 15.75 2.75V7.25ZM12.525 7.025C12.825 6.725 12.825 6.275 12.525 5.975C12.225 5.675 11.775 5.675 11.475 5.975L9 8.45L6.525 5.975C6.225 5.675 5.775 5.675 5.475 5.975C5.175 6.275 5.175 6.725 5.475 7.025L8.475 10.025C8.625 10.175 8.775 10.25 9 10.25C9.225 10.25 9.375 10.175 9.525 10.025L12.525 7.025Z" fill="black"></path>
					</svg>
					
				</span>Private Event Booking
			</button>
			</a>
			<a href="{{ route('admin.memberDetail', [$MemberInfo->id, 'settings']) }}"
				class="btn btn-custom @if (isset($dataType) && !empty($dataType) && $dataType == 'settings') active @endif">
				<span><svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd"
							d="M15.225 11.55C15.3 11.4 15.45 11.25 15.75 11.25C17.025 11.25 18 10.275 18 9C18 7.725 17.025 6.75 15.75 6.75H15.6C15.45 6.75 15.3 6.675 15.225 6.525C15.225 6.45 15.225 6.45 15.15 6.375C15.075 6.225 15.075 6 15.3 5.775C16.2 4.875 16.2 3.45 15.3 2.625C14.85 2.175 14.325 1.95 13.725 1.95C13.125 1.95 12.525 2.175 12.075 2.625C11.925 2.775 11.7 2.775 11.55 2.7C11.4 2.7 11.25 2.475 11.25 2.25C11.25 0.975 10.275 0 9 0C7.725 0 6.75 0.975 6.75 2.25V2.4C6.75 2.55 6.675 2.7 6.525 2.775C6.45 2.775 6.45 2.775 6.375 2.85C6.225 2.925 6 2.85 5.775 2.7C4.875 1.8 3.45 1.8 2.625 2.7C1.725 3.6 1.725 5.025 2.7 5.925C2.85 6.075 2.85 6.3 2.775 6.525C2.7 6.675 2.475 6.825 2.25 6.825C0.975 6.825 0 7.8 0 9.075C0 10.35 0.975 11.325 2.25 11.325H2.4C2.625 11.325 2.775 11.475 2.85 11.625C2.925 11.775 2.925 12 2.7 12.225C2.25 12.675 2.025 13.2 2.025 13.8C2.025 14.4 2.25 14.925 2.7 15.375C3.6 16.275 5.025 16.275 5.925 15.3C6.075 15.15 6.3 15.15 6.525 15.225C6.75 15.3 6.825 15.45 6.825 15.75C6.825 17.025 7.8 18 9.075 18C10.35 18 11.325 17.025 11.325 15.75V15.6C11.325 15.375 11.475 15.225 11.625 15.15C11.775 15.075 12 15.075 12.225 15.3C13.125 16.2 14.55 16.2 15.375 15.3C16.275 14.4 16.275 12.975 15.3 12.075C15.225 11.925 15.15 11.7 15.225 11.55ZM6 9C6 7.35 7.35 6 9 6C10.65 6 12 7.35 12 9C12 10.65 10.65 12 9 12C7.35 12 6 10.65 6 9ZM7.5 9C7.5 9.825 8.175 10.5 9 10.5C9.825 10.5 10.5 9.825 10.5 9C10.5 8.175 9.825 7.5 9 7.5C8.175 7.5 7.5 8.175 7.5 9ZM14.325 13.2C13.725 12.525 13.575 11.7 13.875 10.95C14.175 10.2 14.925 9.75 15.675 9.75C16.2 9.75 16.5 9.45 16.5 9C16.5 8.55 16.2 8.25 15.75 8.25H15.6C14.85 8.25 14.1 7.8 13.8 7.05C13.725 6.975 13.725 6.9 13.725 6.825C13.5 6.15 13.65 5.325 14.175 4.8C14.55 4.425 14.55 3.975 14.25 3.675C14.1 3.525 13.95 3.45 13.725 3.45C13.5 3.45 13.35 3.525 13.2 3.675C12.525 4.275 11.7 4.425 10.95 4.125C10.2 3.825 9.75 3.15 9.75 2.325C9.75 1.8 9.45 1.5 9 1.5C8.55 1.5 8.25 1.8 8.25 2.25V2.4C8.25 3.15 7.8 3.9 7.05 4.2C6.975 4.275 6.9 4.275 6.825 4.275C6.15 4.5 5.325 4.35 4.8 3.825C4.425 3.45 3.975 3.45 3.675 3.75C3.375 4.05 3.375 4.5 3.675 4.8C4.275 5.475 4.425 6.3 4.125 7.05C3.825 7.8 3.15 8.325 2.325 8.325H2.25C1.8 8.325 1.5 8.625 1.5 9.075C1.5 9.525 1.8 9.825 2.25 9.825H2.4C3.15 9.825 3.9 10.275 4.2 11.025C4.575 11.775 4.425 12.6 3.825 13.2C3.6 13.425 3.525 13.575 3.525 13.8C3.525 14.025 3.6 14.175 3.75 14.325C4.05 14.625 4.5 14.625 4.8 14.325C5.175 13.95 5.7 13.725 6.225 13.725C6.525 13.725 6.825 13.725 7.05 13.875C7.8 14.175 8.325 14.85 8.325 15.675V15.75C8.325 16.2 8.625 16.5 9.075 16.5C9.525 16.5 9.825 16.2 9.825 15.75V15.6C9.825 14.85 10.275 14.1 11.025 13.8C11.775 13.425 12.6 13.575 13.2 14.175C13.425 14.4 13.575 14.475 13.8 14.475C14.025 14.475 14.175 14.4 14.325 14.25C14.475 14.1 14.55 13.95 14.55 13.725C14.55 13.5 14.475 13.35 14.325 13.2Z"
							fill="black"></path>
					</svg>
				</span>Setting
			</a>
		</div>
		@if (isset($dataType) && !empty($dataType) && $dataType == 'account')
			@include('admin.members.account')
		@elseif(isset($dataType) && !empty($dataType) && $dataType == 'edit')
			@include('admin.members.edit')
		@elseif(isset($dataType) && !empty($dataType) && $dataType == 'programme')
			<livewire:admin.member-programe-management :member_info_id=$dataId />
		@elseif(isset($dataType) && !empty($dataType) && $dataType == 'hall-booking')
			<livewire:admin.hall-booking-management :user_type_id=$dataId />
		@elseif(isset($dataType) && !empty($dataType) && $dataType == 'evnet-booking')
			<livewire:admin.event-booking-management :member_id=$dataId />
		@elseif(isset($dataType) && !empty($dataType) && $dataType == 'private-event-booking')
        	<livewire:admin.private-event-order-management :member_id=$dataId />
		@elseif(isset($dataType) && !empty($dataType) && $dataType == 'settings')
			@include('admin.members.settings')
		@elseif(isset($dataType) && !empty($dataType) && $dataType == 'show')
			@include('admin.members.show')
		@endif

		<div class="content-backdrop fade "></div>
		<!-- Content wrapper -->
	</div>
@endsection
@push('foorterscript')
	<script>
		function readURL(elem) {
			let reader = new FileReader();
			reader.onload = (e) => {
				$('#memberProfile').attr('src', e.target.result);
			}

			Swal.fire({
				title: "Are you sure?",
				text: "Are you sure change profile image.",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, Update it.",
			}).then((result) => {
				if (result.isConfirmed) {
					reader.readAsDataURL(elem.files[0]);
					var form_data = new FormData();
					form_data.append("_token", "{{ csrf_token() }}");
					form_data.append("file", elem.files[0]);
					$.ajax({
						contentType: false,
						processData: false,
						type: 'POST',
						url: "{{ route('admin.members.memberimageChange', [$MemberInfo->id]) }}",
						data: form_data,
						success: function(data) {},
					});
				}
			});
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
				if (result.isConfirmed) {
					var form_data = new FormData();
					form_data.append("_token", "{{ csrf_token() }}");
					form_data.append("status", value);

					$.ajax({
						contentType: false,
						processData: false,
						type: 'POST',
						url: "{!! route('admin.members.memberselectstatuschange', [$MemberInfo->id]) !!}",
						data: form_data,
						success: function(data) {},
					});
					location.reload();
				}
			});
		}

		function checkmemberinfo(value, id) {
			Swal.fire({
				title: "Are you sure?",
				text: "Are you sure change member status.",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, Update it.",
			}).then((result) => {
				if (result.isConfirmed) {
					var form_data = new FormData();
					form_data.append("_token", "{{ csrf_token() }}");
					form_data.append("status", value);

					$.ajax({
						contentType: false,
						processData: false,
						type: 'POST',
						url: "{!! route('admin.members.memberinfostatus', [$MemberInfo->id]) !!}",
						data: form_data,
						success: function(data) {},
					});
					location.reload();
				}
			});
		}
	</script>
@endpush

<style>
.img-circle>img {
    width: 108px !important;
    height: 95px !important;
 }
</style>
