<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AutenticacionController extends CI_Controller {

    function __construct() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Credentials: true");
        parent::__construct();
        $this->load->Model('Usuarios');
    }

    public function autenticarEmail() {

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->Usuarios->autenticarEmail($this->input->post('email'), $this->input->post('password')));
    }

    public function autenticarNomUsuario() {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->Usuarios->autenticarNomUser($this->input->post('nomusuario'), $this->input->post('password')));
    }

}
