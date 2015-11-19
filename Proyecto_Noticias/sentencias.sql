-- -----------------------------------------------------
-- Schema noticias
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `noticias` DEFAULT CHARACTER SET utf8 ;
USE `noticias` ;

-- -----------------------------------------------------
-- Table `noticias`.`rol`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `noticias`.`rol` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `noticias`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `noticias`.`usuarios` (
  `login` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `nombre` VARCHAR(45) NULL DEFAULT NULL,
  `apellidos` VARCHAR(45) NULL DEFAULT NULL,
  `e-mail` VARCHAR(45) NULL DEFAULT NULL,
  `rol_id` INT(11) NOT NULL,
  PRIMARY KEY (`login`, `rol_id`),
  INDEX `fk_usuarios_rol_idx` (`rol_id` ASC),
  CONSTRAINT `fk_usuarios_rol`
    FOREIGN KEY (`rol_id`)
    REFERENCES `noticias`.`rol` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `noticias`.`noticias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `noticias`.`noticias` (
  `id` INT NOT NULL,
  `titulo` NVARCHAR(100) NULL DEFAULT NULL,
  `cuerpo` LONGTEXT NULL DEFAULT NULL,
  `url_imagen` INT(11) NULL DEFAULT NULL,
  `fecha_inicio` DATETIME NOT NULL,
  `fecha_fin` DATETIME NOT NULL,
  `usuarios_login` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`, `usuarios_Login`, `usuarios_rol_Id`),
  INDEX `fk_noticias_usuarios_idx` (`usuarios_Login` ASC),
  CONSTRAINT `fk_noticias_usuarios`
    FOREIGN KEY (`usuarios_login`)
    REFERENCES `noticias`.`usuarios` (`login`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
