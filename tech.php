<?php session_start();

if (isset($_GET['do']) && $_GET['do'] === 'logout') {
    require_once 'api/auth/LogoutUser.php';
    require_once 'api/DB.php';

    LogoutUser('login.php', $DB, $_SESSION['token']);

    exit;
}

require_once 'api/auth/AuthCheck.php';
require_once 'api/helpers/InputDefaultValue.php';
require_once 'api/clients/ClientsSearch.php';

AuthCheck('', 'login.php');

require_once 'api/helpers/getUserType.php';

$userType = getUserType($DB);

if ($userType !== 'tech') {
    header('Location: clients.php');
}

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
    <title>CRM | Тех</title>
</head>
<body>
    <header class="header">
        <div class="container">
            <p class="header__admin">
                <?php 
                    require 'api/DB.php';
                    require_once 'api/clients/AdminName.php';
                    require_once 'api/helpers/getUserType.php';

                    echo AdminName($_SESSION['token'], $DB);
                    $userType = getUserType($DB);
                    echo " <span style='color: green'>($userType)</span>";
                ?>
            </p>
            <ul class="header__links">
                <li><a href="clients.php">Клиенты</a></li>
                <li><a href="product.php">Товары</a></li>
                <li><a href="orders.php">Заказы</a></li>
                <?php
                    if ($userType === 'tech') {
                        echo '<li><a href="tech.php">Обращение пользователя</a></li>';
                    }
                ?>
            </ul>
            <a href="?do=logout" class="header__logout">Выйти</a>
        </div>
    </header>

    <main class="main">
        <div class="container">
        <h2 class="main__clients__title">Список обращений пользователей</h2>
        <div class="filter-buttons">
            <button class="filter-btn active" data-status="all">Все</button>
            <button class="filter-btn" data-status="waiting">Ожидает</button>
            <button class="filter-btn" data-status="work">В работе</button>
            <button class="filter-btn" data-status="complete">Выполнено</button>
        </div>
        <!-- Пагинация -->
        <?php
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $maxClients = 10; // 10 карточек на странице (5x2)

        // Получаем общее количество тикетов
        $countTickets = $DB->query("SELECT COUNT(*) as count FROM tickets")
            ->fetchAll()[0]['count'];

        $maxPage = ceil($countTickets / $maxClients);
        $minPage = 1;

        // Нормализация currentPage
        if ($currentPage < $minPage || !is_numeric($currentPage)) {
            $currentPage = $minPage;
            header("Location: ?page=$currentPage");
            exit;
        }
        if ($currentPage > $maxPage) {
            $currentPage = $maxPage;
            header("Location: ?page=$currentPage");
            exit;
        }

        $offset = ($currentPage - 1) * $maxClients;
        if ($maxPage > 1) {
            echo '<div class="pagination-container">';
            
            // Кнопка "Предыдущая"
            $prevDisabled = ($currentPage <= $minPage) ? " disabled" : "";
            $Prev = $currentPage - 1;
            echo "<a href='?page=$Prev'$prevDisabled><i class='fa fa-arrow-left' aria-hidden='true'></i></a>";

            // Нумерованные кнопки
            echo "<div class='pagination'>";
            for ($i = 1; $i <= $maxPage; $i++) {
                $activeClass = ($i === $currentPage) ? " class='active'" : "";
                echo "<a href='?page=$i'$activeClass>$i</a>";
            }
            echo "</div>";

            // Кнопка "Следующая"
            $nextDisabled = ($currentPage >= $maxPage) ? " disabled" : "";
            $Next = $currentPage + 1;
            echo "<a href='?page=$Next'$nextDisabled><i class='fa fa-arrow-right' aria-hidden='true'></i></a>";
            
            echo '</div>';
        }
        ?>
        <div class="tickets-container">
            <?php
                // Получаем тикеты с учетом пагинации
                $query = "SELECT t.*, c.name as client_name, u.name as admin_name 
                         FROM tickets t 
                         LEFT JOIN clients c ON t.clients = c.id 
                         LEFT JOIN users u ON t.admin = u.id 
                         ORDER BY t.created_at DESC
                         LIMIT $maxClients OFFSET $offset";
                $stmt = $DB->query($query);
                
                while ($ticket = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $statusClass = '';
                    $statusText = '';
                    
                    switch($ticket['status']) {
                        case 'waiting':
                            $statusClass = 'status-waiting';
                            $statusText = 'Ожидает';
                            break;
                        case 'work':
                            $statusClass = 'status-work';
                            $statusText = 'В работе';
                            break;
                        case 'complete':
                            $statusClass = 'status-complete';
                            $statusText = 'Выполнено';
                            break;
                    }

                    // Определяем тип обращения
                    $ticketType = $ticket['type'] === 'tech' ? 'Техническая неполадка' : 'Проблема с CRM';

                    echo "
                    <div class='ticket-card' data-ticket-id='" . $ticket['id'] . "' data-status='" . $ticket['status'] . "'>
                        <div class='ticket-header'>
                            <span class='ticket-id'>#" . htmlspecialchars($ticket['id']) . "</span>
                            <select class='status-select " . $statusClass . "'>
                                <option value='waiting' " . ($ticket['status'] === 'waiting' ? 'selected' : '') . ">Ожидает</option>
                                <option value='work' " . ($ticket['status'] === 'work' ? 'selected' : '') . ">В работе</option>
                                <option value='complete' " . ($ticket['status'] === 'complete' ? 'selected' : '') . ">Выполнено</option>
                            </select>
                        </div>
                        <div class='ticket-type'>" . $ticketType . "</div>
                        <div class='ticket-message'><strong>Текст обращения:</strong> " . htmlspecialchars($ticket['message']) . "</div>
                        <div class='ticket-client'><i class='fa fa-user'></i> " . htmlspecialchars($ticket['client_name']) . "</div>
                        <div class='ticket-admin'><i class='fa fa-user-circle'></i> " . ($ticket['admin_name'] ? htmlspecialchars($ticket['admin_name']) : 'Не назначен') . "</div>
                        <div class='ticket-date'><i class='fa fa-calendar'></i> " . date('d.m.Y H:i', strtotime($ticket['created_at'])) . "</div>";
                    
                    // Добавляем кнопку просмотра файла, если он есть
                    if ($ticket['file_path']) {
                        echo "<div class='ticket-file'>
                            <button onclick='showFile(\"" . htmlspecialchars($ticket['file_path']) . "\")' class='file-view-btn'>
                                <i class='fa fa-file-o'></i> Просмотреть файл
                            </button>
                        </div>";
                    }
                    
                    echo "</div>";
                }
            ?>
        </div>
        </div>
    </main>

    <div class="modal micromodal-slide" id="file-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
          <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="modal__header">
              <h2 class="modal__title" id="modal-1-title">
                Просмотр файла
              </h2>
              <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
            </header>
            <main class="modal__content" id="modal-1-content">
                <div id="file-content"></div>
            </main>
          </div>
        </div>
    </div>

    <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Функция для остановки всех медиа элементов
        function stopAllMedia() {
            const mediaElements = document.querySelectorAll('video, audio');
            mediaElements.forEach(media => {
                media.pause();
                media.currentTime = 0;
            });
        }

        // Инициализация MicroModal
        MicroModal.init({
            openTrigger: 'data-micromodal-trigger',
            closeTrigger: 'data-micromodal-close',
            disableScroll: true,
            awaitOpenAnimation: false,
            awaitCloseAnimation: false,
            onClose: modal => {
                stopAllMedia();
            }
        });

        // Добавляем обработчики для всех способов закрытия
        const modal = document.getElementById('file-modal');
        const closeButton = modal.querySelector('.modal__close');
        const overlay = modal.querySelector('.modal__overlay');

        // Обработчик для кнопки закрытия (крестик)
        closeButton.addEventListener('click', stopAllMedia);

        // Обработчик для клика по оверлею (свободное место)
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                stopAllMedia();
            }
        });

        // Обработчик для клавиши Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.hasAttribute('aria-hidden')) {
                stopAllMedia();
            }
        });
    });

    function showFile(filePath) {
        const fileContent = document.getElementById('file-content');
        const extension = filePath.split('.').pop().toLowerCase();
        
        fileContent.innerHTML = '';
        
        if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
            fileContent.innerHTML = `<img src="${filePath}" style="max-width: 100%; height: auto;">`;
        } else if (['pdf'].includes(extension)) {
            fileContent.innerHTML = `<embed src="${filePath}" type="application/pdf" style="width: 100%; height: 600px;">`;
        } else if (extension === 'mp4') {
            fileContent.innerHTML = `
                <video controls style="max-width: 100%; height: auto;">
                    <source src="${filePath}" type="video/mp4">
                    Ваш браузер не поддерживает видео тег.
                </video>`;
        } else if (extension === 'mp3') {
            fileContent.innerHTML = `
                <audio controls style="width: 100%;">
                    <source src="${filePath}" type="audio/mpeg">
                    Ваш браузер не поддерживает аудио тег.
                </audio>`;
        } else {
            fileContent.innerHTML = `<a href="${filePath}" target="_blank" class="modal__btn">Скачать файл</a>`;
        }
        
        MicroModal.show('file-modal');
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const ticketCard = this.closest('.ticket-card');
                const ticketId = ticketCard.dataset.ticketId;
                const newStatus = this.value;
                const select = this;

                const formData = new FormData();
                formData.append('ticketId', ticketId);
                formData.append('status', newStatus);

                fetch('api/tickets/UpdateTicketStatus.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        ticketCard.dataset.status = newStatus;
                        select.classList.remove('status-waiting', 'status-work', 'status-complete');
                        select.classList.add('status-' + newStatus);
                    } else {
                        alert('Ошибка при обновлении статуса: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ошибка при выполнении запроса');
                });
            });
        });

        const filterButtons = document.querySelectorAll('.filter-btn');
        const ticketCards = document.querySelectorAll('.ticket-card');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                const status = button.dataset.status;
                
                ticketCards.forEach(card => {
                    const cardStatus = card.querySelector('.status-select').value;
                    if (status === 'all' || cardStatus === status) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });
    </script>
</body>
</html>