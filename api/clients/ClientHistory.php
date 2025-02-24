<?php
require_once '../DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $clientID = $_GET['id'];
    $dateFROM = $_GET['from'];
    $dateTO = $_GET['to'];

    // Преобразуем строки в объекты DateTime для корректного сравнения
    $dateFrom = new DateTime($dateFROM);
    $dateTo = new DateTime($dateTO);

    // Проверяем корректность дат
    if ($dateFrom > $dateTo) {
        session_start();
        $_SESSION['clients_error'] = 'ДАТУ НОРМАЛЬНО ПИШИ!';
        header('Location: ../../clients.php');
        exit;
    }

    echo 'id: ' .  $clientID;
    echo 'from: ' . $dateFROM;
    echo 'to: ' . $dateTO;

    //проверить корректность дат (from <= to)
    //если from <= to, редирект на клиентов
    //и вывод ошибки в модальном окне (clients_error)

    //получить все данные для вывода истории заказов

    //сгенерировать пдф
}
?>