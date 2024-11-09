<?php
session_start();
include 'config.php'; // Ensure this file sets up your database connection

// Get the userType from the URL parameter if it's not set in the session
if (isset($_GET['userType'])) {
    $_SESSION['userType'] = $_GET['userType'];
}

// Check and set userType from session
$userType = isset($_SESSION['userType']) ? $_SESSION['userType'] : null;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $userType = $_POST['userType']; // Get userType from the form submission

    if ($userType === 'applicant') {
        $githubLink = $_POST['githubLink']; // GitHub link is required only for applicants

        // Prepare the SQL statement for applicants
        $stmt = $pdo->prepare("INSERT INTO applicants (email, password, name, githubLink, userType) VALUES (?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$email, $password, $name, $githubLink, $userType])) {
            $_SESSION['user'] = [
                'email' => $email,
                'userType' => $userType
                'name' => $user['name'],
                
            ];
            header("Location: applicant_profile.php");
            exit();
        }
    } elseif ($userType === 'employer') {
        // Prepare the SQL statement for employers (no GitHub link)
        $stmt = $pdo->prepare("INSERT INTO employers (email, password, companyName, userType) VALUES (?, ?, ?, ?)");

        if ($stmt->execute([$email, $password, $name, $userType])) {
            $_SESSION['user'] = [
                'email' => $email,
                'userType' => $userType
                'companyName' => $userType === 'employer' ? $user['companyName'] : null
            ];
            header("Location: employer_profile.php");
            exit();
        }
    }
    
    $error = "Error creating account"; // Handle signup failure
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    
    <?php if ($userType === 'applicant'): ?>
        <!-- Applicant Signup Form -->
         <div class="container">
         
            <div id="card">
            <h2>Sign Up</h2>
        <form method="POST" action="signup.php">
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="name" placeholder="Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="githubLink" placeholder="GitHub Link" required>
            <input type="hidden" name="userType" value="applicant">
            <button type="submit">Sign Up as Applicant</button>
        </form>
        <div>
        <h2 style="font-size:1rem;"> Already have an account? </h2>
        <a href="Entrywindow.php" style="text-decoration:none;color:white;">
        <button style="width:200px; height:70px;text-align: center;margin-top:-10px" onclick="document.querySelector('#additionalFields').style.display = 'block';">
       
        Sign Up
        
        </button>
        </a>
        </div>
        </div>
        </div>
    <?php elseif ($userType === 'employer'): ?>
        <!-- Employer Signup Form -->
        <div class="container">
         
         <div id="card">
        <form method="POST" action="signup.php">
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="name" placeholder="Company Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="hidden" name="userType" value="employer">
            <button type="submit">Sign Up as Employer</button>
        </form>
        <div>
        <h2 style="font-size:1rem;"> Already have an account? </h2>
        <a href="Entrywindow.php" style="text-decoration:none;color:white;">
        <button style="width:200px; height:70px;text-align: center;margin-top:-10px" onclick="document.querySelector('#additionalFields').style.display = 'block';">
        Sign Up
        </button>
        </a>
        </div>
    </div>
    </div>
    <?php else: ?>
        <p>Please select a user type to sign up.</p>
    <?php endif; ?>
    
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
