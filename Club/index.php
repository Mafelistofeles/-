<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Менеджер клуба</title>
    <link rel='shortcut icon' href='favicon.ico' type='image/x-icon'/>
    <link href="css/pace-theme-corner-indicator.css" rel="stylesheet">
    <script src="js/pace.min.js"></script>
    <script>pace.start();</script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/enterprise.js?render=6LcritgpAAAAAMQaYpmYmnnUZ77x-nuh4FPZsXpt"></script>
    <script>
        function onClick(e) {
            e.preventDefault();
            grecaptcha.enterprise.ready(async () => {
                const token = await grecaptcha.enterprise.execute('6LcritgpAAAAAMQaYpmYmnnUZ77x-nuh4FPZsXpt', {action: 'LOGIN'});
                document.getElementById("captcha").value = token; // Помещаем токен каптчи в скрытое поле для отправки на сервер
                document.getElementById("myForm").submit(); // Отправляем форму с токеном каптчи
            });
        }
    </script>
</head>
<?php
session_start();
require_once('funs.php');

if (isset($_SESSION["username"])) {
    header("location:home.php");
    exit();
}
?>


<body style="overflow: hidden;background-color: #c91818;">

<div class="row">
    <h1 class="text-center" style="padding-top:25px;color: #000;font-weight: bold;font-size: 3.0em;">Блог <small>(Курсовая)</small></h1><br>
    <div class="error"><?php login(); ?></div>
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Вход в блог</div>
            <div class="panel-body">
                <form id="myForm" class="" method="post" action="">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control input-lg" placeholder="Логин" name="username" type="text"
                                   autofocus="" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control input-lg" placeholder="Пароль" name="password" type="password"
                                   required>
                        </div>
                        <!-- Капча -->
                        <div class="g-recaptcha" data-sitekey="6LcritgpAAAAAMQaYpmYmnnUZ77x-nuh4FPZsXpt"></div>
                        <input type="hidden" id="captcha" name="captcha"> <!-- Скрытое поле для хранения токена каптчи -->
                        <div class="checkbox">
                            <label>
                                <input name="remember" type="checkbox" value="Remember Me">Запомнить меня
                            </label>
                        </div>
                        <button class="btn btn-primary btn-lg" name="submit" type="submit" id="login">Оставь надежду всяк сюда входящий!</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div><!-- /.col-->
</div><!-- /.row -->	

</body>
</html>
