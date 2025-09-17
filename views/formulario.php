<?php
require_once __DIR__ . '/../models/models_especialidad.php';
$especialidadModel = new Especialidad();
$especialidades = $especialidadModel->listarTodas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Reserva de Citas - Clínica</title>
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
          <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-mic-fill"></i> Asistente</a></li>
          <li class="nav-item"><a class="nav-link active" href="formulario.php"><i class="bi bi-calendar-event"></i> Reserva de citas</a></li>
          <li class="nav-item"><a class="nav-link" href="ayuda.php"><i class="bi bi-info-circle"></i> Ayuda</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contenido -->
  <div class="container">
    <div class="container-box">
      <h1 class="mb-3 text-primary"><i class="bi bi-pencil-square"></i> Reserva de Citas</h1>
      <p class="text-muted">Completa el siguiente formulario para registrar tu cita en la clínica.</p>

      <form id="formReserva">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">DNI</label>
            <input id="dni" name="dni" type="text" class="form-control" placeholder="12345678" required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Nombre completo</label>
            <input id="nombre" name="nombre" type="text" class="form-control" placeholder="Juan Perez" required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Correo</label>
            <input id="correo" name="correo" type="email" class="form-control" placeholder="correo@ejemplo.com" required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input id="telefono" name="telefono" type="text" class="form-control" placeholder="999999999" required />
          </div>

          <!-- Especialidad -->
          <div class="col-md-6">
            <label class="form-label">Especialidad</label>
            <select id="especialidad" name="especialidad" class="form-select" required>
              <option value="">Seleccione una especialidad</option>
              <?php foreach($especialidades as $esp): ?>
              <option value="<?= $esp['id_especialidad'] ?>"><?= $esp['nombre'] ?></option>
            <?php endforeach; ?>


            </select>
          </div>

          <!-- Doctor -->
          <div class="col-md-6">
            <label class="form-label">Doctor</label>
            <select id="doctor" name="doctor" class="form-select" required>
              <option value="">Seleccione un doctor</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Fecha</label>
            <input id="fecha" name="fecha" type="date" class="form-control" required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Hora</label>
            <input id="hora" name="hora" type="time" class="form-control" required />
          </div>
        </div>
        <div class="mt-3">
          <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Registrar cita</button>
        </div>
      </form>
    </div>
  </div>

  <footer>
    <p class="mb-0">© 2025 Clínica | Sistema de Reserva de Citas</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById("especialidad").addEventListener("change", function() {
      let idEspecialidad = this.value;
      let doctorSelect = document.getElementById("doctor");
      doctorSelect.innerHTML = "<option value=''>Cargando...</option>";

      if(idEspecialidad){
        fetch("../controllers/doctores.php?id_especialidad=" + idEspecialidad)
          .then(res => res.json())
          .then(data => {
            doctorSelect.innerHTML = "<option value=''>Seleccione un doctor</option>";
            data.forEach(doc => {
              doctorSelect.innerHTML += `<option value="${doc.id_doctor}">${doc.nombre}</option>`;
            });
          })
          .catch(err => {
            doctorSelect.innerHTML = "<option value=''>Error al cargar</option>";
          });
      } else {
        doctorSelect.innerHTML = "<option value=''>Seleccione un doctor</option>";
      }
    });
  </script>
</body>
</html>
