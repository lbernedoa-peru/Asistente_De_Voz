<?php
// registro.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Registro de Paciente - Clínica</title>
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
          <li class="nav-item"><a class="nav-link" href="formulario.php"><i class="bi bi-calendar-event"></i> Reserva de citas</a></li>
          <li class="nav-item"><a class="nav-link active" href="registro.php"><i class="bi bi-person-plus"></i> Registro</a></li>
          <li class="nav-item"><a class="nav-link" href="ayuda.php"><i class="bi bi-info-circle"></i> Ayuda</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contenido -->
  <div class="container">
    <div class="container-box">
      <h1 class="mb-3 text-primary"><i class="bi bi-person-plus"></i> Registro de Nuevo Paciente</h1>
      <p class="text-muted">Completa tus datos para poder agendar una cita en la clínica.</p>

      <form id="formRegistro" method="POST" action="../controllers/registrarPaciente.php">
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
        </div>

        <div class="mt-3">
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Registrar</button>
          <a href="formulario.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Volver a agendar cita</a>
        </div>
      </form>
    </div>
  </div>

  <footer>
    <p class="mb-0">© 2025 Clínica | Sistema de Reserva de Citas</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Validación simple de DNI
    document.getElementById("formRegistro").addEventListener("submit", function(e){
      const dni = document.getElementById("dni").value.trim();
      if(!/^\d{8}$/.test(dni)){
        e.preventDefault();
        alert("El DNI debe contener 8 dígitos.");
      }
    });
  </script>
  <script>
document.getElementById("formRegistro").addEventListener("submit", function(e) {
    e.preventDefault(); // Evita que el formulario se envíe de manera tradicional

    const formData = new FormData(this);

    fetch("../controllers/registrarPaciente.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        if(res.status === "ok") {
            // Paciente registrado exitosamente
            alert("✅ " + res.mensaje);
            this.reset(); // Limpia el formulario
            // Opcional: redirigir a agendar cita
            window.location.href = "formulario.php";
        } else if(res.status === "exists") {
            // DNI ya registrado
            alert("⚠️ " + res.mensaje);
        } else {
            alert("❌ Error: " + res.mensaje);
        }
    })
    .catch(err => {
        console.error(err);
        alert("❌ Error al procesar la solicitud");
    });
});
</script>

</body>
</html>
