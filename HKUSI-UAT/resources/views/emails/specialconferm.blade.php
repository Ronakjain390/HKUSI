@extends('emails.layout.app')
@section('content')
<tr>
 <td style="padding:20px;background-color: #fff;">
    <table style="background-color: #f5faf6;width: 100%;">
        <tr>
            <td style="padding: 20px;">
                <p>Dear @if(isset($mailInfo['given_name']) && !empty($mailInfo['given_name'])) {{$mailInfo['given_name']}} @endif @if(isset($mailInfo['application_number']) && !empty($mailInfo['application_number'])) [{{$mailInfo['application_number']}}] @endif ,</p>
                <p>Greetings from HKU Summer Institute!</p>
                <p>We have received your two hall reservation requests and have combined them into one lengthened stay period for you. The following are the check-in and check-out dates and the accommodation fees amount for the whole period:</p>

                <p> <b>Check-in and Check-out Arrangement</b> </p>
                @if(isset($mailInfo['check_in_date']) && !empty($mailInfo['check_in_date']))
                <p style="margin: 0;"><b>Check-in Date:</b> {{date('Y-m-d',$mailInfo['check_in_date'])}}</p>
                @endif
                @if(isset($mailInfo['check_out_date']) && !empty($mailInfo['check_out_date']))
                <p style="margin: 0;"><b>Check-out Date:</b> {{date('Y-m-d',$mailInfo['check_out_date'])}}</p>
                @endif
                @if(isset($mailInfo['hall_fees']) && !empty($mailInfo['hall_fees']))
                <p style="margin: 0;"><b>Fees:</b>HKD {{$mailInfo['hall_fees']}} (If you have paid the fee for the first request, you only need to pay the remaining balance)</p>
                @endif
                <p></p>
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
            <td style="padding: 20px 0; text-align: center;">PLEASE DO NOT REPLY TO THIS EMAIL. It is automatically generated from the application system.</td>
        </tr>
    </table>
</td> 
</tr> 
@endsection