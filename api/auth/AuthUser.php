<?php session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = isset($_POST['login']) ? $_POST['login'] : '';

    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $_SESSION['login-errors'] = [];

    if(!$login){
        $_SESSION['login-errors']['login'] = 'Field is required';
        header('Location: ../../login.php');
        exit;
    }

    if(!$password){
        $_SESSION['login-errors']['password'] = 'Field is required';
        header('Location: ../../login.php');
        exit;
    }

    function clearDate($field) {
        $field = trim($field);
        $field = strip_tags($field);
        $field = htmlspecialchars($field, ENT_QUOTES);
        return $field;
    }
    echo $login;
    $login = clearDate($login);
    echo $login;
    $password = clearDate($password);
} else {
    echo json_encode([
    'error' => 'Nevernii zapros']);
}
?>