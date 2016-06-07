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
 * Jefferson Barros Lima  - W3S		   				22/02/2008	                       Create file 
 * 
 */

define('PATH_ROOT', 							Zend_Registry::get(APP_PATH).Constants::ROOT_FOLDER_IMPORT);
define('RETURN_FOLDER_KEY', 					'folder_status');
define('RETURN_FILE_KEY',	 					'file_name');
define('RETURN_PARSE_KEY', 						'parser_status');
define('RETURN_NUMBER_OF_PARSED_LINES_KEY', 	'parsed_lines');

require_once 'Parser.php'; 

class FileHelper
{
	const ZIP_KEY = 'zip';

	public static function verifyFolderImportPermission()
	{
		return FileHelper::verifyFolderPermission(PATH_ROOT);
	}

	public static function verifyFolderPermission($path)
	{
		if(!is_dir($path . "/i"))
		{
			if(mkdir($path . "/i") !== FALSE)
			{
				rmdir($path . "/i");
				return TRUE;
			}
		}
		else
		{
			if(rmdir($path . "/i") !== FALSE)
			{
				return TRUE;
			}
		}
		return FALSE;
	} 
	
	public static function verifyFolderStatusForSchool($path, $numberOfLines=NULL)
	{
		$arrReturn[RETURN_FOLDER_KEY] 					= TRUE;
		$arrReturn[RETURN_FILE_KEY] 					= NULL;
		$arrReturn[RETURN_PARSE_KEY] 					= TRUE;
		$arrReturn[RETURN_NUMBER_OF_PARSED_LINES_KEY] 	= 0;

		if(is_dir($path))
		{
			
			self::extractDir($path, TRUE);
			//abre o diretório
			$folder = opendir($path);
			
			// Lista os arquivos do diretório
			while(($filename = readdir($folder)) !== FALSE)
			{
				$fullPath = $path.'/'.$filename;
				
				// Desconsidera . e ..
				if($filename != '.' && $filename != '..')
				{					
					if($numberOfLines == NULL)
						$numberOfLines = 0;
					$res = FALSE;
					$filename = NULL;
					$res = Parser::parseSchool($path, self::getFilesFromDir($path), $numberOfLines, $filename);
					$arrReturn[RETURN_NUMBER_OF_PARSED_LINES_KEY] = count($res);

					if($res === FALSE || $res === NULL)
					{
						$arrReturn[RETURN_PARSER_KEY] = FALSE; //falha no parser
						$arrReturn[RETURN_FILE_KEY] = $filename; // SETA O NOME DO ARQUIVO
						return $arrReturn;
					}
				}
			}
			closedir($folder); 
		}
		else
		{
			//$arrReturn[RETURN_PARSER_KEY] = FALSE;			
			if(mkdir($path) !== FALSE)
			{
				;
			}
			else
			{
				$arrReturn[RETURN_FOLDER_KEY] = FALSE;
			}
		}
		return $arrReturn;
	}	
	
	public static function verifyFolderStatusForSingleRegister($path, $numberOfLines=NULL)
	{
		$arrReturn[RETURN_FOLDER_KEY] 					= TRUE;
		$arrReturn[RETURN_FILE_KEY] 					= NULL;
		$arrReturn[RETURN_PARSE_KEY] 					= TRUE;
		$arrReturn[RETURN_NUMBER_OF_PARSED_LINES_KEY] 	= 0;

		if(is_dir($path))
		{
			self::extractDir($path, TRUE);
			//abre o diretório
			$folder = opendir($path);
			
			// Lista os arquivos do diretório
			while(($filename = readdir($folder)) !== FALSE)
			{
				$fullPath = $path.'/'.$filename;
				
				// Desconsidera . e ..
				if($filename != '.' && $filename != '..' && !is_dir($fullPath))
				{
					if($numberOfLines == NULL)
						$numberOfLines = 0;
					
					$res = FALSE;
					$filename = NULL;
					Logger::loggerImport('Iniciando verificação...');
					$res = Parser::parseSingleRegister($path, self::getFilesFromDir($path), $numberOfLines, $filename);
					$arrReturn[RETURN_NUMBER_OF_PARSED_LINES_KEY] = count($res);

					if($res === FALSE || $res === NULL)
					{
						$arrReturn[RETURN_PARSER_KEY] = FALSE; //falha no parser
						$arrReturn[RETURN_FILE_KEY] = $filename; // SETA O NOME DO ARQUIVO
						return $arrReturn;
					}
				}
			}
			closedir($folder); 
		}
		else
		{
			//$arrReturn[RETURN_PARSER_KEY] = FALSE;
			if(mkdir($path) !== FALSE)
			{
				;
			}
			else
			{
				$arrReturn[RETURN_FOLDER_KEY] = FALSE;
			}
		}
		return $arrReturn;
	}

