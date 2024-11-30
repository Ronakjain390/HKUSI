@extends('emails.layout.app')
@section('content')
<tr>
 <td style="padding:20px;background-color: #fff;">
    <table style="background-color: #f5faf6;width: 100%;">
        <tr>
            <td style="padding: 20px;">
                <p>Dear @if(isset($mailInfo['given_name']) && !empty($mailInfo['given_name'])) {{$mailInfo['given_name']}} @endif @if(isset($mailInfo['application_number']) && !empty($mailInfo['application_number'])) [{{$mailInfo['application_number']}}] @endif ,</p>
                <p>Please note that there have been recent updates to the residential hall for your upcoming stay. Please review the latest hall details below:</p>
                <p style="margin: 0;"> <b>Accommodation</b> </p>
                @if(isset($mailInfo['quotahall']->college_name) && !empty($mailInfo['quotahall']->college_name))
                <p style="margin: 0;"><b>Hall:</b>{{$mailInfo['quotahall']->college_name}} 
                @endif
                @if(isset($mailInfo['quotahall']->address) && !empty($mailInfo['quotahall']->address))
                <p style="margin: 0;"><b>Address:</b>{{$mailInfo['quotahall']->address}}</p>
                @endif
                @if(isset($mailInfo['quotahall']->room_type) && !empty($mailInfo['quotahall']->room_type))
                <p style="margin: 0;"><b>Room Type:</b>{{$mailInfo['quotahall']->room_type}}</p>
                @endif
                @if(isset($mailInfo['booking']->getQuotaRoomDetail->room_code) && !empty($mailInfo['booking']->getQuotaRoomDetail->room_code))
                <p style="margin: 0;"><b>Room no.:</b>{{$mailInfo['booking']->getQuotaRoomDetail->room_code}}</p>
                @endif
                <br>
                <p style="margin: 0;"> <b>Check-in and Check-out Arrangement</b> </p>
                @if(isset($mailInfo['quotahall']->check_in_date) && !empty($mailInfo['quotahall']->check_in_date))
                <p style="margin: 0;"><b>Check-in Date:</b> {{date('Y-m-d',$mailInfo['quotahall']->check_in_date)}}</p>
                @endif
                @if(isset($mailInfo['quotahall']->check_in_time) && !empty($mailInfo['quotahall']->check_in_time))
                <p style="margin: 0;"><b>Check-in Time:</b> {{date('H:i',$mailInfo['quotahall']->check_in_time)}} (15:00-18:00)</p>
                @endif
                @if(isset($mailInfo['quotahall']->check_out_date) && !empty($mailInfo['quotahall']->check_out_date))
                <p style="margin: 0;"><b>Check-out Date:</b> {{date('Y-m-d',$mailInfo['quotahall']->check_out_date)}}</p>
                @endif
                @if(isset($mailInfo['quotahall']->check_out_time) && !empty($mailInfo['quotahall']->check_out_time))
                <p style="margin: 0;"><b>Check-out Time:</b>{{date('H:i',$mailInfo['quotahall']->check_out_time)}}  (09:00-10:00)</p>
                @endif
                <p style="margin-bottom: 0;"> <b>Important Notes</b> </p>
                <li style="margin-top: 0px; padding-top:0 ;">Upon your arrival, please collect your room key and card at @if(isset($mailInfo['quotahall']->room_key_location) && !empty($mailInfo['quotahall']->room_key_location)) <b> {{$mailInfo['quotahall']->room_key_location}} </b>. @endif Please note that you must return your room key and card to the same location before you depart to complete the check-out procedure.</li>
                <li >On the day of arrival, please bring along your signed Appendix A – Undertaking for Hall Residents and submit to our Hall Assistant. You will only be allowed to check-in after we received this undertaking from you.</li>
                <p style="margin: 0;"> <b>Hall Assistant</b> </p>
                @if(isset($mailInfo['quotahall']->ass_name) && !empty($mailInfo['quotahall']->ass_name))
                <p style="margin: 0;"><b>Name:</b> {{$mailInfo['quotahall']->ass_name}}</p>
                @endif
                @if(isset($mailInfo['quotahall']->ass_mobile) && !empty($mailInfo['quotahall']->ass_mobile))
                <p style="margin: 0;"><b>Mobile:</b> {{$mailInfo['quotahall']->ass_mobile}}</p>
                @endif
                @if(isset($mailInfo['quotahall']->ass_email) && !empty($mailInfo['quotahall']->ass_email))
                <p style="margin: 0;"><b>Email:</b> {{$mailInfo['quotahall']->ass_email}}</p>
                @endif
                <p></p>
                <p>*If you are unable to check-in or check-out within the designated timeframe, you MUST send your request to <a href="mailto:ugsummer@hku.hk">ugsummer@hku.hk</a> / <a href="mailto:hssummer@hku.hk">hssummer@hku.hk</a>at least 5 workings days in advance. We will contact you by email to make special arrangements.</p>
                <p style="margin-bottom: 0;"> <b>Hall rules</b> </p>
                <p style="margin:0px;">Please read through the hall rules carefully and remember to follow to them during your stay. Any violation of these rules will result in immediate termination of your residency, and fines/penaltiesmay be applied for any damage or loss of properties. Please refer to the hall rules:@if(isset($mailInfo['quotahall']->pdf) && !empty($mailInfo['quotahall']->pdf) && Storage::disk($DISK_NAME)->exists($mailInfo['quotahall']->pdf))<a style="color:blue;" target="_blank" href="{{asset(Storage::url($mailInfo['quotahall']->pdf))}}">Click here</a>@endif</p>
                <p style="margin-bottom: 0px;"> <b>Basic Facilities</b> </p>
                <li style="margin-top: 0px; padding-top:0 ;">Each room is equipped with air conditioning and comes furnished with a wardrobe, a desk, a chair, a network port, a pillow, a bed, one bedsheet, one blanket and linen. However, towels and other toiletries will not be provided.</li>
                <li >Floors are single gender based.</li>
                <li>Communal bathrooms, toilets, a pantry with hot water supply, a microwave and a refrigerator are provided.</li>
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
