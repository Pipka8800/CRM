<?php
session_start();
require_once '../DB.php';
require_once '../auth/AuthCheck.php';

AuthCheck('../../clients.php', '../../login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Получаем ID пользователя из базы данных по токену
    $token = $_SESSION['token'] ?? null;
    
    if (!$token) {
        header('Location: ../../clients.php?ticket_status=error');
        exit;
    }
    
    try {
        // Сначала получаем ID пользователя по токену
        $stmt = $DB->prepare("SELECT id FROM users WHERE token = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            header('Location: ../../clients.php?ticket_status=error');
            exit;
        }
        
        // Теперь создаем тикет с полученным ID пользователя
        $stmt = $DB->prepare("INSERT INTO tickets (type, message, clients, admin, created_at) 
                             VALUES (?, ?, ?, NULL, NOW())");
        $stmt->execute([$type, $message, $user['id']]);
        
        header('Location: ../../clients.php?ticket_status=success');
        exit;
    } catch (PDOException $e) {
        header('Location: ../../clients.php?ticket_status=error');
        exit;
    }
} else {
    header('Location: ../../clients.php');
    exit;
} 