<?php

// session_start(); // Ensure session is started

include 'config.php'; // Database connection
include 'navbar.php'; // Include navbar after defining email and userType

// Ensure user is logged in and session data is available
$userEmail = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : null;
$userType = isset($_SESSION['user']['userType']) ? $_SESSION['user']['userType'] : null;
$userData = null;

// Function to get GitHub repositories for a user
if (!function_exists('fetchUserRepos')) {
    function fetchUserRepos($githubLink) {
        $username = substr($githubLink, strrpos($githubLink, '/') + 1);
        $url = "https://api.github.com/users/{$username}/repos";
        $repos = [];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Agent: PHP",
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            echo "cURL Error: " . curl_error($ch);
            curl_close($ch);
            return [];
        }

        curl_close($ch);
        $repos = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error decoding JSON: " . json_last_error_msg();
            return [];
        }

        return $repos;
    }
}

// Function to check if a repository is owned by the user
if (!function_exists('isRepoOwnedByUser')) {
    function isRepoOwnedByUser($githubLink, $repoName) {
        if (!$githubLink) {
            return false;
        }
        $repos = fetchUserRepos($githubLink);
        foreach ($repos as $repo) {
            if (strcasecmp($repo['name'], $repoName) == 0) {
                return true;
            }
        }
        return false;
    }
}

// Main code starts here

if ($userEmail) {
    $stmt = $pdo->prepare("SELECT * FROM applicants WHERE email = ?");
    $stmt->execute([$userEmail]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Check if $userData is null or missing githubLink
if ($userData && isset($userData['githubLink'])) {
    $githubLink = $userData['githubLink'];
} else {
    $githubLink = null;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['liveDemoLink'])) {
    $liveDemoLink = rtrim($_POST['liveDemoLink'], '/'); // Trim trailing slashes

    $repoName = basename($liveDemoLink);

    // Check if $githubLink is available before calling the function
    if (!$githubLink || !isRepoOwnedByUser($githubLink, $repoName)) {
        echo "<script>
                alert('The repository does not belong to your GitHub account or GitHub link is not available.');
                window.location.href = 'portfolio.php'; // Redirect to portfolio page after alert
              </script>";
        exit();
    }

    // Check if this liveDemoLink is already submitted by this user
    $duplicateCheckStmt = $pdo->prepare("SELECT COUNT(*) FROM submissions WHERE applicant_id = ? AND liveDemoLink = ?");
    $duplicateCheckStmt->execute([$userData['id'], $liveDemoLink]);
    $isDuplicate = $duplicateCheckStmt->fetchColumn() > 0;

    if ($isDuplicate) {
        echo "This submission already exists and cannot be added again.";
    } else {
        $htmlScore = rand(60, 100);
        $cssScore = rand(60, 100);
        $jsScore = rand(60, 100);
        $percentage = round(($htmlScore + $cssScore + $jsScore) / 3, 2);

        // Insert the new submission if it's not a duplicate
        $stmt = $pdo->prepare("INSERT INTO submissions (applicant_id, liveDemoLink, htmlScore, cssScore, jsScore, percentage) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userData['id'], $liveDemoLink, $htmlScore, $cssScore, $jsScore, $percentage]);

        echo "Submission added successfully.";
    }
}

// Fetch submissions for the user
$submissions = [];
if ($userData) {
    $submissionsStmt = $pdo->prepare("SELECT * FROM submissions WHERE applicant_id = ?");
    $submissionsStmt->execute([$userData['id']]);
    $submissions = $submissionsStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Calculate average scores
$averageScores = [
    'html' => 0,
    'css' => 0,
    'javascript' => 0,
];
$totalSubmissions = count($submissions);

if ($totalSubmissions > 0) {
    foreach ($submissions as $submission) {
        $averageScores['html'] += $submission['htmlScore'];
        $averageScores['css'] += $submission['cssScore'];
        $averageScores['javascript'] += $submission['jsScore'];
    }

    $averageScores['html'] = round($averageScores['html'] / $totalSubmissions, 2);
    $averageScores['css'] = round($averageScores['css'] / $totalSubmissions, 2);
    $averageScores['javascript'] = round($averageScores['javascript'] / $totalSubmissions, 2);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
</head>
<body>
    <h3>Portfolio</h3>

    <form method="POST">
        <input type="text" name="liveDemoLink" placeholder="Enter Live Demo Link" required>
        <button type="submit">Add Submission</button>
    </form>

    <h4>Submissions</h4>
    <?php if ($totalSubmissions > 0): ?>
        <ul>
            <?php foreach ($submissions as $submission): ?>
                <li>
                    <p>Live Demo Link: <?= htmlspecialchars($submission['liveDemoLink']) ?></p>
                    <p>HTML: <?= $submission['htmlScore'] ?></p>
                    <p>CSS: <?= $submission['cssScore'] ?></p>
                    <p>JavaScript: <?= $submission['jsScore'] ?></p>
                    <p>Percentage: <?= $submission['percentage'] ?>%</p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No submissions found.</p>
    <?php endif; ?>

    <h4>Average Scores</h4>
    <p>HTML: <?= $averageScores['html'] ?>%</p>
    <p>CSS: <?= $averageScores['css'] ?>%</p>
    <p>JavaScript: <?= $averageScores['javascript'] ?>%</p>
</body>
</html>
