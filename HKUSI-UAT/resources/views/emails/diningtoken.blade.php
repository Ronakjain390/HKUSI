@extends('emails.layout.app')
@section('content')
<tr>
    <td style="padding:0 30px;text-align:center;" colspan="2">
        <p style="font-size:15px;margin:20px auto;color:#333;padding:30px 0;font-family: 'SemplicitaPro'; max-width:250px;">Dear {{$mailInfo['given_name']}} [{{$mailInfo['application_id']}}],</p>
    </td>
</tr>
<tr>
    <td style="padding:0 30px;text-align:center;" colspan="2">
        <p style="font-size:15px;margin:20px auto;color:#333;padding:30px 0;font-family: 'SemplicitaPro'; max-width:250px;">We received your order for Dining Tokens successfully and details of the order are show below:</p>
    </td>
</tr>
<table style="width:100%;border:1px solid black; border-collapse: collapse;">
  <tr>
    <th style="border:1px solid black; border-collapse: collapse;">Price(HKD)</th>
    <th style="border:1px solid black; border-collapse: collapse;">Quantity</th> 
    <th style="border:1px solid black; border-collapse: collapse;">HKUSI Token</th>
  </tr>
  <tr>
    <td style="border:1px solid black; border-collapse: collapse;">$100</td>
    <td style="border:1px solid black; border-collapse: collapse;">1</td>
    <td style="border:1px solid black; border-collapse: collapse;">100.00</td>
  </tr>
  <tr>
    <td style="border:1px solid black; border-collapse: collapse;">$500</td>
    <td style="border:1px solid black; border-collapse: collapse;">1</td>
    <td style="border:1px solid black; border-collapse: collapse;">500.00</td>
  </tr>
  <tr>
    <td style="border:1px solid black; border-collapse: collapse;"></td>
    <td style="border:1px solid black; border-collapse: collapse;">Total:</td>
    <td style="border:1px solid black; border-collapse: collapse;">600.00</td>
  </tr>
</table>

@endsection