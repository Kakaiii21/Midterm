<?php 
session_start();

// Guard function to check if the user is authenticated
function guard() {
    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        // Redirect to the login page if the user is not authenticated
        header('Location: ../index.php');  // Adjust the path if necessary
        exit;
    }
}

// Call the guard function to ensure the user is authenticated
guard();
// Handle subject addition
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject = $_POST['subject'] ?? '';

    if (!empty($subject)) {
        if (!isset($_SESSION['subjects'])) {
            $_SESSION['subjects'] = [];
        }

        // Ensure subject isn't duplicated
        if (!in_array($subject, $_SESSION['subjects'])) {
            $_SESSION['subjects'][] = htmlspecialchars($subject);
        }
    }
}

// Initialize errors array
$errors = [];

// Handle form submission for adding a new subject
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject_name = $_POST['subject_name'] ?? '';
    $subject_code = $_POST['subject_code'] ?? '';

    // Check for missing fields
    if (empty($subject_name)) {
        $errors[] = "Subject Name is required.";
    }
    if (empty($subject_code)) {
        $errors[] = "Subject Code is required.";
    }

    // Check for existing subject code
    if (!empty($subject_code)) {
        // Initialize subjects array if it's not already initialized
        if (!isset($_SESSION['subjects'])) {
            $_SESSION['subjects'] = [];
        }

        // Check if the subject code already exists in the session
        foreach ($_SESSION['subjects'] as $subject) {
            if ($subject['subject_code'] === $subject_code) {
                $errors[] = "Subject Code '$subject_code' already exists.";
            }
        }
    }

    // If no errors, proceed with adding the subject
    if (empty($errors)) {
        $_SESSION['subjects'][] = [
            'subject_name' => htmlspecialchars($subject_name),
            'subject_code' => htmlspecialchars($subject_code)
        ];

        // Redirect to the subject listing page
        header('Location: ' . $_SERVER['PHP_SELF']);  // Refresh page to show updated list
        exit;
    }
}

// Function to display errors as a dismissible alert container
function displayErrors($errors) {
    if (!empty($errors)) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        echo '<strong>System Errors:</strong>';
        echo '<ul>';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        echo '<span aria-hidden="true">&times;</span>';
        echo '</button>';
        echo '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .card-container {
            margin-top: 30px;
        }
        .input-container {
            border: 1px solid #ccc; /* Thin border */
            padding: 20px;
            border-radius: 5px;
        }
        .table-container {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
    <h1>Add New Subject</h1>
        <!-- Breadcrumb navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
            </ol>
        </nav>


        <!-- Display Error Messages -->
        <?php displayErrors($errors); ?>

        <!-- Subject Add Form Card -->
        <div class="card card-container">
            <div class="card-body">
                <h5 class="card-title">Subject Information</h5>
                <form method="POST" action="">
                <div class="form-group">
                        <label for="subject_code">Subject Code:</label>
                        <input type="text" name="subject_code" id="subject_code" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label for="subject_name">Subject Name:</label>
                        <input type="text" name="subject_name" id="subject_name" class="form-control" >
                    </div>

                    

                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </form>
            </div>
        </div>

        <!-- Subject List Table -->
        <div class="table-container mt-4">
            <h2>Subject List</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Subject Name</th>
                        <th>Subject Code</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($_SESSION['subjects'])): ?>
                        <?php foreach ($_SESSION['subjects'] as $index => $subject): ?>
                            <tr>
                                <td><?= htmlspecialchars($subject['subject_code']) ?></td>
                                <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                                <td>
                                     <!-- Edit Button -->
                                     <td>
    <!-- Edit Button -->
    <a href="edit.php?id=<?= urlencode($subject['subject_code']) ?>" class="btn btn-warning btn-sm">Edit</a>

    <!-- Delete Button -->
    <a href="delete.php?id=<?= urlencode($subject['subject_code']) ?>" class="btn btn-danger btn-sm">Delete</a>
</td>
                            
                                  
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
