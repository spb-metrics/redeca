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
 * Lucas dos Santos Borges Corrêa  - W3S		   	05/05/2008	                       Create file 
 * 
 */
 
require_once('BasicController.php');

class GroupController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		
		Zend_Loader::loadClass(CLS_AUTH_GROUP);
		Zend_Loader::loadClass('GroupForm');
		Zend_Loader::loadClass('GroupValidator');
		Zend_Loader::loadClass('GroupBusiness');
		
		$frm = new GroupForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
		
		$this->view->groups = GroupBusiness::loadAll();
		$this->view->roles 	= GroupBusiness::loadRoleByQuery($this->assembleRoleQuery());
		
		parent::setControllerResources(CLS_AUTH_GROUP);
		parent::setControllerHelp(CLS_AUTH_GROUP);
	}
	
	/**
	 * Exibe formulário e lista todos os grupos cadastrados
	 */
	function indexAction()
	{
		;
	}
	
	/**
	 * Salva um novo grupo
	 */
	function addAction()
	{
		$form = $this->view->form;
		if($form->getId())
			$errorMessages = GroupValidator::validateGroup($form);
		else
			$errorMessages = GroupValidator::validateGroupAdd($form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$group = GroupForm::assembleFormToGroup($form);
		try
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			
			$id = GroupBusiness::save($group, $db);
			GroupBusiness::dropGroupResource($id, $db);
			GroupBusiness::saveDefaultGroupResource($id, $form->getRoleId(), $db);
			$db->commit();
			$db->closeConnection();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			$resources = Zend_Registry::get(LABEL_RESOURCES);
			trigger_error($resources->group->save->fail, E_USER_ERROR);
		}
		
		$this->_redirect(GROUP_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Exibe a tela de confirmação de exclusão de um grupo
	 * 
	 */
	function confirmAction()
	{
		$errorMessages = GroupValidator::validateGroupId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$result = GroupBusiness::load($this->view->form->getId());
		$this->view->result = $result;
	}	

	/**
	 * Exclui um grupo cadastrado
	 */
	function dropAction()
	{
		$errorMessages 	= GroupValidator::validateGroupId($this->view->form);

		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages 	= $errorMessages;
			return;
		}
		GroupBusiness::drop($this->view->form->getId());
		$this->_redirect(GROUP_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Salva um grupo editado
	 */
	public function editAction()
	{
		$errorMessages 	= GroupValidator::validateGroupId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$group 						= GroupBusiness::load($this->view->form->getId())->current();
		$role 						= GroupBusiness::getGroupRole($group);
		
		GroupForm::assembleGroupToForm($group);
		$this->view->form->setRoleId($role->{AUTH_ID_ROLE});
	}
	
	function successAction()
	{
		;
	}
	
	function viewAction()
	{
		;
	}
	
	/**
	 * Exibe a tela de gerenciamento de permissões de um grupo;
	 * 
	 */
	function groupPermissionAction()
	{
		$errorMessages 	= GroupValidator::validateGroupId($this->view->form);
		$config 		= Zend_Registry::get(CONFIG);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$this->render('index');
			return;
		}
		//lista de resources aplicáveis ao grupo
		$group 								= GroupBusiness::load($this->view->form->getId())->current();
		if(!empty($group))
		{
			$role 								= GroupBusiness::getGroupRole($group);
			$this->view->role					= $role;
			$this->view->groupResources 		= $group->findDependentRowset(CLS_AUTH_GROUP_RESOURCE);
			$this->view->resources				= $role->findDependentRowset(CLS_AUTH_ROLE_RESOURCE);
			$this->view->attendanceIdResource 	= $config->assistance->resourceId;
			$this->view->resourcesFixed			= $config->permission->resource->default;
			$this->view->notOnlyRead			= $config->notonlyread->resourceId;
			GroupForm::assembleGroupToForm($group);
		}
	}
	
	/**
	 * Adiciona uma permissão à um grupo;
	 * 
	 */
	function addPermissionAction()
	{
		$errorMessages = null;
		GroupValidator::validateGroupPermission($this->view->form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$errorMessagesId = null;
			GroupValidator::validateGroupId($this->view->form, $errorMessagesId);
			$config 		= Zend_Registry::get(CONFIG);
			if(sizeof($errorMessagesId) > 0)
			{
				$this->view->errorMessages = $errorMessagesId;
				$this->render('group-permission');
				return;
			}
			//lista de resources aplicáveis ao grupo
			$group 								= GroupBusiness::load($this->view->form->getId())->current();
			$role 								= GroupBusiness::getGroupRole($group);
			$this->view->role					= $role;
			$this->view->groupResources 		= $group->findDependentRowset(CLS_AUTH_GROUP_RESOURCE);
			$this->view->resources				= $role->findDependentRowset(CLS_AUTH_ROLE_RESOURCE);
			$this->view->attendanceIdResource 	= $config->assistance->resourceId;
			$this->view->resourcesFixed			= $config->permission->resource->default;
			$this->view->notOnlyRead			= $config->notonlyread->resourceId;
			GroupForm::assembleGroupToForm($group);

			$this->view->errorMessages = $errorMessages;
			$this->render('group-permission');
			return;
		}
		
		$data 			= GroupForm::assembleFormToGroupResource($this->view->form);
		GroupBusiness::saveGroupResourcePermission($data);
		$this->_redirect(GROUP_CONTROLLER."/".DEFAULT_GROUP_PERMISSION_ACTION."/".GroupForm::id()."/".$this->view->form->getId());
	}
	
	/**
	 * Remove uma permissão de um grupo;
	 * 
	 */
	function dropPermissionAction()
	{
		$errorMessages = null;
		GroupValidator::validateDropGroupPermission($this->view->form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$errorMessagesId = null;
			GroupValidator::validateGroupId($this->view->form, $errorMessagesId);
			$config 		= Zend_Registry::get(CONFIG);
			if(sizeof($errorMessagesId) > 0)
			{
				$this->view->errorMessages = $errorMessagesId;
				$this->render('group-permission');
				return;
			}
			//lista de resources aplicáveis ao grupo
			$group 								= GroupBusiness::load($this->view->form->getId())->current();
			$role 								= GroupBusiness::getGroupRole($group);
			$this->view->role					= $role;
			$this->view->groupResources 		= $group->findDependentRowset(CLS_AUTH_GROUP_RESOURCE);
			$this->view->resources				= $role->findDependentRowset(CLS_AUTH_ROLE_RESOURCE);
			$this->view->attendanceIdResource 	= $config->assistance->resourceId;
			$this->view->resourcesFixed			= $config->permission->resource->default;
			$this->view->notOnlyRead			= $config->notonlyread->resourceId;
			GroupForm::assembleGroupToForm($group);

			$this->view->errorMessages = $errorMessages;
			$this->render('group-permission');
			return;
		}

		GroupBusiness::dropGroupResourcePermission($this->view->form->getId(), $this->view->form->getResourceId());
		$this->_redirect(GROUP_CONTROLLER."/".DEFAULT_GROUP_PERMISSION_ACTION."/".GroupForm::id()."/".$this->view->form->getId());
		;
	}
	
	/**
	 * Monta uma query de busca por usuários do tipo
	 * Operador e Técnico somente.
	 */
	public function assembleRoleQuery()
	{
		Zend_Loader::loadClass(CLS_AUTH_ROLE);
		$config = Zend_Registry::get(CONFIG);
		if(!empty($config))
		{
			$role[]=$config->user->role->operator;
			$role[]=$config->user->role->technician;
			$data[AUTH_ID_ROLE.' in (?)'] = $role;
		}
		
		return $data;
	}
}