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
 * Saulo Esteves Rodrigues  - W3S		    		29/01/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');
require_once('UserBusiness.php');
require_once('UserNotLoggedException.php');
require_once('InvalidResourceForThisOperationException.php');
require_once('User.php');
require_once('GroupResource.php');
require_once('RoleResource.php');
require_once('Resource.php');
require_once('Role.php');
require_once('UserProfile.php');

class AuthBusiness extends BasicBusiness
{
	/**
	 * Faz a autentica��o;
	 * 
	 */
	public static function auth($username, $password)
	{
		$authAdapter 	= AuthBusiness::getAuthAdapter();
		$auth 			= Zend_Auth::getInstance();
		
		$type			= new User();
		$user			= UserBusiness::findByLogin($username);
		if($user->{AUTH_PASSWORD_USER} == md5($password))
		{
			$activeUser = false;
			//verifica se est� ativo, e se a entidade relacionada est� ativa
			if($user->{AUTH_ACTIVE_USER})
			{
				$config 		= Zend_Registry::get(CONFIG);
				$roleAdminId 	= $config->user->role->administrator;
				if($user->{AUTH_ID_ROLE_USER} != $roleAdminId)
				{
					//verifica se a entidade est� ativa
					$typeE 		= new Entity();
					$entity 	= $typeE->find($user->{AUTH_ID_ENTITY_USER})->current();
					if($entity->{ENT_STATUS})
					{
						$activeUser = true;
					}
					else if(($user->{AUTH_ID_ROLE_USER} == $config->user->role->coordinator) && $user->{AUTH_PERMISSION})
					{
						$activeUser = true;
					}
				}
				else
				{
					$activeUser = true;
				}

				if($activeUser)
				{
					//seta as credenciais
					$authAdapter->setIdentity($username);
					$authAdapter->setCredential(md5($password));
			
					//autentica
					$result 	= $auth->authenticate($authAdapter);					
					if($result->isValid())
					{
						$data = $authAdapter->getResultRowObject(null, USER_PASSWORD);//n�o armazena o password na sess�o
						$auth->getStorage()->write($data);//escreve na sess�o
						Logger::loggerAuth("Usu�rio '$username' logou no sistema.");
						return true;
					}
				}
			}			
		}
		
		

		return false;
	}
	
	/**
	 * Configura e eecupera o componente de autentica��o;
	 * 
	 */
	private static function getAuthAdapter()
	{
		Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');
		$db 			= Zend_Registry::get(DB_CONNECTION);
		$authAdapter 	= new Zend_Auth_Adapter_DbTable($db);
		$authAdapter->setTableName(TBL_AUTH_USER);
		$authAdapter->setIdentityColumn(AUTH_LOGIN_USER);
		$authAdapter->setCredentialColumn(AUTH_PASSWORD_USER);

		return $authAdapter;
	}
	
	/**
	 * Recupera a identidade do usu�rio autenticado;
	 * 
	 */
	private static function getIdentity()
	{
		$identity = Zend_Auth::getInstance()->getIdentity();
		if($identity != null)
		{
			return $identity;
		}
	}
	
