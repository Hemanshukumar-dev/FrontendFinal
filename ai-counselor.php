<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AI Counselor - FuturePath Mentor</title>
    <meta name="description" content="Get personalized career advice from our AI counselor" />
    <meta name="author" content="Lovable" />
    
    <!-- Initialize dark mode before any rendering -->
    <script>
      // On page load or when changing themes, best to add inline in `head` to avoid FOUC
      if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
          document.documentElement.classList.add('dark');
      } else {
          document.documentElement.classList.remove('dark');
      }
    </script>

    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
      body {
        font-family: 'Inter', sans-serif;
      }
      .chat-message {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeIn 0.5s forwards;
      }
      @keyframes fadeIn {
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      /* Scrollbar styling */
      #chat-messages::-webkit-scrollbar {
        width: 8px;
      }
      #chat-messages::-webkit-scrollbar-track {
        background: #F3F4F6;
      }
      #chat-messages::-webkit-scrollbar-thumb {
        background-color: #D1D5DB;
        border-radius: 4px;
      }
      .dark #chat-messages::-webkit-scrollbar-track {
        background: #1F2937;
      }
      .dark #chat-messages::-webkit-scrollbar-thumb {
        background-color: #4B5563;
      }
      /* Add transition for smoother theme switching */
      #chat-messages, .chat-message, button, input {
        transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
      }
    </style>
    <!-- Add Gemini API -->
    <script src="https://generativelanguage.googleapis.com/v1beta/models/gemini-pro"></script>
</head>
<body>
<?php
include 'includes/header.php';
include 'includes/navigation.php';
?>

<!-- AI Counselor Header -->
<div class="pt-24 pb-6 bg-white dark:bg-gray-800">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center">
      <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">AI Career Counselor</h1>
      <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 dark:text-gray-400 sm:mt-4">
        Ask questions about careers, education paths, or get personalized advice.
      </p>
    </div>
  </div>
</div>

<!-- Chat Interface -->
<div class="pb-16 bg-white dark:bg-gray-800">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
      <!-- Chat Messages -->
      <div id="chat-messages" class="h-96 overflow-y-auto p-4 space-y-4">
        <!-- AI Welcome Message -->
        <div class="flex items-start chat-message">
          <div class="flex-shrink-0">
            <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white">
              AI
            </div>
          </div>
          <div class="ml-3 bg-gray-100 dark:bg-gray-700 p-3 rounded-lg rounded-tl-none">
            <p class="text-sm text-gray-900 dark:text-white">
              Hello! I'm your AI Career Counselor. I'm here to help you explore career options and answer your questions about educational paths, job opportunities, and more. How can I assist you today?
            </p>
          </div>
        </div>
        
        <!-- Suggestions -->
        <div class="flex items-start chat-message" style="animation-delay: 0.3s;">
          <div class="flex-shrink-0">
            <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white">
              AI
            </div>
          </div>
          <div class="ml-3 bg-gray-100 dark:bg-gray-700 p-3 rounded-lg rounded-tl-none">
            <p class="text-sm text-gray-900 dark:text-white mb-2">
              Here are some questions you might want to ask:
            </p>
            <div class="flex flex-wrap gap-2">
              <button onclick="askQuestion('What careers match my interests in technology and creativity?')" class="text-xs bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-full px-3 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-500 hover:border-primary transition-colors">
                What careers match my interests in technology and creativity?
              </button>
              <button onclick="askQuestion('How do I prepare for a career in healthcare?')" class="text-xs bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-full px-3 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-500 hover:border-primary transition-colors">
                How do I prepare for a career in healthcare?
              </button>
              <button onclick="askQuestion('What skills are most important for future jobs?')" class="text-xs bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-full px-3 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-500 hover:border-primary transition-colors">
                What skills are most important for future jobs?
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Chat Input -->
      <div class="border-t border-gray-200 dark:border-gray-700 p-4">
        <form id="chat-form" class="flex space-x-3">
          <input
            type="text"
            id="user-input"
            class="flex-1 focus:ring-primary focus:border-primary block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
            placeholder="Type your question here..."
          />
          <button
            type="submit"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
          >
            Send
          </button>
        </form>
      </div>
    </div>
    
    <!-- Additional Resources -->
    <div class="mt-8">
      <h2 class="text-lg font-medium text-gray-900 dark:text-white">Additional Resources</h2>
      <div class="mt-4 grid gap-4 md:grid-cols-2">
        <a href="career-paths.php" class="block bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:border-primary transition-colors">
          <h3 class="font-medium text-gray-900 dark:text-white">Career Paths Library</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Browse detailed information about various career options.</p>
        </a>
        <a href="aptitude-test.php" class="block bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:border-primary transition-colors">
          <h3 class="font-medium text-gray-900 dark:text-white">Take the Aptitude Test</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get personalized career recommendations based on your aptitudes.</p>
        </a>
      </div>
    </div>
  </div>
