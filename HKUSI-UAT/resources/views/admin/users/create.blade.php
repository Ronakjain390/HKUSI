@extends('admin.layouts.index')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        {!! Form::open(array('route' => 'admin.users.store','method'=>'POST','class'=>'edit-form','autocomplete' => 'off','files' =>false, 'id'=>'quickForm')) !!}
            <div class="card custom-card profile-details">
                <div class="basic-details">
                    <h6 class="card-heading">Basic Info</h6>
                </div>
                <div class="table-responsive table-details">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Email Address</th>
                                <td>
                                    <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}">
                                    @error('email')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <th class="t-basic">Title</th>
                                <td>
                                    <input type="text" name="title" class="form-control" placeholder="Title" value="{{ old('title') }}">
                                    @error('title')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <th class="t-basic">Gender</th>
                                <td>
                                    <input type="text" name="gender" class="form-control" placeholder="Male" value="{{ old('gender') }}">
                                    @error('gender')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <th class="t-basic">Surname</th>
                                <td>
                                    <input type="text" name="surname" class="form-control" placeholder="Surname" value="{{ old('surname') }}">
                                    @error('surname')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <th class="t-basic">Given Name</th>
                                <td>
                                    <input type="text" name="given_name" class="form-control" placeholder="Given Name" value="{{ old('given_name') }}">
                                    @error('given_name')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <th class="t-basic">Mobile No.</th>
                                <td>
                                    <input type="text" name="mobile_tel_no" class="form-control" placeholder="Mobile No." value="{{ old('mobile_tel_no') }}">
                                    @error('mobile_tel_no')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <th class="t-basic">Department</th>
                                <td>
                                    <input type="text" name="department" class="form-control" placeholder="Department" value="{{ old('department') }}">
                                    @error('department')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card custom-card profile-details">
                <div class="basic-details">
                    <h6 class="card-heading">Role</h6>
                </div>
                <div class="table-responsive table-details">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="t-basic">Role</th>
                                <td>
                                    <select class="form-control" name="role">
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
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
        
        <!-- / Content -->
        <div class="content-backdrop fade "></div>
        <!-- Content wrapper -->
    </div>
    <!-- / Layout page -->
@endsection