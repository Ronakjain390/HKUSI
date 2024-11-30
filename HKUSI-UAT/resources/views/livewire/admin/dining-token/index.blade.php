<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card custom-card">
        <div class="filter-card">
            <div class="row">
                <!--<div class="col-sm-12 col-md-7">
                    <div class=" fliter-flex d-flex align-items-center">
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
                          <path d="M14.1667 22.3953C14.1667 22.6924 13.9946 22.9713 13.7103 23.1101L11.0436 24.4116C10.4716 24.6908 9.83333 24.2753 9.83333 		23.6968V15.3946C9.83333 14.9129 9.63717 14.4534 9.29207 14.1165L1.13059
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
                </div>-->
                <div class="col-sm-12 col-md-12">
                    <div class="search-flex d-flex justify-content-end">
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
        <form method="post" action="{{route('admin.event-type.eventypemultiple')}}">
        {{ csrf_field() }}
        <input type="hidden" name="select_type" id='myhidden' value=''>
        <div class="filter-card action-card">
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <div class=" fliter-flex d-flex align-items-center">
                        <!--<div class="filter-label ">
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
                                <button type="submit" class="btn btn-label-info m-0" onclick="updateStatus()">Confirm</button>
                            </div>-->
                            <div class="filter-button fx plus-button">
                                <a href="{{route('admin.dining-token.create')}}">
                                    <button type="button" class="btn btn-label-info ">
                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 6C12 6.45 11.7 6.75 11.25 6.75H6.75V11.25C6.75 11.7 6.45 12 6 12C5.55 12 5.25 11.7 5.25 11.25V6.75H0.75C0.3 6.75 0 6.45 0 6C0 5.55 0.3 5.25 0.75 5.25H5.25V0.75C5.25 0.3 5.55 0 6 0C6.45 0 6.75 0.3 6.75 0.75V5.25H11.25C11.7 5.25 12 5.55 12 6Z" fill="black"></path>
                                        </svg>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
					<div class="col-sm-12 col-md-8">
						<div class="action-buttons d-flex justify-content-end">
							<div class="data-buttons">
								<button type="button" wire:click="$set('status', '1')" class="flex-button green @if(isset($status) && $status == '1') active @endif">
								  <sapn>Enabled </sapn>
								  <span>@if(isset($totalEnabled) && $totalEnabled > 0) {{$totalEnabled}} @else 0 @endif</span>
							  </button>
							</div>
							<div class="data-buttons">
								<button type="button" wire:click="$set('status', '0')" class="flex-button gray  @if(isset($status) && $status == '0') active @endif">
								  <sapn>Disabled</sapn>
								  <span>@if(isset($totalDisabled) && $totalDisabled > 0) {{$totalDisabled}} @else 0 @endif</span>
							  </button>
							</div>
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
                            <th onclick="return shorting('created_at','{{$order_by}}');" class="sorting @if(isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending " aria-label="Name: activate to sort column ascending ">Create DATE</th>
                            <th onclick="return shorting('created_at','{{$order_by}}');" class="sorting @if(isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending " aria-label="Name: activate to sort column ascending ">Quantity</th>
							<th onclick="return shorting('created_at','{{$order_by}}');" class="sorting @if(isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending " aria-label="Name: activate to sort column ascending ">Unit Price</th>
                            <th onclick="return shorting('status','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'status' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'status' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Status</th>
                            <th rowspan="1" colspan="1">action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($diningtoken))
                            @foreach($diningtoken as $key => $dining)
                                <tr>
                                    <td valign="top" colspan="1" class="dt-checkboxes-cell dt-checkboxes-select-all "><input type="checkbox" name='id[]' class="form-check-input" value="{{$dining->id}}"></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($dining->created_at) && !empty($dining->created_at)) {{date('Y-m-d H:i:s' , strtotime($dining->created_at))}} @endif</a></td>
                                    
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($dining->quantity) && !empty($dining->quantity)) {{$dining->quantity}} @endif</a></td>
									 <td valign="top" colspan="1" class="dataTables_empty">
                                    <a href="javascript:void(0);">@if(isset($dining->unit_price) && !empty($dining->unit_price)) {{$dining->unit_price}} @endif</a></td>
									
                                   <td valign="top" colspan="1" class="dataTables_empty" >
                                        <div class="select_status_filter">                                            
                                            <span class="badge rounded-pill badge-custom  @if($dining->status=='1') green @else gray @endif"> @if($dining->status==1) Enable @else Disable   @endif</span>
                                        </div>                                      
                                    </td>                               
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                        <div class="dropdown text-center">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots"></i>
                                            </button>
                                            <div class="dropdown-menu table-dropdown">
                                                <a class="dropdown-item" href="{{route('admin.dining-token.edit',$dining->id)}}">Edit</a>
                                                <a wire:click="$emit('triggerDelete',{{$dining->id }})" class="dropdown-item" href="javascript:void(0);">Delete</a>     
                                                @if(isset($dining->status) && $dining->status == '0')
                                                <a wire:click="$emit('triggerStatus',{{ $dining->id }})" class="dropdown-item" href="javascript:void(0);">Enable</a>
                                                @else
                                                <a wire:click="$emit('triggerStatus',{{ $dining->id }})" class="dropdown-item" href="javascript:void(0);">Disable</a>
                                                @endif                                         
                                            </div>
                                        </div>
                                    </td>
								</tr>
                            @endforeach
                        @else
                        <td colspan="2"></td>
                        <td valign="top" colspan="1" class="dataTables_empty">{{$notfoundlabel}}</td>
                        <td  colspan="2"></td>
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
                                {{ $diningtoken->links()}}
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


      function checkstatus(value,id) {
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
                  @this.call('statusChange',id)
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
     $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

  </script>
  
@endpush