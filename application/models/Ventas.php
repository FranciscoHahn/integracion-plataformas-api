<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ventas extends CI_Model
{

    private $jwt;
    private $utilidades;
    private $formasPago;
    private $formasRetiro;
    private $estadosVentas;
    private $estadosEntrega;

    public function __construct()
    {
        parent::__construct();
        $this->jwt = new JWT();
        $this->load->database(); // Carga la base de datos configurada en CodeIgniter
        $this->utilidades = new Utilidades();
        $this->formasRetiro = array(
            'En tienda',
            'A domicilio'
        );
        $this->estadosVentas = array(
            'Creada',
            'Pagada',
            'Cancelada'
        );
        $this->formasPago = array(
            'Debito',
            'Crédito',
            'Transferencia'
        );
        $this->estadosEntrega = array(
            'En preparación',
            'Preparada para retiro',
            'Preparada para despacho',
            'Despachando',
            'Entregada',
            'Cancelada'
        );
    }

    public function crearVenta($token, $direccionDespacho, $formaRetiro)
    {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        if (!in_array($formaRetiro, $this->formasRetiro)) {
            return $this->utilidades->buildResponse(false, 'failed', 401, 'Forma de entrega con errores', array("formas de entrega posibles" => $this->formasRetiro));
        }

        if ($formaRetiro == 'A domicilio' && strlen($direccionDespacho) < 20) {
            return $this->utilidades->buildResponse(false, 'failed', 401, 'Para entregas a domicilio debe describir su dirección de despacho en al menos 20 caracteres');
        }

        $idClienteToken = $this->jwt->getProperty($token, 'id_cliente');

        $data = array(
            'cliente_id' => $idClienteToken,
            'estado_pago' => 'Creada',
            'estado_entrega' => 'En Preparación',
            'activo' => 1,
            'forma_retiro' => $formaRetiro,
            'direccion_despacho' => $direccionDespacho,
            'forma_de_pago' => 'no ingresada'
        );

        $this->db->insert('ventas', $data);
        $insertId = $this->db->insert_id();
        return $this->utilidades->buildResponse(true, 'success', 200, 'Venta creada, continue agregando los detalles', array("insert_id" => $insertId));
    }

    public function formasretiro()
    {
        return $this->utilidades->buildResponse(true, 'success', 200, 'formas de retiro posibles', $this->formasRetiro);
    }

    public function estadosventas()
    {
        return $this->utilidades->buildResponse(true, 'success', 200, 'Estados de venta posibles', $this->estadosVentas);
    }

    public function formasDePago()
    {
        return $this->utilidades->buildResponse(true, 'success', 200, 'Formas de pago posibles', $this->formasPago);
    }

    public function estadosEntrega()
    {
        return $this->utilidades->buildResponse(true, 'success', 200, 'Estados de entrega posibles', $this->estadosEntrega);
    }

    public function listarVentas($token, $estadoEntrega, $estadoPago)
    {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }

        $this->db->select('v.*, c.nombre nom_cliente, c.apellido as ap_cliente');
        $this->db->from('ventas v');
        $this->db->join('clientes c', 'c.id = v.cliente_id', 'left');
        $this->db->where('v.activo', 1);
        // Agrega cualquier condición adicional que necesites para filtrar las ventas
        if ($estadoEntrega) {
            $this->db->where('estado_entrega', $estadoEntrega);
        }
        if ($estadoPago) {
            $this->db->where('estado_pago', $estadoPago);
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $ventas = $query->result();
            foreach($ventas as $venta){
                $query = $this->db->select('sum(subtotal) AS subtotal')->from('producto_ventas')->where('venta_id', $venta->id)->get()->row();
                if($query){
                    $venta->total = $query;
                }
            }
            $response = $this->utilidades->buildResponse(true, 'success', 200, 'Ventas obtenidas correctamente', $ventas);
        } else {
            $response = $this->utilidades->buildResponse(false, 'failed', 404, 'No se encontraron ventas', null);
        }

        return $response;
    }

    public function modificarEstadosVenta($token, $idVenta, $estadoPago = null, $estadoEntrega = null, $formaRetiro = null, $formaPago = null)
    {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }

        $existeVenta = $this->db->from('ventas')->where('id', $idVenta)->where('activo', 1)->get()->row();
        if (!$existeVenta) {
            return $this->utilidades->buildResponse(false, 'failed', 401, "venta id " . $idVenta . " no existe");
        }

        if (!in_array($estadoPago, $this->estadosVentas) && $estadoPago) {
            return $this->utilidades->buildResponse(false, 'failed', 401, "estado de pago inválido", array("estados posibles" => $this->estadosVentas));
        }

        if (!in_array($estadoEntrega, $this->estadosEntrega) && $estadoEntrega) {
            return $this->utilidades->buildResponse(false, 'failed', 401, "estado de entrega inválido", array("estados posibles" => $this->estadosEntrega));
        }

        if (!in_array($formaRetiro, $this->formasRetiro) && $formaRetiro) {
            return $this->utilidades->buildResponse(false, 'failed', 401, "forma de retiro inválida", array("formas posibles" => $this->formasRetiro));
        }

        if (!in_array($formaPago, $this->formasPago) && $formaPago) {
            return $this->utilidades->buildResponse(false, 'failed', 401, "forma de pago inválida", array("formas posibles" => $this->formasPago));
        }

        $this->db->where('id', $idVenta);

        if ($estadoPago) {
            $this->db->set('estado_pago', $estadoPago);
        }
        if ($estadoEntrega) {
            $this->db->set('estado_entrega', $estadoEntrega);
        }
        if ($formaPago) {
            $this->db->set('forma_de_pago', $formaPago);
        }

        if ($formaRetiro) {
            $this->db->set('forma_retiro', $formaRetiro);
        }

        $result = $this->db->update('ventas');
        return $this->utilidades->buildResponse(true, 'success', 200, 'Actualización exitosa', null);
    }

    public function agregarProductoAVenta($token, $idInstrumento, $idVenta, $cantidad)
    {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        $producto_info = $this->db->select('stock, precio')->from('instrumentos')->where('id', $idInstrumento)->where('activo', 1)->get()->row();

        if ($producto_info->stock < $cantidad) {
            return $this->utilidades->buildResponse(false, 'failed', 401, 'no se cuenta con el stock suficiente', array("stock_producto" => $producto_info->stock));
        }

        $data = array(
            'precio_unitario' => $producto_info->precio,
            'cantidad' => $cantidad,
            'activo' => 1,
            'subtotal' => $cantidad * $producto_info->precio,
            'instrumento_id' => $idInstrumento,
            'venta_id' => $idVenta
        );

        $this->db->insert('producto_ventas', $data);

        return $this->utilidades->buildResponse(true, 'success', 200, 'Detalle venta insertado', array("insert_id" => $this->db->insert_id()));
    }

    public function eliminarProductoAVenta($token, $idDetalle)
    {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        $data = array(
            'precio_unitario' => 0,
            'cantidad' => 0,
            'activo' => 0,
            'subtotal' => 0,
        );

        $this->db->where('id', $idDetalle)->update('producto_ventas', $data);

        return $this->utilidades->buildResponse(true, 'success', 200, 'Detalle venta eliminado');
    }

    public function getDetallesVenta($token, $idVenta)
    {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }

        $this->db->select('pv.id, pv.venta_id, i.nombre AS nombre_producto, c.nombre AS nombre_categoria, pv.cantidad, pv.precio_unitario, pv.subtotal, v.forma_retiro, i.imagen');
        $this->db->from('producto_ventas pv');
        $this->db->join('instrumentos i', 'pv.instrumento_id = i.id', 'inner');
        $this->db->join('categorias c', 'i.categoria_id = c.id', 'inner');
        $this->db->join('ventas v', 'pv.venta_id = v.id', 'inner');
        $this->db->where('pv.venta_id', $idVenta);
        $this->db->where('v.activo', 1); // Filtra las ventas activas (estado = 1)
        $this->db->where('pv.activo', 1); // Filtra los productos de venta activos (activo = 1)
        $query = $this->db->get()->result_array();

        if ($query) {
            return $this->utilidades->buildResponse(true, 'success', 200, 'Detalle venta', array("detalle_venta" => $query));
        } else {
            return $this->utilidades->buildResponse(true, 'success', 200, 'No tiene detalle de ventas');
        }
    }

    public function getVentaPorId($token, $idVenta)
    {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        $query = $this->db
            ->select('v.*, c.nombre AS nombre_cliente, c.apellido AS apellido_cliente')
            ->from('ventas v')
            ->join('clientes c', 'v.cliente_id = c.id', 'left')
            ->where('v.id', $idVenta)
            ->where('v.activo', 1)->get()->row();


        if ($query) {
            return $this->utilidades->buildResponse(true, 'success', 200, 'Datos Venta', array("datos_venta" => $query));
        } else {
            return $this->utilidades->buildResponse(true, 'success', 200, 'No tiene detalle de ventas');
        }
    }

    public function finalizarVenta($token, $idVenta)
    {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
    }
}
