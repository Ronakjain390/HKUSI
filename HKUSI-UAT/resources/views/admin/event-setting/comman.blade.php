@extends('admin.layouts.index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="profile-img-box">
        <div class="row">
            <div class="col-6">
                <div class="profile-part">
                    <div class="profile-text">
                        <h4 class="Profile-name">@if(isset($eventInfo->event_name) && !empty($eventInfo->event_name))  {{$eventInfo->event_name}}@endif  </h4>
                        <div class="profilte-btn"><span class="badge rounded-pill badge-custom @if($eventInfo->status=='Enabled') green @elseif($eventInfo->status=='Disabled') brown  @elseif($eventInfo->status=='Cancelled') gray @endif">{{$eventInfo->status}}</span></a>
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
                            @if(Request::segment(4) == "edit")
                              <a class="dropdown-item" href="{{route('admin.eventSettingDetails',[$eventInfo->id,'show'])}}">View</a>
                            @endif
                            @if(Request::segment(4) == "images")
                             <a class="dropdown-item" href="{{route('admin.eventSettingDetails',[$eventInfo->id,'editimage'])}}">Edit</a>
                             @elseif(Request::segment(4) == "editimage")
                                <a class="dropdown-item" href="{{route('admin.eventSettingDetails',[$eventInfo->id,'images'])}}">Images</a>
                             @elseif(Request::segment(4) == "edit")
                             @else
                               <a class="dropdown-item" href="{{route('admin.eventSettingDetails',[$eventInfo->id,'edit'])}}">Edit</a>
                           @endif
                            {!! Form::open(['method' => 'DELETE','route' => ['admin.event-setting.destroy', $eventInfo->id],'style'=>'display:inline','id'=>'delete_form_'.$eventInfo->id]) !!}
                            {!! Form::close() !!}
                            <a class="dropdown-item" onclick="delete_member('{{$eventInfo->id}}')" href="javascript:void(0)">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="profile-page-buttons-section" id="active">
        <a href="{{route('admin.eventSettingDetails',[$eventInfo->id,'show'])}}"  class="btn btn-custom @if((isset($dataType) && !empty($dataType) && $dataType == 'show') || (isset($dataType) && !empty($dataType) && $dataType == 'edit'))  active @endif">
              <span><svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.725 0.978039C0.95 0.753039 1.25 0.678039 1.55 0.828039C1.625 0.828039 1.7 0.903039 1.775 0.978039C1.925 1.12804 2 1.27804 2 1.50304C2 1.72804 1.925 1.87804 1.775 2.02804C1.625 2.17804 1.475 2.25304 1.25 2.25304H1.1C1.025 2.17804 1.025 2.17804 0.95 2.17804C0.9125 2.17804 0.89375 2.15929 0.875 2.14054C0.85625 2.12179 0.8375 2.10304 0.8 2.10304L0.725 2.02804C0.6875 1.99054 0.66875 1.95304 0.65 1.91554C0.63125 1.87804 0.6125 1.84054 0.575 1.80304C0.5 1.72804 0.5 1.57804 0.5 1.50304C0.5 1.42804 0.5 1.27804 0.575 1.20304C0.575 1.12804 0.65 1.05304 0.725 0.978039ZM5 0.753039C4.55 0.753039 4.25 1.05304 4.25 1.50304C4.25 1.95304 4.55 2.25304 5 2.25304H14.75C15.2 2.25304 15.5 1.95304 15.5 1.50304C15.5 1.05304 15.2 0.753039 14.75 0.753039H5ZM5 5.25304H14.75C15.2 5.25304 15.5 5.55304 15.5 6.00304C15.5 6.45304 15.2 6.75304 14.75 6.75304H5C4.55 6.75304 4.25 6.45304 4.25 6.00304C4.25 5.55304 4.55 5.25304 5 5.25304ZM14.75 9.75304H5C4.55 9.75304 4.25 10.053 4.25 10.503C4.25 10.953 4.55 11.253 5 11.253H14.75C15.2 11.253 15.5 10.953 15.5 10.503C15.5 10.053 15.2 9.75304 14.75 9.75304ZM1.925 5.70304C1.925 5.66554 1.90625 5.64679 1.8875 5.62804C1.86875 5.60929 1.85 5.59054 1.85 5.55304C1.85 5.47804 1.775 5.47804 1.775 5.47804C1.55 5.25304 1.25 5.17804 0.95 5.32804C0.9125 5.36554 0.875 5.38429 0.8375 5.40304C0.8 5.42179 0.7625 5.44054 0.725 5.47804L0.65 5.55304C0.65 5.59054 0.63125 5.60929 0.6125 5.62804C0.59375 5.64679 0.575 5.66554 0.575 5.70304C0.575 5.73491 0.575 5.75324 0.569245 5.76953C0.561456 5.79158 0.543129 5.80991 0.5 5.85304V6.00304C0.5 6.22804 0.575 6.37804 0.725 6.52804C0.875 6.67804 1.025 6.75304 1.25 6.75304C1.475 6.75304 1.625 6.67804 1.775 6.52804C1.925 6.37804 2 6.22804 2 6.00304V5.85304C2 5.81554 1.98125 5.79679 1.9625 5.77804C1.94375 5.75929 1.925 5.74054 1.925 5.70304ZM0.575 10.203C0.575 10.128 0.65 10.053 0.725 9.97804C1.025 9.67804 1.475 9.67804 1.775 9.97804C1.925 10.128 2 10.278 2 10.503C2 10.728 1.925 10.878 1.775 11.028C1.625 11.178 1.475 11.253 1.25 11.253C1.025 11.253 0.875 11.178 0.725 11.028C0.575 10.878 0.5 10.728 0.5 10.503C0.5 10.428 0.5 10.278 0.575 10.203Z" fill="black"></path>
                </svg>
                </span>Details
         </a>
         <a href="{{route('admin.eventSettingDetails',[$eventInfo->id,'images'])}}"  class="btn btn-custom @if(isset($dataType) && !empty($dataType) && $dataType == 'images' || $dataType == 'editimage' )  active @endif">
            <span><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.25 15.5C10.95 15.5 10.65 15.275 10.575 14.975L6.75 3.65L5.175 8.3C5.1 8.525 4.8 8.75 4.5 8.75H1.5C1.05 8.75 0.75 8.45 0.75 8C0.75 7.55 1.05 7.25 1.5 7.25H3.975L6.075 1.025C6.15 0.725 6.45 0.5 6.75 0.5C7.05 0.5 7.35 0.725 7.425 1.025L11.25 12.35L12.825 7.7C12.9 7.4 13.2 7.175 13.5 7.175H16.5C16.95 7.175 17.25 7.475 17.25 7.925C17.25 8.375 16.95 8.675 16.5 8.675H14.025L11.925 14.9C11.85 15.275 11.55 15.5 11.25 15.5Z">
            </path></svg>
            </span>Images
        </a>
         <a href="{{route('admin.eventSettingDetails',[$eventInfo->id,'programme'])}}">
        <button type="button" class="btn btn-custom @if(isset($dataType) && !empty($dataType) && $dataType == 'programme') active @endif">
              <span><svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M13 0.75H2.875C1.45 0.75 0.25 1.95 0.25 3.375V14.625C0.25 16.05 1.45 17.25 2.875 17.25H13C13.45 17.25 13.75 16.95 13.75 16.5V1.5C13.75 1.05 13.45 0.75 13 0.75ZM2.875 2.25H12.25V12H2.875C2.5 12 2.125 12.075 1.75 12.3V3.375C1.75 2.775 2.275 2.25 2.875 2.25ZM1.75 14.625C1.75 15.225 2.275 15.75 2.875 15.75H12.25V13.5H2.875C2.275 13.5 1.75 14.025 1.75 14.625Z" fill="black"></path>
                </svg>
                </span>Programme
        </button></a>
       
    </button>
    </div>
    @if(isset($dataType) && !empty($dataType) && $dataType == 'edit')
        @include('admin.event-setting.edit')
    @elseif(isset($dataType) && !empty($dataType) && $dataType == 'show')
        @include('admin.event-setting.show')
    @elseif(isset($dataType) && !empty($dataType) && $dataType == 'images')
    <livewire:admin.event-images-management :event_id=$dataId />
    @elseif(isset($dataType) && !empty($dataType) && $dataType == "editimage")
    @include('admin.event-setting.event-images')
    @elseif(isset($dataType) && !empty($dataType) && $dataType == 'programme')
        <livewire:admin.event-programme-management :event_id=$dataId />
    @endif

    <div class="content-backdrop fade "></div>
    <!-- Content wrapper -->
</div>
@endsection