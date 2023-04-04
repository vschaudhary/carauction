<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

@component('mail::message')
# Reset Password

Your six-digit PIN is 

     <h4>{{$token}}</h4>
<p>Please do not share your One Time Pin With Anyone. You made a request to reset your password. Please discard if this wasn't you.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent


<!-- <h2> Reset Password  </h2>

<p> Your six-digit PIN is </p> 
  <h4> token</h4>
<p>Please do not share your One Time Pin With Anyone. You made a request to reset your password. Please discard if this wasn't you.</p>

Thanks,<br> -->
    
</body>
</html>