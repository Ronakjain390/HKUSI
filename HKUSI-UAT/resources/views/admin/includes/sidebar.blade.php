<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" data-bg-class="bg-menu-theme">
    <div class="app-brand demo">
        <a href="javascript:void(0);" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{asset('img/logo.svg')}}">
            </span>
            <!-- <span class="app-brand-text demo menu-text fw-bold">HKUSI</span> -->
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 ps ps--active-y">
        <!-- Dashboards -->
        <li class="menu-item {{ Request::segment(2) === 'dashboard' ? 'active' : null }}">
            <a href="{{route('admin.dashboard')}}" class="menu-link">
                <i class="menu-icon"><svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.2 6.1626L8.45 0.918558C8.15 0.693814 7.775 0.693814 7.55 0.918558L0.8 6.1626C0.575 6.31243 0.5 6.53717 0.5 6.76192V15.0026C0.5 16.2761 1.475 17.25 2.75 17.25H13.25C14.525 17.25 15.5 16.2761 15.5 15.0026V6.76192C15.5 6.53717 15.425 6.31243 15.2 6.1626ZM9.5 9.75851V15.7517H6.5V9.75851H9.5ZM13.25 15.7517C13.7 15.7517 14 15.452 14 15.0026V7.13649L8 2.49177L2 7.13649V15.0026C2 15.452 2.3 15.7517 2.75 15.7517H5V9.00936C5 8.55988 5.3 8.26022 5.75 8.26022H10.25C10.7 8.26022 11 8.55988 11 9.00936V15.7517H13.25Z">
                    </path></svg>                                
                </i>
                <div>Dashboard</div>
            </a>
        </li>

        <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">APPS</span>
        </li>
        @can('members-list')
        <li class="menu-item {{ Request::segment(2) === 'members' ||  Request::segment(2) === 'member-detail' ? 'active' : null }}">
            <a href="{{route('admin.members.index')}}" class="menu-link">
                <i class="menu-icon"><svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 4.25C3.25 2.15 4.9 0.5 7 0.5C9.1 0.5 10.75 2.15 10.75 4.25C10.75 6.35 9.1 8 7 8C4.9 8 3.25 6.35 3.25 4.25ZM13.75 13.25V14.75C13.75 15.2 13.45 15.5 13 15.5C12.55 15.5 12.25 15.2 12.25 14.75V13.25C12.25 11.975 11.275 11 10 11H4C2.725 11 1.75 11.975 1.75 13.25V14.75C1.75 15.2 1.45 15.5 1 15.5C0.55 15.5 0.25 15.2 0.25 14.75V13.25C0.25 11.15 1.9 9.5 4 9.5H10C12.1 9.5 13.75 11.15 13.75 13.25ZM7 6.5C5.725 6.5 4.75 5.525 4.75 4.25C4.75 2.975 5.725 2 7 2C8.275 2 9.25 2.975 9.25 4.25C9.25 5.525 8.275 6.5 7 6.5Z">
                    </path></svg>
                </i>
                <div>Members</div>
            </a>
        </li>
        @endcan
        @can('hallbooking-list')
        <li class="menu-item {{ Request::segment(2) === 'hallbooking' || Request::segment(2) === 'hallbooking-detail' ? 'active' : null }}">
            <a href="{{route('admin.hallbooking.index')}}" class="menu-link">
                <i class="menu-icon"><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15 3.5H12.75V2.75C12.75 1.475 11.775 0.5 10.5 0.5H7.5C6.225 0.5 5.25 1.475 5.25 2.75V3.5H3C1.725 3.5 0.75 4.475 0.75 5.75V13.25C0.75 14.525 1.725 15.5 3 15.5H15C16.275 15.5 17.25 14.525 17.25 13.25V5.75C17.25 4.475 16.275 3.5 15 3.5ZM6.75 2.75C6.75 2.3 7.05 2 7.5 2H10.5C10.95 2 11.25 2.3 11.25 2.75V3.5H6.75V2.75ZM11.25 14V5H6.75V14H11.25ZM2.25 13.25V5.75C2.25 5.3 2.55 5 3 5H5.25V14H3C2.55 14 2.25 13.7 2.25 13.25ZM15 14C15.45 14 15.75 13.7 15.75 13.25V5.75C15.75 5.3 15.45 5 15 5H12.75V14H15Z">
                    </path></svg>
                    </i>
                <div>Hall Booking</div>
            </a>
        </li>
        @endcan
        @can('eventbooking-list')
        <li class="menu-item {{ Request::segment(2) === 'eventbooking' ||  Request::segment(2) === 'event-payment' || Request::segment(2) ==='eventbooking-detail' ? 'active' : null }}">
            <a href="{{route('admin.eventbooking.index')}}" class="menu-link">
                <i class="menu-icon"><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15 0.5H3C1.725 0.5 0.75 1.475 0.75 2.75V7.25C0.75 11.825 4.425 15.5 9 15.5C13.575 15.5 17.25 11.825 17.25 7.25V2.75C17.25 1.475 16.275 0.5 15 0.5ZM15.75 7.25C15.75 11 12.75 14 9 14C5.25 14 2.25 11 2.25 7.25V2.75C2.25 2.3 2.55 2 3 2H15C15.45 2 15.75 2.3 15.75 2.75V7.25ZM12.525 7.025C12.825 6.725 12.825 6.275 12.525 5.975C12.225 5.675 11.775 5.675 11.475 5.975L9 8.45L6.525 5.975C6.225 5.675 5.775 5.675 5.475 5.975C5.175 6.275 5.175 6.725 5.475 7.025L8.475 10.025C8.625 10.175 8.775 10.25 9 10.25C9.225 10.25 9.375 10.175 9.525 10.025L12.525 7.025Z">
                    </path></svg>
                    </i>
                <div>Event Booking</div>
            </a>
        </li>
        @endcan
        <li class="menu-item {{ Request::segment(2) === 'private-event-order' || Request::segment(2) ==='private-event-order-detail' ? 'active' : null }}">
            <a href="{{route('admin.private-event-order.index')}}" class="menu-link">
                <i class="menu-icon"><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15 0.5H3C1.725 0.5 0.75 1.475 0.75 2.75V7.25C0.75 11.825 4.425 15.5 9 15.5C13.575 15.5 17.25 11.825 17.25 7.25V2.75C17.25 1.475 16.275 0.5 15 0.5ZM15.75 7.25C15.75 11 12.75 14 9 14C5.25 14 2.25 11 2.25 7.25V2.75C2.25 2.3 2.55 2 3 2H15C15.45 2 15.75 2.3 15.75 2.75V7.25ZM12.525 7.025C12.825 6.725 12.825 6.275 12.525 5.975C12.225 5.675 11.775 5.675 11.475 5.975L9 8.45L6.525 5.975C6.225 5.675 5.775 5.675 5.475 5.975C5.175 6.275 5.175 6.725 5.475 7.025L8.475 10.025C8.625 10.175 8.775 10.25 9 10.25C9.225 10.25 9.375 10.175 9.525 10.025L12.525 7.025Z">
                    </path></svg>
                    </i>
                <div>Private Event Booking</div>
            </a>
        </li>
       <!--  <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.75 1.5C3.75 1.08579 4.08579 0.75 4.5 0.75C4.91421 0.75 5.25 1.08579 5.25 1.5V3.75C5.25 4.16421 4.91421 4.5 4.5 4.5C4.08579 4.5 3.75 4.16421 3.75 3.75V1.5ZM14.25 11.9731V7.49083C14.7492 7.53768 15.3997 7.70517 15.8838 8.20969C16.7865 9.15039 16.4114 10.4086 16.4114 10.4086C16.4114 10.4086 16.0315 11.5002 15.0108 11.8914C14.686 11.9289 14.4382 11.955 14.25 11.9731ZM14.2425 13.4965C14.1152 15.5908 12.3764 17.25 10.25 17.25H4.75C2.54086 17.25 0.75 15.4591 0.75 13.25V6H13.5H14.25H14.3097C14.5469 6 14.6892 6.02594 14.8778 6.06033C14.9485 6.07322 15.0257 6.0873 15.1169 6.10165C16.9014 6.38365 17.7631 8.26759 17.9281 9C18.093 9.73241 18 11.0348 17.0427 12.2477C16.1987 13.3171 14.6073 13.4743 14.2425 13.4965ZM12.75 7.5H2.25V12.75C2.25 14.4069 3.59315 15.75 5.25 15.75H9.75C11.4069 15.75 12.75 14.4069 12.75 12.75V7.5ZM7.5 0.75C7.08579 0.75 6.75 1.08579 6.75 1.5V3.75C6.75 4.16421 7.08579 4.5 7.5 4.5C7.91421 4.5 8.25 4.16421 8.25 3.75V1.5C8.25 1.08579 7.91421 0.75 7.5 0.75ZM9.75 1.5C9.75 1.08579 10.0858 0.75 10.5 0.75C10.9142 0.75 11.25 1.08579 11.25 1.5V3.75C11.25 4.16421 10.9142 4.5 10.5 4.5C10.0858 4.5 9.75 4.16421 9.75 3.75V1.5Z">
                    </path></svg>
                    </i>
                <div>Dining Tokens</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link">
                        <div>Order</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link">
                        <div>Redemption</div>
                    </a>
                </li>

            </ul>
        </li> -->
         <li class="menu-item {{Request::segment(2) === 'payments' || Request::segment(2) === 'eventpayment'  ? 'open active' : null}}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon"><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.25 15.5C10.95 15.5 10.65 15.275 10.575 14.975L6.75 3.65L5.175 8.3C5.1 8.525 4.8 8.75 4.5 8.75H1.5C1.05 8.75 0.75 8.45 0.75 8C0.75 7.55 1.05 7.25 1.5 7.25H3.975L6.075 1.025C6.15 0.725 6.45 0.5 6.75 0.5C7.05 0.5 7.35 0.725 7.425 1.025L11.25 12.35L12.825 7.7C12.9 7.4 13.2 7.175 13.5 7.175H16.5C16.95 7.175 17.25 7.475 17.25 7.925C17.25 8.375 16.95 8.675 16.5 8.675H14.025L11.925 14.9C11.85 15.275 11.55 15.5 11.25 15.5Z">
                            </path></svg>
                            </i>
                <div>Payment</div>
            </a>
            <ul class="menu-sub">
                 @can('payment-list')
                 <li class="menu-item {{ Request::segment(2) === 'payments' || Request::segment(2) === 'payment-details' ? 'active' : null }}">
                    <a href="{{route('admin.payments.index')}}" class="menu-link">
                        <i class="menu-icon"><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.25 15.5C10.95 15.5 10.65 15.275 10.575 14.975L6.75 3.65L5.175 8.3C5.1 8.525 4.8 8.75 4.5 8.75H1.5C1.05 8.75 0.75 8.45 0.75 8C0.75 7.55 1.05 7.25 1.5 7.25H3.975L6.075 1.025C6.15 0.725 6.45 0.5 6.75 0.5C7.05 0.5 7.35 0.725 7.425 1.025L11.25 12.35L12.825 7.7C12.9 7.4 13.2 7.175 13.5 7.175H16.5C16.95 7.175 17.25 7.475 17.25 7.925C17.25 8.375 16.95 8.675 16.5 8.675H14.025L11.925 14.9C11.85 15.275 11.55 15.5 11.25 15.5Z">
                            </path></svg>
                            </i>
                        <div>Hall Payment</div>
                    </a>
                </li>
                @endcan
               @can('eventpayment-list')
                <li class="menu-item {{ Request::segment(2) === 'eventpayment' || Request::segment(2) === 'eventpayment-details' ? 'active' : null }}">
                    <a href="{{route('admin.eventpayment.index')}}" class="menu-link">
                        <i class="menu-icon"><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.25 15.5C10.95 15.5 10.65 15.275 10.575 14.975L6.75 3.65L5.175 8.3C5.1 8.525 4.8 8.75 4.5 8.75H1.5C1.05 8.75 0.75 8.45 0.75 8C0.75 7.55 1.05 7.25 1.5 7.25H3.975L6.075 1.025C6.15 0.725 6.45 0.5 6.75 0.5C7.05 0.5 7.35 0.725 7.425 1.025L11.25 12.35L12.825 7.7C12.9 7.4 13.2 7.175 13.5 7.175H16.5C16.95 7.175 17.25 7.475 17.25 7.925C17.25 8.375 16.95 8.675 16.5 8.675H14.025L11.925 14.9C11.85 15.275 11.55 15.5 11.25 15.5Z">
                            </path></svg>
                            </i>
                        <div>Event Payment</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link">
                <i class="menu-icon"><svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.6731 0.5C13.9016 0.5 15.0578 1.0198 15.9249 1.91089C16.8643 2.80198 17.3701 4.06436 17.2256 5.32673C17.2256 6.58911 16.7198 7.85149 15.8526 8.74257L9.49363 15.2772C9.34911 15.4257 9.20459 15.5 8.9878 15.5C8.77102 15.5 8.6265 15.4257 8.48197 15.2772L2.12297 8.74257C1.25583 7.85149 0.75 6.58911 0.75 5.32673C0.75 4.06436 1.25583 2.80198 2.19523 1.91089C4.00176 0.0544554 6.96448 0.0544554 8.77102 1.91089L9.06007 2.20792L9.34911 1.91089C10.2162 1.0198 11.4447 0.5 12.6731 0.5ZM9.06007 13.7178L14.9132 7.70297C15.5636 7.03465 15.9249 6.21782 15.9249 5.32673C15.9249 4.43564 15.5636 3.61881 14.9855 2.9505C14.3351 2.35644 13.5403 1.98515 12.6731 1.98515C11.7337 1.98515 10.9389 2.35644 10.2885 2.9505L9.5659 3.76733C9.27685 4.06436 8.84328 4.06436 8.55423 3.76733L7.75936 2.9505C7.10901 2.28218 6.31413 1.98515 5.44699 1.98515C4.65212 1.98515 3.78498 2.28218 3.20689 2.9505C2.6288 3.61881 2.26749 4.43564 2.26749 5.32673C2.26749 6.21782 2.55654 7.03465 3.20689 7.70297L9.06007 13.7178Z">
                    </path></svg>
                    </i>
                <div>Reports</div>
            </a>
        </li>
        @canany(['admin-user-list','admin-role-list'])
        <li class="menu-item {{ Request::segment(2) === 'users' || Request::segment(2) === 'roles'  ? 'open active' : null }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.25 0.5H6.5C6.95 0.5 7.25 0.8 7.25 1.25V6.5C7.25 6.95 6.95 7.25 6.5 7.25H1.25C0.8 7.25 0.5 6.95 0.5 6.5V1.25C0.5 0.8 0.8 0.5 1.25 0.5ZM2 5.75H5.75V2H2V5.75ZM14.75 0.5H9.5C9.05 0.5 8.75 0.8 8.75 1.25V6.5C8.75 6.95 9.05 7.25 9.5 7.25H14.75C15.2 7.25 15.5 6.95 15.5 6.5V1.25C15.5 0.8 15.2 0.5 14.75 0.5ZM10.25 5.75H14V2H10.25V5.75ZM14.75 8.75H9.5C9.05 8.75 8.75 9.05 8.75 9.5V14.75C8.75 15.2 9.05 15.5 9.5 15.5H14.75C15.2 15.5 15.5 15.2 15.5 14.75V9.5C15.5 9.05 15.2 8.75 14.75 8.75ZM10.25 14H14V10.25H10.25V14ZM6.5 8.75H1.25C0.8 8.75 0.5 9.05 0.5 9.5V14.75C0.5 15.2 0.8 15.5 1.25 15.5H6.5C6.95 15.5 7.25 15.2 7.25 14.75V9.5C7.25 9.05 6.95 8.75 6.5 8.75ZM2 14H5.75V10.25H2V14Z">
                    </path></svg>
                    </i>
                <div>Admin</div>
            </a>
            <ul class="menu-sub">
                @can('admin-user-list')
                <li class="menu-item {{ Request::segment(2) === 'users' ? 'active' : null }}">
                    <a href="{{route('admin.users.index')}}" class="menu-link">
                        <div>User</div>
                    </a>
                </li>
                @endcan
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link">
                        <div>Location</div>
                    </a>
                </li>
                @can('admin-role-list')
                <li class="menu-item {{ Request::segment(2) === 'roles' ? 'active' : null }}">
                    <a href="javascript:void(0);" class="menu-link">
                        <div>Role</div>
                    </a>
                </li>
                @endcan               
            </ul>
        </li>
        @endcanany
        @canany(['programmes-list','language-list','event-type','email-setting-list'])
        <li class="menu-item {{Request::segment(2) === 'programme-setting'|| Request::segment(2) === 'hotel-setting'|| Request::segment(2) === 'private-event-setting' || Request::segment(2) === 'accommondation-setting' || Request::segment(2) === 'quota' || Request::segment(2) === 'quota-hall' || Request::segment(2) === 'event-setting' || Request::segment(2) === 'language' || Request::segment(2) === 'event-type' || Request::segment(2) === 'room' || Request::segment(2) === 'adminappversion' || Request::segment(2) === 'studentappversion'|| Request::segment(2) === 'importantnotice'|| Request::segment(2) === 'email-setting' || Request::segment(2) === 'email-template'|| Request::segment(2) === 'country' ? 'open active' : null}}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.225 11.55C15.3 11.4 15.45 11.25 15.75 11.25C17.025 11.25 18 10.275 18 9C18 7.725 17.025 6.75 15.75 6.75H15.6C15.45 6.75 15.3 6.675 15.225 6.525C15.225 6.45 15.225 6.45 15.15 6.375C15.075 6.225 15.075 6 15.3 5.775C16.2 4.875 16.2 3.45 15.3 2.625C14.85 2.175 14.325 1.95 13.725 1.95C13.125 1.95 12.525 2.175 12.075 2.625C11.925 2.775 11.7 2.775 11.55 2.7C11.4 2.7 11.25 2.475 11.25 2.25C11.25 0.975 10.275 0 9 0C7.725 0 6.75 0.975 6.75 2.25V2.4C6.75 2.55 6.675 2.7 6.525 2.775C6.45 2.775 6.45 2.775 6.375 2.85C6.225 2.925 6 2.85 5.775 2.7C4.875 1.8 3.45 1.8 2.625 2.7C1.725 3.6 1.725 5.025 2.7 5.925C2.85 6.075 2.85 6.3 2.775 6.525C2.7 6.675 2.475 6.825 2.25 6.825C0.975 6.825 0 7.8 0 9.075C0 10.35 0.975 11.325 2.25 11.325H2.4C2.625 11.325 2.775 11.475 2.85 11.625C2.925 11.775 2.925 12 2.7 12.225C2.25 12.675 2.025 13.2 2.025 13.8C2.025 14.4 2.25 14.925 2.7 15.375C3.6 16.275 5.025 16.275 5.925 15.3C6.075 15.15 6.3 15.15 6.525 15.225C6.75 15.3 6.825 15.45 6.825 15.75C6.825 17.025 7.8 18 9.075 18C10.35 18 11.325 17.025 11.325 15.75V15.6C11.325 15.375 11.475 15.225 11.625 15.15C11.775 15.075 12 15.075 12.225 15.3C13.125 16.2 14.55 16.2 15.375 15.3C16.275 14.4 16.275 12.975 15.3 12.075C15.225 11.925 15.15 11.7 15.225 11.55ZM6 9C6 7.35 7.35 6 9 6C10.65 6 12 7.35 12 9C12 10.65 10.65 12 9 12C7.35 12 6 10.65 6 9ZM7.5 9C7.5 9.825 8.175 10.5 9 10.5C9.825 10.5 10.5 9.825 10.5 9C10.5 8.175 9.825 7.5 9 7.5C8.175 7.5 7.5 8.175 7.5 9ZM14.325 13.2C13.725 12.525 13.575 11.7 13.875 10.95C14.175 10.2 14.925 9.75 15.675 9.75C16.2 9.75 16.5 9.45 16.5 9C16.5 8.55 16.2 8.25 15.75 8.25H15.6C14.85 8.25 14.1 7.8 13.8 7.05C13.725 6.975 13.725 6.9 13.725 6.825C13.5 6.15 13.65 5.325 14.175 4.8C14.55 4.425 14.55 3.975 14.25 3.675C14.1 3.525 13.95 3.45 13.725 3.45C13.5 3.45 13.35 3.525 13.2 3.675C12.525 4.275 11.7 4.425 10.95 4.125C10.2 3.825 9.75 3.15 9.75 2.325C9.75 1.8 9.45 1.5 9 1.5C8.55 1.5 8.25 1.8 8.25 2.25V2.4C8.25 3.15 7.8 3.9 7.05 4.2C6.975 4.275 6.9 4.275 6.825 4.275C6.15 4.5 5.325 4.35 4.8 3.825C4.425 3.45 3.975 3.45 3.675 3.75C3.375 4.05 3.375 4.5 3.675 4.8C4.275 5.475 4.425 6.3 4.125 7.05C3.825 7.8 3.15 8.325 2.325 8.325H2.25C1.8 8.325 1.5 8.625 1.5 9.075C1.5 9.525 1.8 9.825 2.25 9.825H2.4C3.15 9.825 3.9 10.275 4.2 11.025C4.575 11.775 4.425 12.6 3.825 13.2C3.6 13.425 3.525 13.575 3.525 13.8C3.525 14.025 3.6 14.175 3.75 14.325C4.05 14.625 4.5 14.625 4.8 14.325C5.175 13.95 5.7 13.725 6.225 13.725C6.525 13.725 6.825 13.725 7.05 13.875C7.8 14.175 8.325 14.85 8.325 15.675V15.75C8.325 16.2 8.625 16.5 9.075 16.5C9.525 16.5 9.825 16.2 9.825 15.75V15.6C9.825 14.85 10.275 14.1 11.025 13.8C11.775 13.425 12.6 13.575 13.2 14.175C13.425 14.4 13.575 14.475 13.8 14.475C14.025 14.475 14.175 14.4 14.325 14.25C14.475 14.1 14.55 13.95 14.55 13.725C14.55 13.5 14.475 13.35 14.325 13.2Z">
                    </path></svg>
                    </i>
                <div>Settings</div>
            </a>
            <ul class="menu-sub">
                @can('programmes-list')
                <li class="menu-item {{Request::segment(2) === 'programme-setting' ? 'active' : null}}">
                    <a href="{{route('admin.programme-setting.index')}}" class="menu-link">
                        <div>Programme</div>
                    </a>
                </li>
                @endcan
                @can('hall-list')
                <li class="menu-item {{Request::segment(2) === 'accommondation-setting' || Request::segment(2) === 'quota' || Request::segment(2) === 'quota-hall' || Request::segment(2) === 'room' ? 'active' : null}}">
                    <a href="{{route('admin.accommondation-setting.index')}}" class="menu-link">
                        <div>Accommodation</div>
                    </a>
                </li>
                @endcan
                <li class="menu-item {{Request::segment(2) === 'hotel-setting' ? 'active' : null}}">
                    <a href="{{route('admin.hotel-setting.index')}}" class="menu-link">
                        <div>Hotel</div>
                    </a>
                </li>
                @can('event-setting-list')
                <li class="menu-item {{Request::segment(2) === 'event-setting' ? 'active' : null}}">
                    <a href="{{route('admin.event-setting.index')}}" class="menu-link">
                        <div>Event</div>
                    </a>
                </li>
                @endcan
                @can('event-setting-list')
                <li class="menu-item {{Request::segment(2) === 'private-event-setting' ? 'active' : null}}">
                    <a href="{{route('admin.private-event-setting.index')}}" class="menu-link">
                        <div>Private Event</div>
                    </a>
                </li>
                @endcan
                @can('diningtoken-list')
                <li class="menu-item {{ Request::segment(2) === 'dining-token' ? 'active' : null }}">
                    <a href="{{route('admin.dining-token.index')}}" class="menu-link">
                        <div>Dining Tokens</div>
                    </a>
                </li>
                @endcan
				<li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link">
                        <div>Facilities</div>
                    </a>
                </li>
                @can('email-setting-list')
                <li class="menu-item {{Request::segment(2) === 'email-setting' || Request::segment(2) === 'email-template'  ? 'active' : null}}">
                    <a href="{{route('admin.email-setting.index')}}" class="menu-link">
                        <div>Email</div>
                    </a>
                </li>
                @endcan
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link">
                        <div>Custom Field</div>
                    </a>
                </li>
                 @can('importantnotice-list')
                <li class="menu-item {{Request::segment(2) === 'importantnotice' ? 'active' : null}}">
                    <a href="{{route('admin.importantnotice.index')}}" class="menu-link">
                        <div>Important Notice</div>
                    </a>
                </li>
                @endcan
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link">
                        <div>Push Notification</div>
                    </a>
                </li>
                @can('country-list')
                <li class="menu-item {{Request::segment(2) === 'country' ? 'active' : null}}">
                    <a href="{{route('admin.country.index')}}" class="menu-link">
                        <div>Study Country</div>
                    </a>
                </li>
                @endcan
                 @can('evnettype-list')
                <li class="menu-item {{ Request::segment(2) === 'event-type' ? 'active' : null }}">
                    <a href="{{route('admin.event-type.index')}}" class="menu-link">
                        <div>Event Type</div>
                    </a>
                </li>
                @endcan
                @can('language-list')
                <li class="menu-item {{ Request::segment(2) === 'language' ? 'active' : null }}">
                    <a href="{{route('admin.language.index')}}" class="menu-link">
                        <div>Event Language</div>
                    </a>
                </li>
                @endcan
                <li class="menu-item {{Request::segment(2) === 'adminappversion' ? 'active' : null}}">
                    <a href="{{route('admin.adminappversion.index')}}" class="menu-link">
                        <div>Admin App Version</div>
                    </a>
                </li>
                <li class="menu-item {{Request::segment(2) === 'studentappversion' ? 'active' : null}}">
                    <a href="{{route('admin.studentappversion.index')}}" class="menu-link">
                        <div>Student App Version</div>
                    </a>
                </li>
               
            </ul>
        </li>
        @endcanany
        @canany(['images-list','import-history-list','export-history-list'])
        <li class="menu-item {{Request::segment(2) === 'imagebank' ||  Request::segment(2) === 'import' ||  Request::segment(2)==='activity-logs' || Request::segment(2) === 'export' ? 'open active' : null}}}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-database" viewBox="0 0 18 18">
                      <path d="M4.318 2.687C5.234 2.271 6.536 2 8 2s2.766.27 3.682.687C12.644 3.125 13 3.627 13 4c0 .374-.356.875-1.318 1.313C10.766 5.729 9.464 6 8 6s-2.766-.27-3.682-.687C3.356 4.875 3 4.373 3 4c0-.374.356-.875 1.318-1.313ZM13 5.698V7c0 .374-.356.875-1.318 1.313C10.766 8.729 9.464 9 8 9s-2.766-.27-3.682-.687C3.356 7.875 3 7.373 3 7V5.698c.271.202.58.378.904.525C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777A4.92 4.92 0 0 0 13 5.698ZM14 4c0-1.007-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1s-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4v9c0 1.007.875 1.755 1.904 2.223C4.978 15.71 6.427 16 8 16s3.022-.289 4.096-.777C13.125 14.755 14 14.007 14 13V4Zm-1 4.698V10c0 .374-.356.875-1.318 1.313C10.766 11.729 9.464 12 8 12s-2.766-.27-3.682-.687C3.356 10.875 3 10.373 3 10V8.698c.271.202.58.378.904.525C4.978 9.71 6.427 10 8 10s3.022-.289 4.096-.777A4.92 4.92 0 0 0 13 8.698Zm0 3V13c0 .374-.356.875-1.318 1.313C10.766 14.729 9.464 15 8 15s-2.766-.27-3.682-.687C3.356 13.875 3 13.373 3 13v-1.302c.271.202.58.378.904.525C4.978 12.71 6.427 13 8 13s3.022-.289 4.096-.777c.324-.147.633-.323.904-.525Z"/>
                    </svg>
                    </i>
                <div>Data</div>
            </a>
            <ul class="menu-sub">
                @can('import-history-list')
                <li class="menu-item {{Request::segment(2) === 'import' ? 'active' : null}}">
                    <a href="{{route('admin.import.index')}}" class="menu-link">
                        <div>Import</div>
                    </a>
                </li>
                @endcan
                @can('export-history-list')
                <li class="menu-item {{Request::segment(2) === 'export' ? 'active' : null}}">
                    <a href="{{route('admin.export.index')}}" class="menu-link">
                        <div>Export</div>
                    </a>
                </li>
                @endcan
                @can('images-list')
                <li class="menu-item {{Request::segment(2) === 'imagebank' ? 'active' : null}}">
                    <a href="{{route('admin.imagebank.index')}}" class="menu-link">
                        <div>Images</div>
                    </a>
                </li>
                @endcan
                <li class="menu-item {{Request::segment(2) === 'activity-logs' ? 'active' : null}}">
                    <a href="{{route('admin.activity-logs.index')}}" class="menu-link">
                        <div>System Log</div>
                    </a>
                </li>
            </ul>
        </li>
        @endcanany


        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; height: 332px; right: 4px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 246px;"></div>
        </div>
    </ul>
</aside>