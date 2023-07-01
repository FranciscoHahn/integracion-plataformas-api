<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Instrumentos extends CI_Model {

    private $jwt;
    private $utilidades;

    public function __construct() {
        parent::__construct();
        $this->jwt = new JWT();
        $this->load->database(); // Carga la base de datos configurada en CodeIgniter
        $this->utilidades = new Utilidades();
    }

    public function getInstrumentos($token, $activos = null) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        $this->db->select("instrumentos.*, case when instrumentos.activo = 1 then 'activo' else 'inactivo' end as activo_instrumento, categorias.nombre as nombre_categoria")
                ->from('instrumentos')
                ->join('categorias', 'categorias.id = instrumentos.categoria_id', 'left');

        if ($activos == 'inactivos') {
            $this->db->where('instrumentos.activo', 0);
        }

        if ($activos == 'activos') {
            $this->db->where('instrumentos.activo', 1);
        }

        $instrumentos = $this->db->get()->result_array();
        $response = $this->utilidades->buildResponse(true, 'success', 200, 'Instrumentos obtenidos correctamente', $instrumentos);
        return $response;
    }

    public function getInstrumentosByCategoria($token, $categoriaId) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        $this->db->select("instrumentos.*, case when instrumentos.activo = 1 then 'activo' else 'inactivo' end as activo_instrumento, categorias.nombre as nombre_categoria")
                ->from('instrumentos')
                ->join('categorias', 'categorias.id = instrumentos.categoria_id', 'left')
                ->where('instrumentos.categoria_id', $categoriaId);
        $instrumentos = $this->db->get()->result_array();
        $response = $this->utilidades->buildResponse(true, 'success', 200, 'Instrumentos de la categoría obtenidos correctamente', $instrumentos);
        return $response;
    }

    public function getInstrumentoById($token, $instrumentoId) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        $this->db->select("instrumentos.*, case when instrumentos.activo = 1 then 'activo' else 'inactivo' end as activo_instrumento, categorias.nombre as nombre_categoria")
                ->from('instrumentos')
                ->join('categorias', 'categorias.id = instrumentos.categoria_id', 'left')
                ->where('instrumentos.id', $instrumentoId);
        $instrumento = $this->db->get()->row_array();
        if ($instrumento) {
            $instrumento['activo'] = ($instrumento['activo'] == 1) ? 'activo' : 'inactivo';
            $response = $this->utilidades->buildResponse(true, 'success', 200, 'Instrumento obtenido correctamente', $instrumento);
        } else {
            $response = $this->utilidades->buildResponse(false, 'failed', 404, 'Instrumento no encontrado', null);
        }
        return $response;
    }

    public function insertarInstrumento($token, $nombre, $categoriaId, $precio, $stock, $descripcion, $imagen, $marca) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }

        // Verificar si el nombre del instrumento ya existe
        $this->db->where('nombre', $nombre);
        $this->db->from('instrumentos');
        $count = $this->db->count_all_results();

        if ($count > 0) {
            $response = $this->utilidades->buildResponse(false, 'failed', 400, 'El nombre del instrumento ya existe', null);
            return $response;
        }

        // Insertar el instrumento
        $data = array(
            'nombre' => $nombre,
            'categoria_id' => $categoriaId,
            'precio' => $precio,
            'stock' => $stock,
            'descripcion' => $descripcion,
            'imagen' => $imagen,
            'marca' => $marca
        );

        $this->db->insert('instrumentos', $data);
        $insertId = $this->db->insert_id();
        if ($insertId) {
            $response = $this->utilidades->buildResponse(true, 'success', 200, 'Instrumento insertado correctamente', array("insert_id" => $insertId));
        } else {
            $response = $this->utilidades->buildResponse(false, 'failed', 500, 'Error al insertar el instrumento', null);
        }

        return $response;
    }

    public function actualizarInstrumento($token, $instrumentoId, $nombre, $categoriaId, $precio, $stock, $descripcion, $imagen, $marca) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }

        // Verificar si existe otro instrumento con el mismo nombre
        $this->db->where('nombre', $nombre);
        $this->db->where('id !=', $instrumentoId);
        $this->db->from('instrumentos');
        $count = $this->db->count_all_results();

        if ($count > 0) {
            $response = $this->utilidades->buildResponse(false, 'failed', 400, 'Ya existe otro instrumento con el mismo nombre', null);
            return $response;
        }

        // Actualizar el instrumento
        $data = array(
            'nombre' => $nombre,
            'categoria_id' => $categoriaId,
            'precio' => $precio,
            'stock' => $stock,
            'descripcion' => $descripcion,
            'imagen' => $imagen,
            'marca' => $marca
        );

        $this->db->where('id', $instrumentoId);
        $this->db->update('instrumentos', $data);

        if ($this->db->affected_rows() > 0) {
            $response = $this->utilidades->buildResponse(true, 'success', 200, 'Instrumento actualizado correctamente', null);
        } else {
            $response = $this->utilidades->buildResponse(false, 'failed', 500, 'Error al actualizar el instrumento', null);
        }

        return $response;
    }

    public function desactivarInstrumento($token, $instrumentoId) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }

        // Desactivar el instrumento
        $data = array(
            'activo' => 0
        );

        $this->db->where('id', $instrumentoId);
        $this->db->update('instrumentos', $data);

        if ($this->db->affected_rows() > 0) {
            $response = $this->utilidades->buildResponse(true, 'success', 200, 'Instrumento desactivado correctamente', null);
        } else {
            $response = $this->utilidades->buildResponse(false, 'failed', 500, 'Error al desactivar el instrumento', null);
        }

        return $response;
    }

    public function activarInstrumento($token, $instrumentoId) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }

        // Activar el instrumento
        $data = array(
            'activo' => 1
        );

        $this->db->where('id', $instrumentoId);
        $this->db->update('instrumentos', $data);

        if ($this->db->affected_rows() > 0) {
            $response = $this->utilidades->buildResponse(true, 'success', 200, 'Instrumento activado correctamente', null);
        } else {
            $response = $this->utilidades->buildResponse(false, 'failed', 500, 'Error al activar el instrumento', null);
        }

        return $response;
    }

}
