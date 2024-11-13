<?php
session_start();

// Find the subject to edit based on the `id` query parameter
$subject_code = $_GET['id'] ?? '';
$subject = null;

// Search for the subject in the session
foreach ($_SESSION['subjects'] as $index => $subj) {
    if ($subj['subject_code'] === $subject_code) {
        $subject = &$subj;
        $subject_index = $index;
        break;
    }
}

// Initialize an error message array
$error_messages = [];

// Handle form submission to update the subject
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updated_subject_code = $_POST['subject_code'];
    $updated_subject_name = $_POST['subject_name'];

    // Check if the fields are empty and add specific error messages
    if (empty($updated_subject_code)) {
        $error_messages[] = "Subject Code is required";
    }
    if (empty($updated_subject_name)) {
        $error_messages[] = "Subject Name is required";
    }

    // Display the errors or proceed with updating the subject
    if (empty($error_messages)) {
        // Update the subject in the session
        $_SESSION['subjects'][$subject_index] = [
            'subject_code' => $updated_subject_code,
            'subject_name' => $updated_subject_name
        ];
        $_SESSION['success_message'] = "Subject updated successfully!";
        
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
    <title>Edit Subject</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Subject</h1>

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
                <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
            </ol>
        </nav>

        <form method="POST" action="">
            <div class="form-group">
                <label for="subject_code">Subject Code:</label>
                <input type="text" name="subject_code" id="subject_code" class="form-control" 
                    value="<?= !empty($error_messages) ? '' : htmlspecialchars($subject['subject_code'] ?? '') ?>" >
            </div>
            <div class="form-group">
                <label for="subject_name">Subject Name:</label>
                <input type="text" name="subject_name" id="subject_name" class="form-control" 
                    value="<?= !empty($error_messages) ? '' : htmlspecialchars($subject['subject_name'] ?? '') ?>" >
            </div>
            <button type="submit" class="btn btn-primary">Update Subject</button>
            <a href="add.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
