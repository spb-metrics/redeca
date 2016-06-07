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
 * Lucas dos Santos Borges Corrêa  - W3S		    28/02/2008	                       Create file 
 * 
 */
 
require_once('BasicBusiness.php');

class GroupBusiness extends BasicBusiness
{
	/**
	 * Carrega todos os registros
	 *  
	 */
	public static function loadAll()
	{
		$table = new Group();

		try
		{
			return $table->fetchAll(null, AGP_GROUP);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->group->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadAllNotDisable()
	{
		$table = new Group();

		try
		{
			$where = $table->getAdapter()->quoteInto(AUTH_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where, AGP_GROUP);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->group->load->fail, E_USER_ERROR);
		}
	}
	
	public static function getGroupRole($group)
	{
		if(!empty($group))
		{
			$firstGroupResource 	= $group->findDependentRowset(CLS_AUTH_GROUP_RESOURCE)->current();
			$roleId					= $firstGroupResource->{AGR_ROLE_ID};
			$type 					= new Role();
			return $type->find($roleId)->current();
		}
		return null;
	}
	
	/**
	 * Carrega um registro
	 * 
	 */
	public static function load($id)
	{
		$table = new Group();
		
		try
		{
			if(!empty($id))
			{
				return $table->find($id);
			}
			Logger::loggerOperation('Nenhum registro encontrado para '.AGP_ID_GROUP.' = '.implode(',' ,$id));
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadNotDisable($id)
	{
		$table = new Group();
		
		try
		{
			$where[] = $table->getAdapter()->quoteInto(AGP_ID_GROUP.' in (?)', $id);
			$where[] = $table->getAdapter()->quoteInto(AUTH_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchRow($where, AGP_GROUP);			
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadByName($name)
	{
		$table = new Group();
		
		try
		{
			$where[] = $table->getAdapter()->quoteInto(AGP_GROUP.' = ?', $name);
			return $table->fetchRow($where, AGP_GROUP);			
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega dados da tabela auth_role dado um array contendo filtro
	 * $query[nome_da_coluna . ' clausula ' . ' ? '] = 'valor_da_coluna'
	 * EX: $query[nome_da_coluna . ' in ' . ' (?) '] = 'valor_da_coluna'
	 */
	public static function loadRoleByQuery($query)
	{
		$table = new Role();

		try
		{
			if(!empty($query) && is_array($query))
			{
				$where = NULL;
				foreach($query as $k => $v)
					$where[] = $table->getAdapter()->quoteInto($k, $v);

				return $table->fetchAll($where, AUTH_ROLE);
			}
			return $table->fetchAll($where, AUTH_ROLE);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e);
			trigger_error(parent::getLabelResources()->role->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega um registro da tabela auth_role dado um ID
	 */
	public static function loadRole($id)
	{
		$table = new Role();

		try
		{
			if(!empty($id))
			{
				return $table->find($id)->current();
			}
			return null;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e);
			trigger_error(parent::getLabelResources()->role->load->fail, E_USER_ERROR);
		}
	}


	/**
	 * Carrega todos os Resources dado o IdRole
	 * @param Integer $idRole Identificação de uma role
	 */
	public static function loadAllResourceByRole($idRole)
	{
		$table = RoleResource();
		try
		{
			if(!empty($idRole))
			{
				$where = $group->getAdapter()->quoteInto(ARR_ID_ROLE.' in (?)', $idRole);
				return $table->fetchAll($where, AUTH_ROLE);
			}
			Logger::loggerOperation('Nenhum registro encontrado para '.ARR_ID_ROLE.' = '.$idRole);
			return null;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->load->fail, E_USER_ERROR);
		}
	}
	/**
	 * Persiste na tabela auth_group_resources informações-padrão para todos os grupos criados
	 * @param Integer $idGroup Identificador de Grupo
	 * @param Integer $idRole Identificador de Role
	 * @param Connection $db Objeto contendo a conexão com o database  
	 */
	public static function saveDefaultGroupResource($idGroup, $idRole, &$db=null)
	{
		if(!empty($idGroup) && !empty($idRole))
		{
			$config = Zend_Registry::get(CONFIG);
			$resources = Utils::buildMap($config->permission->resource->default);
			// Armazena todos os ID's inseridos
			$ids = null;
			foreach($resources as $resource)
			{
				$data[AGR_RESOURCE_ID] 		= $resource;
				$data[AGR_ROLE_ID]			= $idRole;
				$data[AGR_ID_GROUP]			= $idGroup;
				$data[AGR_READONLY]			= FALSE;
				$data[AGR_CHANGE_CONFIDENTIALITY]= FALSE;
				$data[AGR_DEFAULT_CONFIDENTIALITY]= NULL;

				$ids[] = GroupBusiness::saveGroupResource($data, $db);
			}
			if(empty($ids))
				Logger::loggerOperation('Nenhum registro inserido na tabela '.TBL_AUTH_GROUP_RESOURCE);
		}
		else
		{
			Logger::loggerError("Caught exception: ".get_class($this)."\nMessage: ".parent::getLabelResources()->groupresource->save->fail);
			trigger_error(parent::getLabelResources()->groupresource->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Persiste dados na tabela auth_group_resource
	 */
	public static function saveGroupResource($groupResource, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{
			$obj = new GroupResource();
			$rows = count($obj->find($groupResource[AGR_RESOURCE_ID], 
				$groupResource[AGR_ROLE_ID],
				$groupResource[AGR_ID_GROUP]));
			if($rows == 0)
			{
				$res = $obj->insert($groupResource);
				Logger::loggerOperation('Registro adicionado na tabela '.TBL_AUTH_GROUP_RESOURCE.' [id='.implode(',',$res).']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(AAP_ID_ASSISTANCE.' = ?', $groupResource[AGR_RESOURCE_ID]);
				$where[] = $obj->getAdapter()->quoteInto(AAP_ID_PROFILE.' = ?', $groupResource[AGR_ROLE_ID]);
				$where[] = $obj->getAdapter()->quoteInto(AAP_ID_PROFILE.' = ?', $groupResource[AGR_ID_GROUP]);
				
				$obj->update($groupResource, $where);
				$res = $obj->getAdapter()->lastInsertId();
				Logger::loggerOperation('Registro modificado na tabela '.TBL_AUTH_GROUP_RESOURCE.
					' ['.AGR_RESOURCE_ID.'='.$groupResource[AGR_RESOURCE_ID].']'.
					' ['.AGR_ROLE_ID.'='.$groupResource[AGR_ROLE_ID].']'.
					' ['.AGR_ID_GROUP.'='.$groupResource[AGR_ID_GROUP].']');
			}
			if($mt) $db->commit();
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e);
			trigger_error(parent::getLabelResources()->groupresource->save->fail, E_USER_ERROR);
		}
	}

	/**
	 * Faz a persistência na tabela auth_group_resource
	 * 
	 */
	public static function saveGroupResourcePermission($data, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		try
		{
			$groupResource 	= new GroupResource();
			$groupResource->insert($data);
			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->group->save->fail, E_USER_ERROR);
		}
		
	}

	/**
	 * Persiste um array de de valores na tabela auth_group_entity
	 */
	public static function saveAllGroupEntity($groupEntity, &$db=null)
	{
		if(!empty($groupEntity) && is_array($groupEntity))
		{
			foreach($groupEntity as $current)
				$res[] = self::saveGroupEntity($current, $db);
			
			foreach($res as $ids)
			{
				$idGroup[] = $ids[AUTH_ID_GROUP_USER];
				$idEntity = $ids[AUTH_ID_ENTITY_USER];
			}
			
			Zend_Loader::loadClass(CLS_AUTH_USER);			
			$objUser = new User();			
						
			$where[] = $objUser->getAdapter()->quoteInto(AUTH_ID_GROUP_USER.' not in (?)', $idGroup);
			$where[] = $objUser->getAdapter()->quoteInto(AUTH_ID_ENTITY_USER.' = ?', $idEntity);
			
			$data = array(
				AUTH_ID_GROUP_USER => null
			);
			
			$rows = $objUser->update($data, $where);			
		}
		return count($res);
	}
	/**
	 * Persiste dados na tabela auth_group_entity
	 */
	public static function saveGroupEntity($groupEntity, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{
			$obj = new GroupEntity();
			$rows = count($obj->find($groupEntity[AGE_ID_GROUP], 
				$groupEntity[AGE_ID_ENTITY]));
			if($rows == 0)
			{
				$res = $obj->insert($groupEntity);
				Logger::loggerOperation('Registro adicionado na tabela '.TBL_AUTH_GROUP_ENTITY.' [id='.implode(',',$res).']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(AGE_ID_GROUP.' = ?', $groupEntity[AGE_ID_GROUP]);
				$where[] = $obj->getAdapter()->quoteInto(AGE_ID_ENTITY.' = ?', $groupEntity[AGE_ID_ENTITY]);
				
				$obj->update($groupEntity, $where);
				$res = array($groupEntity[AGE_ID_GROUP], $groupEntity[AGE_ID_ENTITY]);
				Logger::loggerOperation('Registro modificado na tabela '.TBL_AUTH_GROUP_ENTITY.
					' ['.AGE_ID_GROUP.'='.$groupEntity[AGE_ID_GROUP].']'.
					' ['.AGE_ID_ENTITY.'='.$groupEntity[AGE_ID_ENTITY].']');
			}
			if($mt) $db->commit();
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e);
			trigger_error(parent::getLabelResources()->groupentity->drop->fail, E_USER_ERROR);
		}
	}

	public static function save($group, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		$data = array
				(
					AGP_GROUP	  	=> $group->{AGP_GROUP},
					AUTH_STATUS	  	=> $group->{AUTH_STATUS},
				);

		try
		{
			if($group->{AGP_ID_GROUP} == false)
			{
				$insertedId = $group->insert($data);
				Logger::loggerOperation('Novo grupo adicionado. [id='.$insertedId.']');
			}
			else
			{
				$where = AGP_ID_GROUP . ' = ' . $group->{AGP_ID_GROUP};
				$data[AGP_ID_GROUP] = $group->{AGP_ID_GROUP};
				$res = $group->update($data, $where);
				$insertedId = $group->{AGP_ID_GROUP};
				Logger::loggerOperation('Grupo modificado. [id='.$group->{AGP_ID_GROUP}.']');
			}
			if($mt) $db->commit();
			return $insertedId;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->group->save->fail, E_USER_ERROR);
		}
	}
	
	
	/**
	 * Apaga registros da tabela auth_group_resource dado a Identificação do grupo
	 * @param Integer/Array(Integer) $idGroup Inteiro ou array de Inteiros que 
	 * representam o Identificador de grupo
	 */
	public static function dropGroupResource($idGroup, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{
			$group = new GroupResource();
			$res = 0;
			if(!empty($idGroup))
			{
				$where = $group->getAdapter()->quoteInto(AGR_ID_GROUP.' in (?)', $idGroup);
				$res = $group->delete($where);

				if($mt) $db->commit();
			}
			if($res > 0)
			{
				(is_array($idGroup)) ? $id = implode(',' ,$idGroup) : $id = $idGroup;
				Logger::loggerOperation('O grupo com '.AGP_ID_GROUP.' = '. $id .
				 	" foi excluído da tabela ".TBL_AUTH_GROUP_RESOURCE);
			}
			else
				Logger::loggerOperation('Nenhum registro foi excluído na tabela '. TBL_AUTH_GROUP_RESOURCE);
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e);
			trigger_error(parent::getLabelResources()->group->drop->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Remove um único group_resource;
	 * 
	 */
	public static function dropGroupResourcePermission($id_group, $id_resource, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{
			if(!empty($id_group))
			{
				$groupResource 	= new GroupResource();
				$where[] 		= $groupResource->getAdapter()->quoteInto(AGR_RESOURCE_ID.' = ?', $id_resource);
				$where[]		= $groupResource->getAdapter()->quoteInto(AGR_ID_GROUP.' = ?', $id_group);
				$res 			= $groupResource->delete($where);
				if($mt) $db->commit();
				Logger::loggerOperation("Foi(ram) excluído(s) $res registro(s) da tabela group_resource");
			}
			else
				Logger::loggerOperation("Nenhum registro excluído da tabela group_resource");
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->group->drop->fail, E_USER_ERROR);
		}
	}

	/**
	 * Apaga registros da tabela auth_group_entity dado a Identificação da entidade
	 * @param Integer/Array(Integer) $idGroup Inteiro ou array de Inteiros que 
	 * representam o Identificador da entidade
	 */
	public static function dropGroupEntityByEntity($idEntity, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{
			Zend_Loader::loadClass('GroupEntity');
			$group = new GroupEntity();
			$res = 0;
			if(!empty($idEntity))
			{
				$where = $group->getAdapter()->quoteInto(AGE_ID_ENTITY.' in (?)', $idEntity);
				$res = $group->delete($where);

				if($mt) $db->commit();
			}
			if($res > 0)
			{
				(is_array($idEntity)) ? $id = implode(',' ,$idEntity) : $id = $idEntity;
				Logger::loggerOperation('O(s) grupo(os) com '.AGE_ID_ENTITY.' = '. $id .
				 	" foi(foram) excluído(s) da tabela ".TBL_AUTH_GROUP_ENTITY);
			}
			else
				Logger::loggerOperation('Nenhum registro foi excluído na tabela '. TBL_AUTH_GROUP_ENTITY);
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e);
			trigger_error(parent::getLabelResources()->groupentity->drop->fail, E_USER_ERROR);
		}
	}
	public static function drop($id, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{
			$group = new Group();
			$res = 0;
			if(!is_null($id) || !empty($id))
			{
				$where = $group->getAdapter()->quoteInto(AGP_ID_GROUP.' in (?)', $id);
				$arrGroup[AUTH_STATUS] = Constants::DISABLE;
				$res = $group->update($arrGroup, $where);

				if($mt) $db->commit();
			}
			if($res > 0)
				Logger::loggerOperation('O(s) grupo(os) com '.AGP_ID_GROUP.' = '. implode(',' ,$id). " foi(foram) excluído(s).");
			else
				Logger::loggerOperation('Nenhum registro foi excluído');
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->group->drop->fail, E_USER_ERROR);
		}
	}
}