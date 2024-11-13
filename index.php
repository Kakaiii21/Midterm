<?php
session_start();

// Redirect to dashboard if user is already logged in
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check for empty fields and display appropriate error
    if (empty($email) && empty($password)) {
        $error = "System errors: email is required, password is required.";
    } elseif (empty($email)) {
        $error = "System errors: email is required.";
    } elseif (empty($password)) {
        $error = "System errors: password is required.";
    } else {
        // Assume this function checks credentials in your database
        if (authenticateUser($email, $password)) {
            // Set session for authenticated user
            $_SESSION['authenticated'] = true;
            $_SESSION['user_email'] = $email;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "System errors: Invalid email or password.";
        }
    }
}

function authenticateUser($email, $password) {
    // Simulated user data, replace this with actual database validation
    $validUsers = [
        'user1@gmail.com' => 'password',
        'user2@gmail.com' => 'password',
        'user3@gmail.com' => 'password',
        'user4@gmail.com' => 'password',
        'user5@gmail.com' => 'password',
    ];

    // Check if the entered email exists and the password matches
    return isset($validUsers[$email]) && $validUsers[$email] === $password;
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  </head>
  <body>
    <div class="container">
      <div class="login_con" style="margin-top: 50px; text-align: center;">
        <h2>Login</h2>
      </div>

      <!-- Display dismissable error box if there's an error -->
      <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong><?= htmlspecialchars($error) ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>

      <!-- Login Form -->
      <form class="form" method="POST" action="" style="margin-top: 20px;">
        <div class="form-group">
          <label for="email">Email address</label>
          <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" >
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" class="form-control" id="password" placeholder="Password" >
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
      </form>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
