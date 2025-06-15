<<?php
require_once 'Database.php';
require_once 'ReservationManager.php';
require_once 'ReservationController.php';

$db = new Database();
$pdo = $db->getConnection();
$manager = new ReservationManager($pdo);

$controller = new ReservationController($manager);
$controller->handleRequest();
