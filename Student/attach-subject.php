<?php
session_start();
require_once('../functions.php'); // Include the functions file for validation and handling

// Validate session
guard();

// Initialize error and confirmation messages
$errors = [];
$confirmationMessage = '';

// Fetch student details from the query string
$studentID = $_GET['studentID'] ?? '';
$firstName = $_GET['firstName'] ?? '';
$lastName = $_GET['lastName'] ?? '';

// Handle form submission for attaching subjects
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedSubjects = $_POST['subjects'] ?? [];

    if (empty($selectedSubjects)) {
        $errors[] = "At least one subject must be selected.";
    }

    // Find the student and attach selected subjects
    if (empty($errors)) {
        $studentFound = false;
        foreach ($_SESSION['students'] as &$student) {
            if ($student['studentID'] === $studentID) {
                // Merge the selected subjects to the student's current subjects
                $student['subjects'] = array_merge($student['subjects'] ?? [], $selectedSubjects);
                $confirmationMessage = "Subjects successfully attached to the student.";
                $studentFound = true;
                break;
            }
        }

        if (!$studentFound) {
            $errors[] = "Student with ID $studentID not found.";
        }
    }
}

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

}}
$subjectsAttached = [
    ['id' => 'S1', 'name' => 'Math'],
    ['id' => 'S2', 'name' => 'Science']
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attach Subjects to Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Attach Subjects to Student</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="add.php">Register Students</a></li>
                <li class="breadcrumb-item active" aria-current="page">Attach Subjects</li>
            </ol>
        </nav>

        <?php displayErrors($errors); ?>

        <?php if ($confirmationMessage): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($confirmationMessage) ?>
            </div>
        <?php endif; ?>

        <div class="student-info">
            <h2>Student Information</h2>
            <p><strong>Student ID:</strong> <?= htmlspecialchars($studentID) ?></p>
            <p><strong>First Name:</strong> <?= htmlspecialchars($firstName) ?></p>
            <p><strong>Last Name:</strong> <?= htmlspecialchars($lastName) ?></p>

            <!-- Attach Subjects Form -->
            <form method="POST" action="">
                <!-- Display Subject Checkboxes -->
                <div class="subject-list mt-3">
                    <h3>Select Subjects</h3>
                    <?php
                    // Assuming $_SESSION['subjects'] is an array with subject ID and name (ID - Name format)
                    if (isset($_SESSION['subjects']) && !empty($_SESSION['subjects'])):
                        foreach ($_SESSION['subjects'] as $subject):
                            if (is_array($subject) && isset($subject['id']) && isset($subject['name'])): ?>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="subjects[]" value="<?= $subject['id'] ?>" id="subject-<?= $subject['id'] ?>">
                                    <label class="form-check-label" for="subject-<?= $subject['id'] ?>"><?= htmlspecialchars($subject['id']) ?> - <?= htmlspecialchars($subject['name']) ?></label>
                                </div>
                            <?php endif;
                        endforeach;
                    else: ?>
                        <p>No subjects available. Please add subjects first.</p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary mt-3 btn-attach-subjects">Attach Subjects</button>
            </form>
        </div>

        <!-- Attached Subjects Table -->
       <!-- Attached Subjects Table -->
<!-- Attached Subjects Table -->
<div class="student-info mt-4">
    <h3>Attached Subjects</h3>
    <?php
        // Check if student has any subjects attached
        $subjectsAttached = [];
        foreach ($_SESSION['students'] as $student) {
            if ($student['studentID'] === $studentID && isset($student['subjects'])) {
                $subjectsAttached = $student['subjects'];
                break;
            }
        }
        
        // If there are subjects attached, display them in a table
        if (!empty($subjectsAttached)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Subject ID</th>
                        <th>Subject Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjectsAttached as $subject):
                        // Ensure that $subject is an array and has 'id' and 'name'
                        if (is_array($subject) && isset($subject['id']) && isset($subject['name'])): ?>
                            <tr>
                                <td><?= htmlspecialchars($subject['id']) ?></td>
                                <td><?= htmlspecialchars($subject['name']) ?></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="2">Invalid subject data</td>
                            </tr>
                        <?php endif;
                    endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No subjects attached yet.</p>
        <?php endif; ?>
</div>



    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
