@extends('emails.layout.app')
@section('content')
<tr>
 <td style="padding:20px;background-color: #fff;">
    <table style="background-color: #f5faf6;width: 100%;">
        <tr>
            <td style="padding: 20px;">
                <p>Dear @if(isset($mailInfo['given_name']) && !empty($mailInfo['given_name'])) {{$mailInfo['given_name']}} @endif @if(isset($mailInfo['application_number']) && !empty($mailInfo['application_number'])) [{{$mailInfo['application_number']}}] @endif ,</p>
                <p>We would like to inform you that the event <b> {{ $mailInfo['event_name'] ?? ''}} </b> you have registered has been updated with the following information:</p>
                <p><b>Event</b>:{{ $mailInfo['event_name'] ?? ''}}<br/>
                <b>Date</b>:{{ date("Y-m-d",$mailInfo['date'])}}<br/>
                <b>Time</b>:{{date("H:i",$mailInfo['start_time'])}} - {{date("H:i",$mailInfo['end_time'])}}<br/>
                <b>Location</b>:{{ $mailInfo['location'] ?? ''}}<br/>
                <b>Fees</b>:{{ $mailInfo['unit_price'] ?? ''}}<br/>
                <b>Assembly place</b>:{{ $mailInfo['assembly_location'] ?? ''}}<br/>
                <b>Assembly time</b>:{{ date("H:i",$mailInfo['assembly_start_time'])}} - {{ date("H:i",$mailInfo['assembly_end_time'])}}</p>
                <p>Please review the changes carefully and ensure that you have the latest information before attending the event. Please remember to present your profile QR code, which can be found on the HKUSI Online Reservation Platform under the "Account" page, or your student ID card upon arrival.</p>
                <p>We look forward to seeing you at the event soon!</p>
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
            <td style="padding: 20px 0; text-align: center;">This email is automatically generated from the application system</td>
        </tr>
    </table>
</td> 
</tr> 
@endsection