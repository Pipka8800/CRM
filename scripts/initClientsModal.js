document.addEventListener('DOMContentLoaded', function() {
    // Инициализация всех модальных окон
    MicroModal.init({
        onShow: modal => console.log(`${modal.id} is shown`), // отладочный вывод
        onClose: modal => {
            // Очищаем URL при закрытии модального окна
            let url = new URL(window.location.href);
            url.searchParams.delete(modal.id.replace('-modal', '')); // удаляем соответствующий параметр
            window.history.replaceState({}, '', url);
        },
        openTrigger: 'data-micromodal-trigger', // default: data-micromodal-trigger
        closeTrigger: 'data-micromodal-close', // default: data-micromodal-close
        disableScroll: true, // default: false
        disableFocus: false, // default: false
        awaitOpenAnimation: false, // default: false
        awaitCloseAnimation: false, // default: false
    });

    // Проверяем URL на наличие параметров для открытия модальных окон
    const urlParams = new URLSearchParams(window.location.search);
    
    // Проверяем параметр edit-user
    if (urlParams.has('edit-user')) {
        setTimeout(() => {
            MicroModal.show('edit-modal');
        }, 100); // небольшая задержка для гарантии инициализации
    }

    // Проверяем параметр send-email
    if (urlParams.has('send-email')) {
        setTimeout(() => {
            MicroModal.show('send-email-modal');
        }, 100);
    }

    // Обработчики для системы обращений
    const supportBtn = document.querySelector('.support-btn');
    const supportForm = document.querySelector('.support-create-ticket');
    const myTicketsBtn = document.querySelector('.my-tickets-btn');
    const myTicketsContainer = document.querySelector('.my-tickets-container');
    const ticketsList = document.querySelector('.tickets-list');

    if (supportBtn && supportForm && myTicketsBtn && myTicketsContainer) {
        // Обработчик клика по кнопке поддержки
        supportBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            supportForm.classList.toggle('active');
            myTicketsContainer.classList.remove('active');
        });

        // Обработчик для кнопки "Мои обращения"
        myTicketsBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            supportForm.classList.remove('active');
            myTicketsContainer.classList.add('active');
            loadMyTickets();
        });

        // Предотвращаем закрытие форм при клике внутри них
        supportForm.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        myTicketsContainer.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Закрытие форм при клике вне их области
        document.addEventListener('click', function(e) {
            if (!supportForm.contains(e.target) && !supportBtn.contains(e.target) &&
                !myTicketsContainer.contains(e.target)) {
                supportForm.classList.remove('active');
                myTicketsContainer.classList.remove('active');
            }
        });
    }

    // Функция загрузки списка обращений
    function loadMyTickets() {
        fetch('api/tickets/GetMyTickets.php')
            .then(response => response.json())
            .then(tickets => {
                ticketsList.innerHTML = tickets.map(ticket => `
                    <div class="ticket-item">
                        <div class="ticket-type">${ticket.type === 'tech' ? 'Техническая неполадка' : 'Проблема с CRM'}</div>
                        <div class="ticket-status ${ticket.status}">${getStatusText(ticket.status)}</div>
                        <div class="ticket-date">${formatDate(ticket.created_at)}</div>
                        <div class="ticket-message">${ticket.message.substring(0, 50)}${ticket.message.length > 50 ? '...' : ''}</div>
                        <button class="open-chat-btn" data-ticket-id="${ticket.id}">Открыть чат</button>
                    </div>
                `).join('');
                
                // Обновляем обработчики после загрузки тикетов
                updateChatHandlers();
            })
            .catch(error => {
                console.error('Error loading tickets:', error);
                ticketsList.innerHTML = '<p>Ошибка при загрузке обращений</p>';
            });
    }

    function getStatusText(status) {
        const statusMap = {
            'waiting': 'Ожидает ответа',
            'work': 'В работе',
            'complete': 'Завершено'
        };
        return statusMap[status] || status;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('ru-RU', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    let currentTicketId = null;

    window.openChat = function(ticketId) {
        currentTicketId = ticketId;
        loadChatMessages(ticketId);
        MicroModal.show('chat-modal');
    }

    window.loadChatMessages = function(ticketId) {
        fetch(`api/tickets/GetTicketMessages.php?ticket_id=${ticketId}`)
            .then(response => response.json())
            .then(data => {
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.innerHTML = '';
                data.forEach(message => {
                    const messageTime = new Date(message.created_at).toLocaleString();
                    chatMessages.innerHTML += `
                        <div class="chat-message">
                            <strong>${message.sender_name}:</strong> 
                            ${message.message}
                            <span class="message-time">(${messageTime})</span>
                        </div>`;
                });
                chatMessages.scrollTop = chatMessages.scrollHeight;
            });
    }

    // Обработчик отправки сообщения
    const sendMessageBtn = document.getElementById('send-message');
    if (sendMessageBtn) {
        sendMessageBtn.addEventListener('click', function() {
            const input = document.getElementById('chat-input');
            const message = input.value.trim();
            
            if (message && currentTicketId) {
                const formData = new FormData();
                formData.append('ticket_id', currentTicketId);
                formData.append('message', message);

                fetch('api/tickets/SendMessage.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        input.value = '';
                        loadChatMessages(currentTicketId);
                    } else {
                        console.error('Server response:', data);
                        alert('Ошибка при отправке сообщения: ' + (data.message || 'Неизвестная ошибка'));
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Ошибка при отправке сообщения: ' + error.message);
                });
            }
        });
    }

    // Отправка по Enter
    const chatInput = document.getElementById('chat-input');
    if (chatInput) {
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('send-message').click();
            }
        });
    }

    // Функция для обновления обработчиков после загрузки тикетов
    window.updateChatHandlers = function() {
        document.querySelectorAll('.open-chat-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const ticketId = this.getAttribute('data-ticket-id');
                openChat(ticketId);
            });
        });
    }
});