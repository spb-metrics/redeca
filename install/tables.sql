-- MySQL dump 10.11 
--
-- Host: localhost    Database: recriad
-- ------------------------------------------------------
-- Server version    5.0.51a-3ubuntu5.4

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `act_activity_class`
--

DROP TABLE IF EXISTS `act_activity_class`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `act_activity_class` (
  `id_activity_class` int(10) unsigned NOT NULL auto_increment,
  `id_class` int(10) unsigned NOT NULL,
  `id_activity_detail` int(10) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date default NULL,
  PRIMARY KEY  (`id_activity_class`),
  KEY `fk_act_class_activity_act_activity_detail` (`id_activity_detail`),
  KEY `fk_act_class_activity_act_class` (`id_class`),
  CONSTRAINT `fk_act_class_activity_act_activity_detail` FOREIGN KEY (`id_activity_detail`) REFERENCES `act_activity_detail` (`id_activity_detail`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_act_class_activity_act_class` FOREIGN KEY (`id_class`) REFERENCES `act_class` (`id_class`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Vinculo entre as atividades e suas turmas disponibilizadas p';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `act_activity_detail`
--

DROP TABLE IF EXISTS `act_activity_detail`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `act_activity_detail` (
  `id_activity_detail` int(10) unsigned NOT NULL auto_increment,
  `activity_detail` varchar(100) NOT NULL,
  `hourly_load` int(11) NOT NULL,
  `id_program` int(10) unsigned NOT NULL,
  `id_category` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_activity_detail`),
  KEY `fk_act_activity_detail_act_category` (`id_category`),
  KEY `fk_act_activity_detail_ass_program` (`id_program`),
  CONSTRAINT `fk_act_activity_detail_act_category` FOREIGN KEY (`id_category`) REFERENCES `act_category` (`id_category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_act_activity_detail_ass_program` FOREIGN KEY (`id_program`) REFERENCES `ast_program` (`id_program`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Registro detalhado das atividades oferecidas pela entidade';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `act_category`
--

DROP TABLE IF EXISTS `act_category`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `act_category` (
  `id_category` int(10) unsigned NOT NULL auto_increment,
  `id_category_father` int(10) unsigned default NULL,
  `category` varchar(80) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_category`),
  KEY `fk_act_category_act_subcategory` (`id_category_father`),
  CONSTRAINT `fk_act_category_act_subcategory` FOREIGN KEY (`id_category_father`) REFERENCES `act_category` (`id_category`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Registro das categorias das atividades das entidades vincula';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `act_class`
--

DROP TABLE IF EXISTS `act_class`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `act_class` (
  `id_class` int(10) unsigned NOT NULL auto_increment,
  `id_program` int(10) unsigned NOT NULL,
  `vacancy` int(11) NOT NULL,
  `schedule` varchar(30) default NULL,
  `period` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date default NULL,
  PRIMARY KEY  (`id_class`),
  KEY `fk_act_class_ass_program` (`id_program`),
  CONSTRAINT `fk_act_class_ass_program` FOREIGN KEY (`id_program`) REFERENCES `ast_program` (`id_program`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Registro da turmas disponiblizadas pelas entidades';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `act_class_assistance`
--

DROP TABLE IF EXISTS `act_class_assistance`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `act_class_assistance` (
  `id_class` int(10) unsigned NOT NULL,
  `id_assistance` int(10) unsigned NOT NULL,
  `id_status` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_class`,`id_assistance`),
  KEY `fk_act_class_assistance_act_status_class` (`id_status`),
  KEY `fk_act_class_assistance_ass_assistance` (`id_assistance`),
  CONSTRAINT `fk_act_class_assistance_act_class` FOREIGN KEY (`id_class`) REFERENCES `act_class` (`id_class`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_act_class_assistance_act_status_class` FOREIGN KEY (`id_status`) REFERENCES `act_status_class` (`id_status`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_act_class_assistance_ass_assistance` FOREIGN KEY (`id_assistance`) REFERENCES `ast_assistance` (`id_assistance`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Vinculo entre o atendimento e as turmas disponibilizadas pel';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `act_status_class`
--

DROP TABLE IF EXISTS `act_status_class`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `act_status_class` (
  `id_status` int(10) unsigned NOT NULL auto_increment,
  `status` varchar(80) NOT NULL,
  PRIMARY KEY  (`id_status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Registro do status do atendimento em relacao a turma (atendi';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ast_assistance`
--

DROP TABLE IF EXISTS `ast_assistance`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ast_assistance` (
  `id_assistance` int(10) unsigned NOT NULL auto_increment,
  `id_person` int(10) unsigned NOT NULL,
  `id_program` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `beginning_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `end_date_prevision` date default NULL,
  `real_end_date` timestamp NULL default NULL,
  `confidentiality` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id_assistance`),
  KEY `fk_ass_assistance_ass_program` (`id_program`),
  KEY `fk_ass_assistance_per_person` (`id_person`),
  KEY `fk_atendimento_login` (`id_user`),
  CONSTRAINT `fk_assistance_user_login` FOREIGN KEY (`id_user`) REFERENCES `auth_user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ass_assistance_ass_program` FOREIGN KEY (`id_program`) REFERENCES `ast_program` (`id_program`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ass_assistance_per_person` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Registro do atendimento de uma pessoa, onde serao informadas';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ast_assistance_profile`
--

DROP TABLE IF EXISTS `ast_assistance_profile`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ast_assistance_profile` (
  `id_general_assistance` int(10) unsigned NOT NULL,
  `id_profile` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_general_assistance`,`id_profile`),
  KEY `profile_assistance_FK` (`id_profile`),
  CONSTRAINT `assistance_profile_FK` FOREIGN KEY (`id_general_assistance`) REFERENCES `gas_general_assistance` (`id_general_assistance`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `profile_assistance_FK` FOREIGN KEY (`id_profile`) REFERENCES `auth_profile` (`id_profile`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ast_program`
--

DROP TABLE IF EXISTS `ast_program`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ast_program` (
  `id_program` int(10) unsigned NOT NULL auto_increment,
  `id_entity` int(10) unsigned NOT NULL,
  `id_program_type` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_program`),
  KEY `fk_ass_program_ass_program_type` (`id_program_type`),
  KEY `fk_programa_entidade` (`id_entity`),
  CONSTRAINT `fk_ass_program_ass_program_type` FOREIGN KEY (`id_program_type`) REFERENCES `ast_program_type` (`id_program_type`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_programa_entidade` FOREIGN KEY (`id_entity`) REFERENCES `ent_entity` (`id_entity`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 COMMENT='Vinculo entre a entidade e os programas de atendimento dispo';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ast_program_type`
--

DROP TABLE IF EXISTS `ast_program_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ast_program_type` (
  `id_program_type` int(10) unsigned NOT NULL auto_increment,
  `id_target_market` int(10) unsigned NOT NULL,
  `program_type` varchar(80) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_program_type`),
  KEY `fk_ast_program_type_ast_target_market` (`id_target_market`),
  CONSTRAINT `fk_ast_program_type_ast_target_market` FOREIGN KEY (`id_target_market`) REFERENCES `ast_target_market` (`id_target_market`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COMMENT='Registro dos tipos de programas disponiveis LA, PSC, Complem';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ast_target_market`
--

DROP TABLE IF EXISTS `ast_target_market`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ast_target_market` (
  `id_target_market` int(10) unsigned NOT NULL auto_increment,
  `target_market` varchar(80) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_target_market`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='tabela de registro de publico-alvo';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `auth_group`
--

DROP TABLE IF EXISTS `auth_group`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_group` (
  `id_group` int(10) unsigned NOT NULL auto_increment,
  `group_name` varchar(90) default NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_group`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Grupos criados pelo gerente da rede';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `auth_group_entity`
--

DROP TABLE IF EXISTS `auth_group_entity`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_group_entity` (
  `id_entity` int(10) unsigned NOT NULL,
  `id_group` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_entity`,`id_group`),
  KEY `group_entity_FK` (`id_group`),
  CONSTRAINT `entity_group_FK` FOREIGN KEY (`id_entity`) REFERENCES `ent_entity` (`id_entity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `group_entity_FK` FOREIGN KEY (`id_group`) REFERENCES `auth_group` (`id_group`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `auth_group_resource`
--

DROP TABLE IF EXISTS `auth_group_resource`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_group_resource` (
  `role_resource_id_role` int(10) unsigned NOT NULL,
  `role_resource_id_resource` int(10) unsigned NOT NULL,
  `id_group` int(10) unsigned NOT NULL,
  `readonly` tinyint(4) NOT NULL,
  `change_confidentiality` tinyint(4) NOT NULL,
  `default_confidentiality` tinyint(4) default NULL,
  PRIMARY KEY  (`role_resource_id_role`,`role_resource_id_resource`,`id_group`),
  KEY `group_resource_FK` (`id_group`),
  KEY `role_resource_group_FK` (`role_resource_id_role`,`role_resource_id_resource`),
  CONSTRAINT `group_resource_FK` FOREIGN KEY (`id_group`) REFERENCES `auth_group` (`id_group`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `role_resource_group_FK` FOREIGN KEY (`role_resource_id_role`, `role_resource_id_resource`) REFERENCES `auth_role_resource` (`id_role`, `id_resource`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `auth_profile`
--

DROP TABLE IF EXISTS `auth_profile`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_profile` (
  `id_profile` int(10) unsigned NOT NULL auto_increment,
  `profile` varchar(90) default NULL,
  `active` tinyint(4) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_profile`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Perfis';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `auth_resource`
--

DROP TABLE IF EXISTS `auth_resource`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_resource` (
  `id_resource` int(10) unsigned NOT NULL auto_increment,
  `controller_name` varchar(25) NOT NULL,
  `resource_type` char(1) NOT NULL,
  PRIMARY KEY  (`id_resource`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `auth_role`
--

DROP TABLE IF EXISTS `auth_role`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_role` (
  `id_role` int(10) unsigned NOT NULL auto_increment,
  `role` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `auth_role_resource`
--

DROP TABLE IF EXISTS `auth_role_resource`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_role_resource` (
  `id_resource` int(10) unsigned NOT NULL,
  `id_role` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_resource`,`id_role`),
  KEY `role_resource_FK` (`id_role`),
  CONSTRAINT `resource_role_FK` FOREIGN KEY (`id_resource`) REFERENCES `auth_resource` (`id_resource`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `role_resource_FK` FOREIGN KEY (`id_role`) REFERENCES `auth_role` (`id_role`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `auth_user`
--

DROP TABLE IF EXISTS `auth_user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_user` (
  `id_user` int(10) unsigned NOT NULL auto_increment,
  `id_entity` int(10) unsigned default NULL,
  `id_group` int(10) unsigned default NULL,
  `id_role` int(10) unsigned NOT NULL,
  `name` varchar(90) default NULL,
  `login` varchar(30) default NULL,
  `pass` varchar(32) NOT NULL,
  `email` varchar(90) default NULL,
  `cpf` varchar(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `dat_creation` date NOT NULL,
  `permission` integer NOT NULL,
  PRIMARY KEY  (`id_user`),
  UNIQUE KEY `auth_user_login_Idx` (`login`),
  KEY `fk_login_entidade` (`id_entity`),
  KEY `user_group_FK` (`id_group`),
  KEY `user_role_FK` (`id_role`),
  CONSTRAINT `fk_user_entity` FOREIGN KEY (`id_entity`) REFERENCES `ent_entity` (`id_entity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_group_FK` FOREIGN KEY (`id_group`) REFERENCES `auth_group` (`id_group`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_role_FK` FOREIGN KEY (`id_role`) REFERENCES `auth_role` (`id_role`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COMMENT='Registro dos usuarios do sistema';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `auth_user_profile`
--

DROP TABLE IF EXISTS `auth_user_profile`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_user_profile` (
  `id_profile` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_profile`,`id_user`),
  KEY `user_profile_FK` (`id_user`),
  CONSTRAINT `profile_user_FK` FOREIGN KEY (`id_profile`) REFERENCES `auth_profile` (`id_profile`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_profile_FK` FOREIGN KEY (`id_user`) REFERENCES `auth_user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `con_address`
--

DROP TABLE IF EXISTS `con_address`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `con_address` (
  `id_address` int(10) unsigned NOT NULL auto_increment,
  `id_neighborhood` int(10) unsigned NOT NULL,
  `id_address_type` int(10) unsigned NOT NULL,
  `zip_code` varchar(8) default NULL,
  `address` varchar(72) default NULL,
  `address_metafone` varchar(72) NOT NULL,
  `user_inserted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_address`),
  KEY `fk_con_address_con_address_type` (`id_address_type`),
  KEY `fk_con_address_con_neighborhood` (`id_neighborhood`),
  KEY `fk_con_address_con_source_type` (`user_inserted`),
  CONSTRAINT `fk_con_address_con_address_type` FOREIGN KEY (`id_address_type`) REFERENCES `con_address_type` (`id_address_type`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_con_address_con_neighborhood` FOREIGN KEY (`id_neighborhood`) REFERENCES `con_neighborhood` (`id_neighborhood`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=740501 DEFAULT CHARSET=latin1 COMMENT='Registro dos logradouros do sistema, que serao utilizados pa';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `con_address_nickname`
--

DROP TABLE IF EXISTS `con_address_nickname`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `con_address_nickname` (
  `id_address` int(10) unsigned NOT NULL,
  `id_nickname` int(10) unsigned NOT NULL,
  `nickname` varchar(72) NOT NULL,
  `nickname_metafone` varchar(72) NOT NULL,
  PRIMARY KEY  (`id_address`,`id_nickname`),
  KEY `fk_con_address_nickname_con_address` (`id_address`),
  CONSTRAINT `fk_con_address_nickname_con_address` FOREIGN KEY (`id_address`) REFERENCES `con_address` (`id_address`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro dos possiveis apelidos que o logradouro pode ter';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `con_address_type`
--

DROP TABLE IF EXISTS `con_address_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `con_address_type` (
  `id_address_type` int(10) unsigned NOT NULL auto_increment,
  `description` varchar(72) NOT NULL,
  `status` char(1) NOT NULL,
  `user_inserted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_address_type`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `con_city`
--

DROP TABLE IF EXISTS `con_city`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `con_city` (
  `id_city` int(10) unsigned NOT NULL auto_increment,
  `id_uf` int(10) unsigned NOT NULL,
  `city` varchar(72) NOT NULL,
  `user_inserted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_city`),
  KEY `fk_con_city_con_uf` (`id_uf`),
  CONSTRAINT `fk_con_city_con_uf` FOREIGN KEY (`id_uf`) REFERENCES `con_uf` (`id_uf`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11283 DEFAULT CHARSET=latin1 COMMENT='Registro das cidades e suas faixas de ceps (inicial e final)';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `con_neighborhood`
--

DROP TABLE IF EXISTS `con_neighborhood`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `con_neighborhood` (
  `id_neighborhood` int(10) unsigned NOT NULL auto_increment,
  `id_city` int(10) unsigned NOT NULL,
  `neighborhood` varchar(72) default NULL,
  `user_inserted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_neighborhood`),
  KEY `fk_con_neighborhood_con_city` (`id_city`),
  CONSTRAINT `fk_con_neighborhood_con_city` FOREIGN KEY (`id_city`) REFERENCES `con_city` (`id_city`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=50148 DEFAULT CHARSET=latin1 COMMENT='Registro dos bairros do municipio.';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `con_neighborhood_region`
--

DROP TABLE IF EXISTS `con_neighborhood_region`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `con_neighborhood_region` (
  `id_neighborhood` int(10) unsigned NOT NULL,
  `id_region` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_neighborhood`,`id_region`),
  KEY `fk_con_neighborhood_region_con_region` (`id_region`),
  CONSTRAINT `fk_con_neighborhood_region_con_neighborhood` FOREIGN KEY (`id_neighborhood`) REFERENCES `con_neighborhood` (`id_neighborhood`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_con_neighborhood_region_con_region` FOREIGN KEY (`id_region`) REFERENCES `con_region` (`id_region`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Vinculo dos bairros do municipio com as regioes';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `con_region`
--

DROP TABLE IF EXISTS `con_region`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `con_region` (
  `id_region` int(10) unsigned NOT NULL auto_increment,
  `region` varchar(72) NOT NULL,
  `region_img` varchar(200) default NULL,
  PRIMARY KEY  (`id_region`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro das regioes da cidade possibilitando o agrupamento ';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `con_telephone_number`
--

DROP TABLE IF EXISTS `con_telephone_number`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `con_telephone_number` (
  `id_telephone_number` int(10) unsigned NOT NULL auto_increment,
  `id_telephone_type` int(10) unsigned NOT NULL,
  `ddd` int(11) NOT NULL default '11',
  `number` varchar(30) NOT NULL COMMENT 'retirar todos caracteres estranhos, espacos, etc, deixando apenas os numeros',
  PRIMARY KEY  (`id_telephone_number`),
  UNIQUE KEY `telefone_id_key` (`id_telephone_number`),
  KEY `fk_con_telephone_number_con_telephone_type` (`id_telephone_type`),
  CONSTRAINT `fk_con_telephone_number_con_telephone_type` FOREIGN KEY (`id_telephone_type`) REFERENCES `con_telephone_type` (`id_telephone`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro de todos os telefones do sistema, que podem ser uti';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `con_telephone_type`
--

DROP TABLE IF EXISTS `con_telephone_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `con_telephone_type` (
  `id_telephone` int(10) unsigned NOT NULL auto_increment,
  `telephone` varchar(30) default NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_telephone`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Registro dos tipos de telefone disponiveis no sistema (resid';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `con_uf`
--

DROP TABLE IF EXISTS `con_uf`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `con_uf` (
  `id_uf` int(10) unsigned NOT NULL auto_increment,
  `abbreviation` varchar(2) NOT NULL,
  `user_inserted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_uf`),
  UNIQUE KEY `uf_uf_sigla_key` (`abbreviation`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 COMMENT='Registro das UFs';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `cov_coverage_address`
--

DROP TABLE IF EXISTS `cov_coverage_address`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cov_coverage_address` (
  `id_ubs` int(10) unsigned NOT NULL,
  `id_residence` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_ubs`,`id_residence`),
  KEY `fk_cov_coverage_address_cov_ubs` (`id_ubs`),
  KEY `fk_cov_coverage_address_res_residence` (`id_residence`),
  CONSTRAINT `fk_cov_coverage_address_cov_ubs` FOREIGN KEY (`id_ubs`) REFERENCES `cov_ubs` (`id_ubs`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cov_coverage_address_res_residence` FOREIGN KEY (`id_residence`) REFERENCES `res_residence` (`id_residence`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Vincular as unidades de saude com os logradouros do municipi';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `cov_coverage_health_type`
--

DROP TABLE IF EXISTS `cov_coverage_health_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cov_coverage_health_type` (
  `id_coverage_health` int(10) unsigned NOT NULL auto_increment,
  `coverage_health` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_coverage_health`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Registro dos tipos de cobertura de saude disponiveis no muni';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `cov_ubs`
--

DROP TABLE IF EXISTS `cov_ubs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cov_ubs` (
  `id_ubs` int(10) unsigned NOT NULL auto_increment,
  `id_coverage_health` int(10) unsigned NOT NULL,
  `ubs_name` varchar(90) NOT NULL,
  `number` int(10) unsigned NOT NULL,
  `id_address` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_ubs`),
  KEY `fk_cov_ubs_con_address` (`id_address`),
  KEY `fk_cov_ubs_cov_coverage_health_type` (`id_coverage_health`),
  CONSTRAINT `fk_cov_ubs_con_address` FOREIGN KEY (`id_address`) REFERENCES `con_address` (`id_address`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cov_ubs_cov_coverage_health_type` FOREIGN KEY (`id_coverage_health`) REFERENCES `cov_coverage_health_type` (`id_coverage_health`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro das Unidades Basicas de Saude que atendem por bairr';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `csg_consanguine`
--

DROP TABLE IF EXISTS `csg_consanguine`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `csg_consanguine` (
  `id_person_from` int(10) unsigned NOT NULL,
  `id_consanguine_type` int(10) unsigned NOT NULL,
  `id_person_to` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_person_to`,`id_person_from`),
  KEY `fk_consanguine_consanguine_type` (`id_consanguine_type`),
  KEY `fk_csg_consanguine_per_person_from` (`id_person_from`),
  CONSTRAINT `fk_csg_consanguine_csg_consanguine_type` FOREIGN KEY (`id_consanguine_type`) REFERENCES `csg_consanguine_type` (`id_consanguine_type`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_csg_consanguine_per_person_from` FOREIGN KEY (`id_person_from`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_csg_consanguine_per_person_to` FOREIGN KEY (`id_person_to`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `csg_consanguine_type`
--

DROP TABLE IF EXISTS `csg_consanguine_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `csg_consanguine_type` (
  `id_consanguine_type` int(10) unsigned NOT NULL auto_increment,
  `description` varchar(80) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_consanguine_type`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `eas_especial_assistance`
--

DROP TABLE IF EXISTS `eas_especial_assistance`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `eas_especial_assistance` (
  `id_assistance` int(10) unsigned NOT NULL auto_increment,
  `id_official_letter_origin` int(10) unsigned NOT NULL,
  `id_lawsuit_origin` int(10) unsigned NOT NULL,
  `official_letter_number` int(11) default NULL,
  `official_letter_year` int(11) default NULL,
  `lawsuit_number` int(11) default NULL,
  `lawsuit_year` int(11) default NULL,
  `lawsuit_detail` text,
  `ruling` text,
  PRIMARY KEY  (`id_assistance`),
  KEY `fk_eas_especial_assistance_eas_lawsuit_origin` (`id_lawsuit_origin`),
  KEY `fk_eas_especial_assistance_eas_official_letter_origin` (`id_official_letter_origin`),
  CONSTRAINT `fk_eas_especial_assistance_ast_assistance` FOREIGN KEY (`id_assistance`) REFERENCES `ast_assistance` (`id_assistance`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_eas_especial_assistance_eas_lawsuit_origin` FOREIGN KEY (`id_lawsuit_origin`) REFERENCES `eas_lawsuit_origin` (`id_lawsuit_origin`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_eas_especial_assistance_eas_official_letter_origin` FOREIGN KEY (`id_official_letter_origin`) REFERENCES `eas_official_letter_origin` (`id_official_letter_origin`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Registro de casos especiais, usado nos casos de PSC, LA, SEM';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `eas_lawsuit_origin`
--

DROP TABLE IF EXISTS `eas_lawsuit_origin`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `eas_lawsuit_origin` (
  `id_lawsuit_origin` int(10) unsigned NOT NULL auto_increment,
  `lawsuit_origin` varchar(40) NOT NULL,
  PRIMARY KEY  (`id_lawsuit_origin`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Registro da origem do processo de encaminhamento pelo juiz p';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `eas_official_letter_origin`
--

DROP TABLE IF EXISTS `eas_official_letter_origin`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `eas_official_letter_origin` (
  `id_official_letter_origin` int(10) unsigned NOT NULL auto_increment,
  `official_letter_origin` varchar(40) NOT NULL,
  PRIMARY KEY  (`id_official_letter_origin`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Registro da origem do oficio de encaminhamento emitido pelo ';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `edu_degree_type`
--

DROP TABLE IF EXISTS `edu_degree_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `edu_degree_type` (
  `id_degree` int(10) unsigned NOT NULL auto_increment,
  `degree` varchar(70) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_degree`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COMMENT='Registro dos graus de instrucao possiveis.';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `edu_level_instruction`
--

DROP TABLE IF EXISTS `edu_level_instruction`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `edu_level_instruction` (
  `id_level_instruction` int(10) unsigned NOT NULL auto_increment,
  `id_person` int(10) unsigned NOT NULL,
  `id_degree` int(10) unsigned NOT NULL,
  `last_year_studied` int(11) default NULL,
  `status` char(1) default NULL,
  `last_month_studied` int(11) default NULL,
  `date_collected` date default NULL,
  PRIMARY KEY  (`id_level_instruction`),
  KEY `fk_edu_level_instruction_edu_degree_type` (`id_degree`),
  KEY `fk_edu_level_instruction_per_person` (`id_person`),
  CONSTRAINT `fk_edu_level_instruction_edu_degree_type` FOREIGN KEY (`id_degree`) REFERENCES `edu_degree_type` (`id_degree`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_level_instruction_per_person` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=31430 DEFAULT CHARSET=latin1 COMMENT='Registro do grau de instrucao da pessoa';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `edu_period_type`
--

DROP TABLE IF EXISTS `edu_period_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `edu_period_type` (
  `id_period` int(10) unsigned NOT NULL auto_increment,
  `period` varchar(16) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_period`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Registro dos periodos da aulas disponiveis no municipio (man';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `edu_registration`
--

DROP TABLE IF EXISTS `edu_registration`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `edu_registration` (
  `id_registration` int(10) unsigned NOT NULL auto_increment,
  `id_level_instruction` int(10) unsigned NOT NULL,
  `id_school_year` int(10) unsigned default NULL,
  `id_period` int(10) unsigned default NULL,
  `id_school` int(10) unsigned default NULL,
  `status` char(1) default NULL,
  PRIMARY KEY  (`id_registration`),
  KEY `fk_edu_registration_edu_level_instruction` (`id_level_instruction`),
  KEY `fk_edu_registration_edu_period_type` (`id_period`),
  KEY `fk_edu_registration_edu_school` (`id_school`),
  KEY `fk_edu_registration_edu_school_year_type` (`id_school_year`),
  CONSTRAINT `fk_edu_registration_edu_level_instruction` FOREIGN KEY (`id_level_instruction`) REFERENCES `edu_level_instruction` (`id_level_instruction`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_registration_edu_period_type` FOREIGN KEY (`id_period`) REFERENCES `edu_period_type` (`id_period`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_registration_edu_school` FOREIGN KEY (`id_school`) REFERENCES `edu_school` (`id_school`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_registration_edu_school_year_type` FOREIGN KEY (`id_school_year`) REFERENCES `edu_school_year_type` (`id_school_year`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro do vinculo entre a escola e a pessoa. Tem caracteri';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `edu_school`
--

DROP TABLE IF EXISTS `edu_school`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `edu_school` (
  `id_school` int(10) unsigned NOT NULL auto_increment,
  `inep` varchar(8) NOT NULL,
  `name` varchar(120) NOT NULL,
  `id_school_type` int(10) unsigned default NULL,
  PRIMARY KEY  (`id_school`),
  KEY `fk_edu_school_edu_school_type` (`id_school_type`),
  CONSTRAINT `fk_edu_school_edu_school_type` FOREIGN KEY (`id_school_type`) REFERENCES `edu_school_type` (`id_school_type`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro das escolas do municipio';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `edu_school_type`
--

DROP TABLE IF EXISTS `edu_school_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `edu_school_type` (
  `id_school_type` int(10) unsigned NOT NULL auto_increment,
  `school_type` varchar(40) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_school_type`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Registro dos tipos de escola (medio, fundamental,etc)';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `edu_school_year_type`
--

DROP TABLE IF EXISTS `edu_school_year_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `edu_school_year_type` (
  `id_school_year` int(10) unsigned NOT NULL auto_increment,
  `school_year` varchar(70) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_school_year`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COMMENT='Registro das series/anos escolares';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `emp_employment`
--

DROP TABLE IF EXISTS `emp_employment`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `emp_employment` (
  `id_employment` int(10) unsigned NOT NULL auto_increment,
  `id_employment_status` int(10) unsigned NOT NULL,
  `id_address` int(10) unsigned default NULL,
  `id_emp_income` int(10) unsigned NOT NULL,
  `company_name` varchar(80) default NULL,
  `start_date` date default NULL,
  `end_date` date default NULL,
  `number` int(11) default NULL,
  `complement` varchar(72) default NULL,
  `reference_point` varchar(72) default NULL,
  `occupation` varchar(32) default NULL,
  PRIMARY KEY  (`id_employment`),
  KEY `fk_emp_employment_emp_employment_status_type` (`id_employment_status`),
  KEY `fk_emp_employment_emp_income` (`id_emp_income`),
  KEY `fk_emp_employment_con_address` (`id_address`),
  CONSTRAINT `fk_emp_employment_con_address` FOREIGN KEY (`id_address`) REFERENCES `con_address` (`id_address`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_emp_employment_emp_employment_status_type` FOREIGN KEY (`id_employment_status`) REFERENCES `emp_employment_status_type` (`id_employment_status`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_emp_employment_emp_income` FOREIGN KEY (`id_emp_income`) REFERENCES `emp_income` (`id_emp_income`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=573 DEFAULT CHARSET=latin1 COMMENT='Registro dos dados relativos ao emprego da pessoa';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `emp_employment_status_type`
--

DROP TABLE IF EXISTS `emp_employment_status_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `emp_employment_status_type` (
  `id_employment_status` int(10) unsigned NOT NULL auto_increment,
  `employment_status` varchar(70) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_employment_status`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COMMENT='Registro da situacao do emprego da pessoa (formal, informal,';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `emp_employment_telephone`
--

DROP TABLE IF EXISTS `emp_employment_telephone`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `emp_employment_telephone` (
  `id_employment` int(10) unsigned NOT NULL,
  `id_telephone` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_employment`,`id_telephone`),
  KEY `fk_emp_employment_telephone_con_telephone_number` (`id_telephone`),
  CONSTRAINT `fk_emp_employment_telephone_con_telephone_number` FOREIGN KEY (`id_telephone`) REFERENCES `con_telephone_number` (`id_telephone_number`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_emp_employment_telephone_emp_employment` FOREIGN KEY (`id_employment`) REFERENCES `emp_employment` (`id_employment`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Vincular telefones ao emprego';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `emp_income`
--

DROP TABLE IF EXISTS `emp_income`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `emp_income` (
  `id_emp_income` int(10) unsigned NOT NULL auto_increment,
  `id_person` int(10) unsigned NOT NULL,
  `id_income` int(10) unsigned NOT NULL,
  `register_date` date NOT NULL,
  `value` float NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_emp_income`),
  KEY `fk_emp_income_emp_income_type` (`id_income`),
  KEY `fk_emp_income_per_person` (`id_person`),
  CONSTRAINT `fk_emp_income_emp_income_type` FOREIGN KEY (`id_income`) REFERENCES `emp_income_type` (`id_income`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_emp_income_per_person` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10746 DEFAULT CHARSET=latin1 COMMENT='Registro da renda da pessoa.';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `emp_income_type`
--

DROP TABLE IF EXISTS `emp_income_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `emp_income_type` (
  `id_income` int(10) unsigned NOT NULL auto_increment,
  `income` varchar(80) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_income`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Registro dos tipos de renda que a pessoa pode receber (empre';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ent_entity`
--

DROP TABLE IF EXISTS `ent_entity`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ent_entity` (
  `id_entity` int(10) unsigned NOT NULL auto_increment,
  `id_address` int(10) unsigned default NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(90) default NULL,
  `homepage` varchar(200) default NULL,
  `logo_img` varchar(200) default NULL,
  `number` int(10) unsigned default NULL,
  `complement` varchar(80) default NULL,
  `latitude` double default NULL,
  `longitude` double default NULL,
  `cnpj` varchar(14) default NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id_entity`),
  KEY `fk_ent_entity_con_address` (`id_address`),
  CONSTRAINT `fk_ent_entity_con_address` FOREIGN KEY (`id_address`) REFERENCES `con_address` (`id_address`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COMMENT='Registrar os dados cadastrais das entidades da rede';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ent_entity_area`
--

DROP TABLE IF EXISTS `ent_entity_area`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ent_entity_area` (
  `id_entity` int(10) unsigned NOT NULL,
  `id_entity_area` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_entity_area`,`id_entity`),
  KEY `fk_ent_entity_area_ent_entity` (`id_entity`),
  CONSTRAINT `fk_ent_entity_area_ent_entity` FOREIGN KEY (`id_entity`) REFERENCES `ent_entity` (`id_entity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ent_entity_area_ent_entity_area_type` FOREIGN KEY (`id_entity_area`) REFERENCES `ent_entity_area_type` (`id_entity_area`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Vincular a entidade com as areas de atuacao do ECA';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ent_entity_area_type`
--

DROP TABLE IF EXISTS `ent_entity_area_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ent_entity_area_type` (
  `id_entity_area` int(10) unsigned NOT NULL auto_increment,
  `entity_area` varchar(80) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_entity_area`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Registro das areas de atuacao das entidades com base nos pri';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ent_entity_classification`
--

DROP TABLE IF EXISTS `ent_entity_classification`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ent_entity_classification` (
  `id_entity` int(10) unsigned NOT NULL,
  `id_entity_classification` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_entity`,`id_entity_classification`),
  KEY `fk_ent_entity_classification_ent_entity_classification_type` (`id_entity_classification`),
  CONSTRAINT `fk_ent_entity_classification_ent_entity` FOREIGN KEY (`id_entity`) REFERENCES `ent_entity` (`id_entity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ent_entity_classification_ent_entity_classification_type` FOREIGN KEY (`id_entity_classification`) REFERENCES `ent_entity_classification_type` (`id_entity_classification`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Vincular a entidade com as classificacoes cadastradas';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ent_entity_classification_type`
--

DROP TABLE IF EXISTS `ent_entity_classification_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ent_entity_classification_type` (
  `id_entity_classification` int(10) unsigned NOT NULL auto_increment,
  `entity_classification` varchar(150) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_entity_classification`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Registro das classificacoes possiveis das entidades (creche,';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ent_entity_group`
--

DROP TABLE IF EXISTS `ent_entity_group`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ent_entity_group` (
  `id_entity` int(10) unsigned NOT NULL,
  `id_entity_group` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_entity_group`,`id_entity`),
  KEY `fk_ent_entity_group_ent_entity` (`id_entity`),
  CONSTRAINT `fk_ent_entity_group_ent_entity` FOREIGN KEY (`id_entity`) REFERENCES `ent_entity` (`id_entity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ent_entity_group_ent_entity_group_type` FOREIGN KEY (`id_entity_group`) REFERENCES `ent_entity_group_type` (`id_entity_group`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Vincular entidades "matriz e filiais"';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ent_entity_group_type`
--

DROP TABLE IF EXISTS `ent_entity_group_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ent_entity_group_type` (
  `id_entity_group` int(10) unsigned NOT NULL auto_increment,
  `entity_group` varchar(200) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_entity_group`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COMMENT='Registro da entidade "Matriz" no caso de agrupamento de enti';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ent_entity_telephone`
--

DROP TABLE IF EXISTS `ent_entity_telephone`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ent_entity_telephone` (
  `id_entity` int(10) unsigned NOT NULL,
  `id_telephone` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_entity`,`id_telephone`),
  KEY `fk_ent_entity_telephone_con_telephone_number` (`id_telephone`),
  CONSTRAINT `fk_ent_entity_telephone_con_telephone_number` FOREIGN KEY (`id_telephone`) REFERENCES `con_telephone_number` (`id_telephone_number`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ent_entity_telephone_ent_entity` FOREIGN KEY (`id_entity`) REFERENCES `ent_entity` (`id_entity`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Vincular telefones com a entidade';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `exp_expense`
--

DROP TABLE IF EXISTS `exp_expense`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `exp_expense` (
  `id_expense` int(10) unsigned NOT NULL auto_increment,
  `id_expense_type` int(10) unsigned NOT NULL,
  `id_family` int(10) unsigned NOT NULL,
  `expense_value` float NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_expense`),
  KEY `fk_exp_expense_exp_expense_type` (`id_expense_type`),
  KEY `fk_exp_expense_fam_family` (`id_family`),
  CONSTRAINT `fk_exp_expense_exp_expense_type` FOREIGN KEY (`id_expense_type`) REFERENCES `exp_expense_type` (`id_expense`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_exp_expense_fam_family_id` FOREIGN KEY (`id_family`) REFERENCES `fam_family_id` (`id_family`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7038 DEFAULT CHARSET=latin1 COMMENT='Registro do valor de cada despesa da familia';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `exp_expense_type`
--

DROP TABLE IF EXISTS `exp_expense_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `exp_expense_type` (
  `id_expense` int(10) unsigned NOT NULL auto_increment,
  `expense` varchar(90) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_expense`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COMMENT='Registro das despesas da familia: aluguel, prestacao habitac';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `fam_family`
--

DROP TABLE IF EXISTS `fam_family`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `fam_family` (
  `id_family` int(10) unsigned NOT NULL,
  `id_person` int(10) unsigned NOT NULL,
  `id_kinship` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_kinship`,`id_person`,`id_family`),
  KEY `fk_fam_family_fam_family_id` (`id_family`),
  KEY `fk_formacao_familia` (`id_person`),
  KEY `fk_formacao_familia_tipo_parentesco` (`id_kinship`),
  CONSTRAINT `fk_fam_family_fam_family_id` FOREIGN KEY (`id_family`) REFERENCES `fam_family_id` (`id_family`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_formacao_familia` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_formacao_familia_tipo_parentesco` FOREIGN KEY (`id_kinship`) REFERENCES `fam_kinship_type` (`id_kinship`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Vinculo entre a pessoa, seu parentesco em relacao ao represe';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `fam_family_id`
--

DROP TABLE IF EXISTS `fam_family_id`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `fam_family_id` (
  `id_family` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id_family`)
) ENGINE=InnoDB AUTO_INCREMENT=7912 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `fam_kinship_type`
--

DROP TABLE IF EXISTS `fam_kinship_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `fam_kinship_type` (
  `id_kinship` int(10) unsigned NOT NULL auto_increment,
  `kinship` varchar(80) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_kinship`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 COMMENT='Registro do vinculo de parentesco entre a pessoa e seu repre';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `fam_representative`
--

DROP TABLE IF EXISTS `fam_representative`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `fam_representative` (
  `id_representative` int(10) unsigned NOT NULL auto_increment,
  `id_person` int(10) unsigned NOT NULL,
  `id_family` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_representative`),
  KEY `fk_fam_representative_fam_family` (`id_family`),
  KEY `fk_fam_representative_per_person` (`id_person`),
  CONSTRAINT `fk_fam_representative_fam_family_id` FOREIGN KEY (`id_family`) REFERENCES `fam_family_id` (`id_family`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_fam_representative_per_person` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7688 DEFAULT CHARSET=latin1 COMMENT='Registro do representante legal da familia';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `gas_assistance_benefit`
--

DROP TABLE IF EXISTS `gas_assistance_benefit`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `gas_assistance_benefit` (
  `id_assistance` int(10) unsigned NOT NULL,
  `id_general_assistance` int(10) unsigned NOT NULL,
  `id_assistance_benefit_type` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_assistance`,`id_general_assistance`,`id_assistance_benefit_type`),
  KEY `fk_gas_assitance_benefit_gas_assistance_benefit_type` (`id_assistance_benefit_type`),
  CONSTRAINT `fk_gas_assitance_benefit_gas_assistance_benefit_type` FOREIGN KEY (`id_assistance_benefit_type`) REFERENCES `gas_assistance_benefit_type` (`id_assistance_benefit_type`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_gas_assitance_benefit_gas_general_assistance` FOREIGN KEY (`id_assistance`, `id_general_assistance`) REFERENCES `gas_general_assistance` (`id_assistance`, `id_general_assistance`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Relacao de beneifico que podem ser oferecidos';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `gas_assistance_benefit_type`
--

DROP TABLE IF EXISTS `gas_assistance_benefit_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `gas_assistance_benefit_type` (
  `id_assistance_benefit_type` int(10) unsigned NOT NULL auto_increment,
  `description` varchar(72) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_assistance_benefit_type`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `gas_general_assistance`
--

DROP TABLE IF EXISTS `gas_general_assistance`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `gas_general_assistance` (
  `id_assistance` int(10) unsigned NOT NULL,
  `id_general_assistance` int(10) unsigned NOT NULL auto_increment,
  `assistance_comment` text NOT NULL,
  `register_data` date NOT NULL,
  `confidentiality` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id_general_assistance`),
  KEY `fk_gas_general_assistance_ass_assistance` (`id_assistance`),
  CONSTRAINT `fk_gas_general_assistance_ass_assistance` FOREIGN KEY (`id_assistance`) REFERENCES `ast_assistance` (`id_assistance`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro de atendimentos  diferentes da complementacao, prof';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `hlt_framework_health`
--

DROP TABLE IF EXISTS `hlt_framework_health`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `hlt_framework_health` (
  `id_health` int(10) unsigned NOT NULL,
  `id_framework_health` int(10) unsigned NOT NULL,
  `framework_health_description` text,
  PRIMARY KEY  (`id_health`,`id_framework_health`),
  KEY `fk_hlt_framework_health_hlt_framework_health_type` (`id_framework_health`),
  CONSTRAINT `fk_hlt_framework_health_hlt_framework_health_type` FOREIGN KEY (`id_framework_health`) REFERENCES `hlt_framework_health_type` (`id_framework_health`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_hlt_framework_health_hlt_health` FOREIGN KEY (`id_health`) REFERENCES `hlt_health` (`id_health`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro da descricao da situacao de saude da pessoa que nao';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `hlt_framework_health_type`
--

DROP TABLE IF EXISTS `hlt_framework_health_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `hlt_framework_health_type` (
  `id_framework_health` int(10) unsigned NOT NULL auto_increment,
  `framework_health` varchar(80) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_framework_health`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro do quadro de saude onde sera registrada as situacoe';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `hlt_health`
--

DROP TABLE IF EXISTS `hlt_health`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `hlt_health` (
  `id_health` int(10) unsigned NOT NULL auto_increment,
  `id_person` int(10) unsigned NOT NULL,
  `drug_user` tinyint(4) NOT NULL,
  `vaccine` tinyint(4) NOT NULL,
  `vaccine_to_date` date default NULL,
  `health_plan` varchar(90) default NULL,
  `status` char(1) default NULL,
  PRIMARY KEY  (`id_health`),
  KEY `fk_hlt_health_per_person` (`id_person`),
  CONSTRAINT `fk_hlt_health_per_person` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro de alguns dados relativos a saude da pessoa';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `hlt_pregnancy`
--

DROP TABLE IF EXISTS `hlt_pregnancy`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `hlt_pregnancy` (
  `id_pregnancy` int(10) unsigned NOT NULL auto_increment,
  `id_person` int(10) unsigned NOT NULL,
  `prenatal_sis` varchar(10) default NULL,
  `beginning_pregnancy` date default NULL,
  `met` tinyint(4) NOT NULL,
  `status` char(1) default NULL,
  PRIMARY KEY  (`id_pregnancy`),
  KEY `fk_hlt_pregnancy_per_person` (`id_person`),
  CONSTRAINT `fk_hlt_pregnancy_per_person` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro dos dados referentes a gestacao';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `per_civil_certificate`
--

DROP TABLE IF EXISTS `per_civil_certificate`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `per_civil_certificate` (
  `id_person` int(10) unsigned NOT NULL,
  `id_uf` int(10) unsigned default NULL,
  `certificate_type` int(11) default NULL,
  `term` int(11) default NULL,
  `book` int(11) default NULL,
  `leaf` int(11) default NULL,
  `emission_date` date default NULL,
  `registry_office_name` varchar(100) default NULL,
  PRIMARY KEY  (`id_person`),
  KEY `certidaocivil_termo_key` (`term`,`leaf`,`book`),
  KEY `fk_per_civil_certificate_con_uf` (`id_uf`),
  CONSTRAINT `fk_per_civil_certificate_con_uf` FOREIGN KEY (`id_uf`) REFERENCES `con_uf` (`id_uf`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_civil_certificate_per_person` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro dos dados de certidao de civil da pessoa';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `per_ctps`
--

DROP TABLE IF EXISTS `per_ctps`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `per_ctps` (
  `id_person` int(10) unsigned NOT NULL,
  `id_uf` int(10) unsigned default NULL,
  `number` int(11) default NULL,
  `series` int(11) default NULL,
  `emission_date` date default NULL,
  PRIMARY KEY  (`id_person`),
  KEY `fk_per_ctps_con_uf` (`id_uf`),
  CONSTRAINT `fk_per_ctps_con_uf` FOREIGN KEY (`id_uf`) REFERENCES `con_uf` (`id_uf`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_ctps_per_person` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro dos dados relativos a carteira profissional da pess';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `per_deficiency`
--

DROP TABLE IF EXISTS `per_deficiency`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `per_deficiency` (
  `id_person` int(10) unsigned NOT NULL,
  `id_deficiency` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_person`,`id_deficiency`),
  KEY `fk_per_deficiency_per_deficiency_type` (`id_deficiency`),
  CONSTRAINT `fk_per_deficiency_per_deficiency_type` FOREIGN KEY (`id_deficiency`) REFERENCES `per_deficiency_type` (`id_deficiency`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_deficiency_per_person` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Vincular a pessoa e as deficiencias que essas possam ter';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `per_deficiency_type`
--

DROP TABLE IF EXISTS `per_deficiency_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `per_deficiency_type` (
  `id_deficiency` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(80) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_deficiency`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Registro dos tipos deficiencias  (cegueira, surdez, etc)';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `per_document`
--

DROP TABLE IF EXISTS `per_document`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `per_document` (
  `id_person` int(10) unsigned NOT NULL,
  `cpf` varchar(11) default NULL,
  `nis` varchar(21) default NULL,
  `sus_card` varchar(15) default NULL,
  `ra` varchar(20) default NULL,
  `rg_number` varchar(21) default NULL,
  `rg_complement` varchar(5) default NULL,
  `rg_emission_date` date default NULL,
  `rg_sender` varchar(10) default NULL,
  `id_rg_uf` int(10) unsigned default NULL,
  `title_number` varchar(13) default NULL,
  `title_zone` varchar(4) default NULL,
  `title_section` varchar(4) default NULL,
  PRIMARY KEY  (`id_person`),
  KEY `fk_per_document_con_uf` (`id_rg_uf`),
  CONSTRAINT `fk_per_document_con_uf` FOREIGN KEY (`id_rg_uf`) REFERENCES `con_uf` (`id_uf`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_document_per_person` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro dos documentos pessoais das pessoas';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `per_marital_status`
--

DROP TABLE IF EXISTS `per_marital_status`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `per_marital_status` (
  `id_marital_status` int(10) unsigned NOT NULL,
  `marital_status` varchar(30) NOT NULL,
  PRIMARY KEY  (`id_marital_status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro do estado civil da pessoa';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `per_nationality`
--

DROP TABLE IF EXISTS `per_nationality`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `per_nationality` (
  `id_nationality` int(10) unsigned NOT NULL auto_increment,
  `nationality` varchar(80) NOT NULL,
  PRIMARY KEY  (`id_nationality`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Registro da nacionalidade (Brasileiro, Estrangeiro, Naturali';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `per_person`
--

DROP TABLE IF EXISTS `per_person`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `per_person` (
  `id_person` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(90) NOT NULL,
  `metaname` varchar(60) NOT NULL,
  `nickname` varchar(80) default NULL,
  `metanickname` varchar(60) default NULL,
  `sex` char(1) NOT NULL default 'M',
  `tattoo` varchar(500) default NULL,
  `native_country` varchar(100) default NULL,
  `arrival_date` date default NULL,
  `death_date` date default NULL,
  `birth_date` date NOT NULL,
  `id_nationality` int(10) unsigned default NULL,
  `id_race` int(10) unsigned default NULL,
  `id_marital_status` int(10) unsigned default NULL,
  PRIMARY KEY  (`id_person`),
  KEY `fk_per_person_per_marital_status` (`id_marital_status`),
  KEY `fk_per_person_per_nationality` (`id_nationality`),
  KEY `fk_per_person_per_race` (`id_race`),
  CONSTRAINT `fk_per_person_per_marital_status` FOREIGN KEY (`id_marital_status`) REFERENCES `per_marital_status` (`id_marital_status`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_person_per_nationality` FOREIGN KEY (`id_nationality`) REFERENCES `per_nationality` (`id_nationality`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_person_per_race` FOREIGN KEY (`id_race`) REFERENCES `per_race` (`id_race`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=32256 DEFAULT CHARSET=latin1 COMMENT='Registro dos dados cadastrais das pessoas';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `per_person_address_temp`
--

DROP TABLE IF EXISTS `per_person_address_temp`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `per_person_address_temp` (
  `id_person` int(10) unsigned NOT NULL,
  `id_address` int(10) unsigned default NULL,
  `live_since` date default NULL,
  `number` int(10) unsigned default NULL,
  `complement` varchar(72) default NULL,
  `reference_point` varchar(72) default NULL,
  PRIMARY KEY  (`id_person`),
  KEY `fk_per_person_address_temp_con_address` (`id_address`),
  KEY `fk_per_person_address_temp_per_person` (`id_person`),
  CONSTRAINT `fk_per_person_address_temp_con_address` FOREIGN KEY (`id_address`) REFERENCES `con_address` (`id_address`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_person_address_temp_per_person` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registro de um endereco temporario que a pessoa pode estar m';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `per_person_change_history`
--

DROP TABLE IF EXISTS `per_person_change_history`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `per_person_change_history` (
  `id_person_change_history` int(10) unsigned NOT NULL auto_increment,
  `id_reference_foreign` int(10) unsigned default NULL,
  `id_person` int(10) unsigned NOT NULL,
  `id_resource` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `dat_operation` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `table_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`id_person_change_history`),
  KEY `person_history_FK` (`id_person`),
  KEY `resource_person_history_FK` (`id_resource`),
  KEY `user_person_history_FK` (`id_user`),
  CONSTRAINT `person_history_FK` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `resource_person_history_FK` FOREIGN KEY (`id_resource`) REFERENCES `auth_resource` (`id_resource`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_person_history_FK` FOREIGN KEY (`id_user`) REFERENCES `auth_user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `per_person_telephone`
--

DROP TABLE IF EXISTS `per_person_telephone`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `per_person_telephone` (
  `id_person` int(10) unsigned NOT NULL,
  `id_telephone` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_person`,`id_telephone`),
  KEY `fk_per_person_telephone_con_telephone_number` (`id_telephone`),
  CONSTRAINT `fk_per_person_telephone_con_telephone_number` FOREIGN KEY (`id_telephone`) REFERENCES `con_telephone_number` (`id_telephone_number`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_per_person_telephone_per_person` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Vincular numero de telefone com uma pessoa';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `per_race`
--

DROP TABLE IF EXISTS `per_race`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `per_race` (
  `id_race` int(10) unsigned NOT NULL auto_increment,
  `race` varchar(30) NOT NULL,
  PRIMARY KEY  (`id_race`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Registro da raca da pessoa';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `res_building_type`
--

DROP TABLE IF EXISTS `res_building_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `res_building_type` (
  `id_building` int(10) unsigned NOT NULL auto_increment,
  `building` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_building`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COMMENT='Registro do tipo de construcao do domicilio (taipa, adobe, t';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `res_family_residence`
--

DROP TABLE IF EXISTS `res_family_residence`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `res_family_residence` (
  `id_family` int(10) unsigned NOT NULL,
  `id_residence` int(10) unsigned NOT NULL,
  `live_since` date NOT NULL,
  PRIMARY KEY  (`id_residence`,`id_family`),
  KEY `fk_res_family_residence_fam_family_id` (`id_family`),
  CONSTRAINT `fk_res_family_residence_fam_family_id` FOREIGN KEY (`id_family`) REFERENCES `fam_family_id` (`id_family`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_family_residence_res_residence` FOREIGN KEY (`id_residence`) REFERENCES `res_residence` (`id_residence`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Vinculo entre a familia e um domicilio.';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `res_illumination_type`
--

DROP TABLE IF EXISTS `res_illumination_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `res_illumination_type` (
  `id_illumination` int(10) unsigned NOT NULL auto_increment,
  `illumination` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_illumination`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Registro do tipo de iluminacao do domicilio (relogio proprio';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `res_locality_type`
--

DROP TABLE IF EXISTS `res_locality_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `res_locality_type` (
  `id_locality` int(10) unsigned NOT NULL auto_increment,
  `place` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_locality`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Registro do tipo de domicilio, rural ou urbano';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `res_morada_type`
--

DROP TABLE IF EXISTS `res_morada_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `res_morada_type` (
  `id_morada` int(10) unsigned NOT NULL auto_increment,
  `morada` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_morada`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Registro do tipo de moraria(casa, apartamento, comodo, etc)';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `res_residence`
--

DROP TABLE IF EXISTS `res_residence`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `res_residence` (
  `id_residence` int(10) unsigned NOT NULL auto_increment,
  `id_address` int(10) unsigned default NULL,
  `number` int(11) default NULL,
  `complement` varchar(72) default NULL,
  `reference_point` varchar(72) default NULL,
  `accommodation` int(11) default NULL,
  `id_morada` int(10) unsigned default NULL,
  `id_status` int(10) unsigned default NULL,
  `id_locality` int(10) unsigned default NULL,
  `id_building` int(10) unsigned default NULL,
  `id_supply` int(10) unsigned default NULL,
  `id_water` int(10) unsigned default NULL,
  `id_illumination` int(10) unsigned default NULL,
  `id_sanitary` int(10) unsigned default NULL,
  `id_trash` int(10) unsigned default NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_residence`),
  KEY `fk_res_residence_con_address` (`id_address`),
  KEY `fk_res_residence_res_building_type` (`id_building`),
  KEY `fk_res_residence_res_illumination_type` (`id_illumination`),
  KEY `fk_res_residence_res_locality_type` (`id_locality`),
  KEY `fk_res_residence_res_morada_type` (`id_morada`),
  KEY `fk_res_residence_res_sanitary_type` (`id_sanitary`),
  KEY `fk_res_residence_res_status_type` (`id_status`),
  KEY `fk_res_residence_res_supply_type` (`id_supply`),
  KEY `fk_res_residence_res_trash_type` (`id_trash`),
  KEY `fk_res_residence_res_water_type` (`id_water`),
  CONSTRAINT `fk_res_residence_con_address` FOREIGN KEY (`id_address`) REFERENCES `con_address` (`id_address`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_building_type` FOREIGN KEY (`id_building`) REFERENCES `res_building_type` (`id_building`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_illumination_type` FOREIGN KEY (`id_illumination`) REFERENCES `res_illumination_type` (`id_illumination`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_locality_type` FOREIGN KEY (`id_locality`) REFERENCES `res_locality_type` (`id_locality`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_morada_type` FOREIGN KEY (`id_morada`) REFERENCES `res_morada_type` (`id_morada`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_sanitary_type` FOREIGN KEY (`id_sanitary`) REFERENCES `res_sanitary_type` (`id_sanitary`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_status_type` FOREIGN KEY (`id_status`) REFERENCES `res_status_type` (`id_status`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_supply_type` FOREIGN KEY (`id_supply`) REFERENCES `res_supply_type` (`id_supply`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_trash_type` FOREIGN KEY (`id_trash`) REFERENCES `res_trash_type` (`id_trash`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_res_residence_res_water_type` FOREIGN KEY (`id_water`) REFERENCES `res_water_type` (`id_water`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15690 DEFAULT CHARSET=latin1 COMMENT='Registro das caracteristicas de cada domicilio';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `res_sanitary_type`
--

DROP TABLE IF EXISTS `res_sanitary_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `res_sanitary_type` (
  `id_sanitary` int(10) unsigned NOT NULL auto_increment,
  `sanitary` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_sanitary`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Registro tipo de escoamento sanitario do domicilio (rede pub';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `res_status_type`
--

DROP TABLE IF EXISTS `res_status_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `res_status_type` (
  `id_status` int(10) unsigned NOT NULL auto_increment,
  `status_type` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_status`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COMMENT='Registro da situacao do domicilio (proprio, alugado, invasao';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `res_supply_type`
--

DROP TABLE IF EXISTS `res_supply_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `res_supply_type` (
  `id_supply` int(10) unsigned NOT NULL auto_increment,
  `supply` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_supply`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Registro do tipo de abastecimento de agua (rede publica, nas';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `res_trash_type`
--

DROP TABLE IF EXISTS `res_trash_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `res_trash_type` (
  `id_trash` int(10) unsigned NOT NULL auto_increment,
  `trash` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_trash`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Registro do destino do lixo do domicilio (coleta, queimado, ';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `res_water_type`
--

DROP TABLE IF EXISTS `res_water_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `res_water_type` (
  `id_water` int(10) unsigned NOT NULL auto_increment,
  `water` varchar(30) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_water`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Registro do tipo de tratamento da agua da moradia (filtracao';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `sop_social_program`
--

DROP TABLE IF EXISTS `sop_social_program`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sop_social_program` (
  `id_social_program` int(10) unsigned NOT NULL auto_increment,
  `id_person` int(10) unsigned NOT NULL,
  `id_social_program_type` int(10) unsigned NOT NULL,
  `register_date` date default NULL,
  `status` char(1) default NULL,
  PRIMARY KEY  (`id_social_program`),
  KEY `beneficio_idpessoa_fkey` (`id_person`),
  KEY `fk_beneficio_tipo_beneficio` (`id_social_program_type`),
  CONSTRAINT `beneficio_idpessoa_fkey` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_beneficio_tipo_beneficio` FOREIGN KEY (`id_social_program_type`) REFERENCES `sop_social_program_type` (`id_social_program_type`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2420 DEFAULT CHARSET=latin1 COMMENT='Vinculo entre a pessoa e beneficios de programas sociais';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `sop_social_program_origin_type`
--

DROP TABLE IF EXISTS `sop_social_program_origin_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sop_social_program_origin_type` (
  `id_origin` int(10) unsigned NOT NULL auto_increment,
  `origin` varchar(80) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_origin`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Registro da tipo da origem do beneficio (federal, municipal,';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `sop_social_program_type`
--

DROP TABLE IF EXISTS `sop_social_program_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sop_social_program_type` (
  `id_social_program_type` int(10) unsigned NOT NULL auto_increment,
  `id_origin` int(10) unsigned NOT NULL COMMENT 'federal, estadual, municipal, entidade',
  `benefit` varchar(80) NOT NULL,
  `status` char(1) NOT NULL,
  PRIMARY KEY  (`id_social_program_type`),
  KEY `fk_social_program_type_social_program_origin_type` (`id_origin`),
  CONSTRAINT `fk_social_program_type_social_program_origin_type` FOREIGN KEY (`id_origin`) REFERENCES `sop_social_program_origin_type` (`id_origin`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COMMENT='Registro dos beneficios disponiveis no municipio';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `sys_person_inserts_by_user`
--

DROP TABLE IF EXISTS `sys_person_inserts_by_user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sys_person_inserts_by_user` (
  `id_log` int(10) unsigned NOT NULL auto_increment,
  `id_person` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `tstamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id_log`),
  KEY `fk_sys_person_inserts_by_user_auth_user` (`id_user`),
  KEY `fk_sys_person_inserts_by_user_per_person` (`id_person`),
  CONSTRAINT `fk_sys_person_inserts_by_user_auth_user` FOREIGN KEY (`id_user`) REFERENCES `auth_user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sys_person_inserts_by_user_per_person` FOREIGN KEY (`id_person`) REFERENCES `per_person` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=32256 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-01-21 16:40:53


alter table per_person add unique (name,birth_date);
