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
});