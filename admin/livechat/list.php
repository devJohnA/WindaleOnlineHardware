<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<div id="confirmationDialog" class="confirmation-dialog" style="display: none;">
  <p><button id="confirmDelete" class="delete-icon" style="border:none; background:transparent;"> <i class="fas fa-pencil-alt text-danger">Yes</i></button>Delete?</p>
  
  <!-- <button id="cancelDelete" class="fas fa-close text-danger">No</button> -->
</div>

<style>
         #chat-list {
        max-height: 300px; 
        overflow-y: auto; 
    }
    .chat-list-item {
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .chat-list-item:hover {
        background-color: white;
    }
    .chat-container {
        border: 1px solid #ccc;
        height: 400px;
        display: flex;
        flex-direction: column;
        background-color: white;
    }
    .conversation-container {
        flex-grow: 1;
        overflow-y: auto;
        padding: 10px;
    }
    .message {
        max-width: 70%;
        padding: 10px;
        margin: 5px 0;
        border-radius: 18px;
        word-wrap: break-word;
        clear: both;
    }
    .user-message {
        float: left;
        background-color: #f1f0f0;
        color: #000;
    }
    .admin-message {
        float: right;
        background-color: #fd2323;
        color: white;
    }
    .message-time {
        font-size: 0.75em;
        margin-top: 5px;
        display: block;
    }
    .user-message .message-time {
        color: #888;
    }
    .admin-message .message-time {
        color: white;
    }
    .user-name {
        clear: both;
        float: left;
        margin-bottom: 5px;
        color: #888;
        font-size: 0.9em;
    }
    .reply-container {
        display: flex;
        align-items: center;
        padding: 10px;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }
    #message-textarea {
        flex-grow: 1;
        resize: none;
        border-radius: 20px;
        padding: 8px 15px;
        margin-right: 10px;
    }
    #send-message-btn {
        background-color: #007bff;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2em;
    }
    #send-message-btn:hover {
        background-color: #0056b3;
    }
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }

        .message-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 10px;
        }

        .modal {
    display: none; 
    position: fixed; 
    z-index: 1000; 
    padding-top: 120px; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgba(0, 0, 0, 0.8); 
}


.modal .close {
    position: absolute;
    top: 55px;
    right: 25px;
    color: #ffffff;
    font-size: 35px;
    font-weight: bold;
    cursor: pointer;
}


.modal-content {
    margin: auto;
    display: block;
    width: 85%; 
    max-width: 700px; 
    max-height: 80vh; 
    object-fit: contain; 
}

.modal-content {
    animation-name: zoom;
    animation-duration: 0.6s;
}

@keyframes zoom {
    from {transform: scale(0.1);}
    to {transform: scale(1);}
}

.modal .close:hover,
.modal .close:focus {
    color: #bbb;
}

/* New styles for the status modal */
#statusModal .modal-content {
            background-color: #f8f9fa;
            border-radius: 15px;
        }

        #statusModal .modal-header {
            border-bottom: none;
        }

        #statusModal .modal-footer {
            border-top: none;
        }

        .status-radio {
            display: none;
        }

        .status-label {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 25px;
            transition: all 0.3s;
        }

        #onlineStatus:checked + .status-label {
            background-color: #28a745;
            color: white;
        }

        #offlineStatus:checked + .status-label {
            background-color: #dc3545;
            color: white;
        }

        .message-radio {
            display: none;
        }

        .message-label {
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .message-radio:checked + .message-label {
            background-color: #e9ecef;
            border-color: #6c757d;
        }

        #statusModal {
    z-index: 1055; /* Ensure it's high enough */
}

.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5); /* Ensure it's not completely opaque */
}

