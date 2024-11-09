<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'config.php'; // Ensure this file correctly sets up your PDO connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = $_POST['userType']; // Get the user type from the form

    // Select the correct table based on user type
    if ($userType === 'applicant') {
        $stmt = $pdo->prepare("SELECT * FROM applicants WHERE email = ?");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM employers WHERE email = ?");
    }

    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and verify password
    if ($user) {
        if ($password === $user['password']) {
            // Store user details in session\
            $_SESSION['user'] = [
                'id' => $user['id'], // Add user ID here
                'email' => $user['email'],
                'userType' => $userType,
            ];

            // Redirect based on user type
            if ($userType === 'applicant') {
                header("Location: applicant_profile.php");
            } else {
                header("Location: employer_profile.php");
            }
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div id="card">
        <h2>Sign In</h2>
        <form method="POST" action="login.php">
            <div>
                <select name="userType" required>
                    <option value="applicant">Applicant</option>
                    <option value="employer">Employer</option>
                </select>
            </div>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>

        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <div>
            <h2 style="font-size:1rem;">Don't have an account?</h2>
            <a href="Entrywindow.php" style="text-decoration:none;">
                <button style="width:200px; height:70px;">Sign Up</button>
            </a>
        </div>
    </div>
</div>
</body>
</html>
