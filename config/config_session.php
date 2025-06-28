<?php
// Set session configuration options
ini_set('session.use_only_cookies', 1); // Use only cookies to store session IDs
ini_set('session.use_strict_mode', 1); // Use strict mode for session IDs

// Set session cookie parameters
session_set_cookie_params([
    'lifetime' => 1800, // Session cookie lifetime in seconds (30 minutes)
    // 'domain' => "localhost", // Domain for the session cookie
    'domain' => ($_SERVER['HTTP_HOST'] === 'localhost' ? 'localhost' : 'thatforum.infinityfreeapp.com'),
    'path' => '/', // Path for the session cookie
    'secure' => true, // Use HTTPS for the session cookie
    'httponly' => true, // Make the session cookie HTTP-only
]);

// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Check if the last session regeneration time is set
    if (!isset($_SESSION['last_regeneration'])) {
        // Regenerate the session ID for the logged-in user
        regenerate_session_id_login();
    } else {
        // Calculate the interval for session regeneration (30 minutes)
        $interval = 60 * 30;
        // Check if the session regeneration interval has passed
        if (time() - $_SESSION["last_regeneration"] >= $interval) {
            // Regenerate the session ID for the logged-in user
            regenerate_session_id_login();
        }
    }
} else {
    // Check if the last session regeneration time is set
    if (!isset($_SESSION["last_regeneration"])) {
        // Regenerate the session ID
        regenerate_session_id();
    } else {
        // Calculate the interval for session regeneration (30 minutes)
        $interval = 60 * 30;
        // Check if the session regeneration interval has passed
        if (time() - $_SESSION["last_regeneration"] >= $interval) {
            // Regenerate the session ID
            regenerate_session_id();
        }
    }
}

// Function to regenerate the session ID
function regenerate_session_id() {
    try {
        // Regenerate the session ID
        session_regenerate_id(true);
        // Update the last session regeneration time
        $_SESSION["last_regeneration"] = time();
    } catch (Exception $e) {
        // Handle session regeneration error
        error_log("Session regeneration failed: " . $e->getMessage());
    }
}

// Function to regenerate the session ID for a logged-in user
function regenerate_session_id_login() {
    try {
        // Regenerate the session ID
        session_regenerate_id(true);
        
        // Get the user ID from the session
        $userId = $_SESSION['user_id'];
        
        // Create a new session ID with the user ID appended
        $newSessionId = session_create_id();
        $sessionId = $newSessionId . "_" . $userId;
        
        // Set the new session ID
        session_id($sessionId);
        
        // Update the last session regeneration time
        $_SESSION["last_regeneration"] = time();
    } catch (Exception $e) {
        // Handle session regeneration error for logged-in user
        error_log("Session regeneration for logged-in user failed: " . $e->getMessage());
    }
}