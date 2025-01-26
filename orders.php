<?php session_start();

if (isset($_GET['do']) && $_GET['do'] === 'logout') {
    require_once 'api/auth/LogoutUser.php';
    require_once 'api/DB.php';

    LogoutUser('login.php', $DB, $_SESSION['token']);

    exit;
}

require_once 'api/auth/AuthCheck.php';

AuthCheck('', 'login.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/settings.css">
    <link rel="stylesheet" href="styles/pages/clients.css">
    <link rel="stylesheet" href="styles/modules/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/modules/micromodal.css">
    <title>CRM | Заказы</title>
</head>
<body>
    <header class="header">
        <div class="container">
            <p class="header__admin">
                <?php 
                    require 'api/DB.php';
                    require_once 'api/clients/AdminName.php';

                    echo AdminName($_SESSION['token'], $DB);
                ?>
            </p>
            <ul class="header__links">
                <li><a href="clients.php">Клиенты</a></li>
                <li><a href="product.php">Товары</a></li>
                <li><a href="orders.php">Заказы</a></li>
            </ul>
            <a href="?do=logout" class="header__logout">Выйти</a>
        </div>
    </header>
    <main class="main">
        <section class="main__filters">
            <div class="container">
                <form action="" class="main__form">
                    <input class="main__input" type="text" id="search" name="search" placeholder="Поиск...">
                    <select class="main__select" name="filter" id="filter">
                        <option value="client">По клиенту</option>
                        <option value="id">По ID</option>
                        <option value="date">По дате</option>
                        <option value="price">По сумме</option>
                    </select>
                    <select class="main__select" name="sort" id="sort">
                        <option value="0">По возрастанию</option>
                        <option value="1">По убыванию</option>
                    </select>
                </form>
            </div>
        </section>
        <section class="main__clients">
            <div class="container">
                <h2 class="main__clients__title">Список заказов</h2>
                <button class="main__clients__add" onclick="MicroModal.show('add-modal')"><i class="fa fa-plus-circle"></i></button>
                <table>
                    <thead>
                        <th>ИД</th>
                        <th>ФИО клиента</th>
                        <th>Дата заказа</th>
                        <th>Сумма</th>
                        <th>Состав заказа</th>
                        <th>Действия</th>
                    </thead>
                    <tbody>
                        <?php
                            require 'api/DB.php';
                            require_once 'api/helpers/convertDate.php';
                            require_once('api/helpers/OutputOrders.php');

                            $orders = $DB->query(
                                "SELECT * FROM clients
                            ")->fetchAll();
                        
                            OutputOrders($orders);
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <div class="modal micromodal-slide" id="add-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
          <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="modal__header">
              <h2 class="modal__title" id="modal-1-title">
                Добавить клиента
              </h2>
              <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
            </header>
            <main class="modal__content" id="modal-1-content">
                <form class="modal__form">
                    <div class="modal__form-group">
                        <label for="fullname">ФИО</label>
                        <input type="text" id="fullname" name="fullname" required>
                    </div>
                    <div class="modal__form-group">
                        <label for="email">Почта</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="modal__form-group">
                        <label for="phone">Телефон</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="modal__form-group">
                        <label for="birthday">День рождения</label>
                        <input type="date" id="birthday" name="birthday" required>
                    </div>
                    <div class="modal__form-actions">
                        <button type="submit" class="modal__btn modal__btn-primary">Создать</button>
                        <button type="button" class="modal__btn modal__btn-secondary" data-micromodal-close>Отменить</button>
                    </div>
                </form>
            </main>
          </div>
        </div>
      </div>
      <div class="modal micromodal-slide" id="delete-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true">
                <header class="modal__header">
                    <h2 class="modal__title">Удалить заказ?</h2>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content">
                    <p>Вы уверены, что хотите удалить заказ?</p>
                    <button class="modal__btn modal__btn-danger">Удалить</button>
                    <button class="modal__btn" data-micromodal-close>Отменить</button>
                </main>
            </div>
        </div>
    </div>
    <div class="modal micromodal-slide" id="edit-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
          <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="modal__header">
              <h2 class="modal__title" id="modal-1-title">
                Редактировать клиента
              </h2>
              <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
            </header>
            <main class="modal__content" id="modal-1-content">
                <form class="modal__form">
                    <div class="modal__form-group">
                        <label for="fullname">ФИО</label>
                        <input type="text" id="fullname" name="fullname" required>
                    </div>
                    <div class="modal__form-group">
                        <label for="email">Почта</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="modal__form-group">
                        <label for="phone">Телефон</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="modal__form-actions">
                        <button type="submit" class="modal__btn">Сохранить</button>
                        <button type="button" class="modal__btn" data-micromodal-close>Отменить</button>
                    </div>
                </form>
            </main>
          </div>
        </div>
      </div>
      <div class="modal micromodal-slide" id="history-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">
                        История покупок
                    </h2>
                    <small>Фамилия Имя Отчество</small>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content" id="modal-1-content">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>ID заказа</th>
                                <th>Товар</th>
                                <th>Количество</th>
                                <th>Цена</th>
                                <th>Дата</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Товар 1</td>
                                <td>2</td>
                                <td>1000₽</td>
                                <td>12.01.2024</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Товар 2</td>
                                <td>1</td>
                                <td>500₽</td>
                                <td>15.01.2024</td>
                            </tr>
                        </tbody>
                    </table>
                </main>
            </div>
        </div>
    </div>
    <div class="modal micromodal-slide" id="details-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true">
                <header class="modal__header">
                    <h2 class="modal__title">Информация о заказе #<span id="order-id"></span></h2>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content">
                    <div class="order-details">
                        <p><strong>Клиент:</strong> <span id="client-name"></span></p>
                        <p><strong>Дата заказа:</strong> <span id="order-date"></span></p>
                        <p><strong>Общая сумма:</strong> <span id="order-total"></span>₽</p>
                        
                        <h3>Состав заказа:</h3>
                        <table class="details-table">
                            <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th>Количество</th>
                                    <th>Цена</th>
                                    <th>Сумма</th>
                                </tr>
                            </thead>
                            <tbody id="order-items">
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>
        </div>
    </div>
    <script defer src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
    <script defer src="scripts/initClientsModal.js"></script>
</body>
</html>