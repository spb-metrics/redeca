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
 * Jefferson Barros Lima  - W3S		   				05/05/2008	                       Create file 
 * 
 */
require_once('BasicController.php');

class UserController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('User');
		parent::setControllerHelp('User');
		
		Zend_Loader::loadClass(CLS_AUTH_USER);
		Zend_Loader::loadClass('UserForm');
		Zend_Loader::loadClass('UserValidator');
		Zend_Loader::loadClass('UserBusiness');
		Zend_Loader::loadClass('UserProfile');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('GroupBusiness');
		
		$frm = new UserForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
		
		$this->view->profiles 	= UserBusiness::loadProfile();
		$this->view->roles 		= UserBusiness::loadRole();
		$this->view->entities 	= UserBusiness::loadEntity();
		$this->view->config		= Zend_Registry::get(CONFIG);
		$this->view->loggedRole	= UserLogged::getUserRoleId();
	}
	
	/**
	 * Lista todos os usuários cadastrados no sistema
	 */
	function indexAction()
	{
		$page 	= 1;

		if($this->getRequest()->isPost() && !$this->getRequest()->getPost(UserForm::filter()))
			$page = (int)$this->getRequest()->get(UserForm::page());

		$start 					= BasicController::pageToStart($page, Zend_Registry::get(TUSERPAGE));		
		
		$admin = UserLogged::isAdministrator();
		if($admin === TRUE)
		{
			$total				= UserBusiness::count(TBL_AUTH_USER, AUTH_ID_USER);
			$this->view->users 	= UserBusiness::loadAllUsers($start, Zend_Registry::get(TUSERPAGE));
			$this->view->groups = UserBusiness::loadGroup();
		}
		else
		{
			$entity 			= UserLogged::getEntityId();
			$where[AUTH_ID_ENTITY_USER.' = ?'] = $entity;
			$where[AUTH_ID_ROLE_USER.' > ?'] = UserLogged::getUserRoleId();
			$total				= UserBusiness::count(TBL_AUTH_USER, AUTH_ID_ENTITY_USER, $where);
			$this->view->users 	= UserBusiness::loadUsersByEntity($entity, UserLogged::getUserRoleId(), $start, Zend_Registry::get(TUSERPAGE));
			$groupEntity 		= UserBusiness::loadGroupByIdEntity($entity);
			foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
			$this->view->groups = $group;
		}
		
		$this->navBar($page, $total, Zend_Registry::get(TUSERPAGE));
	}
	
	function viewChangePasswordAction()
	{
		;
	}
	
	
	/**
	 * Persiste a nova senha do usuário
	 */
	function saveChangePasswordAction()
	{	
		//valida as informações inseridas no form pelo usuário  
		$errorMessages = UserValidator::validateChangePassword($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;
			
			//Retorna para o template atual exibindo as mensagens de validação
			return;
		}
		
		$user = array();
		$user[F_ID_USER] 		= UserLogged::getUserId();
		$user[F_PASSWORD] 		= md5($this->view->form->getPassword());
		
		UserBusiness::update($user);
		
		$this->_redirect(USER_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}

	/**
	 * Exibe formulário para criação de um novo usuário
	 */
	function newAction()
	{
		$admin = UserLogged::isAdministrator();
		if($admin === FALSE)
		{
			try{
				$userId = UserLogged::getUserId();
			}catch(UserNotLoggedException $e){
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$this->view->labels->notPermission.' - '.$e);
				trigger_error($this->view->labels->notPermission , E_USER_ERROR);
			}
			
			$this->view->idEntity 	= UserLogged::getEntityId();
			$this->view->idUser 	= $userId;
			$groupEntity 			= UserBusiness::loadGroupByIdEntity($this->view->idEntity);
			foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
			$this->view->groups = $group; 
		}
		else
			$this->view->groups = UserBusiness::loadGroup();
	}
	
	/**
	 * Exibe formulário para edição de um usuário
	 */
	function viewAction()
	{
		$this->view->form->setId($this->_request->getParam($this->view->form->id()));
		
		$errorMessages = UserValidator::validateUserId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$page 	= 1;
			if($this->getRequest()->isPost() && !$this->getRequest()->getPost(UserForm::filter()))
				$page = $this->getRequest()->getPost(UserForm::page());
	
			$start 					= BasicController::pageToStart($page, Zend_Registry::get(TUSERPAGE));			
			
			$admin = UserLogged::isAdministrator();
			if($admin === TRUE)
			{
				$total				= UserBusiness::count(TBL_AUTH_USER, AUTH_ID_USER);
				$this->view->users 	= UserBusiness::loadAllUsers($start, Zend_Registry::get(TUSERPAGE));
				$this->view->groups = UserBusiness::loadGroup();
			}
			else
			{
				$entity 			= UserLogged::getEntityId();
				$where[AUTH_ID_ENTITY_USER.' = ?'] = $entity;
				$where[AUTH_ID_ROLE_USER.' > ?'] = UserLogged::getUserRoleId();
				$total				= UserBusiness::count(TBL_AUTH_USER, AUTH_ID_ENTITY_USER, $where);
				$this->view->users 	= UserBusiness::loadUsersByEntity($entity, UserLogged::getUserRoleId(), $start, Zend_Registry::get(TUSERPAGE));
				$groupEntity 		= UserBusiness::loadGroupByIdEntity($entity);
				foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
				$this->view->groups = $group;
			}
			
			$this->navBar($page, $total, Zend_Registry::get(TUSERPAGE));
			
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		$user = UserBusiness::loadOneUser($this->view->form->getId());
		UserForm::assembleUserToForm($user);
		
		$admin = UserLogged::isAdministrator();
		if($admin === FALSE)
		{
			try{
				$userId = UserLogged::getUserId();
			}catch(UserNotLoggedException $e){
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$this->view->labels->notPermission.' - '.$e);
				trigger_error($this->view->labels->notPermission , E_USER_ERROR);
			}
			
			$this->view->idEntity 	= UserLogged::getEntityId();
			$this->view->idUser 	= $userId;
			$groupEntity 			= UserBusiness::loadGroupByIdEntity($this->view->idEntity);
			foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
			$this->view->groups = $group; 
		}
		else
		{			
			$groupEntity 			= UserBusiness::loadGroupByIdEntity($this->view->form->getidEntity());
			foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
			$this->view->groups = $group; 
		}
		
		$this->view->userProfile = UserBusiness::loadUserProfile($this->view->form->getId());
	}
		
	/**
	 * Salva novo usuário (cadastro)
	 */
	function addAction()
	{	
		$errorMessages = UserValidator::validateUserAdd($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$admin = UserLogged::isAdministrator();
			if($admin === FALSE)
			{
				try{
					$userId = UserLogged::getUserId();
				}catch(UserNotLoggedException $e){
					Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$this->view->labels->notPermission.' - '.$e);
					trigger_error($this->view->labels->notPermission , E_USER_ERROR);
				}
				
				$this->view->idEntity 	= UserLogged::getEntityId();
				$this->view->idUser 	= $userId;
				$groupEntity 			= UserBusiness::loadGroupByIdEntity($this->view->idEntity);
				foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
				$this->view->groups = $group;
			}
			else
				$this->view->groups = UserBusiness::loadGroup();
			return;
		}
		
		$user = UserForm::assembleFormToUser($this->view->form);
		UserBusiness::save($user);
		
		$this->_redirect(USER_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Salva usuário (edição)
	 */
	function editAction()
	{
		$errorMessages = UserValidator::validateUserEdit($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$admin = UserLogged::isAdministrator();
			if($admin === FALSE)
			{
				try{
					$userId = UserLogged::getUserId();
				}catch(UserNotLoggedException $e){
					Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$this->view->labels->notPermission.' - '.$e);
					trigger_error($this->view->labels->notPermission , E_USER_ERROR);
				}
				$this->view->idEntity 	= UserLogged::getEntityId();
				$this->view->idUser 	= $userId;
				$groupEntity 			= UserBusiness::loadGroupByIdEntity($this->view->idEntity);
				foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
				$this->view->groups = $group;
			}
			else
				$this->view->groups = UserBusiness::loadGroup();
			return;
		}
		
		$user = UserForm::assembleFormToUser($this->view->form);
		UserBusiness::save($user);
		
		$this->_redirect(USER_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Desabilita um usuário
	 */
	function disableAction()
	{
		//valida as informações inseridas no form pelo usuário  
		$errorMessages = UserValidator::validateUserId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;
			
			$page 	= 1;
			if($this->getRequest()->isPost() && !$this->getRequest()->getPost(UserForm::filter()))
				$page = $this->getRequest()->getPost(UserForm::page());
						
			$start 					= BasicController::pageToStart($page, Zend_Registry::get(TUSERPAGE));			
			
			$admin = UserLogged::isAdministrator();
			if($admin === TRUE)
			{
				$total				= UserBusiness::count(TBL_AUTH_USER, AUTH_ID_USER);
				$this->view->users 	= UserBusiness::loadAllUsers($start, Zend_Registry::get(TUSERPAGE));
				$this->view->groups = UserBusiness::loadGroup();
			}
			else
			{
				$entity 			= UserLogged::getEntityId();
				$where[AUTH_ID_ENTITY_USER.' = ?'] = $entity;
				$where[AUTH_ID_ROLE_USER.' > ?'] = UserLogged::getUserRoleId();
				$total				= UserBusiness::count(TBL_AUTH_USER, AUTH_ID_ENTITY_USER, $where);
				$this->view->users 	= UserBusiness::loadUsersByEntity($entity, UserLogged::getUserRoleId(), $start, Zend_Registry::get(TUSERPAGE));
				$groupEntity 		= UserBusiness::loadGroupByIdEntity($entity);
				foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
				$this->view->groups = $group;
			}
			
			$this->navBar($page, $total, Zend_Registry::get(TUSERPAGE));
			
			//retorna para o template atual exibindo as mensagens de validação
			return;
		}
		
		$page 	= 1;
		if($this->getRequest()->isPost() && !$this->getRequest()->getPost(UserForm::filter()))
			$page = $this->getRequest()->getPost(UserForm::page());

		$start 					= BasicController::pageToStart($page, Zend_Registry::get(TUSERPAGE));		
		
		$admin = UserLogged::isAdministrator();
		if($admin === TRUE)
		{
			$total				= UserBusiness::count(TBL_AUTH_USER, AUTH_ID_USER);
			$this->view->users 	= UserBusiness::loadAllUsers($start, Zend_Registry::get(TUSERPAGE));
			$this->view->groups = UserBusiness::loadGroup();
		}
		else
		{
			$entity 			= UserLogged::getEntityId();
			$where[AUTH_ID_ENTITY_USER.' = ?'] = $entity;
			$where[AUTH_ID_ROLE_USER.' > ?'] = UserLogged::getUserRoleId();
			$total				= UserBusiness::count(TBL_AUTH_USER, AUTH_ID_ENTITY_USER, $where);
			$this->view->users 	= UserBusiness::loadUsersByEntity($entity, UserLogged::getUserRoleId(), $start, Zend_Registry::get(TUSERPAGE));
			$groupEntity 		= UserBusiness::loadGroupByIdEntity($entity);
			foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
			$this->view->groups = $group;
		}	
		
		$users = UserForm::assembleFormToUser($this->view->form);
		UserBusiness::disableUser($users);
		
		$this->navBar($page, $total, Zend_Registry::get(TUSERPAGE));
		
		$this->_redirect(USER_CONTROLLER);
	}
	
	/**
	 * Habilita um usuário
	 */
	function enableAction()
	{		
		//valida as informações inseridas no form pelo usuário  
		$errorMessages = UserValidator::validateUserId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;
			
			$page 	= 1;
			if($this->getRequest()->isPost() && !$this->getRequest()->getPost(UserForm::filter()))
				$page = $this->getRequest()->getPost(UserForm::page());
	
			$start 					= BasicController::pageToStart($page, Zend_Registry::get(TUSERPAGE));			
			
			$admin = UserLogged::isAdministrator();
			if($admin === TRUE)
			{
				$total				= UserBusiness::count(TBL_AUTH_USER, AUTH_ID_USER);
				$this->view->users 	= UserBusiness::loadAllUsers($start, Zend_Registry::get(TUSERPAGE));
				$this->view->groups = UserBusiness::loadGroup();
			}
			else
			{
				$entity 			= UserLogged::getEntityId();
				$where[AUTH_ID_ENTITY_USER.' = ?'] = $entity;
				$where[AUTH_ID_ROLE_USER.' > ?'] = UserLogged::getUserRoleId();
				$total				= UserBusiness::count(TBL_AUTH_USER, AUTH_ID_ENTITY_USER, $where);
				$this->view->users 	= UserBusiness::loadUsersByEntity($entity, UserLogged::getUserRoleId(), $start, Zend_Registry::get(TUSERPAGE));
				$groupEntity 		= UserBusiness::loadGroupByIdEntity($entity);
				foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
				$this->view->groups = $group;
			}
			
			$this->navBar($page, $total, Zend_Registry::get(TUSERPAGE));
			
			//retorna para o template atual exibindo as mensagens de validação
			return;
		}
		
		$page 	= 1;
		if($this->getRequest()->isPost() && !$this->getRequest()->getPost(UserForm::filter()))
			$page = $this->getRequest()->getPost(UserForm::page());

		$start 					= BasicController::pageToStart($page, Zend_Registry::get(TUSERPAGE));		
		
		$admin = UserLogged::isAdministrator();
		if($admin === TRUE)
		{
			$total				= UserBusiness::count(TBL_AUTH_USER, AUTH_ID_USER);
			$this->view->users 	= UserBusiness::loadAllUsers($start, Zend_Registry::get(TUSERPAGE));
			$this->view->groups = UserBusiness::loadGroup();
		}
		else
		{
			$entity 			= UserLogged::getEntityId();
			$where[AUTH_ID_ENTITY_USER.' = ?'] = $entity;
			$where[AUTH_ID_ROLE_USER.' > ?'] = UserLogged::getUserRoleId();
			$total				= UserBusiness::count(TBL_AUTH_USER, AUTH_ID_ENTITY_USER, $where);
			$this->view->users 	= UserBusiness::loadUsersByEntity($entity, UserLogged::getUserRoleId(), $start, Zend_Registry::get(TUSERPAGE));
			$groupEntity 		= UserBusiness::loadGroupByIdEntity($entity);
			foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
			$this->view->groups = $group;
		}	
		
		$users = UserForm::assembleFormToUser($this->view->form);
		UserBusiness::enableUser($users);
		
		$this->navBar($page, $total, Zend_Registry::get(TUSERPAGE));
		
		$this->_redirect(USER_CONTROLLER);
	}
	
	/**
	 * Lista os grupos de determinada entidade
	 */
	function entityAction()
	{
		$errorMessages = UserValidator::validateEntityId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$admin = UserLogged::isAdministrator();
			if($admin === FALSE)
			{
				try{
					$userId = UserLogged::getUserId();
				}catch(UserNotLoggedException $e){
					Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$this->view->labels->notPermission.' - '.$e);
					trigger_error($this->view->labels->notPermission , E_USER_ERROR);
				}
				$this->view->idEntity 	= UserLogged::getEntityId();
				$this->view->idUser 	= $userId;
				$groupEntity 			= UserBusiness::loadGroupByIdEntity($this->view->idEntity);
				foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
				$this->view->groups = $group;
			}
			else
			{			
				$groupEntity 			= UserBusiness::loadGroupByIdEntity($this->view->form->getidEntity());
				foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
				$this->view->groups = $group; 
			}
			return;
		}
		$groupEntity 		= UserBusiness::loadGroupByIdEntity($this->view->form->getIdEntity());
		
		foreach($groupEntity as $ge)
		{	
			$group = $ge->findParentRow(CLS_AUTH_GROUP);
			if($group->{AUTH_STATUS} != Constants::DISABLE)
			{
				$resource = $group->findDependentRowset(CLS_AUTH_GROUP_RESOURCE)->current();
				if($this->view->form->getIdRole() == $resource->{AGR_ROLE_ID})
				{				
					$arrGroup[] = $group;
				}
			}			
		}
		$this->view->groups = $arrGroup;
	}
	
	/**
	 * Lista os grupos de acordo com o tipo de usuario
	 */
	function roleAction()
	{		
		$errorMessages = UserValidator::validateRoleId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$admin = UserLogged::isAdministrator();
			if($admin === FALSE)
			{
				try{
					$userId = UserLogged::getUserId();
				}catch(UserNotLoggedException $e){
					Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$this->view->labels->notPermission.' - '.$e);
					trigger_error($this->view->labels->notPermission , E_USER_ERROR);
				}
				$this->view->idEntity 	= UserLogged::getEntityId();
				$this->view->idUser 	= $userId;
				$this->view->groups = UserBusiness::loadGroup();				
			}
			else
			{			
				$groupEntity 			= UserBusiness::loadGroupByIdEntity($this->view->form->getidEntity());
				foreach($groupEntity as $ge) $group[] = $ge->findParentRow(CLS_AUTH_GROUP);
				$this->view->groups = $group; 
			}
			return;
		}		
		
		$admin = UserLogged::isAdministrator();
		if($admin === FALSE)
		{
			$this->view->idEntity 	= UserLogged::getEntityId();
			$resGroup = UserBusiness::loadGroupByIdEntity(UserLogged::getEntityId());			
			
			foreach($resGroup as $ge)
			{	
				$group = $ge->findParentRow(CLS_AUTH_GROUP);
				if($group->{AUTH_STATUS} != Constants::DISABLE)
				{
					$resource = $group->findDependentRowset(CLS_AUTH_GROUP_RESOURCE)->current();
					if($this->view->form->getIdRole() == $resource->{AGR_ROLE_ID})
					{				
						$arrGroup[] = $group;
					}
				}			
			}
			$this->view->groups = $arrGroup;
		}
		else
		{
			if($this->view->form->getIdEntity())
			{
				$resGroup = UserBusiness::loadGroupByIdEntity($this->view->form->getIdEntity());
				foreach($resGroup as $ge)
				{	
					$group = $ge->findParentRow(CLS_AUTH_GROUP);
					
					if($group->{AUTH_STATUS} != Constants::DISABLE)
					{
						$resource = $group->findDependentRowset(CLS_AUTH_GROUP_RESOURCE)->current();
						if($this->view->form->getIdRole() == $resource->{AGR_ROLE_ID})
						{				
							$arrGroup[] = $group;
						}
					}			
				}
			}
			$this->view->groups = $arrGroup;
		}
	}
	
	function successAction()
	{
		;
	}
}
