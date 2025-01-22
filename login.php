<?php session_start();

require_once 'api/auth/AuthCheck.php';

AuthCheck('clients.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/settings.css">
    <link rel="stylesheet" href="styles/pages/login.css">
    <title>CRM | Авторизация</title>
</head>
<body>
    <div class="container">
        <form action="api/auth/AuthUser.php" method="post" class="login-form">
            <h1>Вход в систему</h1>
            <div class="form-group">
                <input type="text" id="login" name="login" placeholder="Логин">
                <p style="color: red;">
                    <?php
                    if (isset($_SESSION['login-errors'])) {
                        $errors = $_SESSION['login-errors'];

                        echo isset($errors['login']) ? $errors['login'] : '';
                    }
                    ?>
                </p>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Пароль">
                <p style="color: red;">
                    <?php
                    if (isset($_SESSION['login-errors'])) {
                        $errors = $_SESSION['login-errors'];

                        echo isset($errors['password']) ? $errors['password'] : '';
                    }
                    ?>
                </p>
            </div>
            <button type="submit" class="login-button">Войти</button>
        </form>
    </div>
    
</body>
</html>