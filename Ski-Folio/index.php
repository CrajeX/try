<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ski Folio</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Sign In</h1>
        <form id="signin-form" method="POST" action="signin.php">
            <input type="email" name="email" placeholder="Email" required autocomplete="email">
            <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
            <button type="submit">Sign In</button>
        </form>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
