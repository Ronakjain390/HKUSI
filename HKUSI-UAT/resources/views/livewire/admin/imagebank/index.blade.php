<div class="container-xxl flex-grow-1 container-p-y">
     @php 
        use App\Models\MemberInfo; 
	@endphp
    <style type="text/css">
    .d_loader{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        background: #000000d4;
    }
ul.download-loader {
  width: 100%;
  text-align: center;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
}

ul.download-loader li {
  display: inline-block;
  list-style-type: none;
  color: #484848;
  font-size: 9vw;
  letter-spacing: 15px;
  margin-bottom: 15px;
  animation: flash 1.4s linear infinite;
}

@keyframes flash {
  0% {
    color: #484848;
    text-shadow: none;
  }
  90% {
    color: #484848;
    text-shadow: none;
  }
  100% {
    color: #00ccff;
    text-shadow: 0 0 8px #00ffff, 0 0 50px #00ccff;
  }
}

ul.download-loader li:nth-child(1) {
  animation-delay: 0.1s;
}
ul.download-loader li:nth-child(2) {
  animation-delay: 0.2s;
}
ul.download-loader li:nth-child(3) {
  animation-delay: 0.3s;
}
ul.download-loader li:nth-child(4) {
  animation-delay: 0.4s;
}
ul.download-loader li:nth-child(5) {
  animation-delay: 0.5s;
}
ul.download-loader li:nth-child(6) {
  animation-delay: 0.6s;
}
ul.download-loader li:nth-child(7) {
  animation-delay: 0.7s;
}

