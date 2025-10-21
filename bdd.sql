CREATE DATABASE `kawak` /*!40100 COLLATE 'utf8_spanish_ci' */;

/* Se adicionan indices únicos para prevenir duplicados */
CREATE TABLE `pro_proceso` (
	`pro_id` INT NOT NULL AUTO_INCREMENT,
	`pro_prefijo` VARCHAR(20) NOT NULL DEFAULT '',
	`pro_nombre` VARCHAR(60) NOT NULL DEFAULT '',
	PRIMARY KEY (`pro_id`),
	UNIQUE INDEX `pro_prefijo_unico` (`pro_prefijo`),
	UNIQUE INDEX `pro_nombre_unico` (`pro_nombre`)
)
COLLATE='utf8_spanish_ci'
;

/* Se adicionan indices únicos para prevenir duplicados */
CREATE TABLE `tip_tipo_doc` (
	`tip_id` INT NOT NULL AUTO_INCREMENT,
	`tip_prefijo` VARCHAR(20) NOT NULL DEFAULT '',
	`tip_nombre` VARCHAR(60) NOT NULL DEFAULT '',
	PRIMARY KEY (`tip_id`),
	UNIQUE INDEX `tip_prefijo_unico` (`tip_prefijo`),
	UNIQUE INDEX `tip_nombre_unico` (`tip_nombre`)
)
COLLATE='utf8_spanish_ci'
;

CREATE TABLE `doc_documento` (
	`doc_id` INT NOT NULL AUTO_INCREMENT,
	`doc_nombre` VARCHAR(60) NOT NULL DEFAULT '',
	`doc_codigo` INT NOT NULL DEFAULT 0,
	`doc_contenido` VARCHAR(4000) NOT NULL DEFAULT '',
	`doc_id_tipo` INT NOT NULL DEFAULT 0,
	`doc_id_proceso` INT NOT NULL DEFAULT 0,
	PRIMARY KEY (`doc_id`),
	INDEX `doc_tipo` (`doc_id_tipo`),
	INDEX `doc_proceso` (`doc_id_proceso`)
)
COLLATE='utf8_spanish_ci'
;

/* Inserta valores preliminares */

INSERT INTO `kawak`.`tip_tipo_doc` (`tip_prefijo`, `tip_nombre`) VALUES ('INS', 'Instructivo');
INSERT INTO `kawak`.`tip_tipo_doc` (`tip_prefijo`, `tip_nombre`) VALUES ('CON', 'Contrato');
INSERT INTO `kawak`.`tip_tipo_doc` (`tip_prefijo`, `tip_nombre`) VALUES ('MAN', 'Manual');
INSERT INTO `kawak`.`tip_tipo_doc` (`tip_prefijo`, `tip_nombre`) VALUES ('FAC', 'Factura');
INSERT INTO `kawak`.`tip_tipo_doc` (`tip_prefijo`, `tip_nombre`) VALUES ('OTR', 'Otro');

INSERT INTO `kawak`.`pro_proceso` (`pro_prefijo`, `pro_nombre`) VALUES ('ING', 'Ingeniería');
INSERT INTO `kawak`.`pro_proceso` (`pro_prefijo`, `pro_nombre`) VALUES ('COM', 'Comercial');
INSERT INTO `kawak`.`pro_proceso` (`pro_prefijo`, `pro_nombre`) VALUES ('ADM', 'Administración');
INSERT INTO `kawak`.`pro_proceso` (`pro_prefijo`, `pro_nombre`) VALUES ('I+D', 'Investigación y Desarrollo');
INSERT INTO `kawak`.`pro_proceso` (`pro_prefijo`, `pro_nombre`) VALUES ('RHH', 'Recursos humanos');
