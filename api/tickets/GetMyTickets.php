<?php
session_start();
require_once '../DB.php';
require_once '../auth/AuthCheck.php';

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['token'])) {
        throw new Exception('Пользователь не авторизован');
    }

    // Получаем ID пользователя по токену
    $stmt = $DB->prepare("SELECT id FROM users WHERE token = ?");
    $stmt->execute([$_SESSION['token']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('Пользователь не найден');
    }

    // Получаем все тикеты пользователя
    $stmt = $DB->prepare("
        SELECT id, type, message, status, created_at 
        FROM tickets 
        WHERE clients = ? 
        ORDER BY created_at DESC
    ");
    $stmt->execute([$user['id']]);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tickets);

} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
} 