</style>
    <div class="card custom-card">
        <div class="filter-card">
            <div class="row">
                <div class="col-sm-12 col-md-7">
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
                                                <button type="button" class="accordion-button collapsed"
                                                    data-bs-toggle="collapse" data-bs-target="#accordionStyle1-1"
                                                    aria-expanded="false">
                                                    Year
                                                </button>
                                            </h2>
											<div id="accordionStyle1-1" class="accordion-collapse collapse" data-bs-parent="#accordionStyle1">
                                                <div class="accordion-body">
                                                    <div class="fliter-checkbox">
                                                        <div class="filter-dropdown fx">
                                                            <div class="select-custom" wire:ignore style="width:300px !important">
                                                                <select id="selectpickerBasic" onchange="return getProgrammeFilterdata(this.value);" class="selectpicker w-100" data-style="btn-default" wire:model.defer="hall_setting_id" tabindex="null">
																	<option>Select Year</option>
                                                                    @if (isset($Yeardata) && count($Yeardata))
                                                                        @foreach ($Yeardata as $key => $values)
                                                                            <option value="{{$values->id}}">{{ $values->year }}
                                                                            </option>
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
                                                    Programme
                                                </button>
                                            </h2>
											<div id="accordionStyle1-2" class="accordion-collapse collapse" data-bs-parent="#accordionStyle2">
                                                <div class="accordion-body">
                                                    <div class="fliter-checkbox">
                                                        <div class="filter-dropdown fx">
                                                            <div class="select-custom" wire:ignore style="width:300px !important">
                                                                <select id="selectpickerBasic" onchange="return getProgrammeFilterdata(this.value);" class="selectpicker w-100" data-style="btn-default" wire:model.defer="hall_setting_id" tabindex="null">
																	<option>Select Programme</option>
                                                                    @if(isset($programme) && count($programme))
                                                                        @foreach($programme as $programmeData)
                                                                        <option value="{{$programmeData->programme_code}}">{{$programmeData->programme_name . " / " . $programmeData->programme_code}}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>
                                    <div class="row">
                                            <div class="reset-fiter d-flex">
                                                <button type="button" wire:click="$set('daterange', 'true')"
                                                    class="btn btn-label-info">Filter</button>
                                                <button type="reset" style="background-color: #babfc7;" onclick="window.location.reload();" class="btn">Reset</button>
                                            </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-12 col-md-5">
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
        <form action="{{ route('admin.imagebank.deleteall') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="delete">
        <div class="filter-card action-card">
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <div class=" fliter-flex d-flex align-items-center">
                        <div class="filter-label ">
                            <label><h6>Action</h6></label>
                        </div>                        
                        <div class="flex-filter-box" wire:ignore>
                            <div class="filter-dropdown fx">
                                <div class="select-custom">
                                    <select id="selectpickerBasic" class="selectpicker w-100" data-style="btn-default" tabindex="null">
                                        <option value="Delete all">Delete</option>
                                    </select>
                                </div>
                            </div>
                            <div class="filter-button fx">
                                <button type="submit" class="btn btn-label-info m-0">Confirm</button>
                            </div>
                            <div class="filter-button fx plus-button 123">
								<a  href="javascript:void('0');">
                                <button type="button" id="downloadimages" wire:click="imgDownload()">
                                  <span id="removeicon"><i class="fa fa-download"  aria-hidden="true"></i></span> 
                                   <div class="d_loader" wire:loading wire:target="imgDownload">
                                     <ul class="download-loader">
                                        <li><span>L</span></li>
                                        <li><span>O</span></li>
                                        <li><span>A</span></li>
                                        <li><span>D</span></li>
                                        <li><span>I</span></li>
                                        <li><span>N</span></li>
                                        <li><span>G</span></li>
                                      </ul>
                                   </div>
                                </button>
                                </a>
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
                            <th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all " rowspan="1 " colspan="1 " data-col="0 " aria-label=" " ><input type="checkbox" id="selectAll" class="form-check-input "></th>
                            <th onclick="return shorting('created_at','{{$order_by}}');" class="sorting @if(isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'created_at' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending " aria-label="Name: activate to sort column ascending ">Created Date</th>
                            <th onclick="return shorting('id','{{$order_by}}');" class="sorting @if(isset($order_type) && $order_type == 'id' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'id' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0 " aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1" aria-sort="descending " aria-label="Create Date: activate to sort column ascending ">image #</th>
                            <th onclick="return shorting('application_id','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'application_id' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'application_id' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Import: activate to sort column ascending ">Application No</th>
                            <th onclick="return shorting('application_id','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'application_id' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'application_id' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Import: activate to sort column ascending ">Year</th>
                            <th onclick="return shorting('programe_code','{{$order_by}}');" class="sorting   @if(isset($order_type) && $order_type == 'programe_code' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'programe_code' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " s="" aria-label="Type: activate to sort column ascending ">Programe Code</th>
                            <th onclick="return shorting('programe_name','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'programe_name' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'programe_name' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " width="200px" aria-label="City: activate to sort column ascending ">Programe Name</th>
                            <th onclick="return shorting('profile_image','{{$order_by}}');" class="sorting  @if(isset($order_type) && $order_type == 'profile_image' && isset($order_by) && $order_by == 'DESC') sorting_desc @elseif(isset($order_type) && $order_type == 'profile_image' && isset($order_by) && $order_by == 'ASC') sorting_asc @endif " tabindex="0" aria-controls="DataTables_Table_3 " rowspan="1 " colspan="1 " aria-label="Status: activate to sort column ascending ">Profile Image</th>
                            <th rowspan="1" colspan="1">action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($imagebank))
                            @foreach($imagebank as $key => $iamgedata)
                                <tr>
                                    <td valign="top" colspan="1 " class=" dt-checkboxes-cell dt-checkboxes-select-all "><input type="checkbox" name="selectedata[]" value="{{$iamgedata->id}}"class="form-check-input "></td>
                                    <td valign="top" colspan="1" class="dataTables_empty">@if(isset($iamgedata->created_at) && !empty($iamgedata->created_at)) {{date('Y-m-d H:i:s' , strtotime($iamgedata->created_at))}} @endif</td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    @if(isset($iamgedata->id) && !empty($iamgedata->id)) #{{$iamgedata->id}} @endif</td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    @if(isset($iamgedata->application_id) && !empty($iamgedata->application_id)) {{$iamgedata->application_id}} @endif</td>
                                    @php 
                                    $programme_name = '';
                                    $programmecode = '';
                                    $year= "";
                                    $memberinfo = MemberInfo::where('application_number',$iamgedata->application_id)->first();
                                    @endphp
                                       @php $year = ''; @endphp
                                        @if (isset($memberinfo->getMemberHallSettings) && count($memberinfo->getMemberHallSettings))
                                            @foreach($memberinfo->getMemberHallSettings as $memberYear)
                                                @if(!empty($year))
                                                    @php $year .= ', ' . $memberYear->getHallSettingDetail->year; @endphp
                                                @else
                                                    @php $year .= $memberYear->getHallSettingDetail->year; @endphp
                                                @endif 
                                            @endforeach
                                        @endif
                                    <td valign="top" colspan="1" class="dataTables_empty">{{$year}}</td>
                                   
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                        @if(isset($memberinfo->getMemberProgrammeDetail) && count($memberinfo->getMemberProgrammeDetail))
                                            @foreach($memberinfo->getMemberProgrammeDetail as $programmeYear)
                                                @if(!empty($programme_name) && !empty($programmecode))
                                                    @php 
                                                        $programmecode .= ', ' . $programmeYear->getProgrammeDetail->programme_code;
                                                        $programme_name .= ', ' . $programmeYear->getProgrammeDetail->programme_name;
                                                    @endphp
                                                @else
                                                    @php 
                                                    $programmecode .= $programmeYear->getProgrammeDetail->programme_code;
                                                    $programme_name .= $programmeYear->getProgrammeDetail->programme_name;
                                                     @endphp
                                                @endif 
                                            @endforeach
                                        @endif
                                    @if(isset($programmecode) && !empty($programmecode)) {{$programmecode}} @endif</td>
                                    <td width="200px" valign="top" colspan="1" class="dataTables_empty"> @if(isset($programme_name) && !empty($programme_name)) {{$programme_name}} @endif</td>                                   
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                    @if(isset($iamgedata->profile_image) && !empty($iamgedata->profile_image)) @if(!empty($iamgedata->profile_image) && Storage::disk($DISK_NAME)->exists($iamgedata->profile_image))
                                    <img class="object-fit-contain" width="30px" height="30px" src="{{asset(Storage::url($iamgedata->profile_image))}}">
                                    @endif
                                    @endif
                                    </td>
                                    <td valign="top" colspan="1" class="dataTables_empty">
                                        <div class="dropdown text-center">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots"></i>
                                      </button>
                                            <div class="dropdown-menu table-dropdown">
                                                <a wire:click="$emit('triggerDelete',{{$iamgedata->id }})" class="dropdown-item" href="javascript:void(0);;">Delete</a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                        <td colspan="4"></td>
                        <td valign="top" colspan="1" class="dataTables_empty">{{$notfoundlabel}}</td>
                        <td  colspan="9"></td>
                        @endif
                       
                    </tbody>
                </table>
				<div class="bottom-show-padding">
                        <div class="row custom-show-drop-down ">
                            <div class="col-sm-12 col-md-6">
                                <div class="bottom-dropdown">
                                    @if (isset($paginate) && !empty($paginate) && $countMember > $paginate)
                                        <div class="show-bottom">
                                            <div class="show-text">
                                                <p>Show</p>
                                            </div>
                                            <div class="filter-dropdown">
                                                <div class="btn-group dropup">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        @if (isset($paginate) && !empty($paginate))
                                                            {{ $paginate }}
                                                        @endif
                                                    </button>
                                                    <ul class="dropdown-menu custom-dropdown">
                                                        <li><a class="dropdown-item" wire:click="$set('paginate', '20')" href="javascript:void(0);">20</a></li>
                                                        <li><a class="dropdown-item" wire:click="$set('paginate', '50')" href="javascript:void(0);">50</a></li>
                                                        <li><a class="dropdown-item" wire:click="$set('paginate', '100')" href="javascript:void(0);">100</a></li>
                                                        <li><a class="dropdown-item" wire:click="$set('paginate', '500')" href="javascript:void(0);">500</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dataTables_info " id="DataTables_Table_3_info" role="status" aria-live="polite ">
                                            <p>Showing <span>
                                                @if (isset($page) && !empty($page))
                                                    {{ $page }}
                                                @endif
                                            </span> to <span>
                                                @if (isset($paginate) && !empty($paginate))
                                                    {{ $paginate }}
                                                @endif
                                            </span> of <span>
                                                @if (isset($imagebank))
                                                    {{ count($imagebank) }}
                                                @endif
                                            </span> entries</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <nav aria-label="Page navigation ">
                                     {{$imagebank->links()}}
                                </nav>
                            </div>
                        </div>
                    </div>
				</div>
            </div>

        </div>
    </form>
    <!-- / Content -->
    <div class="content-backdrop fade "></div>
    <!-- Content wrapper -->
	@if($message = Session::get('notFoundError'))
        <script type="text/javascript"> 
            Swal.fire({
                  text: "{{$message}}",
                  icon: "warning",
                  showCancelButton: false,
                  showConformButton: true,
                  confirmButtonColor: "#ea5455",
                  conformButtonColor: "#ea5455",
                  confirmButtonText: "Ok",
                  timer: 7000,
            });
        </script>
    @endif
</div>
@push('foorterscript')
  <script type="text/javascript">
   
    $("#selectAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
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
	
    function getProgrammeFilterdata(id){
        if (id != '') {
            $.ajax({
                url: "{{route('admin.imagebank.getprogram')}}",
                type: "GET",
                data: {
                    'id': id,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data){
                    var selectOpt = '<option>Selct Programme</option>';
                    $.each(data, function (key, value) {
                        selectOpt +='<option value="' + value.programme_code + '">' + value.programme_name + ' '+ "/ "+' '+ value.programme_code+'</option>';                 
                    });
                   $('.appenddata').html(selectOpt);
                }
          });
        }
    }
    
	$(document).ready(function(){
        $('.select2-selection__rendered').on('click', function () { 
			$('.filter-drop-box').addClass('open-filter');
        });
    });
	
	
      
  </script>
@endpush