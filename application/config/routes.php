<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	https://codeigniter.com/userguide3/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |	$route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |		my-controller/my-method	-> my_controller/my_method
 */
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;



$route['auth-email'] = 'AutenticacionController/autenticarEmail';
$route['auth-nomusuario'] = 'AutenticacionController/autenticarNomUsuario';
//crud usuarios
$route['listar-usuarios'] = 'UsuariosController/listarUsuarios';
$route['eliminar-usuario'] = 'UsuariosController/eliminarUsuario';
$route['restaurar-usuario'] = 'UsuariosController/restaurarUsuario';
$route['insertar-usuario'] = 'UsuariosController/insertarusuario';
//$route['modificar-usuario']
$route['modificar-usuario'] = 'UsuariosController/modificarUsuario';


$route['listar-perfiles'] = 'UsuariosController/listarPerfiles';


$route['agregar-cliente'] = "ClientesController/agregarCliente";
//autenticarCliente
$route['autenticar-cliente'] = "ClientesController/autenticarCliente";
$route['actualizar-cliente'] = "ClientesController/actualizarCliente";
$route['activar-cliente'] = "ClientesController/activarCliente";
$route['desactivar-cliente'] = "ClientesController/desactivarCliente";
$route['obtener-clientes'] = "ClientesController/obtenerClientes";
//getDatosUsuario
$route['datos-cliente'] = "ClientesController/getDatosCliente";

$route['listar-categorias'] = "CategoriasController/listarCategorias";
$route['insertar-categoria'] = "CategoriasController/insertarCategoria";
$route['actualizar-categoria'] = "CategoriasController/actualizarCategoria";
$route['activar-categoria'] = "CategoriasController/activarCategoria";
$route['desactivar-categoria'] = "CategoriasController/desactivarCategoria";

$route['listar-instrumentos'] = "InstrumentosController/listarInstrumentos";
$route['obtener-instrumento-por-id'] = "InstrumentosController/obtenerInstrumentoPorId";
$route['insertar-instrumento'] = "InstrumentosController/insertarInstrumento";
$route['actualizar-instrumento'] = "InstrumentosController/actualizarInstrumento";
$route['desactivar-instrumento'] = "InstrumentosController/desactivarInstrumento";
$route['activar-instrumento'] = "InstrumentosController/activarInstrumento";
$route['listar-instrumentos-por-categoria'] = "InstrumentosController/obtenerInstrumentosPorCategoria";

$route['transbank'] = 'Welcome/transbank_integracion';

$route['crear-venta'] = 'VentasController/crearVenta';
$route['formas-retiro'] = 'VentasController/formasRetiro';
$route['estados-ventas'] = 'VentasController/estadosVentas';
$route['formas-pago'] = 'VentasController/formasPago';
$route['estados-entrega'] = 'VentasController/estadosEntrega';
$route['listar-ventas'] = 'VentasController/listarVentas';
$route['modificar-estados-venta'] = 'VentasController/modificarEstadosVenta';
$route['agregar-producto-venta'] = 'VentasController/agregarProductoAVenta';
$route['eliminar-producto-venta'] = 'VentasController/eliminarProductoAVenta';
$route['detalle-venta'] = 'VentasController/getDetalleVentas';
$route['datos-venta'] = 'VentasController/getVentaPorId';
