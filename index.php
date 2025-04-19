<?php
require_once 'SonusAIChat.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SonusAI Unlimited Chat</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/4.3.0/marked.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-primary: #1a1b1e;
            --bg-secondary: #2c2d31;
            --text-primary: #ffffff;
            --text-secondary: #a1a1aa;
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --border-color: #3b3f4e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .chat-container {
            background-color: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            min-height: 60vh;
            max-height: 60vh;
            overflow-y: auto;
        }

        .message {
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 8px;
            background-color: var(--bg-primary);
            animation: fadeIn 0.3s ease-in-out;
            display: flex;
            gap: 1rem;
        }

        .message .icon {
            font-size: 1.5rem;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .message .content {
            flex-grow: 1;
        }

        .message.ai {
            background-color: rgba(99, 102, 241, 0.1);
        }

        .message.ai .icon {
            color: var(--accent);
        }

        .message.user .icon {
            color: var(--text-secondary);
        }

        .message p {
            margin: 0 0 1em 0;
        }

        .message p:last-child {
            margin-bottom: 0;
        }

        .message pre {
            background: #282c34;
            border-radius: 6px;
            padding: 1rem;
            overflow-x: auto;
            margin: 1em 0;
        }

        .message code {
            font-family: 'Fira Code', monospace;
            font-size: 0.9em;
        }

        .message :not(pre) > code {
            background: rgba(99, 102, 241, 0.2);
            padding: 0.2em 0.4em;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .message ul, .message ol {
            margin: 1em 0;
            padding-left: 2em;
        }

        .message blockquote {
            border-left: 4px solid var(--accent);
            margin: 1em 0;
            padding-left: 1em;
            color: var(--text-secondary);
        }

        .message h1, .message h2, .message h3, .message h4 {
            margin: 1em 0 0.5em 0;
            color: var(--accent);
        }

        .message table {
            border-collapse: collapse;
            margin: 1em 0;
            width: 100%;
        }

        .message th, .message td {
            border: 1px solid var(--text-secondary);
            padding: 0.5em;
            text-align: left;
        }

        .message th {
            background: rgba(99, 102, 241, 0.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .typing-indicator {
            display: flex;
            align-items: center;
            background-color: rgba(99, 102, 241, 0.1);
        }

        .typing-indicator .dots {
            display: inline-flex;
            margin-left: 4px;
        }

        .typing-indicator .dots span {
            width: 4px;
            height: 4px;
            margin: 0 2px;
            background: var(--accent);
            border-radius: 50%;
            animation: wave 1.3s linear infinite;
            display: inline-block;
        }

        .typing-indicator .dots span:nth-child(2) {
            animation-delay: -1.1s;
        }

        .typing-indicator .dots span:nth-child(3) {
            animation-delay: -0.9s;
        }

        .reasoning-status {
            color: var(--accent);
            font-size: 0.9rem;
            margin-top: 0.5rem;
            font-style: italic;
        }

        @keyframes wave {
            0%, 60%, 100% {
                transform: initial;
            }
            30% {
                transform: translateY(-4px);
            }
        }

        .input-container {
            display: flex;
            gap: 1rem;
            background: var(--bg-secondary);
            padding: 1rem;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.1);
            transition: border-color 0.2s;
        }

        .input-container:focus-within {
            border-color: var(--accent);
        }

        textarea {
            flex: 1;
            background-color: transparent;
            border: none;
            color: var(--text-primary);
            padding: 0.75rem;
            font-family: inherit;
            font-size: 1rem;
            resize: none;
            min-height: 24px;
            max-height: 200px;
            line-height: 1.5;
        }

        textarea:focus {
            outline: none;
        }

        textarea::placeholder {
            color: var(--text-secondary);
            opacity: 0.7;
        }

        button {
            background-color: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            height: fit-content;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        button svg {
            width: 16px;
            height: 16px;
            transition: transform 0.2s;
        }

        button:hover {
            background-color: var(--accent-hover);
            transform: translateY(-1px);
        }

        button:hover svg {
            transform: translateX(2px);
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        button:disabled svg {
            transform: none;
        }

        .input-info {
            position: absolute;
            bottom: -1.5rem;
            right: 0;
            font-size: 0.75rem;
            color: var(--text-secondary);
            opacity: 0.8;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(45deg, var(--accent), #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header p {
            color: var(--text-secondary);
        }

        .footer {
            text-align: center;
            margin-top: 2rem;
            padding: 1rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .footer a:hover {
            color: var(--accent-hover);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-hover);
        }

        .model-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin: 15px 0;
            padding: 10px;
            border-radius: 10px;
        }

        .select-wrapper {
            position: relative;
            min-width: 200px;
        }

        .select-wrapper::after {
            content: '▼';
            font-size: 12px;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            pointer-events: none;
        }

        select#modelSelect {
            appearance: none;
            -webkit-appearance: none;
            width: 100%;
            padding: 8px 35px 8px 15px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        select#modelSelect:hover {
            border-color: var(--accent);
        }

        select#modelSelect:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(33,150,243,0.25);
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .custom-checkbox {
            position: relative;
            display: inline-block;
        }

        .custom-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
            cursor: pointer;
        }

        .checkmark {
            display: inline-block;
            width: 18px;
            height: 18px;
            background-color: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 4px;
            position: relative;
            transition: all 0.2s ease;
        }

        .custom-checkbox input[type="checkbox"]:checked + .checkmark {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        .custom-checkbox input[type="checkbox"]:checked + .checkmark::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid var(--bg-primary);
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .custom-checkbox input[type="checkbox"]:focus + .checkmark {
            box-shadow: 0 0 0 2px rgba(33,150,243,0.25);
        }

        .checkbox-label {
            font-size: 14px;
            color: var(--text-primary);
            user-select: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SonusAI Unlimited Chat</h1>
            <p>Experience intelligent conversations powered by SonusAI</p>
        </div>

        <div class="model-controls">
            <div class="select-wrapper">
                <select id="modelSelect">
                    <option value="mini">Mini</option>
                    <option value="air">Air</option>
                    <option value="pro">Pro</option>
                </select>
            </div>
            <div id="reasoningOption" style="display: none;">
                <label class="checkbox-wrapper">
                    <span class="custom-checkbox">
                        <input type="checkbox" id="reasoningCheckbox">
                        <span class="checkmark"></span>
                    </span>
                    <span class="checkbox-label">Enable Reasoning</span>
                </label>
            </div>
        </div>
        
        <div class="chat-container" id="chatContainer"></div>
        
        <form id="chatForm" class="input-container">
            <textarea 
                id="messageInput" 
                placeholder="Type your message here... (Ctrl + Enter to send)" 
                required
            ></textarea>
            <button type="submit" id="sendButton">
                Send
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M3.105 2.289a.75.75 0 00-.826.95l1.414 4.925A1.5 1.5 0 005.135 9.25h6.115a.75.75 0 010 1.5H5.135a1.5 1.5 0 00-1.442 1.086l-1.414 4.926a.75.75 0 00.826.95 28.896 28.896 0 0015.293-7.154.75.75 0 000-1.115A28.897 28.897 0 003.105 2.289z" />
                </svg>
            </button>
            <div class="input-info" id="inputInfo">Ctrl + Enter to send</div>
        </form>

        <div class="footer">
            Made with ❤️ by <a href="https://github.com/JemPH" target="_blank" rel="noopener noreferrer">JemPH</a>
        </div>
    </div>

    <script>
        function renderKatex(html) {
            if (!html) {
                return html;
            }

            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;

            try {
                const delimiters = [
                    { left: "$$", right: "$$", display: true },
                    { left: "\\[", right: "\\]", display: true },
                    { left: "\\(", right: "\\)", display: false }
                ];

                renderMathInElement(tempDiv, {
                    delimiters: delimiters,
                    throwOnError: false,
                    errorCallback: function(msg, err) {
                        console.error('KaTeX error:', err);
                        const errorHtml = `<span class="katex-error" title="${msg.toString().replace(/"/g, '&quot;')}" style="color: #cc0000; cursor: help;">
                            [Math Error: ${msg.toString().replace(/</g, '&lt;').replace(/>/g, '&gt;')}]
                        </span>`;
                        return errorHtml;
                    },
                    output: 'html',
                    strict: false,
                    macros: {
                        "\\boxed": "\\bbox[border: 1px solid]{#1}"
                    }
                });
            } catch (error) {
                console.error('Error in renderKatex:', error);
                return `<span class="katex-error" style="color: #cc0000;">Error rendering math expression: ${error.message}</span>`;
            }

            return tempDiv.innerHTML;
        }

        function renderContent(text) {
            const latexBlocks = [];
            let processedText = text.replace(/(\\\[[\s\S]*?\\\]|\\\([\s\S]*?\\\)|\$\$[\s\S]*?\$\$)/g, (match) => {
                latexBlocks.push(match);
                return `@@LATEX${latexBlocks.length - 1}@@`;
            });

            const renderer = new marked.Renderer();
            renderer.code = function(code, language) {
                const validLanguage = hljs.getLanguage(language) ? language : 'plaintext';
                const highlighted = hljs.highlight(code, { language: validLanguage }).value;
                return `<pre><code class="hljs language-${validLanguage}">${highlighted}</code></pre>`;
            };

            const html = marked.parse(processedText, { renderer });

            const restoredHtml = html.replace(/@@LATEX(\d+)@@/g, (match, index) => {
                return latexBlocks[parseInt(index)];
            });

            return renderKatex(restoredHtml);
        }

        const chatContainer = document.getElementById('chatContainer');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const modelSelect = document.getElementById('modelSelect');
        const reasoningCheckbox = document.getElementById('reasoningCheckbox');
        const reasoningOption = document.getElementById('reasoningOption');
        let messageHistory = [];

        modelSelect.addEventListener('change', function() {
            const isPro = this.value === 'pro';
            reasoningOption.style.display = isPro ? 'block' : 'none';
            if (!isPro) {
                reasoningCheckbox.checked = false;
            }
        });

        reasoningOption.style.display = modelSelect.value === 'pro' ? 'block' : 'none';

        function addMessage(message, isAI = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isAI ? 'ai' : 'user'}`;
            
            const iconDiv = document.createElement('div');
            iconDiv.className = 'icon';
            iconDiv.innerHTML = isAI ? '<i class="fas fa-robot"></i>' : '<i class="fas fa-user"></i>';
            
            const contentDiv = document.createElement('div');
            contentDiv.className = 'content';
            
            if (isAI) {
                contentDiv.innerHTML = renderContent(message);
                messageHistory.push({ role: "assistant", content: message });
            } else {
                contentDiv.textContent = message;
                messageHistory.push({ role: "user", content: message });
            }
            
            messageDiv.appendChild(iconDiv);
            messageDiv.appendChild(contentDiv);
            
            if (!isAI) {
                chatContainer.appendChild(messageDiv);

                const typingDiv = document.createElement('div');
                typingDiv.className = 'message ai typing-indicator';
                typingDiv.id = 'typingIndicator';
                
                const typingIconDiv = document.createElement('div');
                typingIconDiv.className = 'icon';
                typingIconDiv.innerHTML = '<i class="fas fa-robot"></i>';
                
                const typingContentDiv = document.createElement('div');
                typingContentDiv.className = 'content';
                typingContentDiv.innerHTML = `
                    <div class="typing-text">
                        AI is thinking
                        <div class="dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="reasoning-status" id="reasoningStatus"></div>
                `;
                
                typingDiv.appendChild(typingIconDiv);
                typingDiv.appendChild(typingContentDiv);
                chatContainer.appendChild(typingDiv);
            } else {
                chatContainer.appendChild(messageDiv);
            }
            
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function updateReasoningStatus(status) {
            const reasoningStatus = document.getElementById('reasoningStatus');
            if (reasoningStatus) {
                if (status) {
                    reasoningStatus.textContent = status;
                    reasoningStatus.style.display = 'block';
                } else {
                    reasoningStatus.style.display = 'none';
                }
            }
        }

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            addMessage(message);
            messageInput.value = '';
            sendButton.disabled = true;

            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        message: message,
                        history: messageHistory.slice(0, -1),
                        model: modelSelect.value,
                        reasoning: modelSelect.value === 'pro' && reasoningCheckbox.checked
                    })
                });

                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let aiMessageDiv = null;
                let currentMessage = '';

                while (true) {
                    const { value, done } = await reader.read();
                    if (done) break;

                    const text = decoder.decode(value);
                    const lines = text.split('\n');

                    for (const line of lines) {
                        if (line.startsWith('data: ')) {
                            try {
                                const data = JSON.parse(line.slice(6));
                                if (data.type === 'content') {
                                    if (!aiMessageDiv) {
                                        const typingIndicator = document.getElementById('typingIndicator');
                                        if (typingIndicator) {
                                            typingIndicator.remove();
                                        }
                                        aiMessageDiv = document.createElement('div');
                                        aiMessageDiv.className = 'message ai';
                                        chatContainer.appendChild(aiMessageDiv);
                                    }
                                    currentMessage += data.content;
                                    const iconDiv = document.createElement('div');
                                    iconDiv.className = 'icon';
                                    iconDiv.innerHTML = '<i class="fas fa-robot"></i>';
                                    const contentDiv = document.createElement('div');
                                    contentDiv.className = 'content';
                                    contentDiv.innerHTML = renderContent(currentMessage);
                                    aiMessageDiv.innerHTML = '';
                                    aiMessageDiv.appendChild(iconDiv);
                                    aiMessageDiv.appendChild(contentDiv);
                                    chatContainer.scrollTop = chatContainer.scrollHeight;
                                } else if (data.type === 'cid') {
                                    console.log('Conversation ID:', data.cid);
                                } else if (data.think) {
                                    updateReasoningStatus(data.think);
                                }
                            } catch (e) {
                                console.error('Error parsing SSE data:', e);
                            }
                        }
                    }
                }

                if (currentMessage) {
                    messageHistory.push({ role: "assistant", content: currentMessage });
                }
            } catch (error) {
                console.error('Error:', error);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'message ai';
                const iconDiv = document.createElement('div');
                iconDiv.className = 'icon';
                iconDiv.innerHTML = '<i class="fas fa-robot"></i>';
                const contentDiv = document.createElement('div');
                contentDiv.className = 'content';
                contentDiv.textContent = 'Failed to send message. Please try again.';
                errorDiv.appendChild(iconDiv);
                errorDiv.appendChild(contentDiv);
                chatContainer.appendChild(errorDiv);
                chatContainer.scrollTop = chatContainer.scrollHeight;
            } finally {
                sendButton.disabled = false;
            }
        });

        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(200, this.scrollHeight) + 'px';
            
            const remainingChars = 4000 - this.value.length;
            const inputInfo = document.getElementById('inputInfo');
            if (remainingChars <= 500) {
                inputInfo.textContent = `${remainingChars} characters remaining`;
                inputInfo.style.color = remainingChars <= 100 ? '#ef4444' : 'var(--text-secondary)';
            } else {
                inputInfo.textContent = 'Ctrl + Enter to send';
            }
        });

        messageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && e.ctrlKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>
</html>
