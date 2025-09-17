<?php
// controllers/registrarPaciente.php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/models_paciente.php';

try {
    // Obtener datos del formulario
    $dni = $_POST['dni'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    // Validar campos requeridos
    if(!$dni || !$nombre || !$correo || !$telefono){
        throw new Exception("Faltan datos requeridos");
    }

    $pacienteModel = new Paciente();

    // Verificar si el DNI ya existe
    $pacienteExistente = $pacienteModel->buscarPorDNI($dni);
    if($pacienteExistente){
        echo json_encode([
            "status" => "exists",
            "mensaje" => "El DNI ya está registrado"
        ]);
        exit;
    }

    // Registrar paciente
    $idPaciente = $pacienteModel->registrarPaciente($dni, $nombre, $correo, $telefono);

    if($idPaciente){
        echo json_encode([
            "status" => "ok",
            "mensaje" => "Paciente registrado exitosamente",
            "id_paciente" => $idPaciente
        ]);
    } else {
        throw new Exception("No se pudo registrar el paciente");
    }

} catch(Exception $e){
    echo json_encode([
        "status" => "error",
        "mensaje" => $e->getMessage()
    ]);
}
