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
 * Fabricio Meireles Monteiro  - W3S		    	25/02/2008	                       Create file 
 * 
 */
 
require_once('BasicBusiness.php');

class EntityBusiness extends BasicBusiness
{
	public static function updateAll($entities, &$db=null)
	{
		$i = 0;
		foreach($entities as $entity)
		{
			$ret = self::update($entity, $db);
			if($ret > 0)
				$i++;
		}
		return $i;
	}
	
	/**
	 * Atualiza os registros de uma entidade
	 */
	public static function update($entity, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Entity();
			if(!Utils::isEmpty($entity[ENT_ID_ENTITY]))
			{
				$where = ENT_ID_ENTITY . ' = ' . $entity[ENT_ID_ENTITY];
				$affected = $obj->update($entity, $where);
				Logger::loggerOperation('Entidade modificada. [id='.$entity[ENT_ID_ENTITY].']');
			}
			
			if($mt) $db->commit();
			return $affected;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->entity->save->fail, E_USER_ERROR);
		}
	}

	/**
	 * Salva um pré-cadastro: usuário coordenador e entidade
	 */
	public static function save($bean, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$transaction = true;
		}
		
		try 
		{
			if(!Utils::isEmpty($bean))
			{	
				$insertedEntityId = self::saveEntity($bean, $db);
					
				if($insertedEntityId != null)
				{
					self::saveUser($bean, $insertedEntityId, $db);
					
					if($bean[PROGRAM_TYPE] != null && sizeof($bean[PROGRAM_TYPE]) > 0)
					{
						$program = array();
						$program[PGR_ID_ENTITY] = $insertedEntityId;
						
						foreach($bean[PROGRAM_TYPE] as $programType)
						{
							$program[PGR_ID_PROGRAM_TYPE] = $programType;
							ProgramBusiness::saveProgram($program, $db);
						}
					}
					
					if($bean[CLASSIFICATION_TYPE] != null && sizeof($bean[CLASSIFICATION_TYPE]) > 0)
					{
						$classification = array();
						$classification[ENC_ID_ENTITY] = $insertedEntityId;
						
						foreach($bean[CLASSIFICATION_TYPE] as $classificationType)
						{
							$classification[ENC_ID_ENTITY_CLASSIFICATION] = $classificationType;
							
							ClassificationBusiness::saveClassification($classification, $db);
						}
					}
					
					if($bean[AREA_TYPE] != null && sizeof($bean[AREA_TYPE]) > 0)
					{
						$area = array();
						$area[ETA_ID_ENTITY] = $insertedEntityId;
						
						foreach($bean[AREA_TYPE] as $areaType)
						{
							$area[ETA_ID_ENTITY_AREA] = $areaType;
							AreaBusiness::saveArea($area, $db);
						}
					}
					
					if($bean[GROUP_TYPE] != null && sizeof($bean[GROUP_TYPE]) > 0)
					{
						Zend_Loader::loadClass(CLS_AUTH_GROUP_ENTITY);
						
						$group = array();
						$group[AGE_ID_ENTITY] = $insertedEntityId;					
						
						foreach($bean[GROUP_TYPE] as $groupType)
						{
							$group[AGE_ID_GROUP] = $groupType;
							GroupBusiness::saveGroupEntity($group, $db);
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
		}
	}

	/**
	 * Insere ou edita uma entidade - pré-cadastro
	 * 
	 */
	public static function saveEntity($bean, &$db=null)
	{
		$entity = array();
		
		Zend_Loader::loadClass('Constants');
						
		$entity[ENT_NAME]	= $bean[0];
		$entity[ENT_STATUS]	= Constants::ZERO;
		$entity[ENT_CNPJ]	= $bean[6];
		
		try
		{	
			$entityObj = new Entity();	
			$insertedEntityId = $entityObj->insert($entity);
			Logger::loggerOperation('Nova entidade adicionada. [id='.$insertedEntityId.']');
			
			return $insertedEntityId;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage()." ".$e);
			trigger_error(parent::getLabelResources()->entity->error->failDB, E_USER_ERROR);		
		}
	}


	/**
	 * Insere ou edita um usuário coordenador - pré-cadastro
	 * 
	 */
	public static function saveUser($bean, $idEntity, &$db=null)
	{
		$user = array();
		
		$user[AUTH_ID_ENTITY_USER]		= $idEntity;
		$user[AUTH_ID_GROUP_USER]		= null;
		
		$config = Zend_Registry::get(CONFIG);				
		$coordinator = $config->user->role->coordinator;
		
		$user[AUTH_ID_ROLE_USER]		= $coordinator;
		$user[AUTH_CREATION_DATE_USER] 	= date("Y-m-d");
		$user[AUTH_NAME_USER]			= $bean[1];
		$user[AUTH_CPF_USER]			= $bean[2];
		$user[AUTH_LOGIN_USER]			= $bean[3];
		$user[AUTH_PASSWORD_USER]		= md5($bean[4]);
		$user[AUTH_EMAIL_USER]			= $bean[5];
		$user[AUTH_ACTIVE_USER]			= Constants::ONE;
		$user[AUTH_PERMISSION]			= Constants::ONE;
		
		try
		{
			$userObj = new User();			
			$insertedId = $userObj->insert($user);
			Logger::loggerOperation('Novo usuário coordenador adicionado. [id='.$insertedId.']');
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage()." ".$e);
			trigger_error(parent::getLabelResources()->user->error->failDB, E_USER_ERROR);		
		}
	}

	/**
	 * Carrega Entidade
	 */
	public static function loadEntity($entityId=NULL, $start=NULL, $limit=NULL)
	{
		if($entityId == NULL)
		{
			return EntityBusiness::loadAll($start, $limit);
		}
		else
		{
			return EntityBusiness::load($entityId);
		}
	}
	
	/**
	 * Carrega um registro
	 */
	public static function load($id)
	{
		$table = new Entity();

		try
		{
			if(!empty($id))
			{
				return $table->find($id)->current();
			}
			Logger::loggerOperation('Nenhum registro encontrado para '.ENT_ID_ENTITY.' = '.$id);
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->entity->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega um registro busca por nome
	 */
	public static function loadByName($name=NULL, $start=NULL, $limit=NULL)
	{
		$table = new Entity();

		try
		{			
			if($name)
				$where = $table->getAdapter()->quoteInto(ENT_NAME.' LIKE ?', '%'.$name.'%');
			else
				$where = null;
			
			return $table->fetchAll($where, $order=ENT_NAME, $limit, $start);			
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->entity->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os registros
	 */
	public static function loadAll($start=NULL, $limit=NULL)
	{
		$table = new Entity();

		try
		{
			return $table->fetchAll($where = null, $order = ENT_NAME, $limit, $start);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->entity->load->fail, E_USER_ERROR);
		}
	}
	/**==========================================================================================
	 * Funções relacionados à Group
	 ============================================================================================*/
	/**
	 * Salva um array de registros de EntityGroup
	 */
	public static function saveAllGroup($groups, &$db=null)
	{
		$affected = 0;
		if(!empty($groups))
		{
			if(is_array($groups))
			{
				foreach($groups as $group)
				{
					$res = self::saveGroup($group, $db);
					if($res > 0)
						$affected++;
				}
			}
		}
		return $affected;
	}

	/**
	 * Atualiza os registros de EntityGroup
	 */
	public static function saveGroup($group, &$db=null)
	{
		Zend_Loader::loadClass(CLS_ENTITYGROUP);
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new EntityGroup();
			$rows = count($obj->find($group[ENG_ID_ENTITY], $group[ENG_ID_ENTITY_GROUP]));

			if($rows == 0)
			{ 
				$id = $obj->insert($group);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_ENTITY_GROUP .' [id='.$id.']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(ENG_ID_ENTITY.' = ?', $group[ENG_ID_ENTITY]);
				$where[] = $obj->getAdapter()->quoteInto(ENG_ID_ENTITY_GROUP.' = ?', $group[ENG_ID_ENTITY_GROUP]);
				$affected = $obj->update($group, $where);
				Logger::loggerOperation('Registro modificado na tabela '.TBL_ENTITY_GROUP.
					' ['.ENG_ID_ENTITY.'='.$group[ENG_ID_ENTITY].']'.
					' ['.ENG_ID_ENTITY_GROUP.'='.$group[ENG_ID_ENTITY_GROUP].']');
			}
			
			if($mt) $db->commit();
			return $affected;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->entitygroup->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega um registro
	 */
	public static function loadGroupByEntity($idEntity)
	{
		Zend_Loader::loadClass(CLS_ENTITYGROUP);
		$obj = new EntityGroup();

		try
		{
			if(!empty($idEntity))
			{
				$where = $obj->getAdapter()->quoteInto(ENG_ID_ENTITY.' in (?)', $idEntity);
				return $obj->fetchAll($where);
			}
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->entitygroup->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Exclui registros de EntityGroup dado o Id da entidade
	 */
	public static function dropGroupByEntity($idEntity, &$db=null)
	{
		Zend_Loader::loadClass(CLS_ENTITYGROUP);
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new EntityGroup();
			if($idEntity)
			{ 
				$where = $obj->getAdapter()->quoteInto(ENG_ID_ENTITY.' in (?)', $idEntity);
				$obj->delete($where);
				
				(is_array($idEntity)) ? $ids = implode(',', $idEntity) : $ids = $idEntity; 
				Logger::loggerOperation('Registro modificado na tabela '.TBL_ENTITY_GROUP.
					' ['.ENG_ID_ENTITY.'='.$ids.']');
			}

			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->entitygroup->drop->fail, E_USER_ERROR);
		}
	}
	/**==========================================================================================
	 * Funções relacionados à GroupType
	 ============================================================================================*/

	/**
	 * Carrega todos os registros de EntityGroupType
	 */
	public static function loadAllTypes()
	{
		Zend_Loader::loadClass(CLS_ENTITYGROUPTYPE);
		$table = new EntityGroupType();

		try
		{
			$where = $table->getAdapter()->quoteInto(EGT_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where, EGT_ENTITY_GROUP);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->entitytype->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadTypes($id)
	{
		Zend_Loader::loadClass(CLS_ENTITYGROUPTYPE);
		$table = new EntityGroupType();

		try
		{
			$where[] = $table->getAdapter()->quoteInto(EGT_ID_ENTITY_GROUP.' in (?)', $id);
			$where[] = $table->getAdapter()->quoteInto(EGT_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where, EGT_ENTITY_GROUP);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->entitytype->load->fail, E_USER_ERROR);
		}
	}

}
?>
