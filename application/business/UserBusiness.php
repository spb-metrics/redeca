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
 * Saulo Esteves Rodrigues  - W3S		    		29/01/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class UserBusiness extends BasicBusiness
{
	/**
	 * Verifica se o usuário pode editar o login 
	 */
	public static function isUniqueLogin($idUser, $login)
	{
		$user = UserBusiness::findByLogin($login);
		
		if($user && count($user) > 0)
		{			
			if($idUser != $user->{AUTH_ID_USER})
			{
				return FALSE;				
			}
		}
		return TRUE;
	}
	/**
	 * Retorna objeto do tipo User com a Role de Coordenador
	 */
	public static function getCoordinator($entityId)
	{
		if($entityId && !empty($entityId))
		{
			$table = new User();
			
			try
			{
				$config = Zend_Registry::get(CONFIG);
				$coordinatorId = $config->user->role->coordinator;

				$where = $table->getAdapter()->quoteInto(AUTH_ID_ROLE.' = ?', $coordinatorId);
				$where = $table->getAdapter()->quoteInto(AUTH_ID_ENTITY_USER.' = ?', $entityId);
				return $table->fetchAll($where);
			}
			catch(Zend_Exception $e)
			{
				$db->rollback();
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->user->load->fail, E_USER_ERROR);
			}
		}
		return NULL;
	} 
	
	/**
	 * Atualiza os registros de um usuário
	 */
	public static function update($user, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new User();
			if(!Utils::isEmpty($user[AUTH_ID_USER]))
			{
				$where = AUTH_ID_USER . ' = ' . $user[AUTH_ID_USER];
				$affected = $obj->update($user, $where);
				Logger::loggerOperation('Dados do Usuário modificados. [id='.$user[AUTH_ID_USER].']');
			}

			if($mt) $db->commit();
			return $affected;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->user->save->fail, E_USER_ERROR);
		}
	}


	/**
	 * Salva usuario 
	 */	
	public static function save($user, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{
			$config  = Zend_Registry::get(CONFIG);
			$userObj = new User();
			
			if(!is_numeric($user[F_ID_ENTITY]))
				$user[F_ID_ENTITY] = null;
			
			if(!is_numeric($user[F_ID_GROUP]))
				$user[F_ID_GROUP] = null;
			
			if($user[F_ID_USER] == false)
			{				
				if($user[F_ID_ROLE] == $config->user->role->coordinator)
				{
					$data = array
					(
						AUTH_ID_ENTITY_USER 	=> 	$user[F_ID_ENTITY],
						AUTH_ID_GROUP_USER 		=> 	$user[F_ID_GROUP],
						AUTH_ID_ROLE_USER 		=> 	$user[F_ID_ROLE],
						AUTH_NAME_USER			=>	$user[F_NAME],
						AUTH_LOGIN_USER			=>	$user[F_LOGIN],
						AUTH_PASSWORD_USER 		=>	md5($user[F_PASSWORD]),
						AUTH_ACTIVE_USER 		=>	$user[F_ACTIVE],
						AUTH_EMAIL_USER			=>	$user[F_EMAIL],
						AUTH_CPF_USER 			=>	$user[F_CPF],
						AUTH_CREATION_DATE_USER	=>	$user[F_CREATION_DATE],
						AUTH_PERMISSION			=>	Constants::ONE
					);
				}
				else
				{
					$data = array
					(
						AUTH_ID_ENTITY_USER 	=> 	$user[F_ID_ENTITY],
						AUTH_ID_GROUP_USER 		=> 	$user[F_ID_GROUP],
						AUTH_ID_ROLE_USER 		=> 	$user[F_ID_ROLE],
						AUTH_NAME_USER			=>	$user[F_NAME],
						AUTH_LOGIN_USER			=>	$user[F_LOGIN],
						AUTH_PASSWORD_USER 		=>	md5($user[F_PASSWORD]),
						AUTH_ACTIVE_USER 		=>	$user[F_ACTIVE],
						AUTH_EMAIL_USER			=>	$user[F_EMAIL],
						AUTH_CPF_USER 			=>	$user[F_CPF],
						AUTH_CREATION_DATE_USER	=>	$user[F_CREATION_DATE]
					);
				}		
				
				$insertedId = $userObj->insert($data);
				if(is_array($user[F_ID_PROFILE]))
				{
					$id = $db->lastInsertId(TBL_AUTH_USER,AUTH_ID_USER);
					
					$userProfileObj = new UserProfile();
					foreach($user[F_ID_PROFILE] as $profile)
					{
						$dataProfile = array(
							AUTH_ID_PROFILE => $profile,
							AUTH_ID_USER_PROFILE => $id
						);
						$insertProfileId = $userProfileObj->insert($dataProfile);
						Logger::loggerOperation('Nova relação de usuário e perfil adicionada. [id='.$insertProfileId.']');
					}
				}
				Logger::loggerOperation('Novo usuário adicionado. [id='.$insertedId.']');
			}
			else
			{
				if($user[F_FLAG_PASSWORD])
				{
					if($user[F_ID_ROLE] == $config->user->role->coordinator)
					{
						$data = array
						(
							AUTH_ID_ENTITY_USER 	=> 	$user[F_ID_ENTITY],
							AUTH_ID_GROUP_USER 		=> 	$user[F_ID_GROUP],
							AUTH_ID_ROLE_USER 		=> 	$user[F_ID_ROLE],
							AUTH_NAME_USER			=>	$user[F_NAME],
							AUTH_LOGIN_USER			=>	$user[F_LOGIN],
							AUTH_PASSWORD_USER 		=>	md5($user[F_PASSWORD]),
							AUTH_ACTIVE_USER 		=>	$user[F_ACTIVE],
							AUTH_EMAIL_USER			=>	$user[F_EMAIL],
							AUTH_CPF_USER 			=>	$user[F_CPF],
							AUTH_CREATION_DATE_USER	=>	$user[F_CREATION_DATE],
							AUTH_PERMISSION			=>	Constants::ONE
						);
					}
					else
					{
						$data = array
						(
							AUTH_ID_ENTITY_USER 	=> 	$user[F_ID_ENTITY],
							AUTH_ID_GROUP_USER 		=> 	$user[F_ID_GROUP],
							AUTH_ID_ROLE_USER 		=> 	$user[F_ID_ROLE],
							AUTH_NAME_USER			=>	$user[F_NAME],
							AUTH_LOGIN_USER			=>	$user[F_LOGIN],
							AUTH_PASSWORD_USER 		=>	md5($user[F_PASSWORD]),
							AUTH_ACTIVE_USER 		=>	$user[F_ACTIVE],
							AUTH_EMAIL_USER			=>	$user[F_EMAIL],
							AUTH_CPF_USER 			=>	$user[F_CPF],
							AUTH_CREATION_DATE_USER	=>	$user[F_CREATION_DATE]
						);
					}
				}
				else
				{
					if($user[F_ID_ROLE] == $config->user->role->coordinator)
					{
						$data = array
						(
							AUTH_ID_ENTITY_USER 	=> 	$user[F_ID_ENTITY],
							AUTH_ID_GROUP_USER 		=> 	$user[F_ID_GROUP],
							AUTH_ID_ROLE_USER 		=> 	$user[F_ID_ROLE],
							AUTH_NAME_USER			=>	$user[F_NAME],
							AUTH_LOGIN_USER			=>	$user[F_LOGIN],
							AUTH_ACTIVE_USER 		=>	$user[F_ACTIVE],
							AUTH_EMAIL_USER			=>	$user[F_EMAIL],
							AUTH_CPF_USER 			=>	$user[F_CPF],
							AUTH_CREATION_DATE_USER	=>	$user[F_CREATION_DATE],
							AUTH_PERMISSION			=>	Constants::ONE
						);
					}
					else
					{
						$data = array
						(
							AUTH_ID_ENTITY_USER 	=> 	$user[F_ID_ENTITY],
							AUTH_ID_GROUP_USER 		=> 	$user[F_ID_GROUP],
							AUTH_ID_ROLE_USER 		=> 	$user[F_ID_ROLE],
							AUTH_NAME_USER			=>	$user[F_NAME],
							AUTH_LOGIN_USER			=>	$user[F_LOGIN],
							AUTH_ACTIVE_USER 		=>	$user[F_ACTIVE],
							AUTH_EMAIL_USER			=>	$user[F_EMAIL],
							AUTH_CPF_USER 			=>	$user[F_CPF],
							AUTH_CREATION_DATE_USER	=>	$user[F_CREATION_DATE]
						);
					}
				}		
				$where = AUTH_ID_USER . ' = ' . $user[F_ID_USER];
				$userObj->update($data, $where);
				
				if(is_array($user[F_ID_PROFILE]))
				{					
					$userProfileObj = new UserProfile();
					$delete = true;
					foreach($user[F_ID_PROFILE] as $profile)
					{
						if($delete == true)
						{
							$where = $userProfileObj->getAdapter()->quoteInto(AUTH_ID_USER_PROFILE.' in (?)', $user[F_ID_USER]);
							$userProfileObj->delete($where);
							$delete = false;
						}
						$dataProfile = array(
							AUTH_ID_PROFILE => $profile,
							AUTH_ID_USER_PROFILE => $user[F_ID_USER]
						);
						$insertProfileId = $userProfileObj->insert($dataProfile);
						Logger::loggerOperation('Nova relação de usuário e perfil adicionada. [id='.$insertProfileId.']');
					}
				}
				Logger::loggerOperation('Usuário modificado. [id='.$user->id.']');
			}
			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->user->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os usuários
	 */
	public static function loadAllUsers($start=NULL, $limit=NULL)
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		try
		{
			Zend_Loader::loadClass('User');
			
			$user = new User();
			
			return $user->fetchAll($where = null, $order = null, $limit, $start);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega os usuarios de um entidade
	 */
	public static function loadUsersByEntity($id, $idRoleUser, $start=NULL, $limit=NULL)
	{
		$table = new User();
		
		try
		{
			if(!empty($id))
			{
				$where[] = $table->getAdapter()->quoteInto(AUTH_ID_ENTITY_USER.' = ?',$id);
				$where[] = $table->getAdapter()->quoteInto(AUTH_ID_ROLE_USER.' > ?',$idRoleUser);
				return $table->fetchAll($where, $order=null, $limit, $start);
			}
			Logger::loggerOperation('Nenhum registro encontrado para '.AUTH_ID_ENTITY_USER.' = '.implode(',' ,$id));
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadUserByCpf($cpf)
	{
		$table = new User();
		
		try
		{
			$where[] = $table->getAdapter()->quoteInto(AUTH_CPF_USER.' = ?',$cpf);
			return $table->fetchRow($where);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega os usuarios de um entidade
	 */
	public static function loadGroupByRole($id)
	{
		$table = new Role();
		
		try
		{
			if(!empty($id))
			{
				$where = $table->getAdapter()->quoteInto(AUTH_ID_ROLE.' = ?',$id);
				$role = $table->fetchAll($where);
			}
			Logger::loggerOperation('Nenhum registro encontrado para '.AUTH_ID_ENTITY_USER.' = '.implode(',' ,$id));
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega um usuario
	 */
	public static function loadOneUser($id)
	{
		$table = new User();
		
		try
		{
			if(!empty($id))
			{
				$where = $table->getAdapter()->quoteInto(AUTH_ID_USER.' = ?',$id);
				return $table->fetchRow($where);
			}
			Logger::loggerOperation('Nenhum registro encontrado para '.AUTH_ID_USER.' = '.implode(',' ,$id));
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->load->fail, E_USER_ERROR);
		}
	}
	
	public static function findByLogin($login)
	{
		$table = new User();
		try
		{
			if(!empty($login))
			{	
				$where = $table->getAdapter()->quoteInto(AUTH_LOGIN_USER.' = ?',$login);
				return $table->fetchRow($where);
			}
			Logger::loggerOperation('Nenhum registro encontrado para '.AUTH_LOGIN_USER.' = '.$login);
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega relacionamentos com perfil de um usuario
	 */
	public static function loadUserProfile($id)
	{
		$table = new UserProfile();
		
		try
		{
			$where = $table->getAdapter()->quoteInto(AUTH_ID_USER_PROFILE.' = ?',$id);
			return $table->fetchAll($where);
//			$select = $db->select()->from(TBL_AUTH_USER_PROFILE);
//			$select->where(AUTH_ID_USER_PROFILE.' = '.$id);
//			return $db->fetchAll($select);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os perfis
	 */
	public static function loadProfile()
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		try
		{
			Zend_Loader::loadClass(CLS_AUTH_PROFILE);
			
			$profile = new Profile();
			
			return $profile->fetchAll(null, AUTH_PROFILE);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadOneProfile($id)
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		try
		{
			Zend_Loader::loadClass(CLS_AUTH_PROFILE);
			
			$profile = new Profile();
			
			$where[] = $profile->getAdapter()->quoteInto(AUTH_ID_PROFILE.' in (?)', $id);
			$where[] = $profile->getAdapter()->quoteInto(AUTH_STATUS.' not in (?)', Constants::DISABLE);
			return $profile->fetchRow($where);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os tipos
	 */
	public static function loadRole()
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		try
		{
			Zend_Loader::loadClass(CLS_AUTH_ROLE);
			
			$role = new Role();
			
			return $role->fetchAll(null, AUTH_ROLE);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos as entidades
	 */
	public static function loadEntity()
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		try
		{
			Zend_Loader::loadClass(CLS_ENTITY);
			
			$entity = new Entity();
			
			return $entity->fetchAll(null, ENT_NAME);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os grupos
	 */
	public static function loadGroup()
	{
		Zend_Loader::loadClass(CLS_AUTH_GROUP);
		$group = new Group();
		
		try
		{
			return $group->fetchAll(null, AGP_GROUP); 
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os gruposde determinada entidade
	 */
	public static function loadGroupByIdEntity($idEntity)
	{
		Zend_Loader::loadClass(CLS_AUTH_GROUP_ENTITY);
		$obj = new GroupEntity();
		
		try
		{			
			$where = $obj->getAdapter()->quoteInto(AGE_ID_ENTITY.' in (?)', $idEntity);						
			return $obj->fetchAll($where); 
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Desabilita um ou mais usuários
	 */
	public static function disableUser($users, &$db = null)
	{		
		if(!empty($users))
		{
			if($db == NULL)
			{
				$db = Zend_Registry::get(DB_CONNECTION);
				$db->beginTransaction();
				$mt = true;
			}
			try
			{	
				$table = new User();
				$data = array();
				
				Zend_Loader::loadClass('Constants');
				
				foreach($users as $user)
				{	
					$data[AUTH_ACTIVE_USER]	= Constants::ZERO;
					$where = $table->getAdapter()->quoteInto(AUTH_ID_USER.' in (?)', $user);
					
					$table->update($data, $where);
				}
				
				Logger::loggerOperation('O(s) usuário(s) com identificação(ões) = '.AUTH_ID_USER."'foi(foram) desativado(s).");
				if($mt) $db->commit();
			}
			catch(Zend_Exception $e)
			{
				$db->rollback();
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->user->save->fail, E_USER_ERROR);
			}
		}
	}
	
	/**
	 * Habilita um ou mais usuários
	 */
	public static function enableUser($users, &$db = null)
	{		
		if(!empty($users))
		{
			if($db == NULL)
			{
				$db = Zend_Registry::get(DB_CONNECTION);
				$db->beginTransaction();
				$mt = true;
			}
			try
			{	
				$table = new User();
				$data = array();
				
				Zend_Loader::loadClass('Constants');
				
				foreach($users as $user)
				{	
					$data[AUTH_ACTIVE_USER]	= Constants::ONE;
					$where = $table->getAdapter()->quoteInto(AUTH_ID_USER.' in (?)', $user);
					
					$table->update($data, $where);
				}
				
				Logger::loggerOperation('O(s) usuário(s) com identificação(ões) = '.AUTH_ID_USER."'foi(foram) desativado(s).");
				if($mt) $db->commit();
			}
			catch(Zend_Exception $e)
			{
				$db->rollback();
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->user->save->fail, E_USER_ERROR);
			}
		}
	}
	
	/**
	 * Verifica se a senha digitada pelo usuário é igual a cadastrada na base de dados
	 * return false : se senhas forem diferentes
	 * return true  : se senhas forem iguais
	 */
	public static function verifyPasswordIsEqualDatabase($password = null)
	{
		$idUserLogged = UserLogged::getUserId();
		$objectUser = self::loadOneUser($idUserLogged); 
				
		if($objectUser != null && sizeof($objectUser) == 1)
		{	
			if(md5($password) != $objectUser->{AUTH_PASSWORD_USER})
			{
				Logger::loggerOperation('Usuário de id = '.$idUserLogged.' digitou uma senha diferente da senha cadastrada no banco de dados.') ;
				
				return false;
			}
			else
			{	
				return true;
			}
		}
			
		trigger_error(BasicBusiness::getLabelResources()->loadUserChangePassword->fail, E_USER_ERROR);
		return false;
	}
	
	
}