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
 * Lucas dos Santos Borges Corrêa  - W3S		    05/05/2008	                       Create file 
 * 
 */

require_once('BasicController.php');


class ActivityController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		Zend_Loader::loadClass('Category');
		Zend_Loader::loadClass('ActivityBusiness');
		Zend_Loader::loadClass('ActivityDetailBusiness');
		Zend_Loader::loadClass('ActivityDetail');
		Zend_Loader::loadClass('ActivityForm');
		Zend_Loader::loadClass('ActivityValidator');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('Constants');
		Zend_Loader::loadClass('ProgramBusiness');

		$frm				= new ActivityForm();
		$frm->assembleRequest($this->_request);
		$this->view->form	= $frm;
		
		parent::setControllerResources('Activity');
		parent::setControllerHelp('Activity');
		/* Seta flag que indica qual painel(Tab) exibir (Default - cadastro)*/
		$this->view->panel = ActivityForm::CADASTRE_FLAG;
	}
	
	/**
	 * Redireciona para a funcionalidade padrão 
	 */
	function indexAction()
	{	
		$this->_redirect(ACTIVITY_CONTROLLER.'/'.DEFAULT_NEW_ACTION);
	}
	
	/**
	 * Exibe formulário para edição de atividades
	 */
	function viewAction()
	{		
		$errorMessages = ActivityValidator::validateActivityId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$this->view->macroActivities = ActivityBusiness::loadMacroActivities();
			$this->view->activities = ActivityBusiness::loadActivities();
			$this->view->panel = ActivityForm::CADASTRE_FLAG;
			$this->view->isAdministrator = UserLogged::isAdministrator();
			return;
		}
		$this->view->form = $this->assembleActivityToForm(ActivityBusiness::load($this->view->form->getIdActivity()));
		$this->view->macroActivities 	= ActivityBusiness::loadMacroActivities();
		$this->view->activities 		= ActivityBusiness::loadActivities();
		
		$this->view->isAdministrator = UserLogged::isAdministrator();
	}
	
	/**
	 * Salva as informações da atividade (edição)
	 */
	function editAction()
	{	
		/**
		 * valida macro-atividade somente se foi selecionada uma atividade
		 * ActivityType = 0 - Cadastro de Macro-atividade
		 * ActivityType = 1 - Cadastro de atividade
		 */		
		if($this->view->form->getActivityType() > 0)
		{
			$errorMessages 			= ActivityValidator::validateMacroActivityData($this->view->form);
		}
		else
		{
			/* Seta pra null o Id da macro atividade para garantir a validação */
			$this->view->form->setIdMacroActivity(null);
			$errorMessages 			= ActivityValidator::validateActivityDataEdit($this->view->form);
		}

		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;

			$this->view->macroActivities = ActivityBusiness::loadMacroActivities();
			$this->view->activities = ActivityBusiness::loadActivities();
			return;
		}
		$category = $this->assembleFormToActivity($this->view->form);

		ActivityBusiness::save($category);
		$this->_redirect(ACTIVITY_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);		
	}
	
	/**
	 * Exibe formulário para criação de nova atividade
	 */
	function newAction()
	{	
		$this->view->macroActivities = ActivityBusiness::loadMacroActivities();
		$this->view->activities = ActivityBusiness::loadActivities();
		 
		$this->view->isAdministrator = UserLogged::isAdministrator();
	}
	
	/**
	 * Salva as informações da atividade (criação)
	 */
	function addAction()
	{
		/**
		 * valida macro-atividade somente se foi selecionada uma atividade
		 * ActivityType = 0 - Cadastro de Macro-atividade
		 * ActivityType = 1 - Cadastro de atividade
		 */		
		if($this->view->form->getActivityType() <= 0)
		{
			/* Seta pra null o Id da macro atividade para garantir a validação */			
			$this->view->form->setIdMacroActivity(null);
			$errorMessages 			= ActivityValidator::validateActivityAdd($this->view->form);
		}
		else
		{
			$errorMessages 			= ActivityValidator::validateActivityData($this->view->form);
		}

		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;

			$this->view->macroActivities = ActivityBusiness::loadMacroActivities();
			$this->view->activities = ActivityBusiness::loadActivities();
			return;
		}
		$category = $this->assembleFormToActivity($this->view->form);

		ActivityBusiness::save($category);
		
		$this->_redirect(ACTIVITY_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Exibe a tela de confirmação de exclusão de uma atividade
	 */
	function confirmAction()
	{
		$this->view->action 	= DEFAULT_DROP_ACTION;
		
		// Verifica se o "id" informado pelo usuário é válido
		$errorMessages 	= ActivityValidator::validateActivityId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			
			$this->view->macroActivities = ActivityBusiness::loadMacroActivities();
			$this->view->activities = ActivityBusiness::loadActivities();
			$this->_redirect(ACTIVITY_CONTROLLER.'/'.DEFAULT_DROP_ACTION);
			return;
		}
		$this->view->macroActivities = ActivityBusiness::load($this->view->form->getIdActivity());
	}

	/**
	 * Exclui uma Atividade
	 */
	function dropAction()
	{
		$errorMessages 	= ActivityValidator::validateActivityId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			
			$this->view->macroActivities = ActivityBusiness::loadMacroActivities();
			$this->view->activities = ActivityBusiness::loadActivities();
			
			return;
		}
		$category = $this->assembleFormToActivity($this->view->form);
		
		$message = ActivityBusiness::delete($category[CAT_ID_CATEGORY]);
		if($message != null)
		{
			$this->view->businessMessage = $message;
			
			$this->view->macroActivities = ActivityBusiness::loadMacroActivities();
			$this->view->activities = ActivityBusiness::loadActivities();
			
			return;
		}

		$this->_redirect(ACTIVITY_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	/**
	 * Exibe as informações de atividades para associação/Desassociação
	 */
	function activityAction()
	{
		/* Seta flag que indica qual painel(Tab) exibir*/
		$this->view->panel = ActivityForm::ASSOCIATION_FLAG;
		$this->view->macroActivities = ActivityBusiness::loadMacroActivities();
		$this->view->activities = ActivityBusiness::loadActivities();
	}
	
	/**
	 * Salva Associação/Desassociation da atividade
	 */
	function associationAction()
	{
		/* Seta flag que indica qual painel(Tab) exibir */
		$this->view->panel = ActivityForm::ASSOCIATION_FLAG;

		$errorMessages = ActivityValidator::validateActivityId($this->view->form);
		ActivityValidator::validateIdMacroActivity($this->view->form, $errorMessages);
				
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$this->view->macroActivities = ActivityBusiness::loadMacroActivities();
			$this->view->activities = ActivityBusiness::loadActivities();
			
			return;
		}
		$activity = $this->assembleFormToActivity($this->view->form);
		ActivityBusiness::associate($activity);
		$this->view->macroActivities 	= ActivityBusiness::loadMacroActivities();
		$this->view->activities 		= ActivityBusiness::loadActivities();
	}
	
	/**
	 * Encerra um atividade
	 */
	function closeAction()
	{
		$this->_redirect(ACTIVITY_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	/**
	 * Popula um array com os dados do Form
	 */
	private function assembleFormToActivity(ActivityForm $frm)
	{
		if(!empty($frm))
		{
			$activity = array();
			if( Utils::isEmpty($frm->getIdActivity()) )
				$activity[CAT_ID_CATEGORY] = null;
			else
				$activity[CAT_ID_CATEGORY] = $frm->getIdActivity();

			if( Utils::isEmpty($frm->getActivityName()) )
				$activity[CAT_CATEGORY] = null;
			else
				$activity[CAT_CATEGORY] = $frm->getActivityName();

			if( Utils::isEmpty($frm->getIdMacroActivity()) )
				$activity[CAT_ID_CATEGORY_FATHER] = null;
			else
				$activity[CAT_ID_CATEGORY_FATHER] = $frm->getIdMacroActivity();

			$activity[CAT_STATUS] = $frm->getStatus();

			return $activity;
		}
		return null;
	}
	/**
	 * Popula o Form com os dados do objeto vindos do banco de dados
	 */
	private function assembleActivityToForm($activity)
	{
		if(!empty($activity))
		{
			/* Se retornar um array de Atividades, somente a primeira posição popula o Form*/
			foreach($activity as $act)
			{
				$activity = $act;
				break;
			}

			$frm = new ActivityForm();
			$frm->setIdActivity($activity->{CAT_ID_CATEGORY});
			$frm->setActivityName($activity->{CAT_CATEGORY});
			$frm->setIdMacroActivity($activity->{CAT_ID_CATEGORY_FATHER});
			$frm->setStatus($activity->{CAT_STATUS});
			if(Utils::isEmpty($activity->{CAT_ID_CATEGORY_FATHER}))
			{
				$frm->setActivityType(Constants::ZERO);
			}
			else
			{
				$frm->setActivityType(Constants::ONE);
			}
			return $frm;
		}
		return null;
	}
			
	function successAction()
	{
		;
	}
}