
<?php  
  
//тестовый токен
$_SESSION['token'] = '123456';  
  
function AuthCheck($successPath = '', $errorPath = '') { 
    require_once 'DB.php';  
     
    // проверка наличия ключа token в $_SESSION
    if (!isset($_SESSION['token'])&& $errorPath) {  
        header("Location: $errorPath");  
        return;  
    } 

    //токен текущего пользователя  
    $token = $_SESSION['token'];  
    $adminID = $DB->query(  
        "SELECT id FROM users WHERE token = '$token'  
        ")->fetchAll();    
     
    if (empty($adminID) && $errorPath) { 
        header("Location: $errorPath");
    } 
    if (!empty($adminID) && $successPath) { 
        header("Location: $successPath");
    } 
}  
 
  
?>