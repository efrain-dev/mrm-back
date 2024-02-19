<!DOCTYPE html>
<html>
<head>
    <title>MRM</title>
</head>
<body>

<h3>{{ $title }}</h3>
<p>
    {{$name}}
    <br>
    {{$body}}
    <br>
    <ul>
    @foreach($link as $item)
        <li><a>{{$item->name}}</a> </li>
    @endforeach
    </ul>

Best regards,<br/>
</p>
</body>
</html>
