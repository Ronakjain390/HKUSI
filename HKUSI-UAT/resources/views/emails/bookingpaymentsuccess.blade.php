@extends('emails.layout.app')
@section('content')
<tr>
 <td style="padding:20px;background-color: #fff;">
    <table style="background-color: #f5faf6;width: 100%;">
        <tr>
            <td style="padding: 20px;">
                <p>Dear <b>@if(isset($mailInfo['given_name']) && !empty($mailInfo['given_name'])) {{$mailInfo['given_name']}} @endif @if(isset($mailInfo['application_id']) && !empty($mailInfo['application_id'])) [{{$mailInfo['application_id']}}] @endif ,</b></p>
                <p>Your payment has been confirmed! You have successfully booked the event {Event_Name}, please check the event information below:</p>
                <p style="margin: 0;"><b>Event: </b>{Event_Name}</p>
                <p style="margin: 0;"><b>Date: </b>{Event_Date}</p>
                <p style="margin: 0;"><b>Time: </b>{Event_Time}</p>
                <p style="margin: 0;"><b>Fees: </b>{Event_Fees}</p>
                <p style="margin: 0;"><b>Assembly place: </b>{Event_Assemby_Place}</p>
                <p style="margin: 0;"><b>Assembly time: </b>{Event_Assembly_Time}</p>
                <p>Please present your profile QR account upon arrival. Remember to check all the details in advance!</p>
                <p>Looking forward to seeing you at the event!</p>
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
            <td style="padding: 20px 0;">PLEASE DO NOT REPLY TO THIS EMAIL. It is automatically generated from the application system.</td>
        </tr>
    </table>
</td> 
</tr> 
@endsection