	/**
	 * Verifica se o usu�rio autenticado pertence ao tipo informado;
	 * 
	 */
	private static function belongsToTheRole($roleId)
	{
		$identity = AuthBusiness::getIdentity();
		if($identity == null)
			throw new UserNotLoggedException();

		$config = Zend_Registry::get(CONFIG);
		if($roleId == $identity->{AUTH_ID_ROLE_USER})
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * Recupera informa��o do usu�rio de acordo com o
	 * nome do campo informado;
	 * 
	 */
	private static function getIdentityField($fieldname)
	{
		$identity = AuthBusiness::getIdentity();
		
		if($identity != null)
		{
			if($fieldname == AUTH_ID_GROUP_USER)
			{
				if(!is_null($identity->{$fieldname}))
				{
					Zend_Loader::loadClass(CLS_AUTH_GROUP);
					$objGroup = new Group();
					
					$where = $objGroup->getAdapter()->quoteInto(AUTH_ID_GROUP_USER.' = ?', $identity->{$fieldname});
					
					$row = $objGroup->fetchRow($where);
					
					if($row->{AUTH_STATUS} == Constants::DISABLE)
					{
						return null;
					}
				}
			}
			return $identity->{$fieldname};
		}
		else
		{
			throw new UserNotLoggedException();
		}
	}
	
	/**
	 * Recupera o resource no banco;
	 * 
	 * throws InvalidResourceForThisOperation
	 * 
	 */
	public static function getResource($resourceName)
	{
		if(substr($resourceName, 0, 1) != "/")
			$resourceName = "/".$resourceName;
		try
		{
			$type 	= new Resource();
			$where 	= $type->getAdapter()->quoteInto(ARC_CONTROLLER_NAME." = '".$resourceName."'");
			$res 	= $type->fetchAll($where);
			
			if(count($res) == 0 || count($res) > 1)
				throw new InvalidResourceForThisOperation();
			
			return $res->current();
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("AuthBusiness::getResource fail. [resourceName=$resourceName]");
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->permission->proccess->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Recupera o groupResource, que cont�m o detalhamento do permissionamento
	 * da funcionalidade/usu�rio atuais;
	 * 
	 * throws InvalidResourceForThisOperation
	 * 
	 */
	private static function getGroupResource($groupId, $resourceId)
	{
		try
		{		
			//verifica a rela��o resourceid/groupid, qual a configura��o de permiss�o
			$type 	= new GroupResource(); 
			$where 	= $type->getAdapter()->quoteInto(AGR_ID_GROUP." = ".$groupId." AND ".AGR_RESOURCE_ID." = ".$resourceId);
			$res 	= $type->fetchAll($where);
			if(count($res) == 0)
				throw new InvalidResourceForThisOperation();

			return $res->current();
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("AuthBusiness::getGroupResource fail. [groupId=$groupId, resourceId=$resourceId]");
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->permission->proccess->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Recupera as permiss�es do grupo do usu�rio;
	 * 
	 */
	public static function getGroupResources()
	{
		try
		{		
			$type 		= new GroupResource();			
			$idGroup	= AuthBusiness::getGroupId();			
			$where 		= $type->getAdapter()->quoteInto(AGR_ID_GROUP." = ?", $idGroup);
			$res 		= $type->fetchAll($where);			
			$arrReturn 	= array();
			foreach($res as $r)
			{
				$type 			= new Resource();
				$arrReturn[] 	= $type->find($r->{AGR_RESOURCE_ID})->current();
			}
			
			return $arrReturn;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("AuthBusiness::getGroupResources fail.");
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->permission->proccess->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Recupera as permiss�es do tipo do usu�rio;
	 * 
	 */
	public static function getRoleResources()
	{
		try
		{		
			$config 		= Zend_Registry::get(CONFIG);
			$identity 	= AuthBusiness::getIdentity();
			$entity		= new Entity();
			$result		= $entity->find($identity->{AUTH_ID_ENTITY_USER})->current();		
			
			if(!$result->{ENT_STATUS} && ($identity->{AUTH_ID_ROLE_USER} == $config->user->role->coordinator))
			{					
				if($identity->{AUTH_PERMISSION})
				{						
					$arrReturn 	= array();
					$type		= new Resource();
					
					$where		= $type->getAdapter()->quoteInto(ARC_CONTROLLER_NAME." = '".ENTITY_CONTROLLER."' OR " . ARC_CONTROLLER_NAME . " = '" . SEARCHADDRESS_CONTROLLER . "'");		
					$arrReturn  = $type->fetchAll($where);
					
					return $arrReturn;
				}
			}
			else
			{
				$type 		= new RoleResource();
				$where 		= $type->getAdapter()->quoteInto(ARR_ID_ROLE." = ".$identity->{AUTH_ID_ROLE_USER});
				$res 		= $type->fetchAll($where);				
				$arrReturn 	= array();
				foreach($res as $r)
				{
					$type 			= new Resource();
					$arrReturn[]	= $type->find($r->{ARR_ID_RESOURCE})->current();
				}
				return $arrReturn;				
			}			
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("AuthBusiness::getRoleResources fail.");
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->permission->proccess->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Recupera todos os resources permitidos  para o usu�rio;
	 * 
	 */
	public static function getResources()
	{
		if(AuthBusiness::isAdministrator() || AuthBusiness::isManager() || AuthBusiness::isCoordinator())
		{
			//n�o possuem grupos - buscar permiss�es no role-resources
			$allowResources = AuthBusiness::getRoleResources();			
		}
		else
		{
			//usu�rio que obrigatoriamente possuem grupos - buscar permiss�es no group-resources
			$allowResources = AuthBusiness::getGroupResources();					
		}		
		return $allowResources;
	}
	
	/**
	 * Verifica se o usu�rio est� logado;
	 * 
	 */
	public static function isLogged()
	{
		$identity = AuthBusiness::getIdentity();
		if($identity == null)
			return FALSE;
		else
			return TRUE;
	}
	
	/**
	 * Recupera o id do tipo do usu�rio;
	 * 
	 */
	public static function getUserRoleId()
	{
		return AuthBusiness::getIdentityField(AUTH_ID_ROLE_USER);
	}
	
	/**
	 * Verifica se o usu�rio � administrador;
	 * 
	 */
	public static function isAdministrator()
	{
		$config = Zend_Registry::get(CONFIG);
		return AuthBusiness::belongsToTheRole($config->user->role->administrator);
	}
	
	/**
	 * Verifica se o usu�rio � Administrador da Rede;
	 * 
	 */
	public static function isManager()
	{
		$config = Zend_Registry::get(CONFIG);
		return AuthBusiness::belongsToTheRole($config->user->role->manager);
	}
	
	/**
	 * Verifica se o usu�rio � Coordenador da Entidade;
	 * 
	 */
	public static function isCoordinator()
	{
		$config = Zend_Registry::get(CONFIG);
		return AuthBusiness::belongsToTheRole($config->user->role->coordinator);
	}
	
	/**
	 * Verifica se o usu�rio � T�cnico;
	 * 
	 */
	public static function isTechnician()
	{
		$config = Zend_Registry::get(CONFIG);
		return AuthBusiness::belongsToTheRole($config->user->role->technician);
	}
	
	/**
	 * Verifica se o usu�rio � Operador;
	 * 
	 */
	public static function isOperator()
	{
		$config = Zend_Registry::get(CONFIG);
		return AuthBusiness::belongsToTheRole($config->user->role->operator);
	}
	
	/**
	 * Recupera o id do usu�rio;
	 * 
	 */
	public static function getUserId()
	{
		return AuthBusiness::getIdentityField(AUTH_ID_USER);
	}
	
	/**
	 * Recupera o nome completo do usu�rio;
	 * 
	 */
	public static function getUserName()
	{
		return AuthBusiness::getIdentityField(AUTH_NAME_USER);
	}
	
	/**
	 * Recupera o login do usu�rio;
	 * 
	 */
	public static function getUserLogin()
	{
		return AuthBusiness::getIdentityField(AUTH_LOGIN_USER);
	}
	
	/**
	 * Recupera o login do usu�rio;
	 * 
	 */
	public static function getGroupId()
	{
		return AuthBusiness::getIdentityField(AUTH_ID_GROUP_USER);
	}
	
	/**
	 * Recupera o id da entidade relacionada ao usu�rio;
	 * 
	 */
	public static function getEntityId()
	{
		return AuthBusiness::getIdentityField(AUTH_ID_ENTITY_USER);
	}
	
	/**
	 * Verifica se o usu�rio possui permiss�o para alterar a confidencialidade
	 * do atendimento;
	 * 
	 * throws UserNotLoggedException, InvalidResourceForThisOperation
	 * 
	 */
	public static function allowChangeDefaultConfidentiality($resourceName)
	{
		//usu�rio Adm, gerente e coordenador n�o possuem essa limita��o
		if(AuthBusiness::isAdministrator() || AuthBusiness::isManager() || AuthBusiness::isCoordinator())
			return TRUE;

		//para operadores e t�cnicos
		try
		{
			$resource = AuthBusiness::getResource($resourceName);
			if($resource->{ARC_RESOURCE_TYPE} == RESOURCE_TYPE_ADM)
				throw new InvalidResourceForThisOperation();
			
			$groupId = AuthBusiness::getGroupId();
			if($groupId != "" && $groupId != null)
			{
				//verifica a rela��o resourceid/groupid, qual a configura��o de permiss�o
				$perm = AuthBusiness::getGroupResource(AuthBusiness::getGroupId(), $resource->id_resource);
				
				if($perm->{AGR_CHANGE_CONFIDENTIALITY} == 1)
					return TRUE;
				else
					return FALSE;
			}
			Logger::loggerError("Database truncado. Usu�rio com perfil operador/t�cnico n�o possui grupo associado.");
			return FALSE;			
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("AuthBusiness::allowChangeDefaultConfidentiality fail. [resourceName=$resourceName]");
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->permission->proccess->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Retorna qual a confidencialidade padr�o para o atendimento;
	 * 
	 * throws UserNotLoggedException, InvalidResourceForThisOperation
	 * 
	 */
	public static function getDefaultConfidentiality($resourceName)
	{
		//usu�rio Adm, gerente e coordenador n�o possuem essa limita��o
		if(AuthBusiness::isAdministrator() || AuthBusiness::isManager() || AuthBusiness::isCoordinator())
			throw new InvalidResourceForThisOperation();

		//para operadores e t�cnicos
		try
		{
			$resource = AuthBusiness::getResource($resourceName);
			if($resource->{ARC_RESOURCE_TYPE} == RESOURCE_TYPE_ADM)
				throw new InvalidResourceForThisOperation();
			
			$groupId = AuthBusiness::getGroupId();
			if($groupId != "" && $groupId != null)
			{
				//verifica a rela��o resourceid/groupid, qual a configura��o de permiss�o
				$perm = AuthBusiness::getGroupResource(AuthBusiness::getGroupId(), $resource->id_resource);
				
				return $perm->{AGR_DEFAULT_CONFIDENTIALITY};
			}
			Logger::loggerError("Database truncado. Usu�rio com perfil operador/t�cnico n�o possui grupo associado.");
			return FALSE;			
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("AuthBusiness::getDefaultConfidentiality fail. [resourceName=$resourceName]");
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->permission->proccess->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Verifica se o usu�rio atual tem apenas permiss�o somente-leitura ao resource atual;
	 * 
	 * throws UserNotLoggedException, InvalidResourceForThisOperation
	 * 
	 */
	 public static function isResourceReadOnly($resourceName)
	 {
		//usu�rio Adm, gerente e coordenador n�o possuem essa limita��o
		if(AuthBusiness::isAdministrator() || AuthBusiness::isManager() || AuthBusiness::isCoordinator())
			return FALSE;

		//para operadores e t�cnicos
		try
		{
			$resource = AuthBusiness::getResource($resourceName);
			if($resource->{ARC_RESOURCE_TYPE} == RESOURCE_TYPE_ADM)
				throw new InvalidResourceForThisOperation();

			$groupId = AuthBusiness::getGroupId();
			if($groupId != "" && $groupId != null)
			{
				//verifica a rela��o resourceid/groupid, qual a configura��o de permiss�o
				$perm = AuthBusiness::getGroupResource(AuthBusiness::getGroupId(), $resource->id_resource);

				if($perm->{AGR_READONLY} == 1)
					return TRUE;
				else
					return FALSE;
			}
			Logger::loggerError("Database truncado. Usu�rio com perfil operador/t�cnico n�o possui grupo associado.");
			return FALSE;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("AuthBusiness::isResourceReadOnly fail. [resourceName=$resourceName]");
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->permission->proccess->fail, E_USER_ERROR);
		}
	 }
	 
	 /**
	  * Booleano que indica se deve exibir os containers de crian�a selecionada;
	  * 
	  */
	 public static function showContainers($resourceName)
	 {
	 	$resource = AuthBusiness::getResource($resourceName);
		if($resource->{ARC_RESOURCE_TYPE} == RESOURCE_TYPE_ADM)
			return FALSE;
		else
			return TRUE;
	 }
	 
	 /**
	  * Returna um array com os perfis associados ao usu�rio;
	  * 
 	  * throws UserNotLoggedException
 	  * 
	  */
	 public static function getProfiles()
	 {
		$userId = AuthBusiness::getUserId();
		
		try
		{	
			$type = new UserProfile();
			
			//recupera os perfis associado ao usu�rio logado			
			$where[] 		= $type->getAdapter()->quoteInto(AUTH_ID_USER_PROFILE.' = ?', $userId);
			$rows 		= $type->fetchAll($where);
			$arrReturn 	= array();
			if(count($rows)>0)
			{
				foreach($rows as $k=>$v)
				{
					if($v->findParentRow(CLS_AUTH_PROFILE)->{AUTH_STATUS} != Constants::DISABLE)
					{
						$profile 	= $v->findParentRow(CLS_AUTH_PROFILE);
						
						$arrReturn[]= $profile;
					}
				}
			}
			return $arrReturn;
			
			Logger::loggerOperation('Nenhum perfil encontrado para o id_usu�rio = '.$userId);
			return;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("AuthBusiness::getProfiles fail.");
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->permission->proccess->fail, E_USER_ERROR);
		}	
	 }
	 
	 /**
	 * Verifica se o usu�rio tem permiss�o de acesso para o nome do controller
	 * informado (utilizado para montar os menus);
	 * 
	 */
	 public static function isAllowResource($controllerName)
	 {
		$resource 	= AuthBusiness::getResource($controllerName);
		$resources 	= AuthBusiness::getResources();		
		
		if(count($resources) > 0)
		{
			foreach($resources as $k=>$v)
			{
				if($v != null)
					if($v->{ARC_ID_RESOURCE} == $resource->{ARC_ID_RESOURCE})
						return TRUE;
			}
		}
		return FALSE;
	 }
}