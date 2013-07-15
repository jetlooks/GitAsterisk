﻿-- Script was generated by Devart dbForge Studio Express for MySQL, Version 5.0.97.0
-- Product home page: http://www.devart.com/dbforge/mysql/studio
-- Script date 7/15/2013 10:14:08 AM
-- Server version: 5.1.69
-- Client version: 4.1

-- 
-- Disable foreign keys
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Set character set the client will use to send SQL statements to the server
--
SET NAMES 'utf8';

-- 
-- Set default database
--
USE gittest;

--
-- Definition for table git_owners
--
DROP TABLE IF EXISTS git_owners;
CREATE TABLE git_owners (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Definition for table git_repositories
--
DROP TABLE IF EXISTS git_repositories;
CREATE TABLE git_repositories (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Definition for table likers_ips
--
DROP TABLE IF EXISTS likers_ips;
CREATE TABLE likers_ips (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  liker_ip VARCHAR(15) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Definition for table likes
--
DROP TABLE IF EXISTS likes;
CREATE TABLE likes (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  git_owner_id INT(11) NOT NULL,
  git_repo_id INT(11) NOT NULL,
  liker_ip_id INT(11) NOT NULL,
  opinion TINYINT(1) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Definition for table user_likes
--
DROP TABLE IF EXISTS user_likes;
CREATE TABLE user_likes (
  id INT(11) NOT NULL AUTO_INCREMENT,
  owner_id INT(11) NOT NULL,
  liker_ip_id INT(11) NOT NULL,
  opinion TINYINT(4) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

DELIMITER $$

--
-- Definition for procedure addLike
--
DROP PROCEDURE IF EXISTS addLike$$
CREATE DEFINER = 'igor888'@'%'
PROCEDURE addLike(IN repo_owner VARCHAR(255), IN repo_title VARCHAR(255), IN user_ip VARCHAR(15), IN user_opinion TINYINT)
BEGIN
    SET @ip_id = (SELECT id FROM likers_ips WHERE liker_ip = user_ip);
    IF(@ip_id IS NULL) THEN
      INSERT INTO likers_ips(liker_ip) VALUE (user_ip);
      SET @ip_id = LAST_INSERT_ID();
    END IF;

    SET @owner_id = (SELECT id FROM git_owners WHERE name = repo_owner);
    IF(@owner_id IS NULL) THEN
      INSERT INTO git_owners(git_owners.name) VALUE (repo_owner);
      SET @owner_id = LAST_INSERT_ID();
    END IF;

    SET @repo_id = (SELECT id FROM git_repositories WHERE title = BINARY repo_title);
    IF(@repo_id IS NULL) THEN
      INSERT INTO git_repositories(title) VALUE (BINARY repo_title);
      SET @repo_id = LAST_INSERT_ID();
    END IF;
    
    INSERT INTO likes(git_owner_id, git_repo_id, liker_ip_id, opinion)
    VALUE (@owner_id, @repo_id, @ip_id, user_opinion);
END
$$

--
-- Definition for procedure addUserLike
--
DROP PROCEDURE IF EXISTS addUserLike$$
CREATE DEFINER = 'igor888'@'%'
PROCEDURE addUserLike(IN user_login VARCHAR(255), IN ip VARCHAR(15), IN user_opinion TINYINT)
BEGIN
    SET @ip_id = (SELECT id FROM likers_ips WHERE liker_ip = ip);
    IF(@ip_id IS NULL) THEN
      INSERT INTO likers_ips(liker_ip) VALUE (ip);
      SET @ip_id = LAST_INSERT_ID();
    END IF;

    SET @user_id = (SELECT id FROM git_owners WHERE git_owners.name = user_login);
    IF(@user_id IS NULL) THEN
      INSERT INTO git_owners(git_owners.name) VALUE (user_login);
      SET @user_id = LAST_INSERT_ID();
    END IF;
    
    INSERT INTO user_likes(owner_id, liker_ip_id, opinion)
    VALUE (@user_id, @ip_id, user_opinion);
END
$$

--
-- Definition for function a
--
DROP FUNCTION IF EXISTS a$$
CREATE DEFINER = 'igor888'@'%'
FUNCTION a(ip VARCHAR(255))
  RETURNS varchar(255) CHARSET latin1
BEGIN
    SET @ip_id = (SELECT id FROM likers_ips WHERE liker_ip = ip);
    IF(@ip_id IS NULL) THEN
    RETURN "NULLL";
    ELSE 
      INSERT INTO likers_ips(liker_ip) VALUE (ip);
    END IF;
END
$$

DELIMITER ;

-- 
-- Dumping data for table git_owners
--
-- Table gittest.git_owners does not contain any data (it is empty)

-- 
-- Dumping data for table git_repositories
--
-- Table gittest.git_repositories does not contain any data (it is empty)

-- 
-- Dumping data for table likers_ips
--
-- Table gittest.likers_ips does not contain any data (it is empty)

-- 
-- Dumping data for table likes
--
-- Table gittest.likes does not contain any data (it is empty)

-- 
-- Dumping data for table user_likes
--
-- Table gittest.user_likes does not contain any data (it is empty)

-- 
-- Enable foreign keys
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;