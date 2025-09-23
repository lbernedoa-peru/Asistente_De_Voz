<?php
// views/index.php
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Asistente de Voz - Clínica</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
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
      margin-top: 30px;
      margin-bottom: 40px;
    }
    #conversacion {
  background: #f1f7fc;
  padding: 15px;
  border-radius: 6px;
  min-height: 20vh;   /* empieza ocupando 20% de la altura visible */
  max-height: 60vh;   /* nunca sobrepasa el 60% de la altura de la pantalla */
  overflow-y: auto;   /* si hay mucho texto, agrega scroll */
  font-size: 0.95rem;
}
    .mensaje { margin: 6px 0; }
    .asistente { color: #0d6efd; font-weight: 500; }
    .usuario { color: #333; }
    .panel .card {
      cursor: pointer;
      transition: transform 0.2s;
    }
    .panel .card:hover {
      transform: scale(1.05);
      border-color: #0d6efd;
    }
    footer {
      margin-top: auto;
      background: #0d6efd;
      color: #fff;
      text-align: center;
      padding: 15px 10px;
    }
    .hidden { display: none; }

  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#"><i class="bi bi-hospital"></i> Clínica Asistente</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link " href="main.php"><i class="bi bi-house"></i> Inicio</a></li>
          <li class="nav-item"><a class="nav-link active" href="index.php"><i class="bi bi-mic-fill"></i> Asistente</a></li>
          <li class="nav-item"><a class="nav-link" href="formulario.php"><i class="bi bi-calendar-event"></i> Reserva de citas</a></li>
          <li class="nav-item"><a class="nav-link " href="registro.php"><i class="bi bi-person-plus"></i> Registro</a></li>
          <li class="nav-item"><a class="nav-link" href="ayuda.php"><i class="bi bi-info-circle"></i> Ayuda</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contenido -->
  <div class="container">
    <div class="container-box">
      <h1 class="mb-3 text-primary"><i class="bi bi-mic"></i> Asistente de Voz - Clínica</h1>
      <p class="text-muted">Usa el asistente para agendar citas por voz. Si tu navegador bloquea el audio, utiliza los botones o la entrada manual.</p>

      <div id="controls" class="d-flex gap-2 my-3">
        <button id="startBtn" class="btn btn-primary"><i class="bi bi-play-fill"></i> Iniciar asistente</button>
        <button id="resetBtn" class="btn btn-outline-secondary"><i class="bi bi-arrow-repeat"></i> Reiniciar</button>
        <button id="listenBtn" class="btn btn-secondary"><i class="bi bi-mic"></i> Hablar</button>
      </div>

      <div id="conversacion" aria-live="polite"></div>

      <div class="panel row row-cols-1 row-cols-md-3 g-3 mt-3" id="panelOpciones"></div>

      <div id="manualForm" class="hidden mt-4">
        <h3 class="h5 text-primary"><i class="bi bi-pencil-square"></i> Entrada manual (fallback)</h3>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">DNI</label>
            <input id="manualDni" type="text" class="form-control" placeholder="12345678" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Nombre completo</label>
            <input id="manualNombre" type="text" class="form-control" placeholder="Juan Perez" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Correo</label>
            <input id="manualCorreo" type="email" class="form-control" placeholder="correo@ejemplo.com" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input id="manualTelefono" type="text" class="form-control" placeholder="999999999" />
          </div>

        </div>
        <div class="mt-3">
          <button id="manualVerify" class="btn btn-success"><i class="bi bi-check-circle"></i> Verificar / Registrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p class="mb-0">© 2025 Clínica Asistente | Desarrollado con <i class="bi bi-heart-fill text-danger"></i></p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../public/app.js"></script>
</body>
</html>
