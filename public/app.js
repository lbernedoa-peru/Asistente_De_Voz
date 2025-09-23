// public/app.js
const ROUTER_PATH = "../router.php";

/* ---------- Helpers fetch (form-urlencoded) ---------- */
function postToRouter(params) {
  const body = Object.keys(params)
    .map(k => encodeURIComponent(k) + "=" + encodeURIComponent(params[k]))
    .join("&");
  return fetch(ROUTER_PATH, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body
  })
    .then(response => response.text())
    .then(text => {
      if (!text) return {};
      try { return JSON.parse(text); }
      catch (err) { return { error: "Respuesta inválida del servidor", raw: text }; }
    })
    .catch(err => ({ error: err.message }));
}

/* ---------- API wrappers ---------- */
function verificarPaciente(dni) { return postToRouter({ accion: "verificarPaciente", dni }); }
function registrarPaciente(dni, nombre, correo, telefono) { return postToRouter({ accion: "registrarPaciente", dni, nombre, correo, telefono }); }
function listarEspecialidades() { return postToRouter({ accion: "especialidades" }); }
function listarDoctores(id_especialidad) { return postToRouter({ accion: "doctores", id_especialidad }); }
function agendarCita(id_paciente, id_doctor, fecha, hora, estado) { return postToRouter({ accion: "agendarCita", id_paciente, id_doctor, fecha, hora, estado }); }

