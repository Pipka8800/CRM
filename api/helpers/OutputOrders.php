<?php
function OutputOrders($orders) {
    foreach ($orders as $order) {
        $fullname = $order['fullname'] ?? 'Неизвестно';
        $order_date = $order['order_date'] ?? 'Неизвестно';
        $total_price = $order['total_price'] ?? '0';
        $order_items = $order['order_items'] ?? 'Нет данных';

        echo "<tr>";
        echo "<td>{$order['id']}</td>";
        echo "<td>{$fullname}</td>";
        echo "<td>" . convertDate($order['order_date']) . "</td>";
        echo "<td>{$total_price}</td>";
        echo "<td>{$order_items}</td>";
        echo "<td><button>Действия</button></td>";
        echo "</tr>";
    }
}
?>