.confirmation-dialog {
    position: absolute;
    background-color: white;
    border: 1px solid #ccc;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    z-index: 1000;
  }
  .confirmation-dialog p {
    margin-bottom: 10px;
  }
  .delete-icon {
    cursor: pointer;
  }
    </style>
    
    <section>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6 col-lg-5 col-xl-4 mb-4 mb-md-0">
                <h5 class="font-weight-bold mb-3 text-center text-lg-start">Customers</h5>
                <button id="settingsBtn" class="btn btn-light mb-3" data-bs-toggle="modal" data-bs-target="#statusModal">
                    <i class="bi bi-gear-fill me-2"></i>
                    Set Status
                </button>
                <div class="card">
                    <div class="card-body">
                        <ul class="list-unstyled mb-0" id="chat-list">
                            <!-- User messages will be dynamically inserted here -->
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-7 col-xl-8">
                <div class="chat-container">
                    <div class="conversation-container" id="conversation-container">
                        <!-- Conversation messages will be dynamically inserted here -->
                    </div>
                    <div class="reply-container">
                        <textarea class="form-control bg-body-tertiary" id="message-textarea" rows="1" placeholder="Type a reply..."></textarea>
                        <button style="background:transparent; color:#fd2323;" type="button" class="btn btn-info btn-rounded" id="send-message-btn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>		
    
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Set Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="statusForm">
        <div class="mb-3">
            <label class="form-label">Status:</label>
            <div>
                <input type="radio" id="onlineStatus" name="status" value="online" class="status-radio">
                <label for="onlineStatus" class="status-label">Online</label>

                <input type="radio" id="offlineStatus" name="status" value="offline" class="status-radio">
                <label for="offlineStatus" class="status-label">Offline</label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Default Message:</label>
            <div>
                <input type="radio" id="message1" name="defaultMessage" value="Hello! How can we help you today? Feel free to ask your questions, and we'll be happy to assist you!" class="message-radio">
                <label for="message1" class="message-label">Hello! How can we help you today? Feel free to ask your questions, and we'll be happy to assist you!</label>

                <input type="radio" id="message2" name="defaultMessage" value="Thank you for reaching out! Unfortunately, our admin is currently offline. Please leave your message." class="message-radio">
                <label for="message2" class="message-label">Thank you for reaching out! Unfortunately, our admin is currently offline. Please leave your message.</label>
            </div>
        </div>
    </form>
                   <!-- Form and inputs for status go here -->
                   </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveStatus">Save</button>
            </div>
        </div>
    </div>
</div>
<script>
 // Global variables
let activeUserId = null;
let lastMessageTimestamp = null;
let isUpdating = false;
let isSending = false;
let messageCache = new Set();
let lastChatListUpdate = 0;
let lastMessageBySameUser = null;
let initialResponseSent = {};




// Debounce function
function debounce(func, timeout = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
}

function formatTime(dateString) {
    const date = new Date(dateString + ' UTC');
    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
}

function isJustNow(dateString) {
    const now = new Date();
    const messageTime = new Date(dateString + ' UTC');
    const diffInMinutes = (now - messageTime) / 60000;
    return diffInMinutes < 5;
}

function scrollChatListToBottom() {
    const chatList = document.getElementById('chat-list');
    chatList.scrollTop = chatList.scrollHeight;
}

let currentDeleteUserId = null;

document.addEventListener('DOMContentLoaded', () => {
    const confirmationDialog = document.getElementById('confirmationDialog');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const cancelDeleteBtn = document.getElementById('cancelDelete');

    loadChatList();

    document.getElementById('chat-list').addEventListener('click', (event) => {
        const chatItem = event.target.closest('.chat-list-item');
        if (chatItem) {
            const userId = chatItem.dataset.userId;
            document.querySelectorAll('.chat-list-item').forEach(item => item.classList.remove('active'));
            chatItem.classList.add('active');
            loadConversation(userId);
            markAsRead(userId);
            updateChatItemStyle(chatItem, false);
            
            // Update read status in the database
            fetch('updateReadStatus.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${userId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Read status updated in database');
                } else {
                    console.error('Failed to update read status:', data.message);
                }
            })
            .catch(error => {
                console.error('Error updating read status:', error);
            });
        }
    });

    document.getElementById('send-message-btn').addEventListener('click', debouncedSendMessage);

    document.getElementById('message-textarea').addEventListener('keypress', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            debouncedSendMessage();
        }
    });

    // Event listeners for confirmation dialog buttons
    confirmDeleteBtn.addEventListener('click', handleDelete);
    cancelDeleteBtn.addEventListener('click', () => {
        confirmationDialog.style.display = 'none';
        currentDeleteUserId = null;
    });

    // Close the confirmation dialog when clicking outside of it
    document.addEventListener('click', (event) => {
        if (!confirmationDialog.contains(event.target) && event.target.closest('.delete-icon') === null) {
            confirmationDialog.style.display = 'none';
            currentDeleteUserId = null;
        }
    });

    setInterval(updateChat, 5000);
});

