{!! Form::model($eventInfo, ['method' => 'PATCH','route' => ['admin.event-setting.update', $eventInfo->id],'id' => 'quickForm','autocomplete' => 'off','files' => 'true','class'=>'edit-form']) !!}
<input type="hidden" name="submit_type" value="basic">
    <div class="card custom-card profile-details">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Event Name</th> 
                        <td><input type="text" name="event_name" required class="form-control" placeholder="Event Name" @if(isset($eventInfo->event_name) && !empty($eventInfo->event_name)) value="{{$eventInfo->event_name}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Type</th>
                        <td>
                            <select class="form-control" name="event_category_id" >
                                <option value="">Select Type</option>
                                @if(isset($category) && count($category))
                                    @foreach($category as $category_type)
                                        <option value="{{$category_type->id}}"@if(isset($eventInfo->event_category_id) && $category_type->id == $eventInfo->event_category_id)  selected @endif>{{$category_type->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        @error('event_category_id')
                        <label class="error" for="event_category_id">{{$message}}</label>
                        @enderror
                        </td>
                    </tr> 
                    <tr>
                        <th class="t-basic">Year</th>
                        <td>
                            <select class="form-control selectYear" name="hall_setting_id" >
                                <option value="">Select Year</option>
                                @if(isset($HallSetting) && count($HallSetting))
                                    @foreach($HallSetting as $hallsettingdata)
                                        <option value="{{$hallsettingdata->id}}"@if(isset($eventInfo->hall_setting_id) && $hallsettingdata->id == $eventInfo->hall_setting_id)  selected @endif>{{$hallsettingdata->year}}</option>
                                    @endforeach
                                @endif
                            </select>
                        @error('hall_setting_id')
                        <label class="error" for="hall_setting_id">{{$message}}</label>
                        @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Language</th>
                        <td>
                            <select class="form-control" name="language_id" >
                                <option value="">Select Language</option>
                                  @if(isset($language) && count($language))
                                    @foreach($language as $datalanguage)
                                        <option value="{{$datalanguage->id}}"@if(isset($eventInfo->language_id) && $datalanguage->id == $eventInfo->language_id)  selected @endif>{{$datalanguage->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        @error('language_id')
                        <label class="error" for="language_id">{{$message}}</label>
                        @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Short Description</th>
                        <td><textarea  name="short_description" required class="form-control" placeholder="Short Description">@if(isset($eventInfo->short_description) && !empty($eventInfo->short_description)){{$eventInfo->short_description}} @endif</textarea>
                        @error('short_description')
                        <label class="error" for="short_description">{{$message}}</label>
                        @enderror
                        </td>
                    </tr> 
                    <tr>
                        <th class="t-basic">Description</th>
                        <td><textarea  name="description" required class="form-control" placeholder="Description">@if(isset($eventInfo->description) && !empty($eventInfo->description)){{$eventInfo->description}} @endif</textarea>
                        @error('description')
                        <label class="error" for="description">{{$message}}</label>
                        @enderror
                        </td>
                    </tr> 
                    <tr>
                        <th class="t-basic">Location</th>
                        <td><input type="text" name="location" required class="form-control" placeholder="Location" @if(isset($eventInfo->location) && !empty($eventInfo->location)) value="{{$eventInfo->location}}" @endif>
                        @error('location')
                        <label class="error" for="location">{{$message}}</label>
                        @enderror
                        </td>
                    </tr> 
                     <tr>
                        <th class="t-basic">Assembly Location</th>
                        <td><input type="text" name="assembly_location" required class="form-control" placeholder="Assembly Location" @if(isset($eventInfo->assembly_location) && !empty($eventInfo->assembly_location)) value="{{$eventInfo->assembly_location}}" @endif>
                        @error('assembly_location')
                        <label class="error" for="assembly_location">{{$message}}</label>
                        @enderror
                        </td>
                    </tr> 
                    <tr>
                        <th class="t-basic">Assembly Start Time</th>
                        <td><input type="time" name="assembly_start_time"  required class="form-control" placeholder="Assembly Start Time" @if(isset($eventInfo->assembly_start_time) && !empty($eventInfo->assembly_start_time)) value="{{date('H:i' , $eventInfo->assembly_start_time)}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Assembly End Time</th>
                        <td><input type="time" name="assembly_end_time"  required class="form-control" placeholder="Assembly End Time" @if(isset($eventInfo->assembly_end_time) && !empty($eventInfo->assembly_end_time)) value="{{date('H:i' , $eventInfo->assembly_end_time)}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Date</th>
                        <td><input type="text" readonly id="date" name="date" required class="form-control datepicker" placeholder="Date" @if(isset($eventInfo->date) && !empty($eventInfo->date)) value="{{date('Y-m-d' , $eventInfo->date)}}" @endif></td>
                    </tr> 
                    <tr>
                        <th class="t-basic">Start Time</th>
                        <td><input type="time" name="start_time"  required class="form-control" placeholder="Start Time" @if(isset($eventInfo->start_time) && !empty($eventInfo->start_time)) value="{{date('H:i' , $eventInfo->start_time)}}" @endif></td>
                    </tr>
                     <tr>
                        <th class="t-basic">End Time</th>
                        <td><input type="time" name="end_time"  required class="form-control" placeholder="End Time" @if(isset($eventInfo->end_time) && !empty($eventInfo->end_time)) value="{{date('H:i' , $eventInfo->end_time)}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Quota(s)</th>
                        <td><input type="text" name="quota" id="quota" onkeypress="return isNumber(event);" class="form-control" placeholder="Quota(s)"  maxlength="9" min="{{$eventInfo->getBookingsQouta()}}" max="999999999" @if(isset($eventInfo->quota)) value="{{$eventInfo->quota ?? '0'}}" @endif></td>
                    </tr>
                     <tr>
                        <th class="t-basic">Quota Balance</th>
                        <td><span id="get_quota_balance">@if(isset($eventInfo->quota_balance)) {{$eventInfo->quota_balance}} @endif</span> <input type="hidden" name="quota_balance" id="quota_balance" class="form-control" placeholder="Quota balance" maxlength="9" min="0" max="999999999"  @if(isset($eventInfo->quota_balance) && !empty($eventInfo->quota_balance)) value="{{$eventInfo->quota_balance}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Unit Price</th>
                        <td><input type="text" name="unit_price" id="unit_price" onkeypress="return isNumber(event);" class="form-control" placeholder="Unit Price" maxlength="9" min="0" max="999999999" @if(isset($eventInfo->unit_price) && !empty($eventInfo->unit_price)) value="{{$eventInfo->unit_price}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Additional Info</th>
                        <td><textarea  name="additional_info" required class="form-control" placeholder="Additional Info">@if(isset($eventInfo->additional_info) && !empty($eventInfo->additional_info)){{$eventInfo->additional_info}} @endif</textarea>
                        @error('additional_info')
                        <label class="error" for="additional_info">{{$message}}</label>
                        @enderror
                        </td>
                    </tr>
                    <tr>
                        <th class="t-basic">Booking Limit</th>
                        <td><input type="text" name="booking_limit" id="booking_limit" onkeypress="return isNumber(event);" class="form-control" placeholder="Booking Limit" maxlength="9" min="0" max="999999999" @if(isset($eventInfo->booking_limit) && !empty($eventInfo->booking_limit)) value="{{$eventInfo->booking_limit}}" @endif></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Deadline</th>
                        <td><input type="text" readonly name="application_deadline" required class="form-control datepicker" placeholder="Deadline" @if(isset($eventInfo->application_deadline) && !empty($eventInfo->application_deadline)) value="{{date('Y-m-d' , $eventInfo->application_deadline)}}" @endif id="application_deadline"></td>
                    </tr> 
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Programme</h6>
        </div>
        <div class="table-details select-table-custom programmeAccordingtoYear">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Programme</th>
                        <td class="programmeTd">
                        <select class="form-control form-select programmeYear" name="programme_id[]" multiple multiselect-search="true" multiselect-select-all="true">
                                @if(isset($programme) && count($programme))
                                    @foreach($programme as $programmeData)
                                        <option value="{{$programmeData->id}}" @if(isset($eventprograme) && !empty($eventprograme) && in_array($programmeData->id,$eventprograme)) Selected @endif>{{$programmeData->programme_name . " / " . $programmeData->programme_code}}</option>
                                    @endforeach
                                @endif
                        </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
 <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Note</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Terms & Condition</th>
                       <td><input type="text" name="terms_condition"  class="form-control" placeholder="Terms Condition" @if(isset($eventInfo->terms_condition) && !empty($eventInfo->terms_condition)) value="{{$eventInfo->terms_condition}}" @endif ></td>
                    </tr>
                     <tr>
                        <th class="t-basic">Terms Link</th>
                       <td><input type="text" name="terms_link"  class="form-control" placeholder="Terms Link" @if(isset($eventInfo->terms_link) && !empty($eventInfo->terms_link)) value="{{$eventInfo->terms_link}}" @endif></td>
                    </tr>
                     <tr>
                        <th class="t-basic">Pre-arrival</th>
                       <td><input type="text" name="pre_arrival"  class="form-control" placeholder="Pre-arrival" @if(isset($eventInfo->pre_arrival) && !empty($eventInfo->pre_arrival)) value="{{$eventInfo->pre_arrival}}" @endif></td>
                    </tr>
                     <tr>
                        <th class="t-basic">Pre-arrival Link</th>
                       <td><input type="text" name="pre_link"  class="form-control" placeholder="Pre-arrival Link" @if(isset($eventInfo->pre_link) && !empty($eventInfo->pre_link)) value="{{$eventInfo->pre_link}}" @endif></td>
                    </tr>
                     <tr>
                        <th class="t-basic">Notes</th>
                        <td><textarea  name="notes" required class="form-control" placeholder="Notes">@if(isset($eventInfo->notes) && !empty($eventInfo->notes)){{strip_tags($eventInfo->notes) }} @endif</textarea>
                        @error('notes')
                        <label class="error" for="notes">{{$message}}</label>
                        @enderror
                        </td>
                    </tr> 

                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Status</h6>
        </div>
        <div class="table-details select-table-custom">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Status</th>
                        <td>
                            <select class="form-control" name="status">
                                <option value="">Select Status</option>
                                <option value="Enabled" @if(isset($eventInfo->status) && $eventInfo->status == 'Enabled') selected @endif>Enabled</option>
                                <option value="Disabled" @if(isset($eventInfo->status) && $eventInfo->status == 'Disabled') selected @endif>Disabled</option>
                                <option value="Cancelled" @if(isset($eventInfo->status) && $eventInfo->status == 'Cancelled') selected @endif>Cancelled</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card custom-card">
        <div class="form-btn">
            <button type="submit" class="btn action-btn">Save Changes</button>
            <button type="reset" class="btn cancel-btn">Delete</button>
        </div>
    </div>
{!! Form::close() !!}

@push('foorterscript')
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<script>
    CKEDITOR.replaceClass="article-ckeditor";
    $(document).ready(function () {
        $("#quota").keyup(function(){
           var bookVal =  '{{$eventInfo->getBookingsQouta()}}';
           var finalVal = Number(this.value) - Number(bookVal);
           $("#get_quota_balance").html(finalVal);
           $("#quota_balance").val(finalVal);
        });
        // $('.profile-tab a.dropdown-item:first-child').css('display','none');

        $.validator.addMethod('lesthen', function(value, element, param) {
              return this.optional(element) || value < $(param).val();
        }, 'Invalid value');
        $.validator.addMethod('gretherthen', function(value, element, param) {
              return this.optional(element) || value > $(param).val();
        }, 'Invalid value');

        $("#quickForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                event_name: "required",
                programme_id: {
                    required: true,
                },
                event_category_id: {
                    required: true,
                },
                short_description: {
                    required: true,
                },
                description: {
                    required: true,
                },
                location: {
                    required: true,
                },
                date: {
                    required: true,
                    gretherthen: "#application_deadline",
                    
                },
                time: {
                    required: true,
                },
                quota: {
                    required: true,
                },
                unit_price: {
                    required: false,
                },
                additional_info: {
                    required: true,
                },
                notes: {
                    required: true,
                },
                terms_condition: {
                    required: true,
                },
                terms_link: {
                    required: true,
                },
                pre_arrival: {
                    required: true,
                },
                pre_link: {
                    required: true,
                },
                booking_limit: {
                    required: true,
                },
                type: {
                    required: true,
                },
                application_deadline: {
                    required: true,
                    lesthen:"#date"
                },
                status: {
                    required: true,
                },
            },
            // in 'messages' user have to specify message as per rules
            messages: {
                event_name: "Please enter a event name",
                programme_id: {
                    required: "Please select a programme",
                },
                event_category_id: {
                    required: "Please select a event category",
                },
                description: "Please enter a description",
                location: {
                    required: "Please enter a location",
                },
                date: {
                    required: "Please choose a date",
                    gretherthen : "it should be always later than the Deadline"
                },
                time: {
                    required: "Please choose a time",
                },
                quota: {
                    required: "Please enter a quota",
                },
                additional_info: {
                    required: "Please enter a additional info",
                },
                unit_price: {
                    required: "Please enter a unit price",
                },
                notes: {
                    required: "Please enter a notes",
                },
                terms_condition: {
                    required: "Please enter a terms condition",
                },
                terms_link: {
                    required: "Please enter a terms link",
                },
                pre_arrival: {
                    required: "Please enter a pre-arrival",
                },
                pre_link: {
                    required: "Please enter a pre-arrival link",
                },
                booking_limit: {
                    required: "Please enter a booking limit",
                },
                type: {
                    required: "Please enter a type",
                },
                application_deadline: {
                    required: "Please choose a deadline",
                    lesthen: "it should be always earlier than event date"
                },
                status: {
                    required: "Please select a status",
                },
            }
        });
    });
    
    
            /*
        if (id != '') {
             $.ajax({
                url: "{{route('admin.event-setting.getYearProgramme')}}",
                type: "GET",
                data: {
                     'id': id,
                     _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data){
                    var selectOpt = '<table class="table" style="width:450px !important"><tbody><tr><th class="t-basic">Programme</th><td><select class="form-control form-select programmeYear" name="programme_id[]" multiple multiselect-search="true" multiselect-select-all="true">';
                    $.each(data, function (key, value) {
                          selectOpt +='<option value="' + value.id + '">' + value.programme_name + ' '+ "/ "+' '+ value.programme_code+'</option>';                   
                    });
                    selectOpt += '</select></td></tr></tbody></table>';
                    $('.programmeAccordingtoYear').html(selectOpt);
                    // $('.programmeYear').select2();
                    //$('.programmeYear').selectpicker('refresh');
                    
                    // $('.programmeYear').select();
                    $(".programmeYear").multiselect();
                }
           });
        }
        */
        
    
    /*
    $(document).on('change','.allSelectProgramme', function() {   
        if ($('.allSelectProgramme').is(":checked")){
            $('.programmeOptions').attr('checked', true);
        } else {
            $('.programmeOptions').attr('checked', false);
        }
    });
    */
    
    $(".programmeTd").click(function(){ 
        $(".selectYear").trigger("change");
    });
    $('.selectYear').on('change', function() {
        var id =$(this).val();
        /*
        if (id != '') {
            $.ajax({
                url: "{{route('admin.event-setting.getYearProgramme')}}",
                type: "GET",
                data: {
                     'id': id,
                     _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data){  
                    $('.programmeYear').html('');
                    $('.multiselect-dropdown-list').html('');
                    $('.programmeYear').html('<option value="">Select Programme</option>');
                    $('.multiselect-dropdown-list').html('<div class="multiselect-dropdown-all-selector"><input type="checkbox" class="allSelectProgramme"><label>All</label></div>');
                    $.each(data, function (key, value){  
                        $(".programmeYear").append('<option value="' + value.id + '">' + value.programme_name + ' '+ "/ "+' '+ value.programme_code+'</option>');
                        $(".multiselect-dropdown-list").append('<div><input type="checkbox" class="programmeOptions"><label>' + value.programme_name + ' '+ "/ "+' '+ value.programme_code+ '</label></div>');
                    });
                }
                // $(".programmeYear").multiselect();
            });
        }
        */
        
    
        if (id != '') {
             $.ajax({
                url: "{{route('admin.event-setting.getYearProgramme')}}",
                type: "GET",
                data: {
                     'id': id,
                     _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(data){
                    var selectOpt = '<table class="table" style="width:450px !important"><tbody><tr><th class="t-basic">Programme</th><td><select class="form-control form-select programmeYear" name="programme_id[]" multiple multiselect-search="true" multiselect-select-all="true">';
                    $.each(data, function (key, value) {
                          selectOpt +='<option value="' + value.id + '">' + value.programme_name + ' '+ "/ "+' '+ value.programme_code+'</option>';                   
                    });
                    selectOpt += '</select></td></tr></tbody></table>';
                    $('.programmeAccordingtoYear').html(selectOpt);
                    $('.programmeYear').select2();
                    // $('.programmeYear').selectpicker('refresh');
                    
                    // $('.programmeYear').select();
                    //$(".programmeYear").multiselect();
                }
           });
        }
    });
    
</script>
@endpush