	public static function saveUploadForSingleRegister($file, &$folder=null)
	{
		if(!empty($file))
		{
			
			if(!empty($folder) && !self::startsWith($folder, '/')) 
				$folder = '/'.$folder;
			
			$directory = PATH_ROOT.$folder;
			
			/* Cria diretorio caso não exista */
			mkdir($directory, 0777);
			
	        // Caminho de gravação do arquivo no servidor
	        $newFile = $directory . '/' . $file[Constants::F_NAME];
			
	        // Grava o arquivo no disco
	        $upload = move_uploaded_file($file[Constants::F_TMP_NAME], $newFile);
	        
			if($upload)
			{
				Logger::loggerOperation('O upload do arquivo foi realizado com sucesso: '.$newFile);
				Logger::loggerImport('O upload do arquivo foi realizado com sucesso: '.$newFile);
			}
			else
			{
				Logger::loggerOperation('O upload do arquivo não foi realizado.');
				Logger::loggerImport('O upload do arquivo não foi realizado.');
			}
			
			return $upload; 
		}
        return NULL;
	}
	
	public static function saveUploadForSchool($file, &$folder=null)
	{
		if(!empty($file))
		{
			
			if(!empty($folder) && !self::startsWith($folder, '/')) 
				$folder = '/'.$folder;
			
			$directory = PATH_ROOT.$folder;
			
			/* Cria diretorio caso não exista */
			mkdir($directory, 0777);
			
	        // Caminho de gravação do arquivo no servidor
	        $newFile = $directory . '/' . $file[Constants::F_NAME];
			
	        // Grava o arquivo no disco
	        $upload = move_uploaded_file($file[Constants::F_TMP_NAME], $newFile);
	        
			if($upload)
			{
				Logger::loggerOperation('O upload do arquivo foi realizado com sucesso: '.$newFile);
				Logger::loggerImportSchool('O upload do arquivo foi realizado com sucesso: '.$newFile);
			}
			else
			{
				Logger::loggerOperation('O upload do arquivo não foi realizado.');
				Logger::loggerImportSchool('O upload do arquivo foi realizado com sucesso: '.$newFile);
			}
			
			return $upload; 
		}
        return NULL;
	}
	
