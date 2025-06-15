<?php

class ReservationManager {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }

    public function addReservation($data) {
        $stmt = $this->db->prepare("INSERT INTO reservations (Meno, Priezviesko, Email, Telefonne_cislo, Datum) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['meno'],
            $data['priezviesko'],
            $data['email'],
            $data['telefonne_cislo'],
            $data['datetime']
        ]);
    }

    public function getReservationsByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE Email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchAll();
    }

    public function deleteReservation($id) {
        $stmt = $this->db->prepare("DELETE FROM reservations WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function updateReservation($id, $newDateTime) {
        $stmt = $this->db->prepare("UPDATE reservations SET Datum = ? WHERE id = ?");
        $stmt->execute([$newDateTime, $id]);
    }
}
