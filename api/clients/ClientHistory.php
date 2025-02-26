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

    // Проверяем, является ли это одним и тем же днем
    $isOneDay = $dateFrom->format('Y-m-d') === $dateTo->format('Y-m-d');

    // Проверяем корректность дат
    if ($dateFrom > $dateTo) {
        session_start();
        $_SESSION['clients_error'] = 'ДАТУ НОРМАЛЬНО ПИШИ!';
        header('Location: ../../clients.php');
        exit;
    }

    $history = [
        'user' => '',
        'orders' => []
    ];

    // Получаем информацию о клиенте
    $clientQuery = "SELECT name FROM clients WHERE id = ?";
    $clientStmt = $DB->prepare($clientQuery);
    $clientStmt->execute([$clientID]);
    $client = $clientStmt->fetch(PDO::FETCH_ASSOC);

    if ($client) {
        $history['user'] = $client['name']; // Сохраняем ФИО пользователя
    }

    // Добавляем запрос для получения заказов по ID клиента
    $query = "SELECT * FROM orders WHERE client_id = ?";
    if (!empty($dateFROM) && !empty($dateTO)) {
        if ($isOneDay) {
            $query .= " AND DATE(order_date) = DATE(?)";
        } else {
            $query .= " AND order_date BETWEEN ? AND ?";
        }
    }
    $stmt = $DB->prepare($query);
    
    if (!empty($dateFROM) && !empty($dateTO)) {
        if ($isOneDay) {
            $stmt->execute([$clientID, $dateFROM]);
        } else {
            $stmt->execute([$clientID, $dateFROM, $dateTO]);
        }
    } else {
        $stmt->execute([$clientID]);
    }
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Проверяем, есть ли заказы у клиента
    if (empty($orders)) {
        // HTML для клиента без заказов
        $html = '
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <style>
                body { font-family: DejaVu Sans, sans-serif; }
                .header { text-align: center; margin-bottom: 20px; }
                .message { text-align: center; font-size: 18px; margin-top: 50px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>История заказов клиента</h2>
                <p>Период: ' . ((!empty($dateFROM) && !empty($dateTO)) ? $dateFROM . ' - ' . $dateTO : 'Все время') . '</p>
            </div>
            <div class="client-info">
                <p>Клиент: ' . $history['user'] . '</p>
            </div>
            <div class="message">
                <p>Данный клиент ещё ничего не заказывал</p>
            </div>
        </body>
        </html>';
    } else {
        foreach ($orders as $order) {
            // Запрос для получения элементов заказа с названиями продуктов
            $itemsQuery = "SELECT oi.*, p.name as product_name 
                         FROM order_items oi 
                         JOIN products p ON oi.product_id = p.id 
                         WHERE oi.order_id = ?";
            $itemsStmt = $DB->prepare($itemsQuery);
            $itemsStmt->execute([$order['id']]);
            $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

            $history['orders'][] = [
                "id" => $order['id'],
                "date" => $order['order_date'],
                "total" => $order['total'],
                "status" => $order['status'],
                "items" => $items
            ];
        }
        
        // Добавляем код для генерации чека
        $data = [
            "clientID" => $clientID,
            "orderDate" => (!empty($dateFROM) && !empty($dateTO)) ? 
                ($isOneDay ? date('Y-m-d', strtotime($dateFROM)) : $dateFROM . ' - ' . $dateTO) : 
                'Все время',
            "orders" => $history['orders']
        ];

        // После формирования $data, создаем HTML для PDF
        $html = '
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <style>
                body { font-family: DejaVu Sans, sans-serif; }
                .header { text-align: center; margin-bottom: 20px; }
                .client-info { margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>История заказов клиента</h2>
                <p>Период: ' . $data['orderDate'] . '</p>
            </div>
            <div class="client-info">
                <p>Клиент: ' . $history['user'] . '</p>
            </div>';

        foreach ($data['orders'] as $order) {
            $html .= '
            <h3>Заказ №' . $order['id'] . ' от ' . $order['date'] . '</h3>
            <table>
                <tr>
                    <th>Наименование</th>
                    <th>Количество</th>
                    <th>Статус</th>
                    <th>Сумма</th>
                </tr>';
            
            foreach ($order['items'] as $item) {
                $html .= '<tr>
                    <td>' . $item['product_name'] . '</td>
                    <td>' . $item['quantity'] . '</td>
                    <td>' . ($order['status'] == 1 ? 'Активен' : 'Архив') . '</td>
                    <td>' . $item['price'] . ' руб.</td>
                </tr>';
            }
            
            $html .= '
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Итого:</strong></td>
                    <td><strong>' . $order['total'] . ' руб.</strong></td>
                </tr>
            </table>';
        }
        
        $html .= '</body></html>';
    }

    // Инициализация Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    // Отправляем PDF в браузер
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="История_Клиента_' . $clientID . '.pdf"');
    echo $dompdf->output();
    exit();
}
?>