</div>

<script>
      // Initialize Gemini API Key
if (typeof API_KEY === 'undefined') {
    const API_KEY = 'AIzaSyDPjHTdRpIghshE6qbjKWbrx-JsMquuLYM';
}
      
      // DOM Elements
      const chatForm = document.getElementById('chat-form');
      const userInput = document.getElementById('user-input');
      const chatMessages = document.getElementById('chat-messages');
      
      // Add User Message to Chat
      function addUserMessage(message) {
        const messageElement = document.createElement('div');
        messageElement.className = 'flex items-start justify-end chat-message';
        messageElement.innerHTML = `
          <div class="mr-3 bg-primary text-white p-3 rounded-lg rounded-tr-none">
            <p class="text-sm">${escapeHTML(message)}</p>
          </div>
          <div class="flex-shrink-0">
            <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300">
              You
            </div>
          </div>
        `;
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
      }
      
      // Add AI Message to Chat
      function addAIMessage(message) {
        const messageElement = document.createElement('div');
        messageElement.className = 'flex items-start chat-message';
        messageElement.innerHTML = `
          <div class="flex-shrink-0">
            <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white">
              AI
            </div>
          </div>
          <div class="ml-3 bg-gray-100 dark:bg-gray-700 p-3 rounded-lg rounded-tl-none">
            <p class="text-sm text-gray-900 dark:text-white">${message}</p>
          </div>
        `;
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
      }
      
      // Show typing indicator
      function showTypingIndicator() {
        const typingIndicator = document.createElement('div');
        typingIndicator.id = 'typing-indicator';
        typingIndicator.className = 'flex items-start chat-message';
        typingIndicator.innerHTML = `
          <div class="flex-shrink-0">
            <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white">
              AI
            </div>
          </div>
          <div class="ml-3 bg-gray-100 dark:bg-gray-700 p-3 rounded-lg rounded-tl-none">
            <p class="text-sm text-gray-900 dark:text-white">Typing<span class="typing-animation">...</span></p>
          </div>
        `;
        chatMessages.appendChild(typingIndicator);
        chatMessages.scrollTop = chatMessages.scrollHeight;
      }

      // Event Listeners
      chatForm.addEventListener('submit', sendMessage);
      
      // Send Message Function
      function sendMessage(e) {
        e.preventDefault();
        const message = userInput.value.trim();
        if (message === '') return;
        
        // Add user message to chat
        addUserMessage(message);
        
        // Clear input field
        userInput.value = '';
        
        // Get AI response
        getAIResponse(message);
      }
      
          // Get AI Response - Replace the existing getAIResponse function with this one
      async function getAIResponse(userMessage) {
          // Show typing indicator
          showTypingIndicator();
          
          try {
              const response = await fetch(`https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key=${API_KEY}`, {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json'
                  },
                  body: JSON.stringify({
                      contents: [{
                          parts: [{
                              text: `As an AI career counselor, provide professional advice for this career-related question: ${userMessage}`
                          }]
                      }]
                  })
              });
      
              if (!response.ok) {
                  throw new Error(`API request failed: ${response.status}`);
              }
      
              const data = await response.json();
              
              // Remove typing indicator
              const typingIndicator = document.getElementById('typing-indicator');
              if (typingIndicator) {
                  chatMessages.removeChild(typingIndicator);
              }
      
              if (data.candidates && data.candidates[0] && data.candidates[0].content) {
                  const aiResponse = data.candidates[0].content.parts[0].text;
                  addAIMessage(aiResponse);
              } else {
                  throw new Error('Invalid response format');
              }
          } catch (error) {
              console.error('Error:', error);
              
              // Remove typing indicator
              const typingIndicator = document.getElementById('typing-indicator');
              if (typingIndicator) {
                  chatMessages.removeChild(typingIndicator);
              }
              
              addAIMessage('Sorry, I encountered an error. Please try again.');
          }
      }

      function appendMessage(type, content) {
          const chatMessages = document.getElementById('chat-messages');
          const messageDiv = document.createElement('div');
          messageDiv.className = `chat-message ${type}-message p-4 rounded-lg ${type === 'user' ? 'bg-primary-50 ml-12' : 'bg-gray-50 mr-12'} dark:bg-gray-700`;
          messageDiv.innerHTML = `
              <div class="flex items-start">
                  <div class="flex-shrink-0">
                      <div class="w-8 h-8 rounded-full ${type === 'user' ? 'bg-primary' : 'bg-secondary'} flex items-center justify-center text-white">
                          ${type === 'user' ? 'U' : 'AI'}
                      </div>
                  </div>
                  <div class="ml-3">
                      <p class="text-sm text-gray-900 dark:text-gray-100">${content}</p>
                  </div>
              </div>
          `;
          chatMessages.appendChild(messageDiv);
          chatMessages.scrollTop = chatMessages.scrollHeight;
      }

      // Handle Enter key press
      document.getElementById('user-input').addEventListener('keypress', function(e) {
          if (e.key === 'Enter') {
              sendMessage();
          }
      });
  </script>

