<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>

<body>
<h1>{{$title}}</h1>
 <p>{{$description}}</p>
 <br>
 <br>
<a href="{{url('user/verify', $token)}}">Approve Job here</a>
</body>

</html>

