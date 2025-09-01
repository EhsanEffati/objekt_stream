<?php
// models/UserModel.php

class UserModel {
    private $conn;
    private $table_name = "users";

    // Eigenschaften des Benutzers
    public $id;
    public $vorname;
    public $nachname;
    public $email;
    public $password_hash;
    public $created_at;

    /**
     * Konstruktor für das UserModel.
     *
     * @param PDO $db Die Datenbankverbindung.
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Registriert einen neuen Benutzer in der Datenbank.
     *
     * @return bool True bei Erfolg, False bei Misserfolg.
     */
    public function register() {
        // SQL-Abfrage zum Einfügen eines neuen Benutzers
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                      vorname=:vorname,
                      nachname=:nachname,
                      email=:email,
                      password_hash=:password_hash";

        // Abfrage vorbereiten
        $stmt = $this->conn->prepare($query);

        // HTML-Sonderzeichen entfernen und Daten bereinigen
        $this->vorname = htmlspecialchars(strip_tags($this->vorname));
        $this->nachname = htmlspecialchars(strip_tags($this->nachname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        // Der password_hash wird bereits gehasht übergeben

        // Werte binden
        $stmt->bindParam(":vorname", $this->vorname);
        $stmt->bindParam(":nachname", $this->nachname);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password_hash", $this->password_hash);

        // Abfrage ausführen
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Findet einen Benutzer anhand seiner E-Mail-Adresse.
     *
     * @return bool True, wenn der Benutzer gefunden wurde, False sonst.
     */
    public function findByEmail() {
        // SQL-Abfrage zum Abrufen des Benutzers nach E-Mail
        $query = "SELECT id, vorname, nachname, email, password_hash, created_at
                  FROM " . $this->table_name . "
                  WHERE email = ?
                  LIMIT 0,1";

        // Abfrage vorbereiten
        $stmt = $this->conn->prepare($query);

        // E-Mail binden
        $stmt->bindParam(1, $this->email);

        // Abfrage ausführen
        $stmt->execute();

        // Zeile abrufen
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Wenn E-Mail existiert, Eigenschaften zuweisen
        if ($row) {
            $this->id = $row['id'];
            $this->vorname = $row['vorname'];
            $this->nachname = $row['nachname'];
            $this->email = $row['email'];
            $this->password_hash = $row['password_hash'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    /**
     * Überprüft, ob die E-Mail-Adresse bereits in der Datenbank existiert.
     *
     * @return bool True, wenn die E-Mail existiert, False sonst.
     */
    public function emailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>