<?php include 'includes/footer.php'; ?>
</body>
</html>

    <script>
        // Initialize Gemini
        const API_KEY = 'AIzaSyDPjHTdRpIghshE6qbjKWbrx-JsMquuLYM';
        
        // Get AI Response
        async function getAIResponse(userMessage) {
            // Show typing indicator
            showTypingIndicator();
            
            try {
                const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=${API_KEY}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        contents: [{
                            parts: [{
                                text: userMessage
                            }]
                        }]
                    })
                });
        
                if (!response.ok) {
                    throw new Error('API request failed');
                }
        
                const data = await response.json();
                
                // Remove typing indicator
                const typingIndicator = document.getElementById('typing-indicator');
                if (typingIndicator) {
                    chatMessages.removeChild(typingIndicator);
                }
        
                if (data.candidates && data.candidates[0] && data.candidates[0].content) {
                    const aiResponse = data.candidates[0].content.parts[0].text;
                    addAIMessage(aiResponse);
                } else {
                    throw new Error('Invalid response format');
                }
            } catch (error) {
                console.error('Error:', error);
                
                // Remove typing indicator
                const typingIndicator = document.getElementById('typing-indicator');
                if (typingIndicator) {
                    chatMessages.removeChild(typingIndicator);
                }
                
                addAIMessage('Sorry, I encountered an error. Please try again.');
            }
        }

        function appendMessage(type, content) {
            const chatMessages = document.getElementById('chat-messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `chat-message ${type}-message p-4 rounded-lg ${type === 'user' ? 'bg-primary-50 ml-12' : 'bg-gray-50 mr-12'} dark:bg-gray-700`;
            messageDiv.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full ${type === 'user' ? 'bg-primary' : 'bg-secondary'} flex items-center justify-center text-white">
                            ${type === 'user' ? 'U' : 'AI'}
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-900 dark:text-gray-100">${content}</p>
                    </div>
                </div>
            `;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Handle Enter key press
        document.getElementById('user-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
</body>
</html>
