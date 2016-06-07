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
 * Fabricio Meireles Monteiro  - W3S		    	14/03/2008	                       Create file 
 * 
 */

	require_once('BasicBusiness.php');
	
	class HealthBusiness extends BasicBusiness
	{
		/**
		 * Carrega do banco as informações de saúde conforme "id" e/ou "status" da pessoa  
		 */
		public static function loadHealthByPerson($idPerson)
		{	
			try
			{	
				$type = new Health();
				
				if(!empty($idPerson))
				{	
					
					//busca na tabela saúde a linha referente ao "idPerson" e "status" informados 			
					$where[] = $type->getAdapter()->quoteInto(HLT_ID_PERSON.' = ?', $idPerson);
					$where[] = $type->getAdapter()->quoteInto(HLT_STATUS.' is null', null);
					
					$row = $type->fetchAll($where);
					
					return $row->current();
				}
				
				Logger::loggerOperation('Nenhum registro de saúde encontrado para '.$idPerson.' = '.implode(',' ,$idPerson));
				return ;
			}
			catch(Zend_Exception $e)
			{	
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->health->load->fail, E_USER_ERROR);
			}
		}
		
				
		/**
		 * Carrega do banco as informações de gestação conforme "id" e/ou "status" da pessoa  
		 */
		public static function loadPregnancyByPerson($idPerson)
		{	
			try
			{	
				$type = new Pregnancy();
				
				if(!empty($idPerson))
				{	
					
					//busca na tabela saúde a linha referente ao "idPerson" e "status" informados 			
					$where[] = $type->getAdapter()->quoteInto(PRG_ID_PERSON.' = ?', $idPerson);
					$where[] = $type->getAdapter()->quoteInto(PRG_STATUS.' is null', null);
					
					$row = $type->fetchAll($where);
					
					return $row->current();
				}
				
				Logger::loggerOperation('Nenhum registro de saúde encontrado para '.$idPerson.' = '.implode(',' ,$idPerson));
				return ;
			}
			catch(Zend_Exception $e)
			{	
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->pregnancy->load->fail, E_USER_ERROR);
			}
		}
		
		
		public static function loadAllHealthTypes()
		{	
			Zend_Loader::loadClass('FrameworkHealthType');
			
			$table	= new FrameworkHealthType();
		
			try
			{	
				$where = $table->getAdapter()->quoteInto(FHT_STATUS.' not in (?)', Constants::DISABLE);
				return $table->fetchAll($where, FHT_FRAMEWORK_HEALTH);
			}
			catch(Zend_Exception $e)
			{
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeframeworkhealth->load->fail, E_USER_ERROR);
			}
		}
		
		public static function loadHealthTypes($id)
		{	
			Zend_Loader::loadClass('FrameworkHealthType');
			
			$table	= new FrameworkHealthType();
		
			try
			{	
				$where[] = $table->getAdapter()->quoteInto(FHT_ID_FRAMEWORK_HEALTH.' in (?)', $id);
				$where[] = $table->getAdapter()->quoteInto(FHT_STATUS.' not in (?)', Constants::DISABLE);
				return $table->fetchAll($where, FHT_FRAMEWORK_HEALTH);
			}
			catch(Zend_Exception $e)
			{
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeframeworkhealth->load->fail, E_USER_ERROR);
			}
		}
		
		/**
		 * Persiste as informações editadas pelo usuário referentes a saúde 
		 */
		public static function save($bean, $nameController, &$db=null)
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
					$verifyHealth = self::existPersonInHealth($bean[ID_PERSON], $db);
					
					if($verifyHealth)
					{	
						self::updateStatusHealth($bean[ID_PERSON], $db);
					}				
					
					$insertedHealthId = self::saveHealth($bean, $db);
				
					if($insertedHealthId != null)
					{												
						//cria objeto do tipo histórico de alterações
						$chHealth = array();
						$chHealth[PCH_ID_REFERENCE_FOREIGN]	= $insertedHealthId;
						$chHealth[PCH_ID_USER]				= UserLogged::getUserId();
						$chHealth[PCH_ID_PERSON]			= $bean[ID_PERSON];
						$chHealth[PCH_ID_RESOURCE]			= parent::loadIdResource($nameController);
						$chHealth[PCH_DATE_OPERATION]		= date("Y-m-d");
						$chHealth[PCH_TABLE_NAME]			= TBL_HEALTH;
	
						//persiste na tabela histórico de alterações					
						HistoryBusiness::save($chHealth, $db);
						
						self::saveFrameworkHealth($bean, $insertedHealthId, $db);
						
						$verifyPregnancy = self::existPersonInPregnancy($bean[ID_PERSON], $db);
						if($verifyPregnancy)
						{	
							self::updateStatusPregnancy($bean[ID_PERSON], $db);
						}
						
						if($bean[PREGNANCY] == 1)
						{
							$insertedPregnancyId = self::savePregnancy($bean, $db);
							
							if($insertedPregnancyId != null)
							{
								//cria objeto do tipo histórico de alterações
								$chPregnancy = array();
								$chPregnancy[PCH_ID_REFERENCE_FOREIGN]	= $insertedPregnancyId;
								$chPregnancy[PCH_ID_USER]				= $chHealth[PCH_ID_USER];
								$chPregnancy[PCH_ID_PERSON]				= $bean[ID_PERSON];
								$chPregnancy[PCH_ID_RESOURCE]			= $chHealth[PCH_ID_RESOURCE];
								$chPregnancy[PCH_DATE_OPERATION]		= date("Y-m-d");
								$chPregnancy[PCH_TABLE_NAME]			= TBL_PREGNANCY;
			
								//persiste na tabela histórico de alterações					
								HistoryBusiness::save($chPregnancy, $db);
							}
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
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->health->fail, E_USER_ERROR);
			}
		}
	
		/**
		 * Verifica se a pessoa já tem cadastro referente a saúde
		 */
		public static function existPersonInHealth($idPerson, &$db)
		{
			try
			{
				$obj = new Health();
			
				//verifica se na tabela gestação existe registro referente ao "idPerson"  			
				$where = $obj->getAdapter()->quoteInto(HLT_ID_PERSON.' = ?', $idPerson);
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
				trigger_error(parent::getLabelResources()->health->load->fail, E_USER_ERROR);		
			}
		}
	
		/**
		 * Verifica se a pessoa já tem cadastro referente a gestação
		 */
		public static function existPersonInPregnancy($idPerson, &$db)
		{
			try
			{
				$obj = new Pregnancy();
			
				//verifica se na tabela gestação existe registro referente ao "idPerson"  			
				$where = $obj->getAdapter()->quoteInto(PRG_ID_PERSON.' = ?', $idPerson);
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
				trigger_error(parent::getLabelResources()->pregnancy->error->failDB, E_USER_ERROR);		
			}
		}
		
		/**
		 * Atualiza o campo "status" da tabela saúde
		 */
		public static function updateStatusHealth($idPerson, &$db)
		{	
			$obj = new Health();
			
			//busca na tabela nível de instrução a linha referente ao "idPerson" cujo "status" seja null 			
			$where[] = $obj->getAdapter()->quoteInto(HLT_ID_PERSON.' = ?', $idPerson);
			$where[] = $obj->getAdapter()->quoteInto(HLT_STATUS.' is null', null);
			$rows = $obj->fetchAll($where);	
			
			$rowHLT = $rows->current();
				
			//se busca retornar mais de uma linha, o campo "status" da tabela "Health" não será atualizado.
			//uma exception é gerada e um "log" será escrito informando o ocorrido
			try
			{
				if($rows == 1)
				{	
					if($rowHLT->{HLT_ID_PERSON} != null)
					{	
						Zend_Loader::loadClass('Constants');
						$data = array
						(
							'status' => Constants::HISTORY
						);
						
						try
						{
							$table = new Health();
							$table->update($data, $where);
							
							Logger::loggerOperation('Saúde da pessoa '.$rowHLT->{HLT_ID_PERSON}.' atualizado com sucesso');
						}
						catch(Zend_Exception $e)
						{
							Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
							trigger_error(parent::getLabelResources()->health->error->failDB, E_USER_ERROR);		
						}
					}
				}
				else
				{
					throw new Exception('Existe mais de um registro da pessoa '.$rowHLT->{HLT_ID_PERSON}.' em saúde para ser atualizado');
				}		
			}	
			catch(Exception $e)
			{
				Logger::loggerOperation($e->getMessage());
				trigger_error(parent::getLabelResources()->updateHealth->error->impossible, E_USER_ERROR);			
			}
		}
		
		/**
		 * Atualiza o campo "status" da tabela gravidez
		 */
		public static function updateStatusPregnancy($idPerson, &$db)
		{	
			$obj = new Pregnancy();
			
			//busca na tabela nível de instrução a linha referente ao "idPerson" cujo "status" seja null 			
			$where[] = $obj->getAdapter()->quoteInto(PRG_ID_PERSON.' = ?', $idPerson);
			$where[] = $obj->getAdapter()->quoteInto(PRG_STATUS.' is null', null);
			$rows = $obj->fetchAll($where);	
			
			$rowPRG = $rows->current();
				
			//se busca retornar mais de uma linha, o campo "status" da tabela "Health" não será atualizado.
			//uma exception é gerada e um "log" será escrito informando o ocorrido
			try
			{
				if($rows == 1)
				{	
					if($rowPRG->{PRG_ID_PERSON} != null)
					{	
						Zend_Loader::loadClass('Constants');
						$data = array
						(
							'status' => Constants::HISTORY
						);
						
						try
						{
							$table = new Pregnancy();				
							$where = null;
							$where = $table->getAdapter()->quoteInto(PRG_ID_PERSON. ' = ?', $rowPRG->{PRG_ID_PERSON});
							$table->update($data, $where);
							
							Logger::loggerOperation('Registro de gravizez da pessoa '.$rowPRG->{HLT_ID_PERSON}.' atualizado com sucesso');
						}
						catch(Zend_Exception $e)
						{
							Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
							trigger_error(parent::getLabelResources()->health->error->failDB, E_USER_ERROR);		
						}
					}
				}
				else
				{
					throw new Exception('Existe mais de um registro relacionado a gravidez da pessoa '.$rowHLT->{HLT_ID_PERSON}.' para ser atualizado');
				}		
			}	
			catch(Exception $e)
			{
				Logger::loggerOperation($e->getMessage());
				trigger_error(parent::getLabelResources()->updatePregnancy->error->impossible, E_USER_ERROR);			
			}
		}
		
		
		/**
		 * Persiste as informações de saúde
		 */
		public static function saveHealth($bean, &$db)
		{
			$health = array();
			$health[HLT_ID_PERSON]		= $bean[ID_PERSON];
			$health[HLT_DRUG_USER]		= $bean[USER_DRUG];
			$health[HLT_VACCINE]		= $bean[VACCINE];
			$health[HLT_VACCINE_TO_DATE]= date("Y-m-d");
			$health[HLT_HEALTH_PLAN]	= $bean[NAME_PLAN];
			$health[HLT_STATUS]		 	= null;
			
			try
			{
				$healthObj = new Health();
				$id = $healthObj->insert($health);
				Logger::loggerOperation('Novo registro de saúde adicionado. [id ='.$id.']');
				
				return $id;
			}
			catch(Zend_Exception $e)
			{
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->health->error->failDB, E_USER_ERROR);		
			}
		}
		
		/**
		 * Persiste as informações de quadro se saúde
		 */
		public static function saveFrameworkHealth($bean, $idHealth, &$db)
		{
			if($idHealth != null)
			{
				$frameworkHealth = array();
				$frameworkHealth[FHL_ID_HEALTH] = $idHealth;
				
				try
				{
					foreach($bean[OBJECTS_HEALTH] as $objFrameworkHealth)
					{	
						$frameworkHealth[FHL_ID_FRAMEWORK_HEALTH]			= $objFrameworkHealth[FHL_ID_FRAMEWORK_HEALTH];
						$frameworkHealth[FHL_FRAMEWORK_HEALTH_DESCRIPTION]	= $objFrameworkHealth[FHL_FRAMEWORK_HEALTH_DESCRIPTION];
				
						$frameworkHealthObj = new FrameworkHealth();
						$id = $frameworkHealthObj->insert($frameworkHealth);
						Logger::loggerOperation('Novo registro quadro de saúde adicionado. [id ='.$id.']'.'[idFrameworkHealth='.$frameworkHealth[FHL_ID_FRAMEWORK_HEALTH].']');	
					}
				}
				catch(Zend_Exception $e)
				{
					Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
					trigger_error(parent::getLabelResources()->frameworkHealth->error->failDB, E_USER_ERROR);		
				}
			}
		}
		
		/**
		 * Persiste as informações de gravidez
		 */
		public static function savePregnancy($bean, &$db)
		{
			$pregnancy = array();
			$pregnancy[PRG_ID_PERSON]			= $bean[ID_PERSON];
			$pregnancy[PRG_PRENATAL_SIS]		= $bean[SIS_PREGNANCY];
			$pregnancy[PRG_BEGINNING_PREGNANCY]	= $bean[BEGIN_PREGNANCY];
			$pregnancy[PRG_MET]					= $bean[MET];
			$pregnancy[PRG_STATUS]	 			= null;
			
			try
			{
				$pregnancyObj = new Pregnancy();
				$idInserted = $pregnancyObj->insert($pregnancy);
				Logger::loggerOperation('Novo registro de gravidez adicionado. [idPerson='.$pregnancy[HLT_ID_PERSON].']');
				
				return $idInserted;
			}
			catch(Zend_Exception $e)
			{
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->pregnancy->error->failDB, E_USER_ERROR);		
			}
		}
	}