function loadChatList() {
    const now = Date.now();
    if (now - lastChatListUpdate < 5000) return;
    lastChatListUpdate = now;

    fetch('fetchMessages.php')
        .then(response => response.json())
        .then(data => {
            const chatList = document.getElementById('chat-list');
            chatList.innerHTML = '';
            data.users.forEach(user => {
                const listItem = document.createElement('li');
                listItem.className = 'p-2 border-bottom chat-list-item';
                listItem.dataset.userId = user.id;

                const isActive = user.id === activeUserId;
                const hasNewMessage = user.has_new_message; // Assuming your backend provides this flag

                // If active, always text-muted. If not active and has new message, bold.
                const textClass = isActive ? 'text-muted' : (hasNewMessage ? 'font-weight-bold' : 'text-muted');

                listItem.innerHTML = `
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <div class="pt-1">
                            <p class="${textClass} mb-0">${user.name}</p>
                            <p class="${textClass} small">${user.message}</p>
                        </div>
                        <div>
                            <span class="delete-icon" data-user-id="${user.id}">
                                <i class="fas fa-pencil-alt text-danger"></i>
                            </span>
                        </div>
                    </div>
                    <div class="pt-1">
                        <p class="${textClass} small mb-1">
                            <span class="message-time">${formatTime(user.timestamp)}</span>
                        </p>
                    </div>
                `;
                chatList.appendChild(listItem);
            });

            // Add event listeners for delete icons
            document.querySelectorAll('.delete-icon').forEach(icon => {
                icon.addEventListener('click', showDeleteConfirmation);
            });

            // Highlight active user if one is selected
            if (activeUserId) {
                const activeItem = chatList.querySelector(`[data-user-id="${activeUserId}"]`);
                if (activeItem) {
                    activeItem.classList.add('active');
                }
            }

            // Update unread message count
            updateUnreadMessageCount();
        });

    scrollChatListToBottom();
}

function showDeleteConfirmation(event) {
    event.stopPropagation();
    const deleteIcon = event.currentTarget;
    currentDeleteUserId = deleteIcon.dataset.userId;

    const confirmationDialog = document.getElementById('confirmationDialog');
    const rect = deleteIcon.getBoundingClientRect();
    confirmationDialog.style.top = `${rect.top - 40}px`;
    confirmationDialog.style.left = `${rect.left - 10}px`;
    confirmationDialog.style.display = 'block';
}

