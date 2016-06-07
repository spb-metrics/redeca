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
 * Jefferson Barros Lima  - W3S		    			05/03/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class SchoolBusiness extends BasicBusiness
{
	/**
	 * Persiste informações no DB 
	 * @param Array $school - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($school, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{	
			$obj = new School();
			if($school[SCH_ID_SCHOOL] == false)
			{
				$id = $obj->insert($school);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_SCHOOL .' [id='.$id.']');
			}
			else
			{
				$where = $obj->getAdapter()->quoteInto(SCH_ID_SCHOOL.' = ?', $school[SCH_ID_SCHOOL]);
				$rowsAffected = $obj->update($school, $where);
				$id = $school[SCH_ID_SCHOOL];
				
				Logger::loggerOperation('Registro modificado na tabela '.TBL_SCHOOL.
					' ['.SCH_ID_SCHOOL.'='.$school[SCH_ID_SCHOOL].']');
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{	
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->school->save->fail, E_USER_ERROR);
		}
	}
	/**
	 * Retorna m Array de objetos School dado o nome
	 */
	public static function findByName($schoolName)
	{
		try
		{
			$obj = new School();
			$rows = null;
			if(!is_null($schoolName))
			{
				$where 	= $obj->getAdapter()->quoteInto(SCH_NAME.' = ?', $schoolName);
        		$rows 	= $obj->fetchAll($where);
			}
			return $rows;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->school->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Retorna o objeto School dado o código INEP
	 */
	public static function findByInepCode($inepCode)
	{
		try
		{
			$obj = new School();
			$rows = null;
			if(!is_null($inepCode))
			{
				$where 	= $obj->getAdapter()->quoteInto(SCH_INEP.' = ?', $inepCode);
        		$rows 	= $obj->fetchAll($where);
			}
			return $rows;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->school->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Retorn o objeto School dado o codigo INEP e o Nome
	 */
	public static function findByInepCodeAndName($inepCode, $name)
	{
		try
		{
			$obj = new School();
			$rows = null;
			if(!is_null($inepCode) && !is_null($name))
			{
				$where[] 	= $obj->getAdapter()->quoteInto(SCH_INEP.' = ?', $inepCode);
				$where[] 	= $obj->getAdapter()->quoteInto(SCH_NAME.' = ?', $name);
        		$rows 	= $obj->fetchAll($where);
			}
			return $rows;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->school->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os registros da tabela "School" 
	 * 
	 */
	public static function load()
	{	
		Zend_Loader::loadClass('School');
		
		$table	= new School();
	
		try
		{	
			return $table->fetchAll(null, SCH_NAME);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->school->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os registros da tabela "SchoolType" 
	 * 
	 */
	public static function loadAllSchool()
	{	
		Zend_Loader::loadClass('SchoolType');
		
		$table	= new SchoolType();
	
		try
		{	
			$where = $table->getAdapter()->quoteInto(SCT_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where, SCT_SCHOOL_TYPE);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->typeschool->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadSchool($id)
	{	
		Zend_Loader::loadClass('School');
		
		$table	= new School();
	
		try
		{	
			$where = $table->getAdapter()->quoteInto(SCH_ID_SCHOOL.' in (?)', $id);
			return $table->fetchRow($where);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->typeschool->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Persiste as informações editadas pelo usuário referentes a educação 
	 */
	public static function saveEducation($bean, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$transaction = true;
		}
		
		try 
		{
			if(!empty($bean))
			{		
				$verify = self::existPerson($bean[LIT_ID_PERSON], $db);
				
				if($verify)
				{	
					self::updateStatus($bean[LIT_ID_PERSON], $db);	
				}
								
				$insertedLevelInstructionId = self::saveLevelInstruction($bean, $db);
				
				if($insertedLevelInstructionId != null)
				{	
					//cria objeto do tipo histórico de alterações
					$chLevelInstruction = array();
					$chLevelInstruction[PCH_ID_REFERENCE_FOREIGN]	= $insertedLevelInstructionId;
					$chLevelInstruction[PCH_ID_USER]				= UserLogged::getUserId();
					$chLevelInstruction[PCH_ID_PERSON]				= $bean[LIT_ID_PERSON];
					$chLevelInstruction[PCH_ID_RESOURCE]			= parent::loadIdResource($bean[NAME_CONTROLLER]);
					$chLevelInstruction[PCH_DATE_OPERATION]			= date("Y-m-d");
					$chLevelInstruction[PCH_TABLE_NAME]				= TBL_LEVEL_INSTRUCTION;

					//persiste na tabela histórico de alterações					
					HistoryBusiness::save($chLevelInstruction, $db);
					
					$insertedRegistrationId = self::saveRegistration($bean, $insertedLevelInstructionId, $db);
					
					if($insertedRegistrationId != null)
					{
						//cria objeto do tipo histórico de alterações
						$chRegistration = array();
						$chRegistration[PCH_ID_REFERENCE_FOREIGN]	= $insertedRegistrationId;
						$chRegistration[PCH_ID_USER]				= $chLevelInstruction[PCH_ID_USER];
						$chRegistration[PCH_ID_PERSON]				= $bean[LIT_ID_PERSON];
						$chRegistration[PCH_ID_RESOURCE]			= $chLevelInstruction[PCH_ID_RESOURCE];
						$chRegistration[PCH_DATE_OPERATION]			= date("Y-m-d");
						$chRegistration[PCH_TABLE_NAME]				= TBL_REGISTRATION;
	
						//persiste na tabela "histórico de alterações"					
						HistoryBusiness::save($chRegistration, $db);
					}	
				}
			}
			
			if($transaction)
			{
				$db -> commit();
			}
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
		}
	}
	
	/**
	 * Verifica se a pessoa já tem cadastro referente as informações escolares
	 */
	public static function existPerson($idPerson, &$db)
	{
		try
		{
			$obj = new LevelInstruction();
		
			//verifica se na tabela nível de instrução existe registro referente ao "idPerson"  			
			$where = $obj->getAdapter()->quoteInto(LIT_ID_PERSON.' = ?', $idPerson);
			$row = count($obj->fetchAll($where));
			
			if($row > 0)
			{
				return true;
			}
			return false;	
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->levelInstruction->load->failDB, E_USER_ERROR);		
		}
	}
	
	/**
	 * Atualiza o campo "status" das tabelas "Nível de Instrução - edu_level_registration" e "Matrícula - edu_registration"
	 */
	public static function updateStatus($idPerson, &$db)
	{	
		$obj = new LevelInstruction();
		
		//busca na tabela nível de instrução a linha referente ao "idPerson" cujo "status" seja null 			
		$where[] = $obj->getAdapter()->quoteInto(LIT_ID_PERSON.' = ?', $idPerson);
		$where[] = $obj->getAdapter()->quoteInto(LIT_STATUS.' is null', null);
		$rows = $obj->fetchAll($where);	
			
		//se busca retornar mais de uma linha, o campo "status" da tabela "LevelInstruction" e "Registration" não serão atualizados.
		//uma exception é gerada e um "log" será escrito informando o ocorrido
		try
		{
			if($rows == 1)
			{	
				$rowLI = $rows->current();
				
				if($rowLI->{LIT_ID_PERSON} != null)
				{	
					Zend_Loader::loadClass('Constants');
					$data = array
					(
						'status' => Constants::HISTORY
					);
					
					try
					{
						$table = new LevelInstruction();
						
						$table->update($data, $where);
						
						Logger::loggerOperation('Status do nível de instrução da pessoa '.$rowLI->{LIT_ID_PERSON}.' atualizado com sucesso');
						
						try
						{
							$objReg = new Registration();
							
							$whereReg[] = $objReg->getAdapter()->quoteInto(REG_ID_LEVEL_INSTRUCTION.' = ?', $rowLI->{LIT_ID_LEVEL_INSTRUCTION});
							$whereReg[] = $objReg->getAdapter()->quoteInto(REG_STATUS.' is null', null);	
							
							$tableReg = new Registration();
							$tableReg->update($data, $whereReg);
							
							Logger::loggerOperation('Status da matrícula com nível de instruçao ='.$rowLI->{LIT_ID_LEVEL_INSTRUCTION}.' atualizado com sucesso');
						}
						catch(Zend_Exception $e)
						{
							Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
							trigger_error(parent::getLabelResources()->registration->error->failDB, E_USER_ERROR);		
						}
					}
					catch(Zend_Exception $e)
					{
						Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
						trigger_error(parent::getLabelResources()->levelInstruction->error->failDB, E_USER_ERROR);		
					}
				}
			}
			else
			{
				throw new Exception('Existe mais de um registro da pessoa '.$rows->{LIT_ID_PERSON}.' em nível de instrução para ser atualizado');
			}		
		}
		catch(Exception $e)
		{
			Logger::loggerOperation($e->getMessage());
			trigger_error(parent::getLabelResources()->updateLevelInstruction->error->impossible, E_USER_ERROR);			
		}	
	}
	
	/**
	 * Persiste as informações de matricula
	 */
	public static function saveLevelInstruction($bean, &$db)
	{
		$levelInstruction = array();
		$levelInstruction[LIT_ID_DEGREE]			= $bean[LIT_ID_DEGREE];
		$levelInstruction[LIT_ID_PERSON]			= $bean[LIT_ID_PERSON];
		$levelInstruction[LIT_LAST_YEAR_STUDIED]	= $bean[LIT_LAST_YEAR_STUDIED];
		$levelInstruction[LIT_LAST_MONTH_STUDIED]	= $bean[LIT_LAST_MONTH_STUDIED];
		$levelInstruction[LIT_STATUS]		 		= null;
		$levelInstruction[LIT_DATE_COLLECTED] 		= date("Y-m-d");
		
		try
		{
			$levelInstructionObj = new LevelInstruction();
			$insertedLevelInstructionId = $levelInstructionObj->insert($levelInstruction);
			Logger::loggerOperation('Novo nível escolar adicionado. [id='.$insertedLevelInstructionId.']');
			
			return $insertedLevelInstructionId;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->levelInstruction->error->failDB, E_USER_ERROR);		
		}
	}
	
	/**
	 * Persiste as informações de escola
	 */
	public static function saveRegistration($bean, $idLevelInstruction, $idSchool, &$db)
	{
		$registration = array();
		
		$registration[REG_ID_PERIOD]			= $bean[PTY_ID_PERIOD];
		$registration[REG_ID_SCHOOL_YEAR]		= $bean[SYT_ID_SCHOOL_YEAR];
		$registration[REG_ID_SCHOOL]			= $bean[SCH_ID_SCHOOL];
		$registration[REG_ID_LEVEL_INSTRUCTION]	= $idLevelInstruction;
		$registration[REG_STATUS]				= null;
		
		try
		{	
			$registrationObj = new Registration();
			
			$insertedRegistrationId = $registrationObj->insert($registration);
			Logger::loggerOperation('Nova escola adicionada. [id='.$insertedRegistrationId.']');
			
			return $insertedRegistrationId;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->registration->error->failDB, E_USER_ERROR);		
		}
	}
}