<?php
/*
 * This program is free software: you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation, either version 2 of the License, or 
 * (at your option) any later version. 
 * 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 * GNU General Public License for more details. 
 *
 * You should have received a copy of the GNU General Public License 
 * along with this program.  If not, see <http://www.gnu.org/licenses/>. 
 * 
 * @copyright Fundação Telefônica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Araçatuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guarujá - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de São Carlos - http://www.saocarlos.sp.gov.br 
 * @copyright Prefeitura Municipal de Várzea Paulista - http://www.varzeapaulista.sp.gov.br 
 * 
 * @copyright Copyright (C) 2008
 * 
 * @license GNU General Public License (GPL) - http://www.gnu.org/licenses/gpl.html 
 * 
 * @author Consulting services for Social Networks Creation by Instituto Fonte para o Desenvolvimento Social  - < fonte@fonte.org.br> - http:// www.fonte.org.br 
 * 
 * @author Consulting services for Software Requirements  by WebUse - <webuse@webuse.com.br > - http://webuse.com.br 
 * 
 * @author Initial Software development by W3S Solutions - <w3s@w3s.com.br> - http://w3s.com.br 
 * 
 * Changelog
 * 
 * Author                                           Date                               History 
 * -----------------------------------------        ------------                       ------------------ 
 * Saulo Esteves Rodrigues  - W3S		   			29/01/2008	                       Create file 
 * 
 */

/*
 * Requirements
 * 
 * PHP 5.1.4 (or higher)
 * mod_rewrite support
 * extension=php_pdo.dll
 * extension=php_pdo_mysql.dll
 * AllowOverride all
 */

error_reporting(E_ALL|E_STRICT);

define('LOCALE', 'pt_BR');//sufixo para resources, help e systemMessages

//locale
setlocale (LC_ALL, LOCALE);
date_default_timezone_set('America/Sao_Paulo');

//classpath
set_include_path('.'
.PATH_SEPARATOR.'./lib'
.PATH_SEPARATOR.'./application/models/'
.PATH_SEPARATOR.'./application/models/act_'
.PATH_SEPARATOR.'./application/models/ast_'
.PATH_SEPARATOR.'./application/models/auth_'
.PATH_SEPARATOR.'./application/models/con_'
.PATH_SEPARATOR.'./application/models/cov_'
.PATH_SEPARATOR.'./application/models/csg_'
.PATH_SEPARATOR.'./application/models/eas_'
.PATH_SEPARATOR.'./application/models/edu_'
.PATH_SEPARATOR.'./application/models/emp_'
.PATH_SEPARATOR.'./application/models/ent_'
.PATH_SEPARATOR.'./application/models/exp_'
.PATH_SEPARATOR.'./application/models/fam_'
.PATH_SEPARATOR.'./application/models/gas_'
.PATH_SEPARATOR.'./application/models/hlt_'
.PATH_SEPARATOR.'./application/models/per_'
.PATH_SEPARATOR.'./application/models/res_'
.PATH_SEPARATOR.'./application/models/sop_'
.PATH_SEPARATOR.'./application/models/sys_'
.PATH_SEPARATOR.'./application/validators/'
.PATH_SEPARATOR.'./application/forms/'
.PATH_SEPARATOR.'./application/utils/'
.PATH_SEPARATOR.'./application/utils/auth'
.PATH_SEPARATOR.'./application/business/'
.PATH_SEPARATOR.'./application/loggers/'
.PATH_SEPARATOR.'./application/acl/'
.PATH_SEPARATOR.'./application/plugins/'
.PATH_SEPARATOR.'./application/controllers/'
.PATH_SEPARATOR.'./application/report/'
.PATH_SEPARATOR.get_include_path());

include_once "Zend/Loader.php";
include_once "tables.php";
include_once "mappings.php";
include_once "registry.php";
include_once "errorHandler.php";
set_error_handler('errorHandler');

//loader
Zend_Loader::loadClass('Zend_Acl');
Zend_Loader::loadClass('Zend_Controller_Front');
Zend_Loader::loadClass('Zend_Controller_Plugin_Abstract');
Zend_Loader::loadClass('Zend_Config_Ini');
Zend_Loader::loadClass('Zend_Config_Xml');
Zend_Loader::loadClass('Zend_Registry');
Zend_Loader::loadClass('Zend_Db');
Zend_Loader::loadClass('Zend_Db_Table');
Zend_Loader::loadClass('Zend_Auth');
Zend_Loader::loadClass('Zend_Log');
Zend_Loader::loadClass('Zend_Log_Writer_Stream');
Zend_Loader::loadClass('Logger');
Zend_Loader::loadClass('AccessControl');
Zend_Loader::loadClass('AuthPlugin');
Zend_Loader::loadClass('PublicFunctions');
Zend_Loader::loadClass('UserLogged');
Zend_Loader::loadClass('ResourcePermission');
Zend_Loader::loadClass('Constants');

//configs e resources
$config			= new Zend_Config_Ini('./application/config.ini', 'system');
$dbconfig		= new Zend_Config_Ini('./application/dbconfig.ini','database');
$validatorResources	= new Zend_Config_Ini('./application/resources/resources_'.LOCALE.'.ini', 'validator');
$labelResources		= new Zend_Config_Ini('./application/resources/resources_'.LOCALE.'.ini', 'label');
$sysMessages		= new Zend_Config_Ini('./application/resources/systemMessages_'.LOCALE.'.ini', 	'messages');

//registro
Zend_Registry::set(CONFIG, 		$config);
Zend_Registry::set(DBCONFIG, 		$dbconfig);
Zend_Registry::set(VALIDATOR_RESOURCES, $validatorResources);
Zend_Registry::set(LABEL_RESOURCES, 	$labelResources);
Zend_Registry::set(SYS_MESSAGES,	$sysMessages);
Zend_Registry::set(TPAGE, 		$config->pagination->size);
Zend_Registry::set(TENTITYPAGE,		$config->pagination->entitysize);
Zend_Registry::set(TUSERPAGE,		$config->pagination->usersize);
Zend_Registry::set(APP_PATH, 		dirname( __FILE__ ));

//logger
Logger::init('./logs/');

//database
try
{
	$db = Zend_Db::factory($dbconfig->db->adapter, $dbconfig->db->config->toArray());
//	$db->fetchCol('SELECT 1 + 1'); $db->query("SET NAMES 'UTF8'");
}
catch(Zend_Exception $e)
{
	Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
	trigger_error($sysMessages->database->comunication->fail, E_USER_ERROR);
}
$db->setFetchMode(Zend_Db::FETCH_ASSOC);
Zend_Db_Table::setDefaultAdapter($db);
Zend_Registry::set(DB_CONNECTION, $db);

//controller
$controller = Zend_Controller_Front::getInstance();
$controller->throwExceptions(true); 
$controller->setControllerDirectory('./application/controllers');

//autenticação
$auth 		= Zend_Auth::getInstance();
$acl 		= new AccessControl($auth); 
$controller->registerPlugin(new AuthPlugin($auth, $acl));

//run
try
{
	$controller->dispatch();
}
catch(Zend_Controller_Dispatcher_Exception $e)
{
	Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
	trigger_error($sysMessages->url->invalid, E_USER_ERROR);
}
catch(Zend_Controller_Action_Exception $e)
{
	Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
	trigger_error($sysMessages->url->invalid, E_USER_ERROR);
}
