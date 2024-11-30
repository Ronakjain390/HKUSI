<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Basic Info</h6>
    </div>
	<div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Registration Date</th>
                    <td>@if(isset($MemberInfo->created_at) && !empty($MemberInfo->created_at)) {{date('Y-m-d' , strtotime($MemberInfo->created_at))}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Registration Time</th>
                    <td>@if(isset($MemberInfo->created_at) && !empty($MemberInfo->created_at)) {{date('h:i:s' , strtotime($MemberInfo->created_at))}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">QR Code</th>
                    <td>@if($MemberInfo->application_number!='') {!! DNS2D::getBarcodeHTML($MemberInfo->application_number, 'QRCODE',1.5,1.5) !!} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Application #</th>
                    <td>@if(isset($MemberInfo->application_number) && !empty($MemberInfo->application_number)) {{$MemberInfo->application_number}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Title</th>
                    <td>@if(isset($MemberInfo->title) && !empty($MemberInfo->title)) {{$MemberInfo->title}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Given Name</th>
                    <td>@if(isset($MemberInfo->given_name) && !empty($MemberInfo->given_name)) {{$MemberInfo->given_name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Surname</th>
                    <td>@if(isset($MemberInfo->surname) && !empty($MemberInfo->surname)) {{$MemberInfo->surname}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Chi. Name</th>
                    <td>@if(isset($MemberInfo->chinese_name) && !empty($MemberInfo->chinese_name)) {{$MemberInfo->chinese_name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Gender</th>
                    <td>@if(isset($MemberInfo->gender) && !empty($MemberInfo->gender)) {{$MemberInfo->gender}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Date of Birth</th>
                    <td>@if(isset($MemberInfo->date_of_birth) && !empty($MemberInfo->date_of_birth)) {{date('Y-m-d',$MemberInfo->date_of_birth)}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">HKID</th>
                    <td>@if(isset($MemberInfo->hkid_card_no) && !empty($MemberInfo->hkid_card_no)) {{$MemberInfo->hkid_card_no}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Passport</th>
                    <td>@if(isset($MemberInfo->passport_no) && !empty($MemberInfo->passport_no)) {{$MemberInfo->passport_no}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Nationality</th>
                    <td>@if(isset($MemberInfo->nationality) && !empty($MemberInfo->nationality)) {{$MemberInfo->getNationalty->name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Study Country</th>
                    <td>@if(isset($MemberInfo->study_country_id) && !empty($MemberInfo->study_country_id)) {{$MemberInfo->getStudyCountry->name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Mobile No.</th>
                    <td>@if(isset($MemberInfo->mobile_tel_no) && !empty($MemberInfo->mobile_tel_no)) {{$MemberInfo->mobile_tel_no}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Email Address</th>
                    <td>@if(isset($MemberInfo->getUserDetail->email) && !empty($MemberInfo->getUserDetail->email)) {{$MemberInfo->getUserDetail->email}} @endif</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="card custom-card profile-details margin-b-20"style="margin-top: 20px;">
    <div class="basic-details">
        <h6 class="card-heading">Contact Info</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Mobile No.</th>
                    <td>@if(isset($MemberInfo->mobile_tel_no) && !empty($MemberInfo->mobile_tel_no)) {{$MemberInfo->mobile_tel_no}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Contact Email Address</th>
                    <td>@if(isset($MemberInfo->contact_email) && !empty($MemberInfo->contact_email)) {{$MemberInfo->contact_email}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Contact Eng. Name</th>
                    <td>@if(isset($MemberInfo->contact_english_name) && !empty($MemberInfo->contact_english_name)) {{$MemberInfo->contact_english_name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Contact Chi. Name</th>
                    <td>@if(isset($MemberInfo->contact_chinese_name) && !empty($MemberInfo->contact_chinese_name)) {{$MemberInfo->contact_chinese_name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Contact Relation</th>
                    <td>@if(isset($MemberInfo->contact_relationship) && !empty($MemberInfo->contact_relationship)) {{$MemberInfo->contact_relationship}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Contact Tel. No.</th>
                    <td>@if(isset($MemberInfo->contact_tel_no) && !empty($MemberInfo->contact_tel_no)) {{$MemberInfo->contact_tel_no}} @endif</td>
                </tr>
                {{--<tr>
                    <th class="t-basic">Activation</th>
                    <td>@if(isset($MemberInfo->status) && !empty($MemberInfo->status) && $MemberInfo->status == '1') Yes @else No @endif</td>
                </tr>--}}
            </tbody>
        </table>
    </div>
</div>