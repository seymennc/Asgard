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
        <input type="text" name="name">
        <button type="submit">Gönder</button>
    </form>


</body>
</html>