<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Basic Info</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Created Date</th>
                    <td>@if(isset($UserInfo->created_at) && !empty($UserInfo->created_at)) {{date('Y-m-d' , strtotime($UserInfo->created_at))}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">User #</th>
                    <td>@if(isset($UserInfo->id) && !empty($UserInfo->id)) #{{$UserInfo->id}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Email Address</th>
                    <td>@if(isset($UserInfo->email) && !empty($UserInfo->email)) #{{$UserInfo->email}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Title</th>
                    <td>@if(isset($UserInfo->title) && !empty($UserInfo->title)) {{$UserInfo->title}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Gender</th>
                    <td>@if(isset($UserInfo->gender) && !empty($UserInfo->gender)) {{$UserInfo->gender}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Surname</th>
                    <td>@if(isset($UserInfo->surname) && !empty($UserInfo->surname)) {{$UserInfo->surname}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Given Name</th>
                    <td>@if(isset($UserInfo->given_name) && !empty($UserInfo->given_name)) {{$UserInfo->given_name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Mobile No.</th>
                    <td>@if(isset($UserInfo->mobile_tel_no) && !empty($UserInfo->mobile_tel_no)) {{$UserInfo->mobile_tel_no}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Department</th>
                    <td>@if(isset($UserInfo->department) && !empty($UserInfo->department)) {{$UserInfo->department}} @endif</td>
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
                        @php
                            $roleNames = $UserInfo->getRoleNames();
                        @endphp
                        @foreach ($roleNames as $roleName)
                            {{$roleName}}
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>