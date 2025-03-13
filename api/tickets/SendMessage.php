<?php
require_once '../DB.php';
session_start();

header('Content-Type: application/json');

try {
    // Добавим отладочную информацию
    if (!isset($_SESSION)) {
        throw new Exception('Сессия не запущена');
    }

    // Выведем содержимое сессии для отладки
    error_log('Session contents: ' . print_r($_SESSION, true));

    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Пользователь не авторизован (user_id отсутствует в сессии)');
    }

    if (!isset($_POST['ticket_id']) || !isset($_POST['message'])) {
        throw new Exception('Отсутствуют необходимые данные');
    }

    $ticketId = $_POST['ticket_id'];
    $message = trim($_POST['message']);
    $userId = $_SESSION['user_id'];

    if (empty($message)) {
        throw new Exception('Сообщение не может быть пустым');
    }

    // Добавим проверку существования пользователя в базе
    $userCheck = $DB->prepare("SELECT id FROM users WHERE id = ?");
    $userCheck->execute([$userId]);
    if (!$userCheck->fetch()) {
        throw new Exception('Пользователь не найден в базе данных');
    }

    $query = "INSERT INTO ticket_messages (ticket_id, user_id, message, created_at) 
              VALUES (?, ?, ?, NOW())";

    $stmt = $DB->prepare($query);
    $success = $stmt->execute([$ticketId, $userId, $message]);

    if (!$success) {
        throw new Exception('Ошибка при сохранении сообщения: ' . implode(', ', $stmt->errorInfo()));
    }

    echo json_encode([
        'success' => true,
        'message' => 'Сообщение успешно отправлено',
        'debug' => [
            'user_id' => $userId,
            'session_id' => session_id()
        ]
    ]);

} catch (Exception $e) {
    error_log('Chat error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'session_status' => session_status(),
            'session_id' => session_id(),
            'session_data' => isset($_SESSION) ? $_SESSION : 'No session'
        ]
    ]);
}
?>