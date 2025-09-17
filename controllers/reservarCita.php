<?php
// controllers/reservarCita.php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/models_paciente.php';
require_once __DIR__ . '/../models/models_cita.php';

try {
    // Obtener datos del formulario
    $dni = $_POST['dni'] ?? '';
    $idEspecialidad = $_POST['especialidad'] ?? '';
    $idDoctor = $_POST['doctor'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';

    // Validar que todos los campos estén completos
    if (!$dni || !$idEspecialidad || !$idDoctor || !$fecha || !$hora) {
        throw new Exception("Faltan datos requeridos");
    }

    $pacienteModel = new Paciente();
    $citaModel = new Cita();

    // Verificar si el DNI existe en la base de datos
    $paciente = $pacienteModel->buscarPorDNI($dni);

    if (!$paciente) {
        // Usuario no encontrado
        echo json_encode([
            "status" => "no_user",
            "mensaje" => "Usuario no encontrado"
        ]);
        exit;
    }

    // Registrar la cita usando los parámetros correctos
    $idCita = $citaModel->registrarCita(
        $paciente['id_paciente'],
        $idDoctor,
        $fecha,
        $hora
    );

    if ($idCita) {
        echo json_encode([
            "status" => "ok",
            "mensaje" => "Cita registrada exitosamente",
            "id_cita" => $idCita
        ]);
    } else {
        throw new Exception("No se pudo registrar la cita");
    }

} catch(Exception $e) {
    // Retornar error en formato JSON
    echo json_encode([
        "status" => "error",
        "mensaje" => $e->getMessage()
    ]);
}
