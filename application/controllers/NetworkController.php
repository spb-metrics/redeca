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

class NetworkController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Network');
		parent::setControllerHelp('Network');	
		
		Zend_Loader::loadClass(CLS_CATEGORY);
		Zend_Loader::loadClass(CLS_PROGRAM);
		Zend_Loader::loadClass(CLS_ACTIVITYDETAIL);
		Zend_Loader::loadClass('NetworkForm');
		Zend_Loader::loadClass('NetworkValidator');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('EntityBusiness');
		Zend_Loader::loadClass('NetworkBusiness');
		Zend_Loader::loadClass('ActivityBusiness');
		
		$frm = new NetworkForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
		
		$this->view->activity = ActivityBusiness::loadActivities();
	}
	
	/**
	 * Redireciona para a visualização padrão
	 * 
	 */
	function indexAction()
	{
		$this->_redirect(NETWORK_CONTROLLER."/".DEFAULT_VIEWENTITY_ACTION);
	}
	
	/**
	 * Popula o container
	 */
	function containerAction()
	{
		$this->view->entity = EntityBusiness::loadAll();
	}
	
	/**
	 * Visualiza as entidades da rede
	 */
	function viewEntityAction()
	{		
		$errorMessages = NetworkValidator::validateEntity($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$page 	= 1;
		if($this->getRequest()->isPost() && !$this->getRequest()->getPost(NetworkForm::filter()))
			$page = $this->getRequest()->getPost(NetworkForm::page());

		$start	= BasicController::pageToStart($page, Zend_Registry::get(TPAGE));		
		
		if($this->view->form->getEntity())
		{
			$resEntity = EntityBusiness::loadByName($this->view->form->getEntity(), $start, Zend_Registry::get(TPAGE));
			$where[ENT_NAME.' LIKE ?'] = '%'.$this->view->form->getEntity().'%';			
		}
		else
		{
			$resEntity = EntityBusiness::loadByName(NULL, $start, Zend_Registry::get(TPAGE));
			$where = null;
		}
		
		$this->view->entity = $resEntity;
		
		$this->view->coordinator = Zend_Registry::get(CONFIG)->user->role->coordinator;
		
		
		$total	= EntityBusiness::count(TBL_ENTITY, ENT_ID_ENTITY, $where);
		
		$this->navBar($page, $total, Zend_Registry::get(TPAGE));
	}
	
	/**
	 * Visualiza as atividades da rede
	 */
	function viewActivityAction()
	{
		$errorMessages = NetworkValidator::validateIdActivity($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$page 	= 1;
		if($this->getRequest()->isPost() && !$this->getRequest()->getPost(NetworkForm::filter()))
			$page = $this->getRequest()->getPost(NetworkForm::page());

		$start	= BasicController::pageToStart($page, Zend_Registry::get(TPAGE));
		
		if($this->view->form->getIdActivity())
			$resActivity = NetworkBusiness::loadAllActivitiesEntity($this->view->form->getIdActivity(), $start, Zend_Registry::get(TPAGE));
		else
			$resActivity = NetworkBusiness::loadAllActivitiesEntity(NULL, $start, Zend_Registry::get(TPAGE));
		
		if($this->view->form->getIdActivity())
			$where[ACD_ID_CATEGORY.' = ?'] = $this->view->form->getIdActivity();
		else
			$where = null;
					
		$total = NetworkBusiness::count(TBL_ACTIVITY_DETAIL, ACD_ID_ACTIVITY_DETAIL, $where);
		
		$this->view->resActivity = $resActivity;
		
		$this->navBar($page, $total, Zend_Registry::get(TPAGE));
	}
}
