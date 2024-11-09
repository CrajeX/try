<?php
// session_start();
include 'config.php'; // Database connection
include 'navbar.php';

// Ensure the user is logged in and has an ID in session
$userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
if (!$userId) {
    echo "User ID not found. Please log in again.";
    exit();
}

// Fetch the applicant’s scores from the submissions table
$submissionsStmt = $pdo->prepare("SELECT htmlScore, cssScore, jsScore FROM submissions WHERE applicant_id = ?");
$submissionsStmt->execute([$userId]);
$submissions = $submissionsStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate the applicant’s average score
$totalScores = 0;
$totalCount = 0;

foreach ($submissions as $submission) {
    $totalScores += $submission['htmlScore'] + $submission['cssScore'] + $submission['jsScore'];
    $totalCount += 3;
}

$applicantAverageScore = $totalCount > 0 ? round($totalScores / $totalCount, 2) : 0;
echo "<script>console.log('Applicant Average Score: $applicantAverageScore');</script>";

// Fetch all jobs
$jobStmt = $pdo->prepare("SELECT * FROM jobs");
$jobStmt->execute();
$jobs = $jobStmt->fetchAll(PDO::FETCH_ASSOC);

// Separate jobs into Highest Matched and All Jobs
$highestMatchedJobs = [];
$allJobs = [];

foreach ($jobs as $job) {
    if ($job['avgScore'] <= $applicantAverageScore) {
        $highestMatchedJobs[] = $job;
    } else {
        $allJobs[] = $job;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Search</title>
</head>
<body>
    <h3>Job Listings</h3>

    <!-- Highest Matched Jobs Section -->
    <?php if (count($highestMatchedJobs) > 0): ?>
        <h4>Highest Matched Jobs</h4>
        <ul>
            <?php foreach ($highestMatchedJobs as $job): ?>
                <li>
                    <h4><?= htmlspecialchars($job['title']) ?></h4>
                    <p>Company: <?= htmlspecialchars($job['companyName']) ?></p>
                    <p>Description: <?= htmlspecialchars($job['description']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No highest matched jobs found.</p>
    <?php endif; ?>

    <!-- All Jobs Section -->
    <h4>All Jobs</h4>
    <ul>
        <?php foreach ($allJobs as $job): ?>
            <li>
                <h4><?= htmlspecialchars($job['title']) ?></h4>
                <p>Company: <?= htmlspecialchars($job['companyName']) ?></p>
                <p>Description: <?= htmlspecialchars($job['description']) ?></p>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if (count($allJobs) === 0): ?>
        <p>No job listings found.</p>
    <?php endif; ?>
</body>
</html>
