<?php
// index.php (Haupt-Router)

// Session starten, falls noch nicht geschehen
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/controllers/AuthController.php';

$controller = new AuthController();

// Standardaktion ist 'login', falls keine Aktion angegeben ist
$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'register':
        $controller->register();
        break;
    case 'login':
        $controller->login();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'stream':
        $controller->showStreamPage();
        break;
    default:
        $controller->showErrorPage("Seite nicht gefunden.");
        break;
}
?>
