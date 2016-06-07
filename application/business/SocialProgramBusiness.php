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

class SocialProgramBusiness extends BasicBusiness
{
	/**
	 * Persiste informações no DB 
	 * @param Array $socProgram - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($socProgram, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{	
			$obj = new SocialProgram();
			if($socProgram[SPG_ID_PR_SOCIAL] == false)
			{	
				$id = $obj->insert($socProgram);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_SOCIAL_PROGRAM .' [id='.$id.']');
			}
			else
			{
				$where = $obj->getAdapter()->quoteInto(SPG_ID_PERSON.' = ?', $socProgram[SPG_ID_PERSON]);
				$id = $obj->update($socProgram, $where);
				
				Logger::loggerOperation('Registro modificado na tabela '.TBL_SOCIAL_PROGRAM.
					' ['.SPG_ID_PERSON.'='.$socProgram[SPG_ID_PERSON].']');
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->socialprogram->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os tipos de programas sociais
	 */
	public static function loadAllSocialPrograms()
	{	
		Zend_Loader::loadClass('SocialProgramType');
		
		$table	= new SocialProgramType();
	
		try
		{	
			$where = $table->getAdapter()->quoteInto(SCP_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where, SCP_BENEFIT);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->typesocialprogram->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadSocialPrograms($id)
	{	
		Zend_Loader::loadClass('SocialProgramType');
		
		$table	= new SocialProgramType();
	
		try
		{	
			$where[] = $table->getAdapter()->quoteInto(SCP_ID_SOCIAL_PROGRAM.' in (?)', $id);
			$where[] = $table->getAdapter()->quoteInto(SCP_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->typesocialprogram->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega do banco as informações de programa social conforme "id do programa"  
	 */
	public static function loadProgramSocialByProgram($idProgram = null)
	{	
		try
		{	
			$type = new SocialProgram();
			
			if(!empty($idProgram))
			{	
				
				//busca na tabela programa social a linha referente ao "idPerson" 			
				$where = $type->getAdapter()->quoteInto(SPG_ID_PR_SOCIAL.' = ?', $idProgram);
				
				$row = $type->fetchAll($where);
				
				return $row;
			}
			
			Logger::loggerOperation('Nenhum registro de programa social encontrado para '.SPG_ID_PR_SOCIAL. ' = '.$idProgram);
			
			return null;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->programsocial->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega do banco as informações de programa social conforme "id" e "programa social" da pessoa  
	 */
	public static function loadProgramSocialByPerson($idPerson, $idProgramSocial = null)
	{	
		try
		{	
			$type = new SocialProgram();
			
			if(!empty($idPerson))
			{	
				
				//busca na tabela programa social a linha referente ao "idPerson" 			
				$where[] = $type->getAdapter()->quoteInto(SPG_ID_PERSON.' = ?', $idPerson);
				$where[] = $type->getAdapter()->quoteInto(SPG_STATUS.' is null', null);
				if($idProgramSocial != null)
				{
					$where[] = $type->getAdapter()->quoteInto(SPG_ID_SOCIAL_PROGRAM.' = ?', $idProgramSocial);
				}
				$row = $type->fetchAll($where);
				
				return $row;
			}
			
			Logger::loggerOperation('Nenhum registro de programa social encontrado para '.$idPerson.' = '.implode(',' ,$idPerson));
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->programsocial->load->fail, E_USER_ERROR);
		}
	}
		
	/*
	 * Persiste as informações referentes a programa social de uma pessoa
	 */
	public static function saveBenefit($bean, &$db=null)
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
				$verify = self::existPerson($bean[SPG_ID_PERSON], $bean[SPG_ID_PR_SOCIAL], $db);

				if($verify != null)
				{	
					self::updateStatus($bean[SPG_ID_PR_SOCIAL], $db);
				}
						
				$program = array();
				$program[SPG_ID_PERSON]	= $bean[SPG_ID_PERSON];
				
				foreach($bean[OBJECTS_BENEFIT] as $ub)
				{	
					$program[SPG_ID_SOCIAL_PROGRAM]	= $ub[SPG_ID_SOCIAL_PROGRAM];
					
					if($ub[SPG_REGISTER_DATE])
					{
						$dateFormat = BasicForm::dateFormat($ub[SPG_REGISTER_DATE]);
						$program[SPG_REGISTER_DATE]	= $dateFormat;	
					}
					else
					{
						$program[SPG_REGISTER_DATE]	= date("Y-m-d");
					}
					
					$insertedProgramId = self::save($program, $db);
					
					if($insertedProgramId != null)
					{
						//cria objeto do tipo histórico de alterações
						$chProgram = array();
						$chProgram[PCH_ID_REFERENCE_FOREIGN]= $insertedProgramId;
						$chProgram[PCH_ID_USER]				= UserLogged::getUserId();
						$chProgram[PCH_ID_PERSON]			= $program[SPG_ID_PERSON];
						$chProgram[PCH_ID_RESOURCE]			= parent::loadIdResource($bean[NAME_CONTROLLER]);
						$chProgram[PCH_DATE_OPERATION]		= date("Y-m-d");
						$chProgram[PCH_TABLE_NAME]			= TBL_SOCIAL_PROGRAM;
						
						//persiste na tabela "histórico de alterações"					
						HistoryBusiness::save($chProgram, $db);
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
			trigger_error(parent::getLabelResources()->programsocial->error->failDB, E_USER_ERROR);
		}
	}
	
	/**
	 * Verifica se a pessoa já tem cadastro referente a programa social
	 */
	public static function existPerson($idPerson, $idProgramSocial,  &$db)
	{	
		try
		{
			$obj = new SocialProgram();
		
			//verifica se na tabela programa social existe registro referente ao "idPerson"  			
			$where[] = $obj->getAdapter()->quoteInto(SPG_ID_PERSON.' = ?', $idPerson);
			$where[] = $obj->getAdapter()->quoteInto(SPG_ID_PR_SOCIAL.' = ?', $idProgramSocial);
			$where[] = $obj->getAdapter()->quoteInto(SPG_STATUS.' is null', null);
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
			trigger_error(parent::getLabelResources()->socialProgram->load->failDB, E_USER_ERROR);		
		}
	}
	
	public static function existProgramToPerson($idPerson, $idProgramSocialType,  &$db)
	{	
		try
		{
			$obj = new SocialProgram();
		
			//verifica se na tabela programa social existe registro referente ao "idPerson"  			
			$where[] = $obj->getAdapter()->quoteInto(SPG_ID_PERSON.' = ?', $idPerson);
			$where[] = $obj->getAdapter()->quoteInto(SPG_ID_SOCIAL_PROGRAM.' = ?', $idProgramSocialType);
			$where[] = $obj->getAdapter()->quoteInto(SPG_STATUS.' is null', null);
			$row = $obj->fetchAll($where);
			
			return $row;	
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->socialProgram->load->failDB, E_USER_ERROR);		
		}
	}
	
	/**
	 * Atualiza o campo "status" da tabela programa social "sop_social_program"
	 */
	public static function updateStatus($idProgramSocial = null, &$db = null)
	{	
		$obj = new SocialProgram();
		
		//busca na tabela programa social a linha referente aos parâmetros passados 			
		if($idProgramSocial != null)
		{
			$where[] = $obj->getAdapter()->quoteInto(SPG_ID_PR_SOCIAL.' = ?', $idProgramSocial);
		}
		
		$rows = $obj->fetchAll($where);	
			
		if(sizeof($rows) == 1)
		{		
			if($db == null)
			{
				$db = Zend_Registry::get(DB_CONNECTION);
				$db->beginTransaction();
				$transaction = true;
			}
			
			try
			{
				Zend_Loader::loadClass('Constants');
				
				$data = array();
				$data[SPG_STATUS] = Constants::HISTORY;
				
				$obj = new SocialProgram();
				$obj->update($data, $where);
				
				if($transaction)
				{
					$db -> commit();
				}
				
				Logger::loggerOperation('Status do programa social de id = '.$idProgramSocial.' atualizado com sucesso');
				
			}
			catch(Zend_Exception $e)
			{
				$db->rollback();
				$db->closeConnection();	
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->programsocial->error->failDB, E_USER_ERROR);		
			}		
		}
	}
}