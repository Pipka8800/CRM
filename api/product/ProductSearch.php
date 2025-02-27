<?php

function ProductSearch($params, $DB) {
    $search = isset($params['search']) ? $params['search'] : '';
    //по умолчанию и убыванию
    $sort = isset($params['sort']) ? $params['sort'] : '0';
    //цена и количество
    $search_name = isset($params['search_name']) ? $params['search_name'] : '0';

    $search = strtolower($search);

    $orderBy = '';
    if ($sort == '1') {
        $orderBy = "ORDER BY $search_name ASC";
    } elseif ($sort == '2') {
        $orderBy = "ORDER BY $search_name DESC";
    }

    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $maxProducts = 5;
    $offset = ($currentPage - 1) * $maxProducts;

    $product = $DB->query(
        "SELECT * FROM products WHERE LOWER(name) LIKE '%$search%'$orderBy LIMIT $maxProducts OFFSET $offset"
    )->fetchAll();

    return $product;
}

?>
