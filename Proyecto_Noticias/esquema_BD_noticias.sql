-- -----------------------------------------------------
-- Schema noticias
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `noticias` DEFAULT CHARACTER SET utf8 ;
USE `noticias` ;

-- -----------------------------------------------------
-- Table `noticias`.`rol`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `noticias`.`rol` (
  `Nombre` VARCHAR(45) NOT NULL,
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `noticias`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `noticias`.`usuarios` (
  `Login` VARCHAR(45) NOT NULL,
  `Password` VARCHAR(45) NOT NULL,
  `Nombre` VARCHAR(45) NOT NULL,
  `Apellidos` VARCHAR(45) NULL DEFAULT NULL,
  `e-mail` VARCHAR(45) NOT NULL,
  `Fecha_Nacimento` DATE NULL DEFAULT NULL,
  `curso` INT(11) NULL DEFAULT NULL,
  `aula` VARCHAR(1) NULL DEFAULT NULL,
  `rol_Id` INT(11) NOT NULL,
  PRIMARY KEY (`Login`, `rol_Id`),
  INDEX `fk_usuarios_rol_idx` (`rol_Id` ASC),
  CONSTRAINT `fk_usuarios_rol`
    FOREIGN KEY (`rol_Id`)
    REFERENCES `noticias`.`rol` (`Id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `noticias`.`noticias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `noticias`.`noticias` (
  `id` INT NOT NULL,
  `Titulo` NVARCHAR(100) NULL DEFAULT NULL,
  `Cuerpo` LONGTEXT NULL DEFAULT NULL,
  `Imagen` INT(11) NULL DEFAULT NULL,
  `fecha_publicacion` DATETIME NULL DEFAULT NULL,
  `fecha_caducidad` DATETIME NULL DEFAULT NULL,
  `Usuario_Autor` INT(11) NULL DEFAULT NULL,
  `usuarios_Login` VARCHAR(45) NOT NULL,
  `usuarios_rol_Id` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `usuarios_Login`, `usuarios_rol_Id`),
  INDEX `fk_noticias_usuarios1_idx` (`usuarios_Login` ASC, `usuarios_rol_Id` ASC),
  CONSTRAINT `fk_noticias_usuarios1`
    FOREIGN KEY (`usuarios_Login` , `usuarios_rol_Id`)
    REFERENCES `noticias`.`usuarios` (`Login` , `rol_Id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `noticias`.`imagenes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `noticias`.`imagenes` (
  `id` INT(11) NOT NULL,
  `foto` MEDIUMBLOB NULL DEFAULT NULL,
  `Altura` INT(11) NULL DEFAULT NULL,
  `Anchura` INT(11) NULL DEFAULT NULL,
  `noticias_id` INT NOT NULL,
  PRIMARY KEY (`id`, `noticias_id`),
  INDEX `fk_imagenes_noticias1_idx` (`noticias_id` ASC),
  CONSTRAINT `fk_imagenes_noticias1`
    FOREIGN KEY (`noticias_id`)
    REFERENCES `noticias`.`noticias` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