function handleDelete() {
    if (!currentDeleteUserId) return;

    fetch('deleteMessages.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${currentDeleteUserId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadChatList();
            if (activeUserId === currentDeleteUserId) {
                document.getElementById('conversation-container').innerHTML = '';
                activeUserId = null;
            }
        } else {
            console.error('Failed to delete messages:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    })
    .finally(() => {
        document.getElementById('confirmationDialog').style.display = 'none';
        currentDeleteUserId = null;
    });
}

// Function to handle delete action
// function handleDelete(event) {
//     event.stopPropagation(); // Prevent triggering the chat item click event
//     const userId = event.currentTarget.dataset.userId;
    
//     // Show confirmation dialog
//     Swal.fire({
//         title: 'Are you sure?',
//         text: "You won't be able to revert this!",
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Yes, delete it!'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             // Send delete request to server
//             fetch('deleteMessages.php', {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/x-www-form-urlencoded',
//                 },
//                 body: `user_id=${userId}`
//             })
//             .then(response => response.json())
//             .then(data => {
//                 if (data.success) {
//                     Swal.fire(
//                         'Deleted!',
//                         'The messages have been deleted.',
//                         'success'
//                     );
//                     // Reload chat list and clear conversation if the deleted user was active
//                     loadChatList();
//                     if (activeUserId === userId) {
//                         document.getElementById('conversation-container').innerHTML = '';
//                         activeUserId = null;
//                     }
//                 } else {
//                     Swal.fire(
//                         'Error!',
//                         'Failed to delete messages: ' + data.message,
//                         'error'
//                     );
//                 }
//             })
//             .catch(error => {
//                 console.error('Error:', error);
//                 Swal.fire(
//                     'Error!',
//                     'An error occurred while deleting messages.',
//                     'error'
//                 );
//             });
//         }
//     });
// }

function isMessageRead(userId) {
    // Implement logic to check if the message has been read
    // This could be based on localStorage, a server-side check, or any other method you prefer
    return localStorage.getItem(`read_${userId}`) === 'true';
}

function markAsRead(userId) {
    // Implement logic to mark the message as read
    localStorage.setItem(`read_${userId}`, 'true');
}

function updateChatItemStyle(item, hasNewMessage) {
    const isActive = item.classList.contains('active');
    const textElements = item.querySelectorAll('p');
    
    textElements.forEach(el => {
        if (isActive) {
            el.classList.remove('font-weight-bold');
            el.classList.add('text-muted');
        } else if (hasNewMessage) {
            el.classList.remove('text-muted');
            el.classList.add('font-weight-bold');
        } else {
            el.classList.remove('font-weight-bold');
            el.classList.add('text-muted');
        }
    });
}


function updateUnreadMessageCount() {
    fetch('get_unread_count.php')
        .then(response => response.json())
        .then(data => {
            const unreadCount = data.unread_count;
            const latestTimestamp = data.latest_timestamp;
            const badge = document.getElementById('unread-message-count');

            if (unreadCount > 0) {
                badge.textContent = unreadCount;
                badge.style.display = 'inline';
                if (latestTimestamp) {
                    const formattedTime = new Date(latestTimestamp).toLocaleString();
                    badge.title = `${unreadCount} customer(s) with unread messages. Latest at ${formattedTime}`;
                } else {
                    badge.title = `${unreadCount} customer(s) with unread messages.`;
                }
            } else {
                badge.style.display = 'none';
            }
        })
        .catch(error => {
            console.error("Error fetching unread message count:", error);
        });
}

// Update the chat every 5 seconds and check for unread messages


function loadConversation(userId) {
    if (isUpdating) return;
    isUpdating = true;

    // Fetch both the conversation and the admin status
    Promise.all([
        fetch(`fetchMessages.php?user_id=${userId}${lastMessageTimestamp ? '&last_timestamp=' + lastMessageTimestamp : ''}`),
        fetch('getAdminStatus.php')
    ])
    .then(responses => Promise.all(responses.map(r => r.json())))
    .then(([conversationData, statusData]) => {
        const conversationContainer = document.getElementById('conversation-container');
        const messages = conversationData.conversations || [];
        let newMessages = false;

        if (userId !== activeUserId) {
            conversationContainer.innerHTML = '';
            messageCache.clear();
            lastMessageTimestamp = null;
            initialResponseSent[userId] = false; // Reset initial response flag for new user
        }

        // Create a document fragment to build the conversation
        const fragment = document.createDocumentFragment();

        messages.forEach((msg, index) => {
            const messageKey = `${msg.sender}-${msg.timestamp}-${msg.message}`;
            if (!messageCache.has(messageKey)) {
                messageCache.add(messageKey);
                
                if (msg.sender === 'user') {
                    const nameDiv = document.createElement('div');
                    nameDiv.className = 'user-name';
                    nameDiv.textContent = msg.username;
                    fragment.appendChild(nameDiv);
                }

                const messageDiv = document.createElement('div');
                const timestamp = new Date(msg.timestamp);
                const formattedTime = isNaN(timestamp.getTime()) ? 'Invalid Date' : timestamp.toLocaleTimeString();

                messageDiv.className = msg.sender === 'user' ? 'message user-message' : 'message admin-message';

                messageDiv.innerHTML = `
                    ${msg.message}
                    <span class="message-time">${formattedTime}</span>
                `;

                // Add image if it exists
                if (msg.image_path) {
                    const imgElement = document.createElement('img');
                    imgElement.src = '../../' + msg.image_path;
                    imgElement.className = 'message-image';
                    imgElement.alt = 'User uploaded image';
                    imgElement.onclick = function () {
                        openImageModal(this.src);
                    };
                    imgElement.onerror = function () {
                        this.style.display = 'none';
                        console.error('Failed to load image:', msg.image_path);
                    };
                    messageDiv.appendChild(imgElement);
                }

                fragment.appendChild(messageDiv);

                // Add system message after the first user message if not already sent
                if (index === 0 && msg.sender === 'user' && !initialResponseSent[userId]) {
                    const systemMessageDiv = document.createElement('div');
                    systemMessageDiv.className = 'message admin-message system-message';
                    systemMessageDiv.innerHTML = `
                        ${statusData.defaultMessage}
                        <span class="message-time">System Message</span>
                    `;
                    fragment.appendChild(systemMessageDiv);
                    initialResponseSent[userId] = true;
                }

                newMessages = true;
            }
        });

        // Append all new messages to the conversation container
        conversationContainer.appendChild(fragment);

        // Add a clearfix div to ensure proper container height
        const clearfix = document.createElement('div');
        clearfix.className = 'clearfix';
        conversationContainer.appendChild(clearfix);

        if (newMessages) {
            conversationContainer.scrollTop = conversationContainer.scrollHeight;
        }
        if (messages.length > 0) {
            lastMessageTimestamp = messages[messages.length - 1].timestamp;
        }
        activeUserId = userId;
        isUpdating = false;
    })
    .catch(error => {
        console.error('Error:', error);
        isUpdating = false;
    });
}

function sendInitialResponse(userId) {
    fetch('getAdminStatus.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const formData = new FormData();
                formData.append('reply_message', data.defaultMessage);
                formData.append('user_id', userId);

                fetch('sentMessage.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    console.log(result);
                    loadConversation(userId); // Reload conversation to show the initial response
                })
                .catch(error => {
                    console.error('Error sending initial response:', error);
                });
            }
        })
        .catch(error => {
            console.error('Error getting admin status:', error);
        });
}

