<?php
session_start();
include '../db.php';

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: ../index.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];

// Retrieve the chat ID if provided, otherwise default to the first chat
$chat_id = isset($_GET['chat_id']) ? intval($_GET['chat_id']) : 0;

// Validate chat ID
if ($chat_id) {
    $stmt = $pdo->prepare("SELECT * FROM chats WHERE id = ? AND (sender_id = ? OR receiver_id = ?)");
    $stmt->execute([$chat_id, $doctor_id, $doctor_id]);
    if (!$stmt->fetch()) {
        $chat_id = 0; // Invalid chat ID
    }
}

// Fetch the list of patients with their profile images
$stmt = $pdo->prepare("SELECT u.id, u.name, u.image FROM users u JOIN patients p ON u.id = p.user_id");
$stmt->execute();
$patients = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Interface</title>
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

        .unread-indicator {
            width: 10px;
            height: 10px;
            background-color: red;
            border-radius: 50%;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .contact-item:hover {
            background-color: #f0f2f5;
        }
        .contact-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        #chat-box {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            background: #f0f2f5;
        }
        #patient-info {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        #patient-info img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        #patient-info strong {
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
            align-self: flex-end; /* Align to right */
            text-align: right; /* Text aligned to right */
        }
        .message.received {
            background-color: #e9ecef;
            align-self: flex-start; /* Align to left */
            text-align: left; /* Text aligned to left */
        }
        .message strong {
            display: block;
            margin-bottom: 5px;
        }
        .message.sent strong {
            display: none; /* Hide sender's name for sent messages */
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
    <a href="doctor_dashboard.php" id="back-button">Back to Dashboard</a>
    <div id="chat-container">
        <div id="contacts">
            <h3>Contacts</h3>
            <?php foreach ($patients as $patient): ?>
                <div class="contact-item" data-id="<?php echo htmlspecialchars($patient['id']); ?>">
                    <img src="<?php echo $patient['image'] ? '../uploads/' . htmlspecialchars($patient['image']) : '../default_images/default.png'; ?>" alt="Profile Image">
                    <?php echo htmlspecialchars($patient['name']); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="chat-box">
            <div id="patient-info">
                <!-- Patient's name and image will be dynamically inserted here -->
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
        let doctorId = <?php echo json_encode($doctor_id); ?>;

        function fetchMessages() {
            if (!chatId) return;

            $.get('fetch_messages.php', { chat_id: chatId }, function(data) {
                const messages = JSON.parse(data);
                $('#messages').empty();
                messages.forEach(message => {
                    const messageClass = message.sender_id === doctorId ? 'sent' : 'received';
                    $('#messages').append(`
                        <div class="message ${messageClass}">
                            ${messageClass === 'received' ? `<strong>${message.sender_name}:</strong>` : ''}
                            <span>${message.message}</span>
                        </div>
                    `);
                });
                $('#messages').scrollTop($('#messages')[0].scrollHeight);
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
                    } else {
                        alert(result.message);  // Show error message if sending failed
                    }
                });
            });

            $('#contacts').on('click', '.contact-item', function() {
                const patientId = $(this).data('id');

                // Create or select a chat with this patient
                $.post('create_or_select_chat.php', { patient_id: patientId }, function(response) {
                    const result = JSON.parse(response);
                    if (result.status === 'success') {
                        chatId = result.chat_id;

                        // Update patient info in #patient-info
                        $('#patient-info').html(`
                            <img src="${result.patient_image}" alt="Patient Image" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 10px;">
                            <span><strong>${result.patient_name}</strong></span>
                        `);

                        // Fetch messages for this chat
                        fetchMessages();
                    } else {
                        alert(result.message);
                    }
                });
            });


        // Fetch messages every 5 seconds to update chat window
        setInterval(fetchMessages, 5000);
        
        // Initial fetch of messages
        fetchMessages();
    </script>
</body>
</html>
