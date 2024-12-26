<?php
// Helper function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Helper function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// function to check authentification
function requireAuth() {
    session_start();

    // Check if user is authenticated
    if (!isset($_SESSION["user_id"])) {
        redirect("index.php");
    }
}
?>
