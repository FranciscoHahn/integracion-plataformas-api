<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UsuariosController extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/userguide3/general/urls.html
     */
    function __construct() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Credentials: true");

        parent::__construct();
        $this->load->Model('Usuarios');
    }

    public function insertarUsuario() {
        $resultado = $this->Usuarios->insertarUsuario($this->input->post('token'), $this->input->post('nombres'), $this->input->post('apellidos'), $this->input->post('rut'), $this->input->post('nombreusuario'), $this->input->post('password'), $this->input->post('email'), $this->input->post('direccion'), $this->input->post('id_perfil'));
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resultado);
    }

    public function modificarUsuario() {
        $resultado = $this->Usuarios->modificarUsuario($this->input->post('token'), $this->input->post('id_usuario'), $this->input->post('nombres'), $this->input->post('apellidos'), $this->input->post('rut'), $this->input->post('nombreusuario'), $this->input->post('password'), $this->input->post('email'), $this->input->post('direccion'), $this->input->post('id_perfil'));
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resultado);
    }

    public function agregarPerfilAUsuario() {
        $idusuario = $this->input->post('id_usuario');
        $idperfil = $this->input->post('id_perfil');
        $token = $this->input->post('token');
        $resultado = $this->Usuarios->asignarPerfilAUsuario($token, $idusuario, $idperfil);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resultado);
    }

    public function eliminarUsuario() {
        $token = $this->input->post('token');
        $idUsuario = $this->input->post('id_usuario');
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->Usuarios->eliminarUsuario($token, $idUsuario));
    }

    public function restaurarUsuario() {
        $token = $this->input->post('token');
        $idUsuario = $this->input->post('id_usuario');
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->Usuarios->restaurarUsuario($token, $idUsuario));
    }

    public function listarUsuarios() {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->Usuarios->listarUsuarios($this->input->post('token')));
    }

    public function listarPerfiles() {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->Usuarios->listarPerfiles($this->input->post('token')));
    }

}
