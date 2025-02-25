<?php
require_once '../DB.php';
require_once '../../vendor/autoload.php';
require_once '../../vendor/dompdf/dompdf/src/Options.php';
require_once '../../vendor/dompdf/dompdf/src/Dompdf.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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

    // Получаем данные о клиенте
    $clientQuery = "SELECT name FROM clients WHERE id = ?";
    $stmt = $DB->prepare($clientQuery);
    $stmt->execute([$clientID]);
    $clientData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Получаем историю заказов клиента с учетом дат
    $ordersQuery = "SELECT o.id, o.total, oi.quantity, oi.price, p.name as product_name
                    FROM orders o
                    JOIN order_items oi ON o.id = oi.order_id
                    JOIN products p ON oi.product_id = p.id
                    WHERE o.client_id = ? AND o.order_date BETWEEN ? AND ?
                    ORDER BY o.order_date DESC";

    $stmt = $DB->prepare($ordersQuery);
    $stmt->execute([$clientID, $dateFROM, $dateTO]);
    $orderHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

    function GetClientName($clientId, $DB) {
        $stmt = $DB->prepare("SELECT fullname FROM clients WHERE id = ?");
        $stmt->execute([$clientId]);
        return $stmt->fetchColumn();
    }

    function GetClientOrders($clientId, $DB) {
    $query = "SELECT 
        o.id as order_id,
        p.name as product_name,
        o.quantity,
        o.price,
        o.created_at
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.client_id = ?
    ORDER BY o.created_at DESC";
    
    $stmt = $DB->prepare($query);
    $stmt->execute([$clientId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Формируем HTML для PDF
    $html = '
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            body { font-family: DejaVu Sans, sans-serif; }
            .header { text-align: center; margin-bottom: 20px; }
            .client-info { margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        </style>
    </head>
    <body>
        <div class="header">
            <h2>История заказов клиента</h2>
            <p>Период: ' . $dateFROM . ' - ' . $dateTO . '</p>
        </div>
        <div class="client-info">
            <p>Клиент: ' . $clientData['name'] . '</p>
        </div>
        <table>
            <tr>
                <th>ID заказа</th>
                <th>Товар</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Сумма</th>
            </tr>';

    foreach ($orderHistory as $item) {
        $html .= '<tr>
            <td>' . $item['id'] . '</td>
            <td>' . $item['product_name'] . '</td>
            <td>' . $item['quantity'] . '</td>
            <td>' . $item['price'] . ' руб.</td>
            <td>' . ($item['quantity'] * $item['price']) . ' руб.</td>
        </tr>';
    }

    $html .= '</table></body></html>';

    // Генерируем PDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Отправляем PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="История_заказов_' . $clientData['name'] . '.pdf"');
    echo $dompdf->output();
    exit();
}
?>