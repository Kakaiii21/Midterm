<?php
session_start();
require_once('../functions.php');

// Validate session
guard();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $subject_code = $_POST['subject_code'];

    // Detach the subject from the student
    if (($key = array_search($subject_code, $_SESSION['students'][$student_id]['subjects'])) !== false) {
        unset($_SESSION['students'][$student_id]['subjects'][$key]);
    }

    header("Location: student.php"); // Redirect after detaching
}
?>
<form method="POST">
    <label for="student_id">Select Student:</label>
    <select name="student_id" required>
        <!-- Options for students here -->
    </select>

    <label for="subject_code">Select Subject to Detach:</label>
    <select name="subject_code" required>
        <!-- Options for subjects here -->
    </select>

    <button type="submit">Detach Subject</button>
</form>
