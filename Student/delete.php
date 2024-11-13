<?php
session_start();

// Guard function to ensure the user is authenticated
function guard() {
    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        header('Location: ../index.php');  // Redirect if not authenticated
        exit;
    }
}

// Ensure the user is authenticated
guard();

// Sanitize and validate the student ID from the URL
$student_id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;
$student_found = false;
$student_details = [];

// Check if student ID exists and if students session is initialized
if ($student_id && isset($_SESSION['students']) && is_array($_SESSION['students'])) {
    foreach ($_SESSION['students'] as $index => $student) {
        // Check if the student has the studentID key
        if (isset($student['studentID']) && $student['studentID'] === $student_id) {
            $student_found = true;
            $student_details = $student;
            break;
        }
    }
}

// If the student is found and the delete button is clicked, delete the student
if ($student_found && isset($_POST['delete'])) {
    foreach ($_SESSION['students'] as $index => $student) {
        if (isset($student['studentID']) && $student['studentID'] === $student_id) {
            unset($_SESSION['students'][$index]);  // Remove the student
            $_SESSION['students'] = array_values($_SESSION['students']);  // Re-index the array
            header("Location: add.php");  // Redirect back to add.php
            exit;
        }
    }
}

// If no student found, set error message
if (!$student_found) {
    header("Location: add.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Delete Student</h1>

        <!-- Breadcrumb navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="add.php">Add Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
            </ol>
        </nav>

        <!-- Student Details -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Are you sure you want to delete this student?</h5>
                <?php if ($student_found): ?>
                    <p><strong>Student ID:</strong> <?= htmlspecialchars($student_details['studentID']) ?></p>
                    <p><strong>First Name:</strong> <?= htmlspecialchars($student_details['firstName']) ?></p>
                    <p><strong>Last Name:</strong> <?= htmlspecialchars($student_details['lastName']) ?></p>
                <?php else: ?>
                    <p>Student details could not be found.</p>
                <?php endif; ?>

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
