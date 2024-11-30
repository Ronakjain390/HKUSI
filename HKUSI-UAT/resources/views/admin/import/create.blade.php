@extends('admin.layouts.index')
@section('content')
 <div class="container-xxl flex-grow-1 container-p-y">
    <div class="profile-page-buttons-section pt-0">
    	<a href="{{route('admin.importData',['student'])}}">
        <button type="button" class="btn btn-custom @if(isset($dataType) && !empty($dataType) && $dataType == 'student') active @endif">
              <span><svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 4.25C3.25 2.15 4.9 0.5 7 0.5C9.1 0.5 10.75 2.15 10.75 4.25C10.75 6.35 9.1 8 7 8C4.9 8 3.25 6.35 3.25 4.25ZM13.75 13.25V14.75C13.75 15.2 13.45 15.5 13 15.5C12.55 15.5 12.25 15.2 12.25 14.75V13.25C12.25 11.975 11.275 11 10 11H4C2.725 11 1.75 11.975 1.75 13.25V14.75C1.75 15.2 1.45 15.5 1 15.5C0.55 15.5 0.25 15.2 0.25 14.75V13.25C0.25 11.15 1.9 9.5 4 9.5H10C12.1 9.5 13.75 11.15 13.75 13.25ZM7 6.5C5.725 6.5 4.75 5.525 4.75 4.25C4.75 2.975 5.725 2 7 2C8.275 2 9.25 2.975 9.25 4.25C9.25 5.525 8.275 6.5 7 6.5Z" fill="#424242"/>
                </svg>                                    
                </span>Student
        </button></a>
    	<a href="{{route('admin.importData',['programme'])}}">
        <button type="button" class="btn btn-custom @if(isset($dataType) && !empty($dataType) && $dataType == 'programme') active @endif">
             <span><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M15 3.5H12.75V2.75C12.75 1.475 11.775 0.5 10.5 0.5H7.5C6.225 0.5 5.25 1.475 5.25 2.75V3.5H3C1.725 3.5 0.75 4.475 0.75 5.75V13.25C0.75 14.525 1.725 15.5 3 15.5H15C16.275 15.5 17.25 14.525 17.25 13.25V5.75C17.25 4.475 16.275 3.5 15 3.5ZM6.75 2.75C6.75 2.3 7.05 2 7.5 2H10.5C10.95 2 11.25 2.3 11.25 2.75V3.5H6.75V2.75ZM11.25 14V5H6.75V14H11.25ZM2.25 13.25V5.75C2.25 5.3 2.55 5 3 5H5.25V14H3C2.55 14 2.25 13.7 2.25 13.25ZM15 14C15.45 14 15.75 13.7 15.75 13.25V5.75C15.75 5.3 15.45 5 15 5H12.75V14H15Z" fill="black"></path>
                </svg></span>
                Programme
        </button></a>
        <a href="{{route('admin.importData',['hall'])}}">
        <button type="button" class="btn btn-custom @if(isset($dataType) && !empty($dataType) && $dataType == 'hall') active @endif">
             <span><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M15 3.5H12.75V2.75C12.75 1.475 11.775 0.5 10.5 0.5H7.5C6.225 0.5 5.25 1.475 5.25 2.75V3.5H3C1.725 3.5 0.75 4.475 0.75 5.75V13.25C0.75 14.525 1.725 15.5 3 15.5H15C16.275 15.5 17.25 14.525 17.25 13.25V5.75C17.25 4.475 16.275 3.5 15 3.5ZM6.75 2.75C6.75 2.3 7.05 2 7.5 2H10.5C10.95 2 11.25 2.3 11.25 2.75V3.5H6.75V2.75ZM11.25 14V5H6.75V14H11.25ZM2.25 13.25V5.75C2.25 5.3 2.55 5 3 5H5.25V14H3C2.55 14 2.25 13.7 2.25 13.25ZM15 14C15.45 14 15.75 13.7 15.75 13.25V5.75C15.75 5.3 15.45 5 15 5H12.75V14H15Z" fill="black"></path>
                </svg></span>
                Hall
        </button></a>
        <a href="{{route('admin.importData',['room'])}}">
        <button type="button" class="btn btn-custom @if(isset($dataType) && !empty($dataType) && $dataType == 'room') active @endif">
        <span><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M15 0.5H3C1.725 0.5 0.75 1.475 0.75 2.75V7.25C0.75 11.825 4.425 15.5 9 15.5C13.575 15.5 17.25 11.825 17.25 7.25V2.75C17.25 1.475 16.275 0.5 15 0.5ZM15.75 7.25C15.75 11 12.75 14 9 14C5.25 14 2.25 11 2.25 7.25V2.75C2.25 2.3 2.55 2 3 2H15C15.45 2 15.75 2.3 15.75 2.75V7.25ZM12.525 7.025C12.825 6.725 12.825 6.275 12.525 5.975C12.225 5.675 11.775 5.675 11.475 5.975L9 8.45L6.525 5.975C6.225 5.675 5.775 5.675 5.475 5.975C5.175 6.275 5.175 6.725 5.475 7.025L8.475 10.025C8.625 10.175 8.775 10.25 9 10.25C9.225 10.25 9.375 10.175 9.525 10.025L12.525 7.025Z" fill="black"></path>
            </svg>
          </span>Room
        </button></a>
        <a href="{{route('admin.importData',['hotel'])}}">
        <button type="button" class="btn btn-custom @if(isset($dataType) && !empty($dataType) && $dataType == 'hotel') active @endif">
        <span><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M15 0.5H3C1.725 0.5 0.75 1.475 0.75 2.75V7.25C0.75 11.825 4.425 15.5 9 15.5C13.575 15.5 17.25 11.825 17.25 7.25V2.75C17.25 1.475 16.275 0.5 15 0.5ZM15.75 7.25C15.75 11 12.75 14 9 14C5.25 14 2.25 11 2.25 7.25V2.75C2.25 2.3 2.55 2 3 2H15C15.45 2 15.75 2.3 15.75 2.75V7.25ZM12.525 7.025C12.825 6.725 12.825 6.275 12.525 5.975C12.225 5.675 11.775 5.675 11.475 5.975L9 8.45L6.525 5.975C6.225 5.675 5.775 5.675 5.475 5.975C5.175 6.275 5.175 6.725 5.475 7.025L8.475 10.025C8.625 10.175 8.775 10.25 9 10.25C9.225 10.25 9.375 10.175 9.525 10.025L12.525 7.025Z" fill="black"></path>
            </svg>
          </span>Hotel
        </button></a>
    	<a href="{{route('admin.importData',['event'])}}">
        <button type="button" class="btn btn-custom @if(isset($dataType) && !empty($dataType) && $dataType == 'event') active @endif">
        <span><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M15 0.5H3C1.725 0.5 0.75 1.475 0.75 2.75V7.25C0.75 11.825 4.425 15.5 9 15.5C13.575 15.5 17.25 11.825 17.25 7.25V2.75C17.25 1.475 16.275 0.5 15 0.5ZM15.75 7.25C15.75 11 12.75 14 9 14C5.25 14 2.25 11 2.25 7.25V2.75C2.25 2.3 2.55 2 3 2H15C15.45 2 15.75 2.3 15.75 2.75V7.25ZM12.525 7.025C12.825 6.725 12.825 6.275 12.525 5.975C12.225 5.675 11.775 5.675 11.475 5.975L9 8.45L6.525 5.975C6.225 5.675 5.775 5.675 5.475 5.975C5.175 6.275 5.175 6.725 5.475 7.025L8.475 10.025C8.625 10.175 8.775 10.25 9 10.25C9.225 10.25 9.375 10.175 9.525 10.025L12.525 7.025Z" fill="black"></path>
            </svg>
          </span>Event
        </button></a>
        <a href="{{route('admin.importData',['country'])}}">
        <button type="button" class="btn btn-custom @if(isset($dataType) && !empty($dataType) && $dataType == 'country') active @endif">
              <span><svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 4.25C3.25 2.15 4.9 0.5 7 0.5C9.1 0.5 10.75 2.15 10.75 4.25C10.75 6.35 9.1 8 7 8C4.9 8 3.25 6.35 3.25 4.25ZM13.75 13.25V14.75C13.75 15.2 13.45 15.5 13 15.5C12.55 15.5 12.25 15.2 12.25 14.75V13.25C12.25 11.975 11.275 11 10 11H4C2.725 11 1.75 11.975 1.75 13.25V14.75C1.75 15.2 1.45 15.5 1 15.5C0.55 15.5 0.25 15.2 0.25 14.75V13.25C0.25 11.15 1.9 9.5 4 9.5H10C12.1 9.5 13.75 11.15 13.75 13.25ZM7 6.5C5.725 6.5 4.75 5.525 4.75 4.25C4.75 2.975 5.725 2 7 2C8.275 2 9.25 2.975 9.25 4.25C9.25 5.525 8.275 6.5 7 6.5Z" fill="#424242"/>
                </svg>                                    
                </span>Country
        </button></a>

        {{-- Private event added by Akash --}}
        <a href="{{route('admin.importData',['private-event'])}}">
        <button type="button" class="btn btn-custom @if(isset($dataType) && !empty($dataType) && $dataType == 'private-event') active @endif">
            <span><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M15 0.5H3C1.725 0.5 0.75 1.475 0.75 2.75V7.25C0.75 11.825 4.425 15.5 9 15.5C13.575 15.5 17.25 11.825 17.25 7.25V2.75C17.25 1.475 16.275 0.5 15 0.5ZM15.75 7.25C15.75 11 12.75 14 9 14C5.25 14 2.25 11 2.25 7.25V2.75C2.25 2.3 2.55 2 3 2H15C15.45 2 15.75 2.3 15.75 2.75V7.25ZM12.525 7.025C12.825 6.725 12.825 6.275 12.525 5.975C12.225 5.675 11.775 5.675 11.475 5.975L9 8.45L6.525 5.975C6.225 5.675 5.775 5.675 5.475 5.975C5.175 6.275 5.175 6.725 5.475 7.025L8.475 10.025C8.625 10.175 8.775 10.25 9 10.25C9.225 10.25 9.375 10.175 9.525 10.025L12.525 7.025Z" fill="black"></path>
                </svg>                                  
            </span>Private Event
        </button></a>

        {{-- Private eventBooking added by Akash --}}
        <a href="{{route('admin.importData',['private-event-order'])}}">
        <button type="button" class="btn btn-custom @if(isset($dataType) && !empty($dataType) && $dataType == 'private-event-order') active @endif">
            <span><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M15 0.5H3C1.725 0.5 0.75 1.475 0.75 2.75V7.25C0.75 11.825 4.425 15.5 9 15.5C13.575 15.5 17.25 11.825 17.25 7.25V2.75C17.25 1.475 16.275 0.5 15 0.5ZM15.75 7.25C15.75 11 12.75 14 9 14C5.25 14 2.25 11 2.25 7.25V2.75C2.25 2.3 2.55 2 3 2H15C15.45 2 15.75 2.3 15.75 2.75V7.25ZM12.525 7.025C12.825 6.725 12.825 6.275 12.525 5.975C12.225 5.675 11.775 5.675 11.475 5.975L9 8.45L6.525 5.975C6.225 5.675 5.775 5.675 5.475 5.975C5.175 6.275 5.175 6.725 5.475 7.025L8.475 10.025C8.625 10.175 8.775 10.25 9 10.25C9.225 10.25 9.375 10.175 9.525 10.025L12.525 7.025Z" fill="black"></path>
                </svg>                                  
            </span>Private Event Booking
        </button></a>
    </div>
    @if(isset($dataType) && !empty($dataType) && $dataType == 'student')  
		<livewire:admin.import-member-management />
    @elseif(isset($dataType) && !empty($dataType) && $dataType == 'programme')
        <livewire:admin.import-programme-management />
    @elseif(isset($dataType) && !empty($dataType) && $dataType == 'hall')
         <livewire:admin.import-hall-management />
    @elseif(isset($dataType) && !empty($dataType) && $dataType == 'room')
        <livewire:admin.import-room-management />
    @elseif(isset($dataType) && !empty($dataType) && $dataType == 'hotel')
        <livewire:admin.import-hotel-management />
    @elseif(isset($dataType) && !empty($dataType) && $dataType == 'event')
        <livewire:admin.import-event-management />
    @elseif(isset($dataType) && !empty($dataType) && $dataType == 'country')
        <livewire:admin.import-country-management /> 
        
    {{-- Private Event By Akash --}}
    @elseif(isset($dataType) && !empty($dataType) && $dataType == 'private-event')
        <livewire:admin.import-private-event-management /> 
    @elseif(isset($dataType) && !empty($dataType) && $dataType == 'private-event-order')
        <livewire:admin.import-private-event-order-management />
    @endif
    <!-- / Content -->
    <div class="content-backdrop fade "></div>
    <!-- Content wrapper -->
</div>
@endsection