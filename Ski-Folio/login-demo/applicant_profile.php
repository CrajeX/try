<?php
// session_start();
include 'config.php'; // Include your database connection setup
include 'navbar.php';

// Check if the user is logged in, if not, redirect to signin page
if (!isset($_SESSION['user'])) {
    header("Location: signin.php");
    exit();
}

// Fetch user data from the database
$userEmail = $_SESSION['user']['email'];
$stmt = $pdo->prepare("SELECT * FROM applicants WHERE email = ?");
$stmt->execute([$userEmail]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Initialize variables
$profilePicURL = $user['profilePicURL'] ?? 'defaultProfilePic.jpg';
$coverPhotoURL = $user['coverPhotoURL'] ?? 'defaultCoverPhoto.jpg';
$name = $user['name'] ?? '';
$certifications = json_decode($user['certifications'], true) ?? ['HTML' => [], 'CSS' => [], 'JavaScript' => []];

// Handle file uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Profile Picture Upload
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/profilePic/';
        $filePath = $uploadDir . basename($_FILES['profilePic']['name']);

        // Create the directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $filePath)) {
            $profilePicURL = $filePath;

            // Update the user's profilePicURL in the database
            $stmt = $pdo->prepare("UPDATE applicants SET profilePicURL = ? WHERE email = ?");
            $stmt->execute([$profilePicURL, $userEmail]);
        } else {
            echo "Error uploading profile picture.";
        }
    }

    // Handle Cover Photo Upload
    if (isset($_FILES['coverPhoto']) && $_FILES['coverPhoto']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/coverPhoto/';
        $filePath = $uploadDir . basename($_FILES['coverPhoto']['name']);

        // Create the directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['coverPhoto']['tmp_name'], $filePath)) {
            $coverPhotoURL = $filePath;

            // Update the user's coverPhotoURL in the database
            $stmt = $pdo->prepare("UPDATE applicants SET coverPhotoURL = ? WHERE email = ?");
            $stmt->execute([$coverPhotoURL, $userEmail]);
        } else {
            echo "Error uploading cover photo.";
        }
    }

    // Handle Certificate Upload
    if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] === UPLOAD_ERR_OK) {
        $selectedSkill = $_POST['selectedSkill']; // Assuming this is sent via the form
        $uploadDir = 'uploads/certifications/' . $selectedSkill . '/';
        $filePath = $uploadDir . basename($_FILES['certificate']['name']);

        // Create the directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['certificate']['tmp_name'], $filePath)) {
            $url = $filePath;

            // Update the certifications in the database
            $certifications[$selectedSkill][] = $url;
            $stmt = $pdo->prepare("UPDATE applicants SET certifications = ? WHERE email = ?");
            $stmt->execute([json_encode($certifications), $userEmail]);
        } else {
            echo "Error uploading certificate.";
        }
    }

    // Redirect after processing uploads
    header("Location: applicant_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Profile</title>
</head>
<body>
    <h2>Applicant Profile</h2>

    <!-- Cover Photo Section -->
    <div id="coverPhotoContainer">
        <img src="<?php echo htmlspecialchars($coverPhotoURL); ?>" alt="Cover Photo" style="width:100%; height:200px; object-fit:cover; cursor:pointer;" onclick="document.getElementById('coverPhotoInput').click();">
        <form method="POST" enctype="multipart/form-data" style="display:none;">
            <input id="coverPhotoInput" type="file" name="coverPhoto" accept="image/*" onchange="this.form.submit();">
        </form>
    </div>

    <!-- Profile Picture and Name -->
    <div id="profileContainer" style="display: flex; align-items: center;">
        <img src="<?php echo htmlspecialchars($profilePicURL); ?>" alt="Profile Picture" style="width:100px; height:100px; border-radius:50%; cursor:pointer;" onclick="document.getElementById('profilePicInput').click();">
        <form method="POST" enctype="multipart/form-data" style="display:none;">
            <input id="profilePicInput" type="file" name="profilePic" accept="image/*" onchange="this.form.submit();">
        </form>
        <div style="margin-left: 20px;">
            <h3><?php echo htmlspecialchars($name); ?></h3>
        </div>
    </div>

    <!-- Certifications Section -->
    <div>
        <h4>Certifications</h4>
        <?php foreach ($certifications as $skill => $urls): ?>
            <div>
                <h5><?php echo htmlspecialchars($skill); ?> Certificates</h5>
                <ul>
                    <?php foreach ($urls as $url): ?>
                        <li><a href="<?php echo htmlspecialchars($url); ?>" target="_blank">View Certificate</a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Upload Certificate Section -->
    <form method="POST" enctype="multipart/form-data">
        <h4>Add a New Certificate</h4>
        <select name="selectedSkill" required>
            <?php foreach (array_keys($certifications) as $skill): ?>
                <option value="<?php echo htmlspecialchars($skill); ?>"><?php echo htmlspecialchars($skill); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="file" name="certificate" accept="image/*,application/pdf" required>
        <button type="submit">Upload Certificate</button>
    </form>
</body>
</html>
