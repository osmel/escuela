-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 14, 2017 at 05:14 PM
-- Server version: 5.5.50-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pepsi`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `SUBSTR_COUNT`(`s` VARCHAR(255), `ss` VARCHAR(255)) RETURNS tinyint(3) unsigned
    READS SQL DATA
BEGIN
      DECLARE v_count, v_haystack_len, v_needle_len, v_offset, v_endpos int unsigned DEFAULT 0;

      SET v_haystack_len = CHAR_LENGTH(in_haystack),
          v_needle_len   = CHAR_LENGTH(in_needle),
          v_offset       = IF(in_offset IS NOT NULL AND in_offset > 0, in_offset, 1),
          v_endpos       = IF(in_length IS NOT NULL AND in_length > 0, v_offset + in_length, v_haystack_len);

      -- The last offset to use with LOCATE is at v_endpos - v_needle_len.
      -- That also means that if v_needlen > v_endpos, the count is trivially 0
      IF (v_endpos > v_needle_len) THEN
         SET v_endpos = v_endpos - v_needle_len;
         WHILE (v_offset < v_endpos) DO
            SET v_offset = LOCATE(in_needle, in_haystack, v_offset);
            IF (v_offset > 0) THEN
               -- v_offset is now the position of the first letter in the needle.
               -- Skip the length of the needle to avoid double counting.
               SET v_count  = v_count  + 1,
                  v_offset = v_offset + v_needle_len;
            ELSE
               -- The needle was not found. Set v_offset = v_endpos to exit the loop.
               SET v_offset = v_endpos;
            END IF;
         END WHILE;
      END IF;

      RETURN v_count;
   END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pepsi_catalogo_configuraciones`
--

CREATE TABLE IF NOT EXISTS `pepsi_catalogo_configuraciones` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `configuracion` text NOT NULL,
  `valor` varchar(100) NOT NULL,
  `activo` bigint(1) NOT NULL DEFAULT '0',
  `tooltip` varchar(256) NOT NULL,
  `consecutivo` int(11) NOT NULL,
  `fecha_pc` int(11) NOT NULL,
  `id_usuario` varchar(36) NOT NULL,
  `fecha_mac` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `orden` int(11) NOT NULL,
  `grupo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pepsi_catalogo_configuraciones`
--

