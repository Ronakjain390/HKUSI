<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style type="text/css">
        @font-face {
            font-family: 'SemplicitaPro';
            src: url('{{asset("emails/")}}/font/SemplicitaPro.otf');
            font-weight: normal;
            font-style: normal;
        }
        
        body,
        a,
        p,
        table,
        td,
        span {
            font-family: 'SemplicitaPro';
            mso-line-height-rule: exactly;
            mso-padding-alt: 0;
            border-spacing: 0;
        }
    </style>
</head>

<body >
<div style="max-width: 700px; margin: 0 auto;background-color: #f6f7fb;">
    <table style="width: 100%;padding: 20px;">

        @include('emails.includes.header')
        
        @yield('content')
        
        @include('emails.includes.footer')
        
    </table>
</div>
</body>
</html>