document.addEventListener('DOMContentLoaded', function () {
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotBox = document.getElementById('chatbot-box');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const chatbotInput = document.getElementById('chatbot-input-field');
    const chatbotSend = document.getElementById('chatbot-send');

    let sessionId = localStorage.getItem('chatbot_session_id') || null;

    // Toggle chatbot visibility
    chatbotToggle.addEventListener('click', function () {
        chatbotBox.style.display = 'flex';
        chatbotToggle.style.display = 'none';

        // Load chat history if session exists
        if (sessionId) {
            loadChatHistory();
        }
    });

    // Close chatbot
    chatbotClose.addEventListener('click', function () {
        chatbotBox.style.display = 'none';
        chatbotToggle.style.display = 'flex';
    });

    // Send message
    function sendMessage() {
        const message = chatbotInput.value.trim();

        if (message) {
            // Display user message
            addMessage(message, false);

            // Clear input
            chatbotInput.value = '';

            // Send message to server
            fetch('/chatbot/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: message,
                    session_id: sessionId
                })
            })
                .then(response => response.json())
                .then(data => {
                    // Save session ID if first message
                    if (!sessionId) {
                        sessionId = data.session_id;
                        localStorage.setItem('chatbot_session_id', sessionId);
                    }

                    // Display bot response
                    addMessage(data.response, true);

                    // Display product suggestions if any
                    if (data.products && data.products.length > 0) {
                        addProductSuggestions(data.products);
                    }

                })
                .catch(error => {
                    console.error('Error:', error);
                    addMessage('Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.', true);
                });
        }
    }

    // Send message on button click
    chatbotSend.addEventListener('click', sendMessage);

    // Send message on Enter key
    chatbotInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Add message to chat
    function addMessage(content, isBot) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message');
        messageDiv.classList.add(isBot ? 'bot-message' : 'user-message');

        const messageContent = document.createElement('div');
        messageContent.classList.add('message-content');
        messageContent.textContent = content;

        messageDiv.appendChild(messageContent);
        chatbotMessages.appendChild(messageDiv);

        // Scroll to bottom
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    // Add product suggestions
    function addProductSuggestions(products) {
        const productsContainer = document.createElement('div');
        productsContainer.classList.add('bot-message');
        productsContainer.classList.add('message');

        products.forEach(product => {
            const productDiv = document.createElement('div');
            productDiv.classList.add('product-suggestion');

            productDiv.innerHTML = `
                <img src="/storage/${product.hinhanh}" alt="${product.tensanpham}">
                <div class="product-info">
                    <h4>${product.tensanpham}</h4>
                    <div class="price">${new Intl.NumberFormat('vi-VN').format(product.dongia)}đ</div>
                    <a href="/sanpham/${product.slug}" class="view-product">Xem chi tiết</a>
                </div>
            `;

            productsContainer.appendChild(productDiv);
        });

        chatbotMessages.appendChild(productsContainer);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    // Load chat history
    function loadChatHistory() {
        fetch(`/chatbot/history?session_id=${sessionId}`)
            .then(response => response.json())
            .then(data => {
                // Clear current messages
                chatbotMessages.innerHTML = '';

                // Add messages from history
                data.messages.forEach(msg => {
                    addMessage(msg.message, msg.is_bot);
                });
            })
            .catch(error => {
                console.error('Error loading chat history:', error);
            });
    }
});