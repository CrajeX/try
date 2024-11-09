<?php
session_start();
session_destroy(); // Destroy the session
header("Location: Entrywindow.php"); // Redirect to auth page
exit();
