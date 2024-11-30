@extends('admin.layouts.index')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="profile-img-box">
            <div class="row">
                <div class="col-6">
                    <div class="profile-part">
                        <div class="profile-text">
                            <h4 class="Profile-name">Import / #{{$dataId}}</h4>
                            <div class="profilte-btn">
                                <span class="badge rounded-pill badge-custom @if(isset($importDataInfo->status) && $importDataInfo->status == '1') green @else gray @endif">@if(isset($importDataInfo->status) && $importDataInfo->status == '1') Success @else Failed @endif</span>
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
                                {!! Form::open(['method' => 'DELETE','route' => ['admin.import.destroy', $importDataInfo->id],'style'=>'display:inline','id'=>'delete_form_'.$importDataInfo->id]) !!}
                                {!! Form::close() !!}
                                <a class="dropdown-item" href="javascript:void(0);" onclick="delete_info('{{$importDataInfo->id}}')" title="Delete">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="profile-page-buttons-section">
            <a href="{{route('admin.import.importDetail',[$importDataInfo->id,'detail'])}}">
                <button type="button" class="btn btn-custom  @if(isset($dataType) && !empty($dataType) && $dataType == 'detail') active @endif">
                      <span><svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.725 0.978039C0.95 0.753039 1.25 0.678039 1.55 0.828039C1.625 0.828039 1.7 0.903039 1.775 0.978039C1.925 1.12804 2 1.27804 2 1.50304C2 1.72804 1.925 1.87804 1.775 2.02804C1.625 2.17804 1.475 2.25304 1.25 2.25304H1.1C1.025 2.17804 1.025 2.17804 0.95 2.17804C0.9125 2.17804 0.89375 2.15929 0.875 2.14054C0.85625 2.12179 0.8375 2.10304 0.8 2.10304L0.725 2.02804C0.6875 1.99054 0.66875 1.95304 0.65 1.91554C0.63125 1.87804 0.6125 1.84054 0.575 1.80304C0.5 1.72804 0.5 1.57804 0.5 1.50304C0.5 1.42804 0.5 1.27804 0.575 1.20304C0.575 1.12804 0.65 1.05304 0.725 0.978039ZM5 0.753039C4.55 0.753039 4.25 1.05304 4.25 1.50304C4.25 1.95304 4.55 2.25304 5 2.25304H14.75C15.2 2.25304 15.5 1.95304 15.5 1.50304C15.5 1.05304 15.2 0.753039 14.75 0.753039H5ZM5 5.25304H14.75C15.2 5.25304 15.5 5.55304 15.5 6.00304C15.5 6.45304 15.2 6.75304 14.75 6.75304H5C4.55 6.75304 4.25 6.45304 4.25 6.00304C4.25 5.55304 4.55 5.25304 5 5.25304ZM14.75 9.75304H5C4.55 9.75304 4.25 10.053 4.25 10.503C4.25 10.953 4.55 11.253 5 11.253H14.75C15.2 11.253 15.5 10.953 15.5 10.503C15.5 10.053 15.2 9.75304 14.75 9.75304ZM1.925 5.70304C1.925 5.66554 1.90625 5.64679 1.8875 5.62804C1.86875 5.60929 1.85 5.59054 1.85 5.55304C1.85 5.47804 1.775 5.47804 1.775 5.47804C1.55 5.25304 1.25 5.17804 0.95 5.32804C0.9125 5.36554 0.875 5.38429 0.8375 5.40304C0.8 5.42179 0.7625 5.44054 0.725 5.47804L0.65 5.55304C0.65 5.59054 0.63125 5.60929 0.6125 5.62804C0.59375 5.64679 0.575 5.66554 0.575 5.70304C0.575 5.73491 0.575 5.75324 0.569245 5.76953C0.561456 5.79158 0.543129 5.80991 0.5 5.85304V6.00304C0.5 6.22804 0.575 6.37804 0.725 6.52804C0.875 6.67804 1.025 6.75304 1.25 6.75304C1.475 6.75304 1.625 6.67804 1.775 6.52804C1.925 6.37804 2 6.22804 2 6.00304V5.85304C2 5.81554 1.98125 5.79679 1.9625 5.77804C1.94375 5.75929 1.925 5.74054 1.925 5.70304ZM0.575 10.203C0.575 10.128 0.65 10.053 0.725 9.97804C1.025 9.67804 1.475 9.67804 1.775 9.97804C1.925 10.128 2 10.278 2 10.503C2 10.728 1.925 10.878 1.775 11.028C1.625 11.178 1.475 11.253 1.25 11.253C1.025 11.253 0.875 11.178 0.725 11.028C0.575 10.878 0.5 10.728 0.5 10.503C0.5 10.428 0.5 10.278 0.575 10.203Z" fill="black"></path>
                        </svg>
                        </span>Details
                </button>
            </a>
            <a href="{{route('admin.import.importDetail',[$importDataInfo->id,'record'])}}">
                <button type="button" class="btn btn-custom  @if(isset($dataType) && !empty($dataType) && $dataType == 'record') active @endif">
                      <span><svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.25 7.5H12.5V5.25C12.5 2.775 10.475 0.75 8 0.75C5.525 0.75 3.5 2.775 3.5 5.25V7.5H2.75C1.475 7.5 0.5 8.475 0.5 9.75V15C0.5 16.275 1.475 17.25 2.75 17.25H13.25C14.525 17.25 15.5 16.275 15.5 15V9.75C15.5 8.475 14.525 7.5 13.25 7.5ZM5 5.25C5 3.6 6.35 2.25 8 2.25C9.65 2.25 11 3.6 11 5.25V7.5H5V5.25ZM13.25 15.75C13.7 15.75 14 15.45 14 15V9.75C14 9.3 13.7 9 13.25 9H2.75C2.3 9 2 9.3 2 9.75V15C2 15.45 2.3 15.75 2.75 15.75H13.25Z" fill="black"></path>
                        </svg>
                        </span>Records({{$importDataInfo['type']}})
                </button>
            </a>
        </div>
        @if(isset($dataType) && !empty($dataType) && $dataType == 'record')
            @if(isset($importDataInfo['type']) && !empty($importDataInfo['type']) && $importDataInfo['type'] == 'Member')
            <livewire:admin.import-member-detail-management :import_data_info_id=$dataId />
            @elseif(isset($importDataInfo['type']) && !empty($importDataInfo['type']) && $importDataInfo['type'] == 'Programme')
            <livewire:admin.import-programme-detail-management :import_data_info_id=$dataId />
            @elseif(isset($importDataInfo['type']) && !empty($importDataInfo['type']) && $importDataInfo['type'] == 'Country')
            <livewire:admin.import-country-detail-management :import_data_info_id=$dataId />
            @elseif(isset($importDataInfo['type']) && !empty($importDataInfo['type']) && $importDataInfo['type'] == 'Event')
            <livewire:admin.import-evet-details-management :import_data_info_id=$dataId />
            @elseif(isset($importDataInfo['type']) && !empty($importDataInfo['type']) && $importDataInfo['type'] == 'Private Event')
            <livewire:admin.import-private-event-setting-details-management :import_data_info_id=$dataId />
            @elseif(isset($importDataInfo['type']) && !empty($importDataInfo['type']) && $importDataInfo['type'] == 'Private Event Booking')
            <livewire:admin.import-private-event-details-management :import_data_info_id=$dataId />
             @elseif(isset($importDataInfo['type']) && !empty($importDataInfo['type']) && $importDataInfo['type'] == 'Hall')
            <livewire:admin.import-hall-details-management :import_data_info_id=$dataId />
            @elseif(isset($importDataInfo['type']) && !empty($importDataInfo['type']) && $importDataInfo['type'] == 'Hotel')
            <livewire:admin.import-hotel-details-management :import_data_info_id=$dataId />

            @elseif(isset($importDataInfo['type']) && !empty($importDataInfo['type']) && $importDataInfo['type'] == 'Room')
            <livewire:admin.import-room-details-management :import_data_info_id=$dataId />
            @endif

        @else
            <div class="card custom-card profile-details margin-b-20">
                <div class="basic-details">
                    <h6 class="card-heading">Import Info</h6>
                </div>
                <div class="table-responsive table-details">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Create Date</th>
                                <td>@if(isset($importDataInfo->created_at) && !empty($importDataInfo->created_at)) {{date('Y-m-d' , strtotime($importDataInfo->created_at))}} @endif</td>
                            </tr>
                            <tr>
                                <th class="t-basic">Create Time</th>
                                <td>@if(isset($importDataInfo->created_at) && !empty($importDataInfo->created_at)) {{date('h:i:s' , strtotime($importDataInfo->created_at))}} @endif</td>
                            </tr>
                            <tr>
                                <th class="t-basic">Import #</th>
                                <td>@if(isset($importDataInfo->id) && !empty($importDataInfo->id)) #{{$importDataInfo->id}} @endif</td>
                            </tr>
                            <tr>
                                <th class="t-basic">Type</th>
                                <td>@if(isset($importDataInfo->type) && !empty($importDataInfo->type)) {{$importDataInfo->type}} @endif</td>
                            </tr>
                            <tr>
                                <th class="t-basic">Admin User</th>
                                <td>@if(isset($importDataInfo->getUserDetail->name) && !empty($importDataInfo->getUserDetail->name)) {{$importDataInfo->getUserDetail->name}} @endif</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- / Content -->


        <div class="content-backdrop fade "></div>
        <!-- Content wrapper -->
    </div>
@endsection