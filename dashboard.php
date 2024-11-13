<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Redirect to login page if not authenticated
    header('Location: index.php');
    exit;
}

// Handle logout request
if (isset($_GET['logout'])) {
    session_unset();  // Remove all session variables
    session_destroy();  // Destroy the session
    header('Location: index.php');  // Redirect to login page after logout
    exit;
}

// Fetch user's email from the session
$user_email = $_SESSION['user_email'];

// Prevent refreshing or submitting the page from redirecting to login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle form submission logic here (if any)
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
    <style>
      .card-body{
        width: 100%;
        align-self: center;
      }

      /* Adjusting button width */
      .btn-custom {
        width: 100%; /* Make button take full width of the card */
      }
    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  </head>
  <body>
    <div class="container mt-4">
      <div class="d-flex justify-content-between align-items-center">
        <!-- Welcome message with user email -->
        <h1>Welcome to the system, <?= htmlspecialchars($user_email) ?></h1>
        <!-- Logout Button -->
        <a href="?logout=true" class="btn btn-outline-danger">Logout</a>
      </div>

      <div class="mt-4">
        <p class="lead">What would you like to do today?</p>

        <!-- Use Bootstrap grid system to display cards side by side -->
        <div class="row">
          <!-- Card for Add Subject button -->
          <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Add a Subject</h5>
                <p class="card-text">Click below to add a new subject to the system.</p>
                <a href="subject/add.php" class="btn btn-primary btn-custom">Add Subject</a>
              </div>
            </div>
          </div>

          <!-- New Card for Add Student -->
          <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Register a Student</h5>
                <p class="card-text">Click below to add a new student to the system.</p>
                <a href="student/add.php" class="btn btn-primary btn-custom">Register</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
