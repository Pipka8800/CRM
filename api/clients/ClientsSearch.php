<?php

function ClientsSearch($params, $DB) {
    $search = isset($params['search']) ? $params['search'] : '';
    $sort = isset($params['sort']) ? $params['sort'] : '0';

    $search = strtolower($search);

    $orderBy = '';
    if ($sort == '1') {
        $orderBy = ' ORDER BY name ASC';
    } elseif ($sort == '2') {
        $orderBy = ' ORDER BY name DESC';
    }

    $clients = $DB->query(
        "SELECT * FROM clients WHERE LOWER(name) LIKE '%$search%'$orderBy"
    )->fetchAll();

    return $clients;
}

?>