INSERT INTO `pepsi_catalogo_configuraciones` (`id`, `configuracion`, `valor`, `activo`, `tooltip`, `consecutivo`, `fecha_pc`, `id_usuario`, `fecha_mac`, `orden`, `grupo`) VALUES
(1, 'correo', 'marketing@pepsi.mx', 0, '', 0, 0, '', '2017-03-14 20:03:09', 0, ''),
(2, 'Nombre sistema', 'Prueba de pruebas', 0, '', 0, 0, '', '2017-03-14 20:34:35', 0, ''),
(3, 'T√©lefono', '(55) 3730-4166 ext. 2222', 0, '', 0, 0, '', '2017-03-14 20:29:30', 0, ''),
(4, 'Ubicaci√≥n Logo', 'img/candidatos_ico.png', 0, '', 0, 0, '', '2017-03-14 22:04:29', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `pepsi_catalogo_estados`
--

CREATE TABLE IF NOT EXISTS `pepsi_catalogo_estados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `id_usuario` varchar(36) NOT NULL,
  `fecha_mac` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `pepsi_catalogo_estados`
--

INSERT INTO `pepsi_catalogo_estados` (`id`, `nombre`, `id_usuario`, `fecha_mac`) VALUES
(1, 'Aguascalientes', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(2, 'Baja California', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(3, 'Baja California Sur', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(4, 'Campeche', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(5, 'Chiapas', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(6, 'Chihuahua', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(7, 'Coahuila', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(8, 'Colima', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(9, 'Distrito Federal', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(10, 'Durango', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(11, 'Estado de M√©xico', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(12, 'Guanajuato', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(13, 'Guerrero', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(14, 'Hidalgo', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(15, 'Jalisco', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(16, 'Michoac√°n', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(17, 'Morelos', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(18, 'Nayarit', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(19, 'Nuevo Le√≥n', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(20, 'Oaxaca', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(21, 'Puebla', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(22, 'Quer√©taro', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(23, 'Quintana Roo', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(24, 'San Luis Potos√≠', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(25, 'Sinaloa', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(26, 'Sonora', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(27, 'Tabasco', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(28, 'Tamaulipas', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(29, 'Tlaxcala', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(30, 'Veracruz', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(31, 'Yucat√°n', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04'),
(32, 'Zacatecas', '65350f7e-d031-11e5-b036-04015a6da701', '2017-02-13 20:57:04');

-- --------------------------------------------------------

--
-- Table structure for table `pepsi_catalogo_operaciones`
--

CREATE TABLE IF NOT EXISTS `pepsi_catalogo_operaciones` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `operacion` text NOT NULL,
  `tooltip` varchar(256) NOT NULL,
  `consecutivo` int(11) NOT NULL,
  `conse_factura` int(11) NOT NULL,
  `conse_remision` int(11) NOT NULL,
  `conse_surtido` int(11) NOT NULL,
  `conse_ajuste_factura` int(11) NOT NULL,
  `conse_ajuste_remision` int(11) NOT NULL,
  `fecha_pc` int(11) NOT NULL,
  `id_usuario` varchar(36) NOT NULL,
  `fecha_mac` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `orden` int(11) NOT NULL,
  `grupo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pepsi_historico_acceso`
--

CREATE TABLE IF NOT EXISTS `pepsi_historico_acceso` (
  `id_usuario` varchar(36) DEFAULT NULL,
  `email` varbinary(128) DEFAULT NULL,
  `id_perfil` int(2) DEFAULT NULL,
  `fecha` int(11) DEFAULT NULL,
  `estatus` varchar(1) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(120) NOT NULL,
  `fecha_mac` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pepsi_perfiles`
--

CREATE TABLE IF NOT EXISTS `pepsi_perfiles` (
  `id_perfil` int(2) NOT NULL,
  `perfil` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `operacion` varchar(10) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'R' COMMENT 'CRUD V-Votar F-Finalista M-Mover obsoleto',
  PRIMARY KEY (`id_perfil`),
  KEY `id_perfil` (`id_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `pepsi_perfiles`
--

INSERT INTO `pepsi_perfiles` (`id_perfil`, `perfil`, `operacion`) VALUES
(1, 'Administrador', 'CRUDFGM'),
(2, 'Operador', 'CRUD'),
(3, 'Cliente', 'R');

-- --------------------------------------------------------

--
-- Table structure for table `pepsi_sessions`
--

CREATE TABLE IF NOT EXISTS `pepsi_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pepsi_sessions`
--

INSERT INTO `pepsi_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('9c050687ae4bd93f14c707faf2b497ae', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.82 Safari/537.36', 1489529384, 'a:13:{s:9:"user_data";s:0:"";s:2:"c1";s:18:"marketing@pepsi.mx";s:2:"c2";s:17:"Prueba de pruebas";s:2:"c3";s:24:"(55) 3730-4166 ext. 2222";s:2:"c4";s:22:"img/candidatos_ico.png";s:7:"session";b:1;s:5:"email";s:24:"osmel.calderon@gmail.com";s:2:"id";s:36:"089bb34d-95f6-11e4-bc9e-7071bce181c3";s:9:"id_perfil";s:1:"1";s:8:"especial";s:1:"1";s:6:"perfil";s:13:"Administrador";s:9:"operacion";s:7:"CRUDFGM";s:15:"nombre_completo";s:14:"osmel calderon";}');

-- --------------------------------------------------------

--
-- Table structure for table `pepsi_usuarios`
--

CREATE TABLE IF NOT EXISTS `pepsi_usuarios` (
  `id` varchar(36) NOT NULL,
  `email` varbinary(128) NOT NULL,
  `contrasena` varbinary(128) NOT NULL,
  `creacion` int(11) NOT NULL,
  `telefono` varbinary(128) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `activo` tinyint(2) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `Apellidos` varchar(40) NOT NULL,
  `estado` int(11) NOT NULL,
  `id_perfil` int(2) NOT NULL,
  `fecha_pc` int(11) NOT NULL,
  `id_usuario` varchar(36) NOT NULL,
  `fecha_mac` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `coleccion_id_operaciones` varchar(80) NOT NULL DEFAULT '[]',
  `coleccion_id_almacenes` varchar(80) NOT NULL DEFAULT '[]',
  `id_cliente` int(11) NOT NULL,
  `especial` int(10) NOT NULL DEFAULT '0',
  UNIQUE KEY `uid` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pepsi_usuarios`
--

INSERT INTO `pepsi_usuarios` (`id`, `email`, `contrasena`, `creacion`, `telefono`, `extension`, `activo`, `nombre`, `Apellidos`, `estado`, `id_perfil`, `fecha_pc`, `id_usuario`, `fecha_mac`, `coleccion_id_operaciones`, `coleccion_id_almacenes`, `id_cliente`, `especial`) VALUES
('bc2ba62b-fda1-11e4-a055-deadbe003700', 'Ê?Ò¬u˙™ﬂ›ë™m±"í¶/•ÌhÅA¨⁄¶Q`K\r>', '#RﬁÿæüÓmÛ°Ω|¶', 1431983181, 'ˆ∑ß*ÖG˚A•qÂFA˜ìŸ', '', 0, 'Adrian', 'Guerrero', 0, 1, 1489529438, '089bb34d-95f6-11e4-bc9e-7071bce181c3', '2017-03-14 22:10:38', '[]', '[]', 0, 1),
('089bb34d-95f6-11e4-bc9e-7071bce181c3', '•» )Q‚Y≈Aêí''3≤W¡_ÔRü‘qVê*‚', '#RﬁÿæüÓmÛ°Ω|¶', 1420584452, 'ˆ∑ß*ÖG˚A•qÂFA˜ìŸ', '', 0, 'osmel', 'calderon', 0, 1, 1489514383, '089bb34d-95f6-11e4-bc9e-7071bce181c3', '2017-03-14 17:59:43', '["9"]', '[]', 6, 1),
('dfebf44e-ff32-11e4-a055-deadbe003700', 'âÒîåh4,/c˝9†Hç"*|„f¥ìNÕ∆Ém‚|œU', 'eÄ±Ô˚§¥>xN≈âC¢_', 1432155469, 'í¶/•ÌhÅA¨⁄¶Q`K\r>', '', 0, 'Miguel Angel', 'Santa Olalla', 0, 4, 1432314721, '089bb34d-95f6-11e4-bc9e-7071bce181c3', '2015-05-22 17:12:01', '[]', '[]', 0, 0),
('81823a6f-ff33-11e4-a055-deadbe003700', '+¯¢Å˛.∏Wdºƒ;-ŒSÿ\r-ª$≥\r…ª˙(yG√q®¯π·ª8u»¬ïÈYó⁄ò', '5ﬁíùy£"é%≤∑ÄÕßgÈ', 1432155740, 'í¶/•ÌhÅA¨⁄¶Q`K\r>', '', 0, 'Asesor', 'Cuautla', 0, 4, 1434648395, 'bc2ba62b-fda1-11e4-a055-deadbe003700', '2015-06-18 17:26:35', '[]', '[]', 0, 0),
('d4b9bcc2-ff33-11e4-a055-deadbe003700', 'Ec˚Œ–5ì8ìn≈9Ôd°‚|„f¥ìNÕ∆Ém‚|œU', 'ÕaWQè Uå•i.æã¶¥', 1432155880, 'í¶/•ÌhÅA¨⁄¶Q`K\r>', '', 0, 'Esmeralda', 'Ocampo', 0, 5, 1432314798, '089bb34d-95f6-11e4-bc9e-7071bce181c3', '2015-05-22 17:13:18', '[]', '[]', 0, 0),
('c0e27311-139e-11e5-a055-deadbe003700', '+¯¢Å˛.∏Wdºƒ;-ŒSRg¨37»X∏ÂÏçÊôK˚ıÊ	fº3¯p∂ezá⁄ôcﬁ', '©Éu–Ü◊‰ó∑AàÀ∞yûSùó¶¸åÔ+)‡Èß', 1434400826, 'í¶/•ÌhÅA¨⁄¶Q`K\r>', '', 0, 'aseror', 'Cuernavaca', 0, 5, 1434400826, 'bc2ba62b-fda1-11e4-a055-deadbe003700', '2015-06-15 20:40:26', '[]', '[]', 0, 0),
('efeab329-153b-11e5-a055-deadbe003700', 'l…^ï.®Úâ]}œ∞@·ÄÎ*‡ÿsF\rû≤SçqÂ4', 'Ã”b≥b Ú€æ»=ø¢D\0', 1434578287, 'óﬁ‹øàÒÅVq\\l≈êK', '', 0, 'Usuario', 'Remarketing', 0, 1, 1435167608, '089bb34d-95f6-11e4-bc9e-7071bce181c3', '2015-06-24 17:40:08', '[]', '[]', 0, 3);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
