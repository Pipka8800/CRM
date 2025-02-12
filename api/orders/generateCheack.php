<?php

require_once '../../vendor/autoload.php';
require_once '../DB.php';

// Явное подключение файлов Dompdf
require_once '../../vendor/dompdf/dompdf/src/Options.php';
require_once '../../vendor/dompdf/dompdf/src/Dompdf.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Проверка наличия ID заказа
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $orderID = $_GET['id'];

$data = [
    "orderID" => '',
    "orderDate" => '',
    "adminName" => '',
    "clientName" => '',
    "orderItems" => '',
    "orderTotal" => ''
    ];
    
    echo json_encode($data);

// Инициализация с настройками
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml('hello'); // Здесь нужно будет добавить HTML-шаблон чека
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('GavnoCheck' . $orderID . '.pdf');
}


?>