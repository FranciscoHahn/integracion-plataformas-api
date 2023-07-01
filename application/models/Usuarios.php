<?php

class Usuarios extends CI_Model {

    private $jwt;
    private $utilidades;

    function __construct() {
        parent::__construct();
        $this->jwt = new JWT();
        $this->utilidades = new Utilidades();
    }

    public function buscarUsuario($nomusuario, $rut, $email) {
        $nombreExiste = $this->db->select('id,nombreusuario, email, rut')
                ->where('nombreusuario', $nomusuario)
                ->or_where('email', $email)
                ->or_where('rut', $rut)
                ->get('usuarios')
                ->result_array();
        return $nombreExiste;
    }

    public function insertarUsuario($token, $nombres, $apellidos, $rut, $nombreusuario, $password, $email, $direccion, $id_perfil) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        if ($this->jwt->getProperty($token, 'perfil_nombre') <> 'Administrador') {
            return $this->utilidades->buildResponse(false, 'failed', 401, 'Perfil no autorizado');
        }
        $inputValidar = array(
            [$nombres, "nombres", "str"],
            [$apellidos, "apellidos", "str"],
            [$rut, "rut", "rut"],
            [$nombreusuario, "nombreusuario", "str"],
            [$email, "email", "email"],
            [$direccion, "direccion", "str"],
            [$password, "password", "str"]
        );
        $validacionesInput = $this->utilidades->validadorInput($inputValidar);
        if ($validacionesInput["error"]) {
            return $this->utilidades->buildResponse(false, 'failed', 422, "inputs con errores", array("errores" => $validacionesInput["resultados"]));
        }
        $usuarioExiste = $this->buscarUsuario($nombreusuario, $rut, $email);
        if ($usuarioExiste) {
            $retorno = $this->utilidades->buildResponse(false, 'failed', 403, 'Usuario existente', array("filas_encontradas" => $usuarioExiste));
        } else {
            $data = array(
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'rut' => $rut,
                'nombreusuario' => $nombreusuario,
                'password' => $password,
                'email' => $email,
                'direccion' => $direccion,
                'token' => '',
                'activo' => 1,
                'id_perfil' => $id_perfil
            );
            $this->db->insert('usuarios', $data);
            $result = array(
                "id_nuevo_usuario" => $this->db->insert_id()
            );
            
            if(!$result["id_nuevo_usuario"]){
                return $this->utilidades->buildResponse(false, 'failed', 400, 'Faltan Datos', null);
            } 
            

            $retorno = $this->utilidades->buildResponse(true, 'success', 200, 'Usuario agregado', $result);
        }
        return $retorno;
    }

    public function modificarUsuario($token, $id, $nombres, $apellidos, $rut, $nombreusuario, $password, $email, $direccion,$idperfil) {
        $retorno = null;
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        if ($this->jwt->getProperty($token, 'perfil_nombre') <> 'Administrador') {
            return $this->utilidades->buildResponse(false, 'failed', 401, 'Perfil no autorizado');
        }
        $inputValidar = array(
            [$nombres, "nombres", "str"],
            [$apellidos, "apellidos", "str"],
            [$rut, "rut", "rut"],
            [$nombreusuario, "nombreusuario", "str"],
            [$email, "email", "email"],
            [$direccion, "direccion", "str"],
            [$password, "password", "str"]
        );
        $validacionesInput = $this->utilidades->validadorInput($inputValidar);
        if ($validacionesInput["error"]) {
            return $this->utilidades->buildResponse(false, "failed", 403, 'inputs con errores',array("errores" => $validacionesInput["resultados"]));
        }
        $query_excluir = $this->buscarExistenteExcluir($id, array('nombreusuario'=> $nombreusuario, 'email' => $email, 'rut' => $rut));
        if($query_excluir){
            return $this->utilidades->buildResponse(false, "failed", 403, 'usuario existente',array("usuarios encontrados"=> $query_excluir));
        }
        
        if (!count($this->buscarUsuarioPorId($id))) {
            return $this->utilidades->buildResponse(false, 'failed', 401, 'Usuario no existe');
        } else {
            $data = array(
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'rut' => $rut,
                'nombreusuario' => $nombreusuario,
                'password' => $password,
                'email' => $email,
                'direccion' => $direccion,
                'token' => '',
                'id_perfil' => $idperfil
            );
            $this->db->where('id', $id)->update('usuarios', $data);
            $result = array(
                "filas_afectadas" => $this->db->affected_rows()
            );

            $retorno = $this->utilidades->buildResponse('true', 'success',200 , 'Usuario modificado', $result);
        }
        return $retorno;
    }

    public function buscarUsuarioPorId($id) {
        return $this->db->where('id', $id)->get('usuarios')->result_array();
    }

    public function autenticarEmail($email, $password) {
        $auth = $this->db->select("u.*, p.nombre as perfil_nombre, case when activo = 1 then 'activo' else 'inactivo' end activo_nombre", false)
                ->from('usuarios u')
                ->join('perfiles p', 'u.id_perfil = p.id', 'left')
                ->where('u.email', $email)
                ->where('u.password', $password)
                ->get()
                ->row();
        if (!$auth) {
            return $this->utilidades->buildResponse(false, 'failed', 404, 'Correo o contraseña incorrectos');
        }
        if (!$auth->activo) {
            return $this->utilidades->buildResponse(false, 'failed', 404, 'Usuario desactivado por administración');
        }
        $tokendata = array(
            "id" => $auth->id,
            "nombres" => $auth->nombres,
            "nombreusuario" => $auth->nombreusuario,
            "apellidos" => $auth->apellidos,
            "email" => $auth->email,
            "direccion" => $auth->direccion,
            "rut" => $auth->rut,
            "perfil_nombre" => $auth->perfil_nombre,
            'exp' => time() + (60 * 60 * 24),
            "usuario_activo" => $auth->activo_nombre
        );
        $token = $this->jwt->generar($tokendata);
        return $this->utilidades->buildResponse(true, 'success', 200, 'Usuario autenticado', array('datos_usuario' => $tokendata, 'token' => $token));
    }

    public function autenticarNomUser($nombreusuario, $password) {
        $auth = $this->db->select("u.*, p.nombre as perfil_nombre, case when activo = 1 then 'activo' else 'inactivo' end activo_nombre", false)
                ->from('usuarios u')
                ->join('perfiles p', 'u.id_perfil = p.id', 'left')
                ->where('u.nombreusuario', $nombreusuario)
                ->where('u.password', $password)
                ->get()
                ->row();
        if (!$auth) {
            return $this->utilidades->buildResponse(false, 'failed', 404, 'Usuario o contraseña incorrectos');
        }
        if (!$auth->activo) {
            return $this->utilidades->buildResponse(false, 'failed', 404, 'Usuario desactivado por administración');
        }
        $tokendata = array(
            "id" => $auth->id,
            "nombres" => $auth->nombres,
            "nombreusuario" => $auth->nombreusuario,
            "apellidos" => $auth->apellidos,
            "email" => $auth->email,
            "direccion" => $auth->direccion,
            "rut" => $auth->rut,
            "perfil_nombre" => $auth->perfil_nombre,
            'exp' => time() + (60 * 60 * 24),
            "usuario_activo" => $auth->activo_nombre
        );
        $token = $this->jwt->generar($tokendata);
        return $this->utilidades->buildResponse(true, 'success', 200, 'Usuario autenticado', array('datos_usuario' => $tokendata, 'token' => $token));
    }

    public function listarUsuarios($token) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        if ($this->jwt->getProperty($token, 'perfil_nombre') <> 'Administrador') {
            return $this->utilidades->buildResponse(false, 'failed', 401, 'Perfil no autorizado');
        }

        $data = $this->db->select("u.*, p.nombre as perfil_nombre, case when activo = 1 then 'activo' else 'inactivo' end activo_nombre", false)
                        ->from('usuarios u')
                        ->join('perfiles p', 'u.id_perfil = p.id', 'left')
                        ->get()->result_array();
        return $this->utilidades->buildResponse(true, 'success', 200, 'Listado de usuarios', array('usuarios' => $data));
    }

    public function listarPerfiles($token) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        $query = $this->db->get('perfiles')->result_array();
        return $this->utilidades->buildResponse(true, 'success', 200, 'Listado de perfiles', array('perfiles' => $query));
    }

    public function eliminarUsuario($token, $id) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        if ($this->jwt->getProperty($token, 'perfil_nombre') <> 'Administrador') {
            return $this->utilidades->buildResponse(false, 'failed', 401, 'Perfil no autorizado');
        }
        if ($this->jwt->getProperty($token, 'id') == $id) {
            return $this->utilidades->buildResponse(false, 'failed', 403, 'no se puede eliminar a si mismo', null);
        }
        //return array($this->jwt->getProperty($token, 'id'), $id);

        if (!$this->buscarPorId($id)) {
            return $this->utilidades->buildResponse(false, 'failed', 403, 'usuario no existe', null);
        }

        $this->db->where('id', $id);
        $this->db->update('usuarios', array('activo' => 0));
        return $this->utilidades->buildResponse(true, 'success', 200, 'usuarios eliminado', array('filas_afectadas' => $this->db->affected_rows()));
    }

    public function restaurarUsuario($token, $id) {
        $verificarExpiracion = $this->jwt->verificarExpiracion($token, 'exp');
        if (!$verificarExpiracion["result"]) {
            return $this->utilidades->buildResponse(false, 'failed', 401, $verificarExpiracion["usrmsg"], $verificarExpiracion);
        }
        if ($this->jwt->getProperty($token, 'perfil_nombre') <> 'Administrador') {
            return $this->utilidades->buildResponse(false, 'failed', 401, 'Perfil no autorizado');
        }

        //return array($this->jwt->getProperty($token, 'id'), $id);

        if ($this->jwt->getProperty($token, 'id') == $id) {
            return $this->utilidades->buildResponse(false, 'failed', 403, 'no se puede activar a si mismo', null);
        }

        if (!$this->buscarPorId($id)) {
            return $this->utilidades->buildResponse(false, 'failed', 403, 'usuario no existe', null);
        }

        $this->db->where('id', $id);
        $this->db->update('usuarios', array('activo' => 1));
        return $this->utilidades->buildResponse(true, 'success', 200, 'usuario activado', array('filas_afectadas' => $this->db->affected_rows()));
    }

    private function buscarPorId($id) {
        return $this->db->select('*')->from('usuarios')->where('id', $id)->get()->result_array();
    }

    public function buscarExistenteExcluir($id_excluir, $datos_busqueda) {
        $this->db->select('id,nombreusuario, email, rut');
        $this->db->from('usuarios');

        $where_clauses = array();
        foreach ($datos_busqueda as $campo => $valor) {
            $where_clauses[] = "$campo = '$valor'";
        }

        $where_clause_string = implode(' OR ', $where_clauses);
        $this->db->where("(" . $where_clause_string . ")");
        $this->db->where_not_in('id', $id_excluir);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getDatosUsuario(){
        
    }

}
