<?php session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = $_POST;
    $fields = ['client', 'products'];
    $errors = [];

    if ($formData['client'] === 'new') {
        $fields[] = 'email';
    }
    
    $_SESSION['orders_error'] = '';

    foreach ($fields as $field) {
        if (!isset($formData[$field]) || empty($_POST[$field])) {
            $errors[$field][] = 'Field is required';
        }
    }

    if (!empty($errors)) {
        $errorList = '<ul>';
        foreach ($errors as $field => $messages) {
            foreach ($messages as $message) {
                $errorList .= '<li>' . ucfirst($field) . ': ' . $message . '</li>';
            }
        }
        $errorList .= '</ul>';
        
        $_SESSION['orders_error'] = $errorList;
        header('Location: ../../orders.php');
        exit;
    }

    require_once '../DB.php';
    //ид выбранных товаров
    $products = $formData['products'];
    //все товары из бд
    $allProducts = $DB->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
    //сумма выбранных товаров
    $total = 0;

    foreach ($allProducts as $product) {
        if (in_array($product['id'], $products)) {
            $total += $product['price'];
        }
    }

    $clientID = $formData['client'] === 'new' ? time() : $formData['client'];

    if ($formData['client'] === 'new') {
        $stmt = $DB->prepare("INSERT INTO clients (id, name, email, phone, birthday) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $clientID,
            $formData['name'] ?? 'не указано',
            $formData['email'],
            'не указано', // значение по умолчанию для phone
            'не указано'  // значение по умолчанию для birthday
        ]);
    }

    // 1. создание заказа с полями
    $orders = [
        'id' => time(),
        'client_id' => $clientID,
        'total' => $total,
    ];

    $stmt = $DB->prepare(
        "INSERT INTO orders (id, client_id, total) VALUES (?, ?, ?)"
    );
    $stmt->execute([
        $orders['id'],
        $orders['client_id'],
        $orders['total'],
    ]);

    // 2. Добавление элементов заказа в order_items
    $stmt = $DB->prepare(
        "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)"
    );

    foreach ($products as $product_id) {
        // Находим информацию о продукте
        $productInfo = array_filter($allProducts, function($p) use ($product_id) {
            return $p['id'] == $product_id;
        });
        $productInfo = reset($productInfo);

        // Добавляем запись в order_items
        $stmt->execute([
            $orders['id'],      // order_id
            $product_id,        // product_id
            1,                  // quantity (по умолчанию 1)
            $productInfo['price'] // price
        ]);
    }

    // Редирект на страницу заказов после успешного создания
    header('Location: ../../orders.php');
    exit;

} else {
    echo json_encode(['error' => 'Invalid method']);
    exit;
}

?>