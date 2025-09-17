<?php
require_once __DIR__ . '/../models/models_doctor.php';

if (isset($_GET['id_especialidad'])) {
    $doctorModel = new Doctor();
    $doctores = $doctorModel->getByEspecialidad($_GET['id_especialidad']);
    header('Content-Type: application/json');
    echo json_encode($doctores);
}
