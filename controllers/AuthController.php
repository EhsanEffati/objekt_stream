<?php
// controllers/AuthController.php

// Session starten, falls noch nicht geschehen
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/Database.php';

class AuthController {
    private $userModel;
    private $db;

    /**
     * Konstruktor für den AuthController.
     */
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new UserModel($this->db);
    }

    /**
     * Zeigt das Registrierungsformular an.
     */
    public function showRegisterForm() {
        require_once __DIR__ . '/../views/register.php';
    }

    /**
     * Verarbeitet die Registrierungsanfrage.
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $vorname = $_POST['vorname'] ?? '';
            $nachname = $_POST['nachname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Server-seitige Validierung
            if (empty($vorname) || empty($nachname) || empty($email) || empty($password) || empty($confirm_password)) {
                $error = "Bitte füllen Sie alle Felder aus.";
                require_once __DIR__ . '/../views/register.php';
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Ungültiges E-Mail-Format.";
                require_once __DIR__ . '/../views/register.php';
                return;
            }

            if (!str_ends_with($email, '@edu.bbq.de')) {
                $error = "E-Mail muss auf '@edu.bbq.de' enden.";
                require_once __DIR__ . '/../views/register.php';
                return;
            }

            if ($password !== $confirm_password) {
                $error = "Passwörter stimmen nicht überein.";
                require_once __DIR__ . '/../views/register.php';
                return;
            }

            if (strlen($password) < 6) {
                $error = "Passwort muss mindestens 6 Zeichen lang sein.";
                require_once __DIR__ . '/../views/register.php';
                return;
            }

            $this->userModel->email = $email;
            if ($this->userModel->emailExists()) {
                $error = "Diese E-Mail-Adresse ist bereits registriert.";
                require_once __DIR__ . '/../views/register.php';
                return;
            }

            // Passwort hashen
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Benutzerdaten setzen
            $this->userModel->vorname = $vorname;
            $this->userModel->nachname = $nachname;
            $this->userModel->email = $email;
            $this->userModel->password_hash = $password_hash;

            // Benutzer registrieren
            if ($this->userModel->register()) {
                $_SESSION['success_message'] = "Registrierung erfolgreich! Bitte melden Sie sich an.";
                header("Location: index.php?action=login");
                exit();
            } else {
                $error = "Fehler bei der Registrierung. Bitte versuchen Sie es erneut.";
                require_once __DIR__ . '/../views/register.php';
            }
        } else {
            $this->showRegisterForm();
        }
    }

    /**
     * Zeigt das Login-Formular an.
     */
    public function showLoginForm() {
        require_once __DIR__ . '/../views/login.php';
    }

    /**
     * Verarbeitet die Login-Anfrage.
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Server-seitige Validierung
            if (empty($email) || empty($password)) {
                $error = "Bitte geben Sie E-Mail und Passwort ein.";
                require_once __DIR__ . '/../views/login.php';
                return;
            }

            if (!str_ends_with($email, '@edu.bbq.de')) {
                $error = "E-Mail muss auf '@edu.bbq.de' enden.";
                require_once __DIR__ . '/../views/login.php';
                return;
            }

            $this->userModel->email = $email;
            if ($this->userModel->findByEmail()) {
                // Passwort überprüfen
                if (password_verify($password, $this->userModel->password_hash)) {
                    // Login erfolgreich, Session setzen
                    $_SESSION['user_id'] = $this->userModel->id;
                    $_SESSION['user_vorname'] = $this->userModel->vorname;
                    $_SESSION['user_nachname'] = $this->userModel->nachname;
                    $_SESSION['user_email'] = $this->userModel->email;

                    header("Location: index.php?action=stream");
                    exit();
                } else {
                    $error = "Ungültige E-Mail oder Passwort.";
                    require_once __DIR__ . '/../views/login.php';
                }
            } else {
                $error = "Ungültige E-Mail oder Passwort.";
                require_once __DIR__ . '/../views/login.php';
            }
        } else {
            $this->showLoginForm();
        }
    }

    /**
     * Meldet den Benutzer ab.
     */
    public function logout() {
        // Alle Session-Variablen löschen
        $_SESSION = array();

        // Session zerstören
        session_destroy();

        // Zum Login-Formular umleiten
        header("Location: index.php?action=login");
        exit();
    }

    /**
     * Überprüft, ob der Benutzer angemeldet ist.
     *
     * @return bool True, wenn angemeldet, False sonst.
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Zeigt die Stream-Seite an.
     */
    public function showStreamPage() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error_message'] = "Bitte melden Sie sich an, um auf den Stream zuzugreifen.";
            header("Location: index.php?action=login");
            exit();
        }
        require_once __DIR__ . '/../views/stream.php';
    }

    /**
     * Zeigt eine Fehlerseite an.
     *
     * @param string $message Die anzuzeigende Fehlermeldung.
     */
    public function showErrorPage($message = "Ein unbekannter Fehler ist aufgetreten.") {
        $error = $message;
        require_once __DIR__ . '/../views/error.php';
    }
}
?>