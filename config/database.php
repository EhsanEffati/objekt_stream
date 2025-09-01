<?php
// config/Database.php

class Database {
    private $host = 'localhost'; // Ihr Datenbank-Host
    private $db_name = 'stream'; // Ihr Datenbankname
    private $username = 'root'; // Ihr Datenbank-Benutzername
    private $password = ''; // Ihr Datenbank-Passwort
    public $conn;

    /**
     * Stellt eine Datenbankverbindung her.
     *
     * @return PDO|null Die PDO-Verbindung oder null im Fehlerfall.
     */
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Fehlerbehandlung aktivieren
            $this->conn->exec("set names utf8"); // Zeichensatz auf UTF-8 setzen
        } catch(PDOException $exception) {
            echo "Verbindungsfehler: " . $exception->getMessage();
            // Im Produktionsbetrieb sollten Sie dies in ein Logfile schreiben, nicht direkt ausgeben.
        }
        return $this->conn;
    }
}
?>