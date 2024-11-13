<?php
session_start();

// Find the student to edit based on the `id` query parameter
$studentID = $_GET['id'] ?? '';
$student = null;

// Search for the student in the session
foreach ($_SESSION['students'] as $index => $stud) {
    if ($stud['studentID'] === $studentID) {
        $student = &$stud;
        $student_index = $index;
        break;
    }
}

// Initialize an error message array
$error_messages = [];

// Handle form submission to update the student
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updated_studentID = $_POST['studentID'];
    $updated_firstName = $_POST['firstName'];
    $updated_lastName = $_POST['lastName'];

    // Check if the fields are empty and add specific error messages
    if (empty($updated_studentID)) {
        $error_messages[] = "Student ID is required";
    }
    if (empty($updated_firstName)) {
        $error_messages[] = "First Name is required";
    }
    if (empty($updated_lastName)) {
        $error_messages[] = "Last Name is required";
    }

    // Display the errors or proceed with updating the student
    if (empty($error_messages)) {
        // Update the student in the session
        $_SESSION['students'][$student_index] = [
            'studentID' => $updated_studentID,
            'firstName' => $updated_firstName,
            'lastName' => $updated_lastName
        ];
        $_SESSION['success_message'] = "Student updated successfully!";
        
        // Redirect to the 'add.php' page after updating
        header("Location: add.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Student</h1>

        <!-- Display dismissible error message if there are errors -->
        <?php if (!empty($error_messages)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>System Errors:</strong>
                <ul>
                    <?php foreach ($error_messages as $message): ?>
                        <li><?= htmlspecialchars($message) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Breadcrumb navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="add.php">Add Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
            </ol>
        </nav>

        <form method="POST" action="">
            <div class="form-group">
                <label for="studentID">Student ID:</label>
                <input type="text" name="studentID" id="studentID" class="form-control" 
                    value="<?= !empty($error_messages) ? '' : htmlspecialchars($student['studentID'] ?? '') ?>" >
            </div>
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" id="firstName" class="form-control" 
                    value="<?= !empty($error_messages) ? '' : htmlspecialchars($student['firstName'] ?? '') ?>" >
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName" id="lastName" class="form-control" 
                    value="<?= !empty($error_messages) ? '' : htmlspecialchars($student['lastName'] ?? '') ?>" >
            </div>
            <button type="submit" class="btn btn-primary">Update Student</button>
            <a href="add.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
