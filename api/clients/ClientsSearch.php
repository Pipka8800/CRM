<?php

function ClientsSearch($params, $DB) {
    $search_name = isset($params['search_name']) ? $params['search_name'] : 'name';
    $search = isset($params['search']) ? $params['search'] : '';
    $sort = isset($params['sort']) ? $params['sort'] : '0';

    $search = strtolower($search);

    $orderBy = '';
    if ($sort == '1') {
        $orderBy = " ORDER BY $search_name ASC";
    } elseif ($sort == '2') {
        $orderBy = " ORDER BY $search_name DESC";
    }

    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $maxClients = 5;
    $offset = ($currentPage - 1) * $maxClients;

    $clients = $DB->query(
        "SELECT * FROM clients WHERE LOWER($search_name) LIKE '%$search%'$orderBy LIMIT $maxClients OFFSET $offset"
    )->fetchAll();

    return $clients;
}

?>