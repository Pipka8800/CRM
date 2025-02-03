<?php session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = $_POST;
    $fields = ['client', 'products'];
    $errors = [];
    
    $_SESSION['orders_error'] = '';

    foreach ($fields as $field) {
        if (!isset($formData[$field]) || empty($_POST[$field])) {
            $errors[$field][] = 'Field is required';
        }
    }

    if (!empty($errors)) {
        $errorList = '<ul>';
        foreach ($errors as $field => $messages) {
            foreach ($messages as $message) {
                $errorList .= '<li>' . ucfirst($field) . ': ' . $message . '</li>';
            }
        }
        $errorList .= '</ul>';
        
        $_SESSION['orders_error'] = $errorList;
        header('Location: ../../orders.php');
        exit;
    }

    } else {
        echo json_encode(['error' => 'Invalid method']);
        exit;
    }

?>