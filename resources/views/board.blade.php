<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="{{route('storeImage')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div>
        <input type="file" name="file" accept="image/*">
        @error('file')
            <small>{{$message}}</small>
        @enderror
    </div>
    <button type="submit">Load Image</button>
</form>
</body>
</html>
