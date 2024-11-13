<?php
session_start();

// Guard function to check if the user is authenticated
function guard() {
    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        header('Location: ../index.php');
        exit;
    }
}

guard();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $studentID = $_POST['studentID'] ?? '';
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';

    if (empty($studentID)) {
        $errors[] = "Student ID is required.";
    }
    if (empty($firstName)) {
        $errors[] = "First Name is required.";
    }
    if (empty($lastName)) {
        $errors[] = "Last Name is required.";
    }

    if (!empty($studentID)) {
        if (!isset($_SESSION['students'])) {
            $_SESSION['students'] = [];
        }

        foreach ($_SESSION['students'] as $student) {
            if ($student['studentID'] === $studentID) {
                $errors[] = "Student ID '$studentID' already exists.";
            }
        }
    }

    if (empty($errors)) {
        $_SESSION['students'][] = [
            'studentID' => htmlspecialchars($studentID),
            'firstName' => htmlspecialchars($firstName),
            'lastName' => htmlspecialchars($lastName)
        ];

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

function displayErrors($errors) {
    if (!empty($errors)) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        echo '<strong>Errors:</strong>';
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
    <title>Add Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .card-container {
            margin-top: 30px;
        }
        .input-container, .table-container {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Add New Student</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Student</li>
            </ol>
        </nav>

        <?php displayErrors($errors); ?>

        <div class="card card-container">
            <div class="card-body">
                <h5 class="card-title">Student Information</h5>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="studentID">Student ID:</label>
                        <input type="text" name="studentID" id="studentID" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" name="firstName" id="firstName" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" name="lastName" id="lastName" class="form-control" >
                    </div>

                    <button type="submit" class="btn btn-primary">Add Student</button>
                </form>
            </div>
        </div>

        <div class="table-container mt-4">
    <h2>Student List</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <?php if (!empty($_SESSION['students'])): ?>
                    <th>Options</th> <!-- Show the Options column only if there are students -->
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
    <?php if (!empty($_SESSION['students'])): ?>
        <?php foreach ($_SESSION['students'] as $student): ?>
            <tr>
                <td><?= isset($student['studentID']) ? htmlspecialchars($student['studentID']) : '' ?></td>
                <td><?= isset($student['firstName']) ? htmlspecialchars($student['firstName']) : '' ?></td>
                <td><?= isset($student['lastName']) ? htmlspecialchars($student['lastName']) : '' ?></td>

                <td>
    <!-- Display buttons only if student details are present -->
    <?php if (!empty($student['studentID']) && !empty($student['firstName']) && !empty($student['lastName'])): ?>
        <a href="edit.php?id=<?= urlencode($student['studentID']) ?>" class="btn btn-info btn-sm">Edit</a>
        <a href="delete.php?id=<?= urlencode($student['studentID']) ?>" class="btn btn-danger btn-sm">Delete</a>
        <!-- Attach Subject button now passes student info via URL -->
        <a href="attach-subject.php?studentID=<?= urlencode($student['studentID']) ?>&firstName=<?= urlencode($student['firstName']) ?>&lastName=<?= urlencode($student['lastName']) ?>" class="btn btn-warning btn-sm">Attach Subject</a>
    <?php endif; ?>
</td>

            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="4">No students found.</td></tr>
    <?php endif; ?>
</tbody>

    </table>
</div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
