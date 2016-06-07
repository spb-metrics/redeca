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
 * @copyright Funda��o Telef�nica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Ara�atuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guaruj� - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de S�o Carlos - http://www.saocarlos.sp.gov.br 
 * @copyright Prefeitura Municipal de V�rzea Paulista - http://www.varzeapaulista.sp.gov.br 
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
 * Jefferson Barros Lima  - W3S		    			25/02/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class ImportBusiness extends BasicBusiness
{
	/**
	 * Retorna a configura��o dos diret�rios e seus respectivos parsers
	 */
	public static function getParserCfg()
	{
		//array [Diret�rio => parser]
		return $arrTypes = array( 
								Constants::FOLDER_TYPE_1 => Constants::TYPE_1, 
								Constants::FOLDER_TYPE_2 => Constants::TYPE_2, 
								Constants::FOLDER_TYPE_3 => Constants::TYPE_3, 
								Constants::FOLDER_TYPE_4 => Constants::TYPE_4,
								Constants::FOLDER_TYPE_5 => Constants::TYPE_5
							); 
	}
	
	public static function schoolStatus()
	{
		//Array com as informa��es do arquivo de cad unico
		$infos = array();
		
		Zend_Loader::loadClass('ImportStepInfo');

		// Vari�vel que representa o passo do upload
		$step = 1;
		
		//Verifica a pasta
		$path = PATH_ROOT. Constants::FOLDER_ROOT_SCHOOL;
		
		// Verifica o status
		$res = FileHelper::verifyFolderStatusForSchool($path, 10);
		
		$info = new ImportStepInfo();
		$info->setImported(TRUE);
		$info->setStep($step);
		$info->setFolderName(Constants::FOLDER_ROOT_SCHOOL);
		
		//se n�o existir a pasta, e n�o for poss�vel cri�-la
		if($res[RETURN_FOLDER_KEY] === FALSE)
		{
			$info->setMessage("Sem permiss�o de escrita para a pasta: ".$path);
			Logger::loggerImportSchool("Sem permiss�o de escrita para a pasta: ".$path);
			$info->setImported(FALSE);
		}
		//s� vai haver falha no parser se existirem arquivos no diret�rio, e n�o forem compat�veis.
		//se n�o existirem arquivos no diret�rio, n�o haver� falha no parser.
		// Caso haja falha no parser, o arquivo � apagado do diret�rio
		if($res[RETURN_PARSER_KEY] === FALSE)
		{
			if($res[RETURN_FILE_KEY])
			{
				if(is_array($res[RETURN_FILE_KEY]))
				{
					$info->setMessage("Falha no parser do arquivo ".implode(' ', $res[RETURN_FILE_KEY])." na pasta ".Constants::FOLDER_ROOT_SCHOOL);
					Logger::loggerImportSchool("Falha no parser do arquivo ".implode(' ', $res[RETURN_FILE_KEY])." na pasta ".Constants::FOLDER_ROOT_SCHOOL);
				}
				else
				{
					$info->setMessage("Falha no parser do arquivo ".$res[RETURN_FILE_KEY]." na pasta ".Constants::FOLDER_ROOT_SCHOOL);
					Logger::loggerImportSchool("Falha no parser do arquivo ".$res[RETURN_FILE_KEY]." na pasta ".Constants::FOLDER_ROOT_SCHOOL);
				}
			}
			else
			{
				$info->setMessage("� necess�rio importar arquivo para a pasta ".Constants::FOLDER_ROOT_SCHOOL);
				Logger::loggerImportSchool("� necess�rio importar arquivo para a pasta ".Constants::FOLDER_ROOT_SCHOOL);
			}
			// Remove o diret�rio

			$info->setImported(FALSE);
		}
		//verifica se existe pelo menos 1 linha parseada
		if($res[RETURN_NUMBER_OF_PARSED_LINES_KEY] == 0)
		{
			$info->setImported(FALSE);
			Logger::loggerImportSchool('N�o h� linhas no arquivo.');
		}

		$infos[] = $info;
		return $infos;
	}
	
	public static function singleRegisterStatus()
	{
		//Array com as informa��es do arquivo de cad unico
		$infos = array();
		
		Zend_Loader::loadClass('ImportStepInfo');

		// Vari�vel que representa o passo do upload
		$step = 1;
		
		//Verifica a pasta
		$path = PATH_ROOT. Constants::FOLDER_ROOT_SINGLEREGISTER;
		
		// Verifica o status
		$res = FileHelper::verifyFolderStatusForSingleRegister($path, 10);

		$info = new ImportStepInfo();
		$info->setImported(TRUE);
		$info->setStep($step);
		$info->setFolderName(Constants::FOLDER_ROOT_SINGLEREGISTER);
		
		//se n�o existir a pasta, e n�o for poss�vel cri�-la
		if($res[RETURN_FOLDER_KEY] === FALSE)
		{
			Logger::loggerImport("Sem permiss�o de escrita para a pasta: ".$path);
			$info->setMessage("Sem permiss�o de escrita para a pasta: ".$path);
			$info->setImported(FALSE);
		}
		//s� vai haver falha no parser se existirem arquivos no diret�rio, e n�o forem compat�veis.
		//se n�o existirem arquivos no diret�rio, n�o haver� falha no parser.
		// Caso haja falha no parser, o arquivo � apagado do diret�rio
		if($res[RETURN_PARSER_KEY] === FALSE)
		{
			if($res[RETURN_FILE_KEY])
			{
				if(is_array($res[RETURN_FILE_KEY]))
				{
					$info->setMessage("Falha no parser do(s) arquivo(s) ".implode(' ', $res[RETURN_FILE_KEY])." na pasta ".Constants::FOLDER_ROOT_SINGLEREGISTER);
					Logger::loggerImport("Falha no parser do(s) arquivo(s) ".implode(' ', $res[RETURN_FILE_KEY])." na pasta ".Constants::FOLDER_ROOT_SINGLEREGISTER);
				}
				else
				{
					$info->setMessage("Falha no parser do(s) arquivo(s) ".$res[RETURN_FILE_KEY]." na pasta ".Constants::FOLDER_ROOT_SINGLEREGISTER);
					Logger::loggerImport("Falha no parser do(s) arquivo(s) ".$res[RETURN_FILE_KEY]." na pasta ".Constants::FOLDER_ROOT_SINGLEREGISTER);
				}
			}
			else
			{
				$info->setMessage("� necess�rio importar arquivo para a pasta ".Constants::FOLDER_ROOT_SINGLEREGISTER);
				Logger::loggerImport("� necess�rio importar arquivo para a pasta ".Constants::FOLDER_ROOT_SINGLEREGISTER);
			}
			// Remove o diret�rio

			$info->setImported(FALSE);
		}
		//verifica se existe pelo menos 1 linha parseada
		if($res[RETURN_NUMBER_OF_PARSED_LINES_KEY] == 0)
		{
			$info->setImported(FALSE);
			Logger::loggerImport('N�o h� linhas no arquivo.');
		}

		$infos[] = $info;
		return $infos;
	}
	
	/**
	 * Processa a importa��o de escolas
	 */
	public static function processSchool()
	{		
		$defaultTime = ini_get('max_execution_time');
		$config = Zend_Registry::get(CONFIG);
		// Seta tempo limite de processamento de uma requisi��o
		set_time_limit($config->import->zipcode->max->time->limit);
		
		$start = TRUE;
		// Armazena a mensagem indicando o sucesso ou n�o da importa��o
		$fileNameError = null;
		
		Logger::loggerImportSchool('Iniciando importa��o...');
		Logger::loggerImportSchool('Verificando os arquivos necess�rios...');
				
		//Verifica a pasta
		$path = PATH_ROOT. Constants::FOLDER_ROOT_SCHOOL;
		
		// Verifica o status
		$res = FileHelper::verifyFolderStatusForSchool($path, 10);
		
		if($res[RETURN_FOLDER_KEY] === FALSE ||	$res[RETURN_PARSER_KEY] === FALSE || $res[RETURN_NUMBER_OF_PARSED_LINES_KEY] == 0)
		{
			$start = FALSE;
			
			Logger::loggerImportSchool('Falha ao ler um ou mais arquivos. Favor verifique novamente os arquivos.');
		}
		
		// Se nenhum problema ocorrer, d� in�cio ao processamento
		if($start)
		{
			if(is_dir($path))
			{
				
				try
				{
					
					Parser::parseSchool($path, FileHelper::getFilesFromDir($path), $numberOfLines=0, $fileNameError=NULL, $flag=true);		
				}
				catch(Zend_Exception $e)
				{
					Logger::loggerImportSchool('Importa��o interrompida. '.$e);
					trigger_error('Importa��o interrompida. '.$e);
				}
			}
			else
			{
				Logger::loggerImportSchool('Diret�rio de importa��o inv�lido ou inexistente: '.$path);
			}
		}
		// Seta tempo limite de processamento de uma requisi��o para o valor padr�o
		set_time_limit($defaultTime);
		return $flag;
	}
	
	/**
	 * Processa a importa��o
	 */
	public static function processSingleRegister()
	{
		Zend_Loader::loadClass('MetaPhoneClass');
		$config = Zend_Registry::get(CONFIG);
		$defaultTime = ini_get('max_execution_time');
		// Seta tempo limite de processamento de uma requisi��o
		set_time_limit($config->import->zipcode->max->time->limit);
		
		$start = TRUE;
		// Armazena a mensagem indicando o sucesso ou n�o da importa��o
		$fileNameError = null;
		
		Logger::loggerImport('Iniciando importa��o...');
		Logger::loggerImport('Verificando os arquivos necess�rios...');
				
		//Verifica a pasta
		$path = PATH_ROOT. Constants::FOLDER_ROOT_SINGLEREGISTER;
		// Verifica o status
		$res = FileHelper::verifyFolderStatusForSingleRegister($path, 10);
		
		if($res[RETURN_FOLDER_KEY] === FALSE ||	$res[RETURN_PARSER_KEY] === FALSE || $res[RETURN_NUMBER_OF_PARSED_LINES_KEY] == 0)
		{
			$start = FALSE;
			Logger::loggerImport('Falha ao ler um ou mais arquivos. Favor verifique novamente os arquivos.');
			Logger::loggerImport('Opera��o abortada.');
		}
		
		// Se nenhum problema ocorrer, d� in�cio ao processamento
		if($start)
		{
			if(is_dir($path))
			{
				try
				{
					Logger::loggerImport('Verifica��o feita com sucesso. Iniciando importa��o para o banco de dados');
					Parser::parseSingleRegister($path, FileHelper::getFilesFromDir($path), $numberOfLines=0, $flag=true);
					Logger::loggerImport('Importa��o para o banco de dados feita com sucesso!');		
				}
				catch(Zend_Exception $e)
				{
					Logger::loggerImport('Importa��o interrompida. '.$e);
					trigger_error('Importa��o interrompida. '.$e);
				}
			}
			else
			{
				Logger::loggerImport('Diret�rio de importa��o inv�lido ou inexistente: '.$path);
			}
		}
		// Seta tempo limite de processamento de uma requisi��o para o valor padr�o
		set_time_limit($defaultTime);
		return $flag;
	}
	
	public static function zipCodeStepStatus()
	{
		//Array com as informa��es dos arquivos de CEP
		$infos = array();
		Zend_Loader::loadClass('MetaPhoneClass');
		Zend_Loader::loadClass('ImportStepInfo');

		// Vari�vel que representa os passos do upload
		$step = 1;
		//verifica as pastas
		foreach(self::getParserCfg() as $folder => $typeFile)
		{
			$path = PATH_ROOT. Constants::FOLDER_ROOT_ZIPCODE .$folder;
			// Verifica o status
			$res = FileHelper::verifyFolderStatus($path, $typeFile, 10);
			
			$info = new ImportStepInfo();
			$info->setImported(TRUE);
			$info->setStep($step);
			$info->setFolderName($folder);
			
			//se n�o existir a pasta, e n�o for poss�vel cri�-la
			if($res[RETURN_FOLDER_KEY] === FALSE)
			{
				Logger::loggerImportAddress("Sem permiss�o de escrita para a pasta: ".$folder);
				$info->setMessage("Sem permiss�o de escrita para a pasta: ".$folder);				
				$info->setImported(FALSE);
			}
			//s� vai haver falha no parser se existirem arquivos no diret�rio, e n�o forem compat�veis.
			//se n�o existirem arquivos no diret�rio, n�o haver� falha no parser.
			// Caso haja falha no parser, o arquivo � apagado do diret�rio
			if($res[RETURN_PARSER_KEY] === FALSE)
			{
				if($res[RETURN_FILE_KEY])
				{
					if(is_array($res[RETURN_FILE_KEY])) 
					{
						Logger::loggerImportAddress("Falha no parser do(s) arquivo(s) ".implode(' ', $res[RETURN_FILE_KEY])." na pasta ".$folder);
						$info->setMessage("Falha no parser do(s) arquivo(s) ".implode(' ', $res[RETURN_FILE_KEY])." na pasta ".$folder);
					}
					else
					{
						Logger::loggerImportAddress("Falha no parser do(s) arquivo(s) ".$res[RETURN_FILE_KEY]." na pasta ".$folder);
						$info->setMessage("Falha no parser do(s) arquivo(s) ".$res[RETURN_FILE_KEY]." na pasta ".$folder);
					}
				}
				else
				{
					Logger::loggerImportAddress("� necess�rio importar arquivo para a pasta ".$folder);
					$info->setMessage("� necess�rio importar arquivo para a pasta ".$folder);
				}
				// Remove o diret�rio
				$info->setImported(FALSE);
			}
			//verifica se existe pelo menos 1 linha parseada
			if($res[RETURN_NUMBER_OF_PARSED_LINES_KEY] == 0)
			{
				Logger::loggerImportAddress("N�o existem linhas no arquivo.");
				$info->setImported(FALSE);
			}

			$infos[] = $info;
			unset($info);
			// incrementa
			$step++;
		}

		return $infos;
	}
	/**
	 * Processa a importa��o
	 */
	public static function process()
	{
		Zend_Loader::loadClass('MetaPhoneClass');
		$config = Zend_Registry::get(CONFIG);
		
		$defaultTime = ini_get('max_execution_time');
		// Seta tempo limite de processamento de uma requisi��o
		set_time_limit($config->import->zipcode->max->time->limit);

		$start = TRUE;
		// Armazena a mensagem indicando o sucesso ou n�o da importa��o
		$fileNameError = null;
		
		Logger::loggerImportAddress('Iniciando importa��o...');
		Logger::loggerImportAddress('Verificando os arquivos necess�rios...');
		
		// Verifica novamente os arquivos importados
		foreach(self::getParserCfg() as $folder => $typeFile)
		{
			$path = PATH_ROOT. Constants::FOLDER_ROOT_ZIPCODE .$folder;
			// Verifica o status
			$res = FileHelper::verifyFolderStatus($path, $typeFile, 10);
			if($res[RETURN_FOLDER_KEY] === FALSE ||
				$res[RETURN_PARSER_KEY] === FALSE ||
				$res[RETURN_NUMBER_OF_PARSED_LINES_KEY] == 0)
			{
				$start = FALSE;
				Logger::loggerImportAddress('Falha ao ler um ou mais arquivos. Favor verifique novamente os arquivos.');
			}
		}
		// Se nenhum problema ocorrer, d� in�cio ao processamento
		if($start)
		{
			$commonPath = PATH_ROOT. Constants::FOLDER_ROOT_ZIPCODE;
			if(is_dir($commonPath))
			{
				try
				{
					// Flag que indica que os dados processados devem ou n�o ser persistidos
					$db = TRUE;
					$continue = FALSE;

					$continue = self::processUf($commonPath, $fileNameError, $db);
					$filesWithError[] = $fileNameError;
					if($continue)
					{
						self::processCity($commonPath, $fileNameError, $db);
						$filesWithError[] = $fileNameError;
					}
					if($continue)
					{
						self::processNeighborhood($commonPath, $fileNameError, $db);
						$filesWithError[] = $fileNameError;
					}
					if($continue)
					{
						self::processAddress($commonPath, $fileNameError, $db);
						$filesWithError[] = $fileNameError;
					}
					if($continue)
					{
						self::processAddressNick($commonPath, $fileNameError, $db);
						$filesWithError[] = $fileNameError;
					}
					if($continue)
					{
						Logger::loggerImportAddress('Processo de importa��o finalizado com sucesso.');
					}
					else
					{
						Logger::loggerImportAddress('Importa��o interrompida.');
					}
				}
				catch(Zend_Exception $e)
				{
					Logger::loggerImportAddress('Importa��o interrompida. '.$e);
					trigger_error('Importa��o interrompida. '.$e);
				}
			}
			else
			{
				Logger::loggerImportAddress('Diret�rio de importa��o inv�lido ou inexistente: '.$commonPath);
			}
		}
		// Seta tempo limite de processamento de uma requisi��o para o valor padr�o
		set_time_limit($defaultTime);
		return $filesWithError;
	}
	
	/**
	 * Processa, insere os dados de UF e retorna um MAP com os Identificadores de UF, 
	 * caso contr�rio retorna NULL;
	 */
	public static function processUf($commonPath, &$fileNameError, &$db=null)
	{
		//------------ Processa informa��es de UF
		$ufFolder = $commonPath .'/'. Constants::FOLDER_TYPE_1;
		return Parser::parseUf($ufFolder, FileHelper::getFilesFromDir($ufFolder), $numberOfLines=0, $fileNameError, $db);
	}

	/**
	 * Processa e insere os dados de cidade. Caso contrario, retorna FALSE
	 */
	public static function processCity($commonPath, &$fileNameError, &$db=null)
	{
		$cityFolder = $commonPath .'/'. Constants::FOLDER_TYPE_2;
		return Parser::parseCity($cityFolder, FileHelper::getFilesFromDir($cityFolder), $numberOfLines=0, 
		$fileNameError, $db);
	}

	/**
	 * Processa e insere os dados de bairro. Se executado com sucesso retorna TRUE.
	 * Caso contrario, retorna FALSE
	 */
	public static function processNeighborhood($commonPath, &$fileNameError, &$db=null)
	{
		$nbhFolder = $commonPath .'/'. Constants::FOLDER_TYPE_3;
		return Parser::parseNeighborhood($nbhFolder, FileHelper::getFilesFromDir($nbhFolder),
			$numberOfLines=0, $fileNameError, $db);
	}

	/**
	 * Processa e insere os dados de Logradouro e popula tamb�m a tabela de address_type(Rua, Avenida, etc). 
	 * Se executado com sucesso retorna TRUE.
	 * Caso contrario, retorna FALSE
	 */
	public static function processAddress($commonPath, &$fileNameError, &$db=null)
	{
		$addressFolder = $commonPath .'/'. Constants::FOLDER_TYPE_4;
		return Parser::parseAddress($addressFolder, FileHelper::getFilesFromDir($addressFolder), 
				$numberOfLines=0, $fName=NULL, $db);
	}

	/**
	 * Processa e insere os dados de Logradouro_apelido. Se executado com sucesso retorna TRUE.
	 * Caso contrario, retorna FALSE
	 */
	public static function processAddressNick($commonPath, &$fileNameError, &$db=null)
	{
		$nickFolder = $commonPath .'/'. Constants::FOLDER_TYPE_5;
		return Parser::parseAddressNickname($nickFolder, FileHelper::getFilesFromDir($nickFolder) , $numberOfLines=0,
		$fileNameError, $db);
	}
}