document.getElementById('message-textarea').addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        debouncedSendMessage();
    }
});

// Function to send a message
function sendMessage() {
    if (isSending || activeUserId === null) return;

    const messageTextarea = document.getElementById('message-textarea');
    const replyMessage = messageTextarea.value.trim();

    if (replyMessage === '') return;

    isSending = true;
    const formData = new FormData();
    formData.append('reply_message', replyMessage);
    formData.append('user_id', activeUserId); // Use the active user ID instead of email

    fetch('sentMessage.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            if (data.includes("Reply sent successfully")) {
                messageTextarea.value = '';
                loadConversation(activeUserId); // Reload conversation
                loadChatList(); // Update the chat list
            } else {
                alert(data);
            }
            isSending = false;
        })
        .catch(error => {
            console.error('Error:', error);
            isSending = false;
        });
}

const debouncedSendMessage = debounce(() => {
    if (!isSending) sendMessage();
}, 300);

function updateChat() {
    loadChatList();
    if (activeUserId !== null && !isUpdating && !isSending) {
        loadConversation(activeUserId);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadChatList();

   // Modify the click event listener for chat items
   document.getElementById('chat-list').addEventListener('click', (event) => {
    const chatItem = event.target.closest('.chat-list-item');
    if (chatItem) {
        const userId = chatItem.dataset.userId;
        document.querySelectorAll('.chat-list-item').forEach(item => {
            item.classList.remove('active');
            updateChatItemStyle(item, item.dataset.hasNewMessage === 'true');
        });
        chatItem.classList.add('active');
        updateChatItemStyle(chatItem, false); // Set to muted when active
        loadConversation(userId);
        markAsRead(userId);
        
        // Update read status in the database
        fetch('updateReadStatus.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${userId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Read status updated in database');
            } else {
                console.error('Failed to update read status:', data.message);
            }
        })
        .catch(error => {
            console.error('Error updating read status:', error);
        });
    }
});


    document.getElementById('send-message-btn').addEventListener('click', debouncedSendMessage);

    document.getElementById('message-textarea').addEventListener('keypress', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            debouncedSendMessage();
        }
    });

    setInterval(updateChat, 5000);
});

function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modal.style.display = "block";
    modalImg.src = src;
}

document.querySelector('.close').onclick = function() {
    document.getElementById('imageModal').style.display = "none";
}

window.onclick = function(event) {
    const modal = document.getElementById('imageModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

document.getElementById('saveStatus').addEventListener('click', function() {
    const status = document.querySelector('input[name="status"]:checked').value;
    const defaultMessage = document.querySelector('input[name="defaultMessage"]:checked').value;

    fetch('saveStatus.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `status=${encodeURIComponent(status)}&defaultMessage=${encodeURIComponent(defaultMessage)}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Close the modal using Bootstrap 5 method
                    var modalElement = document.getElementById('statusModal');
                    var modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Error updating status: ' + data.message,
                confirmButtonColor: '#d33'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'An error occurred while updating status: ' + error.message,
            confirmButtonColor: '#d33'
        });
    });
});

function setDefaultMessage(status) {
    if (status === 'online') {
        document.getElementById('message1').checked = true;
    } else if (status === 'offline') {
        document.getElementById('message2').checked = true;
    }
}

// Add event listeners to status radio buttons
document.getElementById('onlineStatus').addEventListener('change', function() {
    if (this.checked) {
        setDefaultMessage('online');
    }
});

document.getElementById('offlineStatus').addEventListener('change', function() {
    if (this.checked) {
        setDefaultMessage('offline');
    }
});

setInterval(() => {
    updateChat();
    updateUnreadMessageCount();
}, 2000);

// Update the chat every 5 seconds
setInterval(updateChat, 2000);

function updateChat() {
    loadChatList();
    if (activeUserId !== null) {
        loadConversation(activeUserId);
    }
    updateUnreadMessageCount();
}

// Initial update on page load
document.addEventListener('DOMContentLoaded', () => {
    loadChatList();
    updateUnreadMessageCount();
});
    </script>