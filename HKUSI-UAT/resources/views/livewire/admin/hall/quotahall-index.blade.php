<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card custom-card">
        <div class="filter-card">
            <div class="row">
                <div class="col-sm-12 col-lg-7">
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
                            <a href="javascript:void(0);" class="fliter-roll"><svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg ">
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
                <div class="col-sm-12 col-lg-5">
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
        <form method="post" action="{{route('admin.quota.multiplequotadelete')}}">
		{{ csrf_field() }}
        <input type="hidden" name="select_type" id='myhidden' value=''>
        <div class="filter-card action-card">
            <div class="row">

                <div class="col-sm-12 col-lg-4">
                    <div class=" fliter-flex d-flex align-items-center">
                         <div class="filter-label ">
                            <label><h6>Action</h6></label>
                        </div>
                        <div class="flex-filter-box" wire:ignore>
                            <div class="filter-dropdown fx">
                                <div class="select-custom">
                                    <select id="selectpickerBasic" class="selectpicker w-100" data-style="btn-default" tabindex="null" onchange="return selectDataAction(event)">
                                        <option value="enable">Enable</option>
                                        <option value="disable">Disable</option>
                                        <option value="delete">Delete</option>
                                      </select>
                                </div>
                            </div>
                            <div class="filter-button fx">
                                <button type="submit" id="submit" value="Delete All Users" class="btn btn-label-info m-0">Confirm</button>
                            </div>
                            <div class="filter-button fx plus-button">
                                <button type="button" class="btn btn-label-info ">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 6C12 6.45 11.7 6.75 11.25 6.75H6.75V11.25C6.75 11.7 6.45 12 6 12C5.55 12 5.25 11.7 5.25 11.25V6.75H0.75C0.3 6.75 0 6.45 0 6C0 5.55 0.3 5.25 0.75 5.25H5.25V0.75C5.25 0.3 5.55 0 6 0C6.45 0 6.75 0.3 6.75 0.75V5.25H11.25C11.7 5.25 12 5.55 12 6Z" fill="black"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-8">
                    <div class="action-buttons d-flex justify-content-end content_start">
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('is_import', '1')" class="flex-button green active">
                              <sapn>Enabled </sapn>
                              <span>@if(isset($totalCompleted) && $totalCompleted > 0) {{$totalCompleted}} @else 0 @endif</span>
                          </button>
                        </div>
                        <div class="data-buttons">
                            <button type="button" wire:click="$set('is_import', '0')" class="flex-button gray">
                              <sapn>Pending</sapn>
                              <span>@if(isset($totalFailed) && $totalFailed > 0) {{$totalFailed}} @else 0 @endif</span>
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
                            <th onclick="return shorting('start_date','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'start_date' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'start_date' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Position: activate to sort column ascending ">Start Date.</th>
                            <th onclick="return shorting('end_date','{{$order_by}}');" class="sorting   @if(isset($order_type) && $order_type == 'end_date' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'end_date' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " s="" aria-label="Email: activate to sort column ascending ">End Date</th>
                            <th onclick="return shorting('male','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'male' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'male' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Salary: activate to sort column ascending ">Male</th>
                            <th onclick="return shorting('female','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Female</th>
                            <th onclick="return shorting('female','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Hall/Collage</th>
                            <th onclick="return shorting('female','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Address</th>
                            <th onclick="return shorting('female','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Room Type</th>
                            <th onclick="return shorting('female','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">In Date</th>
                            <th onclick="return shorting('female','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">In Time</th>
                            <th onclick="return shorting('female','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Out Date</th>
                            <th onclick="return shorting('female','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Out Time</th>
                            <th onclick="return shorting('female','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">PDF</th>
                            <th onclick="return shorting('female','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Prog.Code</th>
                            <th onclick="return shorting('female','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'female' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Prog.Name</th>
                            <th class=" " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($quotas))
                        @php  $i=1; @endphp        
                            @foreach ($quotas as $key => $quotasdata)
                                <tr>
                                    <td valign="top" colspan="1" class="dt-checkboxes-cell dt-checkboxes-select-all "><input type="checkbox" name='id[]' class="form-check-input" value="{{$quotasdata->id}}"></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->start_date) && !empty($quotasdata->start_date)) {{date('d/m/Y H:i:s',$quotasdata->start_date)}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->end_date) && !empty($quotasdata->end_date)) {{date('d/m/Y H:i:s',$quotasdata->end_date)}} @endif</a></td>                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->male) && !empty($quotasdata->male)) {{$quotasdata->male}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->female) && !empty($quotasdata->female)) {{$quotasdata->female}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->college_name) && !empty($quotasdata->college_name)) {{$quotasdata->college_name}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->address) && !empty($quotasdata->address)) {{$quotasdata->address}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->room_type) && !empty($quotasdata->room_type)) {{$quotasdata->room_type}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->check_in_date) && !empty($quotasdata->check_in_date)) {{$quotasdata->check_in_date}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->check_in_time) && !empty($quotasdata->check_in_time)) {{$quotasdata->check_in_time}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->check_out_date) && !empty($quotasdata->check_out_date)) {{$quotasdata->check_out_date}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->check_out_time) && !empty($quotasdata->check_out_time)) {{$quotasdata->check_out_time}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->pdf) && !empty($quotasdata->pdf)) {{$quotasdata->pdf}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->quota_balance) && !empty($quotasdata->quota_balance)) {{$quotasdata->quota_balance}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($quotasdata->male) && !empty($quotasdata->male)) {{$quotasdata->male}} @endif</a></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                        <div class="dropdown text-center">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots"></i>
                                      </button>
                                            <div class="dropdown-menu table-dropdown">
                                                <a class="dropdown-item" href="{{route('admin.hallDetails',[$quotasdata->id,'edit'])}}">Edit</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Update</a>
                                                <a wire:click="$emit('triggerDelete',{{$quotasdata->id }})" class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                 <a wire:click="javascript:void(0);" class="dropdown-item" href="javascript:void(0);">Add Hall</a>
                                            </div>
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
                                <div class="show-bottom">
                                    <div class="show-text">
                                        <p>Show</p>
                                    </div>
                                    <div class="filter-dropdown">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            @if(isset($paginate) && !empty($paginate)) {{$paginate}} @endif
                                            </button>
                                            <ul class="dropdown-menu custom-dropdown">
                                                <li><a class="dropdown-item" 
                                                wire:click="$set('paginate', '14')" 
                                                href="javascript:void(0);">14</a></li>
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
                                <div class="dataTables_info" id="DataTables_Table_3_info " role="status " aria-live="polite ">
                                    <p>Showing <span>@if(isset($page) && !empty($page)) {{$page}} @endif</span> to <span>@if(isset($paginate) && !empty($paginate)) {{$paginate}} @endif</span> of <span>@if(isset($countMember) && !empty($countMember)) {{$countMember}} @endif</span> entries</p>
                                </div>
                                @endif
                            </div>
                        </div> 
                        <div class="col-sm-12 col-md-6">
                            <nav aria-label="Page navigation ">
                              {{ $quotas->links()}}
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
          @this.on('triggerHallStautsActive', id => {
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
                      @this.call('statusChangeHallQuota',id)
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

      document.addEventListener('DOMContentLoaded', function (e) {
          @this.on('triggerAllDelete', id =>{
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure delete all records ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#6fc5e0",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete",
            }).then((result) => {
                if (result.value) {
                    @this.call('destroyAll')
                } else {
                    console.log("Canceled");
                }
            });
        });
      });

      function selectDataAction(value){
        if (value != '') {
            // if(value == 'Delete all'){
            //     Swal.fire({
            //         title: "Are you sure?",
            //         text: "Are you sure delete all records ?",
            //         icon: "warning",
            //         showCancelButton: true,
            //         confirmButtonColor: "#6fc5e0",
            //         cancelButtonColor: "#d33",
            //         confirmButtonText: "Delete",
            //     }).then((result) => {
            //         if (result.value) {
            //             @this.call('destroyAll')
            //         } else {
            //             console.log("Canceled");
            //         }
            //     });
            // }
        }
      }
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

  </script>

<script>
    function selectDataAction(event) {
        var selectElement = event.target;
        var value = selectElement.value;
        $('#myhidden').val(value);
    }
</script>

<script language="javascript">
    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
  
@endpush