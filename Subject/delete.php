<?php
session_start();

// Define the guard function to check if the user is authenticated
function guard() {
    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        header('Location: ../index.php');  // Redirect if not authenticated
        exit;
    }
}

// Call guard function to check authentication
guard();

// Initialize session messages if not already set
if (!isset($_SESSION['error_message'])) {
    $_SESSION['error_message'] = '';
}
if (!isset($_SESSION['success_message'])) {
    $_SESSION['success_message'] = '';
}

// Check if the 'id' parameter exists in the URL
if (isset($_GET['id'])) {
    $subject_code = $_GET['id'];
    $subject_found = false;
    $subject_details = [];

    // Check if the subject exists in the session
    if (isset($_SESSION['subjects']) && !empty($_SESSION['subjects'])) {
        // Loop through the subjects and find the one with the matching subject code
        foreach ($_SESSION['subjects'] as $index => $subject) {
            if ($subject['subject_code'] === $subject_code) {
                $subject_found = true;
                $subject_details = $subject;
                break;
            }
        }
    }

    // If subject is found and the delete button is clicked
    if ($subject_found && isset($_POST['delete'])) {
        // Remove the subject from the array
        foreach ($_SESSION['subjects'] as $index => $subject) {
            if ($subject['subject_code'] === $subject_code) {
                unset($_SESSION['subjects'][$index]);
                $_SESSION['subjects'] = array_values($_SESSION['subjects']);  // Reindex array
                $_SESSION['success_message'] = "Subject deleted successfully!";
                header("Location: add.php");
                exit;
            }
        }
    }

    // If subject wasn't found, set error message
    if (!$subject_found) {
        $_SESSION['error_message'] = "Subject not found!";
        header("Location: add.php");
        exit;
    }
} else {
    // Redirect if no subject ID is provided
    header("Location: add.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Subject</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
    <h1>Delete Subject</h1>

        <!-- Breadcrumb navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Subject</li>
            </ol>
        </nav>


        <!-- Display Error Messages -->
        <?php
        if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>

        <!-- Display Success Message -->
        <?php
        if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        ?>

        <!-- Subject Details -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Are you sure you want to delete this subject?</h5>
                <p><strong>Subject Code:</strong> <?= htmlspecialchars($subject_details['subject_code']) ?></p>
                <p><strong>Subject Name:</strong> <?= htmlspecialchars($subject_details['subject_name']) ?></p>
                
                <!-- Delete Form -->
                <form method="POST">
                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                    <a href="add.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
