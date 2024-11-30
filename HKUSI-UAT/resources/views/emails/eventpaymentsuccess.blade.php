@extends('emails.layout.app')
@section('content')
<tr>
 <td style="padding:20px;background-color: #fff;">
    <table style="background-color: #f5faf6;width: 100%;">
        <tr>
            <td style="padding: 20px;">
                <p>Dear @if(isset($mailInfo['given_name']) && !empty($mailInfo['given_name'])) {{$mailInfo['given_name']}} @endif @if(isset($mailInfo['application_number']) && !empty($mailInfo['application_number'])) [{{$mailInfo['application_number']}}] @endif ,</p>
                <p>Your registration for the <b> {{ $mailInfo['event_details']->event_name ?? ''}} </b> is now confirmed. Please find the event details below:</p>
                <p><b>Event</b>:{{ $mailInfo['event_details']->event_name ?? ''}} <br>
                <b>Date</b>:{{ date("Y-m-d",$mailInfo['event_details']->date)}} <br>
                <b>Time</b>:{{date("H:i",$mailInfo['event_details']->start_time)}} - {{date("H:i",$mailInfo['event_details']->end_time)}}<br>
                <b>Location</b>:{{ $mailInfo['event_details']->location ?? ''}}<br>
                <b>Fees</b>:{{ $mailInfo['event_details']->unit_price ?? ''}}<br>
                <b>Assembly place</b>:{{ $mailInfo['event_details']->assembly_location ?? ''}}<br>
                <b>Assembly time</b>:{{ date("H:i",$mailInfo['event_details']->assembly_start_time) }} - {{ date("H:i",$mailInfo['event_details']->assembly_end_time) }}</p>
                <p>Please present your profile QR code, which can be found on the HKUSI Online Reservation Platform under the "Account" page upon arrival. We also recommend that you review the event details beforehand to ensure a smooth and enjoyable experience. </p>
                <p>We look forward to welcoming you to the event!</p>
                <p>Should you have any inquiries, please contact us via email at  <a href="mailto:ugsummer@hku.hk">ugsummer@hku.hk</a> (UG Programme) / <a href="mailto:hssummer@hku.hk">hssummer@hku.hk</a> (HS Programme).</p>
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