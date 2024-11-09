<?php
session_start();
$userEmail = $_SESSION['user']['email'];
$userType = $_SESSION['user']['userType'] ?? null;
if ($userType === 'applicant') {
    $stmt = $pdo->prepare("SELECT * FROM applicants WHERE email = ?");
} else {
    $stmt = $pdo->prepare("SELECT * FROM employers WHERE email = ?");
}
$stmt->execute([$userEmail]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch session data using null coalescing operator
$userEmail = $_SESSION['user']['email'] ?? null;

$name = $user['name'] ?? '';
$companyname =   $user['companyName'] ?? '';
?>

<nav id="navbar">
    <ul>
       
        <?php if ($userType === 'applicant'): ?>
            <li style="color:white;font-family:monospace;">Hello, <?= htmlspecialchars($userType === 'applicant' ? $name : $companyname) ?></li>
            <li><a href="applicant_profile.php">Profile</a></li>
            <li><a href="search_job.php">Search Jobs</a></li>
            <li><a href="portfolio.php">Portfolio</a></li>
        <?php elseif ($userType === 'employer'): ?>
            <li style="color:white;font-family:monospace;">Welcome, <?= htmlspecialchars($userType === 'applicant' ? $name : $companyname) ?></li>
            <li><a href="employer_profile.php">Dashboard</a></li>
            <li><a href="post_job.php">Post Job</a></li>
        <?php endif; ?>

        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<script>
    // For debugging, log user data to the console
    const userType = "<?php echo htmlspecialchars($userType); ?>";
    const userEmail = "<?php echo htmlspecialchars($userEmail); ?>";
    const name = "<?php echo htmlspecialchars($name); ?>";
    const companyName = "<?php echo htmlspecialchars($companyname ?? ''); ?>";

    console.log("User Type:", userType);
    console.log("User Email:", userEmail);
    console.log("User Name:", name);
    console.log("Company Name:", companyName);
</script>

<style>
/* Basic styles for the navbar */
#navbar ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    background-color: #333;
}
#navbar ul li {
    margin: 0;
    padding: 10px 20px;
}
#navbar ul li a {
    color: white;
    text-decoration: none;
}
</style>
