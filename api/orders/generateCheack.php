<?php

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    require_once '../DB.php';
    $orderID = $_GET['id'];
    echo $orderID;
}


?>