<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card custom-card">
        <div class="filter-card">
            <div class="row">
                <div class="col-sm-12 col-lg-8">
                    <div class=" fliter-flex d-flex align-items-center flex-wrap">
                        <div class="filter-label ">
                            <label><h6>Filter</h6></label>
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
                        <div class="filter-icon">
                            <a href="javascript:void(0);" class="fliter-roll">
                                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg ">
                                  <path d="M14.1667 22.3953C14.1667 22.6924 13.9946 22.9713 13.7103 23.1101L11.0436 24.4116C10.4716 24.6908 9.83333 24.2753 9.83333 23.6968V15.3946C9.83333 14.9129 9.63717 14.4534 9.29207 14.1165L1.13059
                                      6.14978C0.725634 5.75448 0.5 5.22046 0.5 4.66593V1.30152C0.5 0.869235 0.860689 0.5 1.33333 0.5H22.6667C23.1393 0.5 23.5 0.869235 23.5 1.30152V4.66593C23.5 5.22046 23.2744 5.75448 22.8694 6.14978L14.7079 14.1165C14.3628
                                      14.4534 14.1667 14.9129 14.1667 15.3946V22.3953Z " fill="#E5F7FC" stroke="#6FC5E0"></path>
                                </svg>
                          </a>
                            <div class="filter-drop-box">
                                <h6>Filter by</h6>
                                <form>
                                    <div class="accordion mt-3 accordion-bordered" id="accordionStyle1">
                                        <div class="card accordion-item">
                                            <h2 class="accordion-header">
                                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-4" aria-expanded="false">
                                                  Record period
                                          </button>
                                            </h2>
                                            <div id="accordionStyle1-4" class="accordion-collapse collapse" data-bs-parent="#accordionStyle1">
                                                <div class="accordion-body">
                                                    <div class="fliter-checkbox">
                                                        <div class="form-check  custom-option-basic">
                                                            <label class="form-check-label custom-option-content" for="customRadioTemp1">
                                                        <input name="customRadioTemp" class="form-check-input" type="radio" value="Today" id="customRadioTemp1" wire:model.defer="record_period">
                                                        <span class="custom-option-header">
                                                          <span class="filter-radio-text">Today</span>
                                                        </span> 
                                                      </label>
                                                        </div>
                                                    </div>
                                                    <div class="fliter-checkbox">
                                                        <div class="form-check  custom-option-basic">
                                                            <label class="form-check-label custom-option-content" for="customRadioTemp1">
                                                        <input name="customRadioTemp" class="form-check-input" type="radio" value="This week" id="customRadioTemp1" wire:model.defer="record_period">
                                                        <span class="custom-option-header">
                                                          <span class="filter-radio-text">This Week</span>
                                                        </span> 
                                                      </label>
                                                        </div>
                                                    </div>
                                                    <div class="fliter-checkbox">
                                                        <div class="form-check  custom-option-basic">
                                                            <label class="form-check-label custom-option-content" for="customRadioTemp1">
                                                        <input name="customRadioTemp" class="form-check-input" type="radio" value="This month" id="customRadioTemp1" wire:model.defer="record_period">
                                                        <span class="custom-option-header">
                                                          <span class="filter-radio-text">This Month</span>
                                                        </span> 
                                                      </label>
                                                        </div>
                                                    </div>
                                                    <div class="fliter-checkbox">
                                                        <div class="form-check  custom-option-basic">
                                                            <label class="form-check-label custom-option-content" for="customRadioTemp1">
                                                        <input name="customRadioTemp" class="form-check-input" type="radio" value="Custom range" id="customRadioTemp1" wire:model.defer="record_period">
                                                        <span class="custom-option-header">
                                                          <div class="input-calender">
                                                              <input class="form-control" wire:model.defer="from" type="date" placeholder="from " id="html5-date-input">
                                                          </div>
                                                          <div class="input-calender">
                                                              <input class="form-control" wire:model.defer="to" type="date" placeholder="from" id="html5-date-input">
                                                          </div>
                                                        </span> 
                                                      </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                            <div class="reset-fiter d-flex">
                                                <button type="button" wire:click="$set('daterange1', 'true')"
                                                    class="btn btn-label-info">Filter</button>
                                                <button type="reset" style="background-color: #babfc7;" class="btn">Reset</button>
                                            </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="search-flex d-flex justify-content-end content_start">
                        <div class="search-box">
                            <input class="form-control" type="text" wire:model.defer="search" value="{{$search}}" placeholder="Search">
                            <span><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M7.6 6.9L9.85 9.15C10.05 9.35 10.05 9.65 9.85 9.85C9.75 9.95 9.6 10 9.5 10C9.4 10 9.25 9.95 9.15 9.85L6.9 7.6C6.15 8.15 5.25 8.5 4.25 8.5C1.9 8.5 0 6.6 0 4.25C0 1.9 1.9 0 4.25 0C6.6 0 8.5 1.9 8.5 4.25C8.5 5.25 8.15 6.2 7.6 6.9ZM4.25 1C2.45 1 1 2.45 1 4.25C1 6.05 2.45 7.5 4.25 7.5C5.15 7.5 5.95 7.15 6.55 6.55C7.15 5.95 7.5 5.15 7.5 4.25C7.5 2.45 6.05 1 4.25 1Z" fill="#6E6B7B"></path>
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
        <form method="post" action="{{route('admin.hallbooking.multiplebookingdelete')}}">
        {{ csrf_field() }}
        <input type="hidden" name="select_type" id='myhidden' value=''>
        <div class="filter-card action-card">
            <div class="row">
                <div class="col-sm-12 col-lg-5">
                    {{--<div class=" fliter-flex d-flex align-items-center">
                        <div class="filter-label ">
                            <label><h6>Action</h6></label>
                        </div>
                        <div class="flex-filter-box">
                            <div class="filter-dropdown fx">
                                <div class="select-custom" wire:ignore >
                                    <select id="selectpickerBasic" class="selectpicker w-100" data-style="btn-default" tabindex="null" onchange="return selectDataAction(event)">
                                        <option value="Completed">Completed</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Accepted">Accepted</option>
                                        <option value="Paid">Paid</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Updated">Updated</option>
                                        <option value="Rejected">Rejected</option>
                                        <option value="Group">Group</option>
                                        <option value="delete">Delete</option>
                                      </select>
                                </div>
                            </div>
                            <div class="filter-button fx">
                                <button type="submit" id="submit" value="Delete All Users" class="btn btn-label-info m-0">Confirm</button>
                            </div>
                        </div>
                    </div>--}}
                </div>
                <div class="col-sm-12 col-lg-7">
                    <div class="action-buttons d-flex justify-content-end content_start">
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Completed')" class="flex-button green @if(isset($statusfind) && $statusfind == 'Completed') active @endif">
                              <sapn>Completed </sapn>
                              <span>@if(isset($Completed) && $Completed > 0) {{$Completed}} @else 0 @endif</span>
                          </button>
                        </div> 
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Pending')" class="flex-button brown @if(isset($statusfind) && $statusfind == 'Pending') active @endif">
                              <sapn>Pending </sapn>
                              <span>@if(isset($Pending) && $Pending > 0) {{$Pending}} @else 0 @endif</span>
                          </button>
                        </div> 
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Accepted')" class="flex-button blue @if(isset($statusfind) && $statusfind == 'Accepted') active @endif">
                              <sapn>Accepted </sapn>
                              <span>@if(isset($Accepted) && $Accepted > 0) {{$Accepted}} @else 0 @endif</span>
                          </button>
                        </div> 
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Paid')" class="flex-button orange @if(isset($statusfind) && $statusfind == 'Paid') active @endif">
                              <sapn>Paid </sapn>
                              <span>@if(isset($Paid) && $Paid > 0) {{$Paid}} @else 0 @endif</span>
                          </button>
                        </div> 
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Cancelled')" class="flex-button gray @if(isset($statusfind) && $statusfind == 'Cancelled') active @endif">
                              <sapn>Cancelled </sapn>
                              <span>@if(isset($Cancelled) && $Cancelled > 0) {{$Cancelled}} @else 0 @endif</span>
                          </button>
                        </div> 
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Updated')" class="flex-button red @if(isset($statusfind) && $statusfind == 'Updated') active @endif">
                              <sapn>Updated </sapn>
                              <span>@if(isset($Updated) && $Updated > 0) {{$Updated}} @else 0 @endif</span>
                          </button>
                        </div>
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Rejected')" class="flex-button violet @if(isset($statusfind) && $statusfind == 'Rejected') active @endif">
                              <sapn>Rejected</sapn>
                              <span>@if(isset($Rejected) && $Rejected > 0) {{$Rejected}} @else 0 @endif</span>
                          </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-left: 100px; padding-bottom:10px;">
            @if($message = Session::get('groupError'))
            <div class="error">{{$message}}</div>
            @endif
        </div>
        <div class="card-datatable dataTable_select text-nowrap table-responsive custom-table">
            <div id="DataTables_Table_3_wrapper " class="dataTables_wrapper dt-bootstrap5 no-footer ">
                <table class="dt-select-table table dataTable no-footer dt-checkboxes-select " id="DataTables_Table_3 " aria-describedby="DataTables_Table_3_info ">
                    <thead>
                        <tr>
                            <th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all " rowspan="1 " colspan="1 " data-col="0 " aria-label=" " ><input type="checkbox" class="form-check-input " id="checkAll"></th>
                            <th onclick="return shorting('created_at','{{$order_by}}');" class="sorting @if(isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending " aria-label="Name: activate to sort column ascending ">CREATE DATE</th>
                            <th onclick="return shorting('id','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'id' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'id' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Position: activate to sort column ascending ">Booking #</th>
                            <th onclick="return shorting('quota_id','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'quota_id' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'quota_id' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Position: activate to sort column ascending ">Quota #</th>
                            <th onclick="return shorting('programme_code','{{$order_by}}');" class="sorting   @if(isset($order_type) && $order_type == 'programme_code' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'programme_code' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " s="" aria-label="Email: activate to sort column ascending ">Prog.Code</th>
                            <th onclick="return shorting('collage_name','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'collage_name' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'collage_name' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="City: activate to sort column ascending ">Hall/Collage</th>
                            <th onclick="return shorting('start_date','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'start_date' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'start_date' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Date: activate to sort column ascending ">In Date</th>
                            <th onclick="return shorting('end_date','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'end_date' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'end_date' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Salary: activate to sort column ascending ">Out Date</th>
                            <th onclick="return shorting('start_date','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'start_date' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'start_date' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Nights</th>
                            <th onclick="return shorting('application_id','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'application_id' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'application_id' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Application #</th>
                            <th onclick="return shorting('amount','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'amount' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'amount' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Amount</th>
                            <th onclick="return shorting('amount','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'amount' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'amount' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Booking QTY</th>
                            <th onclick="return shorting('type','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'type' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'type' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Type</th>
                            <th onclick="return shorting('status','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'status' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'status' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($hallbooking))
                        @php  $i=1; @endphp        
                            @foreach ($hallbooking as $key => $hallbookinData)
                                <tr>
                                    <td valign="top" colspan="1" class="dt-checkboxes-cell dt-checkboxes-select-all "><input type="checkbox" name='id[]' class="form-check-input" value="{{$hallbookinData->id}}"></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($hallbookinData->created_at) && !empty($hallbookinData->created_at)) {{date('Y-m-d  H:i:s' , strtotime($hallbookinData->created_at))}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($hallbookinData->booking_number) && !empty($hallbookinData->booking_number)) # {{$hallbookinData->booking_number}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($hallbookinData->quota_id) && !empty($hallbookinData->quota_id)) # {{$hallbookinData->quota_id}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($hallbookinData->programme_code) && !empty($hallbookinData->programme_code)) {{$hallbookinData->programme_code}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($hallbookinData->getQuotaHallDetail->college_name) && !empty($hallbookinData->getQuotaHallDetail->college_name)) {{$hallbookinData->getQuotaHallDetail->college_name}} @else N/A @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);" >@if(isset($hallbookinData->check_in_date) && !empty($hallbookinData->check_in_date)) {{date('Y-m-d',$hallbookinData->check_in_date)}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);"> @if(isset($hallbookinData->check_out_date) && !empty($hallbookinData->check_out_date)) {{date('Y-m-d',$hallbookinData->check_out_date)}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($hallbookinData->nights) && !empty($hallbookinData->nights)) {{$hallbookinData->nights}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($hallbookinData->application_id) && !empty($hallbookinData->application_id)) {{$hallbookinData->application_id}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">
                                   @if(isset($hallbookinData->amount) && !empty($hallbookinData->amount)) {{$hallbookinData->amount}} @endif
                                    </a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);"> @if(isset($hallbookinData->total_bookings) && !empty($hallbookinData->total_bookings)) {{$hallbookinData->total_bookings}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                        @if(isset($hallbookinData->booking_type) && !empty($hallbookinData->booking_type))
                                            @if($hallbookinData->booking_type == 'g')
                                                <div class="dropdown text-center"style="margin-right: 7px">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span style="margin-right: 5px">{{ucfirst($hallbookinData->booking_type)}} </span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/></svg>
                                                    </button>
                                                    {{--<div class="dropdown-menu table-dropdown">
                                                        <a class="dropdown-item" href="javascript:void(0);">Ungroup</a>
                                                    </div>--}}
                                                </div> 
                                            @else 
                                            {{ucfirst($hallbookinData->booking_type)}}
                                            @endif 
                                        @endif
                                    </td>
                                    <td valign="top" style="pointer-events: none;" colspan="1" class="dataTables_empty">
                                        <div class="select_status_filter">
                                            <select readonly id="" class="selectstatus w-100  @if($hallbookinData->status=='Completed') green @elseif($hallbookinData->status=='Pending') brown  @elseif($hallbookinData->status=='Accepted') blue  @elseif($hallbookinData->status=='Paid') orange  @elseif($hallbookinData->status=='Cancelled') gray  @elseif($hallbookinData->status=='Updated') red  @elseif($hallbookinData->status=='Rejected') violet @endif" data-style="btn-default" tabindex="null" name="status" onchange="return changeStatus(this.value,'{{$hallbookinData->id}}')">  
                                                @if($hallbookinData->status == "Completed")
                                                    <option value="Completed">Completed</option>
                                                    @else
                                                    <option value="Completed">Completed</option>
                                                    @endif
                                                    @if($hallbookinData->status == "Pending")     
                                                    <option value="Pending"selected>Pending</option>
                                                    @else
                                                    <option value="Pending">Pending</option>
                                                    @endif
                                                    @if($hallbookinData->status == "Accepted")     
                                                    <option value="Accepted"selected>Accepted</option>
                                                    @else
                                                    <option value="Accepted">Accepted</option>
                                                    @endif
                                                    @if($hallbookinData->status == "Paid")     
                                                    <option value="Paid"selected>Paid</option>
                                                    @else
                                                    <option value="Paid">Paid</option>
                                                    @endif
                                                    @if($hallbookinData->status == "Cancelled")     
                                                    <option value="Cancelled"selected>Cancelled</option>
                                                    @else
                                                    <option value="Cancelled">Cancelled</option>
                                                    @endif
                                                     @if($hallbookinData->status == "Updated") 
                                                    <option value="Updated" selected>Updated</option>
                                                    @else
                                                    <option value="Updated" >Updated</option>
                                                    @endif
                                                     @if($hallbookinData->status == "Rejected") 
                                                    <option value="Rejected"selected>Rejected</option>
                                                    @else
                                                    <option value="Rejected">Rejected</option>
                                                    @endif
                                                </select>
                                        </div>                                      
                                    </td>
                                </tr>
                                @php $i++; @endphp
                            @endforeach
                        @else
                        <td colspan="4"></td>
                        <td valign="top" colspan="1" class="dataTables_empty">{{$notfoundlabel}}</td>
                        <td  colspan="10"></td>
                        @endif
                    </tbody>
                </table>
                <div class="bottom-show-padding">
                    <div class="row custom-show-drop-down ">
                        <div class="col-sm-12 col-md-6">
                            <div class="bottom-dropdown">
                            @if(isset($paginate) && !empty($paginate) && $countMember > $paginate)
                                <div class="show-bottom">
                                    <div class="show-text">
                                        <p>Show</p>
                                    </div>
                                    <div class="filter-dropdown">
                                        <div class="btn-group dropup">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            @if(isset($paginate) && !empty($paginate)) {{$paginate}} @endif
                                            </button>
                                            <ul class="dropdown-menu custom-dropdown">
                                                <li><a class="dropdown-item" 
                                                wire:click="$set('paginate', '20')" 
                                                href="javascript:void(0);">20</a></li>
                                                <li><a class="dropdown-item" 
                                                wire:click="$set('paginate', '50')" href="javascript:void(0);">50</a></li>
                                                <li><a class="dropdown-item"
                                                wire:click="$set('paginate', '100')" href="javascript:void(0);">100</a></li>
                                                <li><a class="dropdown-item"
                                                wire:click="$set('paginate', '500')" href="javascript:void(0);">500</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="dataTables_info " id="DataTables_Table_3_info " role="status " aria-live="polite ">
                                    <p>Showing <span>@if(isset($page) && !empty($page)) {{$page}} @endif</span> to <span>@if(isset($paginate) && !empty($paginate)) {{$paginate}} @endif</span> of <span>@if(isset($countMember) && !empty($countMember)) {{$countMember}} @endif</span> entries</p>
                                </div>
                                @endif
                            </div>
                        </div> 
                        <div class="col-sm-12 col-md-6">
                            <nav aria-label="Page navigation ">
                                {{ $hallbooking->links()}}
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
