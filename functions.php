<?php
// Only start session if one isn't already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Guard function to check if the user is authenticated
function guard() {
    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        // Redirect to login page if not authenticated
        header('Location: index.php');
        exit;
    }
}

// Handle logout request
if (isset($_GET['logout'])) {
    session_unset();  // Remove all session variables
    session_destroy();  // Destroy the session
    header('Location: index.php');  // Redirect to login page after logout
    exit;
}

// Fetch user's email from the session (if needed)
$user_email = $_SESSION['user_email'] ?? ''; // Use null coalescing to avoid errors if 'user_email' is not set

// Prevent refreshing or submitting the page from redirecting to login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle form submission logic here (if any)
}

if (!function_exists('displayErrors')) {
    // Function to display errors
    function displayErrors($errors) {
        if (!empty($errors)) {
            echo '<div class="alert alert-danger" role="alert">';
            echo '<strong>Errors:</strong>';
            echo '<ul>';
            foreach ($errors as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }
}
?>

