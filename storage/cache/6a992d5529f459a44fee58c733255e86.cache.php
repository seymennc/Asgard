<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Başlıkkksss</title>
</head>
<body>


    bu bölüm index içeriği

    <a href="<?=route('about')?>">deneme</a>

    <form action="<?=route('post')?>" method="POST">
        <input type="hidden" name="_csrf_token" value="10f463526ff93c138cfc3db9f9c1f47309a806bf62e29cf5dbabafbaca51f9c6">
        <input type="text" name="name">
        <button type="submit">Gönder</button>
    </form>


</body>
</html>