	/**
	 * Verifica a existência dos arquivos de importação e se são válidos
	 */
	public static function verifyFolderStatus($path, $parseType, $numberOfLines=NULL)
	{
		$arrReturn[RETURN_FOLDER_KEY] 					= TRUE;
		$arrReturn[RETURN_FILE_KEY] 					= NULL;
		$arrReturn[RETURN_PARSE_KEY] 					= TRUE;
		$arrReturn[RETURN_NUMBER_OF_PARSED_LINES_KEY] 	= 0;
		
		if(!is_dir(PATH_ROOT))
		{
			$res = mkdir(PATH_ROOT);
			if(!$res)
			{				
				echo "Sem permissão de escrita para o diretório ".PATH_ROOT;
				Logger::loggerOperation("Sem permissão de escrita para o diretório ".PATH_ROOT);
				return;
			}
		}
		if(!is_dir(PATH_ROOT.Constants::FOLDER_ROOT_ZIPCODE))
		{
			$res = mkdir(PATH_ROOT.Constants::FOLDER_ROOT_ZIPCODE);
			if(!$res)
			{
				echo "Sem permissão de escrita para o diretório ".PATH_ROOT.Constants::FOLDER_ROOT_ZIPCODE;
				Logger::loggerOperation("Sem permissão de escrita para o diretório ".PATH_ROOT.Constants::FOLDER_ROOT_ZIPCODE);
				return;
			}
		}

		if(is_dir($path))
		{
			self::extractDir($path, TRUE);
			//abre o diretório
			
					$j = 0;
			if($numberOfLines == NULL)
				$numberOfLines = 0;

			$res = FALSE;
			
			// Armazena o nome do arquivo em caso de problemas
			$filename = NULL;
			
			// Faz o parse dos arquivos
			if($parseType == Constants::TYPE_1)
			{
				$res = Parser::parseUf($path, self::getFilesFromDir($path), $numberOfLines, $filename);
			}
			else if($parseType == Constants::TYPE_2)
			{
				$res = Parser::parseCity($path, self::getFilesFromDir($path), $numberOfLines, $filename);
			}
			else if($parseType == Constants::TYPE_3)
			{
				$res = Parser::parseNeighborhood($path, self::getFilesFromDir($path), $numberOfLines, $filename);
			}
			else if($parseType == Constants::TYPE_4)
			{
				$res = Parser::parseAddress($path, self::getFilesFromDir($path), $numberOfLines, $filename);
			}
			else if($parseType == Constants::TYPE_5)
			{
				$res = Parser::parseAddressNickname($path, self::getFilesFromDir($path), $numberOfLines, $filename);
			}

			$arrReturn[RETURN_NUMBER_OF_PARSED_LINES_KEY] = count($res);
			if($res === FALSE || $res === NULL)
			{
				$arrReturn[RETURN_PARSER_KEY] = FALSE; //falha no parser
				$arrReturn[RETURN_FILE_KEY] = $filename; // SETA O NOME DO ARQUIVO
				return $arrReturn;
			}

			if($numberOfLines != 0) $j++;
		}
		else
		{
			//$arrReturn[RETURN_PARSER_KEY] = FALSE;
			if(mkdir($path) !== FALSE)
			{
				;
			}
			else
			{
				$arrReturn[RETURN_FOLDER_KEY] = FALSE;
			}
		}
		return $arrReturn;
	}
	
	/**
	 * Lista todos os arquvos do diretório e extrai se for do tipo ZIP
	 * @param String $path - Caminho para o diretório a ser listado
	 * @param Boolean $delete - Flag que sinaliza se o arquivo ZIP deve ser apagado depois de extraído 
	 */
	public static function extractDir($path, $delete=FALSE)
	{
		
		if(is_dir($path))
		{
			//abre o diretório
			$folder = opendir($path);
			// Lista os arquivos do diretório
			while(($filename = readdir($folder)) !== FALSE)
			{
				if($filename != '.' && $filename != '..')
				{					
					
					$extracted = self::extractFile($path, $filename);
					if($extracted === TRUE && $delete === TRUE)
					{
						unlink($path.'/'.$filename);
					}
				}
			}
			
		}
	}
	/**
	 * Extrai um arquivo
	 * @param String $path - Caminho para o arquivo a ser extraído
	 * @param String $fileName - Nome do arquivo
	 * @return FALSE/TRUE Retorna true caso o arquivo seja extraído com sucesso
	 */
	public static function extractFile($path, $fileName)
	{
		if(is_dir($path) && $fileName !== NULL)
		{
			$fullPath = $path.'/'.$fileName;
			
			if(self::ZIP_KEY == self::getFileType($fileName))
			{				
				$zip = new ZipArchive();
				$res = $zip->open($fullPath);
				if($res === TRUE)
				{
					$zip->extractTo($path);
					$zip->close();
					return TRUE;	
				}
				else
				{
					$zip->close();
					return FALSE;
				}
			}
		}
		return FALSE;
	}
	
