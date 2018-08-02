<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <form action="/placements/jcap" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="file" name="placement">
        <input type="submit" value="Get CSV">
    </form>
</body>
</html>