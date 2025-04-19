# sonusai-chat

![SonusAI Chat](https://img.shields.io/badge/SonusAI-Chat-6366f1)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A beautiful, responsive web interface for SonusAI chatbot services with real-time streaming responses, code syntax highlighting, and LaTeX math rendering support.

![SonusAI Preview](https://via.placeholder.com/800x400?text=SonusAI+Chat+Interface)

## ✨ Features

- 🚀 **Real-time streaming responses** with Server-Sent Events
- 💬 **Chat history** persistence within the session
- 🧠 **Reasoning mode** to see AI's thought process
- 🔄 **Model selection** between different AI tiers
- 📱 **Fully responsive design** for all devices
- 🎨 **Beautiful UI** with dark mode
- 📝 **Markdown support** for rich formatting
- 🧮 **LaTeX math** rendering with KaTeX
- 💻 **Syntax highlighting** for code blocks
- 📋 **Copy to clipboard** functionality for code snippets

## 🚀 Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/JemPH/sonusai-chat.git
   ```

2. **Deploy to PHP server**
   - Upload the files to any PHP-compatible web server
   - Ensure the server supports Server-Sent Events

3. **Access the application**
   - Navigate to the URL where you deployed the files
   - Start chatting immediately, no authentication required

## 💻 Tech Stack

- **Frontend**: HTML, CSS, JavaScript with modern ES6+ features
- **Backend**: PHP
- **Libraries**:
  - [KaTeX](https://katex.org/) for LaTeX math rendering
  - [Marked](https://marked.js.org/) for Markdown parsing
  - [Highlight.js](https://highlightjs.org/) for code syntax highlighting
  - [Font Awesome](https://fontawesome.com/) for icons

## 🔧 Configuration

The application is ready to use out of the box with the SonusAI API. If you need to customize:

- Edit `SonusAIChat.php` to change API endpoint settings
- Modify CSS variables in `index.php` to adjust the theme
- Adjust the models available in the dropdown by editing the model selector

## 📖 Usage

1. **Type your message** in the input field at the bottom
2. **Select a model** from the dropdown (Pro recommended)
3. **Toggle reasoning mode** if you want to see the AI's thought process
4. **Send your message** and watch the real-time streaming response

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
