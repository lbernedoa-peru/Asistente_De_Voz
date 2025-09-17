<?php
// controllers/citaController.php
require_once __DIR__ . '/../models/models_especialidad.php';
require_once __DIR__ . '/../models/models_doctor.php';

$especialidadModel = new Especialidad();
$doctorModel = new Doctor();

// Traer todas las especialidades
$especialidades = $especialidadModel->listarTodas();

// Inicializar variables
$especialidadSeleccionada = '';
$doctores = [];

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $especialidadSeleccionada = $_POST['especialidad'] ?? '';
    if ($especialidadSeleccionada) {
        $doctores = $doctorModel->getByEspecialidad($especialidadSeleccionada);
    }
}

// Cargar vista
include __DIR__ . '/../views/formulario.php';
