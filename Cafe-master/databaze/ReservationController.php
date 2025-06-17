<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'Database.php';
require_once 'ReservationManager.php';

class ReservationController
{
    private $manager;

    public function __construct(ReservationManager $manager)
    {
        $this->manager = $manager;
    }

    public function handleRequest()
    {
        var_dump($_POST); // Додай сюди
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meno'])) {
            $this->addReservation();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
            $this->updateReservation();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $this->deleteReservation();
        }
    }

    private function addReservation()
    {
        $data = [
            'meno' => $_POST['meno'] ?? '',
            'priezviesko' => $_POST['priezviesko'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telefonne_cislo' => $_POST['telefonne_cislo'] ?? '',
            'datetime' => $_POST['datetime'] ?? '',
             'table_id' => (int) ($_POST['table_id'] ?? 0)
        ];
        $timestamp = strtotime($data['datetime']);
        $oneHourBefore = date('Y-m-d H:i:s', $timestamp - 3600);
        $oneHourAfter  = date('Y-m-d H:i:s', $timestamp + 3600);

        
        if ($this->manager->isTableReserved($data['table_id'], $oneHourBefore, $oneHourAfter)) {
            echo "<script>alert('On this time table already reservated'); window.history.back();</script>";
            exit;
        }

        
        foreach ($data as $value) {
            if (empty($value)) {
                echo "<script>alert('All fields are required'); window.history.back();</script>";
                exit;
            }
        }

        try {
            $this->manager->addReservation($data);
            header("Location: ../index.php?email=" . urlencode($data['email']));
            exit;
        } catch (Exception $e) {
            echo "<script>alert('Insert error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
            exit;
        }
    }

    private function deleteReservation()
    {
         echo "<pre>DELETE REQUEST RECEIVED\n"; // Додай це
        $id = (int) ($_POST['delete_id'] ?? 0);
        echo "ID to delete: $id\n";          // delete this
        if ($id > 0) {
            $this->manager->deleteReservation($id);
             echo "Reservation deleted\n";       // І це
        }
        $email = $_POST['email'] ?? '';
        header("Location: ../index.php?email=" . urlencode($email));
        exit;
    }

    private function updateReservation()
    {
        $this->manager->updateReservation($_POST['update_id'], $_POST['new_datetime']);
        header("Location: ../index.php?email=" . urlencode($_POST['email']));
        exit;
    }
}
$manager = new ReservationManager(new Database());
$controller = new ReservationController($manager);
$controller->handleRequest();
