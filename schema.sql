SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `blog` ;
CREATE SCHEMA IF NOT EXISTS `blog` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `blog` ;

-- -----------------------------------------------------
-- Table `blog`.`posts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog`.`posts` ;

CREATE  TABLE IF NOT EXISTS `blog`.`posts` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `title` VARCHAR(255) NULL ,
  `content` TEXT NULL ,
  `created` TIMESTAMP NULL ,
  `updated` TIMESTAMP NULL ,
  PRIMARY KEY (`id`, `name`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blog`.`comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog`.`comments` ;

CREATE  TABLE IF NOT EXISTS `blog`.`comments` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NULL ,
  `name` VARCHAR(255) NULL ,
  `content` TEXT NULL ,
  `email` VARCHAR(255) NULL ,
  `posts_id` INT NOT NULL ,
  PRIMARY KEY (`id`, `posts_id`) ,
  CONSTRAINT `fk_comments_posts`
    FOREIGN KEY (`posts_id` )
    REFERENCES `blog`.`posts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_comments_posts` ON `blog`.`comments` (`posts_id` ASC) ;


-- -----------------------------------------------------
-- Table `blog`.`tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog`.`tags` ;

CREATE  TABLE IF NOT EXISTS `blog`.`tags` (
  `id` INT NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`, `name`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blog`.`posts_has_tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog`.`posts_has_tags` ;

CREATE  TABLE IF NOT EXISTS `blog`.`posts_has_tags` (
  `posts_id` INT NOT NULL ,
  `tags_id` INT NOT NULL ,
  PRIMARY KEY (`posts_id`, `tags_id`) ,
  CONSTRAINT `fk_posts_has_tags_posts1`
    FOREIGN KEY (`posts_id` )
    REFERENCES `blog`.`posts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_posts_has_tags_tags1`
    FOREIGN KEY (`tags_id` )
    REFERENCES `blog`.`tags` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_posts_has_tags_posts1` ON `blog`.`posts_has_tags` (`posts_id` ASC) ;

CREATE INDEX `fk_posts_has_tags_tags1` ON `blog`.`posts_has_tags` (`tags_id` ASC) ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
