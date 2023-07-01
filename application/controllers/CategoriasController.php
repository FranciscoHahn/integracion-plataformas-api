<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CategoriasController extends CI_Controller {

    function __construct() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Credentials: true");

        parent::__construct();
        $this->load->Model('Categoria');
    }

    public function listarCategorias() {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->Categoria->obtenerCategorias($this->input->post('token')));
    }
    
        public function obtenerCategoria() {
        $token = $this->input->post('token');
        $idCategoria = $this->input->post('id_categoria');
        $categoria = $this->Categoria->obtenerCategoria($token, $idCategoria);

        // Devolver la respuesta como JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($categoria);
    }

    public function insertarCategoria() {
        $token = $this->input->post('token');
        $nombre = $this->input->post('nombre');
        $descripcion = $this->input->post('descripcion');

        $resultado = $this->Categoria->insertarCategoria($token, $nombre, $descripcion);

        // Devolver la respuesta como JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resultado);
    }

    public function actualizarCategoria() {
        $token = $this->input->post('token');
        $idCategoria = $this->input->post('id_categoria');
        $nombre = $this->input->post('nombre');
        $descripcion = $this->input->post('descripcion');

        $resultado = $this->Categoria->actualizarCategoria($token, $idCategoria, $nombre, $descripcion);

        // Devolver la respuesta como JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resultado);
    }

    public function activarCategoria() {
        $token = $this->input->post('token');
        $idCategoria = $this->input->post('id_categoria');

        $resultado = $this->Categoria->activarCategoria($token, $idCategoria);

        // Devolver la respuesta como JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resultado);
    }

    public function desactivarCategoria() {
        $token = $this->input->post('token');
        $idCategoria = $this->input->post('id_categoria');

        $resultado = $this->Categoria->desactivarCategoria($token, $idCategoria);

        // Devolver la respuesta como JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resultado);
    }

}
