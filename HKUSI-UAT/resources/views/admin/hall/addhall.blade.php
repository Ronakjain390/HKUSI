    {!! Form::open(array('route' => 'admin.accommondation-setting.store','method'=>'POST','class'=>'edit-form','autocomplete' => 'off','files' =>true)) !!}
    <input type="hidden" name="quote_id" value="{{$dataId}}">
    <div class="card custom-card">
        <div class="basic-details">
            <h6 class="card-heading">Basic Info</h6>
        </div>
        <div class="table-responsive table-details">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="t-basic">Start Date</th>
                        <td><input type="datetime-local" name="start_date" class="form-control" placeholder="Start Date"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">End Date</th>
                        <td><input type="datetime-local" name="end_date" class="form-control" placeholder="End Date" ></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Total Quota</th>
                        <td><input type="text" name="total_quotas" class="form-control" placeholder="Total Quota" ></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Male</th>
                        <td><input type="text" name="male" class="form-control" placeholder="Male"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Female</th>
                        <td><input type="text" name="female" class="form-control" placeholder="Female"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">College Name</th>
                        <td><input type="text" name="college_name" class="form-control" placeholder="College Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Address</th>
                        <td><input type="text" name="address" class="form-control" placeholder="Address"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Room Type</th>
                        <td><input type="text" name="room_type" class="form-control" placeholder="Room Type"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">College Name</th>
                        <td><input type="text" name="college_name" class="form-control" placeholder="College Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Check In Date</th>
                        <td><input type="datetime-local" name="check_in_date" class="form-control" placeholder="Check In Date"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Check In Time</th>
                        <td><input type="datetime-local" name="check_in_time" class="form-control" placeholder="Check In Time"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">Check Out Date</th>
                        <td><input type="datetime-local" name="check_out_date" class="form-control" placeholder="College Name"></td>
                    </tr>
                    <tr>
                        <th class="t-basic">PDF</th>
                        <td><input type="file" name="pdf" class="form-control" ></td>
                    </tr>
                    <tr>
                    <th class="t-basic">Status</th>
                        <td><select name="status" class="form-control">
                        	<option>Select Status</option>
                        	<option value="1">Active</option>
                        	<option value="0">Inactive</option>
                        </select></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
   
    <div class="card custom-card">
        <div class="form-btn">
            <button type="submit" class="btn action-btn">Save Changes</button>
        </div>
    </div>
{!! Form::close() !!}