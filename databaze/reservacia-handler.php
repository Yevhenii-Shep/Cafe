<?php
// Файл: databaze/reservacia-handler.php
require_once 'reservacia.php';

// New reservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meno'])) {
    $data = [
        'meno' => $_POST['meno'] ?? '',
        'priezviesko' => $_POST['priezviesko'] ?? '',
        'email' => $_POST['email'] ?? '',
        'telefonne_cislo' => $_POST['telefonne_cislo'] ?? '',
        'datetime' => $_POST['datetime'] ?? ''
    ];

    // Check for all field been with information
    foreach ($data as $value) {
        if (empty($value)) {
            echo "<script>alert('All fields are required.'); window.history.back();</script>";
            exit;
        }
    }

// Add reservation to database
    try {
        addReservation($data);
        header("Location: ../index.php?email=" . urlencode($data['email']));
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Insert error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        exit;
    }
}

// Delete reservation
if (isset($_GET['delete_id'])) {
    deleteReservation($_GET['delete_id']);
    header("Location: ../index.php?email=" . urlencode($_GET['email']));
    exit;
}

// Change reservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    updateReservation($_POST['update_id'], $_POST['new_datetime']);
    header("Location: ../index.php?email=" . urlencode($_POST['email']));
    exit;
}
?>
