-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 30, 2023 at 06:31 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `integracion-plataformas`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `descripcion` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `activo`, `descripcion`) VALUES
(1, 'Cuerdas', 1, ''),
(2, 'Pianos', 1, ''),
(3, 'Baterías', 1, ''),
(4, 'Vientos', 1, ''),
(5, 'Teclados', 1, ''),
(6, 'windies', 1, 'testing catergoira modificacion');

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(500) DEFAULT NULL,
  `apellido` varchar(500) DEFAULT NULL,
  `email` varchar(500) NOT NULL,
  `telefono` int(11) DEFAULT NULL,
  `password` int(11) NOT NULL,
  `activo` int(11) DEFAULT NULL,
  `token` varchar(5000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `email`, `telefono`, `password`, `activo`, `token`) VALUES
(1, 'francisco', 'hahn', 'f@f.cl', 99999999, 123456, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOiIxIiwiaWRfY2xpZW50ZSI6IjEiLCJub21icmUiOiJmcmFuY2lzY28iLCJhcGVsbGlkbyI6ImhhaG4iLCJlbWFpbCI6ImZAZi5jbCIsImV4cCI6MTY4NTQ5OTczMiwicGVyZmlsIjpbeyJpZCI6Ijk5OTk5Iiwibm9tYnJlIjoiQ2xpZW50ZSJ9XSwicGVyZmlsZXMiOlt7ImlkIjoiOTk5OTkiLCJub21icmUiOiJDbGllbnRlIn1dLCJwcm9maWxlIjoiQ2xpZW50ZSIsInBlcmZpbF9ub21icmUiOiJDbGllbnRlIn0._iv8l2UdGnU-ai8K89DVOuwz09eFBQvUscm88uvSbhY'),
(2, 'francisco', 'hahn', 'uu@uu.cl', 99999999, 123456, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOiIyIiwiaWRfY2xpZW50ZSI6IjIiLCJub21icmUiOiJmcmFuY2lzY28iLCJhcGVsbGlkbyI6ImhhaG4iLCJlbWFpbCI6InV1QHV1LmNsIiwiZXhwIjoxNjg1NDA0MjIyLCJwZXJmaWwiOlt7ImlkIjoiOTk5OTkiLCJub21icmUiOiJDbGllbnRlIn1dLCJwZXJmaWxlcyI6W3siaWQiOiI5OTk5OSIsIm5vbWJyZSI6IkNsaWVudGUifV0sInByb2ZpbGUiOiJDbGllbnRlIiwicGVyZmlsX25vbWJyZSI6IkNsaWVudGUifQ.m6sZND8o9Z8wdKesHvrL-FYyHTqo7gnvRPGKrBEfkT4');

-- --------------------------------------------------------

--
-- Table structure for table `instrumentos`
--

CREATE TABLE `instrumentos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `marca` varchar(200) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(500) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instrumentos`
--

INSERT INTO `instrumentos` (`id`, `nombre`, `marca`, `categoria_id`, `precio`, `stock`, `descripcion`, `imagen`, `activo`) VALUES
(1, 'Guitarra Acústica', 'Marca 1', 1, 360000.00, 10, 'Guitarra acústica de cuerpo sólido', 'guitarra_acustica.jpg', 1),
(2, 'Guitarra Eléctrica', 'Marca 2', 1, 680000.00, 5, 'Guitarra eléctrica de 6 cuerdas', 'guitarra_electrica.jpg', 1),
(3, 'Piano de Cola', 'Marca 2', 2, 600000.00, 2, 'Piano de cola de concierto', 'piano_cola.jpg', 1),
(4, 'Batería Completa', 'Marca 1', 3, 670000.00, 3, 'Set completo de batería con platillos', 'bateria_completa.jpg', 1),
(5, 'Saxofón Alto', 'Marca 3', 4, 540000.00, 8, 'Saxofón alto profesional en tono de Mi bemol', 'saxofon_alto.jpg', 1),
(6, 'Teclado Digital', 'Marca 3', 5, 370000.00, 7, 'Teclado digital con 61 teclas y funciones de grabación', 'teclado_digital.jpg', 1),
(7, 'Trompeta', 'Marca 1', 4, 670000.00, 12, 'Trompeta de latón en tono de Si bemol', 'trompeta.jpg', 1),
(8, 'test-instrumento', 'marca de test', 1, 999999.00, 99999, 'hola soy test', NULL, 1),
(9, 'test-instrumento222', 'marca de test mod', 1, 999999.00, 99999, 'hola soy test mod', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `perfiles`
--

CREATE TABLE `perfiles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perfiles`
--

INSERT INTO `perfiles` (`id`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Vendedor'),
(3, 'Bodeguero'),
(4, 'Contador');

-- --------------------------------------------------------

--
-- Table structure for table `producto_ventas`
--

CREATE TABLE `producto_ventas` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `instrumento_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `producto_ventas`
--

INSERT INTO `producto_ventas` (`id`, `venta_id`, `instrumento_id`, `cantidad`, `precio_unitario`, `subtotal`, `activo`) VALUES
(1, 2, 2, 4, 680000.00, 2720000.00, 1),
(2, 2, 2, 0, 0.00, 0.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombres` varchar(2000) DEFAULT NULL,
  `nombreusuario` varchar(2000) DEFAULT NULL,
  `apellidos` varchar(2000) DEFAULT NULL,
  `token` varchar(2000) DEFAULT NULL,
  `password` varchar(2000) DEFAULT NULL,
  `email` varchar(2000) DEFAULT '0',
  `direccion` varchar(2000) DEFAULT NULL,
  `rut` varchar(2000) DEFAULT NULL,
  `id_perfil` int(11) NOT NULL,
  `activo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `nombreusuario`, `apellidos`, `token`, `password`, `email`, `direccion`, `rut`, `id_perfil`, `activo`) VALUES
(1, 'usuarios', 'usuariotest', 'usuarios', '', '123456', 'mail@mail.cl', 'direccion direccioncita', 'rut-rut', 1, 1),
(2, 'usuarios', 'usuariotest2', 'usuarios', '', '123456', 'mail2@mail.cl', 'direccion direccioncita', 'rut-rut-2', 1, 1),
(3, 'usuarios', 'usuariotest3', 'usuarios', '', '123456', 'mail3@mail.cl', 'direccion direccioncita', 'rut-rut-3', 1, 1),
(4, 'nombre persona', 'persona 5555555555', 'apellidos persona 4', '', '123456', 'mail@mailcito.cl', 'direccion persdona 4', 'rut-person-4', 1, 1),
(5, 'nombre persona', 'persona45', 'apellidos persona 4', '', '123456', 'mail@mailcito244.cl', 'direccion persdona 4', '11111111-1', 4, 1),
(7, 'nombre persona', 'personana-44', 'apellidos persona 4', '', '123456', 'mail@mailsito22222222.cl', 'direccion persdona 4', 'rut-persona-4', 1, 1),
(8, 'nombre persona', 'persona15', 'apellidos persona 4', '', '123456', 'm@m.cl', 'direccion persdona 4', '15095150-k', 1, 1),
(9, 'nombre persona', 'persona155', 'apellidos persona 4', '', '123456', 'uuu@uu.cl', 'direccion persdona 4', '60904367-9', 1, 1),
(10, 'nombre persona', 'persona1555', 'apellidos persona 4', '', '123456', 'uuu@uuuu.cl', 'direccion persdona 4', '53107839-K', 1, 1),
(11, 'nombre persona', 'persona15550', 'apellidos persona 4', '', '123456', 'uuu@uu00uu.cl', 'direccion persdona 4', '10213938-0', 1, 1),
(12, 'nombre persona', 'persona15550y', 'apellidos persona 4', '', '123456', 'yyyyyy@uu00uu.cl', 'direccion persdona 4', '56513012-9', 1, 1),
(13, 'nombre persona', 'persona15550yaa', 'apellidos persona 4', '', '123456', 'yyyyyy@uu0a0uu.cl', 'direccion persdona 4', '93428072-5', 1, 1),
(14, 'nombre persona', 'persona15550yaaaaa', 'apellidos persona 4', '', '123456', 'yyyyyy@uu0a0aaaauu.cl', 'direccion persdona 4', '19625932-5', 1, 1),
(15, 'nombre persona', 'persona4555', 'apellidos persona 4', '', '123456', 'f@ffff.cl', 'direccion persdona 4', '18382373-6', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado_pago` varchar(100) DEFAULT NULL,
  `estado_entrega` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `buy_order` varchar(50) DEFAULT NULL,
  `transaction_date` varchar(5000) DEFAULT NULL,
  `transaction_status` varchar(500) DEFAULT NULL,
  `forma_retiro` varchar(500) DEFAULT NULL,
  `direccion_despacho` varchar(500) DEFAULT NULL,
  `forma_de_pago` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_id`, `fecha`, `estado_pago`, `estado_entrega`, `activo`, `buy_order`, `transaction_date`, `transaction_status`, `forma_retiro`, `direccion_despacho`, `forma_de_pago`) VALUES
(1, 1, '2023-05-30 02:26:43', 'Creada', 'En Preparación', 1, NULL, NULL, NULL, 'A domicilio', 'Calle alvaro santa maría número 5 viña del mar', 'no ingresada'),
(2, 1, '2023-05-30 02:28:42', 'Pagada', 'En preparación', 1, NULL, NULL, NULL, 'A domicilio', 'Calle alvaro santa maría número 5 viña del mar', 'Transferencia');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instrumentos`
--
ALTER TABLE `instrumentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_instrumentos_categorias` (`categoria_id`);

--
-- Indexes for table `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `producto_ventas`
--
ALTER TABLE `producto_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_producto_ventas_ventas` (`venta_id`),
  ADD KEY `fk_producto_ventas_instrumentos` (`instrumento_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuarios_perfiles` (`id_perfil`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ventas_clientes` (`cliente_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `instrumentos`
--
ALTER TABLE `instrumentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `producto_ventas`
--
ALTER TABLE `producto_ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `instrumentos`
--
ALTER TABLE `instrumentos`
  ADD CONSTRAINT `fk_instrumentos_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Constraints for table `producto_ventas`
--
ALTER TABLE `producto_ventas`
  ADD CONSTRAINT `fk_producto_ventas_instrumentos` FOREIGN KEY (`instrumento_id`) REFERENCES `instrumentos` (`id`),
  ADD CONSTRAINT `fk_producto_ventas_ventas` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_perfiles` FOREIGN KEY (`id_perfil`) REFERENCES `perfiles` (`id`);

--
-- Constraints for table `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_ventas_clientes` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;