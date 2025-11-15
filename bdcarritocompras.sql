

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE compra (
  idcompra bigint(20) NOT NULL,
  cofecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  idusuario bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE compraestado (
  idcompraestado bigint(20) UNSIGNED NOT NULL,
  idcompra bigint(11) NOT NULL,
  idcompraestadotipo int(11) NOT NULL,
  cefechaini timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  cefechafin timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE compraestadotipo (
  idcompraestadotipo int(11) NOT NULL,
  cetdescripcion varchar(50) NOT NULL,
  cetdetalle varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO compraestadotipo (idcompraestadotipo, cetdescripcion, cetdetalle) VALUES
(1, 'iniciada', 'cuando el usuario : cliente inicia la compra de uno o mas productos del carrito'),
(2, 'aceptada', 'cuando el usuario administrador da ingreso a uno de las compras en estado = 1 '),
(3, 'enviada', 'cuando el usuario administrador envia a uno de las compras en estado =2 '),
(4, 'cancelada', 'un usuario administrador podra cancelar una compra en cualquier estado y un usuario cliente solo en estado=1 ');


CREATE TABLE compraitem (
  idcompraitem bigint(20) UNSIGNED NOT NULL,
  idproducto bigint(20) NOT NULL,
  idcompra bigint(20) NOT NULL,
  cicantidad int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE menu (
  idmenu bigint(20) NOT NULL,
  menombre varchar(50) NOT NULL COMMENT 'Nombre del item del menu',
  medescripcion varchar(124) NOT NULL COMMENT 'Descripcion mas detallada del item del menu',
  idpadre bigint(20) DEFAULT NULL COMMENT 'Referencia al id del menu que es subitem',
  medeshabilitado timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha en la que el menu fue deshabilitado por ultima vez'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO menu (idmenu, menombre, medescripcion, idpadre, medeshabilitado) VALUES
(7, 'nuevo', 'kkkkk', NULL, NULL),
(8, 'nuevo', 'kkkkk', NULL, NULL),
(9, 'nuevo', 'kkkkk', 7, NULL),
(10, 'nuevo', 'kkkkk', NULL, NULL),
(11, 'nuevo', 'kkkkk', NULL, NULL);


CREATE TABLE menurol (
  idmenu bigint(20) NOT NULL,
  idrol bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE producto (
  idproducto bigint(20) NOT NULL,
  pronombre int(11) NOT NULL,
  prodetalle varchar(512) NOT NULL,
  procantstock int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE rol (
  idrol bigint(20) NOT NULL,
  rodescripcion varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE usuario (
  idusuario bigint(20) NOT NULL,
  usnombre varchar(50) NOT NULL,
  uspass int(11) NOT NULL,
  usmail varchar(50) NOT NULL,
  usdeshabilitado timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE usuariorol (
  idusuario bigint(20) NOT NULL,
  idrol bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE compra
  ADD PRIMARY KEY (idcompra),
  ADD UNIQUE KEY idcompra (idcompra),
  ADD KEY fkcompra_1 (idusuario);


ALTER TABLE compraestado
  ADD PRIMARY KEY (idcompraestado),
  ADD UNIQUE KEY idcompraestado (idcompraestado),
  ADD KEY fkcompraestado_1 (idcompra),
  ADD KEY fkcompraestado_2 (idcompraestadotipo);

ALTER TABLE compraestadotipo
  ADD PRIMARY KEY (idcompraestadotipo);

ALTER TABLE compraitem
  ADD PRIMARY KEY (idcompraitem),
  ADD UNIQUE KEY idcompraitem (idcompraitem),
  ADD KEY fkcompraitem_1 (idcompra),
  ADD KEY fkcompraitem_2 (idproducto);


ALTER TABLE menu
  ADD PRIMARY KEY (idmenu),
  ADD UNIQUE KEY idmenu (idmenu),
  ADD KEY fkmenu_1 (idpadre);

ALTER TABLE menurol
  ADD PRIMARY KEY (idmenu,idrol),
  ADD KEY fkmenurol_2 (idrol);

ALTER TABLE producto
  ADD PRIMARY KEY (idproducto),
  ADD UNIQUE KEY idproducto (idproducto);


ALTER TABLE rol
  ADD PRIMARY KEY (idrol),
  ADD UNIQUE KEY idrol (idrol);


ALTER TABLE usuario
  ADD PRIMARY KEY (idusuario),
  ADD UNIQUE KEY idusuario (idusuario);


ALTER TABLE usuariorol
  ADD PRIMARY KEY (idusuario,idrol),
  ADD KEY idusuario (idusuario),
  ADD KEY idrol (idrol);

ALTER TABLE compra
  MODIFY idcompra bigint(20) NOT NULL AUTO_INCREMENT;


ALTER TABLE compraestado
  MODIFY idcompraestado bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE compraitem
  MODIFY idcompraitem bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE menu
  MODIFY idmenu bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;


ALTER TABLE producto
  MODIFY idproducto bigint(20) NOT NULL AUTO_INCREMENT;


ALTER TABLE rol
  MODIFY idrol bigint(20) NOT NULL AUTO_INCREMENT;


ALTER TABLE usuario
  MODIFY idusuario bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE compra
  ADD CONSTRAINT fkcompra_1 FOREIGN KEY (idusuario) REFERENCES usuario (idusuario) ON UPDATE CASCADE;


ALTER TABLE compraestado
  ADD CONSTRAINT fkcompraestado_1 FOREIGN KEY (idcompra) REFERENCES compra (idcompra) ON UPDATE CASCADE,
  ADD CONSTRAINT fkcompraestado_2 FOREIGN KEY (idcompraestadotipo) REFERENCES compraestadotipo (idcompraestadotipo) ON UPDATE CASCADE;


ALTER TABLE compraitem
  ADD CONSTRAINT fkcompraitem_1 FOREIGN KEY (idcompra) REFERENCES compra (idcompra) ON UPDATE CASCADE,
  ADD CONSTRAINT fkcompraitem_2 FOREIGN KEY (idproducto) REFERENCES producto (idproducto) ON UPDATE CASCADE;


ALTER TABLE menu
  ADD CONSTRAINT fkmenu_1 FOREIGN KEY (idpadre) REFERENCES menu (idmenu) ON UPDATE CASCADE;


ALTER TABLE menurol
  ADD CONSTRAINT fkmenurol_1 FOREIGN KEY (idmenu) REFERENCES menu (idmenu) ON UPDATE CASCADE,
  ADD CONSTRAINT fkmenurol_2 FOREIGN KEY (idrol) REFERENCES rol (idrol) ON UPDATE CASCADE;


ALTER TABLE usuariorol
  ADD CONSTRAINT fkmovimiento_1 FOREIGN KEY (idrol) REFERENCES rol (idrol) ON UPDATE CASCADE,
  ADD CONSTRAINT usuariorol_ibfk_2 FOREIGN KEY (idusuario) REFERENCES usuario (idusuario) ON UPDATE CASCADE;
COMMIT;