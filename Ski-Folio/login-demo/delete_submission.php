<?php
include 'config.php';

// Check if submission ID is provided
if (isset($_POST['submissionId'])) {
    $submissionId = $_POST['submissionId'];

    // Delete the submission from the database
    $stmt = $pdo->prepare("DELETE FROM submissions WHERE id = ?");
    $stmt->execute([$submissionId]);

    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "No submission ID provided"]);
}
?>
