<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'config.php'; // Ensure this file correctly sets up your PDO connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];

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
        if ($password === $user['password']) { // Change this to use password_verify if passwords are hashed

            // Set all necessary session variables
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'userType' => $userType,
                'name' => $user['name'],
                'companyName' =>$user['companyName'],
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
                <button style="width:200px; height:70px;margin-top:-10px">Sign Up</button>
            </a>
        </div>
    </div>
</div>
<script>
    // For debugging, log user data to the console
    const userType = "<?php echo htmlspecialchars($_SESSION['user']['userType'] ?? ''); ?>";
    const userEmail = "<?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?>";
    const name = "<?php echo htmlspecialchars($_SESSION['user']['name'] ?? ''); ?>";
    const companyName = "<?php echo htmlspecialchars($_SESSION['user']['companyName'] ?? ''); ?>";

    console.log("User Type:", userType);
    console.log("User Email:", userEmail);
    console.log("User Name:", name);
    console.log("Company Name:", companyName);
</script>

</body>
</html>
