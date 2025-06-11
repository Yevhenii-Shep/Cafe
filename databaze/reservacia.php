<?php
$host = "localhost";
$dbname = "cafe_db";
$port = "3306";
$user = "root";
$password = "";

$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
);

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password, $options);
} catch (PDOException $e) {
    die("Chyba pripojenia: " . $e->getMessage());
}

// ziskanie dat z formulara
$meno = $_POST["meno"] ?? '';
$priezviesko = $_POST["priezviesko"] ?? '';
$email = $_POST["email"] ?? '';
$telefon = $_POST["telefonne_cislo"] ?? '';
$datetime = $_POST["datetime"] ?? '';

// kontrola povinných polí
if (empty($meno) || empty($priezviesko) || empty($email) || empty($telefon) || empty($datetime)) {
    echo "<script>
            alert('You must fill in all fields.');
            window.history.back();
          </script>";
    exit;
}

// vloženie dát
$sql = "INSERT INTO rezervacia (Meno, Priezviesko, Email, telefonne_cislo, Datum) 
        VALUES (:meno, :priezviesko, :email, :telefon, :datetime)";
$statement = $conn->prepare($sql);

try {
    $insert = $statement->execute([
        ':meno' => $meno,
        ':priezviesko' => $priezviesko,
        ':email' => $email,
        ':telefon' => $telefon,
        ':datetime' => $datetime
    ]);

    echo "<script>
            alert('We are waiting for you in our cafe');
            window.location.href = document.referrer;
          </script>";
    exit;
} catch (Exception $exception) {
    echo "<script>
            alert('Error with save tour data: " . addslashes($exception->getMessage()) . "');
            window.history.back();
          </script>";
    exit;
}

$conn = null;
?>
