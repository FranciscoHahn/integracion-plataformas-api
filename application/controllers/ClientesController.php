<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ClientesController extends CI_Controller {

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
        $this->load->Model('Clientes');
    }

    public function agregarCliente() {
        // Obtenemos los datos del cliente a agregar
        $nombre = $this->input->post('nombre');
        $apellido = $this->input->post('apellido');
        $email = $this->input->post('email');
        $telefono = $this->input->post('telefono');
        $password = $this->input->post('password');

        $response = $this->Clientes->insertarCliente($nombre, $apellido, $email, $telefono, $password);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function autenticarCliente() {
        // Obtenemos los datos del cliente a agregar
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $response = $this->Clientes->autenticarCliente($email, $password);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function actualizarCliente() {
        // Obtenemos los datos del cliente a actualizar
        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');
        $apellido = $this->input->post('apellido');
        $email = $this->input->post('email');
        $telefono = $this->input->post('telefono');
        $password = $this->input->post('password');
        $token = $this->input->post('token');

        // Actualizar el cliente
        $response = $this->Clientes->actualizarCliente($token, $id, $nombre, $apellido, $email, $telefono, $password);

        // Enviar la respuesta en formato JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function activarCliente() {
        $id = $this->input->post('id');
        $token = $this->input->post('token');

        // Actualizar el cliente
        $response = $this->Clientes->activarCliente($token, $id);

        // Enviar la respuesta en formato JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function desactivarCliente() {
        $id = $this->input->post('id');
        $token = $this->input->post('token');

        // Actualizar el cliente
        $response = $this->Clientes->desactivarCliente($token, $id);

        // Enviar la respuesta en formato JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function obtenerClientes() {
        $token = $this->input->post('token');
        $response = $this->Clientes->obtenerClientes($token, $this->input->post('estado_clientes'));

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function getDatosCliente() {
        $token = $this->input->post('token');
        $response = $this->Clientes->getDatosUsuario($token, $this->input->post('id'));

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function decodeTokenCliente() {
        $token = $this->input->post('token');

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->Clientes->decode_token($token));
    }

    public function tiempoRestante() {
        $token = $this->input->post('token');

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(time() - $this->Clientes->decode_token($token)->exp);
    }

}
