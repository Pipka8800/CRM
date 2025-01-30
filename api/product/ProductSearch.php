<?php

function ProductSearch($params, $DB) {
    $search = isset($params['search']) ? $params['search'] : '';
    //по умолчанию и убыванию
    $sort = isset($params['sort']) ? $params['sort'] : '0';
    //цена и количество
    $serach_name = isset($params['serach_name']) ? $params['serach_name'] : '0';

    $search = strtolower($search);

    $orderBy = '';
    if ($sort == '1') {
        $orderBy = "ORDER BY $serach_name ASC";
    } elseif ($sort == '2') {
        $orderBy = "ORDER BY $serach_name DESC";
    }

    $product = $DB->query(
        "SELECT * FROM products WHERE LOWER(name) LIKE '%$search%'$orderBy"
    )->fetchAll();

    return $product;
}

?>
