<?php
// session_start();
include 'config.php'; // Ensure this file sets up your database connection
include 'navbar.php';
// Ensure only employers can access this page
if (!isset($_SESSION['user']) || $_SESSION['user']['userType'] !== 'employer') {
    header("Location: signin.php");
    exit();
}

$userEmail = $_SESSION['user']['email'];
$stmt = $pdo->prepare("SELECT * FROM employers WHERE email = ?");
$stmt->execute([$userEmail]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$companyName = $user['companyName'] ?? '';
$error = $success = "";

// Handle job posting form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $avgScore = $_POST['avgScore'];
    
    // Insert job posting into the database
    $stmt = $pdo->prepare("INSERT INTO jobs (title, companyName, description, avgScore, employer_email) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$title, $companyName, $description, $avgScore, $userEmail])) {
        $success = "Job posted successfully!";
    } else {
        $error = "Error posting job.";
    }
}

// Fetch all jobs posted by this employer
$jobStmt = $pdo->prepare("SELECT * FROM jobs WHERE employer_email = ?");
$jobStmt->execute([$userEmail]);
$jobs = $jobStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employer Profile - Job Listings</title>
    <style>
        /* Main styling for the page */
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        
        /* Button to open the modal */
        .open-modal-btn {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* The modal container */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Modal content box */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            position: relative;
        }

        /* Close button on the modal */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover, .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        /* Job card styling */
        .job-card {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
        
        .modal-content form input[type="text"], 
        .modal-content form input[type="number"], 
        .modal-content form textarea {
            width:95%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }

        /* Submit button inside modal */
        .submit-btn {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <h1>Employer Profile - Job Listings</h1>

    <?php
    if ($error) echo "<p style='color: red;'>$error</p>";
    if ($success) echo "<p style='color: green;'>$success</p>";
    ?>

    <!-- Button to open the modal for adding a new job -->
    <button class="open-modal-btn" onclick="openModal()">Post a Job</button>

    <!-- Modal structure -->
    <div id="jobModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Post a New Job</h2>
            <form method="POST" action="employer_profile.php">
                <label>Job Title:</label>
                <input type="text" name="title" required>

                <label>Company Name:</label>
                <input type="text" name="companyName" value="<?php echo htmlspecialchars($companyName); ?>" readonly>

                <label>Job Description:</label>
                <textarea name="description" rows="4" required></textarea>

                <label>Desired Average Score:</label>
                <input type="number" name="avgScore" step="0.1" required>

                <button type="submit" class="submit-btn">Submit Job</button>
            </form>
        </div>
    </div>

    <!-- Displaying all posted jobs -->
    <h2>Your Job Postings</h2>
    <?php if (count($jobs) > 0): ?>
        <div class="job-listings">
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <h4><?= htmlspecialchars($job['title']) ?></h4>
                    <p><strong>Company:</strong> <?= htmlspecialchars($job['companyName']) ?></p>
                    <p><strong>Description:</strong> <?= htmlspecialchars($job['description']) ?></p>
                    <p><strong>Desired Average Score:</strong> <?= htmlspecialchars($job['avgScore']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No jobs posted yet.</p>
    <?php endif; ?>

    <script>
        // Function to open the modal
        function openModal() {
            document.getElementById("jobModal").style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById("jobModal").style.display = "none";
        }

        // Close modal when clicking outside of the modal content
        window.onclick = function(event) {
            let modal = document.getElementById("jobModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
