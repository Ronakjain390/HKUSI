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
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header">
                                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-1" aria-expanded="false">
                                              Nationality
                                          </button>
                                            </h2>

                                            <div id="accordionStyle1-1" class="accordion-collapse collapse" data-bs-parent="#accordionStyle1">
                                                <div class="accordion-body">
                                                    <div class="fliter-checkbox">
                                                        <div class="filter-dropdown fx">
                                                            <div class="select-custom">
                                                                <select id="selectpickerBasic" class="selectpicker w-100" data-style="btn-default" wire:model.defer="nationality" tabindex="null">
                                                                    <option>Select Nationality</option>
                                                                    @if(isset($countries) && !empty($countries))
                                                                        @foreach($countries as $key => $nationalityData)
                                                                            <option>{{$nationalityData->name}}</option>
                                                                        @endforeach
                                                                    @endif
                                                                  </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item card">
                                            <h2 class="accordion-header">
                                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-2" aria-expanded="false">
                                                  Gender
                                          </button>
                                            </h2>
                                            <div id="accordionStyle1-2" class="accordion-collapse collapse" data-bs-parent="#accordionStyle1">
                                                <div class="accordion-body">
                                                    <div class="fliter-checkbox">
                                                        <div class="form-check  custom-option-basic ">
                                                            <label class="form-check-label custom-option-content" for="customRadioTemp1">
                                                                <input name="gender" class="form-check-input" type="radio" value="Male" id="customRadioTemp1" checked="" wire:model.defer="gender">
                                                                <span class="custom-option-header">
                                                                  <span class="filter-radio-text">Male</span>
                                                                </span> 
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="fliter-checkbox">
                                                        <div class="form-check  custom-option-basic">
                                                            <label class="form-check-label custom-option-content" for="customRadioTemp1">
                                                                <input name="gender" class="form-check-input" type="radio" value="Female" id="customRadioTemp1" wire:model.defer="gender">
                                                                <span class="custom-option-header">
                                                                  <span class="filter-radio-text">Female</span>
                                                                </span> 
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card accordion-item">
                                            <h2 class="accordion-header">
                                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-3" aria-expanded="false">
                                                    Study Country
                                                </button>
                                            </h2>
                                            <div id="accordionStyle1-3" class="accordion-collapse collapse" data-bs-parent="#accordionStyle1">
                                                <div class="accordion-body">
                                                    <div class="fliter-checkbox">
                                                        <div class="filter-dropdown fx">
                                                            <div class="select-custom">
                                                                <select id="selectpickerBasic" class="selectpicker w-100" data-style="btn-default" wire:model.defer="study_country" tabindex="null">
                                                                    <option>Select Country</option>
                                                                    @if(isset($countries) && !empty($countries))
                                                                        @foreach($countries as $key => $nationalityData)
                                                                            <option>{{$nationalityData->name}}</option>
                                                                        @endforeach
                                                                    @endif
                                                                  </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                                              <input class="form-control" wire:model.defer="start_date" type="date" placeholder="from " id="html5-date-input">
                                                          </div>
                                                          <div class="input-calender">
                                                              <input class="form-control" wire:model.defer="end_date" type="date" placeholder="from" id="html5-date-input">
                                                          </div>
                                                        </span> 
                                                      </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="reset-fiter">
                                        <button type="reset" class="btn">Reset</button>
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
                <div class="col-sm-12 col-lg-4">
                   <!--  <div class=" fliter-flex d-flex align-items-center">
                        <div class="filter-label ">
                            <label><h6>Action</h6></label>
                        </div>
                        <div class="flex-filter-box" wire:ignore>
                            <div class="filter-dropdown fx">
                                <div class="select-custom">
                                    <select id="selectpickerBasic" class="selectpicker w-100" data-style="btn-default" tabindex="null" onchange="return selectDataAction(event)">
                                        <option value="Completed">Completed</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Accepted">Accepted</option>
                                        <option value="Paid">Paid</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Updated">Updated</option>
                                        <option value="Rejected">Rejected</option>
                                        <option value="delete">Delete</option>
                                      </select>
                                </div>
                            </div>
                            <div class="filter-button fx">
                                <button type="submit" id="submit" value="Delete All Users" class="btn btn-label-info m-0">Confirm</button>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="col-sm-12 col-lg-8">
                    <div class="action-buttons d-flex justify-content-end content_start">
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Completed')" class="flex-button green active">
                              <sapn>Completed </sapn>
                              <span>@if(isset($Completed) && $Completed > 0) {{$Completed}} @else 0 @endif</span>
                          </button>
                        </div> 
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Pending')" class="flex-button gray">
                              <sapn>Pending </sapn>
                              <span>@if(isset($Pending) && $Pending > 0) {{$Pending}} @else 0 @endif</span>
                          </button>
                        </div> 
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Accepted')" class="flex-button blue">
                              <sapn>Accepted </sapn>
                              <span>@if(isset($Accepted) && $Accepted > 0) {{$Accepted}} @else 0 @endif</span>
                          </button>
                        </div> 
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Paid')" class="flex-button orange">
                              <sapn>Paid </sapn>
                              <span>@if(isset($Paid) && $Paid > 0) {{$Paid}} @else 0 @endif</span>
                          </button>
                        </div> 
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Cancelled')" class="flex-button gray">
                              <sapn>Cancelled </sapn>
                              <span>@if(isset($Cancelled) && $Cancelled > 0) {{$Cancelled}} @else 0 @endif</span>
                          </button>
                        </div> 
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Updated')" class="flex-button red">
                              <sapn>Updated </sapn>
                              <span>@if(isset($Updated) && $Updated > 0) {{$Updated}} @else 0 @endif</span>
                          </button>
                        </div>
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('statusfind', 'Rejected')" class="flex-button violet">
                              <sapn>Rejected</sapn>
                              <span>@if(isset($Rejected) && $Rejected > 0) {{$Rejected}} @else 0 @endif</span>
                          </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-datatable dataTable_select text-nowrap table-responsive custom-table ">
            <div id="DataTables_Table_3_wrapper " class="dataTables_wrapper dt-bootstrap5 no-footer ">
                <table class="dt-select-table table dataTable no-footer dt-checkboxes-select " id="DataTables_Table_3 " aria-describedby="DataTables_Table_3_info ">
                    <thead>
                        <tr>
                            <th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all " rowspan="1 " colspan="1 " data-col="0 " aria-label=" " ><input type="checkbox" class="form-check-input " id="checkAll"></th>
                            <th onclick="return shorting('created_at','{{$order_by}}');" class="sorting @if(isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending " aria-label="Name: activate to sort column ascending ">Created Date</th>
                            <th onclick="return shorting('id','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'id' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'id' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Position: activate to sort column ascending ">Booking #</th>
                            <th onclick="return shorting('programme_code','{{$order_by}}');" class="sorting   @if(isset($order_type) && $order_type == 'programme_code' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'programme_code' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " s="" aria-label="Email: activate to sort column ascending ">Prog.Code</th>
                            <th onclick="return shorting('collage_name','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'collage_name' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'collage_name' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="City: activate to sort column ascending ">Hall/Collage</th>
                            <th onclick="return shorting('start_date','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'start_date' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'start_date' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Date: activate to sort column ascending ">Start Date</th>
                            <th onclick="return shorting('end_date','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'end_date' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'end_date' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Salary: activate to sort column ascending ">End Date</th>
                            <th onclick="return shorting('start_date','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'start_date' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'start_date' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Days</th>

                            <th onclick="return shorting('application_id','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'application_id' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'application_id' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Application #</th>
                            <th onclick="return shorting('amount','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'amount' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'amount' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Amount</th>
                            <th onclick="return shorting('amount','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'amount' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'amount' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Booking QTY</th>
                            <th onclick="return shorting('type','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'type' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'type' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Type</th>
                            <th onclick="return shorting('status','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'status' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'status' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Status</th>
                            <!-- <th rowspan="1" colspan="1">action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($hallbooking))
                        @php  $i=1; @endphp        
                            @foreach ($hallbooking as $key => $hallbookinData)
                                @php
                                $remaininsgDays = (int)(($hallbookinData->end_date - $hallbookinData->start_date)/86400);
                                @endphp
                                <tr>
                                    <td valign="top" colspan="1" class="dt-checkboxes-cell dt-checkboxes-select-all "><input type="checkbox" name='id[]' class="form-check-input" value="{{$hallbookinData->id}}"></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="{{route('admin.hallbookingDetails',[$hallbookinData->id,'show'])}}">@if(isset($hallbookinData->created_at) && !empty($hallbookinData->created_at)) {{date('Y-m-d H:i:s' , strtotime($hallbookinData->created_at))}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="{{route('admin.hallbookingDetails',[$hallbookinData->id,'show'])}}">@if(isset($hallbookinData->booking_number) && !empty($hallbookinData->booking_number)) {{$hallbookinData->booking_number}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="{{route('admin.hallbookingDetails',[$hallbookinData->id,'show'])}}">@if(isset($hallbookinData->programme_code) && !empty($hallbookinData->programme_code)) {{$hallbookinData->programme_code}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="{{route('admin.hallbookingDetails',[$hallbookinData->id,'show'])}}">@if(isset($hallbookinData->getProgrammeDetail->getQuotaProgrammeDetail) && !empty($hallbookinData->getProgrammeDetail->getQuotaProgrammeDetail->getQuotaDetail->getQuotaHallDetail[0]->college_name)) {{$hallbookinData->getProgrammeDetail->getQuotaProgrammeDetail->getQuotaDetail->getQuotaHallDetail[0]->college_name}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="{{route('admin.hallbookingDetails',[$hallbookinData->id,'show'])}}" >@if(isset($hallbookinData->start_date) && !empty($hallbookinData->start_date)) {{date('Y-m-d',$hallbookinData->start_date)}} @endif</a></td>
                                    @php

                                     @endphp
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="{{route('admin.hallbookingDetails',[$hallbookinData->id,'show'])}}">@if(isset($hallbookinData->end_date) && !empty($hallbookinData->end_date)) {{date('Y-m-d',$hallbookinData->end_date)}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="{{route('admin.hallbookingDetails',[$hallbookinData->id,'show'])}}">{{$remaininsgDays}}</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="{{route('admin.hallbookingDetails',[$hallbookinData->id,'show'])}}">@if(isset($hallbookinData->application_id) && !empty($hallbookinData->application_id)) {{$hallbookinData->application_id}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="{{route('admin.hallbookingDetails',[$hallbookinData->id,'show'])}}">@if(isset($hallbookinData->amount) && !empty($hallbookinData->amount)) {{$hallbookinData->amount}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="{{route('admin.hallbookingDetails',[$hallbookinData->id,'show'])}}">1</a></td>
                                    <td>Single</td>
                                      <td valign="top" colspan="1" class="dataTables_empty" >
                                        <div class="select_status_filter">                                            
                                            <span class="badge rounded-pill badge-custom  @if($hallbookinData->status=='Completed') green @elseif($hallbookinData->status=='Pending') gray  @elseif($hallbookinData->status=='Accepted') blue  @elseif($hallbookinData->status=='Paid') orange  @elseif($hallbookinData->status=='Cancelled') gray  @elseif($hallbookinData->status=='Updated') red  @elseif($hallbookinData->status=='Rejected') violet @endif">@if(isset($hallbookinData->status) && !empty($hallbookinData->status)) {{$hallbookinData->status}} @endif </span>
                                        </div>                                      
                                    </td>
                                    <!--  <td valign="top" colspan="1" class="dataTables_empty">
                                        <div class="dropdown text-center">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots"></i>
                                      </button>
                                            <div class="dropdown-menu table-dropdown">
                                                <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                <a wire:click="$emit('triggerDelete',{{$hallbookinData->id }})" class="dropdown-item" href="javascript:void(0);">Delete</a>
                                               
                                            </div>
                                        </div>
                                    </td> -->
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
                                @if(isset($paginate) && !empty($paginate) && $countMember > $paginate)
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
@push('foorterscript')
  <script type="text/javascript">
      document.addEventListener('DOMContentLoaded', function (e) {
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
                      @this.call('userstatusChange',id)
                  } else {
                      console.log("Canceled");
                  }
              });
          });
      });

      
      document.addEventListener('DOMContentLoaded', function (e) {
          @this.on('triggerActive', id => {
              Swal.fire({
                  title: "Are you sure?",
                  text: "Are you sure to change to activation?",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#6fc5e0",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Yes, Change it.",
              }).then((result) => {
                  if (result.value) {
                      @this.call('statusChange',id)
                  } else {
                      console.log("Canceled");
                  }
              });
          });
      });

      document.addEventListener('DOMContentLoaded', function (e) {
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
                    @this.call('destroy',id)
                } else {
                    console.log("Canceled");
                }
            });
        });
      });
      function shorting(order_type,order_by){
        if (order_by == 'DESC') {
            var order = 'ASC';
            @this.set('order_type',order_type);
            @this.set('order_by',order);
        }else{
            var order = 'DESC';
            @this.set('order_type',order_type);
            @this.set('order_by',order);
        }
      }

    function selectDataAction(event) {
        var selectElement = event.target;
        var value = selectElement.value;
        $('#myhidden').val(value);
    }
    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    function changeStatus(val,id){
        @this.set('status',val);
        @this.call('statusChange',id,val);
    }
</script>  
@endpush