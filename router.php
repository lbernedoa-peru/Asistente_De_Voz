<?php
// router.php
header("Content-Type: application/json; charset=utf-8");

try {
    require_once __DIR__ . '/controllers/ControlAsistente.php';

    // Instanciar dentro del try para capturar excepciones de constructor/modelo
    $control = new ControlAsistente();

    $accion = $_POST['accion'] ?? null;

    if (!$accion) {
        echo json_encode(["error" => "Falta acción"]);
        exit;
    }

    switch ($accion) {
        case "verificarPaciente":
            $dni = $_POST['dni'] ?? '';
            if (empty($dni)) throw new Exception("Falta dni");

            $res = $control->verificarPaciente($dni);

            if ($res) {
                echo json_encode([
                    "id_paciente" => $res["id_paciente"],
                    "nombre" => $res["nombre"],
                    "dni" => $res["dni"],
                    "correo" => $res["correo"],
                    "telefono" => $res["telefono"],
                    "existe" => true
                ]);
            } else {
                echo json_encode([
                    "existe" => false,
                    "mensaje" => "Paciente no encontrado"
                ]);
            }
            break;

        case "registrarPaciente":
            $dni = $_POST['dni'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            if (empty($dni) || empty($nombre)) throw new Exception("Faltan datos para registro");
            $id = $control->registrarPaciente($dni, $nombre, $correo, $telefono);
            echo json_encode(["id_paciente" => $id]);
            break;

        case "especialidades":
            $res = $control->listarEspecialidades();
            echo json_encode($res);
            break;

        case "doctores":
            $id = $_POST['id_especialidad'] ?? '';
            if (empty($id)) throw new Exception("Falta id_especialidad");
            $res = $control->listarDoctoresPorEspecialidad($id);
            echo json_encode($res);
            break;

        case "agendarCita":
            $id_paciente = $_POST['id_paciente'] ?? '';
            $id_doctor = $_POST['id_doctor'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $hora = $_POST['hora'] ?? '';
            if (empty($id_paciente) || empty($id_doctor) || empty($fecha) || empty($hora)) throw new Exception("Faltan datos para agendar");
            $id = $control->agendarCita($id_paciente, $id_doctor, $fecha, $hora);
            echo json_encode(["id_cita" => $id]);
            break;

        default:
            echo json_encode(["error" => "Acción no reconocida"]);
    }
} catch (Exception $e) {
    // siempre devolvemos JSON válido con el error
    echo json_encode(["error" => $e->getMessage()]);
}
