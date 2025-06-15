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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meno'])) {
            $this->createReservation();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
            $this->updateReservation();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
            $this->deleteReservation();
        }
    }

    private function createReservation()
    {
        $data = [
            'meno' => $_POST['meno'] ?? '',
            'priezviesko' => $_POST['priezviesko'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telefonne_cislo' => $_POST['telefonne_cislo'] ?? '',
            'datetime' => $_POST['datetime'] ?? '',
        ];

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
        $id = (int) ($_GET['delete_id'] ?? 0);
        if ($id > 0) {
            $this->manager->deleteReservation($id);
        }
        $email = $_GET['email'] ?? '';
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
