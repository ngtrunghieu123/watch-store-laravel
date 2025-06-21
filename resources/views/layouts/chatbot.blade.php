<!-- Chatbot UI -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="chatbot-container">
    <div class="chatbot-toggle" id="chatbot-toggle">
        <i class="fas fa-comments"></i>
    </div>

    <div class="chatbot-box" id="chatbot-box">
        <div class="chatbot-header">
            <h5>ENTERNALUX Trợ lý</h5>
            <button type="button" class="chatbot-close" id="chatbot-close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="chatbot-messages" id="chatbot-messages">
            <div class="message bot-message">
                <div class="message-content">Xin chào! Tôi là trợ lý ảo của ENTERNALUX. Tôi có thể giúp bạn tìm kiếm sản phẩm hoặc trả lời các câu hỏi về shop. Bạn cần giúp đỡ gì?</div>
            </div>
        </div>

        <div class="chatbot-input">
            <input type="text" id="chatbot-input-field" placeholder="Nhập tin nhắn...">
            <button id="chatbot-send">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>