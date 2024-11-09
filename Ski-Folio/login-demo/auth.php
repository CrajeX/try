<?php
session_start();
include 'config.php'; // Ensure this file sets up your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and validate the password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['email'];
        header("Location: welcome.php");
        exit();
    } else {
        $error = "Invalid email or password"; // Handle login failure
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
</head>
<body>
    <h2>Sign In</h2>
    <form method="POST" action="auth.php">
        <input type="email" name="email" placeholder="Email" required autocomplete="email">
        <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
        <button type="submit">Sign In</button>
    </form>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
