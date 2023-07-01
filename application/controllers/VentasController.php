<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class VentasController extends CI_Controller {

    public function __construct() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Credentials: true");

        parent::__construct();
        $this->load->Model('Instrumentos');
        $this->load->Model('Ventas');
    }

    public function crearVenta() {
        $token = $this->input->post('token');
        $direccionDespacho = $this->input->post('direccion_despacho');
        $formaRetiro = $this->input->post('forma_retiro');

        $response = $this->Ventas->crearVenta($token, $direccionDespacho, $formaRetiro);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function formasRetiro() {
        $response = $this->Ventas->formasretiro();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function estadosVentas() {
        $response = $this->Ventas->estadosventas();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function formasPago() {
        $response = $this->Ventas->formasDePago();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function estadosEntrega() {
        $response = $this->Ventas->estadosEntrega();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function listarVentas() {
        $token = $this->input->post('token');
        $estadoEntrega = $this->input->post('estado_entrega');
        $estadoPago = $this->input->post('estado_pago');

        $response = $this->Ventas->listarVentas($token, $estadoEntrega, $estadoPago);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function modificarEstadosVenta() {
        $token = $this->input->post('token');
        $idVenta = $this->input->post('id_venta');
        $estadoPago = $this->input->post('estado_pago');
        $estadoEntrega = $this->input->post('estado_entrega');
        $formaRetiro = $this->input->post('forma_retiro');
        $formaPago = $this->input->post('forma_pago');

        $response = $this->Ventas->modificarEstadosVenta($token, $idVenta, $estadoPago, $estadoEntrega, $formaRetiro, $formaPago);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function agregarProductoAVenta() {
        $token = $this->input->post('token');
        $idInstrumento = $this->input->post('id_instrumento');
        $idVenta = $this->input->post('id_venta');
        $cantidad = $this->input->post('cantidad');

        $response = $this->Ventas->agregarProductoAVenta($token, $idInstrumento, $idVenta, $cantidad);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function eliminarProductoAVenta() {
        $token = $this->input->post('token');
        $iddetalle = $this->input->post('id_detalle');

        $response = $this->Ventas->eliminarProductoAVenta($token, $iddetalle);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function getDetalleVentas() {

        $token = $this->input->post('token');
        $idVenta = $this->input->post('id_venta');

        $response = $this->Ventas->getDetallesVenta($token, $idVenta);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    public function getVentaPorId() {
        
        $token = $this->input->post('token');
        $idVenta = $this->input->post('id_venta');

        $response = $this->Ventas->getVentaPorId($token, $idVenta);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

}
