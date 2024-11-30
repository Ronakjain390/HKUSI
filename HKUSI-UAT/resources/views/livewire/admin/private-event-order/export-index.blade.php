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
        <form method="post" action="">
        {{ csrf_field() }}
        <input type="hidden" name="select_type" id='myhidden' value=''>
        <div class="filter-card action-card">
            <div class="row">
                <div class="col-sm-12 col-lg-5">
                  
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

                            <th onclick="return shorting('quota_id','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'quota_id' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'quota_id' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Position: activate to sort column ascending ">Application  #</th>

                            <th onclick="return shorting('programme_code','{{$order_by}}');" class="sorting   @if(isset($order_type) && $order_type == 'programme_code' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'programme_code' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " s="" aria-label="Email: activate to sort column ascending ">Event #</th>

                            <th onclick="return shorting('collage_name','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'collage_name' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'collage_name' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="City: activate to sort column ascending ">Event Name</th>

                            <th onclick="return shorting('start_date','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'start_date' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'start_date' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Date: activate to sort column ascending ">Event Date</th>

                            <th onclick="return shorting('start_time','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'start_time' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'start_time' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Salary: activate to sort column ascending ">Start Time</th>

                             <th onclick="return shorting('end_time','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'start_time' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'start_time' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Salary: activate to sort column ascending ">End Time</th>

                            <th onclick="return shorting('location','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'location' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'location' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Location</th>

                            <th onclick="return shorting('application_id','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'application_id' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'application_id' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Assembly Location</th>

                            <th onclick="return shorting('assembly_start_time','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'assembly_start_time' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'assembly_start_time' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Assembly Start Time</th>

                            <th onclick="return shorting('assembly_end_time','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'assembly_end_time' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'assembly_end_time' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Assembly End Time</th>


                            <th onclick="return shorting('status','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'status' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'status' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($hallbooking))
                        @php  $i=1; @endphp        
                            @foreach ($hallbooking as $key => $eventboookingData)
                                <tr>
                                    <td valign="top" colspan="1" class="dt-checkboxes-cell dt-checkboxes-select-all "><input type="checkbox" name='id[]' class="form-check-input" value="{{$eventboookingData->id}}"></td>

                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($eventboookingData->created_at) && !empty($eventboookingData->created_at)) {{date('Y-m-d  H:i:s' , strtotime($eventboookingData->created_at))}} @endif</a></td> 

                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($eventboookingData->booking_id) && !empty($eventboookingData->booking_id)) # {{$eventboookingData->booking_id}} @endif</a></td>

                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($eventboookingData->application_number) && !empty($eventboookingData->application_number)) # {{$eventboookingData->application_number}} @endif</a></td>

                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($eventboookingData->event_id) && !empty($eventboookingData->event_id)) {{$eventboookingData->event_id}} @endif</a></td>

                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($eventboookingData->event_name) && !empty($eventboookingData->event_name)) {{$eventboookingData->event_name}} @else N/A @endif</a></td>

                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);" >@if(isset($eventboookingData->event_date) && !empty($eventboookingData->event_date)) {{date('Y-m-d',$eventboookingData->event_date)}} @endif</a></td>

                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);"> @if(isset($eventboookingData->start_time) && !empty($eventboookingData->start_time)) {{date('H:i',$eventboookingData->start_time)}} @endif</a></td>

                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);"> @if(isset($eventboookingData->end_time) && !empty($eventboookingData->end_time)) {{date('H:i',$eventboookingData->end_time)}} @endif</a></td>

                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($eventboookingData->location) && !empty($eventboookingData->location)) {{$eventboookingData->location}} @endif</a></td>

                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($eventboookingData->assembly_location) && !empty($eventboookingData->assembly_location)) {{$eventboookingData->assembly_location}} @endif</a></td>

                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);"> @if(isset($eventboookingData->assembly_start_time) && !empty($eventboookingData->assembly_start_time)) {{date('H:i',$eventboookingData->assembly_start_time)}} @endif</a></td> 

                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);"> @if(isset($eventboookingData->assembly_end_time) && !empty($eventboookingData->assembly_end_time)) {{date('H:i',$eventboookingData->assembly_end_time)}} @endif</a></td>

                                   
                                    <td valign="top" style="pointer-events: none;" colspan="1" class="dataTables_empty">
                                        <div class="select_status_filter">
                                            <span class="badge rounded-pill badge-custom  @if($eventboookingData->event_status=='Completed') green @elseif($eventboookingData->event_status=='Pending') brown  @elseif($eventboookingData->event_status=='Paid') orange  @elseif($eventboookingData->event_status=='Cancelled') gray  @elseif($eventboookingData->event_status=='Updated') red  @endif" style="width: 100%;"> @if(isset($eventboookingData->event_status) && !empty($eventboookingData->event_status)){{$eventboookingData->event_status}}  @endif</span>
                                            
                                               
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