	public static function getFileType($fileName)
	{
		if($fileName !== NULL)
		{
			$ext1 = split('\.',$fileName);
			$ext2 = substr($fileName, strlen($fileName)-3, strlen($fileName));
			// Se extensões obtidas de forma diferentes forem iguais, retorna a extensão
			($ext1[1] == $ext2)? $ext1 = $ext1[1] : $ext1 = NULL;
			
			return $ext1;
		}
		return NULL;
	}

	public static function saveUpload($file, $folder = null)
	{
			
		if(!empty($file))
		{
//			if(!empty($folder) && !self::startsWith($folder, '/')) 
//				$folder = '/'.$folder;
				
			$directory = PATH_ROOT . Constants::FOLDER_ROOT_ZIPCODE.$folder;
			
			if(!is_dir(PATH_ROOT))
			{
				$res = mkdir(PATH_ROOT);
				if(!$res)
				{
					Logger::loggerImportAddress("Sem permissão de escrita".PATH_ROOT);
					echo "Sem permissão de escrita".PATH_ROOT;
					Logger::loggerOperation("Sem permissão de escrita".PATH_ROOT);
					return;
				}
				if(!is_dir(PATH_ROOT.Constants::FOLDER_ROOT_ZIPCODE))
				{
					$res = mkdir(PATH_ROOT.Constants::FOLDER_ROOT_ZIPCODE);
					if(!$res)
					{
						echo "Sem permissão de escrita para o diretório ".PATH_ROOT.Constants::FOLDER_ROOT_ZIPCODE;
						Logger::loggerImportAddress("Sem permissão de escrita para o diretório ".PATH_ROOT.Constants::FOLDER_ROOT_ZIPCODE);
						Logger::loggerOperation("Sem permissão de escrita para o diretório ".PATH_ROOT.Constants::FOLDER_ROOT_ZIPCODE);
						return;
					}
				}
			}
			
			/* Cria diretorio caso não exista */
			if($folder != null && $folder != "" && !is_dir($directory))
			{
				$res = mkdir($directory, 0777);
				if(!$res)
				{
					echo "Sem permissão de escrita para o diretório ".$directory;
					Logger::loggerOperation("Sem permissão de escrita para o diretório ".$directory);
					Logger::loggerImportAddress("Sem permissão de escrita para o diretório ".$directory);
					return;
				}
			}

	        // Caminho de gravação do arquivo no servidor
	        $newFile = $directory . '/' . $file[Constants::F_NAME];

	        // Grava o arquivo no disco
	        $upload = move_uploaded_file($file[Constants::F_TMP_NAME], $newFile);
			if($upload !== false)
			{
				Logger::loggerOperation('O upload do arquivo foi realizado com sucesso: '.$newFile);
				Logger::loggerImportAddress('O upload do arquivo foi realizado com sucesso: '.$newFile);
			}
			else
			{
				Logger::loggerOperation('O upload do arquivo não foi realizado.');
				Logger::loggerImportAddress('O upload do arquivo não foi realizado.');
			}

			return $upload; 
		}
        return NULL;
	}

	/**
	 * Retorna um Array com nome dos arquivos de um diretório
	 */
	public static function getFilesFromDir($path)
	{
		if(is_dir($path))
		{
			$filesArray = NULL;
			//abre o diretório
			$folder = opendir($path);
			
			// Lista os arquivos do diretório
			while(($fileName = readdir($folder)) !== FALSE)
			{
				// Desconsidera . e ..
				if($fileName != '.' && $fileName != '..')
				{
					$filesArray[] = $fileName;
				}
			}
			closedir($folder);
			return $filesArray;
		}
		return NULL;
	}

	public static function startsWith($main, $start) 
	{
		return (substr($main, 0, strlen($start)) == $start);
	}
	
	public static function removeSchoolFiles()
	{
		$path = PATH_ROOT.Constants::FOLDER_ROOT_SCHOOL;
		if(is_dir($path))
		{
			//abre o diretório
			$folder = opendir($path);
			// Lista os arquivos do diretório
			while(($filename = readdir($folder)) !== FALSE)
			{
				if($filename != '.' && $filename != '..')
				{	
					unlink($path.'/'.$filename);
				}
			}			
		}
	}
}
