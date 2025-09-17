<?php
// administrador.php
require_once __DIR__ . '/../core/db.php';

$db = new Database();
$conn = $db->conectar();

// Función para obtener datos de cualquier tabla
function obtenerDatos($conn, $tabla) {
    if ($tabla === 'citas') {
        // JOIN para mostrar nombre del paciente y doctor
        $sql = "SELECT c.id_cita, p.nombre AS paciente, d.nombre AS doctor, c.fecha, c.hora, c.estado
                FROM citas c
                INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
                INNER JOIN doctores d ON c.id_doctor = d.id_doctor
                ORDER BY c.fecha DESC, c.hora DESC";
    } else {
        $sql = "SELECT * FROM $tabla";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Tablas que vamos a mostrar
$tablas = ['pacientes', 'doctores', 'especialidades', 'citas'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Administrador - Clínica</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(to right, #e6f2ff, #f8fbff);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .container-box {
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
      margin-top: 40px;
      margin-bottom: 40px;
    }
    footer {
      margin-top: auto;
      background: #0d6efd;
      color: #fff;
      text-align: center;
      padding: 15px 10px;
    }
    table {
      font-size: 0.9rem;
    }
    th, td {
      vertical-align: middle !important;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#"><i class="bi bi-hospital"></i> Clínica</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="main.php"><i class="bi bi-house"></i> Inicio</a></li>
          <li class="nav-item"><a class="nav-link active" href="administrador.php"><i class="bi bi-gear"></i> Administrador</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contenido -->
  <div class="container">
    <div class="container-box">
      <h1 class="mb-3 text-primary"><i class="bi bi-gear"></i> Panel de Administración</h1>
      <p class="text-muted">Visualiza los registros de la base de datos.</p>

      <div class="accordion" id="accordionTablas">
        <?php foreach($tablas as $i => $tabla): 
          $datos = obtenerDatos($conn, $tabla);
        ?>
        <div class="accordion-item">
          <h2 class="accordion-header" id="heading<?= $i ?>">
            <button class="accordion-button <?= $i != 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>" aria-expanded="<?= $i == 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $i ?>">
              <?= ucfirst($tabla) ?>
            </button>
          </h2>
          <div id="collapse<?= $i ?>" class="accordion-collapse collapse <?= $i == 0 ? 'show' : '' ?>" aria-labelledby="heading<?= $i ?>" data-bs-parent="#accordionTablas">
            <div class="accordion-body">
              <?php if(count($datos) > 0): ?>
              <div class="table-responsive">
                <table class="table table-striped table-bordered">
                  <thead class="table-primary">
                    <tr>
                      <?php foreach(array_keys($datos[0]) as $columna): ?>
                        <th><?= ucfirst($columna) ?></th>
                      <?php endforeach; ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($datos as $fila): ?>
                      <tr>
                        <?php foreach($fila as $valor): ?>
                          <td><?= $valor ?></td>
                        <?php endforeach; ?>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <?php else: ?>
                <p class="text-muted">No hay registros en esta tabla.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>

  <footer>
    <p class="mb-0">© 2025 Clínica | Sistema de Administración</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
