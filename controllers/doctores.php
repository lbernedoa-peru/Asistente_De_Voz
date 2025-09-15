
<?php

// controllers/doctores.php
require_once __DIR__ . '/../models/models_doctor.php';

if(isset($_GET['idEspecialidad'])){
    $doctorModel = new Doctor();
    $doctores = $doctorModel->listarPorEspecialidad($_GET['idEspecialidad']);
    echo json_encode($doctores);
}
