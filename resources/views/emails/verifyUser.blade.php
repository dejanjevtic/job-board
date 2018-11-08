<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>

<body>
<h2>Your Job is {{$title}}</h2>
 {{$description}} 
<br/>
<br/>
<a href="{{url('user/verify', $token)}}">Approve Job here</a>
</body>

</html>

