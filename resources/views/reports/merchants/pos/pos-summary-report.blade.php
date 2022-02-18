<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$report->reportTitle}}</title>
</head>
<body>

<div style="align:center">
    <h3 align="center"><b>{{$report->userName}}</b></h3>

    <h4 align="center"><b>{{$report->reportTitle}}</b></h4>

    <h4 align="center">
        {{$report->formattedDuration}}
    </h4>
</div>

<div style="align:center;margin-top: 60px;">
    <table class="items" width="80%" style="font-size: 9pt; border-collapse: collapse;   " align="center" border="1"
           cellpadding="8">
        <thead>
        <tr>
            <th align="center"><strong>Payment Mode</strong></th>
            <th align="center"><strong>Amount({{$report->currency}})</strong></th>
        </tr>
        </thead>
        <tbody>
        @foreach($report->paymentModeSums as $key => $paymentModeSum)
            <tr>
                <td align="center">{{$key}}</td>
                <td align="center">{{number_format($paymentModeSum,2)}}</td>
            </tr>
        @endforeach
        <tr>
            <td align="center"><strong>Total Sales for the Period</strong></td>
            <td align="center"><strong>{{number_format($report->totalAmount,2)}}</strong></td>
        </tr>
        </tbody>
    </table>
</div>

</body>
</html>
