<?php
// views/login.php

// Session starten, falls noch nicht geschehen
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Anmelden</h2>

    <?php
    // Erfolgs- und Fehlermeldungen anzeigen
    if (isset($_SESSION['success_message'])) {
        echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">';
        echo '<strong class="font-bold">Erfolg!</strong>';
        echo '<span class="block sm:inline"> ' . htmlspecialchars($_SESSION['success_message']) . '</span>';
        echo '</div>';
        unset($_SESSION['success_message']); // Nachricht nach Anzeige löschen
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">';
        echo '<strong class="font-bold">Fehler!</strong>';
        echo '<span class="block sm:inline"> ' . htmlspecialchars($_SESSION['error_message']) . '</span>';
        echo '</div>';
        unset($_SESSION['error_message']); // Nachricht nach Anzeige löschen
    }
    if (isset($error)) {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">';
        echo '<strong class="font-bold">Fehler!</strong>';
        echo '<span class="block sm:inline"> ' . htmlspecialchars($error) . '</span>';
        echo '</div>';
    }
    ?>

    <form action="index.php?action=login" method="POST" class="space-y-4">
        <div>
            <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">E-Mail:</label>
            <input type="email" id="email" name="email" required
                   class="shadow-sm appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <p id="email-error" class="text-red-500 text-xs italic mt-1"></p>
        </div>
        <div>
            <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Passwort:</label>
            <input type="password" id="password" name="password" required
                   class="shadow-sm appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-300 ease-in-out">
            Anmelden
        </button>
    </form>
    <p class="text-center text-gray-600 text-sm mt-4">
        Noch nicht registriert? <a href="index.php?action=register" class="text-blue-600 hover:underline">Hier registrieren</a>
    </p>
</div>
<script src="public/js/validation.js"></script>
</body>
</html>
