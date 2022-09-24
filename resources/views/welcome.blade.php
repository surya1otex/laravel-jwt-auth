<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>
<form method="post" action="{{ route('fileuploads') }}" enctype="multipart/form-data">
    <input type="file" name="images[]" multiple>
    <br>
    <input type="file" name="images[]" multiple>
    <br>
    <input type="file" name="images[]" multiple>
    <input type="submit" name="submit">
</form>
</body>
</html>