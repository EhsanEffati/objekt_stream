<?php
// controllers/StreamController.php

class StreamController {
    public function showStreamPage() {
        // Check if user is logged in
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error'] = "برای دسترسی به این صفحه باید وارد شوید."; // You must be logged in to access this page.
            header("Location: /login");
            exit();
        }
        require_once 'views/stream.php';
    }
}

?>