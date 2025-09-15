<?php
// views/index.php
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Asistente de Voz - Clínica</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link rel="stylesheet" href="">
  <style>
    body{font-family:Arial, sans-serif;background:#f7f9fc;margin:0;padding:20px}
    .container{max-width:900px;margin:0 auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 6px 20px rgba(0,0,0,0.06)}
    h1{margin-top:0}
    #controls{margin:16px 0;display:flex;gap:8px}
    button{padding:10px 14px;border-radius:6px;border:0;cursor:pointer}
    button.primary{background:#0d6efd;color:white}
    button.secondary{background:#6c757d;color:white}
    #conversacion{background:#f1f5f9;padding:12px;border-radius:6px;min-height:160px;overflow:auto}
    .mensaje{margin:6px 0}
    .asistente{color:#0d6efd}
    .usuario{color:#333}
    .panel{margin-top:14px;display:flex;gap:12px;flex-wrap:wrap}
    .card{background:#fff;padding:8px 10px;border-radius:6px;border:1px solid #e6edf3;cursor:pointer}
    label{display:block;margin-top:10px}
    input[type=text], input[type=date], input[type=time]{padding:8px;width:100%;box-sizing:border-box;border-radius:6px;border:1px solid #ddd}
    .hidden{display:none}
  </style>
</head>
<body>
  <div class="container">
    <h1>🎤 Asistente de Voz - Clínica</h1>
    <p>Usa el asistente para agendar citas por voz. (Si tu navegador bloquea audio, usa los botones)</p>

    <div id="controls">
      <button id="startBtn" class="primary">▶ Iniciar asistente</button>
      <button id="resetBtn">🔁 Reiniciar</button>
      <button id="listenBtn" class="secondary">🎙️ Hablar</button>
    </div>

    <div id="conversacion" aria-live="polite">
    </div>

    <div class="panel" id="panelOpciones"></div>

    <div id="manualForm" class="hidden">
      <h3>Entrada manual (fallback)</h3>
      <label>DNI</label><input id="manualDni" type="text" placeholder="Ej: 12345678" />
      <label>Nombre completo</label><input id="manualNombre" type="text" placeholder="Juan Perez" />
      <label>Correo</label><input id="manualCorreo" type="text" placeholder="correo@ejemplo.com" />
      <label>Teléfono</label><input id="manualTelefono" type="text" placeholder="999999999" />
      <label>Fecha</label><input id="manualFecha" type="date" />
      <label>Hora</label><input id="manualHora" type="time" />
      <div style="margin-top:8px">
        <button id="manualVerify" class="primary">Verificar / Registrar y Continuar</button>
      </div>
    </div>
  </div>

  <script src="../public/app.js"></script>
</body>
</html>
