function refreshMessages() {
    // Replace with your actual URL for fetching new messages
    fetch('')
        .then(response => response.json())  // Parse JSON response
        .then(data => {
            // Update your message view logic with the new messages (data)
            console.log('New messages received:', data);
        })
        .catch(error => console.error('Error fetching messages:', error));
}

// Set the refresh interval (in milliseconds)
const refreshInterval = 5000;  // Refresh every 5 seconds

// Check if user is typing (optional)
const messageInput = document.getElementById('messagetosend');
let isTyping = false;

messageInput.addEventListener('keydown', () => isTyping = true);
messageInput.addEventListener('keyup', () => isTyping = false);

// Call the refresh function periodically, but only if not typing
setInterval(() => {
    if (!isTyping) {
        refreshMessages();
    }
}, refreshInterval);


function submitForm() {
    event.preventDefault(); // Prevent default form submission behavior
    document.getElementById('searchMessages').submit(); // Submit the form by its ID
}