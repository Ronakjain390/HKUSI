@extends('emails.layout.app')
@section('content')
<tr>
 <td style="padding:20px;background-color: #fff;">
    <table style="background-color: #f5faf6;width: 100%;">
        <tr>
            <td style="padding: 20px;">
                <p>Dear @if(isset($mailInfo['given_name']) && !empty($mailInfo['given_name'])) {{$mailInfo['given_name']}} @endif @if(isset($mailInfo['application_id']) && !empty($mailInfo['application_id'])) [{{$mailInfo['application_id']}}] @endif ,</p>
                <p>Thank you for joining the HKU Summer Institute!</p>
                <p>Your online reservation application account has been created. Please click on the following link to activate your account:</p>
                 <p><a href="{{$mailInfo['url']}}">Link</a></p>
                <p>Your HKUSI Online Reservation Platform account has been created. To activate it, please click on the link below and enter your <b> HKUSI Application Number @if(isset($mailInfo['application_id']) && !empty($mailInfo['application_id'])) ({{$mailInfo['application_id']}}) @endif and email address.</b> Once your account is activated, you will be able to submit requests for residential hall reservations and events booking at HKU. </p>
                 <p>Should you have any inquiries, please contact us via email at <a href="mailto:ugsummer@hku.hk">ugsummer@hku.hk</a> (UG Programme) / <a href="mailto:hssummer@hku.hk">hssummer@hku.hk</a> (HS Programme).</p>
                <p>Best regards,</p>
                <p style="margin: 0;">HKU Summer Institute</p>
                <p style="margin: 0;">Academic Liaison Office</p>
                <p style="margin: 0;">The Registry </p>
                <p style="margin: 0;">The University of Hong Kong</p>
            </td>
        </tr>
    </table>
    <table style="width:100%">
        <tr>
            <td style="padding: 20px 0;text-align: center;">PLEASE DO NOT REPLY TO THIS EMAIL. It is automatically generated from the application system.</td>
        </tr>
    </table>
</td> 
</tr> 
@endsection