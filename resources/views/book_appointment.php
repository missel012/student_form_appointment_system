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
    <form id="appointment-form" action="/book-appointment" method="POST">
        <!-- CSRF token input field removed -->
        <label for="appointment_for">Appointment For:</label><br>
        <input type="text" id="appointment_for" name="appointment_for" required><br><br>
        
        <label for="appointment_time">Appointment Time:</label><br>
        <input type="datetime-local" id="appointment_time" name="appointment_time" required><br><br>
        
        <button type="submit" id="submit-btn">Book Appointment</button>
    </form>

    <div id="message-container"></div>

    <?php
    // Display response message
    if (isset($_SESSION['message'])) {
        echo "<script>showMessage('" . $_SESSION['message'] . "');</script>";
        unset($_SESSION['message']);
    }

    if (isset($_SESSION['error'])) {
        echo "<script>showMessage('" . $_SESSION['error'] . "', true);</script>";
        unset($_SESSION['error']);
    }

    if (isset($events) && count($events) > 0) {
        echo '<h2>Upcoming Events</h2>';
        echo '<ul>';
        foreach ($events as $event) {
            echo '<li>';
            echo '<strong>' . htmlspecialchars($event->getSummary()) . '</strong><br>';
            echo 'Start: ' . htmlspecialchars($event->getStart()->getDateTime()) . '<br>';
            echo 'End: ' . htmlspecialchars($event->getEnd()->getDateTime()) . '<br>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No events found or credentials missing.</p>';
    }
    ?>
</body>
</html>
