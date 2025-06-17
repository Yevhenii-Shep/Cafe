<?php

class ReservationManager {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }

    public function addReservation($data) {
        $stmt = $this->db->prepare("INSERT INTO rezervacia (Meno, Priezviesko, Email, Telefonne_cislo, Datum, table_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['meno'],
            $data['priezviesko'],
            $data['email'],
            $data['telefonne_cislo'],
            $data['datetime'],
            $data['table_id']
        ]);
    }

    public function getReservationsByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM rezervacia WHERE Email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchAll();
    }

    public function deleteReservation($id) {
        $stmt = $this->db->prepare("DELETE FROM rezervacia WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function updateReservation($id, $newDateTime) {
        $stmt = $this->db->prepare("UPDATE rezervacia SET Datum = ? WHERE id = ?");
        $stmt->execute([$newDateTime, $id]);
    }
public function isTableReserved($tableId, $from, $to) {
    $query = "SELECT COUNT(*) FROM rezervacia 
              WHERE table_id = :table_id 
              AND Datum BETWEEN :from AND :to";
    
    $stmt = $this->db->prepare($query);
    $stmt->execute([
        ':table_id' => $tableId,
        ':from' => $from,
        ':to' => $to
    ]);

    return $stmt->fetchColumn() > 0;
}

}
