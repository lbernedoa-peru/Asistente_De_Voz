<?php
// controllers/ControlAsistente.php
require_once __DIR__ . '/../models/ModelAsistente.php';

class ControlAsistente {
    private $model;

    public function __construct() {
        $this->model = new ModelAsistente();
    }

    public function verificarPaciente($dni) {
        return $this->model->getPacienteByDni($dni);
    }

    public function registrarPaciente($dni, $nombre, $correo, $telefono) {
        return $this->model->insertPaciente($dni, $nombre, $correo, $telefono);
    }

    public function listarEspecialidades() {
        return $this->model->getEspecialidades();
    }

    public function listarDoctoresPorEspecialidad($id_especialidad) {
        return $this->model->getDoctoresByEspecialidad($id_especialidad);
    }

    public function agendarCita($id_paciente, $id_doctor, $fecha, $hora) {
        return $this->model->insertCita($id_paciente, $id_doctor, $fecha, $hora);
    }
}
