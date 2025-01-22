<?php session_start();

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
    <title>CRM | Клиенты</title>
</head>
<body>
    <header class="header">
        <div class="container">
            <p class="header__admin">ФИО</p>
            <ul class="header__links">
                <li><a href="#">Клиенты</a></li>
                <li><a href="#">Товары</a></li>
                <li><a href="#">Заказы</a></li>
            </ul>
            <a href="#" class="header__logout">Выйти</a>
        </div>
    </header>
    <main class="main">
        <section class="main__filters">
            <div class="container">
                <form action="" class="main__form">
                    <label class="main__label" for="search">Поиск по имени</label>
                    <input class="main__input" type="text" id="search" name="search" placeholder="Александр">
                    <select class="main__select" name="sort" id="sort">
                        <option value="0">По возрастанию</option>
                        <option value="1">По убыванию</option>
                    </select>
                </form>
            </div>
        </section>
        <section class="main__clients">
            <div class="container">
                <h2 class="main__clients__title">Список клиентов</h2>
                <button onclick="MicroModal.show('add-modal')" class="main__clients__add"><i class="fa fa-plus-circle"></i></button>
                <table>
                    <thead>
                        <th>ИД</th>
                        <th>ФИО</th>
                        <th>Почта</th>
                        <th>Телефон</th>
                        <th>День рождения</th>
                        <th>Дата создания</th>
                        <th>История заказов</th>
                        <th>Редактировать</th>
                        <th>Удалить</th>
                    </thead>
                <tbody>
                    <tr>
                        <td>0</td>
                        <td>Александр</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history" onclick="MicroModal.show('history-modal')"></i></td>
                        <td><i class="fa fa-pencil" onclick="MicroModal.show('edit-modal')"></i></td>
                        <td><i class="fa fa-trash" onclick="MicroModal.show('delete-modal')"></i></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Семён</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Казёл</td>
                        <td>alex@gmail.com</td>
                        <td>89123456789</td>
                        <td>12.01.2000</td>
                        <td>12.01.2025</td>
                        <td><i class="fa fa-history"></i></td>
                        <td><i class="fa fa-pencil"></i></td>
                        <td><i class="fa fa-trash"></i></td>
                    </tr>
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
                Добавление клиента
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
                        <input type="tel" id="phone" name="phone" pattern="[0-9]{11}" required>
                    </div>
                    <div class="modal__form-group">
                        <label for="birthday">День рождения</label>
                        <input type="date" id="birthday" name="birthday" required>
                    </div>
                    <div class="modal__form-actions">
                        <button type="submit" class="modal__btn">Создать</button>
                        <button type="button" class="modal__btn" data-micromodal-close>Отменить</button>
                    </div>
                </form>
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
                        <input type="tel" id="phone" name="phone" pattern="[0-9]{11}" required>
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

      <div class="modal micromodal-slide" id="delete-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
          <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="modal__header">
              <h2 class="modal__title" id="modal-1-title">
                Вы уверены, что хотите удалить клиента?
              </h2>
              <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
            </header>
            <main class="modal__content" id="modal-1-content">
                <button class="modal__btn danger">Удалить</button>
                <button class="modal__btn" data-micromodal-close>Отменить</button>
            </main>
          </div>
        </div>
      </div>

      <div class="modal micromodal-slide" id="history-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
          <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="modal__header">
              <h2 class="modal__title" id="modal-1-title">
                История заказов
              </h2>
              <small>Фамилия Имя Отчество</small>
              <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
            </header>
            <main class="modal__content" id="modal-1-content">
                <div class="order">
                    <div class="order__info">
                        <h3 class="order__number">Заказы №1</h3>
                        <time class="order__date">Дата оформления : 25-01-13 09:25:30</time>
                        <p class="order__total">Общая сумма : 300.00</p>
                    </div>
                    <table class="order__items">
                        <tr>
                            <th>ИД</th>
                            <th>Название товара</th>
                            <th>Количество</th>
                            <th>Цена</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Футболка</td>
                            <td>10</td>
                            <td>10000</td>
                        </tr>
                    </table>
                </div>
            </main>
          </div>
        </div>
      </div>

    <!-- модальные окна скрипт -->
    <script defer src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
    <script defer src="scripts/initClientsModal.js"></script>
    
</body>
</html>