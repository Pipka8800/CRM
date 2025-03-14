    <div class="support-create-ticket">
        <form action="api/tickets/CreateTicket.php" method="POST" enctype="multipart/form-data">
            <label for="type">Тип обращения</label>
            <select name="type" id="type" class="support-select">
                <option value="tech">Техническая неполадка</option>
                <option value="crm">Проблема с crm</option>
            </select>
            <label for="message">Текст обращения</label>
            <textarea name="message" id="message"></textarea>
            <input type="file" name="ticket_file" id="ticket_file">
            <button type="submit" class="support-submit">Создать тикет</button>
            <button type="button" class="support-toggle-btn" onclick="toggleTickets()">Мои обращения</button>
        </form>
    </div>

    <div class="support-my-tickets">
        <h3>Мои обращения</h3>
        <div class="support-ticket-list">
            <?php
                require_once 'api/tickets/GetUserTickets.php';
                $tickets = GetUserTickets($DB, $_SESSION['token']);
                foreach ($tickets as $ticket) {
                    echo '<div class="support-ticket-item" onclick="openTicketChat(' . $ticket['id'] . ')">';
                    echo '<div class="ticket-type">' . htmlspecialchars($ticket['type']) . '</div>';
                    echo '<div class="ticket-message">' . htmlspecialchars(substr($ticket['message'], 0, 50)) . '...</div>';
                    echo '<div class="ticket-date">' . $ticket['created_at'];
                    echo '<span class="ticket-status ' . ($ticket['status'] ? 'open' : 'closed') . '">';
                    echo $ticket['status'] ? 'Открыт' : 'Закрыт';
                    echo '</span></div>';
                    echo '</div>';
                }
            ?>
        </div>
        <button type="button" class="support-toggle-btn" onclick="toggleTickets()">Создать обращение</button>
    </div>

    <script>
    function toggleTickets() {
        const createTicket = document.querySelector('.support-create-ticket');
        const myTickets = document.querySelector('.support-my-tickets');
        
        createTicket.classList.toggle('active');
        myTickets.classList.toggle('active');
    }

    function openTicketChat(ticketId) {
        // Здесь можно добавить логику открытия чата
        console.log('Opening chat for ticket:', ticketId);
    }
    </script> 