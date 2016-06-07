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
 * Saulo Esteves Rodrigues  - W3S		   			28/01/2008	                       Create file 
 * 
 */

require_once('AuthBusiness.php');

class Logger
{
	public static function init($pathToLogFiles)
	{
		//formatação
		$format = '%timestamp% %priorityName% (%priority%): %message%' . PHP_EOL;

		//loggers		
		$loggerOperations 	= Logger::createLogger($pathToLogFiles, 'operations-'.date("Y-m-d").'.txt', $format);
		$loggerErrors 		= Logger::createLogger($pathToLogFiles, 'errors-'.date("Y-m-d").'.txt', $format);
		$loggerAuth 		= Logger::createLogger($pathToLogFiles, 'auth-'.date("Y-m-d").'.txt', $format);
		$loggerImport 		= Logger::createLogger($pathToLogFiles, 'import-'.date("Y-m-d").'.txt', $format);
		$loggerImportAddess	= Logger::createLogger($pathToLogFiles, 'importAddress-'.date("Y-m-d").'.txt', $format);
		$loggerImportSchool	= Logger::createLogger($pathToLogFiles, 'importSchool-'.date("Y-m-d").'.txt', $format);
		
		Zend_Registry::set(LOGGER_OPER, $loggerOperations);
		Zend_Registry::set(LOGGER_ERR, $loggerErrors);
		Zend_Registry::set(LOGGER_AUTH, $loggerAuth);
		Zend_Registry::set(LOGGER_IMPORT, $loggerImport);
		Zend_Registry::set(LOGGER_IMPORT_ADDRESS, $loggerImportAddess);
		Zend_Registry::set(LOGGER_IMPORT_SCHOOL, $loggerImportSchool);
	}
	
	private function createLogger($path, $filename, $format)
	{
		//system messages
		$sysMessages 		= Zend_Registry::get(SYS_MESSAGES);
		
		//file & path
		$stream = @fopen($path.$filename, 'a', false);
		if(!$stream)
		{
		    trigger_error($sysMessages->fail->open->log->file, E_USER_ERROR);
		}
		
		$writer 	= new Zend_Log_Writer_Stream($stream);
		$formatter 	= new Zend_Log_Formatter_Simple($format);
		$writer->setFormatter($formatter);
		$logger 	= new Zend_Log($writer);
		
		return $logger;
	}

/**
	EMERG   = 0;  // Emergency: system is unusable
	ALERT   = 1;  // Alert: action must be taken immediately
	CRIT    = 2;  // Critical: critical conditions
	ERR     = 3;  // Error: error conditions
	WARN    = 4;  // Warning: warning conditions
	NOTICE  = 5;  // Notice: normal but significant condition
	INFO    = 6;  // Informational: informational messages
	DEBUG   = 7;  // Debug: debug messages
 */

	public static function loggerOperation($str)
	{
		$loggerOperations = Zend_Registry::get(LOGGER_OPER);
		$loggerOperations->info($str.' [operador='.AuthBusiness::getUserName().']');
	}
	
	public static function loggerError($str)
	{
		$loggerOperations = Zend_Registry::get(LOGGER_ERR);
		$loggerOperations->log($str, Zend_Log::ERR);
	}
	
	public static function loggerAuth($str)
	{
		$loggerOperations = Zend_Registry::get(LOGGER_AUTH);
		$loggerOperations->info($str);
	}
	public static function loggerImport($str)
	{
		$loggerOperations = Zend_Registry::get(LOGGER_IMPORT);
		$loggerOperations->info($str);
	}
	public static function loggerImportAddress($str)
	{
		$loggerOperations = Zend_Registry::get(LOGGER_IMPORT_ADDRESS);
		$loggerOperations->info($str);
	}
	public static function loggerImportSchool($str)
	{
		$loggerOperations = Zend_Registry::get(LOGGER_IMPORT_SCHOOL);
		$loggerOperations->info($str);
	}
}