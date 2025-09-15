
// public/app.js (solo se muestra la función postToRouter; el resto del archivo queda igual)
const ROUTER_PATH = "../router.php"; // desde views/index.php

/* ---------- Helpers fetch (form-urlencoded) ---------- */
function postToRouter(params) {
  const body = Object.keys(params).map(k => encodeURIComponent(k) + "=" + encodeURIComponent(params[k])).join("&");
  return fetch(ROUTER_PATH, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body
  })
    .then(response => response.text())
    .then(text => {
      // intentar parsear JSON; si falla, devolver objeto con error y raw
      if (!text) return {};
      try {
        return JSON.parse(text);
      } catch (err) {
        console.error("Respuesta inválida del servidor (no JSON):", text);
        return { error: "Respuesta inválida del servidor", raw: text };
      }
    })
    .catch(err => {
      console.error("Error en fetch:", err);
      return { error: err.message };
    });
}


/* ---------- API wrappers ---------- */
function verificarPaciente(dni) { return postToRouter({ accion: "verificarPaciente", dni }); }
function registrarPaciente(dni, nombre, correo, telefono) { return postToRouter({ accion: "registrarPaciente", dni, nombre, correo, telefono }); }
function listarEspecialidades() { return postToRouter({ accion: "especialidades" }); }
function listarDoctores(id_especialidad) { return postToRouter({ accion: "doctores", id_especialidad }); }
function agendarCita(id_paciente, id_doctor, fecha, hora) { return postToRouter({ accion: "agendarCita", id_paciente, id_doctor, fecha, hora }); }

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
    doctores: [], doctor: null, fecha: null, hora: null
  };

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
      const text = ev.results[0][0].transcript;
      appendMensaje(text, "usuario");
      handleUserSpeech(text);
    };
    recognition.onerror = (ev) => {
      appendMensaje("Error reconocimiento: " + ev.error, "asistente");
    };
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

  resetBtn.addEventListener("click", () => {
    location.reload();
  });

  manualVerify.addEventListener("click", () => {
    // fallback manual registration + continue
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
    text = (text || "").toLowerCase();
    const yes = ["si", "sí", "claro", "por favor", "afirmativo"];
    const no = ["no", "nop", "no gracias"];

    const isYes = yes.some(w => text.includes(w));
    const isNo = no.some(w => text.includes(w));

    switch (state.step) {
      case "confirm_start":
        if (isYes) {
          state.step = "ask_dni";
          speak("Perfecto. Dime tu número de DNI, por favor.");
        } else if (isNo) {
          speak("Muy bien, cuando necesites algo vuelve a iniciarme.");
          state.step = "idle";
        } else {
          speak("No entendí. ¿Deseas agendar una cita? Responde sí o no.");
        }
        break;

      case "ask_dni":
        const nums = (text.match(/\d+/g) || []).join("");
        if (nums.length >= 6) {
          state.dni = nums;
          speak(`Verificando DNI ${state.dni}...`);
          verificarPaciente(state.dni).then(res => {
            if (res && res.id_paciente) {
              state.paciente = res;
              speak(`Paciente encontrado: ${res.nombre || res.nombre_completo || "usuario"}. ¿Qué especialidad deseas?`);
              loadEspecialidades();
              state.step = "ask_specialty";
            } else {
              speak("No encuentro ese DNI. Dime tu numero de DNI para registrarte.");
              state.step = "registering_dni";
            }
          });
        } else {
          speak("No escuché un DNI claro. Dime solo los números de tu DNI, por favor.");
        }
        break;
      case "registering_dni":
        const newDni = (text.match(/\d+/g) || []).join("");
        if (newDni.length === 8) {
          state.dni = newDni;
          speak(`Anoté tu DNI como ${state.dni}. Ahora dime tu nombre completo.`);
          state.step = "registering_name";
        } else {
          speak("El DNI debe tener 8 dígitos. Por favor repite tu número completo.");
        }
        break;

      case "registering_name":
        state.register_nombre = capitalizeWords(text);
        speak("Ahora dime tu correo electrónico.");
        state.step = "registering_email";
        break;

      case "registering_email":
        state.register_correo = text.replace(/\s/g, "");
        speak("Listo. Ahora dime tu teléfono.");
        state.step = "registering_phone";
        break;

      case "registering_phone":
        // guardar teléfono capturado
        state.register_telefono = (text.match(/\d+/g) || []).join("");

        // mostrar resumen antes de registrar
        speak(`Por favor confirma tus datos. 
            DNI: ${state.dni || "no registrado"}, 
            Nombre: ${state.register_nombre || "no registrado"}, 
            Correo: ${state.register_correo || "no registrado"}, 
            Teléfono: ${state.register_telefono || "no registrado"}.
            ¿Son correctos? Responde sí o no.`);

        state.step = "confirm_data"; // 👈 nuevo paso
        break;

      case "confirm_data":
        if (isYes) {
          speak("Registrando tus datos...");
          registrarPaciente(
            state.dni || "",
            state.register_nombre || "",
            state.register_correo || "",
            state.register_telefono || ""
          ).then(res => {
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
        } else if (isNo) {
          speak("Entiendo, dime qué dato deseas corregir: DNI, nombre, correo o teléfono.");
          state.step = "correct_field";
        } else {
          speak("Por favor responde sí o no. ¿Los datos son correctos?");
        }
        break;
      case "correct_field":
        if (text.includes("dni")) {
          speak("Dime de nuevo tu número de DNI.");
          state.step = "registering_dni";
        } else if (text.includes("nombre")) {
          speak("Dime de nuevo tu nombre completo.");
          state.step = "registering_name";
        } else if (text.includes("correo")) {
          speak("Dime de nuevo tu correo electrónico.");
          state.step = "registering_email";
        } else if (text.includes("tel")) {
          speak("Dime de nuevo tu número de teléfono.");
          state.step = "registering_phone";
        } else {
          speak("No entendí qué dato quieres corregir. Elige entre DNI, nombre, correo o teléfono.");
        }
        break;


      case "ask_specialty":
        const foundEsp = state.especialidades.find(e => text.includes((e.nombre || "").toLowerCase()));
        if (foundEsp) {
          state.especialidad = foundEsp;
          speak(`Has seleccionado ${foundEsp.nombre}. Buscando doctores disponibles...`);
          speak(`Escoge un doctor de la lista o menciona su apellido.`);
          state.step = "ask_doctor";
        } else {
          speak("No identifiqué la especialidad. Di el nombre o elige en pantalla.");
        }
        break;

      case "ask_doctor":
        const foundDoc = state.doctores.find(d => (d.nombre || "").toLowerCase().includes(text));
        if (foundDoc) {
          state.doctor = foundDoc;
          speak(`Has seleccionado al doctor ${foundDoc.nombre}. Dime la fecha para tu cita, por ejemplo 13 de Septiembre.`);
          panel.innerHTML = "";
          state.step = "ask_date";
        } else {
          speak("No escuché el nombre del doctor. Repite o elige en pantalla.");
        }
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
          state.fechaISO = d.toISOString().slice(0, 10); // → para BD
          state.fechaHumana = d.toLocaleDateString("es-PE"); // → para usuario
        } else if (t.includes("pasado mañana") || t.includes("pasado manana")) {
          let d = new Date();
          d.setDate(d.getDate() + 2);
          state.fechaISO = d.toISOString().slice(0, 10);
          state.fechaHumana = d.toLocaleDateString("es-PE");
        } else {
          // regex para "15 de setiembre (de 2025)"
          const dm = t.match(/([0-3]?\d)(?:º|°)?\s*(?:de\s+)?(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|setiembre|octubre|noviembre|diciembre)(?:\s+de\s+(\d{2,4}))?/i);
          if (dm) {
            let day = parseInt(dm[1], 10);
            let month = monthMap[dm[2].toLowerCase()];
            let year = dm[3] ? parseInt(dm[3], 10) : (new Date()).getFullYear();
            if (year < 100) year += 2000;

            // ISO → seguro para MySQL
            state.fechaISO = `${year}-${String(month).padStart(2, "0")}-${String(day).padStart(2, "0")}`;

            // Humana → bonito para hablar/mostrar
            state.fechaHumana = `${day}/${String(month).padStart(2, "0")}/${year}`;
          }
        }

        if (state.fechaISO) {
          speak(`Perfecto, registré la fecha ${state.fechaHumana}. Ahora dime la hora de tu cita.`);
          state.step = "ask_time"; // siguiente paso
        } else {
          speak("No escuché una fecha válida. Por ejemplo, di '15 de setiembre' o 'mañana'.");
          manualForm.classList.remove("hidden");
        }
        break;




      case "ask_time":
        const timeMatch = text.match(/(\d{1,2}:\d{2})|(\d{1,2})/);
        if (timeMatch) {
          let t = timeMatch[0];
          if (!t.includes(":")) t = t.padStart(2, "0") + ":00";
          if (t.length === 4) t = "0" + t;

          state.horaISO = t;                  // formato para BD
          state.horaHumana = t;               // de momento igual, pero podrías parsear "8 de la noche" → "20:00"

          speak(`Resumen: cita para ${state.paciente.nombre || "paciente"} con ${state.doctor.nombre} (${state.especialidad.nombre}) el ${state.fechaHumana} a las ${state.horaHumana}. ¿Deseas confirmar?`);
          state.step = "confirm";
        } else {
          speak("No escuché una hora válida. Dime por ejemplo 10:00.");
          manualForm.classList.remove("hidden");
        }
        break;

      case "confirm":
        if (isYes) {
          speak("Agendando la cita...");
          agendarCita(state.paciente.id_paciente, state.doctor.id_doctor, state.fechaISO, state.horaISO).then(res => {
            if (res && res.id_cita) {
              speak("Cita agendada correctamente. ¿Deseas que te ayude en algo más?");
              state.step = "after_confirm";
            } else {
              appendMensaje("Error al agendar: " + JSON.stringify(res), "asistente");
              speak("No pude guardar la cita. Intenta nuevamente o usa el formulario manual.");
            }
          });
        } else if (isNo) {
          speak("Cita cancelada. ¿Deseas algo más?");
          state.step = "idle";
        } else {
          speak("No entendí. ¿Deseas confirmar la cita? Di sí o no.");
        }
        break;


      case "after_confirm":
        if (isYes) {
          speak("Perfecto. ¿Deseas agendar otra cita?");
          state.step = "confirm_start";
        } else {
          speak("Muy bien. Volviendo a la pantalla principal.");
          state.step = "idle";
        }
        break;

      default:
        speak("No estoy en un flujo activo. Presiona Iniciar para comenzar.");
        break;
    }
  }

  /* Cargar especialidades y render en panel */
  function loadEspecialidades() {
    listarEspecialidades().then(res => {
      state.especialidades = res || [];
      panel.innerHTML = "";
      if (state.especialidades.length === 0) {
        panel.innerHTML = "<div class='card'>No hay especialidades disponibles</div>";
        return;
      }
      state.especialidades.forEach(e => {
        const div = document.createElement("div");
        div.className = "card";
        div.textContent = e.nombre;
        div.onclick = () => {
          // seleccionar
          state.especialidad = e;
          speak(`Seleccionaste ${e.nombre}, ahora selecciona 
            un doctor.`);
          loadDoctores(e.id_especialidad);
          state.step = "ask_doctor";
        };
        panel.appendChild(div);
      });
    });
  }

  function loadDoctores(id_especialidad) {
    listarDoctores(id_especialidad).then(res => {
      state.doctores = res || [];
      // limpiar panel y mostrar doctores
      panel.innerHTML = "<h4>Doctores disponibles:</h4>";
      if (state.doctores.length === 0) {
        panel.innerHTML += "<div class='card'>No hay doctores disponibles</div>";
        return;
      }
      state.doctores.forEach(d => {
        const div = document.createElement("div");
        div.className = "card";
        div.textContent = d.nombre;
        div.onclick = () => {
          state.doctor = d;
          speak(`Seleccionaste al doctor ${d.nombre}. Indica la fecha de la cita diciendo por ejemplo 15 de setiembre.`);
          panel.innerHTML = "";
          state.step = "ask_date";
        };
        panel.appendChild(div);
      });
    });
  }
  // Función para capitalizar la primera letra de cada palabra en una cadena
  function capitalizeWords(str) {
    return str
      .split(" ") // separamos las palabras
      .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()) // capitalizamos cada palabra
      .join(" "); // unimos de nuevo
  }

  // Exponer funciones en window para debug/test desde consola
  window._asistente = { speak, loadEspecialidades, loadDoctores, verificarPaciente };

  // Inicial: mostrar saludo breve en texto (no habla hasta iniciar)
  appendMensaje("Hola, presiona 'Iniciar asistente' para comenzar.", "asistente");

});




