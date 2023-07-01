<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class InstrumentosController extends CI_Controller {

    function __construct() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Credentials: true");

        parent::__construct();
        $this->load->Model('Instrumentos');
    }

    public function listarInstrumentos() {
        header('Content-Type: application/json; charset=utf-8');
        $token = $this->input->post('token');
        $activos = $this->input->post('activos');
        echo json_encode($this->Instrumentos->getInstrumentos($token, $activos));
    }

    public function obtenerInstrumentosPorCategoria() {
        header('Content-Type: application/json; charset=utf-8');
        $token = $this->input->post('token');
        $idCategoria = $this->input->post('categoria_id');
        echo json_encode($this->Instrumentos->getInstrumentosByCategoria($token, $idCategoria));
    }

    public function obtenerInstrumentoPorId() {
        header('Content-Type: application/json; charset=utf-8');
        $token = $this->input->post('token');
        $instrumentoId = $this->input->post('instrumento_id');
        echo json_encode($this->Instrumentos->getInstrumentoById($token, $instrumentoId));
    }

    public function insertarInstrumento() {
        header('Content-Type: application/json; charset=utf-8');
        $token = $this->input->post('token');
        $nombre = $this->input->post('nombre');
        $categoriaId = $this->input->post('categoria_id');
        $precio = $this->input->post('precio');
        $stock = $this->input->post('stock');
        $descripcion = $this->input->post('descripcion');
        $imagen = $this->input->post('imagen');
        $marca = $this->input->post('marca');
        echo json_encode($this->Instrumentos->insertarInstrumento($token, $nombre, $categoriaId, $precio, $stock, $descripcion, $imagen, $marca));
    }

    public function actualizarInstrumento() {
        header('Content-Type: application/json; charset=utf-8');
        $token = $this->input->post('token');
        $instrumentoId = $this->input->post('instrumento_id');
        $nombre = $this->input->post('nombre');
        $categoriaId = $this->input->post('categoria_id');
        $precio = $this->input->post('precio');
        $stock = $this->input->post('stock');
        $descripcion = $this->input->post('descripcion');
        $imagen = $this->input->post('imagen');
        $marca = $this->input->post('marca');
        echo json_encode($this->Instrumentos->actualizarInstrumento($token, $instrumentoId, $nombre, $categoriaId, $precio, $stock, $descripcion, $imagen, $marca));
    }

    public function desactivarInstrumento() {
        header('Content-Type: application/json; charset=utf-8');
        $token = $this->input->post('token');
        $instrumentoId = $this->input->post('instrumento_id');
        echo json_encode($this->Instrumentos->desactivarInstrumento($token, $instrumentoId));
    }

    public function activarInstrumento() {
        header('Content-Type: application/json; charset=utf-8');
        $token = $this->input->post('token');
        $instrumentoId = $this->input->post('instrumento_id');
        echo json_encode($this->Instrumentos->activarInstrumento($token, $instrumentoId));
    }

}
