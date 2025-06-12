<?php
// Connect to database
function getPDO() {
    $host = "localhost";
    $dbname = "cafe_db";
    $port = "3306";
    $user = "root";
    $password = "";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        return new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password, $options);
    } catch (PDOException $e) {
        die("Chyba pripojenia: " . $e->getMessage());
    }
}

// Add new reservation
function addReservation($data) {
    $conn = getPDO();

    $stmt = $conn->prepare("INSERT INTO rezervacia (Meno, Priezviesko, Email, telefonne_cislo, Datum)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['meno'],
        $data['priezviesko'],
        $data['email'],
        $data['telefonne_cislo'],
        $data['datetime']
    ]);
}

// Check reservation in database dor email
function getReservationsByEmail($email) {
    $conn = getPDO();
    $stmt = $conn->prepare("SELECT * FROM rezervacia WHERE Email = ? ORDER BY Datum DESC");
    $stmt->execute([$email]);
    return $stmt->fetchAll();
}

// Delete reservation for current ID
function deleteReservation($id) {
    $conn = getPDO();
    $stmt = $conn->prepare("DELETE FROM rezervacia WHERE id = ?");
    $stmt->execute([$id]);
}

// Change reservation
function updateReservation($id, $newDatetime) {
    $conn = getPDO();
    $stmt = $conn->prepare("UPDATE rezervacia SET Datum = ? WHERE id = ?");
    $stmt->execute([$newDatetime, $id]);
}

// Check reservation in database for email
function reservationExists($email) {
    $conn = getPDO();
    $stmt = $conn->prepare("SELECT COUNT(*) FROM rezervacia WHERE Email = ?");
    $stmt->execute([$email]);
    return $stmt->fetchColumn() > 0;
}
?>
