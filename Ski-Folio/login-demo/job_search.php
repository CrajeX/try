<?php
session_start();
include 'config.php'; // Database connection
include 'navbar.php'; // Include navbar after defining email and userType

// Ensure the user is an applicant
if (!isset($_SESSION['user']) || $_SESSION['user']['userType'] !== 'applicant') {
    header("Location: login.php");
    exit();
}

// Fetch posted jobs
$stmt = $pdo->query("SELECT job_title, company_name, description, avg_score FROM jobs");
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch applicant's portfolio submissions to calculate average score
$applicantId = $_SESSION['user']['id'];
$submissionStmt = $pdo->prepare("SELECT htmlScore, cssScore, jsScore FROM submissions WHERE applicant_id = ?");
$submissionStmt->execute([$applicantId]);
$submissions = $submissionStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate average scores across submissions
$totalHtmlScore = $totalCssScore = $totalJsScore = 0;
$totalSubmissions = count($submissions);

if ($totalSubmissions > 0) {
    foreach ($submissions as $submission) {
        $totalHtmlScore += $submission['htmlScore'];
        $totalCssScore += $submission['cssScore'];
        $totalJsScore += $submission['jsScore'];
    }

    // Divide the summed scores by the number of submissions, then divide by 3
    $averageScore = round((($totalHtmlScore + $totalCssScore + $totalJsScore) / $totalSubmissions) / 3, 2);
} else {
    $averageScore = 0; // No submissions found
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Search</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h3>Available Jobs</h3>

    <?php if (!empty($jobs)): ?>
        <ul>
            <?php foreach ($jobs as $job): ?>
                <li>
                    <h4><?= htmlspecialchars($job['job_title']) ?> at <?= htmlspecialchars($job['company_name']) ?></h4>
                    <p><?= htmlspecialchars($job['description']) ?></p>
                    <!-- Log the required average score to the console -->
                    <script>
                        console.log("Job: <?= htmlspecialchars($job['job_title']) ?> - Required Score: <?= $job['avg_score'] ?>");
                    </script>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No jobs found.</p>
    <?php endif; ?>

    <h4>Your Portfolio Average Score</h4>
    <p>Calculated Average Score: <?= $averageScore ?>%</p>
</div>
</body>
</html>
