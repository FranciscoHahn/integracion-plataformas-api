<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categoria extends CI_Model {

    private $jwt;
    private $utilidades;

    public function __construct() {
        parent::__construct();
        $this->jwt = new JWT();
        $this->load->database(); // Carga la base de datos configurada en CodeIgniter
        $this->utilidades = new Utilidades();
    }

    public function obtenerCategorias($token, $activos = null) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        $this->db->select('*')->from('categorias');
        if ($activos == 'inactivos') {
            $this->db->where('activo', 0);
        }
        if ($activos == 'activos') {
            $this->db->where('activo', 1);
        }
        $categorias = $this->db->get()->result_array();
        $response = $this->utilidades->buildResponse(true, 'success', 200, 'Categorías obtenidas activas correctamente', $categorias);
        return $response;
    }

    public function obtenerCategoria($token, $categoriaId) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        $this->db->select('*')->from('categorias')->where('id', $categoriaId);
        $categoria = $this->db->get()->row_array();

        if (!$categoria) {
            $response = $this->utilidades->buildResponse(false, 'failed', 404, 'No se encontró la categoría especificada', null);
        } else {
            $response = $this->utilidades->buildResponse(true, 'success', 200, 'Categoría obtenida correctamente', $categoria);
        }

        return $response;
    }

    public function insertarCategoria($token, $nombre, $descripcion) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }

        // Verificar si ya existe una categoría con el mismo nombre
        $this->db->select('*')->from('categorias')->where('nombre', $nombre);
        $categoriaExistente = $this->db->get()->row_array();

        if ($categoriaExistente) {
            $response = $this->utilidades->buildResponse(false, 'failed', 400, 'Ya existe una categoría con el mismo nombre', null);
            return $response;
        }

        // Insertar la categoría
        $data = array(
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'activo' => 1
        );

        $this->db->insert('categorias', $data);
        $categoriaId = $this->db->insert_id();

        if ($categoriaId) {
            $response = $this->utilidades->buildResponse(true, 'success', 200, 'Categoría insertada correctamente', array('categoria_id' => $categoriaId));
        } else {
            $response = $this->utilidades->buildResponse(false, 'failed', 500, 'Error al insertar la categoría', null);
        }

        return $response;
    }

    public function actualizarCategoria($token, $categoriaId, $nombre, $descripcion) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }

        // Verificar si existe otra categoría con el mismo nombre
        $this->db->select('*')->from('categorias')->where('trim(UPPER(nombre))', strtoupper($nombre))->where_not_in('id', $categoriaId);
        $otraCategoriaExistente = $this->db->get()->row_array();

        if ($otraCategoriaExistente) {
            $response = $this->utilidades->buildResponse(false, 'failed', 409, 'Ya existe otra categoría con el mismo nombre', null);
            return $response;
        }

        // Verificar si la categoría existe
        $this->db->select('*')->from('categorias')->where('id', $categoriaId);
        $categoriaExistente = $this->db->get()->row_array();

        if (!$categoriaExistente) {
            $response = $this->utilidades->buildResponse(false, 'failed', 404, 'La categoría no existe', null);
            return $response;
        }

        // Actualizar la categoría
        $data = array(
            'nombre' => $nombre,
            'descripcion' => $descripcion
        );

        $this->db->where('id', $categoriaId);
        $this->db->update('categorias', $data);

        $affectedRows = $this->db->affected_rows();

        if ($affectedRows > 0) {
            $response = $this->utilidades->buildResponse(true, 'success', 200, 'Categoría actualizada correctamente', null);
        } else {
            $response = $this->utilidades->buildResponse(false, 'failed', 500, 'Error al actualizar la categoría', null);
        }

        return $response;
    }

    public function activarCategoria($token, $categoriaId) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }

        // Verificar si la categoría existe
        $this->db->select('*')->from('categorias')->where('id', $categoriaId);
        $categoriaExistente = $this->db->get()->row_array();

        if (!$categoriaExistente) {
            $response = $this->utilidades->buildResponse(false, 'failed', 404, 'La categoría no existe', null);
            return $response;
        }

        // Obtener el estado actual de la categoría
        $activoActual = $categoriaExistente['activo'];

        // Verificar si la categoría ya está activa
        if ($activoActual == 1) {
            $response = $this->utilidades->buildResponse(false, 'failed', 400, 'La categoría ya está activa', null);
            return $response;
        }

        // Activar la categoría
        $data = array(
            'activo' => 1
        );

        $this->db->where('id', $categoriaId);
        $this->db->update('categorias', $data);

        $affectedRows = $this->db->affected_rows();

        if ($affectedRows > 0) {
            $response = $this->utilidades->buildResponse(true, 'success', 200, 'Categoría activada correctamente', null);
        } else {
            $response = $this->utilidades->buildResponse(false, 'failed', 500, 'Error al activar la categoría', null);
        }

        return $response;
    }

    public function desactivarCategoria($token, $categoriaId) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }

        // Verificar si la categoría existe
        $this->db->select('*')->from('categorias')->where('id', $categoriaId);
        $categoriaExistente = $this->db->get()->row_array();

        if (!$categoriaExistente) {
            $response = $this->utilidades->buildResponse(false, 'failed', 404, 'La categoría no existe', null);
            return $response;
        }

        // Obtener el estado actual de la categoría
        $activoActual = $categoriaExistente['activo'];

        // Verificar si la categoría ya está desactivada
        if ($activoActual == 0) {
            $response = $this->utilidades->buildResponse(false, 'failed', 400, 'La categoría ya está desactivada', null);
            return $response;
        }

        // Desactivar la categoría
        $data = array(
            'activo' => 0
        );

        $this->db->where('id', $categoriaId);
        $this->db->update('categorias', $data);

        $affectedRows = $this->db->affected_rows();

        if ($affectedRows > 0) {
            $response = $this->utilidades->buildResponse(true, 'success', 200, 'Categoría desactivada correctamente', null);
        } else {
            $response = $this->utilidades->buildResponse(false, 'failed', 500, 'Error al desactivar la categoría', null);
        }

        return $response;
    }

}
