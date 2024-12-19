<?php
// Start the session to access session variables
session_start();

// Remove all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the login page after logging out
header("Location: signin.php");
exit(); // Ensure no further code is executed
?>
