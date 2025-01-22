<?php session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../api/DB.php';

    $login = isset($_POST['login']) ? $_POST['login'] : '';

    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $_SESSION['login-errors'] = [];

    if(!$login){
        $_SESSION['login-errors']['login'] = 'Field is required';
    }

    if(!$password){
        $_SESSION['login-errors']['password'] = 'Field is required';
    }

    if(!$login || !$password) {
        header('Location: ../../login.php');
        exit;
    }

    function clearDate($field) {
        $field = trim($field);
        $field = strip_tags($field);
        $field = htmlspecialchars($field, ENT_QUOTES);
        return $field;
    }
    $login = clearDate($login);
    $password = clearDate($password);

        //проверка логина  
        $userID = $DB->query(  
            "SELECT id FROM users WHERE login = '$login'  
            ")->fetchAll();

        if(empty($userID)){
            $_SESSION['login-errors']['login'] = 'User not found';
            header('Location: ../../login.php');
            exit;
        }

        //проверка пароля  
        $userID = $DB->query(  
            "SELECT id FROM users WHERE login = '$login' AND password = '$password'  
            ")->fetchAll();

        if(empty($userID)){
            $_SESSION['login-errors']['password'] = 'Wrong password';
            header('Location: ../../login.php');
            exit;
        }

    $uniquerString = time();
    $token = base64_encode(
        "login=$login&password=$password&unique=$uniquerString"
    );

    //Записать в сессию в поле token 
    $_SESSION['token'] = $token; 
    
    //Записать в БД в поле token 
    try { 
        $updateToken = $DB->prepare("UPDATE users SET token = ? WHERE login = ? AND password = ?"); 
        $updateToken->execute([$token, $login, $password]); 
            
        // Если успешно, делаем редирект на страницу клиентов 
        header('Location: ../../clients.php'); 
        exit; 
        
    } catch(PDOException $e) { 
        $_SESSION['login-errors']['token'] = 'Ошибка сохранения сессии'; 
        header('Location: ../../login.php'); 
        exit; 
    }

} else {
    echo json_encode([
    'error' => 'Nevernii zapros']);
}
?>