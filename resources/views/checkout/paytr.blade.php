<!DOCTYPE html>
<html>
<head>
    <title>PayTR Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div style="text-align: center; padding: 50px;">
        <h2>Redirecting to PayTR...</h2>
        <p>Please wait while we redirect you to the payment page.</p>
    </div>

    <form id="paytr_form" action="https://www.paytr.com/odeme/guvenli/{{ $paymentData['merchant_id'] }}" method="post">
        @foreach($paymentData as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>

    <script>
        document.getElementById('paytr_form').submit();
    </script>
</body>
</html>
