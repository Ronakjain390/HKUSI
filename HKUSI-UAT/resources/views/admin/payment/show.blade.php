<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Basic Info</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Create Date</th>
                    <td>@if(isset($PaymentInfo->created_at) && !empty($PaymentInfo->created_at)) {{date('Y-m-d' , strtotime($PaymentInfo->created_at))}} @endif</td>
                </tr>
                 <tr>
                    <th class="t-basic">Create Time</th>
                    <td>@if(isset($PaymentInfo->created_at) && !empty($PaymentInfo->created_at)){{date('h:i:s' , strtotime($PaymentInfo->created_at))}}@endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Year</th>
                    <td>{{$PaymentInfo->getBookingDetails->getHallsetting->year ?? ''}}</td>
                </tr>
                <tr>
                    <th class="t-basic">Payment #</th>
                    <td>@if(isset($PaymentInfo->id) && !empty($PaymentInfo->id)) #{{$PaymentInfo->id}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Order #</th>
                    <td>@if(isset($PaymentInfo->order_no) && !empty($PaymentInfo->order_no)) #{{$PaymentInfo->order_no}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Transaction #</th>
                    <td>@if(isset($PaymentInfo->transaction_id) && !empty($PaymentInfo->transaction_id)) #{{$PaymentInfo->transaction_id}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Service</th>
                    <td>@if(isset($PaymentInfo->service_type) && !empty($PaymentInfo->service_type)) {{$PaymentInfo->service_type}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Amount</th>
                    <td>@if(isset($PaymentInfo->amount) && !empty($PaymentInfo->amount)) ${{$PaymentInfo->amount}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Payment Method</th>
                    <td>@if(isset($PaymentInfo->payment_method) && !empty($PaymentInfo->payment_method)) {{$PaymentInfo->payment_method}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Payment Type</th>
                    <td>@if(isset($PaymentInfo->pay_type) && !empty($PaymentInfo->pay_type)) {{$PaymentInfo->pay_type}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Booking #</th>
                    <td>@if(isset($PaymentInfo->payment_id) && !empty($PaymentInfo->payment_id)) #{{$PaymentInfo->payment_id}} @endif</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="card custom-card profile-details margin-b-20">
    <div class="basic-details">
        <h6 class="card-heading">Member Info</h6>
    </div>
    <div class="table-responsive table-details">
        <table class="table">
            <tbody>
                <tr>
                    <th class="t-basic">Application #</th>
                    <td>@if(isset($PaymentInfo->getMemberInfos->application_number) && !empty($PaymentInfo->getMemberInfos->application_number)) {{$PaymentInfo->getMemberInfos->application_number}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Title</th>
                    <td>@if(isset($PaymentInfo->getMemberInfos->title) && !empty($PaymentInfo->getMemberInfos->title)) {{$PaymentInfo->getMemberInfos->title}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Surname</th>
                    <td>@if(isset($PaymentInfo->getMemberInfos->surname) && !empty($PaymentInfo->getMemberInfos->surname)) {{$PaymentInfo->getMemberInfos->surname}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Given Name</th>
                    <td>@if(isset($PaymentInfo->getMemberInfos->given_name) && !empty($PaymentInfo->getMemberInfos->given_name)) {{$PaymentInfo->getMemberInfos->given_name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Chiness Name</th>
                    <td>@if(isset($PaymentInfo->getMemberInfos->chinese_name) && !empty($PaymentInfo->getMemberInfos->chinese_name)) {{$PaymentInfo->getMemberInfos->chinese_name}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Gender</th>
                    <td>@if(isset($PaymentInfo->getMemberInfos->gender) && !empty($PaymentInfo->getMemberInfos->gender)) {{$PaymentInfo->getMemberInfos->gender}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Date Of Birth</th>
                    <td>@if(isset($PaymentInfo->getMemberInfos->date_of_birth) && !empty($PaymentInfo->getMemberInfos->date_of_birth)) {{date('d F Y',$PaymentInfo->getMemberInfos->date_of_birth)}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">HKID</th>
                    <td>@if(isset($PaymentInfo->getMemberInfos->hkid_card_no) && !empty($PaymentInfo->getMemberInfos->hkid_card_no)) {{$PaymentInfo->getMemberInfos->hkid_card_no}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Passport No.</th>
                    <td>@if(isset($PaymentInfo->getMemberInfos->passport_no) && !empty($PaymentInfo->getMemberInfos->passport_no)) {{$PaymentInfo->getMemberInfos->passport_no}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Nationality</th>
                    <td>@if(isset($PaymentInfo->getMemberInfos->nationality) && !empty($PaymentInfo->getMemberInfos->nationality)) {{$PaymentInfo->getMemberInfos->nationality}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Email Address</th>
                    <td>@if(isset($PaymentInfo->getMemberInfos->email_address) && !empty($PaymentInfo->getMemberInfos->email_address)) {{$PaymentInfo->getMemberInfos->email_address}} @endif</td>
                </tr>
                <tr>
                    <th class="t-basic">Mobile No.</th>
                    <td>@if(isset($PaymentInfo->getMemberInfos->mobile_tel_no) && !empty($PaymentInfo->getMemberInfos->mobile_tel_no)) {{$PaymentInfo->getMemberInfos->mobile_tel_no}} @endif</td>
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
                        <select name="activation" class="form-control" name="payment_status" disabled style="width: 21%; background-color: #fff;">
                            <option value="">Select Status</option>
                            <option value="Processing" @if(isset($PaymentInfo->payment_status) && ($PaymentInfo->payment_status == '' || $PaymentInfo->payment_status == 'Processing')) selected @endif>Processing</option>  
                            <option value="PENDING" @if(isset($PaymentInfo->payment_status) &&  $PaymentInfo->payment_status == 'PENDING') selected @endif> PENDING</option> 
                            <option value="EXPIRED" @if(isset($PaymentInfo->payment_status) &&  $PaymentInfo->payment_status == 'EXPIRED') selected @endif> EXPIRED</option> 
                            <option value="PAID" @if(isset($PaymentInfo->payment_status) &&  $PaymentInfo->payment_status == 'PAID') selected @endif> PAID</option>   
                            <option value="UATPAID" @if(isset($PaymentInfo->payment_status) &&  $PaymentInfo->payment_status == 'UATPAID') selected @endif> UATPAID</option>  
                            <option value="REJECTED" @if(isset($PaymentInfo->payment_status) &&  $PaymentInfo->payment_status == 'REJECTED') selected @endif> REJECTED</option>
                            <option value="CANCELLED" @if(isset($PaymentInfo->payment_status) &&  $PaymentInfo->payment_status == 'CANCELLED') selected @endif> CANCELLED</option>  
                            <option value="REFUNDED" @if(isset($PaymentInfo->payment_status) &&  $PaymentInfo->payment_status == 'REFUNDED') selected @endif> REFUNDED</option>  
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