/* ---------- UI refs ---------- */
document.addEventListener("DOMContentLoaded", () => {
  const conv = document.getElementById("conversacion");
  const panel = document.getElementById("panelOpciones");
  const startBtn = document.getElementById("startBtn");
  const listenBtn = document.getElementById("listenBtn");
  const resetBtn = document.getElementById("resetBtn");
  const manualForm = document.getElementById("manualForm");
  const manualDni = document.getElementById("manualDni");
  const manualNombre = document.getElementById("manualNombre");
  const manualCorreo = document.getElementById("manualCorreo");
  const manualTelefono = document.getElementById("manualTelefono");
  const manualFecha = document.getElementById("manualFecha");
  const manualHora = document.getElementById("manualHora");
  const manualVerify = document.getElementById("manualVerify");

  const state = {
    step: "idle",
    dni: null, paciente: null,
    especialidades: [], especialidad: null,
    doctores: [], doctor: null, fechaISO: null, fechaHumana: null,
    horaISO: null, horaHumana: null
  };

  // 🔹 Auxiliar para Doctor/Doctora
  function getTituloDoctor(doctor) {
    if (!doctor) return "";
    if (doctor.genero === "F") return "la doctora";
    return "el doctor";
  }

  function appendMensaje(text, who = "asistente") {
    const el = document.createElement("div");
    el.className = "mensaje " + (who === "asistente" ? "asistente" : "usuario");
    el.innerHTML = `<strong>${who === "asistente" ? "Asistente" : "Tú"}:</strong> ${text}`;
    conv.appendChild(el);
    conv.scrollTop = conv.scrollHeight;
  }

  function speak(text) {
    appendMensaje(text, "asistente");
    const u = new SpeechSynthesisUtterance(text);
    u.lang = "es-ES";
    window.speechSynthesis.cancel();
    window.speechSynthesis.speak(u);
  }

  /* Reconocimiento */
  const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
  let recognition = null;
  if (SpeechRecognition) {
    recognition = new SpeechRecognition();
    recognition.lang = "es-ES";
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;
    recognition.onresult = (ev) => {
      const text = ev.results[0][0].transcript.toLowerCase();
      appendMensaje(text, "usuario");
      handleUserSpeech(text);
    };
    recognition.onerror = (ev) => appendMensaje("Error reconocimiento: " + ev.error, "asistente");
  }

  /* start assistant */
  startBtn.addEventListener("click", () => {
    state.step = "confirm_start";
    panel.innerHTML = "";
    manualForm.classList.add("hidden");
    speak("Hola, soy el asistente de voz de la clínica. ¿Deseas agendar una cita?");
  });

  listenBtn.addEventListener("click", () => {
    if (!recognition) return alert("Tu navegador no soporta reconocimiento de voz.");
    recognition.start();
  });

  resetBtn.addEventListener("click", () => location.reload());

  manualVerify.addEventListener("click", () => {
    const dni = manualDni.value.trim();
    const nombre = manualNombre.value.trim();
    const correo = manualCorreo.value.trim();
    const tel = manualTelefono.value.trim();
    const fecha = manualFecha.value;
    const hora = manualHora.value;
    if (!dni || !nombre) return alert("DNI y nombre requeridos");
    appendMensaje("Registrando manualmente...", "asistente");
    registrarPaciente(dni, nombre, correo, tel).then(r => {
      if (r && r.id_paciente) {
        state.paciente = { id_paciente: r.id_paciente, nombre, dni };
        speak("Registro exitoso. Ahora elige la especialidad.");
        loadEspecialidades();
        state.step = "ask_specialty";
      } else {
        appendMensaje("Error al registrar: " + JSON.stringify(r), "asistente");
      }
    });
  });

  /* Handlers de pasos */
  function handleUserSpeech(text) {
    const yes = ["si", "sí", "claro", "por favor", "afirmativo"];
    const no = ["no", "nop", "no gracias"];
    const isYes = yes.some(w => text.includes(w));
    const isNo = no.some(w => text.includes(w));

    switch (state.step) {
      case "confirm_start":
        if (isYes) { state.step = "ask_dni"; speak("Perfecto. Dime tu número de DNI, por favor."); }
        else if (isNo) { speak("Muy bien, cuando necesites algo vuelve a iniciarme."); state.step = "idle"; }
        else speak("No entendí. ¿Deseas agendar una cita? Responde sí o no.");
        break;

      case "ask_dni":
        const nums = (text.match(/\d+/g) || []).join("");
        if (nums.length >= 6) {
          state.dni = nums;
          speak(`Verificando DNI ${state.dni}...`);
          verificarPaciente(state.dni).then(res => {
            if (res && res.id_paciente) {
              state.paciente = res;
              speak(`Paciente encontrado: ${res.nombre || "usuario"}. ¿Qué especialidad deseas?`);
              loadEspecialidades();
              state.step = "ask_specialty";
            } else {
              speak("No encuentro ese DNI. Dime tu número de DNI para registrarte.");
              state.step = "registering_dni";
            }
          });
        } else speak("No escuché un DNI claro. Dime solo los números de tu DNI, por favor.");
        break;

      case "registering_dni":
        const newDni = (text.match(/\d+/g) || []).join("");
        if (newDni.length === 8) { state.dni = newDni; speak(`Anoté tu DNI como ${state.dni}. Ahora dime tu nombre completo.`); state.step = "registering_name"; }
        else speak("El DNI debe tener 8 dígitos. Por favor repite tu número completo.");
        break;

      case "registering_name":
        state.register_nombre = capitalizeWords(text);
        speak("Ahora dime tu correo electrónico.");
        state.step = "registering_email";
        break;

      case "registering_email":
    // Quitar espacios y convertir a minúsculas
    const email = text.replace(/\s/g, "").toLowerCase();

    // Expresión regular más completa para validar correos
    const regex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;

    if (regex.test(email)) {
        state.register_correo = email;
        speak(`Listo. Ahora dime tu teléfono.`);
        state.step = "registering_phone";
    } else {
        // Si no es válido, pedir que lo repita
        speak("Ese correo no parece válido. Por favor, repítelo.");
        state.step = "registering_email";
    }
    break;


      case "registering_phone":
        state.register_telefono = (text.match(/\d+/g) || []).join("");
        speak(`Por favor confirma tus datos: DNI: ${state.dni}, Nombre: ${state.register_nombre}, Correo: ${state.register_correo}, Teléfono: ${state.register_telefono}. ¿Son correctos?`);
        state.step = "confirm_data";
        break;

      case "confirm_data":
        if (isYes) {
          speak("Registrando tus datos...");
          registrarPaciente(state.dni, state.register_nombre, state.register_correo, state.register_telefono).then(res => {
            if (res && res.id_paciente) {
              state.paciente = { id_paciente: res.id_paciente, nombre: state.register_nombre, dni: state.dni };
              speak("Registro exitoso. Dime la especialidad que deseas.");
              loadEspecialidades();
              state.step = "ask_specialty";
            } else {
              appendMensaje("Error al registrar: " + JSON.stringify(res), "asistente");
              speak("No pude registrar tus datos. Usa el formulario manual en pantalla.");
              manualForm.classList.remove("hidden");
            }
          });
        } else if (isNo) { speak("Entiendo, dime qué dato deseas corregir: DNI, nombre, correo o teléfono."); state.step = "correct_field"; }
        else speak("Por favor responde sí o no. ¿Los datos son correctos?");
        break;

      case "correct_field":
        if (text.includes("dni")) { speak("Dime de nuevo tu número de DNI."); state.step = "registering_dni"; }
        else if (text.includes("nombre")) { speak("Dime de nuevo tu nombre completo."); state.step = "registering_name"; }
        else if (text.includes("correo")) { speak("Dime de nuevo tu correo electrónico."); state.step = "registering_email"; }
        else if (text.includes("tel")) { speak("Dime de nuevo tu número de teléfono."); state.step = "registering_phone"; }
        else speak("No entendí qué dato quieres corregir. Elige entre DNI, nombre, correo o teléfono.");
        break;

      case "ask_specialty":
        if (!state.especialidades || state.especialidades.length === 0) return;
        let selectedEsp = state.especialidades.find(e => text.includes((e.nombre || "").toLowerCase()));
        if (selectedEsp) {
          state.especialidad = selectedEsp;
          speak(`Has seleccionado ${selectedEsp.nombre}. Buscando doctores disponibles...`);
          loadDoctores(selectedEsp.id_especialidad);
          speak(`Escoge un doctor o di su nombre y apellido.`);
          state.step = "ask_doctor";
        } else speak("No identifiqué la especialidad. Di el nombre o elige en pantalla.");
        break;

      case "ask_doctor":
        if (!state.doctores || state.doctores.length === 0) return;
        const foundDoc = state.doctores.find(d => (d.nombre || "").toLowerCase().includes(text));
        if (foundDoc) {
          state.doctor = foundDoc;
          speak(`Has seleccionado a ${getTituloDoctor(foundDoc)} ${foundDoc.nombre}. Dime la fecha para tu cita, por ejemplo 13 de septiembre.`);
          panel.innerHTML = "";
          state.step = "ask_date";
        } else if (text && text.trim() !== "") speak("No escuché el nombre del doctor. Repite o elige en pantalla.");
        break;

      case "ask_date":
        const normalize = s => (s || "").toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        let t = normalize(text);

        // mapa meses
        const monthMap = {
          enero: 1, febrero: 2, marzo: 3, abril: 4, mayo: 5, junio: 6,
          julio: 7, agosto: 8, septiembre: 9, setiembre: 9, octubre: 10, noviembre: 11, diciembre: 12
        };

        // opciones relativas
        if (t.includes("mañana") || t.includes("manana")) {
          let d = new Date();
          d.setDate(d.getDate() + 1);
          state.fechaISO = d.toISOString().slice(0, 10); 
          state.fechaHumana = d.toLocaleDateString("es-PE"); 
        } else if (t.includes("pasado mañana") || t.includes("pasado manana")) {
          let d = new Date();
          d.setDate(d.getDate() + 2);
          state.fechaISO = d.toISOString().slice(0, 10);
          state.fechaHumana = d.toLocaleDateString("es-PE");
        } else {
          const dm = t.match(/([0-3]?\d)(?:º|°)?\s*(?:de\s+)?(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|setiembre|octubre|noviembre|diciembre)(?:\s+de\s+(\d{2,4}))?/i);
          if (dm) {
            let day = parseInt(dm[1], 10);
            let month = monthMap[dm[2].toLowerCase()];
            let year = dm[3] ? parseInt(dm[3], 10) : (new Date()).getFullYear();
            if (year < 100) year += 2000;

            state.fechaISO = `${year}-${String(month).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
            state.fechaHumana = `${day}/${String(month).padStart(2, "0")}/${year}`;
          }
        }

        if (state.fechaISO) {
          speak(`Perfecto, registré la fecha ${state.fechaHumana}. Ahora dime la hora de tu cita.`);
          state.step = "ask_time"; 
        } else {
          speak("No escuché una fecha válida. Por ejemplo, di '15 de setiembre' o 'mañana'.");
          manualForm.classList.remove("hidden");
        }
        break;

      case "ask_time":
        const timeMatch = text.match(/(\d{1,2}:\d{2})|(\d{1,2})/);
        if (timeMatch) {
          let t = timeMatch[0];
          let hour = 0;
          let minutes = 0;

          if (t.includes(":")) {
            [hour, minutes] = t.split(":").map(Number);
          } else {
            hour = parseInt(t);
            minutes = 0;
          }

          const lowerText = text.toLowerCase();
          if (lowerText.includes("tarde") || lowerText.includes("noche")) {
            if (hour >= 1 && hour <= 11) {
              hour += 12;
            }
          } else if (lowerText.includes("mañana")) {
            if (hour === 12) {
              hour = 0;
            }
          }

          const hh = String(hour).padStart(2, "0");
          const mm = String(minutes).padStart(2, "0");

          state.horaISO = `${hh}:${mm}`;
          state.horaHumana = `${hh}:${mm}`;

          speak(`Resumen: cita para ${state.paciente.nombre || "paciente"} con ${getTituloDoctor(state.doctor)} ${state.doctor.nombre} (${state.especialidad.nombre}) el ${state.fechaHumana} a las ${state.horaHumana}. ¿Deseas confirmar?`);
          state.step = "confirm";

        } else {
          speak("No escuché una hora válida. Dime por ejemplo 10 de la mañana o 8 de la noche.");
          manualForm.classList.remove("hidden");
        }
        break;

      case "confirm":
        if (isYes) {
          agendarCita(state.paciente.id_paciente, state.doctor.id_doctor, state.fechaISO, state.horaISO, state.estado).then(res => {
            if (res && res.id_cita) { 
              speak("Cita agendada correctamente. ¿Deseas que te ayude en algo más?"); 
              state.step = "after_confirm"; 
            }
            else { 
              appendMensaje("Error al agendar: " + JSON.stringify(res), "asistente"); 
              speak("No pude guardar la cita. Intenta nuevamente o usa el formulario manual."); 
            }
          });
        } else if (isNo) { speak("Cita cancelada. ¿Deseas algo más?"); state.step = "idle"; }
        else speak("No entendí. ¿Deseas confirmar la cita? Di sí o no.");
        break;

      case "after_confirm":
        if (isYes) { speak("Perfecto. ¿Deseas agendar otra cita?"); state.step = "confirm_start"; }
        else { speak("Muy bien. Volviendo a la pantalla principal."); state.step = "idle"; }
        break;

      default:
        speak("No estoy en un flujo activo. Presiona Iniciar para comenzar.");
        break;
    }
  }

  function loadEspecialidades() {
  listarEspecialidades().then(res => {
    state.especialidades = res || [];
    panel.innerHTML = "<h4 class='w-100 mb-3'>Especialidades disponibles:</h4>";

    if (state.especialidades.length === 0) {
      panel.innerHTML += "<div class='alert alert-warning'>No hay especialidades disponibles</div>";
      return;
    }

    state.especialidades.forEach(e => {
      // Columna Bootstrap
      const col = document.createElement("div");
      col.className = "col";

      // Card dentro de la columna
      const div = document.createElement("div");
      div.className = "card p-3 shadow-sm option-card text-center";
      div.style.cursor = "pointer";
      div.textContent = e.nombre;
      div.onclick = () => {
        state.especialidad = e;
        speak(`Seleccionaste ${e.nombre}, ahora selecciona un doctor.`);
        loadDoctores(e.id_especialidad);
        state.step = "ask_doctor";
      };

      col.appendChild(div);
      panel.appendChild(col);
    });
  });
}

function loadDoctores(id_especialidad) {
  listarDoctores(id_especialidad).then(res => {
    state.doctores = res || [];
    panel.innerHTML = "<h4 class='w-100 mb-3'>Doctores disponibles:</h4>";

    if (!state.doctores || state.doctores.length === 0) {
      panel.innerHTML += "<div class='alert alert-warning'>No hay doctores disponibles</div>";
      return;
    }

    state.doctores.forEach(d => {
      // Columna Bootstrap
      const col = document.createElement("div");
      col.className = "col";

      // Card dentro de la columna
      const div = document.createElement("div");
      div.className = "card p-3 shadow-sm option-card text-center";
      div.style.cursor = "pointer";

      // Función para prefijo del doctor
      function getTituloDoctor(d) {
        if (!d.genero) return "Dr./Dra."; // fallback si no hay género
        return d.genero === "M" ? "Dr." : "Dra.";
      }

      div.textContent = `${getTituloDoctor(d)} ${d.nombre}`;
      div.onclick = () => {
        state.doctor = d;
        speak(`Seleccionaste a ${getTituloDoctor(d)} ${d.nombre}. Dime la fecha de tu cita.`);
        state.step = "ask_date";
        panel.innerHTML = "";
      };

      col.appendChild(div);
      panel.appendChild(col);
    });
  });
}



  function capitalizeWords(str) {
    return (str || "").split(" ").map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(" ");
  }
});
