<?php
// views/ayuda.php
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Ayuda - Clínica</title>
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
          <li class="nav-item"><a class="nav-link " href="index.php"><i class="bi bi-mic-fill"></i> Asistente</a></li>
            <li class="nav-item"><a class="nav-link" href="formulario.php"><i class="bi bi-calendar-event"></i> Reserva de citas</a></li>
          <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-info-circle"></i> Ayuda</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contenido -->
  <div class="container">
    <div class="container-box">
      <h1 class="mb-3 text-primary"><i class="bi bi-info-circle"></i> Ayuda</h1>
      <p class="text-muted">Aquí encontrarás información sobre cómo usar el sistema de la clínica.</p>

      <div class="accordion" id="faqAyuda">
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq1">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
              <i class="bi bi-mic me-2"></i> ¿Cómo uso el asistente de voz?
            </button>
          </h2>
          <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAyuda">
            <div class="accordion-body">
              Haz clic en <strong>"Iniciar asistente"</strong> y luego en el botón <i class="bi bi-mic"></i> para hablar. 
              El asistente te guiará paso a paso para reservar tu cita.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faq2">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
              <i class="bi bi-pencil-square me-2"></i> ¿Puedo registrar una cita sin usar voz?
            </button>
          </h2>
          <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAyuda">
            <div class="accordion-body">
              Sí, puedes usar el <strong>formulario de reserva</strong>. Ingresa tu DNI, nombre, correo, teléfono, fecha y hora, 
              luego presiona el botón <em>Registrar cita</em>.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faq3">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
              <i class="bi bi-envelope me-2"></i> ¿Recibiré confirmación de mi cita?
            </button>
          </h2>
          <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAyuda">
            <div class="accordion-body">
              Sí, recibirás un correo de confirmación con los datos de tu cita una vez que quede registrada en el sistema.
            </div>
          </div>
        </div>
      </div>

      <div class="alert alert-info mt-4">
        <i class="bi bi-telephone-inbound"></i> Si necesitas ayuda personalizada, comunícate con nuestra central: <strong>01-234-5678</strong>.
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p class="mb-0">© 2025 Clínica | Página de Ayuda</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
