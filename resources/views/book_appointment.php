<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <script>
        function showMessage(message, isError = false) {
            const messageContainer = document.getElementById('message-container');
            const messageBox = document.createElement('div');
            messageBox.textContent = message;
            messageBox.classList.add(isError ? 'error-message' : 'success-message');
            messageContainer.appendChild(messageBox);
            setTimeout(() => {
                messageBox.remove();
            }, 5000);
        }
    </script>
    <style>
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 10px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Book Appointment</h1>
    <form id="appointment-form" action="{{ route('book-appointment') }}" method="POST">
        <label for="appointment_for">Appointment For:</label><br>
        <input type="text" id="appointment_for" name="appointment_for" required><br><br>
        
        <label for="appointment_time">Appointment Time:</label><br>
        <input type="datetime-local" id="appointment_time" name="appointment_time" required><br><br>
        
        <button type="submit" id="submit-btn">Book Appointment</button>
    </form>

    <div id="message-container"></div>

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Fetch response message from PHP
        $response = json_decode(file_get_contents('php://input'), true);

        // Display response message
        if (isset($response['error'])) {
            echo "<script>showMessage('" . $response['error'] . "', true);</script>";
        } elseif (isset($response['message'])) {
            echo "<script>showMessage('" . $response['message'] . "');</script>";
        }
    }
    ?>
</body>
</html>
