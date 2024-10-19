<?php
session_start();
include '../db.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: ../index.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// Retrieve the chat ID if provided, otherwise default to the first chat
$chat_id = isset($_GET['chat_id']) ? intval($_GET['chat_id']) : 0;

// Validate chat ID
if ($chat_id) {
    $stmt = $pdo->prepare("SELECT * FROM chats WHERE id = ? AND (sender_id = ? OR receiver_id = ?)");
    $stmt->execute([$chat_id, $patient_id, $patient_id]);
    if (!$stmt->fetch()) {
        $chat_id = 0; // Invalid chat ID
    }
}

// Fetch the list of doctors with profile images and unread message counts
$stmt = $pdo->prepare("
    SELECT u.id, u.name, u.image,
           IFNULL(SUM(CASE WHEN m.is_read = 0 AND m.sender_id != ? THEN 1 ELSE 0 END), 0) AS unread_messages
    FROM users u
    JOIN doctors d ON u.id = d.user_id
    LEFT JOIN messages m ON (m.sender_id = u.id AND m.is_read = 0 AND m.chat_id IN (
        SELECT id FROM chats WHERE sender_id = ? OR receiver_id = ?
    ))
    WHERE u.id != ?
    GROUP BY u.id, u.name, u.image
");
$stmt->execute([$patient_id, $patient_id, $patient_id, $patient_id]);
$doctors = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with Doctors</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }
        #chat-container {
            display: flex;
            height: 90vh;
            margin: 20px auto;
            max-width: 1200px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        #contacts {
            width: 30%;
            border-right: 1px solid #ddd;
            padding: 10px;
            background: #fff;
            overflow-y: auto;
        }
        #contacts h3 {
            margin-top: 0;
            color: #007bff;
        }
        .contact-item {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            display: flex;
            align-items: center;
            position: relative;
        }
        .contact-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .notification-dot {
            width: 10px;
            height: 10px;
            background-color: red;
            border-radius: 50%;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        #chat-box {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            background: #f0f2f5;
        }
        #doctor-info {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        #doctor-info img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        #doctor-info strong {
            font-size: 18px;
            color: #333;
        }
        #messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }
        .message {
            margin-bottom: 15px;
            max-width: 60%;
            padding: 10px;
            border-radius: 20px;
            font-size: 14px;
            line-height: 1.5;
            position: relative;
        }
        .message.sent {
            background-color: #007bff;
            color: white;
            align-self: flex-end;
            text-align: right;
        }
        .message.received {
            background-color: #e9ecef;
            align-self: flex-start;
            text-align: left;
        }
        .message strong {
            display: block;
            margin-bottom: 5px;
        }
        .message.sent strong {
            display: none;
        }
        #message-input-container {
            display: flex;
            align-items: center;
            padding: 10px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        #message-input {
            flex: 1;
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 20px;
            background-color: #f0f2f5;
        }
        #send-button {
            width: 60px;
            padding: 10px;
            margin-left: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
        }
        #send-button:hover {
            background-color: #0056b3;
        }
        #back-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 16px;
        }
        #back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <a href="patient_dashboard.php" id="back-button">Back to Dashboard</a>
    <div id="chat-container">
        <div id="contacts">
            <h3>Doctors</h3>
            <?php foreach ($doctors as $doctor): ?>
                <div class="contact-item" data-id="<?php echo htmlspecialchars($doctor['id']); ?>">
                    <img src="<?php echo $doctor['image'] ? '../uploads/' . htmlspecialchars($doctor['image']) : '../default_images/default.png'; ?>" alt="Profile Image">
                    <?php echo htmlspecialchars($doctor['name']); ?>
                    <?php if ($doctor['unread_messages'] > 0): ?>
                        <div class="notification-dot"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="chat-box">
            <div id="doctor-info">
                <!-- Doctor's name and image will be dynamically inserted here -->
            </div>
            <div id="messages"></div>
            <div id="message-input-container">
                <input type="text" id="message-input" placeholder="Type your message...">
                <button id="send-button">Send</button>
            </div>
        </div>
    </div>

    <script>
        let chatId = <?php echo json_encode($chat_id); ?>;
        let patientId = <?php echo json_encode($patient_id); ?>;

        function fetchMessages() {
            if (!chatId) return;

            $.get('fetch_messages.php', { chat_id: chatId }, function(data) {
                const messages = JSON.parse(data);
                $('#messages').empty();
                messages.forEach(message => {
                    const messageClass = message.sender_id === patientId ? 'sent' : 'received';
                    $('#messages').append(`<div class="message ${messageClass}"><strong>${messageClass === 'received' ? message.sender_name + ':' : ''}</strong>${message.message}</div>`);
                });
                $('#messages').scrollTop($('#messages')[0].scrollHeight);
            });
        }

        function fetchContacts() {
            $.get('fetch_contacts.php', function(data) {
                const contacts = JSON.parse(data);
                $('#contacts').empty();
                contacts.forEach(contact => {
                    const notificationDot = contact.unread_messages > 0 ? '<div class="notification-dot"></div>' : '';
                    $('#contacts').append(`<div class="contact-item" data-id="${contact.id}"><img src="${contact.image ? '../uploads/' + contact.image : '../default_images/default.png'}" alt="Profile Image">${contact.name}${notificationDot}</div>`);
                });
            });
        }

        $('#send-button').click(function() {
            const message = $('#message-input').val();
            if (message.trim() === '' || !chatId) return;

            $.post('send_message.php', { chat_id: chatId, message: message }, function(response) {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    $('#message-input').val('');  // Clear input after sending
                    fetchMessages();  // Fetch the latest messages
                    fetchContacts();  // Update contact list with unread counts
                }
            });
        });

        $('#contacts').on('click', '.contact-item', function() {
            const doctorId = $(this).data('id');

            // Create or select a chat with this doctor
            $.post('create_or_select_chat.php', { doctor_id: doctorId }, function(response) {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    chatId = result.chat_id;

                    // Update doctor info in #doctor-info
                    $('#doctor-info').html(`<img src="${result.doctor_image}" alt="Doctor Image" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 10px;"><span><strong>${result.doctor_name}</strong></span>`);

                    fetchMessages();  // Fetch messages for this chat
                    fetchContacts();  // Update contact list to reflect chat selection
                }
            });
        });

        // Set interval to fetch messages and contacts every 5 seconds
        setInterval(function() {
            fetchMessages();
            fetchContacts();
        }, 5000);

        fetchMessages();
        fetchContacts();
    </script>
</body>
</html>
