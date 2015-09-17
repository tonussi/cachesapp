ALTER TABLE `usuarios_caches` DROP FOREIGN KEY `usuarios_caches_fk0`;

ALTER TABLE `usuarios_caches` DROP FOREIGN KEY `usuarios_caches_fk1`;

DROP TABLE IF EXISTS `caches`;

DROP TABLE IF EXISTS `usuarios`;

DROP TABLE IF EXISTS `usuarios_caches`;

CREATE TABLE `caches` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`address_size_bits` INT NOT NULL,
	`tag_size_bits` INT NOT NULL,
	`offset_size_bits` INT NOT NULL,
	`control_size_bits` INT NOT NULL,
	`index_size_size` INT NOT NULL,
	`ways_size` INT NOT NULL,
	CHECK (`control_size_bits` <= 3 and `control_size_bits` >= 0),
	CHECK (`address_size_bits` = `tag_size_bits` + `offset_size_bits` + `index_size_size`),
	PRIMARY KEY (`id`)
);

CREATE TABLE `usuarios` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`nome` TEXT NOT NULL,
	`curso` TEXT NOT NULL,
	CHECK (`nome` <> '' or `nome` <> NULL),
	PRIMARY KEY (`id`)
);

CREATE TABLE `usuarios_caches` (
	`idusuario` INT NOT NULL,
	`idcache` INT NOT NULL,
	PRIMARY KEY (`idusuario`,`idcache`)
);

ALTER TABLE `usuarios_caches` ADD CONSTRAINT `usuarios_caches_fk0` FOREIGN KEY (`idusuario`) REFERENCES `usuarios`(`id`);
ALTER TABLE `usuarios_caches` ADD CONSTRAINT `usuarios_caches_fk1` FOREIGN KEY (`idcache`) REFERENCES `caches`(`id`);

INSERT INTO `caches` (`address_size_bits`, `control_size_bits`, `tag_size_bits`, `offset_size_bits`, `index_size_size`, `ways_size`) VALUES ('36', '1', '13', '10', '13', '1');
INSERT INTO `caches` (`address_size_bits`, `control_size_bits`, `tag_size_bits`, `offset_size_bits`, `index_size_size`, `ways_size`) VALUES ('32', '1', '13', '9', '10', '2');
INSERT INTO `caches` (`address_size_bits`, `control_size_bits`, `tag_size_bits`, `offset_size_bits`, `index_size_size`, `ways_size`) VALUES ('32', '1', '13', '11', '8', '1');
INSERT INTO `caches` (`address_size_bits`, `control_size_bits`, `tag_size_bits`, `offset_size_bits`, `index_size_size`, `ways_size`) VALUES ('43', '1', '24', '0', '19', '32');
INSERT INTO `caches` (`address_size_bits`, `control_size_bits`, `tag_size_bits`, `offset_size_bits`, `index_size_size`, `ways_size`) VALUES ('32', '1', '4', '14', '14', '1');

insert into `usuarios` (`nome`, `curso`) values ('Lucas', 'Ciência da Computação');
insert into `usuarios` (`nome`, `curso`) values ('Marcela', 'Ciência da Computação');

insert into `usuarios_caches` (`idusuario`, `idcache`) values ('1', '1');
insert into `usuarios_caches` (`idusuario`, `idcache`) values ('1', '2');
insert into `usuarios_caches` (`idusuario`, `idcache`) values ('1', '3');

insert into `usuarios_caches` (`idusuario`, `idcache`) values ('2', '1');
insert into `usuarios_caches` (`idusuario`, `idcache`) values ('2', '2');
insert into `usuarios_caches` (`idusuario`, `idcache`) values ('2', '3');

