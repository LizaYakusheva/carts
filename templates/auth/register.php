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
    <h1>Зарегестрироваться</h1>
    <form action="/register" method="post">
        <input type="text" name="login" id="login" placeholder="+7(000)-000-00-00" required>
        <input type="text" name="password" id="password" placeholder="Пароль" required>
        <input type="submit" value="Зарегестрироваться">
    </form>
    <a href="/login">Войти</a>
</body>
</html>
