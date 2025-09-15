<?php
// views/main.php
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Clínica - Página Principal</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f8fbff;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    header {
      background: linear-gradient(to right, #0d6efd, #0a58ca);
      color: #fff;
      padding: 60px 20px;
      text-align: center;
    }
    header h1 {
      font-weight: bold;
      margin-bottom: 15px;
    }
    .features {
      margin-top: 40px;
      margin-bottom: 40px;
    }
    .feature-card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 25px;
      transition: transform 0.2s ease;
    }
    .feature-card:hover {
      transform: translateY(-5px);
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
          <li class="nav-item"><a class="nav-link active" href="main.php"><i class="bi bi-house"></i> Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-mic-fill"></i> Asistente</a></li>
          <li class="nav-item"><a class="nav-link" href="formulario.php"><i class="bi bi-calendar-event"></i> Reserva de citas</a></li>
          <li class="nav-item"><a class="nav-link" href="ayuda.php"><i class="bi bi-info-circle"></i> Ayuda</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Encabezado -->
  <header>
    <h1><i class="bi bi-heart-pulse"></i> Bienvenido a la Clínica</h1>
    <p class="lead">Cuidamos tu salud con la mejor atención médica y tecnológica.</p>
    <a href="formulario.php" class="btn btn-light btn-lg mt-3"><i class="bi bi-calendar-check"></i> Reservar una cita</a>
  </header>

  <!-- Secciones -->
  <div class="container features">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-card text-center">
          <i class="bi bi-mic fs-1 text-primary"></i>
          <h4 class="mt-3">Asistente de voz</h4>
          <p>Reserva tus citas fácilmente hablando con nuestro asistente virtual.</p>
          <a href="index.php" class="btn btn-outline-primary btn-sm"><i class="bi bi-play-circle"></i> Probar</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card text-center">
          <i class="bi bi-laptop fs-1 text-success"></i>
          <h4 class="mt-3">Reserva en línea</h4>
          <p>Completa un formulario con tus datos y elige la fecha y hora que prefieras.</p>
          <a href="formulario.php" class="btn btn-outline-success btn-sm"><i class="bi bi-pencil-square"></i> Ir al formulario</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card text-center">
          <i class="bi bi-info-circle fs-1 text-warning"></i>
          <h4 class="mt-3">Ayuda</h4>
          <p>Consulta las preguntas frecuentes y aprende a usar el sistema.</p>
          <a href="ayuda.php" class="btn btn-outline-warning btn-sm"><i class="bi bi-question-circle"></i> Ver ayuda</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p class="mb-0">© 2025 Clínica | Página Principal</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
