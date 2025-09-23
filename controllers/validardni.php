// archivo: validar_dni.php
<?php
require_once "conexion.php"; // tu conexión a BD

if (isset($_POST['dni'])) {
    $dni = $_POST['dni'];
    $sql = "SELECT COUNT(*) as total FROM pacientes WHERE dni = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    echo json_encode(["existe" => $result['total'] > 0]);
}
?>
